<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StaffStoreRequest extends FormRequest
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
            'name_en' => 'required|max:150',
            'name_bn' => 'max:150',
            'mobile_no' => 'required|numeric',
            'nid' => 'nullable|string',
            'designation' => 'required',
            'division_id' => 'required_if:is_foreign,0',
            'district_id' => 'required_if:is_foreign,0',
            'upazila_id' => 'required_if:is_foreign,0',
            'country_uid' => 'required_if:is_foreign,1',
            'image' => 'mimes:jpeg,png,jpg|max:100',
            'signature' => 'mimes:jpeg,png,jpg|max:60',
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => 'অনুগ্রহ করে স্টাফের নাম প্রদান করুন।',
            'designation.required' => 'অনুগ্রহ করে স্টাফের পদবি প্রদান করুন।',
            'mobile_no.required' => 'অনুগ্রহ করে মোবাইল নম্বর প্রদান করুন।',
            'division_id.required_if' => 'অনুগ্রহ করে বিভাগ নির্বাচন করুন।',
            'district_id.required_if' => 'অনুগ্রহ করে জেলা নির্বাচন করুন।',
            'upazila_id.required_if' => 'অনুগ্রহ করে উপজেলা নির্বাচন করুন।',
            'country_uid.required_if' => 'অনুগ্রহ করে দেশ নির্বাচন করুন।',
            'image.mimes' => 'ছবি শুধুমাত্র JPG, JPEG, PNG হবে',
            'image.max' => 'ছবির সাইজ সর্বোচ্চ হবে 100 KB',
            'signature.mimes' => 'ছবি শুধুমাত্র JPG, JPEG, PNG হবে',
            'signature.max' => 'ছবির সাইজ সর্বোচ্চ হবে 60 KB',
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
