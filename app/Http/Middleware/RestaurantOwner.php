<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestaurantOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $restaurant = $request->route('restaurant') ?? Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant || $restaurant->owner_id !== $user->id) {
            abort(403, 'Unauthorized access to this restaurant.');
        }

        return $next($request);
    }
}