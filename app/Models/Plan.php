<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Plan extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description', 'features_html'];

    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'price_monthly', 
        'price_yearly', 
        'ticket_limit', 
        'max_subadmins',
        'is_active',
        'allowed_event_types',
        // New fields
        'position',
        'is_recommended',
        'features_html',
        'application_fee_percent', // <--- Added this
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_recommended' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'application_fee_percent' => 'decimal:2', // <--- Added this
        'allowed_event_types' => 'array',
    ];
}
