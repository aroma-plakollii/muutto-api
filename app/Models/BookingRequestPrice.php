<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequestPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_request_id',
        'company_id',
        'price'
    ];
}