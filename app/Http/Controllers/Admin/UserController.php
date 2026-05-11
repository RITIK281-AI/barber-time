<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // show all customers with search and filter
    public function index(Request $request)
    {
        $filteredBaseQuery = User::where('role', 'user');

        if ($search = $request->input('search')) {
            $filteredBaseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $summary = [
            'total_customers' => (clone $filteredBaseQuery)->count(),
            'with_completed_bookings' => (clone $filteredBaseQuery)
                ->whereHas('bookings', fn($q) => $q->where('status', 'completed'))
                ->count(),
            'with_unpaid_fines' => (clone $filteredBaseQuery)
                ->whereHas('bookings', fn($q) => $q
                    ->where('cancellation_fine', '>', 0)
                    ->where('fine_paid', false)
                )
                ->count(),
            'total_loyalty_points' => (clone $filteredBaseQuery)->sum('loyalty_points'),
        ];

        $users = (clone $filteredBaseQuery)
            ->withCount([
                'bookings',
                'bookings as completed_bookings_count' => fn($q) => $q->where('status', 'completed'),
                'bookings as unpaid_fines_count' => fn($q) => $q
                    ->where('cancellation_fine', '>', 0)
                    ->where('fine_paid', false),
            ])
            ->withSum([
                'bookings as unpaid_fine_amount' => fn($q) => $q
                    ->where('cancellation_fine', '>', 0)
                    ->where('fine_paid', false),
            ], 'cancellation_fine')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users', 'summary'));
    }

    // show single user details
    public function show(User $user)
    {
        abort_unless($user->role === 'user', 404);

        $user->loadCount([
            'bookings',
            'bookings as completed_bookings_count' => fn($q) => $q->where('status', 'completed'),
            'bookings as cancelled_bookings_count' => fn($q) => $q->where('status', 'cancelled'),
            'bookings as no_show_bookings_count' => fn($q) => $q->where('status', 'no_show'),
            'bookings as unpaid_fines_count' => fn($q) => $q
                ->where('cancellation_fine', '>', 0)
                ->where('fine_paid', false),
        ])->loadSum([
            'bookings as unpaid_fine_amount' => fn($q) => $q
                ->where('cancellation_fine', '>', 0)
                ->where('fine_paid', false),
        ], 'cancellation_fine');

        $bookings = $user->bookings()
            ->with(['barberShop', 'barber', 'service', 'review'])
            ->latest()
            ->paginate(10, ['*'], 'bookings_page');

        $reviews = Review::with(['barberShop', 'barber', 'booking'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(8, ['*'], 'reviews_page');

        $loyaltyTransactions = $user->loyaltyTransactions()
            ->latest()
            ->take(8)
            ->get();

        $recentBooking = $user->bookings()->latest('booking_date')->first();

        $profileCompletionItems = [
            !empty($user->name),
            !empty($user->email),
            !empty($user->phone),
            !empty($user->address),
            !is_null($user->email_verified_at),
        ];
        $profileCompletion = (int) round((collect($profileCompletionItems)->filter()->count() / count($profileCompletionItems)) * 100);

        return view('admin.users.show', compact(
            'user',
            'bookings',
            'reviews',
            'loyaltyTransactions',
            'recentBooking',
            'profileCompletion'
        ));
    }
}
