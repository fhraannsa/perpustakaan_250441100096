<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peminjaman;

class AdminController extends Controller
{
    public function manageUsers()
    {
        $users = User::all();
        $peminjamans = Peminjaman::all();

        return view('admin.users', compact('users', 'peminjamans'));
    }

    public function manageTransaksi()
    {
        $peminjamans = \Illuminate\Support\Facades\DB::table('peminjamans')
            ->leftJoin('buku', 'peminjamans.buku_id', '=', 'buku.id')
            ->select('peminjamans.*', 'buku.judul as judul_buku')
            ->latest('peminjamans.id')
            ->get();

        return view('admin.transaksi', compact('peminjamans'));
    }

    public function laporan()
    {
        $peminjamans = \Illuminate\Support\Facades\DB::table('peminjamans')
            ->leftJoin('buku', 'peminjamans.buku_id', '=', 'buku.id')
            ->select('peminjamans.*', 'buku.judul as judul_buku')
            ->latest('peminjamans.id')
            ->get();

        foreach ($peminjamans as $item) {
            
            $anggota = \Illuminate\Support\Facades\DB::table('anggotas')
                ->where('user_id', $item->anggota_id)
                ->orWhere('id', $item->anggota_id)
                ->first();

            if ($anggota) {
                $item->nama_peminjam = $anggota->nama;
            } else {
                
                $user = \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', $item->anggota_id)
                    ->first();
                
                $item->nama_peminjam = $user ? $user->name : 'ID ' . $item->anggota_id . ' (Tidak Ditemukan)';
            }
        }

        return view('admin.laporan', compact('peminjamans'));
    }

    public function kembalikanBuku($id)
    {
        \Illuminate\Support\Facades\DB::table('peminjamans')->where('id', $id)->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan!');
    }

    public function destroyTransaksi($id)
    {
        \Illuminate\Support\Facades\DB::table('peminjamans')->where('id', $id)->delete();

        return back()->with('success', 'Data transaksi berhasil dihapus!');
    }

    public function createUser()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole($request->role);
        }

        return redirect()->route('admin.users')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'role' => 'required|string',
        ]);

        
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('admin.users')->with('success', 'Role pengguna berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan!');
        }

        User::findOrFail($id)->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}