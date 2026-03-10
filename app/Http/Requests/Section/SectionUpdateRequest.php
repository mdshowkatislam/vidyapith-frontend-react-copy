<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SectionUpdateRequest extends FormRequest
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
            'section_name' => [
                'required',
                Rule::unique('sections')
                    ->where('eiin', getAuthInfo()['eiin'])
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->where('version_id', $this->request->get('version_id'))
                    ->where('shift_id', $this->request->get('shift_id'))
                    ->where('class_id', $this->request->get('class_id'))
                    ->whereNot('uid', $this->request->get('uid'))
                    ->whereNull('deleted_at')
            ],
            'section_name_en' => [
                'required',
                Rule::unique('sections')
                    ->where('eiin', getAuthInfo()['eiin'])
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->where('version_id', $this->request->get('version_id'))
                    ->where('shift_id', $this->request->get('shift_id'))
                    ->where('class_id', $this->request->get('class_id'))
                    ->whereNot('uid', $this->request->get('uid'))
                    ->whereNull('deleted_at')
            ],
            'branch_id' => 'required',
            'shift_id' => 'required',
            'version_id' => 'required',
            'class_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'section_name.required' => 'অনুগ্রহ করে সেকশন নাম প্রদান করুন।',
            'section_name.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না।',
            'section_name_en.required' => 'অনুগ্রহ করে সেকশন নাম প্রদান করুন।',
            'section_name_en.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না।',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন।',
            'shift_id.required' => 'অনুগ্রহ করে শিফট প্রদান করুন।',
            'version_id.required' => 'অনুগ্রহ করে ভার্সন প্রদান করুন।',
            'class_id.required' => 'অনুগ্রহ করে শ্রেণী প্রদান করুন।',
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
