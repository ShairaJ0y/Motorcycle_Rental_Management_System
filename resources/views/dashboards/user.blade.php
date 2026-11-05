@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-6">

    <div class="bg-white border border-[#D8DED5] rounded-lg p-5 mb-6">
        <h1 class="text-2xl font-bold text-[#234734]">
            My Dashboard
        </h1>

        <p class="text-[#5C665E] mt-1">
            Everything you need to keep track of your motorcycle rentals is right here.
        </p>
    </div>

    <!-- Rental Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-5">
            <p class="text-sm text-[#5C665E]">Total Rentals</p>
            <h2 class="text-3xl font-bold text-[#234734] mt-2">
                {{ $rentals->count() }}
            </h2>
        </div>

        <div class="bg-white border border-[#D8DED5] rounded-lg p-5">
            <p class="text-sm text-[#5C665E]">Pending Requests</p>
            <h2 class="text-3xl font-bold text-yellow-700 mt-2">
                {{ $rentals->where('status', 'pending')->count() }}
            </h2>
        </div>

        <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-5">
            <p class="text-sm text-[#5C665E]">Current Rentals</p>
            <h2 class="text-3xl font-bold text-[#234734] mt-2">
                {{ $rentals->where('status', 'approved')->count() }}
            </h2>
        </div>

        <div class="bg-white border border-[#D8DED5] rounded-lg p-5">
            <p class="text-sm text-[#5C665E]">Returned</p>
            <h2 class="text-3xl font-bold text-blue-700 mt-2">
                {{ $rentals->where('status', 'returned')->count() }}
            </h2>
        </div>

    </div>

    <!-- Driver's License -->
    @php
        $user = auth()->user();
        $licenseStatus = $user->license_status ?? 'not_submitted';

        $licenseExpired = !$user->license_expiration ||
            \Illuminate\Support\Carbon::parse($user->license_expiration)->lt(today());
    @endphp

    <div class="bg-white border border-[#D8DED5] rounded-lg px-4 py-3 mb-6">

        <div class="flex flex-wrap items-center gap-2">
            <h2 class="text-base font-bold text-[#234734]">
                My Driver's License
            </h2>

            <span class="inline-block text-xs px-2.5 py-1 rounded-full
                {{ $licenseStatus === 'verified' && !$licenseExpired
                    ? 'bg-[#DCE7D8] text-[#234734]'
                    : ($licenseStatus === 'pending'
                        ? 'bg-yellow-100 text-yellow-800'
                        : ($licenseStatus === 'rejected' || ($licenseStatus === 'verified' && $licenseExpired)
                            ? 'bg-red-100 text-red-700'
                            : 'bg-gray-100 text-gray-600')) }}">
                {{ $licenseStatus === 'not_submitted'
                    ? 'Not Submitted'
                    : ($licenseStatus === 'verified' && $licenseExpired
                        ? 'Expired'
                        : ucfirst($licenseStatus)) }}
            </span>
        </div>

        <p class="text-xs text-[#5C665E] mt-1 mb-3">
            Manage your license information and view its verification status.
        </p>

        @if ($licenseStatus === 'pending')

            <p class="text-sm text-yellow-800 bg-yellow-50 rounded-md px-3 py-2">
                Your license details are waiting for admin review. You can rent once they are approved.
            </p>

        @elseif ($licenseStatus === 'verified' && !$licenseExpired)

            <div class="bg-[#F3F6F1] rounded-md px-3 py-3">

                <p class="text-sm font-semibold text-[#234734]">
                    ✓ Your driver's license has been verified.
                </p>

                <p class="text-xs text-[#5C665E] mt-2">
                    Expiration Date
                </p>

                <p class="text-base font-bold text-[#234734]">
                    {{ \Illuminate\Support\Carbon::parse($user->license_expiration)->format('F d, Y') }}
                </p>

                <p class="text-xs text-[#5C665E] mt-1">
                    Your license remains valid for motorcycle rentals until its expiration date.
                </p>

            </div>

        @else

            @if ($licenseStatus === 'rejected')
                <p class="text-sm text-red-700 bg-red-50 rounded-md px-3 py-2 mb-3">
                    Your previous license submission was rejected. Please review your details and submit them again.
                </p>
            @elseif ($licenseStatus === 'verified' && $licenseExpired)
                <p class="text-sm text-red-700 bg-red-50 rounded-md px-3 py-2 mb-3">
                    Your driver's license has expired. Please provide your updated license information for review.
                </p>
            @else
                <p class="text-sm text-[#5C665E] mb-3">
                    Submit your driver's license details for admin review. Approval is required before you can rent a motorcycle.
                </p>
            @endif

            <details class="group">
                <summary class="list-none cursor-pointer inline-flex items-center justify-center bg-[#234734] hover:bg-[#183526] text-white text-xs font-semibold px-3 py-2 rounded-md transition">
                    {{ $licenseStatus === 'not_submitted'
                        ? 'Submit Driver\'s License'
                        : 'Resubmit Driver\'s License' }}

                    <span class="ml-2 transition-transform group-open:rotate-180">
                        ▼
                    </span>
                </summary>

                <div class="rounded-lg p-4 mt-3 bg-[#FAFBF8]">

                    <h3 class="text-sm font-bold text-[#234734] mb-1">
                        License Information
                    </h3>

                    <p class="text-xs text-[#5C665E] mb-3">
                        Enter your details exactly as they appear on your driver's license.
                    </p>

                    <form action="{{ route('license.submit') }}" method="POST" class="space-y-3">
                        @csrf

                        <div>
                            <label for="license_full_name" class="block text-sm font-medium text-[#5C665E] mb-1">
                                Full Name
                            </label>
                            <input
                                type="text"
                                id="license_full_name"
                                name="license_full_name"
                                value="{{ old('license_full_name', $user->license_full_name) }}"
                                placeholder="Name printed on your license"
                                required
                                class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                            >
                        </div>

                        <div>
                            <label for="license_number" class="block text-sm font-medium text-[#5C665E] mb-1">
                                Driver's License Number
                            </label>
                            <input
                                type="text"
                                id="license_number"
                                name="license_number"
                                value="{{ old('license_number', $user->license_number) }}"
                                placeholder="Enter your license number"
                                required
                                class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                            >
                        </div>

                        <div>
                            <label for="license_code" class="block text-sm font-medium text-[#5C665E] mb-1">
                                Driver's License Code
                            </label>
                            <input
                                type="text"
                                id="license_code"
                                name="license_code"
                                value="{{ old('license_code', $user->license_code) }}"
                                placeholder="Example: A, A1, B, B1, B2"
                                required
                                class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                            >
                        </div>

                        <div>
                            <label for="license_expiration" class="block text-sm font-medium text-[#5C665E] mb-1">
                                Expiration Date
                            </label>
                            <input
                                type="date"
                                id="license_expiration"
                                name="license_expiration"
                                value="{{ old('license_expiration', $user->license_expiration ? \Illuminate\Support\Carbon::parse($user->license_expiration)->format('Y-m-d') : '') }}"
                                min="{{ now()->toDateString() }}"
                                required
                                class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
                            >
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-[#5C665E] mb-1">
                                Contact Number
                            </label>
                            <input
                                type="tel"
                                id="contact_number"
                                name="contact_number"
                                value="{{ old('contact_number', $user->contact_number) }}"
                                placeholder="Enter your contact number"
                                required
                                class="w-full rounded-md border border-[#C8D6C4] bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#234734]"
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

    <!-- My Rental Records -->
    <div class="bg-white border border-[#D8DED5] rounded-lg p-5 mb-6">
        <h2 class="text-lg font-bold text-[#234734] mb-1">
            My Rental Records
        </h2>

        <p class="text-sm text-[#5C665E] mb-4">
            Check your rental requests, payment progress, and return dates.
        </p>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#DCE7D8] text-[#234734]">
                    <tr>
                        <th class="p-3">Motorcycle</th>
                        <th class="p-3">Rental Date</th>
                        <th class="p-3">Return Deadline</th>
                        <th class="p-3">Total Cost</th>
                        <th class="p-3">Amount Paid</th>
                        <th class="p-3">Remaining Balance</th>
                        <th class="p-3">Payment Status</th>
                        <th class="p-3">Rental Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rentals as $rental)
                        @php
                            $totalCost = (float) $rental->total_cost;
                            $amountPaid = (float) $rental->down_payment;
                            $remainingBalance = max($totalCost - $amountPaid, 0);
                            $paymentStatus = $rental->payment_status ?? 'unpaid';
                        @endphp

                        <tr class="border-t border-[#D8DED5]">

                            <td class="p-3 text-[#234734] font-medium">
                                {{ $rental->motorcycle?->name ?? 'Unknown Motorcycle' }}
                            </td>

                            <td class="p-3 text-[#5C665E]">
                                {{ $rental->rental_date?->format('M d, Y') ?? 'Not set' }}
                            </td>

                            <td class="p-3 text-[#5C665E]">
                                {{ $rental->return_date?->format('M d, Y') ?? 'Not set' }}
                            </td>

                            <td class="p-3 text-[#234734] font-medium whitespace-nowrap">
                                ₱{{ number_format($totalCost, 2) }}
                            </td>

                            <td class="p-3 text-[#234734] whitespace-nowrap">
                                ₱{{ number_format($amountPaid, 2) }}
                            </td>

                            <td class="p-3 font-bold whitespace-nowrap {{ $remainingBalance > 0 ? 'text-red-700' : 'text-[#234734]' }}">
                                ₱{{ number_format($remainingBalance, 2) }}
                            </td>

                            <td class="p-3">
                                <span class="inline-block text-xs px-2 py-1 rounded-full
                                    {{ $paymentStatus === 'paid'
                                        ? 'bg-[#DCE7D8] text-[#234734]'
                                        : ($paymentStatus === 'partial'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-700') }}">
                                    {{ $paymentStatus === 'partial'
                                        ? 'Partially Paid'
                                        : ucfirst($paymentStatus) }}
                                </span>
                            </td>

                            <td class="p-3">
                                <span class="inline-block text-xs px-2 py-1 rounded-full
                                    {{ $rental->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($rental->status === 'approved'
                                            ? 'bg-[#DCE7D8] text-[#234734]'
                                            : ($rental->status === 'returned'
                                                ? 'bg-blue-100 text-blue-700'
                                                : 'bg-red-100 text-red-700')) }}">
                                    {{ ucfirst($rental->status ?? 'pending') }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-5 text-center text-[#727970]">
                                You have no rental records yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Browse Motorcycles -->
    <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-5">
        <h2 class="text-lg font-bold text-[#234734] mb-1">
            Looking for a Motorcycle?
        </h2>

        <p class="text-sm text-[#5C665E] mb-4">
            Browse available motorcycles and find your next ride.
        </p>

        <a href="{{ route('motorcycles.index') }}"
           class="inline-block bg-[#234734] text-white px-4 py-2 rounded-md hover:bg-[#315B42]">
            Browse Motorcycles
        </a>
    </div>

</div>
@endsection