<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Permintaan Pembelian
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @auth
                    @if(auth()->user()->division_id == 2)
                        <a href="{{ route('purchase-requests.create') }}"
                            class="mb-4 inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Buat Permintaan
                        </a>
                    @endif
                @endauth

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Receiver</th>
                            <th class="px-4 py-2 border">Produk Diminta</th>
                            <th class="px-4 py-2 border">Notes</th>
                            <th class="px-4 py-2 border">Tanggal Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goodsReceipts as $receipt)
                            @php
                                $totalPrice = $receipt->items->sum(fn($item) => $item->price * $item->quantity);
                                $exceed = auth()->user()->division_id === 2 && $totalPrice > $activeLimit;
                                // Jika melebihi limit dan dari division 2, baris disable
                            @endphp
                            <tr
                                class="text-center border-t {{ $exceed ? 'bg-yellow-100' : '' }} {{ $exceed ? 'opacity-70 pointer-events-none' : '' }}">
                                <td class="px-4 py-2 border">{{ $receipt->id }}</td>
                                <td class="px-4 py-2 border">{{ $receipt->receiver }}</td>


                                <td class="px-4 py-2 border">
                                    @foreach($receipt->items as $item)
                                        <span class="block">{{ $item->product->name }} ({{ $item->quantity }})</span>
                                    @endforeach
                                </td>

                                <td class="px-4 py-2 border">
                                    {{ $receipt->notes }}
                                </td>

                                <td class="px-4 py-2 border">
                                    {{ \Carbon\Carbon::parse($receipt->received_at)->format('d-m-Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>