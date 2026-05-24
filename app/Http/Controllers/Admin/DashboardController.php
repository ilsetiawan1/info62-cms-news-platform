<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 4 Stat Cards
        $totalArticles = \App\Models\Article::count();
        $totalViews = \App\Models\ArticleView::count();
        $activeCategories = \App\Models\Category::has('articles')->count();
        $totalUsers = \App\Models\User::count(); // The 4th Stat Card: Total Pengguna

        // Visitor Trend over the last 7 days (ArticleView)
        $visitorTrend = \App\Models\ArticleView::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as views')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('views', 'date');

        $trendLabels = [];
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->translatedFormat('d M');
            $trendLabels[] = $label;
            $trendData[] = (int) $visitorTrend->get($date, 0);
        }

        // Left Column: 5 latest published articles
        $recentActivities = \App\Models\Article::with(['category', 'author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        // Right Column: 5 popular articles based on views count in ArticleView
        $popularArticles = \App\Models\Article::with('category')
            ->select('id', 'title', 'category_id')
            ->selectSub(function ($query) {
                $query->selectRaw('count(*)')
                    ->from('article_views')
                    ->whereColumn('article_views.article_id', 'articles.id');
            }, 'views_count_from_views')
            ->orderByDesc('views_count_from_views')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalArticles',
            'totalViews',
            'activeCategories',
            'totalUsers',
            'trendLabels',
            'trendData',
            'recentActivities',
            'popularArticles'
        ));
    }
}
