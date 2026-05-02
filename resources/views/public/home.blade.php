@extends('layouts.public')

@section('meta_title', 'Info Seputar +62 — Berita Terkini Indonesia')
@section('meta_description', 'Portal berita terpercaya yang menyajikan informasi terkini seputar Indonesia. Tepat, akurat, dan independen.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ===== HERO SLIDER (auto-switch tiap 5 detik) ===== --}}
    @if($heroSlides->isNotEmpty())
    <section class="mb-10" x-data="{
        current: 0,
        total: {{ $heroSlides->count() }},
        interval: null,
        init() {
            this.interval = setInterval(() => { this.next(); }, 5000);
        },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
        goto(i) { this.current = i; clearInterval(this.interval); this.interval = setInterval(() => { this.next(); }, 5000); }
    }">
        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-800 bg-slate-900 aspect-[16/7] md:aspect-[16/6]">
            @foreach($heroSlides as $idx => $slide)
                <div x-show="current === {{ $idx }}"
                     x-transition:enter="transition-opacity duration-700"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0"
                     style="{{ $idx > 0 ? 'display:none;' : '' }}">
                    @if($slide->cover_image)
                        <img src="{{ Storage::url($slide->cover_image) }}" alt="{{ $slide->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary via-blue-700 to-slate-900"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <a href="{{ route('article.show', $slide->slug) }}" class="absolute inset-0 flex flex-col justify-end p-6 sm:p-10">
                        <div class="max-w-4xl">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-primary/90 text-white mb-4 backdrop-blur-sm">{{ $slide->category->name }}</span>
                            <h1 class="text-xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-3 hover:underline underline-offset-4">
                                {{ $slide->title }}
                            </h1>
                            @if($slide->excerpt)
                                <p class="text-gray-300 text-sm sm:text-base line-clamp-2 mb-4 max-w-2xl">{{ $slide->excerpt }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-4 text-gray-400 text-xs">
                                <span class="flex items-center gap-1.5 font-medium text-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $slide->author->name }} — Info Seputar+62
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $slide->published_at?->diffForHumans() }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ number_format($slide->views_count) }} kali dibaca
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

            <!-- Prev/Next Arrows -->
            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/40 dark:bg-black/30 dark:hover:bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all border border-white/20 z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/40 dark:bg-black/30 dark:hover:bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all border border-white/20 z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Dot Indicators -->
            <div class="absolute bottom-4 right-6 flex gap-2 z-10">
                @foreach($heroSlides as $idx => $s)
                    <button @click="goto({{ $idx }})"
                            :class="current === {{ $idx }} ? 'bg-white w-8' : 'bg-white/40 w-2.5'"
                            class="h-2.5 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== MAIN 2-COLUMN LAYOUT ===== --}}
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ===== LEFT: MAIN CONTENT ===== --}}
        <div class="flex-1 min-w-0">

            <!-- Ads Space (Top Right of Main Content) -->
            <div class="ad-box w-full h-24 mb-8 flex-row gap-3" id="ads-top">
                <svg class="w-6 h-6 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span class="text-center">Ruang Iklan &bull; 728 × 90</span>
            </div>

            {{-- Section Header --}}
            <div id="terkini" class="flex items-center gap-3 mb-6">
                <div class="flex items-center gap-2.5">
                    <div class="w-1 h-6 bg-primary dark:bg-primary-500 rounded-full"></div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-gray-50">Berita Terbaru</h2>
                </div>
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
            </div>

            {{-- Article Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                @forelse($latestArticles as $article)
                    <a href="{{ route('article.show', $article->slug) }}" class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-[0_2px_15px_rgb(0,0,0,0.04)] overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        <div class="relative h-44 bg-slate-100 dark:bg-gray-900 overflow-hidden">
                            @if($article->cover_image)
                                <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-gray-700">
                                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold bg-primary/90 text-white shadow">{{ $article->category->name }}</span>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="font-bold text-slate-900 dark:text-gray-50 text-base leading-snug mb-2 group-hover:text-primary dark:group-hover:text-primary-500 transition-colors line-clamp-2">{{ $article->title }}</h3>
                            @if($article->excerpt)
                                <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-2 mb-3 flex-1">{{ $article->excerpt }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-slate-400 dark:text-gray-500 mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                                <span class="flex items-center gap-1.5 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $article->author->name }}
                                </span>
                                <span>{{ $article->published_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-2 text-center py-16 text-slate-400 dark:text-gray-500">
                        <p class="font-medium">Belum ada artikel yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($latestArticles->hasPages())
                <div class="flex justify-center mt-4">{{ $latestArticles->links() }}</div>
            @endif
        </div>

        {{-- ===== RIGHT SIDEBAR ===== --}}
        <aside class="w-full lg:w-80 flex-shrink-0 space-y-6">

            <!-- Ads Space (Top Right, pojok kanan atas) -->
            <div class="ad-box w-full h-64" id="ads-sidebar-top">
                <svg class="w-8 h-8 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span>Ruang Iklan<br>300 × 250</span>
            </div>

            <!-- Most Read -->
            <div id="trending" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-1 h-5 bg-red-500 rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-gray-50 uppercase tracking-wider">Trending</h3>
                </div>
                <ol class="space-y-4">
                    @foreach($mostViewed as $i => $top)
                        <li class="flex gap-3 items-start group">
                            <span class="text-2xl font-black leading-none flex-shrink-0 {{ $i === 0 ? 'text-primary dark:text-primary-500' : 'text-slate-200 dark:text-gray-700' }} w-6">{{ $i + 1 }}</span>
                            <a href="{{ route('article.show', $top->slug) }}" class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-700 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-500 transition-colors line-clamp-3 leading-snug">{{ $top->title }}</p>
                                <span class="text-xs text-slate-400 dark:text-gray-500 mt-1 block">{{ number_format($top->views_count) }} dibaca</span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </div>

            <!-- Ads Space (Samping Kiri Tengah setelah trending) -->
            <div class="ad-box w-full h-60" id="ads-sidebar-mid">
                <svg class="w-8 h-8 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span>Ruang Iklan<br>300 × 250</span>
            </div>

            <!-- Categories Widget -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-1 h-5 bg-primary dark:bg-primary-500 rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-gray-50 uppercase tracking-wider">Kategori</h3>
                </div>
                <ul class="space-y-1.5">
                    @foreach($navCategories as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-slate-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 hover:bg-primary/5 dark:hover:bg-primary-500/10 font-medium transition-all">
                                {{ $cat->name }}
                                <svg class="w-4 h-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </aside>

    </div>
</div>
@endsection
