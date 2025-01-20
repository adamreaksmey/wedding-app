<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;

class EventUserRequest extends FormRequest
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
            "event_id" => ['required', 'numeric', 'exists:events,id'],
            "user_id" => ['required', 'numeric', 'exists:users,id'],
            "coupon_id" => ["nullable", 'numeric', 'exists:coupons,id'],
            "start_date" => ["nullable", "date"],
            'end_date' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'paid_status' => ['nullable', 'in:unpaid,paid,refunded']
        ];
    }
}
