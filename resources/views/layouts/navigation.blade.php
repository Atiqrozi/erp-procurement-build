<!-- Navigation + Sidebar -->
@php use Illuminate\Support\Str; @endphp

<div x-data="{ sidebarOpen: false }" class="relative z-50">
    <!-- Top Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <!-- Hamburger button -->
            <button @click="sidebarOpen = true" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            

            <a class="capitalize text-gray-700 dark:text-gray-200">
                
            @php
use App\Models\Division;


$path = request()->path();
$displayName = 'Dashboard';
$division = null;

if (Str::startsWith($path, 'divisions/')) {
    $id = request()->segment(2);
    $division = Division::find($id);
    $displayName = $division?->name ?? 'Unknown Division';
} elseif ($path !== '/') {
    $displayName = ucfirst(last(explode('/', $path)));
}
@endphp




{{-- Output ke konsol browser --}}
<script>
    console.log("Display Name: {{ addslashes($displayName) }}");
    const division = @json($division);
    console.log("Division:", division);
</script>


            <a class="capitalize text-gray-700 dark:text-gray-200">
                {{ $displayName }}
            </a>
                    
        </div>

        <!-- Right side: Auth / Profile -->
        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- If the user is logged in, show the user's name -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- If the user is not logged in, show login and register links -->
                    <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Login') }}
                    </x-nav-link>
                    <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-nav-link>
                @endauth
            </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black bg-opacity-40" @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Sidebar Panel -->
    <aside x-show="sidebarOpen"
           x-transition:enter="transition transform duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition transform duration-300"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-900 shadow-lg z-50 overflow-y-auto">

        <div class="p-4 flex justify-between items-center border-b dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Menu</h2>
            <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('dashboard') }}"
                class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
        
            @auth
                @if(Auth::user()->division_id === 2)
                    <a href="{{ route('products.index') }}"
                        class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Produk</a>
                    <a href="{{ route('purchase-requests.index') }}"
                        class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Pembelian</a>
                @endif

                @if(Auth::user()->division_id === 4)
                    <a href="{{ route('suppliers.index') }}"
                        class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Suppliers</a>
                @endif

                @if(Auth::user()->division_id === 4)
                    <a href="{{ route('supplier_rating.index') }}"
                        class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Rating</a>
                @endif

                @if(Auth::user()->division_id === 4 && Auth::user()->role === 'manager')
                    <a href="{{ route('blacklist.index') }}"
                        class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Blacklist
                    </a>
                @endif


                @if(Auth::user()->division_id === 1 && Auth::user()->role === 'manager')
                    <a href="{{ route('purchase-orders.index') }}"
                        class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Manajemen Order
                    </a>
                @endif

                @if(Auth::user()->division_id === 1 && Auth::user()->role === 'manager')
                    <a href="{{ route('roles.index') }}"
                        class="block px-4 py-2 rounded text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Role dan Divisi
                    </a>
                @endif
            @endauth
        </nav>

    </aside>
</div>
