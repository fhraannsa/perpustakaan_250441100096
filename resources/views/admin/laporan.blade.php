<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                {{ __('Laporan Peminjaman') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Statistik Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-900 text-white p-6 rounded-lg shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Transaksi</p>
                    <p class="text-3xl font-bold mt-2">{{ isset($peminjamans) ? count($peminjamans) : 0 }}</p>
                </div>
                <div class="bg-green-700 text-white p-6 rounded-lg shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-green-200">Transaksi Selesai</p>
                    <p class="text-3xl font-bold mt-2">
                        {{ isset($peminjamans) ? $peminjamans->where('status', 'dikembalikan')->count() : 0 }}
                    </p>
                </div>
            </div>

            <!-- Tabel Rekapitulasi -->
            <div class="bg-green-50/70 p-6 rounded-lg shadow-sm border border-green-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-green-900">Riwayat & Rekapitulasi Peminjaman Buku</h3>
                </div>
                
                <div class="overflow-x-auto bg-white rounded-md border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-200/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Judul Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Tgl Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Tgl Kembali</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @isset($peminjamans)
                                @forelse($peminjamans as $index => $item)
                                    <tr class="hover:bg-green-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        
                                        <!-- Nama Peminjam -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->nama_peminjam ?? 'User Tidak Ditemukan' }}
                                        </td>
                                        
                                        <!-- Judul Buku -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->judul_buku ?? 'Judul Tidak Ditemukan' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->tanggal_pinjam ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->tanggal_kembali ?? '-' }}</td>
                                        
                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            @php
                                                $status = strtolower($item->status ?? 'dipinjam');
                                                $statusColor = match($status) {
                                                    'selesai', 'dikembalikan' => 'bg-green-100 text-green-800',
                                                    'dipinjam' => 'bg-yellow-100 text-yellow-800',
                                                    default => 'bg-blue-100 text-blue-800'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data laporan.</td>
                                    </tr>
                                @endforelse
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>