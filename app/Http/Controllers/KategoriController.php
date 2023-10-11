<?php

namespace App\Http\Controllers;

use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return KategoriResource::collection($kategoris);
    }

    public function detail($id)
    {
        $kategoris = Kategori::find($id);

        if (!$kategoris) {
            return response()->json(['message' => 'Data Tidak Ada'], 404);
        }

        return new KategoriResource($kategoris);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori'
        ], [
            'nama_kategori.required' => 'Nama Kategori Wajib Diisi',
            'nama_kategori.unique' => 'Nama Kategori Sudah Ada'
        ]);

        $kategoris = Kategori::create($request->all());
        return response()->json([
            'status' => '200',
            'message' => 'Data berhasil disimpan',
            'data' => new KategoriResource($kategoris)
        ], 200);

    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ], [
            'nama_kategori.required' => 'Nama Kategori Wajib Diisi'
        ]);

        $kategoris = Kategori::find($id);

        if (!$kategoris) {
            return response()->json(['message' => 'Data Tidak Ada'], 404);
        }

        $kategoris->update($request->all());

        return response()->json([
            'status' => '200',
            'message' => 'Data berhasil diedit',
            'data' => new KategoriResource($kategoris)
        ], 200);

    }

    public function hapus($id)
    {
        $kategoris = Kategori::find($id);

        if (!$kategoris) {
            return response()->json(['message' => 'Data Tidak Ada'], 404);
        }

        $kategoris->delete();

        return response()->json([
            'message' => 'Data Berhasil Dihapus'
        ], 200);
    }
}
