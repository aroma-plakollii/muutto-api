<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCompanyProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_id',
        'price',
        'saturday_price',
        'sunday_price',
        'discount_price',
        'price_per_m2',
        'included_m2',
        'no_elevator',
        'small_elevator',
        'big_elevator',
        'new_building',
        'price_per_km',
        'included_km',
        'basement_storage_price_per_m2',
        'included_m2_basement_storage',
        'roof_storage_price_per_m2',
        'included_m2_roof_storage',
        'included_meters_outdoor',
        'outdoor_price_per_meter',
        'description'
    ];

    public function company(){
        return $this->belongsTo(MsCompany::class);
    }

    public function product(){
        return $this->belongsTo(MsProduct::class);
    }
}
