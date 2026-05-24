@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
    <!-- Welcome Card -->
    <div class="mb-8 bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6 flex items-center justify-between transition-all">
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-1">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-slate-500 text-sm">Selamat datang di Panel Admin Info Seputar +62. Hari ini adalah {{ now()->translatedFormat('l, d F Y') }}.</p>
        </div>
        <div class="hidden sm:block">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Total Artikel</p>
                    <h3 class="text-3xl font-bold text-slate-900">{{ number_format($totalArticles) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-sm text-slate-500">
                <span class="text-emerald-500 font-medium flex items-center mr-2">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Aktif
                </span>
                di portal berita
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Total Pengunjung</p>
                    <h3 class="text-3xl font-bold text-slate-900">{{ number_format($totalViews) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-sm text-slate-500">
                <span class="text-emerald-500 font-medium flex items-center mr-2">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Total views
                </span>
                semua artikel
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Kategori Aktif</p>
                    <h3 class="text-3xl font-bold text-slate-900">{{ number_format($activeCategories) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-sm text-slate-500">
                <span class="text-blue-500 font-medium mr-2">Kategori</span>
                dengan artikel aktif
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Total Pengguna</p>
                    <h3 class="text-3xl font-bold text-slate-900">{{ number_format($totalUsers) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-sm text-slate-500">
                <span class="text-emerald-500 font-medium mr-2">Admin & Staf</span>
                terdaftar di sistem
            </div>
        </div>
    </div>

    <!-- Visitor Trend Chart -->
    <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Tren Pengunjung (7 Hari Terakhir)</h3>
                <p class="text-slate-500 text-xs mt-0.5">Statistik jumlah tayangan artikel harian</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                <span class="text-xs text-slate-500 font-medium">Tayangan Artikel</span>
            </div>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="visitorTrendChart"></canvas>
        </div>
    </div>

    <!-- Grid Layout: Aktivitas Terkini & Artikel Terpopuler -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left Column: Aktivitas Terkini -->
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Aktivitas Terkini</h3>
                        <p class="text-slate-500 text-xs mt-0.5">5 artikel terakhir yang dipublikasikan</p>
                    </div>
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center text-primary hover:text-primary-hover text-xs font-semibold px-3 py-1.5 rounded-lg bg-primary/5 transition-colors">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentActivities as $activity)
                        <div class="px-6 py-4 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 font-bold text-sm">
                                    {{ substr($activity->author->name ?? 'S', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">
                                        {{ $activity->title }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $activity->category->name ?? 'Umum' }}
                                        </span>
                                        <span class="text-xs text-slate-400">•</span>
                                        <span class="text-xs text-slate-500">
                                            Oleh {{ $activity->author->name ?? 'Sistem' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-slate-400 whitespace-nowrap ml-4">
                                {{ $activity->published_at ? $activity->published_at->diffForHumans() : $activity->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm">Belum ada aktivitas yang direkam.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: 5 Artikel Terpopuler -->
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-white">
                <h3 class="text-lg font-bold text-slate-900">5 Artikel Terpopuler</h3>
                <p class="text-slate-500 text-xs mt-0.5">Berdasarkan total tayangan (views) dari ArticleView</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-gray-100">
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Total Views</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($popularArticles as $popular)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 min-w-[200px]">
                                    <div class="text-sm font-semibold text-slate-900 line-clamp-2" title="{{ $popular->title }}">
                                        {{ $popular->title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                        {{ $popular->category->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5 text-slate-700 font-bold text-sm">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        {{ number_format($popular->views_count_from_views) }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm">Belum ada data statistik artikel.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('visitorTrendChart').getContext('2d');
            
            // Create nice gradient fill
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)'); // Indigo
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

            const trendLabels = @json($trendLabels);
            const trendData = @json($trendData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Tayangan Artikel',
                        data: trendData,
                        borderColor: '#4f46e5', // Indigo-600
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b', // slate-800
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `Tayangan: ${context.parsed.y} views`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b', // slate-500
                                font: {
                                    family: 'Figtree, sans-serif',
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9', // slate-100
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: trendData.length ? Math.max(1, Math.ceil(Math.max(...trendData) / 5)) : 1,
                                color: '#64748b',
                                font: {
                                    family: 'Figtree, sans-serif',
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
