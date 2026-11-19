<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-3xl mx-auto my-10 px-4">
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-900 px-6 py-4 rounded mb-6">
            <h1 class="text-xl font-bold">Detail Transaksi Peminjaman Buku</h1>
        </div>

        <div class="bg-white rounded-lg border border-emerald-200 p-6 shadow-sm space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">ID Transaksi</p>
                    <p class="text-base font-medium">#{{ $peminjaman->id }}</p>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">Status</p>
                    @if (is_null($peminjaman->tanggal_kembali))
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                            Dipinjam
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                            Dikembalikan
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">Peminjam / Anggota</p>
                    <p class="text-base font-medium">
                        {{ $peminjaman->anggota->nama ?? auth()->user()->name ?? '-' }}
                        <span class="text-sm text-gray-500">(ID Anggota: {{ $peminjaman->anggota_id }})</span>
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">Email Anggota</p>
                    <p class="text-base">{{ $peminjaman->anggota->email ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">Judul Buku</p>
                    <p class="text-base font-semibold">{{ $peminjaman->buku->judul ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">Penulis / Penerbit</p>
                    <p class="text-base">
                        {{ $peminjaman->buku->penulis ?? '-' }}
                        @if (!empty($peminjaman->buku->penerbit))
                            / {{ $peminjaman->buku->penerbit }}
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">Tanggal Pinjam</p>
                    <p class="text-base">{{ $peminjaman->tanggal_pinjam }}</p>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-500 font-semibold">Tanggal Kembali</p>
                    <p class="text-base">{{ $peminjaman->tanggal_kembali ?? '-' }}</p>
                </div>
            </div>

            @if (!empty($peminjaman->buku->deskripsi))
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-xs uppercase text-gray-500 font-semibold mb-1">Deskripsi Buku</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $peminjaman->buku->deskripsi }}</p>
                </div>
            @endif

            <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ url()->previous() }}" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-sm font-medium">
                    Kembali
                </a>

                @if (is_null($peminjaman->tanggal_kembali) && auth()->user()?->hasAnyRole(['admin', 'petugas']))
                    <form action="{{ route('peminjaman.updateStatus', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">
                            Proses Pengembalian
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</body>
</html>