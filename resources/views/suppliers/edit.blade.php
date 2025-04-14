<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Supplier
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input name="name" value="{{ $supplier->name }}" required
                            class="w-full mt-1 rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kontak</label>
                        <input name="contact" value="{{ $supplier->contact }}" required
                            class="w-full mt-1 rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ $supplier->email }}"
                            class="w-full mt-1 rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address"
                            class="w-full mt-1 rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $supplier->address }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Produk yang Disuplai</label>
                        <div class="mt-2 grid grid-cols-2 gap-4">
                            @foreach($products as $product)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                        {{ $supplier->products->contains($product->id) ? 'checked' : '' }}
                                        class="rounded border-gray-300 shadow-sm text-blue-600 focus:ring-blue-500">
                                    <span>{{ $product->name }}</span>
                                    <input type="number" name="product_prices[{{ $product->id }}]" 
                                        value="{{ $supplier->products->contains($product->id) ? $supplier->products->find($product->id)->pivot->price : '' }}"
                                        placeholder="Harga"
                                        class="ml-2 w-1/2 rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
