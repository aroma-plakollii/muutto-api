<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MbFreeCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
        'price',
    ];

    public function company()
    {
        return $this->belongsTo(MbCompany::class);
    }
}
