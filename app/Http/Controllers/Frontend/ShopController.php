<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // browse and search all approved shops
   public function index(Request $request)
    {
        $query = BarberShop::query()
            ->where('status', 'approved')
            ->withCount('barbers')
            ->withMin(['services as starting_price' => function ($query) {
                $query->where('status', 'active');
            }], 'price')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('district', 'like', "%{$search}%");
            });
        }

        if ($district = $request->input('district')) {
            $query->where('district', $district);
        }

        if ($rating = $request->input('rating')) {
            $query->where('average_rating', '>=', $rating);
        }

        $shops = $query->paginate(12)->withQueryString();

        $districts = BarberShop::where('status', 'approved')
            ->distinct()
            ->pluck('district')
            ->filter()
            ->sort()
            ->values();

        return view('frontend.shops.index', compact('shops', 'districts'));
    }

    // show a single shop detail page
    public function show($id)
    {
        $shop = BarberShop::with([
                'barbers',
                'services' => function ($query) {
                    $query->where('status', 'active');
                },
                'reviews' => function ($query) {
                    $query->latest()->with('user');
                },
                'closedDays' => function ($query) {
                    $query->orderBy('day_of_week');
                },
                'holidayDates' => function ($query) {
                    $query->whereDate('date', '>=', now()->toDateString())
                        ->orderBy('date')
                        ->take(5);
                },
            ])
            ->where('status', 'approved')
            ->findOrFail($id);

        return view('frontend.shops.show', compact('shop'));
    }

    // return nearby shops as JSON for the map (Haversine formula)
    public function nearby(Request $request)
    {
        $lat    = (float) $request->input('lat');
        $lng    = (float) $request->input('lng');
        $radius = (float) $request->input('radius', 10);

        $shops = BarberShop::where('status', 'approved')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        return response()->json($shops);
    }
}
