@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-6">

    <div class="bg-white border border-[#D8DED5] rounded-lg p-5 mb-6">
        <h1 class="text-2xl font-bold text-[#234734]">Admin Dashboard</h1>
        <p class="text-[#5C665E] mt-1">Manage motorcycles and rental records.</p>
    </div>

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

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-5">
            <p class="text-sm text-[#5C665E]">Total Motorcycles</p>
            <h2 class="text-3xl font-bold text-[#234734] mt-2">{{ $totalMotorcycles }}</h2>
        </div>

        <div class="bg-white border border-[#D8DED5] rounded-lg p-5">
            <p class="text-sm text-[#5C665E]">Available Motorcycles</p>
            <h2 class="text-3xl font-bold text-[#234734] mt-2">{{ $availableMotorcycles }}</h2>
        </div>

        <div class="bg-[#DCE7D8] border border-[#C8D6C4] rounded-lg p-5">
            <p class="text-sm text-[#5C665E]">Total Rentals</p>
            <h2 class="text-3xl font-bold text-[#234734] mt-2">{{ $totalRentals }}</h2>
        </div>
    </div>

    <!-- Motorcycle Management -->
    <div class="bg-white border border-[#D8DED5] rounded-lg p-5 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
            <div>
                <h2 class="text-lg font-bold text-[#234734]">Manage Motorcycles</h2>
                <p class="text-sm text-[#5C665E] mt-1">Add, edit, delete, and check motorcycle records.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('motorcycles.index') }}"
                   class="text-center border border-[#234734] text-[#234734] px-4 py-2 rounded-md hover:bg-[#F8F5EC]">
                    View Motorcycle Page
                </a>

                <a href="{{ route('motorcycles.create') }}"
                   class="text-center bg-[#234734] text-white px-4 py-2 rounded-md hover:bg-[#315B42]">
                    + Add Motorcycle
                </a>
            </div>
        </div>

        <table class="w-full text-sm text-left border-collapse border border-[#C8D6C4]">
            <thead class="bg-[#DCE7D8] text-[#234734]">
                <tr>
                    <th class="p-3 border border-[#C8D6C4]">Motorcycle</th>
                    <th class="p-3 border border-[#C8D6C4]">Transmission</th>
                    <th class="p-3 border border-[#C8D6C4]">Engine</th>
                    <th class="p-3 border border-[#C8D6C4]">Daily Rate</th>
                    <th class="p-3 border border-[#C8D6C4]">Availability</th>
                    <th class="p-3 border border-[#C8D6C4]">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($motorcycles as $motorcycle)
                    <tr>
                        <td class="p-3 border border-[#D8DED5] font-medium text-[#234734]">{{ $motorcycle->name }}</td>
                        <td class="p-3 border border-[#D8DED5] text-[#5C665E]">{{ $motorcycle->transmission }}</td>
                        <td class="p-3 border border-[#D8DED5] text-[#5C665E]">{{ $motorcycle->engine_cc }}cc</td>
                        <td class="p-3 border border-[#D8DED5] text-[#234734] font-medium">
                            ₱{{ number_format((float) $motorcycle->daily_rate, 2) }}
                        </td>
                        <td class="p-3 border border-[#D8DED5]">
                            <span class="inline-block text-xs px-2 py-1 rounded-full {{ $motorcycle->is_available ? 'bg-[#DCE7D8] text-[#234734]' : 'bg-gray-100 text-gray-600' }}">
                                {{ $motorcycle->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td class="p-3 border border-[#D8DED5]">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('motorcycles.edit', $motorcycle->id) }}"
                                   class="border border-[#C8D6C4] text-[#234734] px-3 py-2 rounded-md hover:bg-[#F8F5EC]">
                                    Edit
                                </a>

                                <form action="{{ route('motorcycles.destroy', $motorcycle->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this motorcycle?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="border border-red-200 text-red-700 px-3 py-2 rounded-md hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-5 text-center border border-[#D8DED5] text-[#727970]">
                            No motorcycles have been added yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Driver's License Review -->
    <div class="bg-white border border-[#D8DED5] rounded-lg p-5 mb-6">
        <h2 class="text-lg font-bold text-[#234734] mb-1">
            Driver's License Review
        </h2>

        <p class="text-sm text-[#5C665E] mb-4">
            Review the driver's license information submitted by users and verify or reject their submissions.
        </p>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse border border-[#C8D6C4]">
                <thead class="bg-[#DCE7D8] text-[#234734]">
                    <tr>
                        <th class="p-3 border border-[#C8D6C4]">User</th>
                        <th class="p-3 border border-[#C8D6C4]">Full Name on License</th>
                        <th class="p-3 border border-[#C8D6C4]">License Number</th>
                        <th class="p-3 border border-[#C8D6C4]">License Code</th>
                        <th class="p-3 border border-[#C8D6C4]">Expiration Date</th>
                        <th class="p-3 border border-[#C8D6C4]">Contact Number</th>
                        <th class="p-3 border border-[#C8D6C4]">Status</th>
                        <th class="p-3 border border-[#C8D6C4]">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($licenseUsers as $licenseUser)
                        <tr>
                            <td class="p-3 border border-[#D8DED5] font-bold text-[#234734]">
                                {{ $licenseUser->name }}
                            </td>

                            <td class="p-3 border border-[#D8DED5] text-[#5C665E]">
                                {{ $licenseUser->license_full_name }}
                            </td>

                            <td class="p-3 border border-[#D8DED5] text-[#5C665E]">
                                {{ $licenseUser->license_number }}
                            </td>

                            <td class="p-3 border border-[#D8DED5] text-[#5C665E]">
                                {{ $licenseUser->license_code }}
                            </td>

                            <td class="p-3 border border-[#D8DED5] text-[#5C665E]">
                                {{ $licenseUser->license_expiration
                                    ? \Illuminate\Support\Carbon::parse($licenseUser->license_expiration)->format('M d, Y')
                                    : 'Not set' }}
                            </td>

                            <td class="p-3 border border-[#D8DED5] text-[#5C665E]">
                                {{ $licenseUser->contact_number }}
                            </td>

                            <td class="p-3 border border-[#D8DED5]">
                                <span class="inline-block text-xs px-2 py-1 rounded-full
                                    {{ $licenseUser->license_status === 'verified'
                                        ? 'bg-[#DCE7D8] text-[#234734]'
                                        : ($licenseUser->license_status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($licenseUser->license_status ?? 'pending') }}
                                </span>
                            </td>

                            <td class="p-3 border border-[#D8DED5]">
                                @if ($licenseUser->license_status === 'pending')
                                    <div class="flex flex-col gap-2">
                                        <form action="{{ route('licenses.verify', $licenseUser->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    onclick="return confirm('Verify this driver\\'s license?')"
                                                    class="w-full bg-[#234734] text-white px-3 py-2 rounded-md hover:bg-[#315B42] text-xs">
                                                Verify
                                            </button>
                                        </form>

                                        <form action="{{ route('licenses.reject', $licenseUser->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    onclick="return confirm('Reject this driver\\'s license?')"
                                                    class="w-full border border-red-200 text-red-700 px-3 py-2 rounded-md hover:bg-red-50 text-xs">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[#727970]">No actions available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-5 text-center border border-[#D8DED5] text-[#727970]">
                                No driver's license submissions yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rental Management -->
    <div class="bg-white border border-[#D8DED5] rounded-lg p-5">
        <h2 class="text-lg font-bold text-[#234734] mb-1">Manage Rentals</h2>
        <p class="text-sm text-[#5C665E] mb-4">
            Review rental requests, payments, rental costs, deadlines, and status.
        </p>

        <table class="w-full text-sm text-left border-collapse border border-[#C8D6C4] table-fixed">
            <thead class="bg-[#DCE7D8] text-[#234734]">
                <tr>
                    <th class="p-2 border border-[#C8D6C4]">Renter</th>
                    <th class="p-2 border border-[#C8D6C4]">Motorcycle</th>
                    <th class="p-2 border border-[#C8D6C4]">Cost</th>
                    <th class="p-2 border border-[#C8D6C4]">Return Deadline</th>
                    <th class="p-2 border border-[#C8D6C4]">Status</th>
                    <th class="p-2 border border-[#C8D6C4]">Payments</th>
                    <th class="p-2 border border-[#C8D6C4]">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rentals as $rental)
                    <tr>
                        <td class="p-2 border border-[#D8DED5] text-[#5C665E] font-bold">
                            {{ $rental->user?->name ?? 'Unknown User' }}
                        </td>

                        <td class="p-2 border border-[#D8DED5] text-[#5C665E]">
                            {{ $rental->motorcycle?->name ?? 'Unknown Motorcycle' }}
                        </td>

                        <td class="p-2 border border-[#D8DED5] text-[#234734] font-medium">
                            ₱{{ number_format((float) $rental->total_cost, 2) }}
                        </td>

                        <td class="p-2 border border-[#D8DED5] text-[#5C665E]">
                            {{ $rental->return_date?->format('M d, Y') ?? 'Not set' }}
                        </td>

                        <td class="p-2 border border-[#D8DED5]">
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

                        <!-- Payment Records -->
                        <td class="p-2 border border-[#D8DED5]">
                            @if ($rental->status !== 'rejected')
                                <form action="{{ route('rentals.payments', $rental->id) }}"
                                      method="POST"
                                      class="space-y-2">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="block text-xs text-[#5C665E] mb-1">Down Payment (₱)</label>
                                        <input type="number"
                                               name="down_payment"
                                               min="0"
                                               max="{{ $rental->total_cost }}"
                                               step="0.01"
                                               value="{{ $rental->down_payment }}"
                                               required
                                               {{ $rental->status === 'returned' ? 'readonly' : '' }}
                                               class="w-full min-w-0 border border-[#C8D6C4] rounded-md px-2 py-1 {{ $rental->status === 'returned' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                                    </div>

                                    <div>
                                        <label class="block text-xs text-[#5C665E] mb-1">Deposit Amount (₱)</label>
                                        <input type="number"
                                               name="deposit_amount"
                                               min="0"
                                               step="0.01"
                                               value="{{ $rental->deposit_amount }}"
                                               required
                                               class="w-full min-w-0 border border-[#C8D6C4] rounded-md px-2 py-1">
                                    </div>

                                    <div>
                                        <label class="block text-xs text-[#5C665E] mb-1">Payment Status</label>
                                        <select name="payment_status"
                                                required
                                                {{ $rental->status === 'returned' ? 'disabled' : '' }}
                                                class="w-full min-w-0 border border-[#C8D6C4] rounded-md px-2 py-1 {{ $rental->status === 'returned' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                                            <option value="unpaid" {{ $rental->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="partial" {{ $rental->payment_status === 'partial' ? 'selected' : '' }}>Partially Paid</option>
                                            <option value="paid" {{ $rental->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>

                                        @if ($rental->status === 'returned')
                                            <input type="hidden"
                                                   name="payment_status"
                                                   value="{{ $rental->payment_status }}">
                                        @endif
                                    </div>

                                    <div>
                                        <label class="block text-xs text-[#5C665E] mb-1">Deposit Status</label>
                                        <select name="deposit_status"
                                                required
                                                class="w-full min-w-0 border border-[#C8D6C4] rounded-md px-2 py-1">
                                            <option value="unpaid" {{ $rental->deposit_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="paid" {{ $rental->deposit_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="refunded" {{ $rental->deposit_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            <option value="deducted" {{ $rental->deposit_status === 'deducted' ? 'selected' : '' }}>Deducted</option>
                                        </select>
                                    </div>

                                    <div class="border-t border-[#D8DED5] pt-2">
                                        <label class="block text-xs text-[#5C665E] mb-1">Remaining Balance</label>
                                        <p class="font-bold text-[#234734]">
                                            ₱{{ number_format(max((float) $rental->total_cost - (float) $rental->down_payment, 0), 2) }}
                                        </p>
                                    </div>

                                    <button type="submit"
                                            class="w-full bg-[#234734] text-white px-2 py-2 rounded-md hover:bg-[#315B42] text-xs">
                                        Save Payments
                                    </button>
                                </form>
                            @else
                                <span class="text-[#727970]">Not applicable</span>
                            @endif
                        </td>

                        <!-- Rental Actions -->
                        <td class="p-2 border border-[#D8DED5]">
                            @if ($rental->status === 'pending')
                                <div class="flex flex-col gap-2">

                                    <form action="{{ route('rentals.approve', $rental->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                onclick="return confirm('Approve this rental request?')"
                                                class="w-full bg-[#234734] text-white px-2 py-2 rounded-md hover:bg-[#315B42] text-xs">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('rentals.reject', $rental->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                onclick="return confirm('Reject this rental request?')"
                                                class="w-full border border-red-200 text-red-700 px-2 py-2 rounded-md hover:bg-red-50 text-xs">
                                            Reject
                                        </button>
                                    </form>
                                </div>

                            @elseif ($rental->status === 'approved')
                                <form action="{{ route('rentals.returned', $rental->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Confirm that the motorcycle has been returned?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full bg-[#234734] text-white px-2 py-2 rounded-md hover:bg-[#315B42] text-xs">
                                        Mark as Returned
                                    </button>
                                </form>

                            @elseif ($rental->status === 'returned')
                                {{-- No action needed after return --}}

                            @else
                                <span class="text-[#727970]">No actions available</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-5 text-center border border-[#D8DED5] text-[#727970]">
                            No rental records yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection