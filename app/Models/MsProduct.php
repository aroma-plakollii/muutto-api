<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'name',
        'capacity_info',
        'description',
        'duration',
        'image'
    ];

    public function company(){
        return $this->belongsTo(MsCompany::class);
    }

    public function bookings()
    {
        return $this->hasMany(MsBooking::class, 'product_id');
    }

    public function companyProductPrice()
    {
        return $this->hasMany(MsCompanyProductPrice::class, 'product_id');
    }

    public function customPrice()
    {
        return $this->hasMany(MsCustomPrice::class, 'product_id');
    }
}
