<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')
            ->where('barber_shop_id', Auth::user()->barber_shop_id)
            ->latest()
            ->get();

        return view('barbershop.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();

        return view('barbershop.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge(['status' => trim($request->input('status', ''))]);
        $request->validate([
            'category_id'  => 'required|exists:service_categories,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'duration'     => 'required|integer|min:1',
            'status'       => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Service::create([
            'barber_shop_id' => Auth::user()->barber_shop_id,
            'category_id'    => $request->category_id,
            'name'           => $request->name,
            'description'    => $request->description,
            'price'          => $request->price,
            'duration'       => $request->duration,
            'status'         => $request->status,
        ]);

        return redirect()
            ->route('shop.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $service    = Service::where('barber_shop_id', Auth::user()->barber_shop_id)->findOrFail($id);
        $categories = ServiceCategory::orderBy('sort_order')->get();

        return view('barbershop.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $service = Service::where('barber_shop_id', Auth::user()->barber_shop_id)->findOrFail($id);

        $request->merge(['status' => trim($request->input('status', ''))]);
        $request->validate([
            'category_id'  => 'required|exists:service_categories,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'duration'     => 'required|integer|min:1',
            'status'       => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $service->update([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'duration'     => $request->duration,
            'status'       => $request->status,
        ]);

        return redirect()
            ->route('shop.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Toggle service status (activate/deactivate) instead of deleting.
     */
    public function destroy(string $id)
    {
        $service = Service::where('barber_shop_id', Auth::user()->barber_shop_id)->findOrFail($id);

        // Toggle status instead of deleting. Treat legacy available/unavailable values as active/inactive.
        $current = ($service->status === 'active' || $service->status === 'available') ? 'active' : 'inactive';
        $newStatus = $current === 'active' ? 'inactive' : 'active';
        $service->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'activated' : 'deactivated';

        return redirect()
            ->route('shop.services.index')
            ->with('success', "Service {$label} successfully.");
    }
}
