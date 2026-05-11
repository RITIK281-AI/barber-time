<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'barberShop', 'barber', 'service']);

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        if ($request->filled('shop_id')) {
            $query->where('barber_shop_id', $request->shop_id);
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'confirmed', 'completed', 'cancelled'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status') && in_array($request->payment_status, ['paid', 'unpaid'], true)) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method') && in_array($request->payment_method, ['cod', 'khalti'], true)) {
            $query->where('payment_method', $request->payment_method);
        }

        $bookings = $query->latest('booking_date')->paginate(20)->withQueryString();

        $shops = BarberShop::where('status', 'approved')
            ->orderBy('name')
            ->get(['id', 'name']);

        $summary = [
            'total' => Booking::count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'paid' => Booking::where('payment_status', 'paid')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'shops', 'summary'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'barberShop',
            'barber',
            'service',
            'items.service',
            'payments.recordedBy',
            'review',
        ]);

        return view('admin.bookings.show', compact('booking'));
    }
}
