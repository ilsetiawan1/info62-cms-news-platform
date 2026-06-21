<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    protected $table = 'social_media';

    protected $fillable = ['platform', 'url', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
