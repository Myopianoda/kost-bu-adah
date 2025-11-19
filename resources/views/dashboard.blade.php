<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium">PENDAPATAN BULAN INI</h3>
                    <p class="text-3xl font-semibold mt-1">Rp {{ number_format($pendapatanBulanIni) }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium">PENGELUARAN BULAN INI</h3>
                    <p class="text-3xl font-semibold mt-1 text-red-600">Rp {{ number_format($pengeluaranBulanIni) }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium">LABA BERSIH BULAN INI</h3>
                    <p class="text-3xl font-semibold mt-1 text-green-600">Rp {{ number_format($labaBersihBulanIni) }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium">UNIT TERISI</h3>
                    <p class="text-3xl font-semibold mt-1">{{ $unitTerisi }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium">UNIT KOSONG</h3>
                    <p class="text-3xl font-semibold mt-1">{{ $unitKosong }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium">TAGIHAN BELUM DIBAYAR</h3>
                    <p class="text-3xl font-semibold mt-1">Rp {{ number_format($tagihanBelumBayar) }}</p>
                </div>

            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Grafik Pendapatan (6 Bulan Terakhir)</h3>
                    <div>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Tagihan Akan Jatuh Tempo</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyewa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($tagihanJatuhTempo as $tagihan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tagihan->sewa->penyewa->nama_lengkap }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tagihan->sewa->unit->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($tagihan->jumlah) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-red-600">{{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada tagihan yang akan jatuh tempo dalam waktu dekat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


{{-- Script untuk Chart.js (Sudah diperbaiki anti-loop) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return; // Hentikan jika elemen kanvas tidak ditemukan

        // Cek apakah sudah ada instance grafik di kanvas ini
        let existingChart = Chart.getChart(ctx);
        if (existingChart) {
            // Jika ada, hancurkan dulu sebelum membuat yang baru
            existingChart.destroy();
        }

        // Buat instance grafik yang baru
        const revenueChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Pendapatan (Rp)',
                    data: @json($chartData),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
</x-app-layout>