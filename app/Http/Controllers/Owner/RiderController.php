<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RiderController extends Controller
{
    public function index()
    {
        $riders = User::where('role', 'rider')->latest()->get();
        return view('owner.riders.index', compact('riders'));
    }

    public function create()
    {
        return view('owner.riders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_plate' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'rider',
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'status' => 'active',
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('rider-profiles', 'public');
            $userData['profile_picture'] = $profilePicturePath;
        }

        User::create($userData);

        return redirect()->route('owner.riders.index')
            ->with('success', 'Rider created successfully!');
    }

    public function edit(User $rider)
    {
        if ($rider->role !== 'rider') {
            abort(404, 'Rider not found.');
        }

        return view('owner.riders.edit', compact('rider'));
    }

    public function update(Request $request, User $rider)
    {
        if ($rider->role !== 'rider') {
            abort(404, 'Rider not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $rider->id,
            'phone' => 'required|string|max:20',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_plate' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'status' => $request->status,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($rider->profile_picture && Storage::disk('public')->exists($rider->profile_picture)) {
                Storage::disk('public')->delete($rider->profile_picture);
            }
            // Store new profile picture
            $profilePicturePath = $request->file('profile_picture')->store('rider-profiles', 'public');
            $updateData['profile_picture'] = $profilePicturePath;
        }

        $rider->update($updateData);

        return redirect()->route('owner.riders.index')
            ->with('success', 'Rider updated successfully!');
    }

    public function destroy(User $rider)
    {
        if ($rider->role !== 'rider') {
            abort(404, 'Rider not found.');
        }

        // Delete profile picture if exists
        if ($rider->profile_picture && Storage::disk('public')->exists($rider->profile_picture)) {
            Storage::disk('public')->delete($rider->profile_picture);
        }

        $rider->delete();

        return redirect()->route('owner.riders.index')
            ->with('success', 'Rider deleted successfully!');
    }
}