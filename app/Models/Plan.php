<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    // Manual handling instead of Spatie Trait (since package is missing)
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
        'position',
        'is_recommended',
        'features_html',
        'application_fee_percent',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'features_html' => 'array',
        'is_active' => 'boolean',
        'is_recommended' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'application_fee_percent' => 'decimal:2',
        'allowed_event_types' => 'array',
    ];

    /**
     * Helper to emulate Spatie's getTranslation method
     */
    public function getTranslation(string $attribute, ?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');
        
        $value = $this->$attribute;

        if (is_array($value)) {
            return $value[$locale] ?? $value[$fallback] ?? '';
        }

        return $value;
    }
}
