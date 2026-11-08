<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                {{ __('Daftar Transaksi Peminjaman Buku') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-green-50/70 p-6 rounded-lg shadow-sm border border-green-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-green-900">Data Transaksi Peminjaman</h3>
                </div>
                
                <div class="overflow-x-auto bg-white rounded-md border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-200/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">ID Anggota</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Judul Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @isset($peminjamans)
                                @forelse($peminjamans as $index => $item)
                                    <tr class="hover:bg-green-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->anggota_id ?? '-' }}</td>
                                        
                                        <!-- Menampilkan Judul Buku -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->judul_buku ?? (\App\Models\Buku::find($item->buku_id)->judul ?? 'Judul Tidak Ditemukan') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->tanggal_pinjam ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->tanggal_kembali ?? '-' }}</td>
                                        
                                        <!-- Status Badge -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            @php
                                                $status = strtolower(trim($item->status ?? 'dipinjam'));
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

                                        <!-- Tombol Aksi: Proses Pengembalian & Hapus -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                            @php
                                                $isDipinjam = ($status == 'dipinjam' || $status == 'pending');
                                            @endphp

                                            @if($isDipinjam)
                                                <form action="{{ route('admin.peminjaman.kembalikan', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded shadow-sm transition-colors">
                                                        Proses Pengembalian
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-500 text-xs font-medium italic">Selesai</span>
                                            @endif

                                            <form action="{{ route('admin.peminjaman.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data transaksi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow-sm transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data transaksi.</td>
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