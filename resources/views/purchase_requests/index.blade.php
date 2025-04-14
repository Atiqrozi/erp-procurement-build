<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Permintaan Pembelian
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('purchase-requests.create') }}" class="mb-4 inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Buat Permintaan
                </a>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">User</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Produk Diminta</th>
                            <th class="px-4 py-2 border">Tindakan</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            <tr class="text-center border-t">
                                <td class="px-4 py-2 border">{{ $req->id }}</td>
                                <td class="px-4 py-2 border">{{ $req->user->name }}</td>
                                <td class="px-4 py-2 border">{{ $req->status }}</td>
                                <td class="px-4 py-2 border">
                                    @foreach($req->items as $item)
                                        <span class="block">{{ $item->product->name }} ({{ $item->quantity }})</span>
                                    @endforeach
                                </td>

                                <td class="px-4 py-2 border">
                                    @if(auth()->user()->role === 'admin' && $req->status === 'pending')
                                        <form method="POST" action="{{ route('purchase-requests.approve', $req) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-600 hover:underline">✔️</button>
                                        </form>
                                        <form method="POST" action="{{ route('purchase-requests.reject', $req) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-red-600 hover:underline">❌</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">{{ $req->created_at->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 border">
                                    @if($req->status === 'approved')
                                        @if($req->purchaseOrders->isNotEmpty())
                                            <span class="text-gray-500">PO Sudah Dibuat</span>
                                        @else
                                            <a href="{{ route('purchase-orders.create', $req) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                Buat PO
                                            </a>
                                        @endif
                                    @else
                                        -
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
