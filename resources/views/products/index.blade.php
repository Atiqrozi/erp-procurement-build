<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Produk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600 font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-end mb-4">
                    <a href="{{ route('products.create') }}"
                        class="bg-gray-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        + Tambah Produk
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-300 text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2">Nama</th>
                                <th class="border px-4 py-2">SKU</th>
                                <th class="border px-4 py-2">Unit</th>

                                @if(Auth::user()->division_id == 3)
                                    <th class="border px-4 py-2">Deskripsi</th>
                                    <th class="border px-4 py-2">Stok</th>

                                    <th class="border px-4 py-2">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="border px-4 py-2">{{ $product->name }}</td>
                                    <td class="border px-4 py-2">{{ $product->sku }}</td>
                                    <td class="border px-4 py-2">{{ $product->unit }}</td>

                                    @if(Auth::user()->division_id == 3)
                                        <td class="border px-4 py-2">{{ $product->description }}</td>
                                        <td class="border px-4 py-2">{{ $product->stock ?? '-' }}</td>

                                        <td class="border px-4 py-2 space-x-2">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>