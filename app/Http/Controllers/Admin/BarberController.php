<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\BarberShop;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    // show all barbers with search and filter
    public function index(Request $request)
    {
        $query = Barber::with('shop')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('shop', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            if (in_array($status, ['active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        if ($shop = $request->input('shop')) {
            $query->where('barber_shop_id', $shop);
        }

        $barbers = $query->paginate(15);
        $barbers->appends($request->only(['search', 'status', 'shop']));

        $shops = BarberShop::orderBy('name')->get(['id', 'name']);

        return view('admin.barbers.index', compact('barbers', 'shops'));
    }

    // show a single barber's full details
    public function show(Barber $barber)
    {
        // load the shop and reviews relationships
        $barber->load('shop', 'reviews');
        return view('admin.barbers.show', compact('barber'));
    }
}
