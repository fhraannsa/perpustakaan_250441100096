<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                {{ auth()->user()->hasRole('siswa') ? __('Riwayat Peminjaman Buku') : __('Pengelolaan Peminjaman') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Notifikasi jika ada session -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Konten Utama Tabel Riwayat/Peminjaman -->
            <div class="bg-green-100/70 shadow-sm rounded-lg overflow-hidden border border-green-300 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-green-900">
                        {{ auth()->user()->hasRole('siswa') ? 'Daftar Buku yang Pernah Anda Pinjam' : 'Daftar Seluruh Peminjaman' }}
                    </h3>
                    
                    @role('siswa')
                    <!-- Tombol Ajukan Pinjam Baru (Opsional jika ada fitur pengajuan) -->
                    <a href="{{ route('buku.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow transition">
                        + Pinjam Buku Baru
                    </a>
                    @endrole
                </div>

                <div class="overflow-x-auto bg-white rounded-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-200/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">No</th>
                                @hasanyrole('admin|petugas')
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Nama Peminjam</th>
                                @endhasanyrole
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Judul Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Contoh data statis dummy agar langsung terlihat tidak kosong sebelum dihubungkan ke Database Controller --}}
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                @hasanyrole('admin|petugas')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Siswa Teladan</td>
                                @endhasanyrole
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Belajar Pemrograman Laravel 11</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2026-06-01</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Dipinjam
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <span class="text-gray-400 text-xs">Selesai</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                                @hasanyrole('admin|petugas')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Siswa Teladan</td>
                                @endhasanyrole
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Algoritma & Struktur Data</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2026-05-15</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Dikembalikan
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <span class="text-gray-400 text-xs">Selesai</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>