<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'email',
        'logo',
        'favicon',
        'is_active',
        'current_plan_id',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    // Removed the problematic previous insert, re-adding correctly

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function currentPlan(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany()->where('status', 'active')->with('plan');
    }
    
    // Helper for active subscription (simulated relationship for now)
    public function plan()
    {
        return $this->hasOneThrough(Plan::class, Subscription::class, 'tenant_id', 'id', 'id', 'plan_id')
            ->where('subscriptions.status', 'active')
            ->latest('subscriptions.created_at');
    }
}
