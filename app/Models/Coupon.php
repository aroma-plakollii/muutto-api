<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'code',
        'price',
        'available_usages',
        'used',
        'status',
        'is_percentage',
        'is_unlimited'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // public function checkAndUpdateStatus()
    // {
    //     $usedCodes = explode(',', $this->used);
    //     $allCodes = explode(',', rtrim($this->code, ','));

    //     if (count($usedCodes) >= count($allCodes)) {
    //         $this->status = false;
    //     } else {
    //         $this->status = true;
    //     }

    //     $this->save();
    // }

    public function checkAndUpdateStatus()
    {
        if (!$this->is_unlimited && $this->used >= $this->available_usages) {
            $this->status = false;
        } else {
            $this->status = true;
        }

        $this->save();
    }
}