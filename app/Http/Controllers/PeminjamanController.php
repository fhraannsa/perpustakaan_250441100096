<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $user = auth()->user();

        
        $anggota = Anggota::firstOrCreate(
            ['nama' => $user->name],
            [
                'nama'       => $user->name,
                'email'      => $user->email,
                'no_telepon' => '08123456789'
            ]
        );

        Peminjaman::create([
            'anggota_id'      => $anggota->id,
            'buku_id'         => $request->buku_id,
            'tanggal_pinjam'  => now()->toDateString(),
            'tanggal_kembali' => null,
        ]);

        return redirect()->route('peminjaman.riwayat')->with('success', 'Berhasil melakukan peminjaman buku!');
    }

    
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    
    public function riwayat()
    {
        $user = auth()->user();
        
       
        $anggota = Anggota::where('nama', $user->name)->first();
        $anggotaId = $anggota ? $anggota->id : 0;

        $peminjamans = Peminjaman::with('buku')
            ->where('anggota_id', $anggotaId)
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('peminjamans'));
    }

    
    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Buku telah berhasil dikembalikan!');
    }

   
    public function laporan()
    {
        
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();

       
        $totalPinjam = $peminjamans->count();
        $sedangDipinjam = $peminjamans->whereNull('tanggal_kembali')->count();
        $sudahKembali = $peminjamans->whereNotNull('tanggal_kembali')->count();

        return view('laporan.index', compact('peminjamans', 'totalPinjam', 'sedangDipinjam', 'sudahKembali'));
    }
    
    public function show($id)
    {
        // Ambil data peminjaman
        $peminjaman = Peminjaman::findOrFail($id);
        
        // AMBIL JUGA DATA BUKUNYA supaya variabel $buku di file view-mu gak kosong/error
        $buku = \App\Models\Buku::find($peminjaman->buku_id);

        // Arahkan ke folder buku.show (sesuai tempat file view kamu sekarang)
        return view('buku.show', compact('buku', 'peminjaman'));
    }
}