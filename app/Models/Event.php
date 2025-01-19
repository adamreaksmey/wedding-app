<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'events';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'gallery' => 'array',
        'location' => 'json',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->using(EventUser::class)
            ->withPivot('start_date', 'end_date', 'coupon_id', 'paid_status')
            ->withTimestamps();
    }
}
