<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $client = $this->route('businessClient'); 
        return [
            'name' => ['required', 'string', 'max:255'],
            'business_type_id' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'vat_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email',Rule::unique('users', 'email')->ignore($client?->user_id)],
            'number' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'is_active' => ['required', 'bool']
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
