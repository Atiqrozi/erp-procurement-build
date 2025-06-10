<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            Tambah Budget Limit
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('budget_limits.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="limit" class="block text-gray-700">Limit</label>
                        <input type="number" step="any" name="limit" id="limit" class="w-full border rounded px-3 py-2"
                            required>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>