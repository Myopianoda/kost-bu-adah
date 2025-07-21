<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Detail Tagihan</h3>
                    <p>Penyewa: {{ $tagihan->sewa->penyewa->nama_lengkap }}</p>
                    <p>Unit: {{ $tagihan->sewa->unit->name }}</p>
                    <p>Jumlah: Rp {{ number_format($tagihan->jumlah) }}</p>
                    <button id="pay-button" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Lanjutkan Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Midtrans Snap --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // Ambil tombol bayar
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Panggil snap.pay dengan Snap Token
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    /* Anda bisa menangani hasil sukses di sini */
                    alert("Pembayaran sukses!"); console.log(result);
                    window.location.href = "{{ route('tagihan.index') }}";
                },
                onPending: function(result){
                    /* Anda bisa menangani hasil pending di sini */
                    alert("Menunggu pembayaran!"); console.log(result);
                    window.location.href = "{{ route('tagihan.index') }}";
                },
                onError: function(result){
                    /* Anda bisa menangani hasil error di sini */
                    alert("Pembayaran gagal!"); console.log(result);
                },
                onClose: function(){
                    /* Pop-up ditutup tanpa menyelesaikan pembayaran */
                    alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                }
            });
        });
    </script>
</x-app-layout>