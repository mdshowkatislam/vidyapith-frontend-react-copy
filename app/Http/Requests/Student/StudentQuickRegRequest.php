<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentQuickRegRequest extends FormRequest
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
            'version' => 'required',
            'session' => 'required',
            'shift' => 'required',
            'students' => 'required|array|min:1',
            'students.*.student_unique_id' => [
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
            'version.required' => 'অনুগ্রহ করে ভার্সন নির্বাচন করুন।',
            'session.required' => 'অনুগ্রহ করে সেশন নির্বাচন করুন।',
            'shift.required' => 'অনুগ্রহ করে শিফট নির্বাচন করুন।',
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
