<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MbBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'start_address',
        'end_address',
        'start_date',
        'end_date',
        'price',
        'start_price',
        'end_price',
        'rent_price',
        'type',
        'quantity',
        'start_door_number',
        'end_door_number',
        'start_door_code',
        'end_door_code',
        'start_comment',
        'end_comment',
        'payment_status',
        'progress_status',
    ];

    public function days()
    {
        return $this->hasMany(MbAdditionalDay::class, 'booking_id');
    }

    public function company()
    {
        return $this->belongsTo(MbCompany::class);
    }
}
