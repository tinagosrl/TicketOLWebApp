<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'code',
        'name',
        'subject_en',
        'body_en',
        'subject_it',
        'body_it',
        'variables' // JSON helper to show available variables
    ];

    protected $casts = [
        'variables' => 'array',
    ];
}
