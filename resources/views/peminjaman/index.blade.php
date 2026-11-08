<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
           
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-900 text-white p-6 rounded-lg shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold tracking-wider uppercase text-gray-400">Total Transaksi</span>
                    <span class="text-3xl font-bold mt-2">
                        @isset($peminjamans) {{ count($peminjamans) }} @else 0 @endisset
                    </span>
                </div>

                <div class="bg-green-700 text-white p-6 rounded-lg shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold tracking-wider uppercase text-green-100">Transaksi Selesai</span>
                    <span class="text-3xl font-bold mt-2">
                        @isset($peminjamans)
                            {{ $peminjamans->where('status', 'selesai')->count() }}
                        @else
                            0
                        @endisset
                    </span>
                </div>
            </div>

    
            <div class="bg-green-50/70 p-6 rounded-lg shadow-sm border border-green-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-green-900">Daftar Transaksi Peminjaman Buku</h3>
                </div>
                
                <div class="overflow-x-auto bg-white rounded-md border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-200/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Judul Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Tgl Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Tgl Kembali</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @isset($peminjamans)
                                @forelse($peminjamans as $index => $item)
                                    <tr class="hover:bg-green-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \App\Models\User::find($item->anggota_id)->name ?? 'Siswa' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ \App\Models\Buku::find($item->buku_id)->judul ?? 'Judul Buku' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->tanggal_pinjam ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->tanggal_kembali ?? '-' }}
                                        </td>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                {{-- Tombol Pengembalian (Hanya muncul jika status belum selesai/dikembalikan) --}}
                                                @if($status !== 'selesai' && $status !== 'dikembalikan')
                                                    <form action="{{ route('admin.peminjaman.kembalikan', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi buku ini sudah dikembalikan?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded font-semibold shadow-sm transition">
                                                            Kembalikan
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('admin.peminjaman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded font-semibold shadow-sm transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data peminjaman.</td>
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