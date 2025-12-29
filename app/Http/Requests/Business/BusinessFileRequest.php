<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class BusinessFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('businessFile') !== null;
        return [
            'business_client_id' => ['required', 'exists:business_clients,client_key'],
            'country_file_category_id' => ['required', 'exists:country_file_categories,slug'],
            'country_file_type_id' => ['nullable', 'exists:country_file_types,slug'],
            'country_file_status_id' => ['nullable', 'exists:country_file_statuses,key'],

            'document_date' => ['nullable', 'date'],
            'reference_number' => ['nullable', 'string', 'max:255'],

           'file' => $isUpdate
                    ? [] 
                    : ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xlsx,jpg,jpeg,png'],
        ]; 
    }

    public function messages(): array
    {
        return [
            'business_client_id.required' => 'Klijent je obavezan.',
            'business_client_id.exists' => 'Odabrani klijent ne postoji.',

            'country_file_category_id.required' => 'Kategorija dokumenta je obavezna.',
            'country_file_category_id.exists' => 'Odabrana kategorija ne postoji.',

            'file.required' => 'Dokument je obavezan.',
            'file.mimes' => 'Dokument mora biti PDF ili slika.',
            'file.max' => 'Dokument ne smije biti veći od 10MB.',
            'file.file' => 'Dokument mora biti validan fajl.',
        ];
    }
}
