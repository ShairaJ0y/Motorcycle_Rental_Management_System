<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    // Display the admin dashboard
    public function index()
    {
        $totalMotorcycles = Motorcycle::count();

        $availableMotorcycles = Motorcycle::where(
            'is_available',
            true
        )->count();

        $totalRentals = Rental::count();

        $motorcycles = Motorcycle::latest()->get();

        $rentals = Rental::with(['user', 'motorcycle'])
            ->latest()
            ->get();

        $licenseUsers = User::whereIn('license_status', [
            'pending',
            'verified',
            'rejected',
        ])->latest()->get();

        return view('dashboards.admin', compact(
            'totalMotorcycles',
            'availableMotorcycles',
            'totalRentals',
            'motorcycles',
            'rentals',
            'licenseUsers'
        ));
    }

    // Verify a user's driver's license
    public function verifyLicense($id)
    {
        $user = User::findOrFail($id);

        if ($user->license_status !== 'pending') {
            return back()->with('error', 'This license submission is not waiting for review.');
        }

        if (
            !$user->license_expiration ||
            Carbon::parse($user->license_expiration)->lt(today())
        ) {
            return back()->with('error', "This driver's license has expired and cannot be verified.");
        }

        $user->update([
            'license_status' => 'verified',
        ]);

        return back()->with('success', 'Driver license verified successfully.');
    }

    // Reject a user's driver's license
    public function rejectLicense($id)
    {
        $user = User::findOrFail($id);

        if ($user->license_status !== 'pending') {
            return back()->with('error', 'This license submission is not waiting for review.');
        }

        $user->update([
            'license_status' => 'rejected',
        ]);

        return back()->with('success', 'Driver license submission rejected.');
    }

    // Update down payment and deposit records
    public function updatePayments(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);

        $validated = $request->validate([
            'down_payment' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'deposit_status' => 'required|in:unpaid,paid,refunded,deducted',
        ]);

        $rental->update($validated);

        return back()->with(
            'success',
            'Rental payment records updated successfully.'
        );
    }

    // Approve a rental request
    public function approve($id)
    {
        $result = DB::transaction(function () use ($id) {
            $rental = Rental::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($rental->status !== 'pending') {
                return 'processed';
            }

            $user = User::findOrFail($rental->user_id);

            if (
                $user->license_status !== 'verified' ||
                !$user->license_expiration ||
                Carbon::parse($user->license_expiration)->lt(today())
            ) {
                return 'license_invalid';
            }

            $motorcycle = Motorcycle::whereKey($rental->motorcycle_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$motorcycle->is_available) {
                $rental->update(['status' => 'rejected']);

                return 'unavailable';
            }

            $rental->update(['status' => 'approved']);

            $motorcycle->update(['is_available' => false]);

            return 'approved';
        });

        if ($result === 'processed') {
            return back()->with(
                'error',
                'This rental request has already been processed.'
            );
        }

        if ($result === 'license_invalid') {
            return back()->with(
                'error',
                'This renter does not have a verified and unexpired driver’s license.'
            );
        }

        if ($result === 'unavailable') {
            return back()->with(
                'error',
                'The motorcycle is unavailable. The request was rejected.'
            );
        }

        return back()->with(
            'success',
            'Rental request approved successfully.'
        );
    }

    // Reject a rental request
    public function reject($id)
    {
        $rental = Rental::findOrFail($id);

        if ($rental->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending rental requests can be rejected.'
            );
        }

        $rental->update(['status' => 'rejected']);

        return back()->with(
            'success',
            'Rental request rejected.'
        );
    }

    // Mark an approved rental as returned
    public function markReturned($id)
    {
        $result = DB::transaction(function () use ($id) {
            $rental = Rental::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($rental->status !== 'approved') {
                return false;
            }

            $motorcycle = Motorcycle::whereKey($rental->motorcycle_id)
                ->lockForUpdate()
                ->firstOrFail();

            $rental->update([
                'status' => 'returned',
            ]);

            $motorcycle->update([
                'is_available' => true,
            ]);

            return true;
        });

        if (!$result) {
            return back()->with(
                'error',
                'Only approved rentals can be marked as returned.'
            );
        }

        return back()->with(
            'success',
            'Motorcycle returned successfully. It is now available for rent.'
        );
    }
}