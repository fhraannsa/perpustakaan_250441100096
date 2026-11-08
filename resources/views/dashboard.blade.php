<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    @if(auth()->check() && auth()->user()->hasRole('admin'))
                        <h3 class="text-lg font-bold">Panel Administrator</h3>
                        <p class="mt-2">Selamat datang, Admin! Anda memiliki akses penuh untuk mengelola data buku, pengguna, role, dan laporan perpustakaan.</p>
                    @endif

                    {{-- Tampilan khusus PETUGAS --}}
                    @if(auth()->check() && auth()->user()->hasRole('petugas'))
                        <h3 class="text-lg font-bold">Panel Petugas Perpustakaan</h3>
                        <p class="mt-2">Selamat datang, Petugas! Silakan kelola data buku serta proses peminjaman dan pengembalian buku.</p>
                    @endif

                    {{-- Tampilan khusus SISWA --}}
                    @if(auth()->check() && auth()->user()->hasRole('siswa'))
                        <h3 class="text-lg font-bold">Katalog Buku Perpustakaan</h3>
                        <p class="mt-2">Selamat datang, Siswa! Silakan cari buku yang ingin kamu pinjam atau cek riwayat peminjamanmu.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>