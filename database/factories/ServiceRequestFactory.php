<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Department;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class ServiceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_number' => 'IT-'.date('Y').'-'.str_pad((string) $this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'category_id' => Category::factory(),
            'assigned_to' => null,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'status' => 'OPEN',
            'location' => $this->faker->optional()->city(),
            'device' => $this->faker->optional()->word(),
        ];
    }
}

