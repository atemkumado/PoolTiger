<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.head')
    @stack('scripts')
</head>

<body class="antialiased">
    @auth
        <header>
            <nav class="nav-container">
                <ul class="logo">
                    <li><img src="{{ asset('images/TOG Talent Pool Logo.jpg') }}" alt="logo"></li>
                </ul>
                <ul class="menu">
                    <li>
                        <div class="item">
                            <p class="menu-location font-semibold  hover:text-gray-900">HCM</p>
                            <p class="menu-statistic font-semibold">512</p>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <p class="menu-location font-semibold  hover:text-gray-900">Hanoi</p>
                            <p class="menu-statistic font-semibold">378</p>
                        </div>  
                    </li>
                    <li>
                        <div class="item">
                            <p class="menu-location font-semibold  hover:text-gray-900">Danang</p>
                            <p class="menu-statistic font-semibold">121</p>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <p class="menu-location font-semibold  hover:text-gray-900">Other</p>
                            <p class="menu-statistic font-semibold">101</p>
                        </div>
                    </li>
                </ul>
            </nav>
        </header>
    @endauth
    <div class="relative sm:flex sm:justify-center sm:items-center bg-dots-darker bg-center">
        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    {{-- <a href="{{ url('/dashboard') }}"
                        class="font-semibold  hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-gray-500">Dashboard</a>
                         --}}
                @else
                    <a href="{{ route('login') }}"
                        class="font-semibold  hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-gray-500">Log
                        in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 font-semibold  hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-gray-500">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        @auth
            @yield('content')
        @else
            <img src="{{ asset('images/Talent Pool visual.jpg') }}" alt="Image Content">
        @endauth


    </div>
</body>

</html>
