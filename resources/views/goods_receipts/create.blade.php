<x-app-layout>
    <div class="max-w-xl mx-auto mt-8 bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-blue-700">Form Penerimaan Barang (Goods Receipt)</h2>
        <form method="POST" action="{{ route('goods_receipts.store', $purchaseOrder) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Tanggal Terima</label>
                <input type="date" name="received_at" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Penerima</label>
                <input type="text" value="{{ Auth::user()->name }}" readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-700 cursor-not-allowed">
                <input type="hidden" name="receiver" value="{{ Auth::user()->name }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Catatan</label>
                <textarea name="notes"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            </div>

            <div class="mb-6">
                <h4 class="font-bold text-lg mb-2 text-gray-800">Barang yang Diterima</h4>
                @if($items && count($items) > 0)
                    <div class="space-y-3">
                        @foreach($items as $item)
                            <div class="flex items-center gap-4 bg-gray-50 p-3 rounded">
                                <span class="flex-1 font-medium text-gray-700">{{ $item->product->name }}</span>
                                <input type="number" name="quantities[{{ $item->product_id }}]" value="{{ $item->quantity }}"
                                    min="1" required
                                    class="w-24 border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <span class="text-gray-500">Qty</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-red-600">Tidak ada item pada PO ini.</div>
                @endif
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">Simpan</button>
        </form>
    </div>
</x-app-layout>