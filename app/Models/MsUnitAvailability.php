<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsUnitAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'unit_id',
        'start_date',
        'end_date'
    ];

    public function unit(){
        return $this->belongsTo(MsUnit::class);
    }
}
