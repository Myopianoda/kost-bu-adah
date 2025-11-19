<x-penyewa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat datang, <strong>{{ $penyewa->nama_lengkap }}</strong>!
                </div>
            </div>

            {{-- LOOPING UNTUK SETIAP SEWA YANG DIMILIKI PENYEWA --}}
            @forelse ($sewaPenyewa as $sewa)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-xl font-semibold mb-4">
                            Tagihan untuk: {{ $sewa->unit->name }}
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode Tagihan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($sewa->tagihan as $tagihan)
                                        <tr>
                                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('M Y') }}</td>
                                            <td class="px-6 py-4">Rp {{ number_format($tagihan->jumlah) }}</td>
                                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}</td>
                                            <td class="px-6 py-4">
                                                @if($tagihan->status == 'lunas')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Lunas
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Belum Bayar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                {{-- Tampilkan tombol Bayar jika belum lunas --}}
                                                @if($tagihan->status == 'belum_bayar')
                                                    <form action="{{ route('tagihan.bayar', $tagihan->id) }}" method="POST">
                                                        @csrf
                                                        <button type."submit" class="font-medium text-indigo-600 hover:text-indigo-900">
                                                            Bayar Sekarang
                                                        </button>
                                                    </form>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                Belum ada data tagihan untuk unit ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900 text-center">
                        Anda belum memiliki data sewa yang aktif.
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</x-penyewa-layout>