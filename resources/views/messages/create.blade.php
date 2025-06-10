<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kirim Pesan ke Divisi</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow sm:rounded-lg">
            <form method="POST" action="{{ route('messages.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Tujuan Divisi</label>
                    <select name="target_division_id" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $div)
                            @if($div->id !== auth()->user()->division_id)
                                <option value="{{ $div->id }}">{{ $div->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Isi Pesan</label>
                    <textarea name="message" rows="4" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Status (opsional)</label>
                    <input type="text" name="status"
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <button type="submit" class="bg-gray-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>