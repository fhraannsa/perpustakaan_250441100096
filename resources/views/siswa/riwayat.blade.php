<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                {{ __('Riwayat Peminjaman Buku') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-green-50/70 p-6 rounded-lg shadow-sm border border-green-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-green-900">Daftar Transaksi Peminjaman</h3>
                </div>
                
                <div class="overflow-x-auto bg-white rounded-md border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-200/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Judul Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @isset($peminjamans)
                                @forelse($peminjamans as $index => $item)
                                    <tr class="hover:bg-green-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        
                                        <!-- Menampilkan Judul Buku (Menggunakan Join dari Controller atau Fallback ke Model) -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->judul_buku ?? (\App\Models\Buku::find($item->buku_id)->judul ?? 'Judul Buku Tidak Ditemukan') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->tanggal_pinjam ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->tanggal_kembali ?? '-' }}
                                        </td>
                                        
                                        <!-- Menampilkan Status -->
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
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat peminjaman buku.</td>
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