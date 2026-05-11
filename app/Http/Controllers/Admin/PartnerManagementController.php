<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use App\Models\User;
use App\Mail\Partner\PartnerApproved;
use App\Mail\Partner\PartnerRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PartnerManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $partnerRequests = BarberShop::when($status !== 'all', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->latest()
        ->paginate(15);

        return view('admin.partners.index', compact('partnerRequests', 'status'));
    }

    public function show(BarberShop $barberShop)
    {
        return view('admin.partners.show', compact('barberShop'));
    }

    public function approve(Request $request, BarberShop $barberShop)
    {
        // Prevent re-approving
        if ($barberShop->status !== 'pending') {
            return redirect()->route('admin.partners.show', $barberShop)
                ->with('error', 'This request has already been processed.');
        }

        // Step 1: Generate random 4-digit PIN
        $plainPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        // Step 2: Create User account with HASHED pin
        $user = User::create([
            'name'           => $barberShop->owner_name,
            'email'          => $barberShop->email,
            'password' => Hash::make($plainPin),
            'role' => 'barber_shop',
            'barber_shop_id' => $barberShop->id,
        ]);

        // Step 3: Update barber shop status
        $barberShop->update([
            'status'        => 'approved',
            'admin_remarks' => $request->input('admin_remarks'),
            'reviewed_at'   => now(),
        ]);

        // Step 4: Send welcome email with plain PIN
        Mail::to($barberShop->email)->send(new PartnerApproved($barberShop, $plainPin));

        // $plainPin is gone after this method returns — never stored

        return redirect()->route('admin.partners.index')
            ->with('success', "Partner '{$barberShop->name}' approved! Login credentials sent to {$barberShop->email}.");
    }

    public function reject(Request $request, BarberShop $barberShop)
    {
        if ($barberShop->status !== 'pending') {
            return redirect()->route('admin.partners.show', $barberShop)
                ->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'admin_remarks' => 'required|string|max:1000',
        ]);

        $adminRemarks = $request->input('admin_remarks');

        $barberShop->update([
            'status'        => 'rejected',
            'admin_remarks' => $adminRemarks,
            'reviewed_at'   => now(),
        ]);

        Mail::to($barberShop->email)->send(new PartnerRejected($barberShop, $adminRemarks));

        return redirect()->route('admin.partners.index')
            ->with('success', "Partner request from '{$barberShop->name}' has been rejected.");
    }
}
