<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('customer.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('customer.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:500',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'delivery_address' => $request->delivery_address,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            // Store new profile picture
            $profilePicturePath = $request->file('profile_picture')->store('customer-profiles', 'public');
            $updateData['profile_picture'] = $profilePicturePath;
        }

        $user->update($updateData);

        return redirect()->route('customer.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    public function editPassword()
    {
        return view('customer.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.profile.show')
            ->with('success', 'Password updated successfully!');
    }
}