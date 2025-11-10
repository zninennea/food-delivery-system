<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('restaurants')->delete();
        DB::table('users')->delete();

        // Create default owner
        $owner = User::create([
            'name' => 'NaNi Owner',
            'email' => 'nani_owner@nani.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '09194445566',
        ]);

        // Create default restaurant for the owner
        Restaurant::create([
            'owner_id' => $owner->id,
            'name' => 'NaNi Restaurant',
            'address' => 'JP Laurel Ave, Davao City',
            'phone' => '09194445566',
            'facebook_url' => 'facebook.com/NaNiMediauranti',
        ]);

        // Create default rider
        User::create([
            'name' => 'NaNi Rider',
            'email' => 'nani_rider@nani.com',
            'password' => Hash::make('password'),
            'role' => 'rider',
            'phone' => '09184445566',
        ]);

        // Create sample customer
        User::create([
            'name' => 'Sample Customer',
            'email' => 'customer@nani.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '09174445566',
        ]);

        $this->command->info('Default users created:');
        $this->command->info('Owner: nani_owner@nani.com / password');
        $this->command->info('Rider: nani_rider@nani.com / password');
        $this->command->info('Customer: customer@nani.com / password');
    }
}