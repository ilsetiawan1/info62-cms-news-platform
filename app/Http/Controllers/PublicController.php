<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicController extends Controller
{
    /**
     * Fetch and format advertisements for views.
     */
    private function getAds(): array
    {
        $ads = Cache::remember('active_ads', 3600, function () {
            $now = now();
            return Advertisement::where('status', 'active')
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->get()
                ->groupBy('position');
        });

        return [
            'ads_sidebar_top'    => $ads->get('sidebar_top', collect())->first(),
            'ads_sidebar_mid'    => $ads->get('sidebar_mid', collect())->first(),
            'ads_article_mid'    => $ads->get('article_mid', collect())->first(),
            'ads_article_bottom' => $ads->get('article_bottom', collect())->first(),
        ];
    }

    public function index()
    {
        $now = now();

        // Hero: latest 5 published articles (scheduled in the past or now)
        $heroSlides = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', $now)
            ->latest('published_at')
            ->limit(5)
            ->get();

        $heroIds = $heroSlides->pluck('id');

        // Latest articles (exclude hero slides)
        $latestArticles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', $now)
            ->whereNotIn('id', $heroIds)
            ->latest('published_at')
            ->limit(20)
            ->get();

        // Most viewed
        $mostViewed = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->orderByDesc('views_count')
            ->limit(7)
            ->get();

        // Categories for navbar & category bar
        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        // Breaking news ticker (latest 8 headlines)
        $tickerNews = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);

        // Articles grouped per top category (for homepage sections)
        $categoryArticles = $navCategories->map(function ($cat) use ($heroIds, $now) {
            $catIds = collect([$cat->id])
                ->merge($cat->children->pluck('id'))
                ->toArray();
            $articles = Article::with(['category', 'author'])
                ->where('status', 'published')
                ->where('published_at', '<=', $now)
                ->whereNotIn('id', $heroIds)
                ->whereIn('category_id', $catIds)
                ->latest('published_at')
                ->limit(5)
                ->get();
            return ['category' => $cat, 'articles' => $articles];
        })->filter(fn ($item) => $item['articles']->isNotEmpty())->values()->take(5);

        return view('public.home', array_merge(
            compact('heroSlides', 'latestArticles', 'mostViewed', 'navCategories', 'tickerNews', 'categoryArticles'),
            $this->getAds()
        ));
    }

    /**
     * Display a single article.
     */
    public function show(string $slug)
    {
        $now = now();

        $article = Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', $now)
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
                'created_at' => $now,
            ]);

            // Increment counter on articles table
            $article->increment('views_count');

            // Mark as viewed in cache for 24 hours
            cache()->put($cacheKey, true, $now->copy()->addHours(24));
        }

        // Related articles: 3 from same category
        $relatedSameCategory = Article::with('category')
            ->where('status', 'published')
            ->where('published_at', '<=', $now)
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // 3 random from other categories
        $relatedOther = Article::with('category')
            ->where('status', 'published')
            ->where('published_at', '<=', $now)
            ->where('category_id', '!=', $article->category_id)
            ->where('id', '!=', $article->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        $tickerNews = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);

        return view('public.article', array_merge(
            compact('article', 'relatedSameCategory', 'relatedOther', 'navCategories', 'tickerNews'),
            $this->getAds()
        ));
    }

    /**
     * Display articles by category.
     */
    public function category(string $slug)
    {
        $now = now();
        $category = Category::with('children')->where('slug', $slug)->firstOrFail();

        // Collect category IDs (parent + all children)
        $categoryIds = collect([$category->id])
            ->merge($category->children->pluck('id'))
            ->toArray();

        $articles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', $now)
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->paginate(12);

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        $tickerNews = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);

        $mostViewed = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->orderByDesc('views_count')
            ->limit(7)
            ->get();

        $latestSidebar = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->latest('published_at')
            ->limit(5)
            ->get();

        $ads = $this->getAds();

        return view('public.category', array_merge([
            'category' => $category,
            'articles' => $articles,
            'navCategories' => $navCategories,
            'tickerNews' => $tickerNews,
            'mostViewed' => $mostViewed,
            'latestSidebar' => $latestSidebar,
        ], $ads));
    }

    /**
     * Search articles by title.
     */
    public function search(Request $request)
    {
        $now = now();
        $query = $request->input('q');
        
        $articles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', $now)
            ->where('title', 'like', '%' . $query . '%')
            ->latest('published_at')
            ->paginate(12)
            ->appends(['q' => $query]);

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        $tickerNews = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);

        $mostViewed = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->orderByDesc('views_count')
            ->limit(7)
            ->get();

        $latestSidebar = Article::where('status', 'published')
            ->where('published_at', '<=', $now)
            ->latest('published_at')
            ->limit(5)
            ->get();

        $ads = $this->getAds();

        return view('public.search', array_merge([
            'articles' => $articles,
            'query' => $query,
            'navCategories' => $navCategories,
            'tickerNews' => $tickerNews,
            'mostViewed' => $mostViewed,
            'latestSidebar' => $latestSidebar,
        ], $ads));
    }
}
