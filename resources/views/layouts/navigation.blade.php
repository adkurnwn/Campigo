<nav class="bg-white sticky top-0 z-50 flex justify-between items-center py-6 px-10">

    <x-nav-link :href="route('home')">
        <h1 class="text-3xl font-extrabold text-teal-700">Campigo</h1>
    </x-nav-link>

    <ul id="nav-links" class="hidden lg:flex space-x-6 text-gray-700 font-semibold">
        <x-nav-link :href="route('home')">Home</a></x-nav-link>
        <x-nav-link :href="route('home')">Products</a></x-nav-link>
        <x-nav-link :href="route('home')">Contacts</a></x-nav-link>
    </ul>

    @auth

        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button>
                        <div class="ms-1">
                            <svg width="48" 
                                 height="48" 
                                 viewBox="0 0 24 24" 
                                 stroke="#000000" 
                                 stroke-width="2" 
                                 stroke-linecap="round" 
                                 stroke-linejoin="round" 
                                 fill="none" class="fill-current h-4 w-4" 
                                 xmlns="http://www.w3.org/2000/svg">
                                <!-- Cart Body -->
                                <path d="M6 6h15l-1.5 9H7.5L6 6z"/>
                                <!-- Cart Wheels -->
                                <circle cx="9" cy="20" r="2"/>
                                <circle cx="18" cy="20" r="2"/>
                                <!-- Handle of the Cart -->
                                <path d="M6 6L4 4H2"/>
                            </svg>
                        </div>
                        
                    </button>

                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div class="ms-1">
                            <svg width="48" 
                            height="48" 
                            viewBox="0 0 24 24" 
                            stroke="#000000" 
                            stroke-width="2" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            fill="none" class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <circle cx="12" cy="8" r="5" />
                            <path d="M3,21 h18 C 21,12 3,12 3,21"/>
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        <div>{{ Auth::user()->name }}</div>
                    </x-dropdown-link>

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
        </div>
    @else
        <a href="{{ route('register') }}"
            class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
            Book Now
        </a>
    @endauth

    </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">

            @auth

                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
