<x-app-layout>
    <form method="POST" action="{{ route('goods-receipts.store', $purchaseOrder) }}">
        @csrf
        <label>Tanggal Terima</label>
        <input type="date" name="received_at" required>
        <label>Penerima</label>
        <input type="text" name="receiver" required>
        <label>Catatan</label>
        <textarea name="notes"></textarea>
        <h4 class="mt-4 font-bold">Barang yang Diterima</h4>
        @foreach($items as $item)
            <div class="mb-2">
                <span>{{ $item->product->name }}</span>
                <input type="number" name="quantities[{{ $item->product_id }}]" value="{{ $item->quantity }}" min="1" required>
            </div>
        @endforeach
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</x-app-layout>