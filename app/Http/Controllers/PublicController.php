<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display the public homepage.
     */
    public function index()
    {
        // Hero Slider: latest 5 published articles
        $heroSlides = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(5)
            ->get();

        $heroIds = $heroSlides->pluck('id');

        // Latest articles (exclude hero slides)
        $latestArticles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->whereNotIn('id', $heroIds)
            ->latest('published_at')
            ->paginate(9);

        // Most viewed for sidebar
        $mostViewed = Article::where('status', 'published')
            ->orderByDesc('views_count')
            ->limit(5)
            ->get();

        // Categories for navbar & category bar
        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        return view('public.home', compact('heroSlides', 'latestArticles', 'mostViewed', 'navCategories'));
    }

    /**
     * Display a single article.
     */
    public function show(string $slug)
    {
        $article = Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // --- View Tracking (24h cooldown per IP per article) ---
        $ip        = request()->ip();
        $cacheKey  = 'article_view_' . $article->id . '_' . md5($ip);
        $alreadyViewed = cache()->has($cacheKey);

        if (!$alreadyViewed) {
            // Store in article_views table
            \DB::table('article_views')->insert([
                'article_id' => $article->id,
                'ip_address' => $ip,
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);

            // Increment counter on articles table
            $article->increment('views_count');

            // Mark as viewed in cache for 24 hours
            cache()->put($cacheKey, true, now()->addHours(24));
        }

        // Related articles: 3 from same category
        $relatedSameCategory = Article::with('category')
            ->where('status', 'published')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // 3 random from other categories
        $relatedOther = Article::with('category')
            ->where('status', 'published')
            ->where('category_id', '!=', $article->category_id)
            ->where('id', '!=', $article->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        return view('public.article', compact('article', 'relatedSameCategory', 'relatedOther', 'navCategories'));
    }

    /**
     * Display articles by category.
     */
    public function category(string $slug)
    {
        $category = Category::with('children')->where('slug', $slug)->firstOrFail();

        // Collect category IDs (parent + all children)
        $categoryIds = collect([$category->id])
            ->merge($category->children->pluck('id'))
            ->toArray();

        $articles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->paginate(12);

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        return view('public.category', compact('category', 'articles', 'navCategories'));
    }
}
