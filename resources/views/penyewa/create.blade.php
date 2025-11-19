<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Penyewa Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- TAMBAHKAN BLOK INI UNTUK MENAMPILKAN SEMUA ERROR --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops! Ada yang salah:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- AKHIR BLOK ERROR --}}

                    {{-- Tambahkan enctype untuk upload file --}}
                    <form method="POST" action="{{ route('penyewa.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="telepon">Nomor Telepon</label>
                            <input id="telepon" class="block mt-1 w-full" type="text" name="telepon" :value="old('telepon')" required />
                        </div>

                        <div class="mt-4">
                            <label for="nomor_ktp">Nomor KTP</label>
                            <input id="nomor_ktp" class="block mt-1 w-full" type="text" name="nomor_ktp" :value="old('nomor_ktp')" required />
                        </div>

                        <div class="mt-4">
                            <label for="alamat_asal">Alamat Asal</label>
                            <textarea id="alamat_asal" name="alamat_asal" class="block mt-1 w-full" required>{{ old('alamat_asal') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="foto_ktp">Foto KTP</label>
                            <input id="foto_ktp" class="block mt-1 w-full" type="file" name="foto_ktp" />
                        </div>

                        <div class="mt-4">
                            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                            <input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('penyewa.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>