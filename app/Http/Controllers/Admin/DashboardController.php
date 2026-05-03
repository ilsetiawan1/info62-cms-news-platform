<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalArticles = \App\Models\Article::count();
        $totalViews = \App\Models\ArticleView::count();
        $activeCategories = \App\Models\Category::has('articles')->count();
        $recentActivities = \App\Models\Article::with(['category', 'author'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalArticles',
            'totalViews',
            'activeCategories',
            'recentActivities'
        ));
    }
}
