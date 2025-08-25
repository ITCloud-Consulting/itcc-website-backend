<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactRequest extends FormRequest
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
            //
            'name' => ['required', 'string', 'max:100', 'min:2'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'subject' => ['required', 'string', 'max:200', 'min:5'],
            'message' => ['required', 'string', 'max:2000', 'min:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'subject.required' => 'Le sujet est obligatoire.',
            'subject.min' => 'Le sujet doit contenir au moins 5 caractères.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Erreur de Validation',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    public function prepareForValidation()
    {
        $this->merge([
            'name' => trim($this->name),
            'email' => trim($this->email),
            'subject' => trim($this->subject),
            'message' => trim($this->message)
        ]);
    }
}
