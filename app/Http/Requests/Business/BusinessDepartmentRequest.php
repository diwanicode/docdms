<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class BusinessDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
         
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'employees' => ['nullable', 'array'],
            'employees.*.id' => ['required','exists:business_employees,employee_key'],
            'permissions' => ['nullable', 'array'],
            'permissions.*.id' => ['required','exists:permissions,slug'],
        ]; 
    }

    public function messages(): array
    {
        $business = $this->route('business');
        $lang = $business?->lang ?? app()->getLocale();

        $translations = getTranslations($lang)['translations'];

        return [
            'name.required' => $translations['formValidation']['department.name.required'] 
                ?? 'Department name is required.',
            'name.max' => $translations['formValidation']['department.name.max'] 
                ?? 'Department name is too long.',
        ];
    }
}
