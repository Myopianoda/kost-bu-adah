<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kost Bu Adah</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="container mx-auto px-4 py-8">

            <header class="mb-8">
                <h1 class="text-4xl font-bold text-center text-gray-800">
                    Selamat Datang di Kost Bu Adah
                </h1>
                <p class="text-center text-xl text-gray-600 mt-2">
                    Kami menyediakan kost dan kontrakan yang bersih, aman, dan nyaman.
                </p>
            </header>

            <nav class="absolute top-0 right-0 p-6">
                @auth('web') {{-- Cek apakah Admin yang login --}}
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dashboard Admin</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Login Admin</a>

                    {{-- TAMBAHKAN LINK LOGIN PENYEWA --}}
                    <a href="{{ route('penyewa.login') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900">Login Penyewa</a>
                @endauth
            </nav>

            <main>
                <h2 class="text-2xl font-semibold text-gray-700 mb-6">
                    Unit Tersedia Saat Ini
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @forelse ($unitTersedia as $unit)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            {{-- Kita bisa tambahkan gambar unit di sini nanti --}}
                            {{-- <img src="https..."" alt="{{ $unit->name }}" class="w-full h-48 object-cover"> --}}

                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800">{{ $unit->name }}</h3>
                                <p class="text-lg text-gray-900 font-bold mt-2">
                                    Rp {{ number_format($unit->price) }} / bulan
                                </p>
                                @if($unit->description)
                                    <p class="text-gray-600 mt-4">
                                        {{ $unit->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-10">
                            <p>Mohon maaf, saat ini semua unit sudah terisi.</p>
                        </div>
                    @endforelse

                </div>
            </main>
        </div>
    </body>
</html>