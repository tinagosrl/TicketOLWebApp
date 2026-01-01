<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = ['coupon_id', 'tenant_id'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
