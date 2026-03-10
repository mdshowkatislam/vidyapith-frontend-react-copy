<?php

namespace App\Http\Requests\FinalExam;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FinalExamStoreRequest extends FormRequest
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
            'class_id' => 'nullable',
            'section_id' => 'required',
            'subject_code' => 'required',
            'exam_no' => 'required|max:50',
            'exam_name' => 'required|string|max:150',
            'exam_full_mark' => 'required|max:50',
            'exam_date' => 'required',
            'exam_time' => 'required',
            // 'exam_start_time' => 'required',
            // 'exam_end_time' => 'required',
            'exam_details_info' => 'required|string|max:150',
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
            'subject_code.required' => 'অনুগ্রহ করে বিষয় কোড নির্বাচন করুন।',
            'exam_no.required' => 'অনুগ্রহ করে পরীক্ষা নং প্রদান করুন।',
            'exam_name.required' => 'অনুগ্রহ করে পরীক্ষার নাম প্রদান করুন।',
            'exam_full_mark.required' => 'অনুগ্রহ করে পরীক্ষার পূর্ণমান প্রদান করুন।',
            'exam_date.required' => 'অনুগ্রহ করে তারিখ প্রদান করুন।',
            'exam_time.required' => 'অনুগ্রহ করে বরাদ্দকৃত সময় প্রদান করুন।',
            'exam_start_time.required' => 'অনুগ্রহ করে পরীক্ষা শুরুর সময় প্রদান করুন।',
            'exam_end_time.required' => 'অনুগ্রহ করে পরীক্ষার শেষ সময় প্রদান করুন।',
            'exam_details_info.required' => 'অনুগ্রহ করে পরিক্ষার বিস্তারিত তথ্য প্রদান করুন।',
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
