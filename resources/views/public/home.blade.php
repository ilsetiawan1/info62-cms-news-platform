@extends('layouts.public')

@section('meta_title', 'Info Seputar +62 — Berita Terkini & Terpercaya')
@section('meta_description', 'Portal berita digital terdepan di Indonesia yang menyajikan informasi terkini, akurat, dan mendalam.')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    <div class="grid grid-cols-12 gap-6">

        {{-- ===== SIDEBAR KIRI (Desktop only, 2 cols) ===== --}}
        <aside class="hidden lg:block lg:col-span-2">
            <div class="sticky top-[136px] flex flex-col gap-6">
                {{-- Prakiraan Cuaca +62 --}}
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-blue-100 dark:border-slate-700 p-4 text-white shadow-md relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 opacity-10">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75zM6.16 5.1a.75.75 0 0 1 1.06 0l1.59 1.59a.75.75 0 1 1-1.06 1.06L6.16 6.16a.75.75 0 0 1 0-1.06zm11.68 0a.75.75 0 0 1 0 1.06l-1.59 1.59a.75.75 0 1 1-1.06-1.06l1.59-1.59a.75.75 0 0 1 1.06 0zM12 5.25a6.75 6.75 0 1 0 6.75 6.75A6.75 6.75 0 0 0 12 5.25zM3 12a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12zm15 0a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5h-2.25A.75.75 0 0 1 18 12zm-11.84 5.25a.75.75 0 0 1 1.06 0l1.59 1.59a.75.75 0 1 1-1.06 1.06l-1.59-1.59a.75.75 0 0 1 0-1.06zm9.68 0a.75.75 0 0 1 0 1.06l-1.59 1.59a.75.75 0 1 1-1.06-1.06l1.59-1.59a.75.75 0 0 1 1.06 0zM12 18.75a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V19.5a.75.75 0 0 1 .75-.75z"/></svg>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black tracking-wider uppercase text-blue-100/90">Cuaca Nusantara</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-white/20 text-white backdrop-blur-sm">Live</span>
                    </div>
                    <div class="space-y-3" x-data="{ 
                        cities: [
                            { name: 'Jakarta', temp: '32°C', desc: 'Cerah Berawan', icon: '⛅' },
                            { name: 'Surabaya', temp: '34°C', desc: 'Cerah', icon: '☀️' },
                            { name: 'Bandung', temp: '25°C', desc: 'Hujan Ringan', icon: '🌧️' },
                            { name: 'Medan', temp: '30°C', desc: 'Berawan', icon: '☁️' }
                        ],
                        activeIdx: 0,
                        init() {
                            setInterval(() => {
                                this.activeIdx = (this.activeIdx + 1) % this.cities.length;
                            }, 5000);
                        }
                    }">
                        <div class="bg-white/10 dark:bg-slate-900/40 rounded-xl p-3 backdrop-blur-sm transition-all duration-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-extrabold tracking-tight" x-text="cities[activeIdx].name">Jakarta</h4>
                                    <p class="text-[11px] text-blue-100/80" x-text="cities[activeIdx].desc">Cerah Berawan</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black" x-text="cities[activeIdx].temp">32°C</div>
                                    <div class="text-lg" x-text="cities[activeIdx].icon">⛅</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slot 1 sudah eksklusif di Sayap Kiri Atas desktop — tidak dirender ulang di sini --}}

                {{-- Trivia Nusantara --}}
                {{-- Fakta Nusantara (Dinamis dari DB) --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm relative overflow-hidden">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-red-500 text-sm">💡</span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Fakta Nusantara</h3>
                    </div>
                    <p class="text-[12px] text-slate-600 dark:text-slate-300 leading-relaxed font-medium min-h-[72px]">
                        {{ $fact?->content ?? 'Indonesia adalah negara kepulauan terbesar di dunia dengan lebih dari 17.000 pulau.' }}
                    </p>
                    <div class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-700/50">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">Fakta Nusantara</span>
                    </div>
                </div>

                {{-- Waktu & Hari Lokal --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 text-center shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Waktu Lokal</h3>
                    <div id="local-clock" class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">00:00:00</div>
                    <div id="local-date" class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">---</div>
                </div>

                {{-- Kurs Nusantara --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm relative overflow-hidden">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-emerald-500 text-sm">💱</span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Kurs Rupiah</h3>
                    </div>
                    <div class="flex flex-col gap-2">
                        {{-- USD --}}
                        <div class="flex flex-col p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 rounded">USD</span>
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">USD / IDR</span>
                                </div>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-black {{ ($financialData['usd_change'] ?? 0) >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ ($financialData['usd_change'] ?? 0) >= 0 ? '▲' : '▼' }}{{ abs($financialData['usd_change'] ?? 0) }}%
                                </span>
                            </div>
                            <div class="text-[14px] font-black text-slate-900 dark:text-white">
                                Rp{{ number_format($financialData['usd_to_idr'] ?? 16250, 0, ',', '.') }}
                            </div>
                        </div>
                        {{-- SGD --}}
                        <div class="flex flex-col p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 rounded">SGD</span>
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">SGD / IDR</span>
                                </div>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-black {{ ($financialData['sgd_change'] ?? 0) >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ ($financialData['sgd_change'] ?? 0) >= 0 ? '▲' : '▼' }}{{ abs($financialData['sgd_change'] ?? 0) }}%
                                </span>
                            </div>
                            <div class="text-[14px] font-black text-slate-900 dark:text-white">
                                Rp{{ number_format($financialData['sgd_to_idr'] ?? 12050, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slot 2 sudah eksklusif di Sayap Kiri Bawah desktop — tidak dirender ulang di sini --}}

                {{-- Logam Mulia --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-bl from-amber-500/10 to-transparent pointer-events-none rounded-bl-full"></div>
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-amber-500 text-sm">✨</span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Harga Emas</h3>
                    </div>
                    <div class="flex flex-col p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Antam / Gram</span>
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[9px] font-black {{ ($financialData['gold_change'] ?? 0) >= 0 ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500' : 'bg-rose-50 dark:bg-rose-950/20 text-rose-500' }}">
                                {{ ($financialData['gold_change'] ?? 0) >= 0 ? '▲' : '▼' }}{{ abs($financialData['gold_change'] ?? 0) }}%
                            </span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <div class="text-[15px] font-black text-slate-900 dark:text-white">
                                Rp{{ number_format($financialData['gold_price'] ?? 1350000, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-amber-500 dark:text-amber-400 font-bold">LM 24 Karat</div>
                        </div>
                    </div>
                    <div class="mt-3 text-[9px] text-slate-400 dark:text-slate-500 flex flex-col gap-0.5 border-t border-slate-100 dark:border-slate-700/50 pt-2">
                        <div class="flex items-center justify-between">
                            <span>Pembaruan otomatis</span>
                            <span class="font-bold text-slate-500 dark:text-slate-400">{{ $financialData['updated_at'] ?? now()->format('d M Y') }}</span>
                        </div>
                    </div>
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
                        <p class="text-white/60 text-xs mt-1.5">{{ $hero->category->name }} • {{ $hero->published_at?->format('d M Y') }}</p>
                    </div>
                </div>
            </a>

            {{-- Slot 1: Mobile/Tablet fallback (Desktop sudah di Sayap Kiri Atas) --}}
            @if(isset($adSlot1) && $adSlot1)
            <div class="lg:hidden w-full my-6 flex justify-center">
                <div class="w-full max-w-[728px] overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                    <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span>Sponsor</span>
                    </div>
                    <a href="{{ $adSlot1->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                        <img src="{{ $adSlot1->image_url }}" alt="{{ $adSlot1->title }}" class="w-full h-auto object-contain mx-auto max-h-[160px] sm:max-h-[220px]">
                    </a>
                </div>
            </div>
            @endif

            {{-- More hero articles (compact) --}}
            @if($heroSlides->count() > 1)
            <div class="mb-8">
                <!-- Desktop & Tablet: 2 rows of 3 cards | Mobile: 2 rows of cards scrolling horizontally, aligned with px-4 -->
                <div class="grid grid-rows-2 grid-flow-col gap-4 overflow-x-auto md:grid-flow-row md:grid-rows-none md:grid-cols-3 md:overflow-visible pb-4 md:pb-0 snap-x snap-mandatory scroll-smooth hide-scrollbar px-4 md:px-0">
                    @foreach($heroSlides->skip(1)->take(6) as $slide)
                    <a href="{{ route('article.show', $slide->slug) }}" class="group block flex-shrink-0 w-[calc(50vw-24px)] sm:w-[280px] md:w-auto snap-start">
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
            </div>
            @endif
            @endif

            {{-- Slot 2: Mobile/Tablet fallback (Desktop sudah di Sayap Kiri Bawah) --}}
            @if(isset($adSlot2) && $adSlot2)
            <div class="lg:hidden w-full my-6 flex justify-center">
                <div class="w-full max-w-[728px] overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                    <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span>Sponsor</span>
                    </div>
                    <a href="{{ $adSlot2->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                        <img src="{{ $adSlot2->image_url }}" alt="{{ $adSlot2->title }}" class="w-full h-auto object-contain mx-auto max-h-[160px] sm:max-h-[220px]">
                    </a>
                </div>
            </div>
            @endif

            {{-- Slot 3: Mobile/Tablet fallback (Desktop sudah di Sayap Kanan Atas) --}}
            @if(isset($adSlot3) && $adSlot3)
            <div class="lg:hidden w-full my-6 flex justify-center">
                <div class="w-full max-w-[728px] overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                    <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span>Sponsor</span>
                    </div>
                    <a href="{{ $adSlot3->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                        <img src="{{ $adSlot3->image_url }}" alt="{{ $adSlot3->title }}" class="w-full h-auto object-contain mx-auto max-h-[160px] sm:max-h-[220px]">
                    </a>
                </div>
            </div>
            @endif

            {{-- Divider + Terkini --}}
            <div class="flex items-center gap-3 mb-5">
                <span class="w-1 h-5 bg-red-500 rounded-full flex-shrink-0"></span>
                <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Terkini</h2>
            </div>

            {{-- Slot 4: Mobile/Tablet fallback (Desktop sudah di Sayap Kanan Bawah) --}}
            @if(isset($adSlot4) && $adSlot4)
            <div class="lg:hidden w-full my-6 flex justify-center">
                <div class="w-full max-w-[728px] overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                    <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span>Sponsor</span>
                    </div>
                    <a href="{{ $adSlot4->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                        <img src="{{ $adSlot4->image_url }}" alt="{{ $adSlot4->title }}" class="w-full h-auto object-contain mx-auto max-h-[160px] sm:max-h-[220px]">
                    </a>
                </div>
            </div>
            @endif

            {{-- Article list --}}
            <div class="grid grid-cols-12 gap-4 md:block">
                <div class="col-span-12 divide-y divide-slate-100 dark:divide-slate-800">
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
                                <span class="text-[10px] text-slate-400 dark:text-slate-500">{{ $art->published_at?->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-[14px] font-semibold text-slate-900 dark:text-slate-100 leading-[1.4] tracking-tight group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors line-clamp-2">
                                {{ $art->title }}
                            </h3>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Slot 5 (Visible on all screens - Atas Terkini) --}}
            @if(isset($adSlot5) && $adSlot5)
            <div class="w-full my-6 flex justify-center">
                <div class="w-full max-w-[728px] overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                    <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span>Sponsor</span>
                    </div>
                    <a href="{{ $adSlot5->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                        <img src="{{ $adSlot5->image_url }}" alt="{{ $adSlot5->title }}" class="w-full h-auto object-contain mx-auto max-h-[160px] sm:max-h-[220px]">
                    </a>
                </div>
            </div>
            @endif

            {{-- Divider --}}
            <div class="my-8 border-b border-slate-200/60 dark:border-slate-800/50"></div>

            {{-- Kategori Blocks --}}
            @if(isset($categoryArticles) && $categoryArticles->isNotEmpty())
            <div class="grid grid-cols-12 gap-4 md:block">
                <div class="col-span-12">
                    @foreach($categoryArticles->take(3) as $index => $catData)
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
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 block">{{ $art->published_at?->format('d M Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Slot 6 (Visible on all screens - Sela Terkini/Kategori Blocks) --}}
                    @if($index === 0 && isset($adSlot6) && $adSlot6)
                    <div class="w-full my-6 flex justify-center">
                        <div class="w-full max-w-[728px] overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                            <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                <span>Sponsor</span>
                            </div>
                            <a href="{{ $adSlot6->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                                <img src="{{ $adSlot6->image_url }}" alt="{{ $adSlot6->title }}" class="w-full h-auto object-contain mx-auto max-h-[160px] sm:max-h-[220px]">
                            </a>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Slot 7 (Visible on all screens - Atas Footer) --}}
            @if(isset($adSlot7) && $adSlot7)
            <div class="w-full my-6 flex justify-center">
                <div class="w-full max-w-[728px] overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                    <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span>Sponsor</span>
                    </div>
                    <a href="{{ $adSlot7->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                        <img src="{{ $adSlot7->image_url }}" alt="{{ $adSlot7->title }}" class="w-full h-auto object-contain mx-auto max-h-[160px] sm:max-h-[220px]">
                    </a>
                </div>
            </div>
            @endif

        </main>

        {{-- ===== SIDEBAR KANAN (col-span-12 → md:4 → lg:4) ===== --}}
        <aside class="col-span-12 md:col-span-4 lg:col-span-4">
            <div class="md:sticky md:top-[136px] flex flex-col gap-6">

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
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $pop->published_at?->format('d M Y') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Slot 8 (Visible on all screens - Bawah Terpopuler) --}}
                @if(isset($adSlot8) && $adSlot8)
                <div class="w-full flex justify-center">
                    <div class="w-full overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                        <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                            <span>Sponsor</span>
                        </div>
                        <a href="{{ $adSlot8->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                            <img src="{{ $adSlot8->image_url }}" alt="{{ $adSlot8->title }}" class="w-full h-auto object-cover mx-auto max-h-[280px]">
                        </a>
                    </div>
                </div>
                @endif

                {{-- Topik Populer --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-1 h-4 bg-blue-600 dark:bg-sky-500 rounded-full"></span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Topik Populer</h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if(isset($popularTopics) && $popularTopics->isNotEmpty())
                            @foreach($popularTopics as $topic)
                                <a href="{{ route('category.show', $topic->slug) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#{{ str_replace(' ', '', $topic->name) }}</a>
                            @endforeach
                        @else
                            <span class="text-xs text-slate-500 dark:text-slate-400">Tidak ada topik populer.</span>
                        @endif
                    </div>
                </div>

                {{-- Slot 9 (Visible on all screens - Bawah Topik Populer) --}}
                @if(isset($adSlot9) && $adSlot9)
                <div class="w-full flex justify-center">
                    <div class="w-full overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                        <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                            <span>Sponsor</span>
                        </div>
                        <a href="{{ $adSlot9->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                            <img src="{{ $adSlot9->image_url }}" alt="{{ $adSlot9->title }}" class="w-full h-auto object-cover mx-auto max-h-[280px]">
                        </a>
                    </div>
                </div>
                @endif

                {{-- Sorotan (mini list) --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                        <span class="w-1 h-4 bg-amber-500 rounded-full"></span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Sorotan</h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($sorotanArticles as $latest)
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
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $latest->published_at?->format('d M Y') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Slot 10 (Visible on all screens - Bawah Sorotan) --}}
                @if(isset($adSlot10) && $adSlot10)
                <div class="w-full flex justify-center">
                    <div class="w-full overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1 flex flex-col">
                        <div class="flex items-center justify-between px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                            <span>Sponsor</span>
                        </div>
                        <a href="{{ $adSlot10->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900/60">
                            <img src="{{ $adSlot10->image_url }}" alt="{{ $adSlot10->title }}" class="w-full h-auto object-cover mx-auto max-h-[280px]">
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </aside>

    </div>
</div>
@endsection
