<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                {{ __('Ubah Role Pengguna') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-50/70 p-6 rounded-lg shadow-sm border border-green-300 max-w-2xl mx-auto">
                
                <h3 class="text-lg font-bold text-green-900 mb-2">Form Ubah Role Pengguna</h3>
                <p class="text-sm text-gray-600 mb-6">Pengguna: <span class="font-semibold text-gray-800">{{ $user->name }}</span> ({{ $user->email }})</p>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Pilihan Role / Peran Saja -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Role / Peran Baru</label>
                        <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2.5 border bg-white">
                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ $user->hasRole('petugas') ? 'selected' : '' }}>Petugas</option>
                            <option value="siswa" {{ $user->hasRole('siswa') ? 'selected' : '' }}>Siswa</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold rounded-md shadow-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md shadow-sm transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>