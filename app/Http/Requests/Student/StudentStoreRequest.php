<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentStoreRequest extends FormRequest
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
        $authInfo = getAuthInfo();
        $eiinId = $authInfo['eiin'];
        return [
            'branch' => 'required',
            'shift' => 'required',
            'version' => 'required',
            'class' => 'required',
            'section' => 'required',
            'registration_year' => 'required',
            'roll' => 'required',
            'student_name_en' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'father_name_en' => 'required',
            'father_mobile_no' => 'required',
            // 'image' => 'mimes:jpeg,png,jpg|max:100',
            'student_unique_id' => [
                'required',
                function ($attribute, $value, $fail) use($eiinId) {
                    $exists = \App\Models\Student::where('eiin', $eiinId) 
                        ->where('student_unique_id', $value)
                        ->exists();
                    if ($exists) {
                        $fail('এই EIIN এর অধীনে Student Unique ID ('.$value.') ইতিমধ্যেই বিদ্যমান।');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'branch.required' => 'অনুগ্রহ করে ব্রাঞ্চ নির্বাচন করুন।',
            'shift.required' => 'অনুগ্রহ করে শিফট নির্বাচন করুন।',
            'version.required' => 'অনুগ্রহ করে ভার্সন নির্বাচন করুন।',
            'class.required' => 'অনুগ্রহ করে শ্রেণী নির্বাচন করুন।',
            'section.required' => 'অনুগ্রহ করে সেকশন নির্বাচন করুন।',
            'registration_year.required' => 'অনুগ্রহ করে সন প্রদান করুন।',
            'roll.required' => 'অনুগ্রহ করে রোল নম্বর প্রদান করুন।',
            'student_name_en.required' => 'অনুগ্রহ করে শিক্ষার্থীর নাম (ইংরেজি) প্রদান করুন।',
            'gender.required' => 'অনুগ্রহ করে লিঙ্গ নির্বাচন করুন।',
            'religion.required' => 'অনুগ্রহ করে ধর্ম নির্বাচন করুন।',
            'father_name_en.required' => 'অনুগ্রহ করে পিতার নাম (ইংরেজি) প্রদান করুন।',
            'father_mobile_no.required' => 'অনুগ্রহ করে পিতার মোবাইল নম্বর প্রদান করুন।',
            // 'image.mimes' => 'ছবি শুধুমাত্র JPG, JPEG, PNG হবে',
            // 'image.max' => 'ছবির সাইজ সর্বোচ্চ হবে 100 KB',
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
