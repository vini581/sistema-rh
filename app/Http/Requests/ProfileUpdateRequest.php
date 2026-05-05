<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        if ($this->user()->employee) {
            $empId = $this->user()->employee->id;
            $rules = array_merge($rules, [
                'rg' => ['nullable', 'string', 'max:20', Rule::unique('employees', 'rg')->ignore($empId)],
                'birth_date' => ['nullable', 'date'],
                'gender' => ['nullable', 'in:not_specified,male,female,other'],
                'marital_status' => ['nullable', 'in:single,married,divorced,widowed,other'],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:500'],
                'emergency_contact_name' => ['nullable', 'string', 'max:255'],
                'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            ]);
        }

        return $rules;
    }
}
