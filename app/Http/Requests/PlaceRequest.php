<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlaceRequest extends FormRequest
{
    use ApiResponse;
    
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
        $rules = [
            'name' => 'required|string|unique:places,name',
            'city' => 'required|string',
            'state' => 'required|string'
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $field => $rule) {
                $rules[$field] = "sometimes|$rule";
            }
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
		$mappedErrors = [];
        $action = $this->isMethod('put') || $this->isMethod('patch') ? 'update' : 'create';

		foreach ($errors as $key => $messages) {
			$mappedErrors[$key] = $messages;
		}
        throw new HttpResponseException($this->errorResponse("Failed to $action the place", 400, $mappedErrors));
    }
}
