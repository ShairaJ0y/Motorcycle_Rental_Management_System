@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    <div class="bg-white border border-[#D8DED5] rounded-lg p-6">
        <h1 class="text-2xl font-bold text-[#234734]">
            Edit Motorcycle
        </h1>

        <p class="text-[#5C665E] mt-1 mb-6">
            Update the details of {{ $motorcycle->name }}.
        </p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('motorcycles.update', $motorcycle->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4"
        >

            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-[#234734] mb-1">
                    Motorcycle Name
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $motorcycle->name) }}"
                    required
                    class="w-full border border-[#C8D6C4] rounded-md px-3 py-2"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-[#234734] mb-1">
                    Transmission
                </label>
                <select
                    name="transmission"
                    required
                    class="w-full border border-[#C8D6C4] rounded-md px-3 py-2"
                >
                    <option value="Automatic"
                        @selected(old('transmission', $motorcycle->transmission) === 'Automatic')>
                        Automatic
                    </option>
                    <option value="Manual"
                        @selected(old('transmission', $motorcycle->transmission) === 'Manual')>
                        Manual
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#234734] mb-1">
                    Engine CC
                </label>
                <input
                    type="number"
                    name="engine_cc"
                    value="{{ old('engine_cc', $motorcycle->engine_cc) }}"
                    min="1"
                    required
                    class="w-full border border-[#C8D6C4] rounded-md px-3 py-2"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-[#234734] mb-1">
                    Daily Rate (₱)
                </label>
                <input
                    type="number"
                    name="daily_rate"
                    value="{{ old('daily_rate', $motorcycle->daily_rate) }}"
                    min="0"
                    step="0.01"
                    required
                    class="w-full border border-[#C8D6C4] rounded-md px-3 py-2"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-[#234734] mb-1">
                    Availability
                </label>
                <select
                    name="is_available"
                    required
                    class="w-full border border-[#C8D6C4] rounded-md px-3 py-2"
                >
                    <option value="1"
                        @selected((string) old('is_available', (int) $motorcycle->is_available) === '1')>
                        Available
                    </option>
                    <option value="0"
                        @selected((string) old('is_available', (int) $motorcycle->is_available) === '0')>
                        Not Available
                    </option>
                </select>
            </div>

            <!-- Current Motorcycle Picture -->
            <div>
                <label class="block text-sm font-medium text-[#234734] mb-2">
                    Current Motorcycle Picture
                </label>

                @if ($motorcycle->image_url)
                    <img
                        src="{{ $motorcycle->image_url }}"
                        alt="{{ $motorcycle->name }}"
                        class="w-48 h-32 object-cover rounded-md border border-[#D8DED5] mb-3"
                    >
                @else
                    <p class="text-sm text-[#5C665E] mb-3">
                        No picture uploaded yet.
                    </p>
                @endif

                <label class="block text-sm font-medium text-[#234734] mb-1">
                    Upload New Picture
                </label>

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="w-full border border-[#C8D6C4] rounded-md px-3 py-2"
                >

                <p class="text-xs text-[#5C665E] mt-1">
                    JPG, JPEG, PNG, or WEBP. Maximum size: 5MB.
                    Leave empty to keep the current picture.
                </p>
            </div>

            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    class="bg-[#234734] text-white px-5 py-2 rounded-md hover:bg-[#315B42]"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="border border-[#C8D6C4] text-[#234734] px-5 py-2 rounded-md"
                >
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection