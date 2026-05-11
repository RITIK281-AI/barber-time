<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use Illuminate\Support\Facades\Auth;

abstract class ShopBaseController extends Controller
{
    /**
     * Returns the barber_shop_id for the authenticated shop owner, or aborts 403.
     */
    protected function shopId(): int
    {
        $id = Auth::user()->barber_shop_id;

        if (!$id) {
            abort(403, 'No barber shop associated with this account.');
        }

        return $id;
    }

    /**
     * Returns the BarberShop model for the authenticated shop owner, or aborts 403.
     */
    protected function shop(): BarberShop
    {
        $shop = Auth::user()->barberShop;

        if (!$shop) {
            abort(403, 'No barber shop associated with this account.');
        }

        return $shop;
    }
}
