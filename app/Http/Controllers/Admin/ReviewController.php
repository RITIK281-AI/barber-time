<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // show all reviews with search and filter
    public function index(Request $request)
    {
        $query = Review::with(['user', 'barberShop', 'barber', 'booking'])
            ->latest();

        $shops = BarberShop::orderBy('name')->get(['id', 'name']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('barberShop', fn($s) => $s->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('barber', fn($b) => $b->where('name', 'like', "%{$search}%"))
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        // filter by barber shop
        if ($shopId = $request->input('shop_id')) {
            $query->where('barber_shop_id', $shopId);
        }

        // filter by rating value
        if ($rating = (int) $request->input('rating')) {
            $query->where(function ($q) use ($rating) {
                $q->where('shop_rating', $rating)
                  ->orWhere('barber_rating', $rating);
            });
        }

        $reviews = $query->paginate(15);

        return view('admin.reviews.index', compact('reviews', 'shops'));
    }

    // delete a review
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
