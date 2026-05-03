@extends('layouts.public')

@section('meta_title', 'Info Seputar +62 — Berita Terkini & Terpercaya')
@section('meta_description', 'Portal berita digital terdepan di Indonesia yang menyajikan informasi terkini, akurat, dan mendalam.')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    <div class="grid grid-cols-12 gap-6">

        {{-- ===== SIDEBAR KIRI (Desktop only, 2 cols) ===== --}}
        <aside class="hidden lg:block lg:col-span-2">
            <div class="sticky top-24 space-y-6">
                {{-- Kategori Populer --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4">
                    <h3 class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Kategori</h3>
                    <ul class="space-y-1">
                        @foreach($navCategories->take(8) as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}"
                               class="block px-3 py-2 rounded-xl text-[13px] font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-sky-400 transition-colors {{ request()->is('kategori/'.$cat->slug.'*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-sky-400 font-semibold' : '' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Ad placeholder kiri --}}
                <div class="ad-placeholder h-[200px] w-full">
                    <span class="opacity-40 text-[10px]">Iklan 160×200</span>
                </div>
            </div>
        </aside>

        {{-- ===== KONTEN UTAMA (col-span-12 → md:8 → lg:6) ===== --}}
        <main class="col-span-12 md:col-span-8 lg:col-span-6">

            {{-- Featured / Hero Article --}}
            @if($heroSlides->isNotEmpty())
            @php $hero = $heroSlides->first(); @endphp
            <a href="{{ route('article.show', $hero->slug) }}" class="group block mb-8">
                <div class="relative w-full aspect-[16/9] rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 mb-4">
                    @if($hero->cover_image)
                    <img src="{{ $hero->cover_image_url }}" alt="{{ $hero->title }}"
                         class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <span class="inline-block px-2.5 py-0.5 bg-blue-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full mb-2">
                            {{ $hero->category->name }}
                        </span>
                        <h1 class="text-lg sm:text-xl font-bold text-white leading-[1.3] tracking-tight group-hover:text-blue-100 transition-colors line-clamp-3">
                            {{ $hero->title }}
                        </h1>
                        <p class="text-white/60 text-xs mt-1.5">{{ $hero->author->name }} · {{ $hero->published_at?->diffForHumans() }}</p>
                    </div>
                </div>
            </a>

            {{-- More hero articles (compact) --}}
            @if($heroSlides->count() > 1)
            <div class="grid grid-cols-2 gap-4 mb-8">
                @foreach($heroSlides->skip(1)->take(2) as $slide)
                <a href="{{ route('article.show', $slide->slug) }}" class="group block">
                    <div class="relative w-full aspect-[16/10] rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 mb-2">
                        @if($slide->cover_image)
                        <img src="{{ $slide->cover_image_url }}" alt="{{ $slide->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <span class="absolute bottom-2 left-2 px-2 py-0.5 bg-blue-600 text-white text-[9px] font-bold uppercase rounded">{{ $slide->category->name }}</span>
                    </div>
                    <h3 class="text-[13px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.35] line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">
                        {{ $slide->title }}
                    </h3>
                </a>
                @endforeach
            </div>
            @endif
            @endif

            {{-- Divider + Terkini --}}
            <div class="flex items-center gap-3 mb-5">
                <span class="w-1 h-5 bg-red-500 rounded-full flex-shrink-0"></span>
                <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Terkini</h2>
            </div>

            {{-- Article list --}}
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($latestArticles->take(10) as $art)
                <a href="{{ route('article.show', $art->slug) }}" class="group flex gap-4 py-4 first:pt-0">
                    <div class="w-24 h-[70px] flex-shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        @if($art->cover_image)
                        <img src="{{ $art->cover_image_url }}" alt="{{ $art->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-blue-600 dark:text-sky-400 uppercase tracking-wider">{{ $art->category->name }}</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500">{{ $art->published_at?->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-[14px] font-semibold text-slate-900 dark:text-slate-100 leading-[1.4] tracking-tight group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors line-clamp-2">
                            {{ $art->title }}
                        </h3>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Middle Ad --}}
            <div class="ad-placeholder h-[90px] w-full my-8">
                @if($ads_article_mid)
                <a href="{{ $ads_article_mid->url ?? '#' }}" target="_blank" rel="noopener" class="w-full h-full flex items-center justify-center">
                    <img src="{{ $ads_article_mid->image_url }}" alt="{{ $ads_article_mid->title }}" class="max-h-full object-contain rounded-xl">
                </a>
                @else
                <span class="opacity-40 text-[11px]">Iklan Banner 728×90</span>
                @endif
            </div>

            {{-- Kategori Blocks --}}
            @if(isset($categoryArticles) && $categoryArticles->isNotEmpty())
            @foreach($categoryArticles->take(3) as $catData)
            @php $catArts = $catData['articles']; @endphp
            @if($catArts->isNotEmpty())
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-slate-900 dark:border-sky-500">
                    <h2 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">{{ $catData['category']->name }}</h2>
                    <a href="{{ route('category.show', $catData['category']->slug) }}" class="text-[10px] font-bold text-blue-600 dark:text-sky-400 hover:opacity-75">Lihat Semua &rsaquo;</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($catArts->take(4) as $art)
                    <a href="{{ route('article.show', $art->slug) }}" class="group flex gap-3 py-3 first:pt-0">
                        <div class="w-[68px] h-[54px] flex-shrink-0 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            @if($art->cover_image)
                            <img src="{{ $art->cover_image_url }}" alt="{{ $art->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.35] group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors line-clamp-2 tracking-tight">
                                {{ $art->title }}
                            </h4>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 block">{{ $art->published_at?->diffForHumans() }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
            @endif

        </main>

        {{-- ===== SIDEBAR KANAN (col-span-12 → md:4 → lg:4) ===== --}}
        <aside class="col-span-12 md:col-span-4 lg:col-span-4">
            <div class="md:sticky md:top-24 space-y-6">

                {{-- Terpopuler --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                        <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Terpopuler</h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($mostViewed->take(7) as $i => $pop)
                        <a href="{{ route('article.show', $pop->slug) }}"
                           class="group flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <span class="text-xl font-black leading-none flex-shrink-0 w-6 text-center mt-0.5 {{ $i === 0 ? 'text-red-500' : 'text-slate-200 dark:text-slate-700' }}">{{ $i+1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.4] line-clamp-3 group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors tracking-tight">{{ $pop->title }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $pop->published_at?->diffForHumans() }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Sidebar Ad (300x250) --}}
                <div class="ad-placeholder h-[250px] w-full">
                    @if($ads_sidebar_top)
                    <a href="{{ $ads_sidebar_top->url ?? '#' }}" target="_blank" rel="noopener" class="w-full h-full flex items-center justify-center">
                        <img src="{{ $ads_sidebar_top->image_url }}" alt="{{ $ads_sidebar_top->title }}" class="max-h-full object-contain rounded-xl">
                    </a>
                    @else
                    <span class="opacity-40 text-[11px]">Iklan 300×250</span>
                    @endif
                </div>

                {{-- Berita Terbaru (mini list) --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                        <span class="w-1 h-4 bg-blue-600 dark:bg-sky-500 rounded-full"></span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Berita Terbaru</h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($latestArticles->take(5) as $latest)
                        <a href="{{ route('article.show', $latest->slug) }}"
                           class="group flex gap-3 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="w-[56px] h-[46px] flex-shrink-0 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600">
                                @if($latest->cover_image)
                                <img src="{{ $latest->cover_image_url }}" alt="{{ $latest->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[12px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.35] line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">{{ $latest->title }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $latest->published_at?->diffForHumans() }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </aside>

    </div>
</div>
@endsection
