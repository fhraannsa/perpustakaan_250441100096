<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
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

        return view('buku.create'); // atau sesuaikan dengan return view kamu
    }

    public function store(Request $request)
    {
        Gate::authorize('kelola-buku');

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|digits:4|max:' . date('Y'),
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

        // Contoh query dengan binding yang aman dari SQL Injection
        $buku = Buku::where('judul', 'LIKE', "%{$judul}%")->get();

        // Mengembalikan hasil dalam bentuk JSON agar persis `[]` saat kosong
        return response()->json($buku);
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
        $buku = Buku::findOrFail($id);
        
        $dataSimpan = [
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam'
        ];

        if (Schema::hasColumn('peminjamans', 'user_id')) {
            $dataSimpan['user_id'] = auth()->id();
        } elseif (Schema::hasColumn('peminjamans', 'id_user')) {
            $dataSimpan['id_user'] = auth()->id();
        }

        if (Schema::hasColumn('peminjamans', 'anggota_id')) {
            $dataSimpan['anggota_id'] = auth()->id();
        }

        Peminjaman::create($dataSimpan);

        return redirect()->route('siswa.riwayat.alt')->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam!');
    }

    public function riwayatPeminjaman()
    {
        $query = Peminjaman::query();
        
        if (Schema::hasColumn('peminjamans', 'user_id')) {
            $query->where('user_id', auth()->id());
        } elseif (Schema::hasColumn('peminjamans', 'id_user')) {
            $query->where('id_user', auth()->id());
        } elseif (Schema::hasColumn('peminjamans', 'anggota_id')) {
            $query->where('anggota_id', auth()->id());
        }

        $peminjamans = $query->get();
        return view('siswa.riwayat', compact('peminjamans'));
    }
}