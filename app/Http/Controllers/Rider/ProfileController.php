<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the rider's profile (view only)
     */
    public function show()
    {
        $user = Auth::user();

        // Ensure only riders can access
        if ($user->role !== 'rider') {
            abort(403, 'Unauthorized access. Rider role required.');
        }

        return view('rider.profile.show', compact('user'));
    }
}