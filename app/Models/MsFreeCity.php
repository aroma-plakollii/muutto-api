<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsFreeCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
    ];

    public function company(){
        return $this->belongsTo(MsCompany::class);
    }
}
