<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Purchase Orders
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Nomor PO</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Total Amount</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Aksi</th>
                            <th class="px-4 py-2 border">Goods Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                            <tr>
                                <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border">PO-{{ $po->id }}</td>
                                <td class="px-4 py-2 border">{{ $po->created_at->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 border">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 border">
                                    @if($po->status === 'paid')
                                        <span class="text-green-600">Sudah Dibayar</span>
                                    @else
                                        <span class="text-red-600">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">
                                    @if(auth()->user()->division_id === 2 && $po->status === 'pending')
                                        <form method="POST" action="{{ route('purchase-orders.pay', $po) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Bayar</button>
                                        </form>
                                    @endif    
                                    @if($po->status === 'paid')
                                        <span class="text-black-600">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">
                                    @if($po->status === 'paid' && $po->division_id === auth()->user()->division_id)
                                        {{-- Tampilkan tombol jika PO sudah dibayar --}}
                                        @if($po->goodsReceipts && $po->goodsReceipts->count() > 0)
                                            <span>-</span>
                                        @else
                                            <a href="{{ route('goods-receipts.create', $po) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                                Goods Receipt
                                            </a>
                                        @endif
                                    @else
                                        {{-- Tidak tampilkan tombol jika PO belum dibayar --}}
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>