@php $user = auth()->user(); if(is_null($user)) return redirect('/')  @endphp
<nav x-data="{ open: false }"
     class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed z-10 w-full shadow">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="w-full mx-auto">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link
                        title="Statistics"
                        :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <x-heroicon-o-chart-bar width="25"/>
                    </x-nav-link>
                    <x-nav-link
                        title="Deals"
                        :href="route('deal')" :active="request()->routeIs('deal')">
                        <x-heroicon-o-currency-dollar width="25"/>
                    </x-nav-link>
                    <x-nav-link
                        title="Leads"
                        :href="route('leads')" :active="request()->routeIs('leads')">
                        <x-heroicon-o-user-group width="25"/>
                    </x-nav-link>
                    <x-nav-link
                        title="Campaigns"
                        :href="route('campaigns')"
                        :active="request()->routeIs('campaigns')">
                        <x-heroicon-o-megaphone width="25"/>
                    </x-nav-link>
                    <x-nav-link
                        title="Tasks"
                        :href="route('task')" :active="request()->routeIs('task')">
                        <x-heroicon-o-numbered-list width="25"/>
                    </x-nav-link>
                    @if($user->hasRole('ADMIN'))
                        <x-nav-link
                            title="Users"
                            :href="route('user')" :active="request()->routeIs('user')">
                            <x-heroicon-o-user-circle width="25"/>
                        </x-nav-link>
                    @endif
                    @if($user->hasRole('ADMIN'))
{{--                        <x-nav-link--}}
{{--                            title="Settings"--}}
{{--                            :href="route('settings')" :active="request()->routeIs('settings')">--}}
{{--                            <x-heroicon-o-adjustments-horizontal width="25"/>--}}
{{--                        </x-nav-link>--}}
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <Settings />

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
{{--    <div class="hidden sm:hidden">--}}
{{--        <div class="pt-2 pb-3 space-y-1">--}}
{{--            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">--}}
{{--                {{ __('Dashboard') }}--}}
{{--            </x-responsive-nav-link>--}}
{{--        </div>--}}

{{--        <!-- Responsive Settings Options -->--}}
{{--        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">--}}
{{--            <div class="px-4">--}}
{{--                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>--}}
{{--                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>--}}
{{--            </div>--}}

{{--            <div class="mt-3 space-y-1">--}}
{{--                <x-responsive-nav-link :href="route('profile.edit')">--}}
{{--                    {{ __('Profile') }}--}}
{{--                </x-responsive-nav-link>--}}

{{--                <!-- Authentication -->--}}
{{--                <form method="POST" action="{{ route('logout') }}">--}}
{{--                    @csrf--}}

{{--                    <x-responsive-nav-link :href="route('logout')"--}}
{{--                            onclick="event.preventDefault();--}}
{{--                                        this.closest('form').submit();">--}}
{{--                        {{ __('Log Out') }}--}}
{{--                    </x-responsive-nav-link>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</nav>

