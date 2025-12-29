<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('businessEmployee'); 
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email',Rule::unique('users', 'email')->ignore($employee?->user_id)],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string'],
            'departments' => ['nullable', 'array'],
            'departments.*.id' => ['required','exists:business_departments,key'],
            'is_active' => ['required', 'bool'],
            'is_working' => ['required', 'bool'],
            'is_owner' => ['required', 'bool'],
        ]; 
    }

    public function messages(): array
    {
        $business = $this->route('business');
        $lang = $business?->lang ?? app()->getLocale();

        $translations = getTranslations($lang)['translations'];

        return [
            'name.required' => $translations['formValidation']['employee.name.required'] 
                ?? 'Employee name is required.',
            'name.max' => $translations['formValidation']['employee.name.max'] 
                ?? 'Employee name is too long.',
        ];
    }
}
