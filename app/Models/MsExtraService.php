<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsExtraService extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'description',
    ];

    public function booking()
    {
        return $this->belongsTo(MsBooking::class);
    }
}