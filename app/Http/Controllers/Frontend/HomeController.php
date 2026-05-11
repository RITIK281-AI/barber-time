<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $shops    = BarberShop::where('status', 'approved')->latest()->take(8)->get();
        $services = Service::with('barberShop')->latest()->take(4)->get();

        $shopCount    = BarberShop::where('status', 'approved')->count();
        $clientCount  = Booking::whereIn('status', ['completed', 'confirmed'])->count();
        $avgRating    = Review::avg('shop_rating');

        return view('frontend.home', compact(
            'shops', 'services',
            'shopCount', 'clientCount', 'avgRating'
        ));
    }

    public function howItWorks()
    {
        return view('frontend.how-it-works');
    }
}
