<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'url'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'url'],
            'price' => ['required', 'numeric', 'min:0'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'location' => ['nullable', 'json'],
        ];
    }
}
