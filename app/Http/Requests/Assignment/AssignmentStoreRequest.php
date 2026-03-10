<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssignmentStoreRequest extends FormRequest
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
            'shift_id' => 'required',
            'version_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'assignment_no' => 'required',
            'assignment_name' => 'required',
            'subject_code' => 'required',
            'assignment_full_mark' => 'required',
            'assignment_submission_date' => 'required',
            'assignment_details_info' => 'required',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'branch_id.required' => 'অনুগ্রহ করে ক্যাম্পাস নির্বাচন করুন।',
            'shift_id.required' => 'অনুগ্রহ করে শিফট নির্বাচন করুন।',
            'version_id.required' => 'অনুগ্রহ করে ভার্সন নির্বাচন করুন।',
            'class_id.required' => 'অনুগ্রহ করে শ্রেণি নির্বাচন করুন।',
            'section_id.required' => 'অনুগ্রহ করে সেকশন নির্বাচন করুন।',
            'assignment_no.required' => 'অনুগ্রহ করে এসাইনমেন্ট নং প্রদান করুন।',
            'assignment_name.required' => 'অনুগ্রহ করে এসাইনমেন্ট নাম প্রদান করুন।',
            'subject_code.required' => 'অনুগ্রহ করে বিষয় কোড নির্বাচন করুন।',
            'assignment_full_mark.required' => 'অনুগ্রহ করে এসাইনমেন্ট পূর্ণমান প্রদান করুন।',
            'assignment_submission_date.required' => 'অনুগ্রহ করে জমা দেয়ার তারিখ প্রদান করুন।',
            'assignment_details_info.required' => 'অনুগ্রহ করে এসাইনমেন্টের বিস্তারিত তথ্য প্রদান করুন।',
            'status.required' => 'অনুগ্রহ করে স্ট্যাটাস নির্বাচন করুন।',
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
