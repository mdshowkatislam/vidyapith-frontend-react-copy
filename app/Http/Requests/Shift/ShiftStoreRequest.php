<?php

namespace App\Http\Requests\Shift;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ShiftStoreRequest extends FormRequest
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
        if (isLocalWithTestAuth()) {
            $eiinId = getTestAuthentication()['eiinId'];
        } else {
            $eiinId = app('sso-auth')->user()->eiin;
        }

        // \Log::info($eiinId);
        return [
            'shift_name_en' => [
                'required',
                Rule::unique('shifts')
                    ->where('eiin', $eiinId)
                    // ->where('shift_start_time', $request->shift_start_time)
                    // ->where('shift_end_time', $request->shift_end_time)
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->whereNull('deleted_at')
            ],
            'shift_start_time' => [
                'required',
                Rule::unique('shifts')
                    ->where('eiin', $eiinId)
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->whereNull('deleted_at')
            ],
            'shift_end_time' => [
                'required',
                'date_format:H:i',
                'after:shift_start_time',
                Rule::unique('shifts')
                    ->where('eiin', $eiinId)
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->whereNull('deleted_at')
            ],
            'branch_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'shift_name_en.required' => 'অনুগ্রহ করে শিফট নাম প্রদান করুন।',
            'shift_name_en.unique' => 'একই শিফট একাধিকবার দেয়া সম্ভব না।',
            'shift_start_time.required' => 'অনুগ্রহ করে শিফট শুরু হওয়ার সময় প্রদান করুন।',
            'shift_start_time.unique' => 'এই সময় স্লট আর খালি নেই।',
            'shift_end_time.required' => 'অনুগ্রহ করে শেষ হওয়ার সময় প্রদান করুন।',
            'shift_end_time.unique' => 'এই সময় স্লট আর খালি নেই।',
            'shift_end_time.after' => 'শেষ হওয়ার সময় অবশ্যই শুরুর পরবর্তী সময় হতে হবে।',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন।',
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
