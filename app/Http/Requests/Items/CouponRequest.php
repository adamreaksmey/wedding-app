<?php

namespace App\Http\Requests\Items;

use Illuminate\Support\Facades\Validator;


class CouponRequest
{
    public static function validated($data)
    {
        // Whitelist of fields that should be allowed
        $allowedFields = [
            'description',
            'discount_type',
            'discount_value',
            'usage_limit',
            'used_count',
            'valid_from',
            'valid_until'
        ];

        // Only keep fields that are in the allowed list
        $filteredData = array_intersect_key($data, array_flip($allowedFields));

        $validator = Validator::make($filteredData, [
            'description' => 'nullable|string',
            'discount_type' => 'nullable|string|in:percentage,fixed_amount|max:255',
            'discount_value' => 'required|numeric',
            'usage_limit' => 'nullable|numeric',
            'used_count' => 'nullable|numeric',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        if ($validator->fails()) {
            abort(response()->json(['errors' => $validator->errors()], 422));
        }

        return $validator->validated();
    }
}
