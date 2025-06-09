<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Rating Supplier
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Rating Supplier</h3>
                    <a href="{{ route('supplier_rating.create') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Rating</a>
                </div>

                @if(session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Supplier</th>
                            <th class="px-4 py-2 border">Ketepatan</th>
                            <th class="px-4 py-2 border">Kualitas</th>
                            <th class="px-4 py-2 border">Harga</th>
                            <th class="px-4 py-2 border">Layanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ratings as $rating)
                            <tr class="text-center">
                                <td class="px-4 py-2 border">{{ $rating->supplier->name ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ ucfirst($rating->ketepatan) }}</td>
                                <td class="px-4 py-2 border">{{ ucfirst($rating->kualitas) }}</td>
                                <td class="px-4 py-2 border">{{ ucfirst($rating->harga) }}</td>
                                <td class="px-4 py-2 border">{{ ucfirst($rating->layanan) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 border text-center text-gray-500">Belum ada rating.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>