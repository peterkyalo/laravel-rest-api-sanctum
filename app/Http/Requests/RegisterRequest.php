<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|min:5|max:150',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:25',
            'phone_number' => 'required|digits:10',
        ];
    }

    /**
     * Get the validation errors message that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages() : array
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.min' => 'Name must be at least 5 characters long.',
            'name.max' => 'Name must not exceed 150 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'Email already taken, please try with another email.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Password must be at least 5 characters long.',
            'password.max' => 'Password must not exceed 25 characters.',
            'phone_number.required' => 'Please enter your phone number.',
            'phone_number.digits' => 'Phone number must be exactly 10 digits long.',
        ];
    }
}
