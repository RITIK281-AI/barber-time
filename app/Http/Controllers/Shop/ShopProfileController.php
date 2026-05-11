<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ShopProfileController extends Controller
{
    // get the shop linked to the logged-in shop admin
    private function getShop()
    {
        return BarberShop::findOrFail(Auth::user()->barber_shop_id);
    }

    // show the profile edit form
    public function edit()
    {
        $shop = $this->getShop();

        return view('barbershop.profile.edit', compact('shop'));
    }

    // save updated profile including location
    public function update(Request $request)
    {
        $shop = $this->getShop();

        $request->validate([
            'name'                       => 'required|string|max:255',
            'phone'                      => 'nullable|string|max:20',
            'email'                      => 'nullable|email|max:255',
            'address'                    => 'nullable|string|max:500',
            'district'                   => 'nullable|string|max:100',
            'city'                       => 'nullable|string|max:100',
            'description'                => 'nullable|string|max:1000',
            'opening_time'               => 'nullable|string',
            'closing_time'               => 'nullable|string',
            'shop_image'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'latitude'                   => 'nullable|numeric|between:-90,90',
            'longitude'                  => 'nullable|numeric|between:-180,180',
            'business_license_number'    => 'nullable|string|max:100',
            'business_registration_date' => 'nullable|date|before:today',
            'shop_area_sqft'             => 'nullable|integer|min:1|max:100000',
            'number_of_chairs'           => 'nullable|integer|min:1|max:50',
            'number_of_barbers'          => 'nullable|integer|min:1|max:50',
            'years_of_experience'        => 'nullable|integer|min:0|max:100',
            'emergency_contact_name'     => 'nullable|string|max:255',
            'emergency_contact_phone'    => 'nullable|string|max:20',
            'services_offered'           => 'nullable|string|max:1000',
            'current_password'           => ['nullable', 'required_with:new_password,new_password_confirmation'],
            'new_password'               => [
                'bail',
                'nullable',
                'string',
                'confirmed',
                'required_with:current_password',
                'min:9',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ], [
            'current_password.required_with' => 'Current password is required to change password.',
            'new_password.required_with' => 'New password is required when changing password.',
            'new_password.confirmed' => 'New password confirmation does not match.',
            'new_password.min' => 'New password must be at least 9 characters.',
            'new_password.regex' => 'New password must include at least one uppercase letter, one number, and one special character.',
        ]);

        $shop->name                       = $request->name;
        $shop->phone                      = $request->phone;
        $shop->email                      = $request->email;
        $shop->address                    = $request->address;
        $shop->district                   = $request->district;
        $shop->city                       = $request->city;
        $shop->description                = $request->description;
        $shop->opening_time               = $request->opening_time;
        $shop->closing_time               = $request->closing_time;
        $shop->latitude                   = $request->latitude;
        $shop->longitude                  = $request->longitude;
        $shop->business_license_number    = $request->business_license_number;
        $shop->business_registration_date = $request->business_registration_date;
        $shop->shop_area_sqft             = $request->shop_area_sqft;
        $shop->number_of_chairs           = $request->number_of_chairs;
        $shop->number_of_barbers          = $request->number_of_barbers;
        $shop->years_of_experience        = $request->years_of_experience;
        $shop->emergency_contact_name     = $request->emergency_contact_name;
        $shop->emergency_contact_phone    = $request->emergency_contact_phone;
        $shop->services_offered           = $request->services_offered;

        // handle shop image upload and delete old one
        if ($request->hasFile('shop_image')) {
            if ($shop->shop_image) {
                Storage::disk('public')->delete($shop->shop_image);
            }
            $shop->shop_image = $request->file('shop_image')->store('shops', 'public');
        }

        if ($request->filled('new_password')) {
            /** @var User $user */
            $user = Auth::user();

            if (!Hash::check((string) $request->current_password, (string) $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }

            // User model has "password" => "hashed" cast.
            $user->password = $request->new_password;
            $user->save();
        }

        $shop->save();

        return redirect()->route('shop.profile.edit')
                         ->with('success', 'Shop profile updated successfully.');
    }
}
