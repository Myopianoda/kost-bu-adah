<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- UBAH JUDUL --}}
            {{ __('Edit Unit: ') . $unit->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- UBAH ACTION FORM & TAMBAHKAN METHOD --}}
                    <form method="POST" action="{{ route('units.update', $unit->id) }}">
                        @csrf
                        @method('PUT') {{-- PENTING UNTUK UPDATE --}}

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Unit</label>
                            {{-- ISI VALUE DENGAN DATA LAMA --}}
                            <input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $unit->name) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="price" class="block font-medium text-sm text-gray-700">Harga Sewa (per bulan)</label>
                            {{-- ISI VALUE DENGAN DATA LAMA --}}
                            <input id="price" class="block mt-1 w-full" type="number" name="price" value="{{ old('price', $unit->price) }}" required />
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('units.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
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