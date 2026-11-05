<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class MotorcycleController extends Controller
{
    // Display all motorcycles
    public function index()
    {
        $motorcycles = Motorcycle::all();

        return view('motorcycles.index', compact('motorcycles'));
    }

    // Display motorcycle details
    public function show($id)
    {
        $motorcycle = Motorcycle::findOrFail($id);

        return view('motorcycles.show', compact('motorcycle'));
    }

    // Show Add Motorcycle form
    public function create()
    {
        return view('motorcycles.create');
    }

    // Save a new motorcycle
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'transmission' => 'required|in:Automatic,Manual',
            'engine_cc' => 'required|integer|min:1',
            'daily_rate' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $image = $request->file('image');
        unset($validated['image']);

        $motorcycle = Motorcycle::create($validated);

        if ($image) {
            $directory = public_path('images');
            $filename = $motorcycle->id . '.' . $image->getClientOriginalExtension();

            $image->move($directory, $filename);
        }

        return redirect('/motorcycles')
            ->with('success', 'Motorcycle added successfully.');
    }

    // Show Edit Motorcycle form
    public function edit($id)
    {
        $motorcycle = Motorcycle::findOrFail($id);

        return view('motorcycles.edit', compact('motorcycle'));
    }

    // Update a motorcycle
    public function update(Request $request, $id)
    {
        $motorcycle = Motorcycle::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'transmission' => 'required|in:Automatic,Manual',
            'engine_cc' => 'required|integer|min:1',
            'daily_rate' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $image = $request->file('image');
        unset($validated['image']);

        $motorcycle->update($validated);

        if ($image) {
            $directory = public_path('images');

            foreach (glob($directory . '/' . $motorcycle->id . '.*') as $oldImage) {
                if (is_file($oldImage)) {
                    unlink($oldImage);
                }
            }

            $filename = $motorcycle->id . '.' . $image->getClientOriginalExtension();

            $image->move($directory, $filename);
        }

        return redirect('/motorcycles')
            ->with('success', 'Motorcycle updated successfully.');
    }

    // Submit driver's license information
    public function submitLicense(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->license_status === 'verified') {
            return back()->with('error', "Your driver's license has already been verified.");
        }

        if ($user->license_status === 'pending') {
            return back()->with('error', "Your driver's license is already waiting for admin review.");
        }

        $validated = $request->validate([
            'license_full_name' => 'required|string|max:255',
            'license_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'license_number')->ignore($user->id),
            ],
            'license_code' => 'required|string|max:30',
            'license_expiration' => 'required|date|after_or_equal:today',
            'contact_number' => 'required|string|max:30',
        ]);

        $validated['license_status'] = 'pending';

        $user->update($validated);

        return back()->with('success', 'Driver’s license information submitted. Please wait for admin verification.');
    }

    // Submit a rental request for admin review
    public function rent(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (
            $user->license_status !== 'verified' ||
            !$user->license_expiration ||
            Carbon::parse($user->license_expiration)->lt(today())
        ) {
            return redirect()
                ->route('motorcycles.show', $id)
                ->with('error', 'You need a verified and unexpired Philippine driver’s license before renting.');
        }

        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:30',
        ]);

        $rented = DB::transaction(function () use ($user, $id, $validated) {
            $motorcycle = Motorcycle::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$motorcycle->is_available) {
                return false;
            }

            $hasPendingRequest = Rental::where('motorcycle_id', $motorcycle->id)
                ->where('status', 'pending')
                ->exists();

            if ($hasPendingRequest) {
                return false;
            }

            $days = (int) $validated['days'];

            $rentalDate = now()->startOfDay();
            $returnDate = $rentalDate->copy()->addDays($days);
            $totalCost = (float) $motorcycle->daily_rate * $days;

            Rental::create([
                'user_id' => $user->id,
                'motorcycle_id' => $motorcycle->id,
                'rental_date' => $rentalDate,
                'return_date' => $returnDate,
                'total_cost' => $totalCost,
                'status' => 'pending',
            ]);

            return true;
        });

        if (!$rented) {
            return redirect()
                ->route('motorcycles.show', $id)
                ->with('error', 'This motorcycle is unavailable or already has a pending rental request.');
        }

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Rental request submitted! Please wait for admin approval.');
    }

    // Delete a motorcycle if it has no rental records
    public function destroy($id)
    {
        $motorcycle = Motorcycle::findOrFail($id);

        if ($motorcycle->rentals()->exists()) {
            return redirect('/motorcycles')
                ->with('error', 'This motorcycle cannot be deleted because it has rental records.');
        }

        $motorcycle->delete();

        return redirect('/motorcycles')
            ->with('success', 'Motorcycle deleted successfully.');
    }
}