<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // show dashboard with the correct tab
    public function dashboard(Request $request): View
    {
        $allowed   = ['bookings', 'profile', 'notifications', 'favourites'];
        $activeTab = in_array($request->get('tab'), $allowed)
            ? $request->get('tab')
            : 'bookings';

        /** @var User $user */
        $user       = Auth::user();
        $bookings   = collect();
        $favourites = collect();
        $confirmedNotifications = collect();
        $paymentNotifications   = collect();


        $loyaltyPoints = $user->loyalty_points ?? 0;

        if ($activeTab === 'bookings') {
            $bookings = $user
                ->bookings()
                ->with(['barberShop', 'service', 'services', 'barber', 'review'])
                ->latest('booking_date')
                ->get();
        }

        if ($activeTab === 'favourites') {
            $favourites = $user
                ->favourites()
                ->with('barberShop')
                ->latest()
                ->get()
                ->pluck('barberShop')
                ->filter();
        }

        if ($activeTab === 'notifications') {
            $confirmedNotifications = $user
                ->bookings()
                ->with(['barberShop', 'service'])
                ->where('status', 'confirmed')
                ->latest('updated_at')
                ->take(8)
                ->get();

            $paymentNotifications = $user
                ->bookings()
                ->with(['barberShop', 'service'])
                ->where('payment_status', 'paid')
                ->latest('updated_at')
                ->take(8)
                ->get();
        }

        // Recommended shops: exclude already-favourited, order by rating
        $favouriteShopIds = $user
            ->favourites()
            ->pluck('barber_shop_id')
            ->toArray();

        $recommendedShops = \App\Models\BarberShop::where('status', 'approved')
            ->whereNotIn('id', $favouriteShopIds)
            ->withAvg('reviews', 'shop_rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_shop_rating')
            ->take(4)
            ->get();

        return view('frontend.dashboard.index', compact(
            'activeTab', 'bookings', 'favourites',
            'loyaltyPoints', 'recommendedShops',
            'confirmedNotifications', 'paymentNotifications'
        ));
    }

    // update user profile information
    public function update(Request $request)
    {
        $validated = $request->validateWithBag(
            'profileErrors',
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . Auth::id(),
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'current_password' => ['nullable', 'required_with:password,password_confirmation'],
                'password' => ['nullable', 'confirmed', Password::defaults()],
            ]
        );

        /** @var User $user */
        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        if ($request->filled('password')) {
            if (!Hash::check((string) $request->current_password, (string) $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'], 'profileErrors')
                    ->withInput();
            }

            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password']);

        $user->update($validated);

        $message = $request->filled('password')
            ? 'Profile and password updated successfully!'
            : 'Profile updated successfully!';

        return back()->with('success', $message);
    }

    // update user settings (notification preferences)
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'notify_email' => 'boolean',
            'notify_reminders' => 'boolean',
            'notify_promotions' => 'boolean',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update($validated);

        return back()->with('success', 'Settings updated successfully!');
    }

    // delete/destroy user account
    public function destroy()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}

