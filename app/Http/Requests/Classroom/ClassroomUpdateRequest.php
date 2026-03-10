<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClassroomUpdateRequest extends FormRequest
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
            'session_year' => 'required',
            'class_teacher_id' => 'required',
            'section_id' => [
                'required',
                Rule::unique('class_rooms')
                    ->where('eiin', app('sso-auth')->user()->eiin)
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->where('version_id', $this->request->get('version_id'))
                    ->where('shift_id', $this->request->get('shift_id'))
                    ->where('class_id', $this->request->get('class_id'))
                    ->where('session_year', $this->request->get('session_year'))
                    ->whereNot('uid', $this->request->get('uid'))
                    ->whereNull('deleted_at')
            ],
        ];
    }

    public function messages()
    {
        return [
            'section_id.required' => 'অনুগ্রহ করে সেকশন প্রদান করুন।',
            'section_id.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না।',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন।',
            'shift_id.required' => 'অনুগ্রহ করে শিফট প্রদান করুন।',
            'version_id.required' => 'অনুগ্রহ করে ভার্সন প্রদান করুন।',
            'class_id.required' => 'অনুগ্রহ করে শ্রেণী প্রদান করুন।',
            'session_year.required' => 'অনুগ্রহ করে শিক্ষাবর্ষ প্রদান করুন।',
            'class_teacher_id.required' => 'অনুগ্রহ করে শ্রেণী শিক্ষক প্রদান করুন।',
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
