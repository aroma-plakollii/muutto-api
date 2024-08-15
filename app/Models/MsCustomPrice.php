<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCustomPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_id',
        'date',
        'price',
    ];

    public function company(){
        return $this->belongsTo(MsCompany::class);
    }

    public function product(){
        return $this->belongsTo(MsProduct::class);
    }
}
