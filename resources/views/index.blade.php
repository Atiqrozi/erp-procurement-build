<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6 text-center">Pilih Divisi</h1>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($divisions as $division)
                            <a href="{{ route('divisions.show', $division) }}" 
                               class="block bg-gray-100 hover:bg-gray-200 text-center py-6 rounded-lg shadow-md">
                                <span class="text-lg font-semibold text-gray-800">{{ $division->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>