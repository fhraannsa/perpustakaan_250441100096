<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sistem Perpustakaan Digital</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-2xl text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-[#19140035] dark:border-[#3E3E3A] rounded-sm text-sm font-medium hover:border-[#1915014a] dark:hover:border-[#62605b] transition"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm font-medium transition"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-[#19140035] dark:border-[#3E3E3A] rounded-sm text-sm font-medium hover:border-[#1915014a] dark:hover:border-[#62605b] transition"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-700 lg:grow">
            <main class="flex flex-col lg:max-w-4xl max-w-2xl w-full lg:flex-row items-center justify-between gap-8">
                <!-- Bagian Kiri: Teks & Informasi -->
                <div class="flex flex-col items-start gap-4 lg:w-1/2">
                    <div class="flex flex-col gap-1">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            Sistem Perpustakaan Digital
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Let's get started[cite: 6]
                        </p>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        With so many options available to you, we suggest you start with the following:[cite: 6]
                    </p>

                    <ul class="flex flex-col gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                            <a href="https://laravel.com/docs" target="_blank" class="text-green-600 hover:underline">
                                Read the Documentation ↗
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                            <a href="https://laracasts.com" target="_blank" class="text-green-600 hover:underline">
                                Watch video tutorials at Laracasts ↗
                            </a>
                        </li>
                    </ul>

                    <div class="mt-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2.5 rounded-md shadow transition">
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2.5 rounded-md shadow transition">
                                Login Sekarang
                            </a>
                        @endauth
                    </div>

                    <div class="text-xs text-gray-500 mt-2">
                        v13.36.0 <a href="https://github.com/laravel/framework/blob/13.x/CHANGELOG.md" target="_blank" class="text-green-600 hover:underline">View changelog ↗[cite: 6]</a>
                    </div>
                </div>

                <!-- Bagian Kanan: Angka 13 Khas Laravel 13 -->
                <div class="flex items-center justify-center lg:w-1/2">
                    <div class="relative flex items-center justify-center">
                        <span class="text-red-600 font-black text-7xl lg:text-9xl tracking-tighter opacity-90 select-none">Laravel</span>
                        <span class="absolute font-black text-8xl lg:text-[14rem] text-transparent [-webkit-text-stroke:2px_#1b1b18] dark:[-webkit-text-stroke:2px_#EDEDEC] opacity-20 select-none -z-10">13</span>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>