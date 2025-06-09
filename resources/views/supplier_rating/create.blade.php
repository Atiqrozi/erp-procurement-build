<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Rating Supplier
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('supplier_rating.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="supplier_id" class="block font-medium text-sm text-gray-700">Pilih Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded">
                            <option value="">-- Pilih --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @foreach(['ketepatan', 'kualitas', 'harga', 'layanan'] as $field)
                        <div class="mb-4">
                            <label for="{{ $field }}"
                                class="block font-medium text-sm text-gray-700">{{ ucfirst($field) }}</label>
                            <select name="{{ $field }}" id="{{ $field }}" class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">-- Pilih --</option>
                                <option value="sangat baik">Sangat Baik</option>
                                <option value="baik">Baik</option>
                                <option value="kurang baik">Kurang Baik</option>
                                <option value="tidak baik">Tidak Baik</option>
                            </select>
                        </div>
                    @endforeach

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan Rating
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>