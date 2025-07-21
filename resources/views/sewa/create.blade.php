<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sewakan Unit: ') . $unit->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('sewa.store') }}">
                        @csrf
                        {{-- Kirim ID unit secara tersembunyi --}}
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                        <div>
                            <label for="penyewa_id">Pilih Penyewa</label>
                            <select name="penyewa_id" id="penyewa_id" class="block mt-1 w-full" required>
                                <option value="">-- Pilih salah satu --</option>
                                @foreach ($daftar_penyewa as $penyewa)
                                    <option value="{{ $penyewa->id }}">{{ $penyewa->nama_lengkap }} ({{ $penyewa->telepon }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="tanggal_mulai">Tanggal Mulai Sewa</label>
                            <input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('units.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan dan Sewakan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>