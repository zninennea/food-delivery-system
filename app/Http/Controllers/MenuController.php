<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Get the restaurant (assuming one restaurant for now)
        $restaurant = Restaurant::first();
        
        // Get all menu items grouped by category
        $menuItemsByCategory = MenuItem::orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');
        
        // Get featured items (first 6 items)
        $featuredItems = MenuItem::limit(6)->get();

        return view('menu.public', compact(
            'menuItemsByCategory',
            'featuredItems',
            'restaurant'
        ));
    }

    public function show(MenuItem $menuItem)
    {
        // Get similar items
        $similarItems = MenuItem::where('category', $menuItem->category)
            ->where('id', '!=', $menuItem->id)
            ->limit(4)
            ->get();

        return view('menu.item-detail', compact('menuItem', 'similarItems'));
    }
}