<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InstituteUpdateRequest extends FormRequest
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
            'institute_name'            => 'required',
            'institute_name_bn'         => 'required',
            'eiin'                      => 'required',
            'caid'                      => 'required',
            'is_foreign'                => 'required',
            // 'board_uid'                 => 'required',
            'category'                  => 'required',
            'phone'                     => 'required_if:is_foreign,0',
            // 'division_id'               => 'required_if:is_foreign,0',
            // 'district_id'               => 'required_if:is_foreign,0',
            // 'upazila_id'                => 'required_if:is_foreign,0',
            'country'                   => 'required_if:is_foreign,1',
            'email'                     => 'required_if:is_foreign,1',
            // 'logo'                      => 'mimes:jpg,png,jpeg|max:300',
        ];
    }

    public function messages()
    {
        return [
            'institute_name.required'   => 'অনুগ্রহ করে প্রতিষ্ঠানের নাম (ইংরেজি) প্রদান করুন।',
            'institute_name_bn.required'=> 'অনুগ্রহ করে প্রতিষ্ঠানের নাম (বাংলা) প্রদান করুন।',
            'eiin.required'             => 'অনুগ্রহ করে EIIN প্রদান করুন।',
            'caid.required'             => 'অনুগ্রহ করে CAID প্রদান করুন।',
            'is_foreign.required'       => 'প্রতিষ্ঠানটি দেশি/বিদেশী?।',
            'board_uid.required'        => 'বোর্ড নির্বাচন করুন।',
            'category.required'         => 'প্রতিষ্ঠানের ধরন নির্বাচন করুন।',
            'phone.required_if'         => 'অনুগ্রহ করে মোবাইল নম্বর প্রদান করুন।',
            'division_id.required_if'   => 'বিভাগ নির্বাচন করুন।',
            'district_id.required_if'   => 'জেলা নির্বাচন করুন।',
            'upazila_id.required_if'    => 'উপজেলা নির্বাচন করুন।',
            'country.required_if'       => 'দেশ নির্বাচন করুন।',
            'email.required_if'         => 'অনুগ্রহ করে ইমেইল প্রদান করুন।',
            'logo.mimes'                => 'লোগো শুধুমাত্র JPG, JPEG, PNG হবে।',
            'logo.max'                  => 'লোগো এর সাইজ সর্বোচ্চ হবে 300 KB।'
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
