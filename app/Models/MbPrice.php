<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MbPrice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'price_per_km',
        'price_per_day',
        'price_per_package',
        'booking_price',
        'additional_price',
        'additional_package_price',
        'package_days',
        'included_km',
        'min_boxes',
        'type',
    ];

    public function company()
    {
        return $this->belongsTo(MbCompany::class);
    }
}
