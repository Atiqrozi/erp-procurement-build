<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Produk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Nama Produk">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">SKU (Kode Produk)</label>
                        <input name="sku" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="SKU">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Satuan</label>
                        <input name="unit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="pcs, kg, dst">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Harga</label>
                        <input name="price" required type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Harga">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Deskripsi Produk"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-gray-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
