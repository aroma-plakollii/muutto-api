<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'booking_number',
        'start_date',
        'end_date',
        'first_name',
        'last_name',
        'email',
        'phone',
        'start_address',
        'end_address',
        'start_door_number',
        'end_door_number',
        'start_door_code',
        'end_door_code',
        'start_floor',
        'end_floor',
        'start_elevator',
        'end_elevator',
        'start_outdoor_distance',
        'end_outdoor_distance',
        'start_storage',
        'end_storage',
        'start_storage_m2',
        'end_storage_m2	',
        'start_storage_floor',
        'end_storage_floor',
        'start_square_meters',
        'end_square_meters',
    ];
}