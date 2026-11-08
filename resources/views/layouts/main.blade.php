<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Header & Navigasi Atas -->
    <div>
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-xl font-bold text-gray-800">Perpustakaan Digital</h1>
            </div>
        </header>

        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <ul class="flex space-x-8 h-12 items-center">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-green-600 border-b-2 border-green-600 pb-3' : 'text-gray-600 hover:text-gray-900' }}">Beranda</a>
                    </li>
                    <li>
                        <a href="{{ route('buku.index') }}" class="text-sm font-medium {{ request()->routeIs('buku.*') ? 'text-green-600 border-b-2 border-green-600 pb-3' : 'text-gray-600 hover:text-gray-900' }}">Daftar Buku</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Konten Utama -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Bawah -->
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-500 mt-auto">
        <p>&copy; {{ date('Y') }} Perpustakaan Digital. All rights reserved.</p>
    </footer>

</body>
</html>