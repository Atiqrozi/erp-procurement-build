<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Budget Limit
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(Auth::user()->role === 'manager' || Auth::user()->role === 'user')
                    <a href="{{ route('budget_limits.create') }}"
                        class="mb-4 inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-600">
                        + Tambah Limit
                    </a>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 text-center">
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Limit</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Active</th>
                            @if(Auth::user()->role === 'manager')
                                <th class="px-4 py-2 border">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr
                                class="text-center border-t {{ in_array($budget->status, ['pending', 'rejected']) ? 'bg-gray-100 text-gray-500' : '' }}">

                                <td class="px-4 py-2 border">{{ $budget->id }}</td>
                                <td class="px-4 py-2 border">{{ $budget->limit }}</td>
                                <td class="px-4 py-2 border">{{ $budget->status }}</td>
                                <td class="px-4 py-2 border">
                                    <form action="{{ route('budget_limits.toggle-active', $budget->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox"
                                            onchange="this.form.submit()"
                                            name="active"
                                            value="1"
                                                    {{ $budget->active ? 'checked' : '' }}
                                                {{ in_array($budget->status, ['pending', 'rejected']) ? 'disabled' : '' }}>

                                    </form>
                                </td>
                                @if(Auth::user()->role === 'manager')
                                    <td class="px-4 py-2 border space-y-1">
                                        <form action="{{ route('budget_limits.destroy', $budget->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm w-full">
                                                Delete
                                            </button>
                                        </form>

                                        {{-- Tombol Setujui --}}
                                        <form action="{{ route('budget_limits.update-status', [$budget->id, 'approved']) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm w-full">
                                                Approve
                                            </button>
                                        </form>

                                        {{-- Tombol Tolak --}}
                                        <form action="{{ route('budget_limits.update-status', [$budget->id, 'rejected']) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm w-full">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                @endif

                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'manager' ? 5 : 4 }}" class="text-center py-4">
                                    Tidak ada data budget limit.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>