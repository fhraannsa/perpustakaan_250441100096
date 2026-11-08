<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                {{ __('Kelola Pengguna') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-50/70 p-6 rounded-lg shadow-sm border border-green-300">
                
                <!-- Bagian Judul dan Tombol Tambah -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-green-900">Manajemen Pengguna</h3>
                        <p class="text-sm text-gray-600">Kelola akun, hak akses, dan peran pengguna sistem perpustakaan.</p>
                    </div>
                    <a href="#" class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-md shadow-sm transition">
                        + Tambah Pengguna Baru
                    </a>
                </div>

                <!-- Tabel Data Pengguna -->
                <div class="overflow-x-auto bg-white rounded-md border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-200/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-green-900 uppercase tracking-wider">Role / Peran</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-green-900 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @isset($users)
                                @forelse($users as $index => $user)
                                    @php
                                        // Cek role user, jika menggunakan Spatie permission atau pengecekan string biasa
                                        $roleName = method_exists($user, 'getRoleNames') ? strtolower($user->getRoleNames()->implode(', ')) : 'siswa';
                                        $isAdmin = $roleName === 'admin';
                                    @endphp
                                    <tr class="hover:bg-green-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ ucfirst($roleName ?: 'siswa') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                            <button class="bg-amber-400 hover:bg-amber-500 text-white text-xs px-3 py-1.5 rounded font-semibold shadow-sm transition">
                                                Edit
                                            </button>
                                            @if(!$isAdmin)
                                                <button class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded font-semibold shadow-sm transition">
                                                    Hapus
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pengguna.</td>
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