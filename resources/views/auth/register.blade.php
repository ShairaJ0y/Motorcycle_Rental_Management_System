@extends('layouts.app')

@section('title', 'Register | MotorRent')

@section('content')

<section class="flex items-center justify-center px-6 py-8">

    <div class="w-full max-w-md rounded-xl border border-[#D8DED5] bg-white p-6 shadow-sm sm:p-8">

        <div class="mb-5 text-center">
            <h1 class="text-2xl font-bold text-forest">Create Account</h1>
            <p class="mt-2 text-sm text-[#727970]">
                Register to get started with MotorRent.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-[#303A36]">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    required
                    autocomplete="name"
                    class="w-full rounded-lg border border-[#D8DED5] bg-cream px-3 py-2.5 text-sm outline-none focus:border-forest"
                >
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-[#303A36]">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autocomplete="email"
                    class="w-full rounded-lg border border-[#D8DED5] bg-cream px-3 py-2.5 text-sm outline-none focus:border-forest"
                >
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-semibold text-[#303A36]">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-[#D8DED5] bg-cream px-3 py-2.5 text-sm outline-none focus:border-forest"
                >
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-[#303A36]">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-[#D8DED5] bg-cream px-3 py-2.5 text-sm outline-none focus:border-forest"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-forest px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#315D43]"
            >
                Create Account
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-[#5C665E]">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-forest underline">
                Log in here
            </a>
        </p>

    </div>

</section>
@endsection