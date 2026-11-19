<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $user = auth()->user();

        try {
            // Gunakan firstOrCreate untuk menghindari duplikasi transaksi stateful di Supabase pooler
            $anggota = Anggota::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama'       => $user->name ?? 'User',
                    'email'      => $user->email,
                    'no_telepon' => '-'
                ]
            );

            // Masukkan data peminjaman
            Peminjaman::create([
                'anggota_id'      => $anggota->id,
                'buku_id'         => $request->buku_id,
                'tanggal_pinjam'  => now()->toDateString(),
                'tanggal_kembali' => null,
            ]);

            return redirect()->route('peminjaman.riwayat')->with('success', 'Berhasil melakukan peminjaman buku!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal meminjam buku: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    
    public function riwayat()
    {
        $user = auth()->user();
        
        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            $peminjamans = collect();
        } else {
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

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('siswa')) {
            $anggota = Anggota::where('user_id', $user->id)->first();
            if (!$anggota || $peminjaman->anggota_id !== $anggota->id) {
                abort(403, 'Anda tidak berhak mengakses detail peminjaman ini.');
            }
        }

        return view('peminjaman.show', compact('peminjaman'));
    }
}