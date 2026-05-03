<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    use HasFactory;

    protected $guarded = [];

    // The table doesn't have updated_at according to schema, so disable timestamps 
    // or just updated_at. Looking at schema:
    // id, article_id, ip_address, created_at
    const UPDATED_AT = null;

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
