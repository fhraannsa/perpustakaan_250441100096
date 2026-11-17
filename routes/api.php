<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;


Route::apiResource('buku', BukuController::class)->names('api.buku');


Route::get('rekap/buku-per-kategori', [BukuController::class, 'rekapBukuPerKategori']);
Route::get('rekap/peminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);