<?php

namespace App\Http\Requests\Items;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;


class CouponRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => 'nullable|string',
            'discount_type' => 'nullable|string|in:percentage,fixed_amount|max:255',
            'discount_value' => 'required|numeric',
            'usage_limit' => 'nullable|numeric',
            'used_count' => 'nullable|numeric',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ];
    }
}
