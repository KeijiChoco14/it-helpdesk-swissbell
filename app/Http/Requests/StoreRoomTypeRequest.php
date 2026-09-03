<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'it_admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:room_types,code'],
            'description' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
