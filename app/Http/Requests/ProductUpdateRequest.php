<?php

namespace App\Http\Requests;

use App\Src\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductUpdateRequest extends FormRequest
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
            'category_id'=> 'required|exists:categories,id',
            'name'=> 'required',
            'images'=> 'required|array',
            'first_price'=> 'required',
            'second_price'=> 'required',
            'description'=> 'required',
            'discount'=> 'required',
            'size'=> 'nullable|array',
            'color'=> 'nullable|array',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponse::error($validator->errors()->first(), 422));
    }
}
