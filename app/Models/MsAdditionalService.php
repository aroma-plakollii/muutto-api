<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsAdditionalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
    ];

    public function booking() {
        return $this->belongsTo(MsBooking::class);
    }
}
