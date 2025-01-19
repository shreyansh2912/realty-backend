<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class updateReaquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'name'=>'required',
            'location'=>'required',
            'price'=>'required|numeric',
            'property_type'=>'required',
            'image'=>'required|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->errorJson([], [
                'errors' => $validator->errors()
            ], 400)
        );
    }
}
