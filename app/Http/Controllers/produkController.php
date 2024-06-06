<?php

namespace App\Http\Controllers;

use App\Models\produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class produkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 4;
        if(strlen($katakunci)){
            $data = produk::where('kode', 'like', "%$katakunci%")
                ->orWhere('nama', 'like', "%$katakunci%" )
                ->orWhere('jenis', 'like', "%$katakunci%" )
                ->paginate($jumlahbaris);
        }else{
            $data = produk::orderBy('kode', 'desc')->paginate($jumlahbaris);
        }
        return view('produk.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Session::flash('kode', $request->kode);
        Session::flash('nama', $request->nama);
        Session::flash('jenis', $request->jenis);

        $request->validate([
            'kode'=>'required|numeric|unique:produk,kode',
            'nama'=>'required',
            'jenis'=>'required',
        ],[
            'kode.required' =>'kode wajib diisi',
            'kode.numeric' =>'kode wajib berupa angka',
            'kode.unique' =>'kode yang diinput telah ada di database',
            'nama.required' =>'nama wajib diisi',
            'jenis.required' =>'jenis wajib diisi',
        
        ]);
        $data = [
            'kode' =>$request->kode,
            'nama' =>$request->nama,
            'jenis' =>$request->jenis,
        ];
        produk::create($data);
        return redirect()->to('produk')->with('yeay berhasil', 'Anda telah sukses menginput data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = produk::where('kode', $id)->first();
        return view('produk.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'=>'required',
            'jenis'=>'required',
        ],[
            'nama.required' =>'nama wajib diisi',
            'jenis.required' =>'jenis wajib diisi',
        
        ]);
        $data = [
            'nama' =>$request->nama,
            'jenis' =>$request->jenis,
        ];
        produk::where('kode', $id)->update($data);
        return redirect()->to('produk')->with('yeay berhasil', 'anda sukses melakukan update data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        produk::where('kode', $id)->delete();
        return redirect()->to('produk')->with('hore berhasil', 'sukses melakukan delete data');
    }
}
