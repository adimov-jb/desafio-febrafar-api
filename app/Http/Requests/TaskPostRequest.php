<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;
class TaskPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'type_id' => 'required|integer',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'deadline' => 'required|date',
            'finish_date' => 'required|date',
            'status' => ['required', new Enum(TaskStatusEnum::class)],

        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
        ]));
    }
}
