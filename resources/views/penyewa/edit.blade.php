<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Penyewa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('penyewa.update', $penyewa->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nama_lengkap" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                            <input id="nama_lengkap" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $penyewa->nama_lengkap) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="telepon" class="block font-medium text-sm text-gray-700">Nomor Telepon</label>
                            <input id="telepon" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="telepon" value="{{ old('telepon', $penyewa->telepon) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="nomor_ktp" class="block font-medium text-sm text-gray-700">Nomor KTP</label>
                            <input id="nomor_ktp" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="nomor_ktp" value="{{ old('nomor_ktp', $penyewa->nomor_ktp) }}" required />
                        </div>
                        
                        <div class="mt-4">
                            <label for="alamat_asal" class="block font-medium text-sm text-gray-700">Alamat Asal</label>
                            <textarea id="alamat_asal" name="alamat_asal" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('alamat_asal', 'alamat_asal', $penyewa->alamat_asal) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="foto_ktp" class="block font-medium text-sm text-gray-700">Foto KTP (Kosongkan jika tidak ingin ganti)</label>
                            @if ($penyewa->foto_ktp)
                                <div class="my-2">
                                    <img src="{{ asset('storage/' . $penyewa->foto_ktp) }}" alt="Foto KTP Lama" class="h-20 w-32 object-cover">
                                </div>
                            @endif
                            <input id="foto_ktp" class="block mt-1 w-full" type="file" name="foto_ktp" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('penyewa.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>