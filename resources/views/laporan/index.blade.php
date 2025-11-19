<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan & Export Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Export Laporan Keuangan</h3>
                    <p class="mb-4 text-gray-600">Download data keuangan dalam format Excel.</p>

                    <div class="flex flex-col md:flex-row gap-4">
                        <a href="{{ route('tagihan.export') }}" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Download Laporan Tagihan (Pendapatan)
                        </a>

                        <a href="{{ route('pengeluaran.export') }}" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Download Laporan Pengeluaran
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Export Data Master</h3>
                    <p class="mb-4 text-gray-600">Download data administratif.</p>

                    <a href="{{ route('penyewa.export') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Download Data Penyewa
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>