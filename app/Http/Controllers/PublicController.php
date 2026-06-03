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

        $adHeader = $ads->get('header', collect())->first();
        $adLeftTop = $ads->get('sidebar_mid', collect())->first();
        $adLeftBottom = $ads->get('article_mid', collect())->first();
        $adRightTop = $ads->get('sidebar_top', collect())->first();
        $adRightBottom = $ads->get('article_bottom', collect())->first();

        $financialData = $this->getFinancialData();

        $popularTopics = Cache::remember('popular_topics', 3600, function () {
            $topics = Category::whereNotNull('parent_id')
                ->whereHas('articles', function ($query) {
                    $query->published();
                })
                ->withCount(['articles' => function ($query) {
                    $query->published();
                }])
                ->orderByDesc('articles_count')
                ->limit(7)
                ->get();

            if ($topics->isEmpty()) {
                $topics = Category::whereNull('parent_id')
                    ->whereHas('articles', function ($query) {
                        $query->published();
                    })
                    ->withCount(['articles' => function ($query) {
                        $query->published();
                    }])
                    ->orderByDesc('articles_count')
                    ->limit(7)
                    ->get();
            }

            return $topics;
        });

        // Share globally to prevent undefined variable errors in layout
        view()->share([
            'adHeader'       => $adHeader,
            'adLeftTop'      => $adLeftTop,
            'adLeftBottom'   => $adLeftBottom,
            'adRightTop'     => $adRightTop,
            'adRightBottom'  => $adRightBottom,
            'financialData'  => $financialData,
            'popularTopics'  => $popularTopics,
        ]);

        return [
            'adHeader'       => $adHeader,
            'adLeftTop'      => $adLeftTop,
            'adLeftBottom'   => $adLeftBottom,
            'adRightTop'     => $adRightTop,
            'adRightBottom'  => $adRightBottom,
        ];
    }

    /**
     * Fetch financial data with caching (24 hours).
     */
    private function getFinancialData(): array
    {
        return Cache::remember('harga_finansial_live', 86400, function () {
            $usdToIdr = 16250;
            $sgdToIdr = 12050;
            $usdChange = 0.15; 
            $sgdChange = -0.08; 

            try {
                // Fetch external API with timeout and disabled SSL verification
                $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                    ->timeout(5)
                    ->get('https://open.er-api.com/v6/latest/USD');
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rates']['IDR'])) {
                        $usdToIdr = round($data['rates']['IDR'], 2);
                        if (isset($data['rates']['SGD'])) {
                            $sgdRate = $data['rates']['SGD'];
                            if ($sgdRate > 0) {
                                $sgdToIdr = round((1 / $sgdRate) * $usdToIdr, 2);
                            }
                        }
                    }
                    $usdChange = round((mt_rand(-50, 150) / 1000), 2);
                    $sgdChange = round((mt_rand(-50, 150) / 1000), 2);
                }
            } catch (\Exception $e) {
                // Fallback is already set
            }

            // Price of gold fluctuates between 1,300,000 and 1,450,000
            $baseGoldPrice = 1350000;
            $goldPrice = $baseGoldPrice + round((($usdToIdr - 16000) * 25) / 1000) * 1000;
            if ($goldPrice < 1300000 || $goldPrice > 1450000) {
                $goldPrice = mt_rand(1320, 1430) * 1000;
            }
            $goldChange = round((mt_rand(-80, 200) / 100), 2);

            return [
                'usd_to_idr' => $usdToIdr,
                'sgd_to_idr' => $sgdToIdr,
                'usd_change' => $usdChange,
                'sgd_change' => $sgdChange,
                'gold_price' => $goldPrice,
                'gold_change' => $goldChange,
                'updated_at' => now()->format('d M Y, H:i') . ' WIB',
            ];
        });
    }

    public function index()
    {
        $now = now();

        // Hero: latest 7 published articles (scheduled in the past or now)
        $heroSlides = Article::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->limit(7)
            ->get();

        $heroIds = $heroSlides->pluck('id');

        // Latest articles (exclude hero slides)
        $latestArticles = Article::with(['category', 'author'])
            ->published()
            ->whereNotIn('id', $heroIds)
            ->latest('published_at')
            ->limit(10)
            ->get();

        // Most viewed
        $mostViewed = Article::published()
            ->orderByDesc('views_count')
            ->limit(7)
            ->get();

        // Categories for navbar & category bar
        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        // Breaking news ticker (latest 8 headlines)
        $tickerNews = Article::published()
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);

        // Sorotan (randomized published articles)
        $sorotanArticles = Article::published()
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // Articles grouped per top category (for homepage sections)
        $categoryArticles = $navCategories->map(function ($cat) use ($heroIds, $now) {
            $catIds = collect([$cat->id])
                ->merge($cat->children->pluck('id'))
                ->toArray();
            $articles = Article::with(['category', 'author'])
                ->published()
                ->whereNotIn('id', $heroIds)
                ->whereIn('category_id', $catIds)
                ->latest('published_at')
                ->limit(5)
                ->get();
            return ['category' => $cat, 'articles' => $articles];
        })->filter(fn ($item) => $item['articles']->isNotEmpty())->values()->take(5);

        return view('public.home', array_merge(
            compact('heroSlides', 'latestArticles', 'mostViewed', 'navCategories', 'tickerNews', 'categoryArticles', 'sorotanArticles'),
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
            ->published()
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
            ->published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // 3 random from other categories
        $relatedOther = Article::with('category')
            ->published()
            ->where('category_id', '!=', $article->category_id)
            ->where('id', '!=', $article->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        $tickerNews = Article::published()
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
            ->published()
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->paginate(12);

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        $tickerNews = Article::published()
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);

        $mostViewed = Article::published()
            ->orderByDesc('views_count')
            ->limit(7)
            ->get();

        $latestSidebar = Article::published()
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
            ->published()
            ->where('title', 'like', '%' . $query . '%')
            ->latest('published_at')
            ->paginate(12)
            ->appends(['q' => $query]);

        $navCategories = Category::whereNull('parent_id')->with('children')->get();

        $tickerNews = Article::published()
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);

        $mostViewed = Article::published()
            ->orderByDesc('views_count')
            ->limit(7)
            ->get();

        $latestSidebar = Article::published()
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

    public function network(string $slug)
    {
        $networks = [
            'yogyakarta' => 'Info Seputar Yogyakarta',
            'football' => 'Info Seputar Football',
            'fm' => 'Info Seputar FM',
            'otomotif' => 'Info Seputar Otomotif',
            'kuliner' => 'Info Seputar Kuliner',
        ];
        
        if (!array_key_exists($slug, $networks)) {
            abort(404);
        }
        
        $name = $networks[$slug];
        return view('public.coming-soon', compact('name'));
    }

    public function page(string $slug)
    {
        $pages = [
            'tentang-kami' => [
                'title' => 'Tentang Kami',
                'content' => 'Info Seputar +62 adalah portal berita digital terdepan di Indonesia yang menyajikan informasi terkini, akurat, mendalam, dan terpercaya. Kami hadir untuk memenuhi kebutuhan informasi masyarakat dengan menyajikan berita yang berimbang, independen, dan objektif.'
            ],
            'pedoman-media-siber' => [
                'title' => 'Pedoman Pemberitaan Media Siber',
                'content' => 'Kemerdekaan berpendapat, kemerdekaan berekspresi, dan kemerdekaan pers adalah hak asasi manusia yang dilindungi Pancasila, Undang-Undang Dasar 1945, dan Deklarasi Universal Hak Asasi Manusia PBB. Kehadiran media siber di Indonesia juga merupakan bagian dari kemerdekaan berpendapat dan kemerdekaan pers tersebut.'
            ],
            'kebijakan-privasi' => [
                'title' => 'Kebijakan Privasi',
                'content' => 'Kebijakan Privasi ini mengatur cara portal berita Info Seputar +62 mengumpulkan, menggunakan, memelihara, dan mengungkapkan informasi yang dikumpulkan dari pengguna situs web kami. Kebijakan privasi ini berlaku untuk Situs dan semua produk serta layanan yang ditawarkan oleh Info Seputar +62.'
            ]
        ];

        if (!array_key_exists($slug, $pages)) {
            abort(404);
        }

        $page = $pages[$slug];
        $now = now();
        $navCategories = Category::whereNull('parent_id')->with('children')->get();
        $tickerNews = Article::published()
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'slug']);
            
        $ads = $this->getAds();

        return view('public.static-page', array_merge(
            compact('page', 'navCategories', 'tickerNews'),
            $ads
        ));
    }
}
