@extends('layouts.app')

@section('title', 'Motorcycle Details | MotorRent')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-5">

    <!-- Page Heading -->
    <div class="bg-white border border-[#D8DED5] rounded-lg px-5 py-3 mb-4">
        <h1 class="text-xl font-bold text-[#234734]">Motorcycle Details</h1>
        <p class="text-sm text-[#5C665E] mt-1">
            Check the motorcycle information and rental cost.
        </p>
    </div>

    <!-- Messages -->
    @if (session('success'))
        <div class="bg-[#DCE7D8] border border-[#C8D6C4] text-[#234734] rounded-lg px-4 py-3 mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Motorcycle Card -->
    <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-4 md:p-5">
        <div class="bg-white border border-[#D8DED5] rounded-lg p-4 md:p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 items-start gap-5">

                <!-- Motorcycle Image -->
                <div class="flex h-72 w-full items-center justify-center self-start bg-white border border-[#D8DED5] rounded-lg p-2">
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

                <!-- Motorcycle Information -->
                <div class="flex flex-col">

                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs text-[#727970] mb-1">Motorcycle</p>
                            <h2 class="text-2xl font-bold text-[#234734]">
                                {{ $motorcycle->name }}
                            </h2>
                            <p class="text-sm text-[#5C665E] mt-1">
                                {{ ucfirst($motorcycle->transmission) }} Transmission
                                · {{ $motorcycle->engine_cc }}cc
                            </p>
                        </div>

                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                            {{ $motorcycle->is_available
                                ? 'text-[#234734] bg-[#DCE7D8]'
                                : 'text-gray-600 bg-gray-100' }}">
                            {{ $motorcycle->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <div class="border-t border-[#D8DED5] my-4"></div>

                    <!-- Rental Rate -->
                    <p class="text-sm text-[#5C665E]">Rental Rate</p>
                    <p class="text-3xl font-bold text-[#234734]">
                        ₱{{ number_format((float) $motorcycle->daily_rate, 2) }}
                        <span class="text-sm font-normal text-[#727970]">/ day</span>
                    </p>

                    <!-- Specifications -->
                    <div class="grid grid-cols-2 gap-2 mt-4">
                        <div class="bg-[#F8F5EC] border border-[#D8DED5] rounded-md p-3">
                            <p class="text-xs text-[#727970]">Transmission</p>
                            <p class="text-sm font-semibold text-[#234734] mt-1">
                                {{ ucfirst($motorcycle->transmission) }}
                            </p>
                        </div>

                        <div class="bg-[#F8F5EC] border border-[#D8DED5] rounded-md p-3">
                            <p class="text-xs text-[#727970]">Engine</p>
                            <p class="text-sm font-semibold text-[#234734] mt-1">
                                {{ $motorcycle->engine_cc }}cc
                            </p>
                        </div>

                        <div class="bg-[#F8F5EC] border border-[#D8DED5] rounded-md p-3">
                            <p class="text-xs text-[#727970]">Rental Period</p>
                            <p class="text-sm font-semibold text-[#234734] mt-1">
                                Per Day
                            </p>
                        </div>

                        <div class="bg-[#F8F5EC] border border-[#D8DED5] rounded-md p-3">
                            <p class="text-xs text-[#727970]">Availability</p>
                            <p class="text-sm font-semibold text-[#234734] mt-1">
                                {{ $motorcycle->is_available ? 'Available' : 'Unavailable' }}
                            </p>
                        </div>
                    </div>

                    <!-- Rental Calculator -->
                    <div class="bg-white border border-[#D8DED5] rounded-lg p-4 mt-4">
                        <h3 class="text-base font-bold text-[#234734] mb-3">
                            Estimate Rental Cost
                        </h3>

                        <label for="rentalDays"
                               class="block text-sm font-medium text-[#5C665E] mb-2">
                            Number of Days
                        </label>

                        <input
                            type="number"
                            id="rentalDays"
                            min="1"
                            max="30"
                            value="{{ old('days', 1) }}"
                            required
                            class="w-full rounded-md border border-[#C8D6C4] bg-[#F8F5EC] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                        >

                        <p id="calculatorMessage" class="hidden text-sm text-red-600 mt-2">
                            Please enter a number from 1 to 30.
                        </p>

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-[#D8DED5]">
                            <span class="text-sm text-[#5C665E]">Estimated Total</span>
                            <span id="totalCost" class="text-xl font-bold text-[#234734]">
                                ₱{{ number_format((float) $motorcycle->daily_rate, 2) }}
                            </span>
                        </div>

                        <!-- Rental Buttons -->
                        @if (!$motorcycle->is_available)

                            <button
                                type="button"
                                disabled
                                class="block w-full mt-4 bg-gray-300 text-gray-600 text-sm font-semibold py-2.5 rounded-md cursor-not-allowed"
                            >
                                Currently Unavailable
                            </button>

                        @else

                            @guest
                                <a href="{{ route('login') }}"
                                   class="block text-center w-full mt-4 bg-[#234734] hover:bg-[#183526] text-white text-sm font-semibold py-2.5 rounded-md transition">
                                    Log In to Rent
                                </a>

                                <p class="text-xs text-[#727970] text-center mt-2">
                                    Log in to continue with your rental request.
                                </p>
                            @endguest

                            @auth
                                @if (auth()->user()->role === 'admin')

                                    <a href="{{ route('admin.dashboard') }}"
                                       class="block text-center w-full mt-4 bg-[#234734] hover:bg-[#183526] text-white text-sm font-semibold py-2.5 rounded-md transition">
                                        Return to Admin Dashboard
                                    </a>

                                    <p class="text-xs text-[#727970] text-center mt-2">
                                        You are viewing this page as an administrator.
                                    </p>

                                @else

                                    @php
                                        $user = auth()->user();

                                        $licenseStatus = $user->license_status ?? 'not_submitted';

                                        $licenseExpired = !$user->license_expiration ||
                                            \Illuminate\Support\Carbon::parse($user->license_expiration)->lt(today());
                                    @endphp

                                    @if ($licenseStatus === 'verified' && !$licenseExpired)

                                        <!-- Rent Now: Verified Users Only -->
                                        <form action="{{ route('motorcycles.rent', $motorcycle->id) }}"
                                              method="POST"
                                              class="mt-4">
                                            @csrf

                                            <input
                                                type="hidden"
                                                id="rentalDaysValue"
                                                name="days"
                                                value="{{ old('days', 1) }}"
                                            >

                                            <button
                                                type="submit"
                                                id="rentButton"
                                                class="block w-full bg-[#234734] hover:bg-[#183526] text-white text-sm font-semibold py-2.5 rounded-md transition"
                                            >
                                                Rent Now
                                            </button>
                                        </form>

                                        <p class="text-xs text-[#727970] text-center mt-2">
                                            Choose 1–30 days. Your rental cost will be calculated automatically.
                                        </p>

                                    @else

                                        <!-- Driver's License Submission -->
                                        <div class="border-t border-[#D8DED5] mt-4 pt-4">

                                            @if ($licenseStatus === 'pending')

                                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                                    <p class="text-sm font-semibold text-yellow-800">
                                                        License Pending Review
                                                    </p>

                                                    <p class="text-xs text-yellow-700 mt-1">
                                                        Your license details have been submitted and are waiting for admin review. You can rent once they are approved.
                                                    </p>
                                                </div>

                                            @else

                                                @if ($licenseStatus === 'verified' && $licenseExpired)

                                                    <div class="bg-red-50 border border-red-200 rounded-md p-3 mb-3">
                                                        <p class="text-sm font-semibold text-red-700">
                                                            License Expired
                                                        </p>

                                                        <p class="text-xs text-red-600 mt-1">
                                                            Your driver's license has expired. Please provide your updated license information for review.
                                                        </p>
                                                    </div>

                                                @elseif ($licenseStatus === 'rejected')

                                                    <div class="bg-red-50 border border-red-200 rounded-md p-3 mb-3">
                                                        <p class="text-sm font-semibold text-red-700">
                                                            License Rejected
                                                        </p>

                                                        <p class="text-xs text-red-600 mt-1">
                                                            Your previous submission was rejected. Please review your details and submit them again.
                                                        </p>
                                                    </div>

                                                @else

                                                    <p class="text-xs text-[#727970] mb-3">
                                                        Submit your driver's license details for admin review. Approval is required before you can rent a motorcycle.
                                                    </p>

                                                @endif

                                                <!-- Expandable License Form -->
                                                <details class="group mt-3">

                                                    <summary class="list-none cursor-pointer inline-flex items-center justify-center bg-[#234734] hover:bg-[#183526] text-white text-sm font-semibold px-4 py-2.5 rounded-md transition">
                                                        {{ $licenseStatus === 'not_submitted'
                                                            ? 'Submit Driver\'s License'
                                                            : 'Resubmit Driver\'s License' }}

                                                        <span class="ml-2 transition-transform group-open:rotate-180">
                                                            ▼
                                                        </span>
                                                    </summary>

                                                    <div class="border border-[#D8DED5] rounded-lg p-4 mt-4 bg-[#FAFBF8]">

                                                        <h5 class="text-sm font-bold text-[#234734] mb-1">
                                                            License Information
                                                        </h5>

                                                        <p class="text-xs text-[#5C665E] mb-4">
                                                            Enter your details exactly as they appear on your driver's license.
                                                        </p>

                                                        <form action="{{ route('license.submit') }}" method="POST" class="space-y-3">
                                                            @csrf

                                                            <div>
                                                                <label for="license_full_name"
                                                                       class="block text-sm font-medium text-[#5C665E] mb-1">
                                                                    Full Name
                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    id="license_full_name"
                                                                    name="license_full_name"
                                                                    value="{{ old('license_full_name', $user->license_full_name) }}"
                                                                    placeholder="Name printed on your license"
                                                                    required
                                                                    class="w-full rounded-md border border-[#C8D6C4] bg-[#F8F5EC] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                                                                >
                                                            </div>

                                                            <div>
                                                                <label for="license_number"
                                                                       class="block text-sm font-medium text-[#5C665E] mb-1">
                                                                    Driver's License Number
                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    id="license_number"
                                                                    name="license_number"
                                                                    value="{{ old('license_number', $user->license_number) }}"
                                                                    placeholder="Enter your license number"
                                                                    required
                                                                    class="w-full rounded-md border border-[#C8D6C4] bg-[#F8F5EC] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                                                                >
                                                            </div>

                                                            <div>
                                                                <label for="license_code"
                                                                       class="block text-sm font-medium text-[#5C665E] mb-1">
                                                                    Driver's License Code
                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    id="license_code"
                                                                    name="license_code"
                                                                    value="{{ old('license_code', $user->license_code) }}"
                                                                    placeholder="Example: A, A1, B, B1, B2"
                                                                    required
                                                                    class="w-full rounded-md border border-[#C8D6C4] bg-[#F8F5EC] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                                                                >
                                                            </div>

                                                            <div>
                                                                <label for="license_expiration"
                                                                       class="block text-sm font-medium text-[#5C665E] mb-1">
                                                                    Expiration Date
                                                                </label>

                                                                <input
                                                                    type="date"
                                                                    id="license_expiration"
                                                                    name="license_expiration"
                                                                    value="{{ old('license_expiration', $user->license_expiration ? \Illuminate\Support\Carbon::parse($user->license_expiration)->format('Y-m-d') : '') }}"
                                                                    min="{{ now()->toDateString() }}"
                                                                    required
                                                                    class="w-full rounded-md border border-[#C8D6C4] bg-[#F8F5EC] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                                                                >
                                                            </div>

                                                            <div>
                                                                <label for="contact_number"
                                                                       class="block text-sm font-medium text-[#5C665E] mb-1">
                                                                    Contact Number
                                                                </label>

                                                                <input
                                                                    type="tel"
                                                                    id="contact_number"
                                                                    name="contact_number"
                                                                    value="{{ old('contact_number', $user->contact_number) }}"
                                                                    placeholder="Enter your contact number"
                                                                    required
                                                                    class="w-full rounded-md border border-[#C8D6C4] bg-[#F8F5EC] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                                                                >
                                                            </div>

                                                            <button
                                                                type="submit"
                                                                class="block w-full bg-[#234734] hover:bg-[#183526] text-white text-sm font-semibold py-2.5 rounded-md transition"
                                                            >
                                                                Submit
                                                            </button>
                                                        </form>
                                                    </div>
                                                </details>

                                            @endif
                                        </div>

                                    @endif

                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('motorcycles.index') }}"
           class="text-sm font-medium text-[#234734] hover:underline">
            ← Back to Motorcycles
        </a>
    </div>

</div>

<script>
    const rentalDays = document.getElementById('rentalDays');
    const totalCost = document.getElementById('totalCost');
    const calculatorMessage = document.getElementById('calculatorMessage');
    const rentalDaysValue = document.getElementById('rentalDaysValue');
    const dailyRate = {{ (float) $motorcycle->daily_rate }};

    function updateRentalCost() {
        const days = Number(rentalDays.value);

        if (!Number.isInteger(days) || days < 1 || days > 30) {
            calculatorMessage.classList.remove('hidden');
            totalCost.textContent = '—';

            if (rentalDaysValue) {
                rentalDaysValue.value = '';
            }

            return;
        }

        calculatorMessage.classList.add('hidden');

        const total = days * dailyRate;

        totalCost.textContent = '₱' + total.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        if (rentalDaysValue) {
            rentalDaysValue.value = days;
        }
    }

    rentalDays.addEventListener('input', updateRentalCost);
    updateRentalCost();
</script>
@endsection