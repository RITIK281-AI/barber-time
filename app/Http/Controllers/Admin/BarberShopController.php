<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarberShopController extends Controller
{
    // show all barber shops with search and filter
    public function index(Request $request)
    {
        $query = BarberShop::query()->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            if (in_array($status, ['pending', 'approved', 'rejected', 'suspended'])) {
                $query->where('status', $status);
            }
        }

        $barberShops = $query->paginate(15);
        $barberShops->appends($request->only(['search', 'status']));

        return view('admin.barbershops.index', compact('barberShops'));
    }

    // show single barber shop details
    public function show(BarberShop $barbershop)
    {
        $barbershop->load(['barbers', 'services', 'reviews.user']);
        return view('admin.barbershops.show', ['barberShop' => $barbershop]);
    }

    public function create()
    {
        return view('admin.barbershops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'shop_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'phone'        => 'nullable|string|max:20',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'status'       => 'required|in:pending,approved,rejected,suspended',
        ]);

        if ($request->hasFile('shop_image')) {
            $validated['shop_image'] = $request->file('shop_image')
                ->store('barber_shops', 'public');
        }

        BarberShop::create($validated);

        return redirect()
            ->route('admin.barbershops.index')
            ->with('success', 'Barber shop created successfully.');
    }

    public function edit(BarberShop $barbershop)
    {
        return view('admin.barbershops.edit', ['barberShop' => $barbershop]);
    }

    public function update(Request $request, BarberShop $barbershop)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'shop_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'phone'        => 'nullable|string|max:20',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'status'       => 'required|in:pending,approved,rejected,suspended',
        ]);

        if ($request->hasFile('shop_image')) {
            // delete old image before saving new one
            if ($barbershop->shop_image) {
                Storage::disk('public')->delete($barbershop->shop_image);
            }
            $validated['shop_image'] = $request->file('shop_image')
                ->store('barber_shops', 'public');
        }

        $barbershop->update($validated);

        return redirect()
            ->route('admin.barbershops.index')
            ->with('success', 'Barber shop updated successfully.');
    }

    // suspend a barber shop instead of deleting it
    public function suspend(BarberShop $barbershop)
    {
        $barbershop->update(['status' => 'suspended']);

        return back()->with('success', "{$barbershop->name} has been suspended.");
    }

    // restore a suspended barber shop back to approved
    public function restore(BarberShop $barbershop)
    {
        $barbershop->update(['status' => 'approved']);

        return back()->with('success', "{$barbershop->name} has been restored.");
    }
}
