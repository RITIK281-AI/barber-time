<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\BarberShop;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopReviewController extends Controller
{
    // list all reviews for this shop with optional barber filter
    public function index(Request $request)
    {
        $shopId = Auth::user()->barber_shop_id;

        $shop = BarberShop::findOrFail($shopId);

        // all barbers for filter dropdown
        $barbers = Barber::where('barber_shop_id', $shopId)->get();

        $query = Review::with(['user', 'barber'])
            ->where('barber_shop_id', $shopId)
            ->latest();

        // filter by barber if selected
        if ($request->filled('barber_id')) {
            $query->where('barber_id', $request->barber_id);
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('barbershop.reviews.index', compact('shop', 'barbers', 'reviews'));
    }
}
