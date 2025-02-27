<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $projectStartDate = $this->route('project')->getRawOriginal('start_date'); // Use raw date
                    if ($value < $projectStartDate) {
                        $fail('The task start date must not be before the project start date.');
                    }
                },
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) {
                    $projectStartDate = $this->route('project')->getRawOriginal('start_date'); // Use raw date
                    if ($value < $projectStartDate) {
                        $fail('The task end date must not be before the project start date.');
                    }
                },
                function ($attribute, $value, $fail) {
                    $projectEndDate = $this->route('project')->getRawOriginal('end_date'); // Use raw date
                    if ($value > $projectEndDate) {
                        $fail('The task end date must not be after the project end date.');
                    }
                },
            ],
            'assignee' => 'nullable|exists:users,id',
            'dependency' => 'nullable|exists:tasks,id',
        ];
    }
}
