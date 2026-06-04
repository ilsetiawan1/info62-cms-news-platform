<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $startDate = null;
        $endDate = now()->endOfDay();

        if ($filter === 'today') {
            $startDate = now()->startOfDay();
        } elseif ($filter === '7_days') {
            $startDate = now()->subDays(6)->startOfDay();
        } elseif ($filter === '30_days') {
            $startDate = now()->subDays(29)->startOfDay();
        }

        // Apply filters to stat cards
        $totalArticlesQuery = \App\Models\Article::query();
        $totalViewsQuery = \App\Models\ArticleView::query();
        $activeCategoriesQuery = \App\Models\Category::query();
        $totalUsersQuery = \App\Models\User::query();

        if ($startDate) {
            $totalArticlesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $totalViewsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $activeCategoriesQuery->whereHas('articles', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
            $totalUsersQuery->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $activeCategoriesQuery->has('articles');
        }

        $totalArticles = $totalArticlesQuery->count();
        $totalViews = $totalViewsQuery->count();
        $activeCategories = $activeCategoriesQuery->count();
        $totalUsers = $totalUsersQuery->count();

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
            'popularArticles',
            'filter'
        ));
    }
}
