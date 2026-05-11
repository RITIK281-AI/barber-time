<?php

namespace App\Http\Middleware;

use App\Models\BarberShop;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureBarberShopLinked
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'barber_shop') {
            return $next($request);
        }

        // if no shop linked yet, create one automatically
        if (!$user->barber_shop_id) {
            $shopName = preg_match("/'s shop$/i", $user->name) ? $user->name : $user->name . "'s Shop";

            $shop = BarberShop::create([
                'name'       => $shopName,
                'address'    => 'Address to be updated',
                'shop_image' => null,
                'latitude'   => 0,
                'longitude'  => 0,
                'phone'      => null,
                'owner_name' => $user->name,
                'status'     => 'approved',
            ]);

            $user->barber_shop_id = $shop->id;
            $user->save();

            return $next($request);
        }

        // check if the linked shop is suspended
        $shop = BarberShop::find($user->barber_shop_id);

        if ($shop && $shop->status === 'suspended') {
            // log them out and redirect to login with message
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your barber shop has been suspended. Please contact the administrator.']);
        }

        return $next($request);
    }
}
