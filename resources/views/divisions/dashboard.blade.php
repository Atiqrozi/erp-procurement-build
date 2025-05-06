<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard {{ $division->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Selamat Datang di Dashboard {{ $division->name }}</h1>
                <p class="mb-6">Sistem ini digunakan untuk mengelola produk, supplier, dan permintaan pembelian untuk divisi {{ $division->name }}.</p>

                <div class="space-y-2">
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">📦 Manajemen Produk</a> <br>
                    <a href="{{ route('suppliers.index') }}" class="text-blue-600 hover:underline">🏢 Manajemen Supplier</a> <br>
                    <a href="{{ route('purchase-requests.index') }}" class="text-blue-600 hover:underline">📝 Permintaan Pembelian</a>
                    @auth
                        @if(auth()->user()->role === 'manager')
                            <a href="{{ route('purchase-orders.index') }}" class="block text-blue-600 hover:underline">📑 Manajemen Purchase Orders</a>
                        @endif
                        @if(auth()->user()->division_id === $division->id && $division->name === 'HR')
                            <a href="{{ route('roles.index') }}" class="block text-blue-600 hover:underline">👤 Manajemen Role dan Divisi</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>