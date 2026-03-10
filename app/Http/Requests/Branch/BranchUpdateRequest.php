<?php

namespace App\Http\Requests\Branch;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class BranchUpdateRequest extends FormRequest
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
        $validation_rules = [
            'branch_location' => 'required|string',
            'branch_name' => [
                'required',
                Rule::unique('branches')->where(function ($query) {
                    return $query
                        ->where('eiin', getAuthInfo()['eiin'])
                        ->where('branch_name', $this->request->get('branch_name'))
                        ->where('uid', '!=', $this->request->get('uid'))
                        ->whereNull('deleted_at');
                })
            ],
            'branch_name_en' => [
                Rule::unique('branches')->where(function ($query) {
                    return $query
                        ->where('eiin', getAuthInfo()['eiin'] )
                        ->where('branch_name_en', $this->request->get('branch_name_en'))
                        ->where('uid', '!=', $this->request->get('uid'))
                        ->whereNull('deleted_at');
                })
            ],
            'head_of_branch_id' => 'required',
        ];
        if ($this->request->get('branch_location') == null) {
            $validation_rules['branch_location'] = 'required|string';
        }

        return $validation_rules;
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