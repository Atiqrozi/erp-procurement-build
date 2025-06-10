@php
    use App\Models\BudgetLimit;
    $activeLimit = BudgetLimit::where('active', 1)->latest('id')->first()?->limit ?? 0;
@endphp

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


                <div class="mb-4 inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded text-xl">
                    <strong>Budget Limit Aktif:</strong> Rp{{ number_format($activeLimit, 0, ',', '.') }}
                </div>


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
                            <th class="px-4 py-2 border">Supplier</th>
                            <th class="px-4 py-2 border">Price</th>
                            <th class="px-4 py-2 border">Tindakan</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            @php
                                $totalPrice = $req->items->sum(fn($item) => $item->price * $item->quantity);
                                $exceed = auth()->user()->division_id === 2 && $totalPrice > $activeLimit;
                                // Jika melebihi limit dan dari division 2, baris disable
                            @endphp
                            <tr
                                class="text-center border-t {{ $exceed ? 'bg-yellow-100' : '' }} {{ $exceed ? 'opacity-70 pointer-events-none' : '' }}">
                                <td class="px-4 py-2 border">{{ $req->id }}</td>
                                <td class="px-4 py-2 border">{{ $req->user->name }}</td>
                                <td class="px-4 py-2 border">{{ ucfirst($req->status) }}</td>

                                <td class="px-4 py-2 border">
                                    @foreach($req->items as $item)
                                        <span class="block">{{ $item->product->name }} ({{ $item->quantity }})</span>
                                    @endforeach
                                </td>

                                <td class="px-4 py-2 border">
                                    @foreach($req->items as $item)
                                        @if($item->supplierProduct && $item->supplierProduct->supplier)
                                            {{ $item->supplierProduct->supplier->name }}<br>
                                        @else
                                            <span class="text-red-500 italic">Tidak ada supplier</span><br>
                                        @endif
                                    @endforeach
                                </td>

                                <td class="px-4 py-2 border">
                                    Rp{{ number_format($totalPrice, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-2 border">
                                    @if($req->status === 'pending' && (auth()->user()->role === 'manager' || auth()->user()->division_id === 5) && !$exceed)

                                        <div class="flex gap-2 items-start">
                                            {{-- Tombol Approve --}}
                                            <form action="{{ route('purchase-requests.approve', $req->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 text-sm">Approve</button>
                                            </form>
                                            {{-- Tombol Reject --}}
                                            <form action="{{ route('purchase-requests.reject', $req->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-2 border">{{ $req->created_at->format('d-m-Y') }}</td>

                                <td class="px-4 py-2 border">
                                    @if($req->status === 'approved' && !$exceed)
                                        @if($req->purchaseOrders->isNotEmpty())
                                            <span class="text-gray-500">PO Sudah Dibuat</span>
                                        @elseif(auth()->user()->role === 'manager' || auth()->user()->division_id === 5 && auth()->user()->role === 'manager')

                                            {{-- Tombol Buat PO --}}
                                            <form action="{{ route('purchase-orders.store') }}" method="POST"
                                                onsubmit="return confirm('Buat PO sekarang?')">
                                                @csrf
                                                <input type="hidden" name="purchase_request_id" value="{{ $req->id }}">
                                                <input type="hidden" name="total_amount" value="{{ $totalPrice }}">
                                                <button type="submit"
                                                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">Buat
                                                    PO</button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    @else
                                        <span class="text-gray-500">-</span>
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