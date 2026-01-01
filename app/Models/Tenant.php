<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'subdomain', // Keep for backward compatibility if needed, but 'domain' is primary
        'database_name',
        'database_username',
        'database_password',
        'logo',
        'primary_color',
        'secondary_color',
        'vat_number',
        'sdi_code',
        'pec',
        'address',
        'city',
        'province',
        'zip_code',
        'stripe_account_id',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Helper to see if tenant has an active subscription
    public function hasActiveSubscription()
    {
        return $this->subscription && $this->subscription->status === 'active';
    }

    public function isConnectedToStripe(): bool
    {
        return !empty($this->stripe_account_id);
    }
}
