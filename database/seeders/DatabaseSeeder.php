<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $departments = [
            'Front Office', 'Housekeeping', 'Food & Beverage',
            'Finance', 'Human Resources', 'Sales', 'Engineering', 'IT',
        ];

        foreach ($departments as $dept) {
            Department::create(['name' => $dept, 'is_active' => true]);
        }

        $categories = [
            ['name' => 'Computer', 'type' => 'Hardware'],
            ['name' => 'Printer', 'type' => 'Hardware'],
            ['name' => 'Internet', 'type' => 'Network'],
            ['name' => 'Application Error', 'type' => 'Software'],
            ['name' => 'Password', 'type' => 'Account'],
        ];

        foreach ($categories as $cat) {
            Category::create(['name' => $cat['name'], 'type' => $cat['type'], 'is_active' => true]);
        }

        // Create IT Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@hotel.com',
            'role' => 'it_admin',
            'department_id' => 8, // IT
        ]);

        // Create IT Support
        User::factory(2)->create([
            'role' => 'it_support',
            'department_id' => 8, // IT
        ]);

        // Create Employees
        User::factory(5)->create([
            'role' => 'employee',
            'department_id' => 1, // Front Office
        ]);

        // Create Tickets
        Ticket::factory(20)->create(function () {
            return [
                'user_id' => User::where('role', 'employee')->inRandomOrder()->first()->id ?? 1,
                'department_id' => Department::inRandomOrder()->first()->id ?? 1,
                'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            ];
        });
    }
        // Optional: Assign some service requests to rooms
        $rooms = \App\Models\Room::all();
        if ($rooms->count() > 0) {
            \App\Models\ServiceRequest::all()->each(function ($req) use ($rooms) {
                // 30% chance to assign a room to existing requests
                if (rand(1, 100) <= 30) {
                    $req->room_id = $rooms->random()->id;
                    $req->save();
                }
            });
        }
    }
}
