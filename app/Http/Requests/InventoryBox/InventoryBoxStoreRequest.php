<?php

namespace App\Http\Requests\InventoryBox;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryBoxStoreRequest extends FormRequest
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
                Rule::unique('inventory_items')
                    ->where('eiin', app('sso-auth')->user()->eiin)
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->where('store_id', $this->request->get('store_id'))
                    ->where('rack_id', $this->request->get('rack_id'))
                    ->where('shelves_id', $this->request->get('shelves_id'))
                    ->whereNull('deleted_at')
            ],
            'store_id' => 'required',
            'rack_id' => 'required',
            'shelves_id' => 'required',
            'branch_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => 'অনুগ্রহ করে রেক নাম প্রদান করুন।',
            'name_en.unique' => 'একই রেক একাধিকবার দেয়া সম্ভব না।',
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
