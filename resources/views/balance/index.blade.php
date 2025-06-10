<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Transaksi Balance
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('balance.create') }}"
                    class="mb-4 inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600">
                    + Tambah Balance
                </a>

                <div class="mb-4">
                    <div class="text-lg font-semibold">💰 Total Income: <span class="text-green-600">Rp
                            {{ number_format($totalIncome, 0, ',', '.') }}</span></div>
                    <div class="text-lg font-semibold">💸 Total Expense: <span class="text-red-600">Rp
                            {{ number_format($totalExpense, 0, ',', '.') }}</span></div>
                    <div class="text-lg font-semibold">🧮 Current Balance:
                        <span class="{{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </span>
                    </div>
                </div>


                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 text-center">
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Income</th>
                            <th class="px-4 py-2 border">Expense</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Information</th>
                            <th class="px-4 py-2 border">PO ID</th>
                            @if(Auth::user()->role === 'manager')
                                <th class="px-4 py-2 border">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($balances as $balance)
                            <tr class="text-center border-t">
                                <td class="px-4 py-2 border">{{ $balance->id }}</td>
                                <td class="px-4 py-2 border">{{ $balance->income ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $balance->expense ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $balance->status ?? 'pending' }}</td>
                                <td class="px-4 py-2 border">{{ $balance->information }}</td>
                                <td class="px-4 py-2 border">{{ $balance->purchaseOrder->id ?? '-' }}</td>
                                @if(Auth::user()->role === 'manager')
                                    <td class="px-4 py-2 border space-y-1">
                                        <form action="{{ route('balance.updateStatus', $balance->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit"
                                                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm w-full">
                                                ✅ Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('balance.updateStatus', $balance->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit"
                                                class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 text-sm w-full">
                                                ❌ Reject
                                            </button>
                                        </form>

                                        <form action="{{ route('balance.destroy', $balance->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm w-full">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'manager' ? 7 : 6 }}" class="text-center py-4">
                                    Tidak ada data balance
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>