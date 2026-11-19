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
                                    @php
                                        $anggota = \App\Models\Anggota::find($item->anggota_id);
                                        $userPeminjam = \App\Models\User::find($anggota->user_id ?? $item->anggota_id);
                                        $buku = \App\Models\Buku::find($item->buku_id);
                                        $namaPeminjam = $anggota->nama ?? $userPeminjam->name ?? 'Siswa';
                                        $emailPeminjam = $anggota->email ?? $userPeminjam->email ?? '-';
                                        $judulBuku = $buku->judul ?? 'Judul Buku';
                                        $penulisBuku = $buku->penulis ?? '-';
                                        $status = strtolower($item->status ?? 'dipinjam');
                                        $statusColor = match($status) {
                                            'selesai', 'dikembalikan' => 'bg-green-100 text-green-800',
                                            'dipinjam' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-blue-100 text-blue-800'
                                        };
                                    @endphp
                                    <tr class="hover:bg-green-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $namaPeminjam }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
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
                                            <div class="flex items-center justify-center space-x-2">
                                                {{-- Tombol Detail --}}
                                                <button type="button"
                                                    onclick="bukaDetailPeminjaman(this)"
                                                    data-id="{{ $item->id }}"
                                                    data-anggota-id="{{ $item->anggota_id ?? '-' }}"
                                                    data-peminjam="{{ $namaPeminjam }}"
                                                    data-email="{{ $emailPeminjam }}"
                                                    data-buku="{{ $judulBuku }}"
                                                    data-penulis="{{ $penulisBuku }}"
                                                    data-tgl-pinjam="{{ $item->tanggal_pinjam ?? '-' }}"
                                                    data-tgl-kembali="{{ $item->tanggal_kembali ?? '-' }}"
                                                    data-status="{{ ucfirst($status) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded font-semibold shadow-sm transition">
                                                    Detail
                                                </button>

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

    {{-- Modal Detail Peminjaman --}}
    <div id="modalDetailPeminjaman" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full overflow-hidden border border-green-200">
            <div class="bg-green-100 border-b border-green-200 px-6 py-4 flex items-center justify-between">
                <h4 class="text-lg font-bold text-green-900">Detail Transaksi Peminjaman</h4>
                <button type="button" onclick="tutupDetailPeminjaman()" class="text-green-900 hover:text-red-600 font-bold text-lg">&times;</button>
            </div>
            <div class="p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">ID Transaksi</span>
                    <span id="detailId" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">ID Anggota</span>
                    <span id="detailAnggotaId" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Nama Peminjam</span>
                    <span id="detailPeminjam" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Email</span>
                    <span id="detailEmail" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Judul Buku</span>
                    <span id="detailBuku" class="font-semibold text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Penulis</span>
                    <span id="detailPenulis" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Tanggal Pinjam</span>
                    <span id="detailTglPinjam" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-500">Tanggal Kembali</span>
                    <span id="detailTglKembali" class="font-medium text-gray-900">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Status</span>
                    <span id="detailStatus" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">-</span>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end border-t">
                <button type="button" onclick="tutupDetailPeminjaman()" class="bg-gray-700 hover:bg-gray-800 text-white text-xs px-4 py-2 rounded font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function bukaDetailPeminjaman(btn) {
            document.getElementById('detailId').textContent = '#' + btn.dataset.id;
            document.getElementById('detailAnggotaId').textContent = btn.dataset.anggotaId;
            document.getElementById('detailPeminjam').textContent = btn.dataset.peminjam;
            document.getElementById('detailEmail').textContent = btn.dataset.email;
            document.getElementById('detailBuku').textContent = btn.dataset.buku;
            document.getElementById('detailPenulis').textContent = btn.dataset.penulis;
            document.getElementById('detailTglPinjam').textContent = btn.dataset.tglPinjam;
            document.getElementById('detailTglKembali').textContent = btn.dataset.tglKembali;
            document.getElementById('detailStatus').textContent = btn.dataset.status;

            const modal = document.getElementById('modalDetailPeminjaman');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupDetailPeminjaman() {
            const modal = document.getElementById('modalDetailPeminjaman');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>