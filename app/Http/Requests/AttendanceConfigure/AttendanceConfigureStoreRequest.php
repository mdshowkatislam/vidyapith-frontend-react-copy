<?php

namespace App\Http\Requests\AttendanceConfigure;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AttendanceConfigureStoreRequest extends FormRequest
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
            'mode' => 'required|in:normal,configurable', 
            // 'normal_value' => 'required_if:mode,normal|numeric|min:0|max:100',
            'rules' => 'required_if:mode,configurable|array',
            'rules.*.from' => 'required_if:mode,configurable|numeric|min:0|max:100',
            'rules.*.to' => 'required_if:mode,configurable|numeric|min:0|max:100',
            'rules.*.value' => 'required_if:mode,configurable|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            // 'branch_id.required' => 'অনুগ্রহ করে ক্যাম্পাস নির্বাচন করুন।',
            // 'shift_id.required' => 'অনুগ্রহ করে শিফট নির্বাচন করুন।',
            // 'version_id.required' => 'অনুগ্রহ করে ভার্সন নির্বাচন করুন।',
            // 'class_id.required' => 'অনুগ্রহ করে শ্রেণি নির্বাচন করুন।',
            // 'section_id.required' => 'অনুগ্রহ করে সেকশন নির্বাচন করুন।',
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
