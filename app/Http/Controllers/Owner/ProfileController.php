<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Show profile (view only)
    public function show()
    {
        $user = Auth::user();
        $restaurant = Restaurant::where('owner_id', $user->id)->firstOrFail();
        
        return view('owner.profile.show', compact('user', 'restaurant'));
    }

    // Show edit form
    public function edit()
    {
        $user = Auth::user();
        $restaurant = Restaurant::where('owner_id', $user->id)->firstOrFail();
        
        return view('owner.profile.edit', compact('user', 'restaurant'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();
        $restaurant = Restaurant::where('owner_id', $user->id)->firstOrFail();

        // Validate all data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
            'restaurant_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'restaurant_phone' => 'required|string|max:20',
            'facebook_url' => 'nullable|url|max:255',
            'background_image' => 'nullable|image|max:5120'
        ]);

        try {
            DB::transaction(function () use ($request, $user, $restaurant) {
                $userData = [
                    'name' => $request->name,
                    'phone' => $request->phone,
                ];

                $restaurantData = [
                    'name' => $request->restaurant_name,
                    'address' => $request->address,
                    'phone' => $request->restaurant_phone,
                    'facebook_url' => $request->facebook_url,
                ];

                // Handle profile picture upload
                if ($request->hasFile('profile_picture')) {
                    // Delete old profile picture if exists
                    if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                        Storage::disk('public')->delete($user->profile_picture);
                    }
                    // Store new profile picture
                    $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');
                    $userData['profile_picture'] = $profilePicturePath;
                }

                // Handle background image upload
                if ($request->hasFile('background_image')) {
                    // Delete old background image if exists
                    if ($restaurant->background_image && Storage::disk('public')->exists($restaurant->background_image)) {
                        Storage::disk('public')->delete($restaurant->background_image);
                    }
                    // Store new background image
                    $backgroundImagePath = $request->file('background_image')->store('backgrounds', 'public');
                    $restaurantData['background_image'] = $backgroundImagePath;
                }

                // Update user
                DB::table('users')->where('id', $user->id)->update($userData);
                
                // Update restaurant
                DB::table('restaurants')->where('id', $restaurant->id)->update($restaurantData);
            });

            return redirect()->route('owner.profile.show')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating profile: ' . $e->getMessage())
                ->withInput();
        }
    }
}