<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'ticket_limit',
        'max_subadmins',
        'position',
        'is_recommended',
        'features_html',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'features_html' => 'array',
        'is_recommended' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the translation for a given attribute.
     * 
     * @param string $attribute
     * @param string|null $locale
     * @return string
     */
    public function getTranslation(string $attribute, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $value = $this->{$attribute};

        if (is_array($value)) {
            return $value[$locale] ?? $value['it'] ?? $value['en'] ?? '';
        }

        return (string) $value;
    }
}
