<?php

namespace App\Http\Requests;

use App\Models\CleaningTask;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCleaningTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', CleaningTask::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'equipment_id' => ['required', 'exists:equipment,id'],
            'task_type' => ['required', 'string', 'in:cleaning_pc,thermal_paste,dust_removal,deep_clean,other'],
            'description' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ];
    }
}
