<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                @role('siswa')
                    {{ __('Daftar Katalog Buku') }}
                @else
                    {{ __('Pengelolaan Data Buku') }}
                @endrole
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded-md shadow mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Data Buku -->
            @hasanyrole('admin|petugas')
            <div class="bg-green-100/70 p-6 rounded-lg shadow-sm border border-green-300">
                <h3 class="text-lg font-semibold text-green-900 mb-4" id="form-title">Form Data Buku</h3>
                
                <form action="/buku" method="POST" id="form-buku">
                    @csrf
                    <div id="method-container"></div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori ID:</label>
                        <input type="text" name="kategori_id" id="input-kategori" class="w-full border-gray-300 rounded-md shadow-sm p-2 border bg-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku:</label>
                        <input type="text" name="judul" id="input-judul" class="w-full border-gray-300 rounded-md shadow-sm p-2 border bg-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penulis:</label>
                        <input type="text" name="penulis" id="input-penulis" class="w-full border-gray-300 rounded-md shadow-sm p-2 border bg-white" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit:</label>
                        <input type="number" name="tahun_terbit" id="input-tahun" min="1900" max="2026" class="w-full border-gray-300 rounded-md shadow-sm p-2 border bg-white" required>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="submit" id="btn-submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow transition">Simpan</button>
                        <button type="button" id="btn-batal" style="display: none;" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition" onclick="resetForm()">Batal</button>
                    </div>
                </form>
            </div>
            @endhasanyrole

            <!-- Daftar Buku -->
            <div class="bg-green-100/70 shadow-sm rounded-lg overflow-hidden border border-green-300 p-4">
                <h3 class="text-lg font-semibold text-green-900 mb-4">Daftar Buku</h3>
                <div class="overflow-x-auto bg-white rounded-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-200/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Penulis</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase">Tahun</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bukus as $buku)
                                <tr class="hover:bg-green-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $buku->kategori_id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{!! $buku->judul !!}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $buku->penulis }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $buku->tahun_terbit }}</td>
                                    <td class="px-6 py-4 text-center text-sm space-x-2">
                                        <a href="{{ route('buku.show', $buku->id) }}" class="text-blue-600 font-semibold">Detail</a>
                                        
                                        @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas') || auth()->user()->can('kelola data buku')))
                                            <button type="button" onclick="pilihUbah({{ $buku->id }}, '{{ $buku->kategori_id }}', '{{ addslashes($buku->judul) }}', '{{ addslashes($buku->penulis) }}', {{ $buku->tahun_terbit }})" class="text-green-600 font-semibold hover:text-green-900">Ubah</button>
                                            
                                            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data buku ini?')" class="text-red-600 font-semibold hover:text-red-900">Hapus</button>
                                            </form>
                                        @endif

                                        @role('siswa')
                                            <a href="{{ url('/siswa/pinjam/' . $buku->id) }}" onclick="return confirm('Yakin ingin meminjam buku ini?')" class="text-green-600 font-semibold">Pinjam Buku</a>
                                        @endrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('js')
    <script>
        function pilihUbah(id, kategoriId, judul, penulis, tahunTerbit) {
            document.getElementById('input-kategori').value = kategoriId;
            document.getElementById('input-judul').value = judul;
            document.getElementById('input-penulis').value = penulis;
            document.getElementById('input-tahun').value = tahunTerbit;

            const form = document.getElementById('form-buku');
            form.action = "{{ url('/buku') }}/" + id;

            document.getElementById('method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('form-title').innerText = 'Edit Data Buku';
            document.getElementById('btn-submit').innerText = 'Perbarui';
            document.getElementById('btn-batal').style.display = 'inline-block';

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('form-buku').action = "/buku";
            document.getElementById('method-container').innerHTML = '';
            document.getElementById('form-buku').reset();

            document.getElementById('form-title').innerText = 'Form Data Buku';
            document.getElementById('btn-submit').innerText = 'Simpan';
            document.getElementById('btn-batal').style.display = 'none';
        }
    </script>
    @endpush
</x-app-layout>