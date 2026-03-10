<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BranchStoreRequest extends FormRequest
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
            'branch_location' => 'required|string',
            'branch_name' => [
                'required',
                Rule::unique('branches')
                    ->where('eiin', getAuthInfo()['eiin'])
                    ->whereNull('deleted_at')
            ],
            'branch_name_en' => [
                Rule::unique('branches')
                    ->where('eiin', getAuthInfo()['eiin'])
                    ->whereNull('deleted_at')
            ],
            'head_of_branch_id' => 'required',
            // 'head_of_branch_id' => [
            //     'required',
            //     Rule::unique('branches')
            //         ->where('head_of_branch_id', $request->head_of_branch_id)
            //         ->where('eiin', app('sso-auth')->user()->eiin)
            //         ->whereNull('deleted_at')
            // ],
        ];
    }

    public function messages()
    {
        return [
            'branch_location.required' => 'অনুগ্রহ করে ব্রাঞ্চ ঠিকানা প্রদান করুন।',
            'branch_name.required' => 'অনুগ্রহ করে ব্রাঞ্চ নাম প্রদান করুন।',
            'branch_name.unique' => 'একই নামের ব্রাঞ্চ একই স্কুল এ দেয়া যাবে না।',
            'branch_name_en.unique' => 'একই নামের ব্রাঞ্চ একই স্কুল এ দেয়া যাবে না।',
            'head_of_branch_id.required' => 'অনুগ্রহ করে প্রধান শিক্ষক এর নাম প্রদান করুন।',
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