<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;
    protected $table = 'coupons';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'discount_value' => 'float',
    ];

    /**
     * Check if the coupon is valid based on current conditions.
     */
    public function isValid()
    {
        $now = now();
        return $this->status === 'active' &&
            ($this->valid_from === null || $this->valid_from <= $now) &&
            ($this->valid_until === null || $this->valid_until >= $now) &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
