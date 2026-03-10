<?php

namespace App\Http\Requests\InventoryProduct;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryProductStoreRequest extends FormRequest
{
    use ApiResponser;
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
            'branch_id' => 'required',
            'category_id' => 'required',
            'item_id' => 'required',
            'unique_no' => 'required',
        ];
    }

    public function messages()
    {
        return [
            // 'name_en.required' => 'অনুগ্রহ করে প্রোডাক্ট নাম প্রদান করুন।',
            // 'name_en.unique' => 'একই প্রোডাক্ট একাধিকবার দেয়া সম্ভব না।',
            // 'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন।',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->messages();

        if (isApi()) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => $errors,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        parent::failedValidation($validator);
    }
}
