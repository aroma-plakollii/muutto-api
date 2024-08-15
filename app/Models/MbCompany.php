<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MbCompany extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'user_id',
        'business_number',
        'private_key',
        'api_key',
        'secret_key',
        'status',
    ];

    public function price()
    {
        return $this->hasOne(MbPrice::class);
    }

    public function bookings()
    {
        return $this->hasMany(MbBooking::class);
    }

    public function cities()
    {
        return $this->hasMany(MbFreeCity::class);
    }

    public function blockedDates()
    {
        return $this->hasMany(MbBlockedDates::class);
    }

    public function user()
    {
        return $this->belongsTo(MbCompany::class);
    }
}
