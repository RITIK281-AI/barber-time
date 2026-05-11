<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\BarberShop;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShopDashboardController extends Controller
{
    public function index()
    {
        $shopId = $this->currentShopId();
        $shop   = BarberShop::findOrFail($shopId);

        // today's bookings count (any status)
        $todayBookings = Booking::where('barber_shop_id', $shopId)
            ->today()
            ->count();

        // revenue from completed bookings this month
        $monthlyEarnings = Booking::where('barber_shop_id', $shopId)
            ->completed()
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->sum('final_amount');

        // barbers currently active in this shop
        $activeBarbers = Barber::where('barber_shop_id', $shopId)
            ->where('status', 'active')
            ->count();

        // services this shop offers
        $totalServices = Service::where('barber_shop_id', $shopId)->count();

        // next 5 upcoming confirmed bookings with user and barber info
        $upcomingBookings = Booking::where('barber_shop_id', $shopId)
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereDate('booking_date', '>=', today())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->with(['user', 'barber'])
            ->limit(5)
            ->get();

        // bookings per day for the last 7 days for the chart
        $weeklyData = $this->weeklyBookingData($shopId);

        // pending bookings that need action
        $pendingCount = Booking::where('barber_shop_id', $shopId)
            ->where('status', 'pending')
            ->count();

        // total bookings all time
        $totalBookings = Booking::where('barber_shop_id', $shopId)->count();

        // average rating from the shop model
        $averageRating = $shop->average_rating;

        return view('barbershop.dashboard', compact(
            'shop',
            'todayBookings',
            'monthlyEarnings',
            'activeBarbers',
            'totalServices',
            'upcomingBookings',
            'weeklyData',
            'pendingCount',
            'totalBookings',
            'averageRating',
        ));
    }

    // build last 7 days labels and booking counts for the chart
    private function weeklyBookingData(int $shopId): array
    {
        $labels = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $labels[] = $date->format('D'); // Mon, Tue ...

            $counts[] = Booking::where('barber_shop_id', $shopId)
                ->whereDate('booking_date', $date)
                ->count();
        }

        return ['labels' => $labels, 'counts' => $counts];
    }

    // get the shop id linked to the logged-in user
    private function currentShopId(): int
    {
        $shopId = Auth::user()->barber_shop_id;

        if (!$shopId) {
            abort(403, 'No barber shop linked to this account.');
        }

        return $shopId;
    }
}
