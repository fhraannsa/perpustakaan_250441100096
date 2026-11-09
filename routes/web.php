<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/siswa/buku', [BukuController::class, 'indexView'])->name('siswa.buku');
    Route::get('/siswa/riwayat', [BukuController::class, 'riwayatPeminjaman'])->name('siswa.riwayat.alt');
    Route::get('/siswa/pinjam/{id}', [BukuController::class, 'pinjamSiswa'])->name('siswa.pinjam');
});


Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::middleware([RoleMiddleware::using('admin|petugas')])->group(function () {
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        
        Route::get('/transaksi', [AdminController::class, 'manageTransaksi'])->name('admin.transaksi');
        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
        
        Route::delete('/peminjaman/{id}', [AdminController::class, 'destroyTransaksi'])->name('admin.peminjaman.destroy');
        Route::put('/peminjaman/{id}/kembalikan', [AdminController::class, 'kembalikanBuku'])->name('admin.peminjaman.kembalikan');
    });

    
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

    Route::get('/a4-test', [A4Controller::class, 'formTest'])->name('a4.form');
    Route::post('/a4-test', [A4Controller::class, 'prosesDeserialization'])->name('a4.proses');
});

Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

class MaliciousGadget {
    public $command = 'whoami';

    public function __destruct() {
        if (!empty($this->command)) {
            echo "<pre>Hasil Eksekusi RCE: " . shell_exec($this->command) . "</pre>";
        }
    }
}

Route::get('/a4-test', function () {
    $obj = new MaliciousGadget();
    $samplePayload = base64_encode(serialize($obj));

    return "<h2>Simulasi A4: Insecure Deserialization</h2>
            <p>Contoh Payload Valid (Base64): <code>{$samplePayload}</code></p>
            <form method='POST' action='/a4-test'>
                <input type='hidden' name='_token' value='" . csrf_token() . "'>
                <textarea name='payload' rows='4' cols='50' placeholder='Masukkan string serialize di sini...'></textarea><br><br>
                <button type='submit'>Kirim Payload</button>
            </form>";
});

Route::post('/a4-test', function (Request $request) {
    $inputPayload = $request->input('payload');
    $decodedData = unserialize(base64_decode($inputPayload));

    return "Deserialisasi berhasil diproses untuk objek kelas: " . get_class($decodedData);
});

require __DIR__.'/auth.php';