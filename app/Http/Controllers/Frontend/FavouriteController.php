<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\BarberShop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    // toggle save / unsave a shop
    public function toggle(BarberShop $shop)
    {
        $user = Auth::user();

        $existing = Favourite::where('user_id', $user->id)
            ->where('barber_shop_id', $shop->id)
            ->first();

        if ($existing) {
            // already favourited — remove it
            $existing->delete();
            $isFavourited = false;
            $message = 'Shop removed from favourites.';
        } else {
            // not yet favourited — add it
            Favourite::create([
                'user_id'       => $user->id,
                'barber_shop_id' => $shop->id,
            ]);
            $isFavourited = true;
            $message = 'Shop saved to favourites!';
        }

        // return JSON for AJAX button update
        if (request()->expectsJson()) {
            return response()->json([
                'favourited' => $isFavourited,
                'message'    => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    // show all favourites on the dashboard tab
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $favourites = $user
            ->favourites()
            ->with('barberShop')
            ->latest()
            ->get()
            ->pluck('barberShop')
            ->filter(); // remove any nulls if shop was deleted

        return view('frontend.dashboard.index', [
            'activeTab'  => 'favourites',
            'favourites' => $favourites,
        ]);
    }
}
