<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'role' =>  ['sometimes', 'required'],
            'company_name' =>  ['sometimes', 'required'],
            'position' =>  ['required', 'sometimes'],
            'phoneNumber' => 'sometimes|required|regex:/(0)[0-9]{9}/'

        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name is required',
            'last_name.required' => 'The last name field is required',
            'email.required' => 'The email field is required',
            'company_name.required' => 'The company field is required',
            'position.required' => 'The position field is required',
            'phoneNumber.required' => "The phone number field is required",
            'role.required' => 'No Role Selected',
        ];
    }
}
