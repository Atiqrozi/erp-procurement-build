<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Purchase Order
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('purchase-orders.store', $purchaseRequest) }}">
                    @csrf
                    @foreach($purchaseRequest->items as $item)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ $item->product->name }}</label>
                            <select name="suppliers[{{ $item->product_id }}]" 
                                    class="supplier-select block w-full rounded-md border-gray-300 shadow-sm"
                                    data-product-id="{{ $item->product_id }}"
                                    data-quantity="{{ $item->quantity }}"> <!-- Tambahkan data-quantity -->
                                @foreach($item->product->suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" data-price="{{ $supplier->pivot->price }}">
                                        {{ $supplier->name }} - Rp {{ number_format($supplier->pivot->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                    <div class="mb-4">
                        <label for="total_amount" class="block text-sm font-medium text-gray-700">Harga Total</label>
                        <input type="number" name="total_amount" id="total_amount" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                               readonly>
                    </div>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600">Create Purchase Order</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const supplierSelects = document.querySelectorAll('.supplier-select');
            const totalAmountInput = document.getElementById('total_amount');

            function calculateTotalAmount() {
                let total = 0;

                supplierSelects.forEach(select => {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                    const quantity = parseInt(select.getAttribute('data-quantity')) || 0;
                    total += price * quantity; // Mengalikan harga dengan quantity
                });

                totalAmountInput.value = total.toFixed(2); // Format angka menjadi desimal 2
            }

            // Hitung total saat halaman dimuat
            calculateTotalAmount();

            // Hitung ulang total saat supplier berubah
            supplierSelects.forEach(select => {
                select.addEventListener('change', calculateTotalAmount);
            });
        });
    </script>
</x-app-layout>