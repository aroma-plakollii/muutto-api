<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCompanyAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
    ];

    public function company(){
        return $this->belongsTo(MsCompany::class);
    }
}
