<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MbBlockedDates extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'company_id',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(MbCompany::class);
    }
}
