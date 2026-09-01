<?php

namespace App\Models;

use Database\Factories\EquipmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'name', 'type', 'brand', 'model', 'serial_number', 'location', 'status', 'purchase_date', 'notes'])]
class Equipment extends Model
{
    /** @use HasFactory<EquipmentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cleaningTasks()
    {
        return $this->hasMany(CleaningTask::class);
    }

    public function assignments()
    {
        return $this->hasMany(EquipmentAssignment::class)->latest('assigned_at');
    }
}
