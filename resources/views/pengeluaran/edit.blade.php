<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengeluaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Arahkan action ke route 'update' dan gunakan method PUT --}}
                    <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran->id) }}">
                        @csrf
                        @method('PUT') {{-- PENTING: Method untuk update --}}

                        <div>
                            <label for="keterangan" class="block font-medium text-sm text-gray-700">Keterangan</label>
                            {{-- Isi value dengan data yang ada --}}
                            <input id="keterangan" class="block mt-1 w-full" type="text" name="keterangan" value="{{ old('keterangan', $pengeluaran->keterangan) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="jumlah" class="block font-medium text-sm text-gray-700">Jumlah (Rp)</label>
                            <input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" value="{{ old('jumlah', $pengeluaran->jumlah) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="tanggal" class="block font-medium text-sm text-gray-700">Tanggal Pengeluaran</label>
                            <input id="tanggal" class="block mt-1 w-full" type="date" name="tanggal" value="{{ old('tanggal', $pengeluaran->tanggal) }}" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('pengeluaran.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
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