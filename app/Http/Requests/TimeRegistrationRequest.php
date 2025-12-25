<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|numeric|min:0.25|max:24',
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(\App\Models\TimeRegistration::getStatuses())),
            'location' => 'nullable|string|max:255',
            'distance' => 'nullable|integer|min:0|max:999999',
        ];
    }
}
