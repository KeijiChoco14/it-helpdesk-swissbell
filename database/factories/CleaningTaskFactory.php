<?php

namespace Database\Factories;

use App\Models\CleaningTask;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CleaningTask>
 */
class CleaningTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'in_progress', 'completed']);
        $scheduledAt = $this->faker->dateTimeBetween('-1 month', '+1 month');

        return [
            'equipment_id' => Equipment::factory(),
            'performed_by' => User::factory()->state(['role' => 'it_support']),
            'task_type' => $this->faker->randomElement(['cleaning_pc', 'thermal_paste', 'dust_removal', 'deep_clean', 'other']),
            'description' => $this->faker->sentence(),
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'completed_at' => $status === 'completed' ? $this->faker->dateTimeBetween($scheduledAt, 'now') : null,
            'notes' => $status === 'completed' && $this->faker->boolean() ? $this->faker->sentence() : null,
        ];
    }
}
