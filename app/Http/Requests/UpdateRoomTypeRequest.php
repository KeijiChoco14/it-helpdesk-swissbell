<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'it_admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('room_types')->ignore($this->route('room_type'))],
            'description' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
