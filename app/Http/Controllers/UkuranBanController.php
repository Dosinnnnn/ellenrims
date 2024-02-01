<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UkuranBan;
class UkuranBanController extends Controller
{


public function index()
{
    $ukuranBan=UkuranBan::orderBy('id','DESC')->paginate();
    return view('backend.ukuran.index', compact('ukuranBan'));
}

public function create()
{
    $ukuranBan = UkuranBan::all();
    return view('backend.ukuran.create', compact('ukuranBan'));
}

public function edit($id)
{
    $ukuranBan = UkuranBan::find($id);

if (!$ukuranBan) {
    request()->session()->flash('kesalahan', 'Ukuran tidak ditemukan');
    return redirect()->route('ukuran.index'); // atau redirect ke halaman lain jika diinginkan
}

return view('backend.ukuran.edit')->with('ukuranBan', $ukuranBan);
}

public function update(Request $request, $id)
{
    $ukuranBan=UkuranBan::find($id);
        $this->validate($request,[
            'ukuran' => 'required|string|unique:ukuran_ban|max:255',
        ], [
            'ukuran.required' => 'Ukuran harus diisi.',
            'ukuran.string' => 'Ukuran harus berupa teks.',
            'ukuran.unique' => 'Ukuran sudah ada.',
            'ukuran.max' => 'Ukuran tidak boleh melebihi 255 karakter.',
        ]);
        $data=$request->all();
       
        $ukuranBan=$ukuranBan->fill($data)->save();
        if($ukuranBan){
            request()->session()->flash('sukses','Brand berhasil diperbarui');
        }
        else{
            request()->session()->flash('Kesalahan, Silakan coba lagi');
        }
        return redirect()->route('ukuran.index');
}

public function store(Request $request)
{
    // Validasi input
    $this->validate($request, [
        'ukuran' => 'required|string|unique:ukuran_ban|max:255',
    ], [
        'ukuran.required' => 'Ukuran harus diisi.',
        'ukuran.string' => 'Ukuran harus berupa teks.',
        'ukuran.unique' => 'Ukuran sudah ada.',
        'ukuran.max' => 'Ukuran tidak boleh melebihi 255 karakter.',
    ]);

    // Simpan ukuran ban ke database
    $ukuranBan = UkuranBan::create([
        'ukuran' => $request->ukuran,
    ]);

    if ($ukuranBan) {
        request()->session()->flash('success', 'Ukuran ban berhasil ditambahkan');
    } else {
        request()->session()->flash('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

    // Redirect atau tampilkan pesan sukses
    return redirect()->route('ukuran.index');
}


public function destroy($id)
{
    $ukuranBan = UkuranBan::findOrFail($id);
    $ukuranBan->delete();

    return redirect()->route('ukuran.index')->with('success', 'Ukuran ban berhasil dihapus');
}

// ...

}
