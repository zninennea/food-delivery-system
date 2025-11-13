<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Owner User
        $owner = User::create([
            'name' => 'NaNi Owner',
            'email' => 'owner@nani.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '09194445566',
        ]);

        // Create Restaurant
        $restaurant = Restaurant::create([
            'owner_id' => $owner->id,
            'name' => 'NaNi Japanese Restaurant',
            'address' => 'JP Laurel Ave, Davao City',
            'phone' => '09194445566',
            'facebook_url' => 'https://facebook.com/nanirestaurant',
        ]);

        // Create Sample Rider
        $rider = User::create([
            'name' => 'Cardo Dalisay',
            'email' => 'rider@nani.com',
            'password' => Hash::make('password'),
            'role' => 'rider',
            'phone' => '09197778888',
        ]);

        // Create Sample Customer
        $customer = User::create([
            'name' => 'John Customer',
            'email' => 'customer@nani.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '09196667777',
        ]);

        // Create Sample Menu Items
        $menuItems = [
            [
                'name' => 'Okonomiyaki',
                'description' => 'Japanese savory pancake with various ingredients',
                'price' => 180.00,
                'category' => 'main',
                'image' => null,
            ],
            [
                'name' => 'California Maki',
                'description' => 'Sushi roll with crab, avocado, and cucumber',
                'price' => 220.00,
                'category' => 'sushi',
                'image' => null,
            ],
            [
                'name' => 'Tonkotsu Ramen',
                'description' => 'Rich pork bone broth ramen with chashu pork',
                'price' => 250.00,
                'category' => 'ramen',
                'image' => null,
            ],
            [
                'name' => 'Chicken Teriyaki Don',
                'description' => 'Grilled chicken with teriyaki sauce over rice',
                'price' => 160.00,
                'category' => 'donburi',
                'image' => null,
            ],
            [
                'name' => 'Edamame',
                'description' => 'Steamed young soybeans with sea salt',
                'price' => 80.00,
                'category' => 'appetizer',
                'image' => null,
            ],
            [
                'name' => 'Mochi Ice Cream',
                'description' => 'Japanese rice cake with ice cream filling',
                'price' => 120.00,
                'category' => 'dessert',
                'image' => null,
            ],
            [
                'name' => 'Green Tea',
                'description' => 'Traditional Japanese green tea',
                'price' => 50.00,
                'category' => 'beverage',
                'image' => null,
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create(array_merge($item, ['restaurant_id' => $restaurant->id]));
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Owner Login: owner@nani.com / password');
        $this->command->info('Rider Login: rider@nani.com / password');
        $this->command->info('Customer Login: customer@nani.com / password');
    }
}