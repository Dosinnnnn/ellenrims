<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use PDF;
use Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::orderBy('id', 'DESC')->paginate(10);
        return view('backend.order.index')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'first_name' => 'string|required',
        //     'last_name' => 'string|required',
        //     'address1' => 'string|required',
        //     'address2' => 'string|nullable',
        //     'coupon' => 'nullable|numeric',
        //     'phone' => 'numeric|required',
        //     'post_code' => 'string|nullable',
        //     'email' => 'string|required'
        // ]);
        // return $request->all();

        if (empty(Cart::where('user_id', auth()->user()->id)->where('order_id', null)->first())) {
            request()->session()->flash('error', 'Cart is Empty !');
            return back();
        }
        // $cart=Cart::get();
        // // return $cart;
        // $cart_index='ORD-'.strtoupper(uniqid());
        // $sub_total=0;
        // foreach($cart as $cart_item){
        //     $sub_total+=$cart_item['amount'];
        //     $data=array(
        //         'cart_id'=>$cart_index,
        //         'user_id'=>$request->user()->id,
        //         'product_id'=>$cart_item['id'],
        //         'quantity'=>$cart_item['quantity'],
        //         'amount'=>$cart_item['amount'],
        //         'status'=>'new',
        //         'price'=>$cart_item['price'],
        //     );

        //     $cart=new Cart();
        //     $cart->fill($data);
        //     $cart->save();
        // }

        // $total_prod=0;
        // if(session('cart')){
        //         foreach(session('cart') as $cart_items){
        //             $total_prod+=$cart_items['quantity'];
        //         }
        // }
        $order_number = 'ORD-' . strtoupper(Str::random(10));
        $user_id = $request->user()->id;
        $shipping_id = $request->shipping;
        $ongkir = explode('|', $request->jenis);
        $shipping = $ongkir[1];
        // return session('coupon')['value'];
        $sub_total = Helper::totalCartPrice();
        $quantity = Helper::cartCount();
        if (session('coupon')) {
            $coupon = session('coupon')['value'];
        } else {
            $coupon = 0;
        }
        if ($shipping) {
            if (session('coupon')) {
                $total_amount = Helper::totalCartPrice() + $shipping - session('coupon')['value'];
            } else {
                $total_amount = Helper::totalCartPrice() + $shipping;
            }
        } else {
            if (session('coupon')) {
                $total_amount = Helper::totalCartPrice() - session('coupon')['value'];
            } else {
                $total_amount = Helper::totalCartPrice();
            }
        }
        // return $order_data['total_amount'];
        $status = "new";
        if (request('payment_method') == 'paypal') {
            $payment_method = 'paypal';
            $payment_status = 'paid';
        } else {
            $payment_method = 'cod';
            $payment_status = 'Unpaid';
        }
        $sendtomidtrans = $this->midtrans($order_number, $total_amount, $request->first_name, $request->email, $request->phone);
        $decodemidtrans = json_decode($sendtomidtrans);
        // dd($sendtomidtrans);
        $data = [
            'order_number' => $order_number,
            'user_id' => $user_id,
            'sub_total' => $sub_total,
            'shipping' => $shipping,
            'coupun' => $coupon,
            'total_amount' => $total_amount,
            'quantity' => $quantity,
            'payment_status' => $payment_status,
            'first_name' => $request->first_name,
            'last_name' => $request->first_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => 'indonesia',
            'post_code' => $request->post_code,
            'address1' => $request->alamat,
            'address2' => $request->province . ' ' . $request->city,
        ];

        $create = Order::create($data);
        if ($create)
            // dd($order->id);
            $users = User::where('role', 'admin')->first();
        // $details = [
        //     'title' => 'New order created',
        //     'actionURL' => route('order.show', $create->id),
        //     'fas' => 'fa-file-alt'
        // ];
        // Notification::send($users, new StatusNotification($details));
        // if (request('payment_method') == 'paypal') {
        //     return redirect()->route('payment')->with(['id' => $order->id]);
        // } else {
        session()->forget('cart');
        session()->forget('coupon');
        // }
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $create->id]);


        return Redirect::to($decodemidtrans->redirect_url);
    }

    private function request($url, $head, $data = null)
    {
        $client = new Client();

        $options = [
            'headers' => $head,
            'body' => $data,
        ];

        $response = $client->post($url, $options);
        $result = $response->getBody()->getContents();

        return $result;
    }

    public function midtrans($id, $total_amount, $firstname, $email, $phone)
    {
        // $secretkey = 'Mid-server-j1N9cH_nT5oz0uRvMnMiUqSX'; //production
        $secretkey = 'SB-Mid-server-GqhSfX7oEQzBdRkuev2zKszD'; //sandbox

        $AUTH_STRING = base64_encode($secretkey);

        $url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        // $url = 'https://app.midtrans.com/snap/v1/transactions';
        $head = [
            'Content-Type' => 'application/json',
            'authorization' => 'Basic ' . $AUTH_STRING,
            'accept' => 'application/json',
        ];

        $data =  [
            'transaction_details' => [
                'order_id' => $id,
                'gross_amount' => $total_amount,
            ],
            'customer_details' => [
                'first_name' => $firstname,
                'email' => $email,
                'phone' => $phone
            ]
        ];

        $data_string = json_encode($data);

        $exec = $this->request($url, $head, $data_string);
        return $exec;
        return redirect()->route('order.track');
    }

    public function callbackmidtrans()
    {
        $secretkey = 'SB-Mid-server-GqhSfX7oEQzBdRkuev2zKszD'; //sandbox
        $order_id = request('order_id');
        $transaction_status = request('transaction_status');
        $status_code = request('status_code');
        if ($status_code === '200' && $transaction_status === 'settlement') {
            Order::where('order_number', $order_id)
                ->update(['payment_status' => 'paid']);
        } else {
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        // return $order;
        return view('backend.order.show')->with('order', $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('backend.order.edit')->with('order', $order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $this->validate($request, [
            'status' => 'required|in:new,process,delivered,cancel'
        ]);
        $data = $request->all();
        // return $request->status;
        if ($request->status == 'delivered') {
            foreach ($order->cart as $cart) {
                $product = $cart->product;
                // return $product;
                $product->stock -= $cart->quantity;
                $product->save();
            }
        }
        $status = $order->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Successfully updated order');
        } else {
            request()->session()->flash('error', 'Error while updating order');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $status = $order->delete();
            if ($status) {
                request()->session()->flash('success', 'Order Successfully deleted');
            } else {
                request()->session()->flash('error', 'Order can not deleted');
            }
            return redirect()->route('order.index');
        } else {
            request()->session()->flash('error', 'Order can not found');
            return redirect()->back();
        }
    }

    public function orderTrack($id)
    {
        $order = Order::find($id);
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request)
    {
        // return $request->all();
        $order = Order::where('user_id', auth()->user()->id)->where('order_number', $request->order_number)->first();
        if ($order) {
            if ($order->status == "new") {
                request()->session()->flash('success', 'Your order has been placed. please wait.');
                return redirect()->route('home');
            } elseif ($order->status == "process") {
                request()->session()->flash('success', 'Your order is under processing please wait.');
                return redirect()->route('home');
            } elseif ($order->status == "delivered") {
                request()->session()->flash('success', 'Your order is successfully delivered.');
                return redirect()->route('home');
            } else {
                request()->session()->flash('error', 'Your order canceled. please try again');
                return redirect()->route('home');
            }
        } else {
            request()->session()->flash('error', 'Invalid order numer please try again');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request)
    {
        $order = Order::getAllOrder($request->id);
        // return $order;
        $file_name = $order->order_number . '-' . $order->first_name . '.pdf';
        // return $file_name;
        $pdf = PDF::loadview('backend.order.pdf', compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request)
    {
        $year = \Carbon\Carbon::now()->year;
        // dd($year);
        $items = Order::with(['cart_info'])->whereYear('created_at', $year)->where('status', 'delivered')->get()
            ->groupBy(function ($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        // dd($items);
        $result = [];
        foreach ($items as $month => $item_collections) {
            foreach ($item_collections as $item) {
                $amount = $item->cart_info->sum('amount');
                // dd($amount);
                $m = intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount : $result[$m] = $amount;
            }
        }
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $data[$monthName] = (!empty($result[$i])) ? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
}
