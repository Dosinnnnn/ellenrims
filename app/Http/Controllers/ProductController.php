<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\UkuranBan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::getAllProduct();
        // return $products;
        return view('backend.product.index')->with('products',$products);
        // $products = Product::with('ukuranBan')->getAllProduct();
        // return view('backend.product.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::get();
        $categories = Category::where('is_parent', 1)->get();
        $ukuranBan = UkuranBan::all();
        return view('backend.product.create', compact('categories', 'brands', 'ukuranBan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'stock' => 'required|numeric',
            'cat_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'is_featured' => 'sometimes|in:1',
            'status' => 'required|in:aktif,tidak aktif',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'ukuran_ban_id.*' => 'exists:ukuran_ban,id',
        ]);
    
        // Proses penyimpanan produk ke dalam database
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->input('is_featured', 0);
    
        // Simpan produk ke database
        $product = Product::create($data);
        $product->stock = $request->input('stock');
        $product->weight = $request->input('berat');
    
        // Simpan ukuran-ukuran yang dipilih jika ada
        $selectedUkuranBan = $request->input('ukuran_ban_id', []);
    
        // Attach ukuran ban ke produk menggunakan relasi many-to-many
        $product->ukuranBan()->sync($selectedUkuranBan);
    
        // Refresh relasi untuk mendapatkan data terbaru
        $product->load('ukuranBan');
    
        // Periksa apakah produk berhasil disimpan
        if ($product->wasRecentlyCreated) {
            request()->session()->flash('sukses', 'Produk Berhasil ditambahkan');
            return redirect()->route('product.index'); // Redirect jika sukses
        } else {
            request()->session()->flash('kesalahan', 'Silakan coba lagi!!');
            return redirect()->route('product.create')->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
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
        $product = Product::find($id);

    if (!$product) {
        abort(404); // Atau berikan respons yang sesuai untuk produk tidak ditemukan
    }

    return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
{
    $brand = Brand::get();
    $product = Product::findOrFail($id);

    if (!$product) {
        abort(404); // Atau sesuaikan dengan logika penanganan jika produk tidak ditemukan
    }

    $category = Category::where('is_parent', 1)->get();
    $ukuranBan = UkuranBan::all();
    $ukuranBanOptions = UkuranBan::all();
    $selectedUkuranBan = $product->ukuranBan->pluck('id')->toArray();

    return view('backend.product.edit')
        ->with('product', $product)
        ->with('brands', $brand)
        ->with('categories', $category)
        ->with('ukuranBan', $ukuranBan)
        ->with('ukuranBanOptions', $ukuranBanOptions)
        ->with('selectedUkuranBan', $selectedUkuranBan);
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
        // Ambil produk yang ingin diupdate
        $product = Product::findOrFail($id);
    
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'ukuran_ban.*' => 'exists:ukuran_ban,id',
            'weight' => 'nullable|numeric',
            'stock' => 'required|numeric',
            'cat_id' => 'nullable|exists:categories,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'is_featured' => 'sometimes|in:1',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:aktif,tidak aktif',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
        ]);

    
        // Lakukan konversi atau manipulasi sesuai kebutuhan, contoh:
        $stock = $request->input('stock');
        $parsedStock = $this->parseStockInput($stock);
    
        $slug = Str::slug($request->title);
        $count = Product::where('slug', $slug)->where('id', '!=', $product->id)->count();
    
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
    
        $data = $request->all();
        $data['slug'] = $slug;
        $data['is_featured'] = $request->input('is_featured', 0);
        $data['weight'] = $request->input('berat'); // Menambahkan berat ke data
    
        // Update stok
        $product->stock = $request->input('stock');
    
        // Update produk ke database
        $product->update($data);
    
        // Update ukuran-ukuran yang dipilih
        $selectedUkuranBan = $request->input('ukuran_ban', []);
        
        // Sync ukuran ban ke produk menggunakan relasi many-to-many
        $product->ukuranBan()->sync($selectedUkuranBan);
    
        if ($product) {
            request()->session()->flash('sukses', 'Produk Berhasil diperbarui');
            return redirect()->route('product.index'); // Redirect jika sukses
        } else {
            request()->session()->flash('kesalahan', 'Silakan coba lagi!!');
            return redirect()->route('product.edit', $product->id)->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }
    

// Tambahkan method untuk parsing input stok
protected function parseStockInput($stock)
{
    // Sesuaikan logika parsing stok sesuai kebutuhan
    return $stock;
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $status=$product->delete();
        
        if($status){
            request()->session()->flash('sukses','Produk berhasil dihapus');
        }
        else{
            request()->session()->flash('kesalahan','Kesalahan saat menghapus produk');
        }
        return redirect()->route('product.index');
    }
}
