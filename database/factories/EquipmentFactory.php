<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['pc', 'laptop', 'printer', 'monitor', 'network', 'other'];
        $type = $this->faker->randomElement($types);

        $brand = match ($type) {
            'pc', 'laptop' => $this->faker->randomElement(['Dell', 'HP', 'Lenovo', 'Apple']),
            'printer' => $this->faker->randomElement(['Epson', 'Canon', 'HP']),
            'monitor' => $this->faker->randomElement(['LG', 'Samsung', 'Dell', 'BenQ']),
            'network' => $this->faker->randomElement(['Cisco', 'MikroTik', 'Ubiquiti']),
            default => $this->faker->word(),
        };

        return [
            'user_id' => $this->faker->boolean(70) ? User::factory() : null,
            'name' => ucfirst($type).' '.$this->faker->unique()->numerify('##'),
            'type' => $type,
            'brand' => $brand,
            'model' => $this->faker->word().'-'.$this->faker->numerify('###'),
            'serial_number' => $this->faker->unique()->bothify('SN-????-####'),
            'location' => $this->faker->randomElement(['Front Desk', 'Back Office', 'Room 101', 'Room 202', 'Lobby']),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'maintenance', 'retired']),
            'purchase_date' => $this->faker->dateTimeBetween('-3 years', 'now'),
            'notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
        ];
    }
}
