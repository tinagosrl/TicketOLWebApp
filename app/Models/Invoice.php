<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'amount',
        'status',
        'pdf_path',
        'issued_at',
        'paid_at',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'paid_at' => 'date',
        'amount' => 'decimal:2',
    ];
}
