<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

       $permissions = [
            'Akses dashboard admin',                  // 1
            'Akses dashboard petugas',                // 2
            'Lihat daftar buku',                      // 3
            'Kelola data buku',                       // 4
            'Kelola data pengguna',                   // 5
            'Kelola role pengguna',                   // 6
            'Kelola peminjaman',                      // 7
            'Kelola pengembalian',                    // 8
            'Mengajukan peminjaman buku',             // 9
            'Memproses persetujuan peminjaman',       // 10
            'Mencatat pengembalian buku',             // 11
            'Melihat status peminjaman sendiri',      // 12
            'Melihat riwayat peminjaman sendiri',     // 13
            'Lihat laporan perpustakaan',             // 14
            'Lihat profil sendiri',                   // 15
            'Ubah profil sendiri',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $petugasRole = Role::firstOrCreate(['name' => 'petugas']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);

        $adminRole->givePermissionTo([
            'Akses dashboard admin',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola role pengguna',
            'Kelola peminjaman',
            'Kelola pengembalian',
            'Mengajukan peminjaman buku',
            'Memproses persetujuan peminjaman',
            'Mencatat pengembalian buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        $petugasRole->givePermissionTo([
            'Akses dashboard petugas',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola peminjaman',
            'Kelola pengembalian',
            'Mengajukan peminjaman buku',
            'Memproses persetujuan peminjaman',
            'Mencatat pengembalian buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        $siswaRole->givePermissionTo([
            'Lihat daftar buku',
            'Mengajukan peminjaman buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        
        $admin = User::updateOrCreate(
            ['email' => 'admin@perpus.com'],
            ['name' => 'Administrator', 'password' => Hash::make('admin1234')]
        );
        $admin->assignRole($adminRole);

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpus.com'],
            ['name' => 'Petugas Perpus', 'password' => Hash::make('petugas123')]
        );
        $petugas->assignRole($petugasRole);

        $siswa = User::firstOrCreate(
            ['email' => 'siswa@perpus.com'],
            ['name' => 'Siswa Teladan', 'password' => Hash::make('siswa123')]
        );
        $siswa->assignRole($siswaRole);
    }   
}
