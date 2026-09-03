<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Standard Room',
                'code' => 'STD',
                'description' => 'A basic room with essential amenities.',
                'capacity' => 2,
            ],
            [
                'name' => 'Superior Room',
                'code' => 'SUP',
                'description' => 'A comfortable room with extra space.',
                'capacity' => 2,
            ],
            [
                'name' => 'Deluxe Room',
                'code' => 'DLX',
                'description' => 'A premium room with upgraded features and view.',
                'capacity' => 2,
            ],
            [
                'name' => 'Executive Suite',
                'code' => 'EXE',
                'description' => 'A spacious suite with a separate living area.',
                'capacity' => 4,
            ],
            [
                'name' => 'Presidential Suite',
                'code' => 'PRS',
                'description' => 'The most luxurious suite in the hotel.',
                'capacity' => 4,
            ],
        ];

        foreach ($types as $type) {
            RoomType::firstOrCreate(['code' => $type['code']], $type);
        }
    }
}
