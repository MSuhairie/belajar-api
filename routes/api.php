<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    PostController, AuthenticationController,
    KategoriController,
};

Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);

    Route::post('/post', [PostController::class, 'tambah']);
    Route::patch('/post/{id}', [PostController::class, 'edit'])->middleware('pemilik-postingan');
    Route::delete('/post/{id}', [PostController::class, 'hapus'])->middleware('pemilik-postingan');

    Route::post('/kategori', [KategoriController::class, 'tambah']);
    Route::patch('/kategori/{id}', [KategoriController::class, 'edit']);
    Route::delete('/kategori/{id}', [KategoriController::class, 'hapus']);
});

Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'detail']);

Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/kategori/{id}', [KategoriController::class, 'detail']);


