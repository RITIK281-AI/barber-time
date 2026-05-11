<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use App\Models\Booking;
use App\Models\Commission;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    private function finePaidCondition($query)
    {
        return $query->where('status', 'cancelled')
            ->where('cancellation_fine', '>', 0)
            ->where('fine_paid', true);
    }

    public function index(Request $request)
    {
        // build the base query with all needed relations
        $query = Booking::with(['user', 'barberShop', 'service'])
            ->where(function ($q) {
                $q->whereNotNull('payment_method')
                    ->orWhere(fn($fq) => $this->finePaidCondition($fq));
            });

        // filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        // filter by shop
        if ($request->filled('shop_id')) {
            $query->where('barber_shop_id', $request->shop_id);
        }

        // filter by payment method
        if ($request->filled('payment_method')) {
            if ($request->payment_method === 'khalti') {
                $query->where(function ($q) {
                    $q->where('payment_method', 'khalti')
                        ->orWhere(fn($fq) => $this->finePaidCondition($fq));
                });
            } elseif ($request->payment_method === 'cod') {
                $query->where('payment_method', 'cod');
            }
        }

        // filter by payment purpose
        if ($request->filled('payment_for') && in_array($request->payment_for, ['booking', 'fine'], true)) {
            if ($request->payment_for === 'fine') {
                $query->where(fn($fq) => $this->finePaidCondition($fq));
            } else {
                $query->whereNotNull('payment_method');
            }
        }

        // filter by booking status
        if ($request->filled('booking_status')) {
            $query->where('status', $request->booking_status);
        }

        $paymentRecords = $query->latest('booking_date')->paginate(20)->withQueryString();

        // only completed + paid bookings count as collected revenue
        $commissionRate = Commission::commissionRate();
        $totalRevenue = Booking::revenue()->sum('final_amount');
        $cashTotal = Booking::revenue()->cod()->sum('final_amount');
        $onlineTotal = Booking::revenue()->online()->sum('final_amount');

        // platform commission: use stored amount when present, otherwise derive by current rate
        $platformRevenue = Booking::revenue()
            ->selectRaw(
                'COALESCE(SUM(CASE
                    WHEN commission_amount IS NOT NULL AND commission_amount > 0 THEN commission_amount
                    ELSE final_amount * ? / 100
                END), 0) as total',
                [$commissionRate]
            )
            ->value('total');

        // paid fines are compensation to shops, not platform revenue
        $fineCompensation = Booking::where('status', 'cancelled')
            ->where('cancellation_fine', '>', 0)
            ->where('fine_paid', true)
            ->sum('cancellation_fine');

        // shop earnings: use stored value when present, otherwise derive by current rate
        $totalShopEarnings = Booking::revenue()
            ->selectRaw(
                'COALESCE(SUM(CASE
                    WHEN shop_earnings IS NOT NULL AND shop_earnings > 0 THEN shop_earnings
                    ELSE final_amount - (final_amount * ? / 100)
                END), 0) as total',
                [$commissionRate]
            )
            ->value('total');

        // shop list for the filter dropdown
        $shops = BarberShop::where('status', 'approved')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.payments.index', compact(
            'paymentRecords',
            'totalRevenue',
            'cashTotal',
            'onlineTotal',
            'platformRevenue',
            'totalShopEarnings',
            'fineCompensation',
            'commissionRate',
            'shops',
        ));
    }
}
