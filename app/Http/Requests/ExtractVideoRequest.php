<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExtractVideoRequest extends FormRequest
{
    /**
     * All requests to this endpoint are allowed (no auth required).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the extraction request.
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'string',
                'max:2048',
                // Must be a valid Twitter or X URL
                'regex:/^https?:\/\/(www\.)?(twitter\.com|x\.com)\/[a-zA-Z0-9_]+\/status\/\d+(?:\/?[a-zA-Z0-9_\-\.\?\&%\=]*)?$/i',
            ],
        ];
    }

    /**
     * Human-readable error messages.
     */
    public function messages(): array
    {
        return [
            'url.required' => 'A Twitter or X video URL is required.',
            'url.regex'    => 'The URL must be a valid twitter.com or x.com link.',
            'url.max'      => 'The URL is too long.',
        ];
    }

    /**
     * Override default behavior to return a JSON response on validation failure.
     */
    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(
            response()->json([
                'error'   => 'Validation failed',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
