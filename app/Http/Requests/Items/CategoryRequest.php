<?php

namespace App\Http\Requests\Items;

use Illuminate\Support\Facades\Validator;

class CategoryRequest
{
    public static function validated($data)
    {
        // Whitelist of fields that should be allowed
        $allowedFields = ['name', 'image'];

        // Only keep fields that are in the allowed list
        $filteredData = array_intersect_key($data, array_flip($allowedFields));

        $validator = Validator::make($filteredData, [
            'name' => 'required|string|max:255',
            'image' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            abort(response()->json(['errors' => $validator->errors()], 422));
        }

        return $validator->validated();
    }
}
