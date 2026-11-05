<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'MotorRent')</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style type="text/tailwindcss">
        @theme {
            --color-forest: #234734;
            --color-cream: #F8F5EC;
            --color-sage: #DCE7D8;
        }
    </style>
</head>

<body class="min-h-screen bg-cream text-[#303A36]">

    <!-- Navigation -->
    <header class="border-b border-white/10 bg-forest text-cream">
        <nav class="mx-auto flex min-h-[72px] max-w-7xl items-center justify-between gap-4 px-6 py-4">

            <a href="{{ route('home') }}" class="text-2xl font-bold tracking-wide">
                MotorRent
            </a>

            <div class="flex flex-wrap items-center justify-end gap-4 text-sm sm:gap-6">

                <a href="{{ route('home') }}" class="transition hover:text-sage">
                    Home
                </a>

                <a href="{{ route('motorcycles.index') }}" class="transition hover:text-sage">
                    Motorcycles
                </a>

                @guest
                    <a href="{{ route('login') }}" class="transition hover:text-sage">
                        Log In
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-cream px-4 py-2 font-semibold text-forest transition hover:bg-sage">
                        Register
                    </a>
                @endguest

                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="transition hover:text-sage">
                            Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}"
                           class="transition hover:text-sage">
                            My Dashboard
                        </a>
                    @endif

                    <span class="text-sage">
                        Hi, {{ Auth::user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                            class="rounded-lg border border-cream/40 px-4 py-2 font-semibold transition hover:bg-white/10">
                            Log Out
                        </button>
                    </form>
                @endauth

            </div>
        </nav>
    </header>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-16 bg-forest px-6 py-6 text-center text-sm text-cream">
        © {{ date('Y') }} MotorRent. All rights reserved.
    </footer>

</body>
</html>