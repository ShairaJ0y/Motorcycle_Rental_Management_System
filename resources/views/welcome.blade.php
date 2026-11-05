@extends('layouts.app')

@section('title', 'Home | MotorRent')

@section('content')

@php
    $featuredMotorcycles = \App\Models\Motorcycle::where('is_available', true)
        ->take(3)
        ->get();

    $heroMotorcycle = $featuredMotorcycles->firstWhere('name', 'Honda Click 125i')
        ?? $featuredMotorcycles->first();
@endphp

{{-- Hero Section --}}
<section class="mx-auto max-w-6xl px-6 py-8">
    <div class="grid items-center gap-6 rounded-xl border border-[#D8DED5] bg-white p-6 md:grid-cols-2 md:gap-8 md:p-8">

        <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-widest text-forest">
                Your Ride, Your Journey
            </p>

            <h1 class="text-3xl font-bold leading-tight text-forest md:text-4xl">
                Find Your Next Ride
            </h1>

            <p class="mt-3 max-w-lg text-sm leading-6 text-[#5C665E] md:text-base">
                Explore our collection of motorcycles for your daily commute,
                weekend trips, and everyday adventures. Choose your ride at a
                price that fits your budget.
            </p>

            {{-- Browse Motorcycles Button Only --}}
            <div class="mt-5">
                <a href="{{ route('motorcycles.index') }}"
                   class="inline-block rounded-lg bg-forest px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#315D43]">
                    Browse Motorcycles
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center bg-white">
            @if ($heroMotorcycle && $heroMotorcycle->image_url)
                <img
                    src="{{ $heroMotorcycle->image_url }}"
                    alt="{{ $heroMotorcycle->name }} motorcycle"
                    class="h-48 w-full max-w-sm bg-white object-contain md:h-56"
                >
            @else
                <div class="flex h-48 w-full max-w-sm items-center justify-center bg-white text-sm text-[#727970] md:h-56">
                    Image unavailable
                </div>
            @endif
        </div>

    </div>
</section>

{{-- Why Choose MotorRent --}}
<section class="mx-auto max-w-6xl px-6 py-5">
    <div class="rounded-xl border border-[#C8D6C4] bg-sage p-5 md:p-6">

        <div class="mb-5 text-center">
            <h2 class="text-2xl font-bold text-forest">
                Why Choose MotorRent?
            </h2>
            <p class="mt-1 text-sm text-[#5C665E]">
                A simple and convenient way to rent a motorcycle.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-lg border border-[#E2E7DE] bg-white p-5">
                <h3 class="text-lg font-bold text-forest">Different Choices</h3>
                <p class="mt-2 text-sm leading-6 text-[#5C665E]">
                    Explore different motorcycles and find one that suits your needs.
                </p>
            </div>

            <div class="rounded-lg border border-[#E2E7DE] bg-white p-5">
                <h3 class="text-lg font-bold text-forest">Clear Daily Rates</h3>
                <p class="mt-2 text-sm leading-6 text-[#5C665E]">
                    Check the daily rental price before choosing your motorcycle.
                </p>
            </div>

            <div class="rounded-lg border border-[#E2E7DE] bg-white p-5">
                <h3 class="text-lg font-bold text-forest">Track Your Rental</h3>
                <p class="mt-2 text-sm leading-6 text-[#5C665E]">
                    View your rental cost, status, and scheduled return date.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Featured Motorcycles --}}
<section class="mx-auto max-w-6xl px-6 py-8">

    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-forest">Featured Motorcycles</h2>
            <p class="mt-1 text-sm text-[#5C665E]">
                Take a look at some of our available rides.
            </p>
        </div>

        <a href="{{ route('motorcycles.index') }}"
           class="text-sm font-semibold text-forest underline hover:text-[#315D43]">
            View All Motorcycles
        </a>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">

        @forelse ($featuredMotorcycles as $motorcycle)
            <div class="overflow-hidden rounded-xl border border-[#D8DED5] bg-white">

                <div class="flex h-44 items-center justify-center bg-white p-4">
                    @if ($motorcycle->image_url)
                        <img
                            src="{{ $motorcycle->image_url }}"
                            alt="{{ $motorcycle->name }}"
                            class="h-full w-full bg-white object-contain"
                        >
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-white text-sm text-[#727970]">
                            Image unavailable
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-forest">{{ $motorcycle->name }}</h3>

                    <p class="mt-1 text-sm text-[#727970]">
                        {{ ucfirst($motorcycle->transmission) }}
                        · {{ $motorcycle->engine_cc }}cc
                    </p>

                    <p class="mt-3 font-bold text-forest">
                        ₱{{ number_format((float) $motorcycle->daily_rate, 2) }}
                        <span class="text-sm font-normal text-[#5C665E]">/ day</span>
                    </p>

                    <a href="{{ route('motorcycles.show', $motorcycle->id) }}"
                       class="mt-4 block rounded-lg bg-forest px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-[#315D43]">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-[#D8DED5] bg-white p-8 text-center">
                <p class="text-[#5C665E]">
                    No motorcycles are currently available.
                </p>
                <a href="{{ route('motorcycles.index') }}"
                   class="mt-3 inline-block font-semibold text-forest underline">
                    Browse all motorcycles
                </a>
            </div>
        @endforelse
    </div>
</section>
@endsection