<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Supplier
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Supplier</h3>
                    <a href="{{ route('suppliers.create') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Supplier</a>
                </div>

                @if(session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Nama</th>
                                <th class="px-4 py-2 border">Kontak</th>
                                <th class="px-4 py-2 border">Email</th>
                                <th class="px-4 py-2 border">Alamat</th>
                                <th class="px-4 py-2 border">Produk</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr class="text-center">
                                    <td class="px-4 py-2 border">{{ $supplier->name }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->contact }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->email }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->address }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->products->pluck('name')->implode(', ') }}</td>
                                    <td class="px-4 py-2 border space-x-2">
                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                           class="text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:underline"
                                                    onclick="return confirm('Yakin ingin menghapus supplier ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
