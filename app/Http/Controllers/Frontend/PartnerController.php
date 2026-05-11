<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use Illuminate\Http\Request;
use App\Mail\Partner\PartnerRequestReceived;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
{
    public function create()
    {
        return view('frontend.partner.become-partner');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_name'                 => 'required|string|max:255',
            'email'                      => 'required|email|max:255|unique:barber_shops,email',
            'phone'                      => 'required|string|max:20',
            'name'                       => 'required|string|max:255',
            'address'                    => 'required|string|max:500',
            'district'                   => 'required|string|max:100',
            'city'                       => 'nullable|string|max:100',
            'pan_number'                 => 'nullable|string|max:50',
            'business_license_number'    => 'required|string|max:100',
            'business_registration_date' => 'nullable|date|before:today',
            'shop_area_sqft'             => 'nullable|integer|min:1|max:100000',
            'number_of_chairs'           => 'required|integer|min:1|max:50',
            'number_of_barbers'          => 'required|integer|min:1|max:50',
            'years_of_experience'        => 'nullable|integer|min:0|max:100',
            'emergency_contact_name'     => 'nullable|string|max:255',
            'emergency_contact_phone'    => 'nullable|string|max:20',
            'services_offered'           => 'required|string|max:1000',
            'description'                => 'nullable|string|max:2000',
            'shop_image'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'shop_license'               => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'registration_document'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'tax_clearance_document'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('shop_image')) {
            $validated['shop_image'] = $request->file('shop_image')
                ->store('partner-shops', 'public');
        }

        if ($request->hasFile('shop_license')) {
            $validated['shop_license'] = $request->file('shop_license')
                ->store('partner-licenses', 'public');
        }

        if ($request->hasFile('registration_document')) {
            $validated['registration_document'] = $request->file('registration_document')
                ->store('partner-documents', 'public');
        }

        if ($request->hasFile('tax_clearance_document')) {
            $validated['tax_clearance_document'] = $request->file('tax_clearance_document')
                ->store('partner-documents', 'public');
        }

        $barberShop = BarberShop::create($validated);

        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
        Mail::to($adminEmail)->send(new PartnerRequestReceived($barberShop));

        return redirect()->route('frontend.shops.partner.create')
            ->with('success', 'Your partnership request has been submitted! We will review your application and contact you via email.');
    }
}
