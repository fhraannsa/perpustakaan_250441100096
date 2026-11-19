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
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($peminjaman as $index => $item)
                                @php
                                    $buku = $item->buku ?? \App\Models\Buku::find($item->buku_id);
                                    $judulBuku = $buku->judul ?? 'Judul Buku Tidak Ditemukan';
                                    $penulisBuku = $buku->penulis ?? '-';
                                    $status = strtolower($item->status ?? (is_null($item->tanggal_kembali) ? 'dipinjam' : 'dikembalikan'));
                                    $statusColor = match($status) {
                                        'selesai', 'dikembalikan' => 'bg-green-100 text-green-800',
                                        'dipinjam' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-blue-100 text-blue-800'
                                    };
                                @endphp
                                <tr class="hover:bg-green-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $judulBuku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->tanggal_pinjam ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->tanggal_kembali ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <button type="button"
                                            onclick="bukaDetailSiswa(this)"
                                            data-id="{{ $item->id }}"
                                            data-buku="{{ $judulBuku }}"
                                            data-penulis="{{ $penulisBuku }}"
                                            data-tgl-pinjam="{{ $item->tanggal_pinjam ?? '-' }}"
                                            data-tgl-kembali="{{ $item->tanggal_kembali ?? '-' }}"
                                            data-status="{{ ucfirst($status) }}"
                                            class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded font-semibold shadow-sm transition">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada buku yang dipinjam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Detail Peminjaman Siswa --}}
    <div id="modalDetailSiswa" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full overflow-hidden border border-green-200">
            <div class="bg-green-100 border-b border-green-200 px-6 py-4 flex items-center justify-between">
                <h4 class="text-lg font-bold text-green-900">Detail Peminjaman Buku</h4>
                <button type="button" onclick="tutupDetailSiswa()" class="text-green-900 hover:text-red-600 font-bold text-lg">&times;</button>
            </div>
            <div class="p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">ID Transaksi</span>
                    <span id="siswaDetailId" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Judul Buku</span>
                    <span id="siswaDetailBuku" class="font-semibold text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Penulis</span>
                    <span id="siswaDetailPenulis" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Tanggal Pinjam</span>
                    <span id="siswaDetailTglPinjam" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Tanggal Kembali</span>
                    <span id="siswaDetailTglKembali" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Status</span>
                    <span id="siswaDetailStatus" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">-</span>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end border-t">
                <button type="button" onclick="tutupDetailSiswa()" class="bg-gray-700 hover:bg-gray-800 text-white text-xs px-4 py-2 rounded font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function bukaDetailSiswa(btn) {
            document.getElementById('siswaDetailId').textContent = '#' + btn.dataset.id;
            document.getElementById('siswaDetailBuku').textContent = btn.dataset.buku;
            document.getElementById('siswaDetailPenulis').textContent = btn.dataset.penulis;
            document.getElementById('siswaDetailTglPinjam').textContent = btn.dataset.tglPinjam;
            document.getElementById('siswaDetailTglKembali').textContent = btn.dataset.tglKembali;
            document.getElementById('siswaDetailStatus').textContent = btn.dataset.status;

            const modal = document.getElementById('modalDetailSiswa');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupDetailSiswa() {
            const modal = document.getElementById('modalDetailSiswa');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>