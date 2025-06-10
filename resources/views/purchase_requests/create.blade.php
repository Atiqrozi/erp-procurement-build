@php
    use App\Models\BudgetLimit;
    $activeLimit = BudgetLimit::where('active', 1)->latest('id')->first()?->limit ?? 0;
@endphp

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

                    <div id="product-container">
                        <div class="product-row flex items-center space-x-4 mb-4">
                            <select name="product_ids[]" required
                                class="w-1/4 px-3 py-2 border border-gray-300 rounded product-select">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} - ({{ $product->unit }})
                                    </option>
                                @endforeach
                            </select>

                            <select name="supplier_products_ids[]" required
                                class="w-1/4 px-3 py-2 border border-gray-300 rounded supplier-select">
                                <option value="">Pilih Supplier</option>
                                @foreach($supplierProducts as $sp)
                                    <option value="{{ $sp->id }}" data-product-id="{{ $sp->product_id }}"
                                        data-price="{{ $sp->price }}">
                                        {{ $sp->supplier->name }} - ({{ $sp->product->name }})
                                    </option>
                                @endforeach
                            </select>

                            <input name="quantities[]" type="number" placeholder="Jumlah" required
                                class="w-1/4 px-3 py-2 border border-gray-300 rounded quantity-input">

                            <span class="subtotal w-1/4 text-gray-700">Rp 0</span>
                        </div>
                    </div>

                    <button type="button" onclick="addProductRow()"
                        class="bg-yellow-400 hover:bg-yellow-500 text-gray px-4 py-2 rounded mb-4">
                        + Tambah Produk
                    </button>

                    <div class="mt-4 text-right text-lg font-semibold text-gray-700">
                        Total Harga: <span id="total-price">Rp 0</span>
                    </div>

                    <div class="mt-4 text-right text-lg font-semibold text-gray-700">
                        <strong>Budget Limit Aktif:</strong> Rp{{ number_format($activeLimit, 0, ',', '.') }}
                    </div>


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
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
        }

        function updateSubtotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const supplierSelect = row.querySelector('.supplier-select');
            const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const subtotal = quantity * price;
            row.querySelector('.subtotal').textContent = formatRupiah(subtotal);
            updateTotalPrice();
        }

        function updateTotalPrice() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const supplierSelect = row.querySelector('.supplier-select');
                const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
                const price = parseFloat(selectedOption?.dataset?.price) || 0;
                total += quantity * price;
            });
            document.getElementById('total-price').textContent = formatRupiah(total);
        }

        document.addEventListener('change', function (e) {
            const row = e.target.closest('.product-row');

            if (e.target.classList.contains('product-select')) {
                const productId = e.target.value;
                const supplierSelect = row.querySelector('.supplier-select');
                const allOptions = supplierSelect.querySelectorAll('option');

                supplierSelect.value = '';
                allOptions.forEach(option => {
                    option.style.display = !option.value || option.dataset.productId === productId ? 'block' : 'none';
                });
            }

            if (e.target.classList.contains('supplier-select') || e.target.classList.contains('quantity-input')) {
                updateSubtotal(row);
            }
        });

        function addProductRow() {
            const container = document.querySelector('.product-row').parentNode;
            const firstRow = document.querySelector('.product-row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(input => input.value = '');
            newRow.querySelector('.subtotal').textContent = 'Rp 0';
            container.appendChild(newRow);
        }
    </script>


</x-app-layout>