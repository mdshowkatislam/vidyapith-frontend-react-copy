<?php

namespace App\Http\Requests\InventoryCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryCategoryStoreRequest extends FormRequest
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
            'name_en' => [
                'required',
                Rule::unique('inventory_categories')
                    ->where('eiin', app('sso-auth')->user()->eiin)
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->whereNull('deleted_at')
            ],
            'branch_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => 'অনুগ্রহ করে ক্যাটাগরি নাম প্রদান করুন।',
            'name_en.unique' => 'একই ক্যাটাগরি একাধিকবার দেয়া সম্ভব না।',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন।',
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
