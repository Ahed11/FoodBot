<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'FoodUser',
            'email' => 'Food@Food.com',
            'password' => bcrypt('FoodPass'),
        ]);

        Dish::factory()->create([
            'name' => 'makluba',
            'price' => 50
        ]);
    }
}
