<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'region_id',
        'name',
        'address',
        'price',
        'persons',
        'capacity',
        'availability',
        'max_shift',
        'start_time',
        'end_time',
        'image'
    ];

    public function company(){
        return $this->belongsTo(MsCompany::class);
    }

    public function bookings(){
        return $this->hasMany(MsBooking::class, 'unit_id');
    }

    public function ms_unit_availabilities(){
        return $this->hasMany(MsUnitAvailability::class, 'unit_id');
    }

}
