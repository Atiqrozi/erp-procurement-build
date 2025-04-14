<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Permintaan Pembelian
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('purchase-requests.store') }}">
                    @csrf

                    <div id="product-fields">
                        <div class="product-row flex items-center space-x-4 mb-4">
                            <select name="product_ids[]" required class="w-1/2 px-3 py-2 border border-gray-300 rounded">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} - ({{ $product->unit }})</option>
                                @endforeach
                            </select>
                            <input name="quantities[]" type="number" placeholder="Jumlah" required class="w-1/3 px-3 py-2 border border-gray-300 rounded">
                        </div>
                    </div>

                    <button type="button" onclick="addProductRow()" class="bg-yellow-400 hover:bg-yellow-500 text-gray px-4 py-2 rounded">
                        + Tambah Produk
                    </button>

                    <div class="mt-6">
                        <button type="submit" class="bg-gray-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                            Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addProductRow() {
            const container = document.getElementById('product-fields');
            const row = container.firstElementChild.cloneNode(true);
            row.querySelectorAll('input, select').forEach(input => input.value = '');
            container.appendChild(row);
        }
    </script>
</x-app-layout>
