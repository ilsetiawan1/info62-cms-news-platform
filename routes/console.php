<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;

Artisan::command('articles:clean-trash', function () {
    $this->info('Cleaning articles trash...');
    $cutoff = now()->subDays(30);
    
    $oldArticles = Article::onlyTrashed()
        ->where('deleted_at', '<=', $cutoff)
        ->get();

    $count = $oldArticles->count();

    foreach ($oldArticles as $article) {
        if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
            Storage::disk('public')->delete($article->cover_image);
        }
        $article->forceDelete();
    }

    $this->info("Successfully deleted {$count} trashed articles older than 30 days.");
})->purpose('Clean soft deleted articles older than 30 days');

Schedule::command('articles:clean-trash')->daily();
