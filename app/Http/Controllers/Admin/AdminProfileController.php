<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    // show the profile edit page
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    // update name, email, phone, address
    public function update(Request $request)
    {
        /** @var User $admin */
        $admin = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $admin->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $admin->name    = $request->name;
        $admin->email   = $request->email;
        $admin->phone   = $request->phone;
        $admin->address = $request->address;
        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // upload new profile photo
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /** @var User $admin */
        $admin = Auth::user();

        // delete old photo if one already exists
        if ($admin->profile_photo) {
            Storage::disk('public')->delete($admin->profile_photo);
        }

        // store new photo in storage/app/public/profile-photos
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $admin->profile_photo = $path;
        $admin->save();

        return back()->with('success', 'Profile photo updated.');
    }

    // change password
    public function updatePassword(Request $request)
    {
        $request->validate(
            [
                'current_password' => ['required'],
                'new_password'     => [
                    'bail',
                    'required',
                    'confirmed',
                    'min:9',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[^A-Za-z0-9]/',
                ],
            ],
            [
                'current_password.required' => 'Current password is required.',
                'new_password.required' => 'New password is required.',
                'new_password.confirmed' => 'New password confirmation does not match.',
                'new_password.min' => 'New password must be at least 9 characters.',
                'new_password.regex' => 'New password must include at least one uppercase letter, one number, and one special character.',
            ]
        );

        /** @var User $admin */
        $admin = Auth::user();

        if (!Hash::check((string) $request->current_password, (string) $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        // User model has "password" => "hashed" cast.
        $admin->password = $request->new_password;
        $admin->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
