<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeminjamanController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $user = auth()->user();

        // 1. Ambil data anggota yang PUNYA user_id persis dengan user yang login saat ini
        $anggota = Anggota::where('user_id', $user->id)->first();

        // 2. Jika akun ini belum punya data anggota, buatkan secara spesifik
        if (!$anggota) {
            $anggota = Anggota::create([
                'user_id'    => $user->id,
                'nama'       => $user->name,
                'email'      => $user->email,
                'no_telepon' => '-'
            ]);
        }

        // 3. Masukkan data peminjaman dengan MEMASTIKAN 'anggota_id' murni milik si $anggota yang login
        Peminjaman::create([
            'anggota_id'      => $anggota->id, // <-- Kunci utamanya di sini!
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
        
        // 1. Cari data anggota yang persis terikat ke user_id yang sedang login
        $anggota = Anggota::where('user_id', $user->id)->first();

        // 2. Jika akun ini belum punya profil anggota, return kosong
        if (!$anggota) {
            $peminjamans = collect();
        } else {
            // 3. AMBIL PEMINJAMAN HANYA MILIK ID ANGGOTA INI (PENTING!)
            $peminjamans = Peminjaman::with('buku')
                ->where('anggota_id', $anggota->id)
                ->latest()
                ->get();
        }

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

    // A4 no 1
    public function show($id)
    {
        
        $buku = Buku::findOrFail($id);

        
        return view('buku.show', compact('buku'));
    }

    // A4 no selanjutnya
    // public function show($id)
    // {
    //     $peminjaman = Peminjaman::findOrFail($id);

        
    //     Gate::authorize('show', $peminjaman);

    //     return view('peminjaman.show', compact('peminjaman'));
    // }
}