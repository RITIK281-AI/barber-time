<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\BarberShop;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // basic counts
        $totalUsers    = User::where('role', 'user')->count();
        $totalShops    = BarberShop::count();
        $approvedShops = BarberShop::where('status', 'approved')->count();
        $pendingShops  = BarberShop::where('status', 'pending')->count();
        $totalBarbers  = Barber::count();

        // booking counts
        $totalBookings     = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $pendingBookings   = Booking::where('status', 'pending')->count();

        // total revenue from completed paid bookings
        $totalRevenue = Booking::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->sum('final_price');

        // recent 8 bookings for the table
        $recentBookings = Booking::with(['user', 'barberShop', 'barber'])
            ->latest()
            ->take(8)
            ->get();

        // top 5 rated shops
        $topShops = BarberShop::where('status', 'approved')
            ->where('average_rating', '>', 0)
            ->orderByDesc('average_rating')
            ->take(5)
            ->get();

        // booking trend — last 7 months count
        $bookingTrend = Booking::select(
                DB::raw('MONTH(booking_date) as month'),
                DB::raw('YEAR(booking_date) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('booking_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // recent 5 reviews
        $recentReviews = Review::with(['user', 'barberShop', 'barber'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalShops',
            'approvedShops',
            'pendingShops',
            'totalBarbers',
            'totalBookings',
            'completedBookings',
            'pendingBookings',
            'totalRevenue',
            'recentBookings',
            'topShops',
            'bookingTrend',
            'recentReviews'
        ));
    }

    // analytics page data
    public function analytics()
    {
        // bookings per month for last 12 months
        $bookingsByMonth = Booking::select(
                DB::raw('MONTHNAME(booking_date) as month'),
                DB::raw('MONTH(booking_date) as month_num'),
                DB::raw('YEAR(booking_date) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('booking_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month_num', 'month')
            ->orderBy('year')
            ->orderBy('month_num')
            ->get();

        // revenue per month for last 12 months
        $revenueByMonth = Booking::select(
                DB::raw('MONTHNAME(booking_date) as month'),
                DB::raw('MONTH(booking_date) as month_num'),
                DB::raw('YEAR(booking_date) as year'),
                DB::raw('SUM(final_price) as total')
            )
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereDate('booking_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month_num', 'month')
            ->orderBy('year')
            ->orderBy('month_num')
            ->get();

        // top 5 shops by booking count
        $topShopsByBookings = BarberShop::withCount('bookings')
            ->where('status', 'approved')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // top 5 shops by rating
        $topShopsByRating = BarberShop::where('status', 'approved')
            ->where('average_rating', '>', 0)
            ->orderByDesc('average_rating')
            ->take(5)
            ->get();

        // booking status breakdown
        $bookingStatuses = Booking::select(
                DB::raw('status'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // payment method breakdown
        $paymentMethods = Booking::select(
                DB::raw('payment_method'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get()
            ->pluck('total', 'payment_method');

        // summary numbers
        $totalRevenue      = Booking::where('status', 'completed')->where('payment_status', 'paid')->sum('final_price');
        $totalBookings     = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        return view('admin.analytics.index', compact(
            'bookingsByMonth',
            'revenueByMonth',
            'topShopsByBookings',
            'topShopsByRating',
            'bookingStatuses',
            'paymentMethods',
            'totalRevenue',
            'totalBookings',
            'completedBookings',
            'cancelledBookings'
        ));
    }
}
