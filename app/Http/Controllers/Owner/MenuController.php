<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        $menuItems = MenuItem::where('restaurant_id', $restaurant->id)->get();
        
        return view('owner.menu.index', compact('menuItems', 'restaurant'));
    }

    public function create()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        return view('owner.menu.create', compact('restaurant'));
    }

    public function store(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048' // 2MB max
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store in storage/app/public/menu-items
            $imagePath = $request->file('image')->store('menu-items', 'public');
            $data['image'] = $imagePath;
        } else {
            // Set default image if no image uploaded
            $data['image'] = null;
        }

        $data['restaurant_id'] = $restaurant->id;

        MenuItem::create($data);

        return redirect()->route('owner.menu.index')->with('success', 'Menu item added successfully!');
    }

    public function edit(MenuItem $menuItem)
    {
        // Check if menu item belongs to owner's restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('owner.menu.edit', compact('menuItem'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        // Check if menu item belongs to owner's restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('menu-items', 'public');
            $data['image'] = $imagePath;
        } else {
            // Keep existing image if no new image uploaded
            $data['image'] = $menuItem->image;
        }

        $menuItem->update($data);

        return redirect()->route('owner.menu.index')->with('success', 'Menu item updated successfully!');
    }

    public function destroy(MenuItem $menuItem)
    {
        // Check if menu item belongs to owner's restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        // Delete image file if exists
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return redirect()->route('owner.menu.index')->with('success', 'Menu item deleted successfully!');
    }
}