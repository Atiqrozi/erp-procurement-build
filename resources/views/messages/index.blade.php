<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Pesan</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            @auth
                <a href="{{ route('messages.create') }}"
                    class="inline-block mb-4 bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Kirim Pesan
                </a>
            @endauth

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <table class="min-w-full table-auto border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-center">
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Dari Divisi</th>
                        <th class="px-4 py-2 border">Pesan</th>
                        <th class="px-4 py-2 border">Waktu</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                        <tr class="text-center border-t {{ $msg->is_read ? '' : 'bg-yellow-50 font-semibold' }}">
                            <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border">{{ $msg->senderDivision->name }}</td>
                            <td class="px-4 py-2 border">{{ $msg->message }}</td>
                            <td class="px-4 py-2 border">{{ $msg->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-2 border">
                                @if(!$msg->is_read && auth()->user()->division_id !== $msg->senderDivision->id)
                                    <form action="{{ route('messages.markAsRead', $msg) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                            Tandai Sudah Dibaca
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500">–</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>