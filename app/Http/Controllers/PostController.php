<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;

use App\Http\Resources\{
    PostResource, PostDetailResource,
};

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return PostDetailResource::collection($posts->loadMissing('writer:id,username'));
        // return PostResource::collection($posts); kegunaanya jika anda tidak ingin memanggil relasi dengan author
    }

    public function detail($id)
    {
        $posts = Post::find($id);

        if (!$posts) {
            return response()->json(['message' => 'Data Tidak Ada'], 404);
        }

        return new PostDetailResource($posts->loadMissing('writer:id,username'));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ], [
            'title.required' => 'Title Wajib diisi',
            'title.max' => 'Maximal 255 kata',
            'news_content.required' => 'News Content Wajib diisi',
        ]);

        $fileName = null; // jika tidak ada foto di upload maka hasil kosong dan agar tidak error
        $file = $request->foto;

        if ($file) {
            // Upload Foto
            $fileName = time().'.'.$file->extension();
            $file->move(public_path('assets/foto'), $fileName);
        }

        $request['image'] = $fileName;
        $request['author'] = Auth::user()->id;

        $posts = Post::create($request->all());
        return new PostDetailResource($posts->loadMissing('writer:id,username'));
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ], [
            'title.required' => 'Title Wajib diisi',
            'title.max' => 'Maximal 255 kata',
            'news_content.required' => 'News Content Wajib diisi',
        ]);

        if ($request->foto <> "") {
             // jika ingin ganti foto
            // Upload Foto
            $file = $request->foto;
            $fileName = time().'.'.$file->extension();
            $file->move(public_path('assets/foto'), $fileName);

            $request['image'] = $fileName;
            $posts = Post::findOrFail($id);
            $posts->update($request->all());

        }else{
            //jika tidak ingin ganti foto
            $posts = Post::findOrFail($id);
            $posts->update($request->all());
        }

        return new PostDetailResource($posts->loadMissing('writer:id,username'));
    }

    public function hapus($id)
    {
        $posts = Post::findOrFail($id);
        if ($posts->image <> "") {
            unlink(public_path('assets/foto') . '/' . $posts->image);
        }
        $posts->delete();
        // return new PostDetailResource($posts->loadMissing('writer:id,username'));
        return response()->json(['message' => 'Data Berhasil Dihapus'], 200);
    }
}
