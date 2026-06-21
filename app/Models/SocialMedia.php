<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'social_media';

    protected $fillable = ['platform', 'url', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
