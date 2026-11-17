<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::all();
        return view('buku.index', compact('bukus'));
    }

    public function indexView()
    {
        $bukus = Buku::all();
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
       
        Gate::authorize('kelola-buku');

        return view('buku.create'); 
    }

    public function store(Request $request)
    {
        Gate::authorize('kelola-buku');

        
        $request->validate([
            'judul' => 'required|string|min:3|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|digits:4',
            'kategori_id' => 'required',
        ]);

        
        Buku::create([
            'kategori_id' => $request->kategori_id,
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
            'kode_buku' => 'BK-' . rand(1000, 9999),
            'gambar' => 'default.jpg',
        ]);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil disimpan!');
    }

    public function edit($id)
    {
        Gate::authorize('kelola-buku');

        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function cari(Request $request)
    {
        $judul = $request->input('judul');

        
        $buku = Buku::where('judul', 'LIKE', "%{$judul}%")->get();
        // $bukus = DB::select("SELECT * FROM buku WHERE judul LIKE '%" . $judul . "%'");
       
        return response()->json($buku);
        // return view('buku.index', compact('bukus'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('kelola-buku');

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|digits:4|max:' . date('Y'),
            'kategori_id' => 'required',
        ]);

        $buku = Buku::findOrFail($id);
        
        $buku->update([
            'kategori_id' => $request->kategori_id,
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
        ]);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Gate::authorize('kelola-buku');

        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil dihapus!');
    }

    public function pinjamSiswa($id)
    {
        $user = auth()->user();
        $buku = Buku::findOrFail($id);

        
        $anggota = Anggota::where('user_id', $user->id)->first();

        
        if (!$anggota) {
            $anggota = Anggota::create([
                'user_id'    => $user->id,
                'nama'       => $user->name,
                'email'      => $user->email,
                'no_telepon' => '-'
            ]);
        }

        
        Peminjaman::create([
            'anggota_id'      => $anggota->id,
            'buku_id'         => $buku->id,
            'tanggal_pinjam'  => now()->toDateString(),
            'tanggal_kembali' => null,
            'status'          => 'dipinjam',
        ]);

        return redirect()->route('siswa.riwayat.alt')->with('success', 'Berhasil melakukan peminjaman buku!');
    }

   public function riwayatPeminjaman()
    {
        $user = auth()->user();

        
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            $peminjaman = Peminjaman::with(['buku', 'anggota'])->latest()->get();
        } else {
            
            $anggota = Anggota::where('user_id', $user->id)->first();

            if (!$anggota) {
                
                $peminjaman = collect();
            } else {
               
                $peminjaman = Peminjaman::with(['buku'])
                    ->where('anggota_id', $anggota->id)
                    ->latest()
                    ->get();
            }
        }

        return view('siswa.riwayat', compact('peminjaman'));
    }
}