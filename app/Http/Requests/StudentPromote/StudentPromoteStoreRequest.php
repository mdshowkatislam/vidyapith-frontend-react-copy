<?php

namespace App\Http\Requests\StudentPromote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentPromoteStoreRequest extends FormRequest
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
            'branch_uid' => 'required',
            'shift_uid' => 'required',
            'version_uid' => 'required',
            'class_uid' => 'required',
            'section_uid' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'branch_uid.required' => 'অনুগ্রহ করে ব্রাঞ্চ নির্বাচন করুন।',
            'shift_uid.required' => 'অনুগ্রহ করে শিফট নির্বাচন করুন।',
            'version_uid.required' => 'অনুগ্রহ করে ভার্সন নির্বাচন করুন।',
            'class_uid.required' => 'অনুগ্রহ করে শ্রেণী নির্বাচন করুন।',
            'section_uid.required' => 'অনুগ্রহ করে সেকশন নির্বাচন করুন।',
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
