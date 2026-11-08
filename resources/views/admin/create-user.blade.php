<x-app-layout>
    <x-slot name="header">
        <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm">
            <h2 class="font-semibold text-xl text-green-900 leading-tight">
                {{ __('Tambah Pengguna Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-50/70 p-6 rounded-lg shadow-sm border border-green-300 max-w-2xl mx-auto">
                
                <h3 class="text-lg font-bold text-green-900 mb-6">Form Tambah Pengguna</h3>

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role / Peran</label>
                        <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2 border">
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="siswa" selected>Siswa</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold rounded-md shadow-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md shadow-sm transition">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>