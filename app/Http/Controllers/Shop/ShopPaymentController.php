<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Commission;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopPaymentController extends Controller
{
    private function finePaidCondition($query)
    {
        return $query->where('status', 'cancelled')
            ->where('cancellation_fine', '>', 0)
            ->where('fine_paid', true);
    }

    // get the shop id linked to the logged-in shop admin
    private function currentShopId(): int
    {
        $shopId = Auth::user()->barber_shop_id;

        if (!$shopId) {
            abort(403, 'No barber shop linked to this account.');
        }

        return $shopId;
    }

    public function index(Request $request)
    {
        $shopId = $this->currentShopId();
        $commissionRate = Commission::commissionRate();

        // base query scoped to this shop only
        $query = Booking::with(['user', 'service', 'barber'])
            ->where('barber_shop_id', $shopId)
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

        // filter by service
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // filter by barber (only if barber belongs to this shop)
        if ($request->filled('barber_id')) {
            $barberId = (int) $request->barber_id;
            $barberExistsInShop = Barber::where('barber_shop_id', $shopId)
                ->where('id', $barberId)
                ->exists();

            if ($barberExistsInShop) {
                $query->where('barber_id', $barberId);
            }
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

        $paymentRecords = $query->latest('booking_date')->paginate(20)->withQueryString();

        $shopEarningsExpr = 'COALESCE(SUM(CASE
            WHEN shop_earnings IS NOT NULL AND shop_earnings > 0 THEN shop_earnings
            ELSE final_amount - (final_amount * ? / 100)
        END), 0) as total';

        // today earnings - completed + paid bookings today, after commission deduction
        $todayRevenue = Booking::where('barber_shop_id', $shopId)
            ->revenue()
            ->today()
            ->selectRaw($shopEarningsExpr, [$commissionRate])
            ->value('total');

        // monthly earnings - completed + paid bookings this month, after commission deduction
        $monthlyRevenue = Booking::where('barber_shop_id', $shopId)
            ->revenue()
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->selectRaw($shopEarningsExpr, [$commissionRate])
            ->value('total');

        // all-time earnings - completed + paid bookings, after commission deduction
        $totalEarnings = Booking::where('barber_shop_id', $shopId)
            ->revenue()
            ->selectRaw($shopEarningsExpr, [$commissionRate])
            ->value('total');

        // total cash earnings (COD) after commission deduction
        $cashCollected = Booking::where('barber_shop_id', $shopId)
            ->revenue()
            ->cod()
            ->selectRaw($shopEarningsExpr, [$commissionRate])
            ->value('total');

        // total online earnings (Khalti) after commission deduction
        $onlineCollected = Booking::where('barber_shop_id', $shopId)
            ->revenue()
            ->online()
            ->selectRaw($shopEarningsExpr, [$commissionRate])
            ->value('total');

        // paid cancellation fines are shop compensation
        $fineCompensation = Booking::where('barber_shop_id', $shopId)
            ->where('status', 'cancelled')
            ->where('cancellation_fine', '>', 0)
            ->where('fine_paid', true)
            ->sum('cancellation_fine');

        // barber and service lists for filter dropdowns
        $barbers = Barber::where('barber_shop_id', $shopId)
            ->orderBy('name')
            ->get(['id', 'name']);

        $services = Service::where('barber_shop_id', $shopId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('barbershop.payments.index', compact(
            'paymentRecords',
            'todayRevenue',
            'monthlyRevenue',
            'totalEarnings',
            'cashCollected',
            'onlineCollected',
            'fineCompensation',
            'barbers',
            'services',
        ));
    }
}
