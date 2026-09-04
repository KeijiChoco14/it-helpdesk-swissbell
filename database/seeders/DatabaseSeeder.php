<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\ServiceRequest;
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

        // Create Specific Housekeeping Staff for testing
        $tono = User::create([
            'name' => 'Tono Housekeeping',
            'email' => 'tono@hotel.com',
            'password' => bcrypt('password'),
            'role' => 'housekeeping',
            'department_id' => 2, // Housekeeping
        ]);

        // Create Housekeeping Staff
        User::factory(3)->create([
            'role' => 'housekeeping',
            'department_id' => 2, // Housekeeping
        ]);

        // Create Employees
        User::factory(5)->create([
            'role' => 'employee',
            'department_id' => 1, // Front Office
        ]);

        // Create Tickets
        ServiceRequest::factory(20)->create(function () {
            return [
                'user_id' => User::where('role', 'employee')->inRandomOrder()->first()->id ?? 1,
                'department_id' => Department::inRandomOrder()->first()->id ?? 1,
                'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            ];
        });

        // Optional: Assign some service requests to rooms
        $rooms = \App\Models\Room::all();
        if ($rooms->count() > 0) {
            \App\Models\ServiceRequest::all()->each(function ($req) use ($rooms) {
                if (rand(1, 100) <= 30) {
                    $req->room_id = $rooms->random()->id;
                    $req->save();
                }
            });

            // Seed Housekeeping Tasks for Tono
            for ($i = 0; $i < 3; $i++) {
                \App\Models\HousekeepingTask::create([
                    'room_id' => $rooms->random()->id,
                    'task_type' => 'Daily Cleaning',
                    'priority' => 'Medium',
                    'status' => \App\Enums\HousekeepingTaskStatus::ASSIGNED,
                    'assigned_to' => $tono->id,
                    'scheduled_at' => now()->addHours($i),
                    'notes' => 'Seeded task for testing housekeeping dashboard.',
                ]);
            }
        }
    }
}




