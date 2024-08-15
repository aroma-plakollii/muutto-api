<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "business_number",
        "description",
        "first_name",
        "last_name",
        "email",
        "phone",
        "address",
        "register_date",
        "termination_date",
        "api_key",
        "private_key",
        "is_featured",
        "status",
    ];

    public function bookings() {
        return $this->hasMany(MsBooking::class, 'company_id');
    }

    public function products() {
        return $this->hasMany(MsProduct::class);
    }

    public function units() {
        return $this->hasMany(MsUnit::class, 'company_id');
    }

    public function cities() {
        return $this->hasMany(MsFreeCity::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function company_availabilities(){
        return $this->hasMany(MsCompanyAvailability::class);
    }

    public function price() {
        return $this->hasMany(MsCompanyProductPrice::class);
    }

    public function custom_price(){
        return $this->hasMany(MsCustomPrice::class, 'company_id');
    }

    public function unit_availabilities(){
        return $this->hasMany(MsUnitAvailability::class, 'company_id');
    }

    public function free_cities(){
        return $this->hasMany(MsFreeCity::class, 'company_id');
    }

    public function ms_company_product_prices(){
        return $this->hasMany(MsCompanyProductPrice::class, 'company_id');
    }

}
