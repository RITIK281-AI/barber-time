<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barber;

class BarberController extends Controller
{
    // public barber profile page with their reviews
    public function show($id)
    {
        $barber = Barber::with(['reviews.user', 'shop'])
            ->findOrFail($id);

        return view('frontend.barbers.show', compact('barber'));
    }
}
