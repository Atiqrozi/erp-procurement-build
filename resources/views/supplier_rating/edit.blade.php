<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Rating Supplier</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white p-6 shadow-sm rounded-lg">
            <form action="{{ route('supplier_rating.update', $supplier_rating) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block font-medium">Supplier</label>
                    <p>{{ $supplier_rating->supplier->name }}</p>
                </div>

                @foreach(['ketepatan', 'kualitas', 'harga', 'layanan'] as $field)
                    <div class="mb-4">
                        <label class="block font-medium">{{ ucfirst($field) }}</label>
                        <select name="{{ $field }}" class="block w-full border mt-1 px-2 py-1 rounded">
                            <option value="">-- Tidak diubah --</option>
                            @foreach(['sangat baik', 'baik', 'kurang baik', 'tidak baik'] as $val)
                                <option value="{{ $val }}" {{ $supplier_rating->$field === $val ? 'selected' : '' }}>
                                    {{ ucfirst($val) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>