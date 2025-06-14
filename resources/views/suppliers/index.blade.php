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
                                <th class="px-4 py-2 border">Rating</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr
                                    class="text-center {{ $supplier->status === 'pending approval' ? 'text-gray-400' : '' }}">
                                    <td class="px-4 py-2 border">{{ $supplier->name }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->contact }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->email }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->address }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->products->pluck('name')->implode(', ') }}
                                    </td>
                                    <td class="px-4 py-2 border text-sm">
                                        @php
                                            $avg = $supplier->average_rating;

                                            $kategori = match (true) {
                                                $avg >= 3.5 => 'Sangat Baik',
                                                $avg >= 2.5 => 'Baik',
                                                $avg >= 1.5 => 'Kurang Baik',
                                                $avg > 0 => 'Tidak Baik',
                                                default => '-',
                                            };
                                        @endphp

                                        {{ $kategori }}
                                    </td>


                                    {{-- Kolom Status --}}
                                    <td class="px-4 py-2 border space-y-1 text-center">
                                        @if ($supplier->status === 'pending approval')
                                            <span class="text-gray-400">Menunggu Persetujuan</span>
                                        @elseif ($supplier->status === 'approved')
                                            <span class="text-green-600 font-semibold">Disetujui</span>
                                        @elseif ($supplier->status === 'blacklist')
                                            <span class="text-red-600 font-semibold">Blacklist</span>
                                        @endif

                                        {{-- Tampilkan tombol untuk manager --}}
                                        @if(auth()->user()->role === 'manager')
                                            <div class="space-x-1 mt-1">
                                                @if($supplier->status !== 'approved')
                                                    <form action="{{ route('suppliers.changeStatus', $supplier) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit"
                                                            class="text-green-600 hover:underline">Setujui</button>
                                                    </form>
                                                @endif

                                                {{-- Tombol blacklist tetap tampil untuk semua status --}}
                                                <form action="{{ route('suppliers.changeStatus', $supplier) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="blacklist">
                                                    <button type="submit" class="text-red-600 hover:underline"
                                                        onclick="return confirm('Yakin ingin mem-blacklist supplier ini? Tindakan ini tidak dapat dibatalkan.')">
                                                        Blacklist
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>


                                    {{-- Kolom Aksi --}}
                                    <td class="px-4 py-2 border space-x-2">
                                        @if ($supplier->status === 'approved')
                                            <a href="{{ route('suppliers.edit', $supplier) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline"
                                                    onclick="return confirm('Yakin ingin menghapus supplier ini?')">Hapus</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">Tidak tersedia</span>
                                        @endif
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