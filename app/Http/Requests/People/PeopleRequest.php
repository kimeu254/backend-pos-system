<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;

class PeopleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'country' => 'required',
            'city' => 'required',
            'address' => 'required'
        ];
    }
}
