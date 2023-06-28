<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePersonalInformationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'cell_number' => 'required',
            'home_number' => 'required',
            'work_number' => 'required',
            'id_number' => 'required',
            'unit_number_and_complex_name' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'postal_code' => 'required'
        ];
    }


    public function messages(): array
    {
        return [
        ];
    }


    /**
* Get the error messages for the defined validation rules.*
* @return array
*/
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
