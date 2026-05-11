<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // show the review form for a completed booking
    public function create($bookingId)
    {
        $booking = Booking::with(['barber', 'barberShop', 'review'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // only completed bookings can be reviewed
        if ($booking->status !== 'completed') {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'You can only review completed bookings.');
        }

        // already reviewed — send to edit
        if ($booking->review) {
            return redirect()->route('frontend.reviews.edit', $booking->review->id);
        }

        return view('frontend.reviews.create', compact('booking'));
    }

    // save a new review
    public function store(Request $request)
    {
        $request->validate([
            'booking_id'    => 'required|exists:bookings,id',
            'barber_rating' => 'required|integer|min:1|max:5',
            'shop_rating'   => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
        ]);

        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // block duplicate — one review per booking
        if ($booking->review) {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'You have already reviewed this booking.');
        }

        Review::create([
            'booking_id'     => $booking->id,
            'user_id'        => Auth::id(),
            'barber_id'      => $booking->barber_id,
            'barber_shop_id' => $booking->barber_shop_id,
            'barber_rating'  => $request->barber_rating,
            'shop_rating'    => $request->shop_rating,
            'comment'        => $request->comment,
        ]);

        // update averages on both barber and shop
        $this->updateBarberRating($booking->barber_id);
        $this->updateShopRating($booking->barber_shop_id);

        return redirect()->route('frontend.bookings.index')
            ->with('success', 'Thank you! Your review has been submitted.');
    }

    // show edit form with existing values prefilled
    public function edit($id)
    {
        $review = Review::with(['booking.barber', 'booking.barberShop'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $booking = $review->booking;

        return view('frontend.reviews.edit', compact('review', 'booking'));
    }

    // save the updated review
    public function update(Request $request, $id)
    {
        $request->validate([
            'barber_rating' => 'required|integer|min:1|max:5',
            'shop_rating'   => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
        ]);

        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->update([
            'barber_rating' => $request->barber_rating,
            'shop_rating'   => $request->shop_rating,
            'comment'       => $request->comment,
        ]);

        // recalculate averages after edit
        $this->updateBarberRating($review->barber_id);
        $this->updateShopRating($review->barber_shop_id);

        return redirect()->route('frontend.bookings.index')
            ->with('success', 'Your review has been updated.');
    }

    // recalculate average and total for a barber
    private function updateBarberRating($barberId)
    {
        $avg   = Review::where('barber_id', $barberId)->avg('barber_rating');
        $total = Review::where('barber_id', $barberId)->count();

        \App\Models\Barber::where('id', $barberId)->update([
            'average_rating' => round($avg, 1),
            'total_reviews'  => $total,
        ]);
    }

    // recalculate average and total for a shop
    private function updateShopRating($shopId)
    {
        $avg   = Review::where('barber_shop_id', $shopId)->avg('shop_rating');
        $total = Review::where('barber_shop_id', $shopId)->count();

        \App\Models\BarberShop::where('id', $shopId)->update([
            'average_rating' => round($avg, 1),
            'total_reviews'  => $total,
        ]);
    }
}
