@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-6">

    <!-- Page Heading -->
    <div class="bg-white border border-[#D8DED5] rounded-lg p-5 mb-6">
        <h1 class="text-2xl font-bold text-[#234734]">
            Explore Our Collection
        </h1>
        <p class="text-[#5C665E] mt-1">
            Find Your Next Ride
        </p>
    </div>

    <!-- Messages -->
    @if (session('success'))
        <div class="bg-[#DCE7D8] border border-[#C8D6C4] text-[#234734] rounded-md p-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label for="search" class="block text-sm font-medium text-[#234734] mb-2">
                    Search Motorcycle
                </label>
                <input type="text" id="search"
                       placeholder="Enter motorcycle name..."
                       class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm">
            </div>

            <div>
                <label for="transmission" class="block text-sm font-medium text-[#234734] mb-2">
                    Transmission
                </label>
                <select id="transmission"
                        class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm">
                    <option value="">All Types</option>
                    <option value="automatic">Automatic</option>
                    <option value="manual">Manual</option>
                </select>
            </div>

            <div>
                <label for="availability" class="block text-sm font-medium text-[#234734] mb-2">
                    Availability
                </label>
                <select id="availability"
                        class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm">
                    <option value="">All Motorcycles</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Motorcycle Listings -->
    <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-5">

        <div class="mb-5">
            <h2 class="text-xl font-bold text-[#234734]">
                Our Motorcycles
            </h2>
            <p class="text-sm text-[#5C665E] mt-1">
                <span id="resultCount">{{ $motorcycles->count() }}</span>
                motorcycles found
            </p>
        </div>

        <div id="motorcycleList"
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @forelse ($motorcycles as $motorcycle)

                <div class="motorcycle-card bg-white border border-[#D8DED5] rounded-lg overflow-hidden"
                     data-name="{{ strtolower($motorcycle->name) }}"
                     data-transmission="{{ strtolower($motorcycle->transmission) }}"
                     data-availability="{{ $motorcycle->is_available ? 'available' : 'unavailable' }}">

                    <!-- Motorcycle Image -->
                    <div class="h-48 bg-white border-b border-[#D8DED5] p-2">
                        @if ($motorcycle->image_url)
                            <img
                                src="{{ $motorcycle->image_url }}"
                                alt="{{ $motorcycle->name }}"
                                class="w-full h-full object-contain"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center text-sm text-[#727970]">
                                Image unavailable
                            </div>
                        @endif
                    </div>

                    <!-- Motorcycle Details -->
                    <div class="p-4">

                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-lg font-bold text-[#234734]">
                                {{ $motorcycle->name }}
                            </h3>

                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $motorcycle->is_available
                                    ? 'bg-[#DCE7D8] text-[#234734]'
                                    : 'bg-gray-100 text-gray-600' }}">
                                {{ $motorcycle->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>

                        <p class="text-sm text-[#5C665E] mt-2">
                            {{ $motorcycle->transmission }}
                            · {{ $motorcycle->engine_cc }}cc
                        </p>

                        <p class="text-lg font-bold text-[#234734] mt-4">
                            ₱{{ number_format((float) $motorcycle->daily_rate, 2) }}
                            <span class="text-xs font-normal text-[#727970]">/ day</span>
                        </p>

                        <!-- View Details Only -->
                        <div class="mt-4">
                            <a href="{{ route('motorcycles.show', $motorcycle->id) }}"
                               class="inline-block bg-[#234734] text-white text-sm px-4 py-2 rounded-md hover:bg-[#315B42]">
                                View Details
                            </a>
                        </div>

                    </div>
                </div>

            @empty
                <div class="col-span-full bg-white border border-[#D8DED5] rounded-lg p-8 text-center">
                    <p class="text-[#5C665E]">
                        No motorcycles have been added yet.
                    </p>
                </div>
            @endforelse

        </div>

        <p id="noResults"
           class="hidden bg-white rounded-lg p-6 text-center text-[#5C665E] mt-4">
            No motorcycles match your search.
        </p>

    </div>
</div>

<script>
    const searchInput = document.getElementById('search');
    const transmissionFilter = document.getElementById('transmission');
    const availabilityFilter = document.getElementById('availability');
    const cards = document.querySelectorAll('.motorcycle-card');
    const resultCount = document.getElementById('resultCount');
    const noResults = document.getElementById('noResults');

    function filterMotorcycles() {
        const search = searchInput.value.toLowerCase().trim();
        const transmission = transmissionFilter.value;
        const availability = availabilityFilter.value;

        let count = 0;

        cards.forEach(card => {
            const matchesName = card.dataset.name.includes(search);
            const matchesTransmission =
                !transmission || card.dataset.transmission === transmission;
            const matchesAvailability =
                !availability || card.dataset.availability === availability;

            const visible =
                matchesName && matchesTransmission && matchesAvailability;

            card.style.display = visible ? '' : 'none';

            if (visible) count++;
        });

        resultCount.textContent = count;
        noResults.classList.toggle('hidden', count !== 0);
    }

    searchInput.addEventListener('input', filterMotorcycles);
    transmissionFilter.addEventListener('change', filterMotorcycles);
    availabilityFilter.addEventListener('change', filterMotorcycles);
</script>
@endsection