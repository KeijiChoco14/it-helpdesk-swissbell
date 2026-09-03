<?php

namespace Database\Seeders;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $types = RoomType::all();
        
        if ($types->isEmpty()) {
            $this->call(RoomTypeSeeder::class);
            $types = RoomType::all();
        }

        $standard = $types->where('code', 'STD')->first();
        $superior = $types->where('code', 'SUP')->first();
        $deluxe = $types->where('code', 'DLX')->first();
        $executive = $types->where('code', 'EXE')->first();
        $presidential = $types->where('code', 'PRS')->first();

        // Let's create rooms for floors 1 to 4
        $floors = [
            1 => [ // Floor 1: mostly standard and superior
                ['type' => $standard, 'count' => 6],
                ['type' => $superior, 'count' => 4],
            ],
            2 => [ // Floor 2: superior and deluxe
                ['type' => $superior, 'count' => 5],
                ['type' => $deluxe, 'count' => 5],
            ],
            3 => [ // Floor 3: deluxe and executive
                ['type' => $deluxe, 'count' => 6],
                ['type' => $executive, 'count' => 4],
            ],
            4 => [ // Floor 4: executive and presidential
                ['type' => $executive, 'count' => 5],
                ['type' => $presidential, 'count' => 1],
            ],
        ];

        $statuses = RoomStatus::cases();

        foreach ($floors as $floorNumber => $config) {
            $roomIndex = 1;
            foreach ($config as $setup) {
                for ($i = 0; $i < $setup['count']; $i++) {
                    $roomNumber = sprintf("%d%02d", $floorNumber, $roomIndex);
                    
                    // Assign random status but bias towards AVAILABLE and OCCUPIED
                    $randomStatus = collect([
                        RoomStatus::AVAILABLE, RoomStatus::AVAILABLE, RoomStatus::AVAILABLE,
                        RoomStatus::OCCUPIED, RoomStatus::OCCUPIED, RoomStatus::OCCUPIED,
                        RoomStatus::DIRTY, RoomStatus::CLEANING,
                        RoomStatus::MAINTENANCE
                    ])->random();

                    Room::firstOrCreate(
                        ['room_number' => $roomNumber],
                        [
                            'floor' => $floorNumber,
                            'room_type_id' => $setup['type']->id,
                            'status' => $randomStatus,
                            'description' => null,
                        ]
                    );

                    $roomIndex++;
                }
            }
        }
    }
}
