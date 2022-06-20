<?php

namespace App\Http\Requests;

use App\Src\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'parent_id'=> 'required',
            'name'=> 'required|unique:categories,name'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponse::error($validator->errors()->first(), 422));
    }
}
