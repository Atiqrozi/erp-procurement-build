<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-5xl mx-auto">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <h1 class="text-4xl font-bold mb-6 text-center">Selamat datang di Aplikasi ERP Modul Procurement</h1>
                    <div class="flex justify-center">
                        <p class="text-3xl text-center max-w-xl mt-5">
                            Anda saat ini sedang berada di beranda divisi {{ Auth::user()->division?->name ?? 'Tidak diketahui' }} 
                            dan anda sedang login dengan role sebagai {{ Auth::user()->role ?? 'Tidak diketahui' }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>