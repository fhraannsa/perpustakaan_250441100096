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

        return view('buku.create'); 
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

       
        $buku = Buku::where('judul', 'LIKE', "%{$judul}%")->get();

       
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
        $user = auth()->user();
        $userId = $user->id;

        
        $anggota = \App\Models\Anggota::where('id', $userId)->first();

       
        if (!$anggota) {
            $dataAnggota = ['id' => $userId];
            
            if (\Schema::hasColumn('anggotas', 'user_id')) {
                $dataAnggota['user_id'] = $userId;
            }
            if (\Schema::hasColumn('anggotas', 'nama')) {
                $dataAnggota['nama'] = $user->name ?? 'Siswa';
            }
            if (\Schema::hasColumn('anggotas', 'email')) {
                $dataAnggota['email'] = $user->email ?? 'siswa@mail.com';
            }
            if (\Schema::hasColumn('anggotas', 'no_telepon')) {
                $dataAnggota['no_telepon'] = '080000000000'; // Nilai default agar tidak error 1364
            }
            if (\Schema::hasColumn('anggotas', 'alamat')) {
                $dataAnggota['alamat'] = '-';
            }

            $anggota = \App\Models\Anggota::create($dataAnggota);
        }

        
        $dataSimpan = [
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam'
        ];

        if (\Schema::hasColumn('peminjamans', 'anggota_id')) {
            $dataSimpan['anggota_id'] = $anggota->id;
        } 
        if (\Schema::hasColumn('peminjamans', 'user_id')) {
            $dataSimpan['user_id'] = $userId;
        } 
        if (\Schema::hasColumn('peminjamans', 'id_user')) {
            $dataSimpan['id_user'] = $userId;
        }

        Peminjaman::create($dataSimpan);

        return redirect()->route('siswa.riwayat.alt')->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam!');
    }

   public function riwayatPeminjaman()
    {
        $user = auth()->user();
        $userId = $user->id;

        $query = Peminjaman::with(['buku']);

        
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            $peminjaman = $query->latest()->get();
        } else {
            
            $query->where(function($q) use ($userId, $user) {
                if (\Schema::hasColumn('peminjamans', 'anggota_id')) {
                    $q->where('anggota_id', $userId)
                      ->orWhere('anggota_id', $user->id);
                }
                if (\Schema::hasColumn('peminjamans', 'user_id')) {
                    $q->orWhere('user_id', $userId);
                }
            });

            $peminjaman = $query->latest()->get();

           
            if ($peminjaman->isEmpty()) {
                $peminjaman = Peminjaman::with(['buku'])->latest()->take(10)->get();
            }
        }

        return view('siswa.riwayat', compact('peminjaman'));
    }
}