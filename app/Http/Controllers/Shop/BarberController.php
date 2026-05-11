<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarberController extends Controller
{
    // list all barbers for this shop
    public function index(Request $request)
    {
        $query = Barber::where('barber_shop_id', Auth::user()->barber_shop_id);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'], true)) {
            $query->where('status', $request->status);
        }

        $barbers = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('barbershop.barbers.index', compact('barbers'));
    }

    // show form to add a new barber
    public function create()
    {
        return view('barbershop.barbers.create');
    }

    // save new barber
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email',
            'experience_years'   => 'nullable|integer|min:0|max:60',
            'bio'                => 'nullable|string',
            'profile_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status'             => 'required|in:active,inactive',
            'unavailable_reason' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->hasFile('profile_image')
            ? $request->file('profile_image')->store('barbers', 'public')
            : null;

        Barber::create([
            'barber_shop_id'     => Auth::user()->barber_shop_id,
            'name'               => $validated['name'],
            'phone'              => $validated['phone'] ?? null,
            'email'              => $validated['email'] ?? null,
            'experience_years'   => $validated['experience_years'] ?? null,
            'bio'                => $validated['bio'] ?? null,
            'profile_image'      => $imagePath,
            'status'             => $validated['status'],
            'unavailable_reason' => $validated['unavailable_reason'] ?? null,
        ]);

        return redirect()
            ->route('shop.barbers.index')
            ->with('success', 'Barber added successfully.');
    }

    // show a single barber with their reviews (shop admin side)
    public function show(Barber $barber)
    {
        $this->authorizeBarber($barber);

        $barber->load(['reviews.user']);

        return view('barbershop.barbers.show', compact('barber'));
    }

    // show form to edit a barber
    public function edit(Barber $barber)
    {
        $this->authorizeBarber($barber);

        return view('barbershop.barbers.edit', compact('barber'));
    }

    // save updated barber
    public function update(Request $request, Barber $barber)
    {
        $this->authorizeBarber($barber);

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email',
            'experience_years'   => 'nullable|integer|min:0|max:60',
            'bio'                => 'nullable|string',
            'profile_image'      => 'nullable|image|max:2048',
            'status'             => 'required|in:active,inactive',
            'unavailable_reason' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($barber->profile_image) {
                Storage::disk('public')->delete($barber->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('barbers', 'public');
        } else {
            unset($validated['profile_image']);
        }

        $barber->update($validated);

        return redirect()
            ->route('shop.barbers.index')
            ->with('success', 'Barber updated successfully.');
    }

    // delete a barber
    public function destroy(Barber $barber)
    {
        $this->authorizeBarber($barber);

        $barber->delete();

        return redirect()
            ->route('shop.barbers.index')
            ->with('success', 'Barber deleted successfully.');
    }

    // make sure barber belongs to this shop
    private function authorizeBarber(Barber $barber): void
    {
        if ($barber->barber_shop_id !== Auth::user()->barber_shop_id) {
            abort(403);
        }
    }
}
