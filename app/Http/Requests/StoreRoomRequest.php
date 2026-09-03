<?php

namespace App\Http\Requests;

use App\Enums\RoomStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'it_admin';
    }

    public function rules(): array
    {
        return [
            'room_number' => ['required', 'string', 'max:50', 'unique:rooms,room_number'],
            'floor' => ['required', 'integer'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'status' => ['required', Rule::enum(RoomStatus::class)],
            'description' => ['nullable', 'string'],
        ];
    }
}
