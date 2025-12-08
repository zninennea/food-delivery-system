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

        $profilePictureUrl = null;
        if ($user->profile_picture) {
            $profilePictureUrl = asset('storage/' . $user->profile_picture);
        } elseif ($user->oauth_provider === 'google' && $user->avatar) {
            $profilePictureUrl = $user->avatar;
        }

        // Use Google avatar if available and no custom profile picture
        if (!$user->profile_picture && $user->oauth_provider === 'google' && $user->avatar) {
            $user->profile_picture_url = $user->avatar;
        } else {
            $user->profile_picture_url = $user->profile_picture
                ? asset('storage/' . $user->profile_picture)
                : null;
        }

        return view('customer.profile.show', compact(
            'user',
            'profilePictureUrl'
        ));
    }

    public function edit()
    {
        $user = Auth::user();

        $profilePictureUrl = null;
        if ($user->profile_picture) {
            $profilePictureUrl = asset('storage/' . $user->profile_picture);
        } elseif ($user->oauth_provider === 'google' && $user->avatar) {
            $profilePictureUrl = $user->avatar;
        }

        return view('customer.profile.edit', compact(
            'user',
            'profilePictureUrl'
        ));
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

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => [
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'delivery_address' => $user->delivery_address,
                    'profile_picture' => $user->profile_picture ? asset('storage/' . $user->profile_picture) : null,
                ]
            ]);
        }

        return redirect()->route('customer.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    public function editPassword()
    {
        $user = Auth::user();

        $profilePictureUrl = null;
        if ($user->profile_picture) {
            $profilePictureUrl = asset('storage/' . $user->profile_picture);
        } elseif ($user->oauth_provider === 'google' && $user->avatar) {
            $profilePictureUrl = $user->avatar;
        }

        return view('customer.profile.password', compact(
            'profilePictureUrl'
        ));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
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
