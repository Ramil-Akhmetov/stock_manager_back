<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class IndexActivityRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_by' => 'nullable|string|in:created_at,log_name,description,subject_type',

            'order' => 'nullable|string|in:asc,desc',
            'limit' => 'nullable|integer|min:1',
            'search' => 'nullable|string',
        ];
    }
}
