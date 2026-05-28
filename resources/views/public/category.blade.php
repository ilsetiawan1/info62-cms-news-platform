@extends('layouts.public')

@section('meta_title', 'Kategori: ' . $category->name . ' — Info Seputar +62')
@section('meta_description', 'Baca berita terbaru seputar ' . $category->name . ' di Info Seputar +62.')
@section('canonical', route('category.show', $category->slug))

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    <div class="grid grid-cols-12 gap-6">

        {{-- ===== SIDEBAR KIRI (Desktop only) ===== --}}
        <aside class="hidden lg:block lg:col-span-2">
            <div class="sticky top-24 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4">
                    <h3 class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Kategori</h3>
                    <ul class="space-y-1 max-h-[240px] overflow-y-auto block target-scroll">
                        @foreach($navCategories as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}"
                               class="block px-3 py-2 rounded-xl text-[13px] font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-sky-400 transition-colors {{ $cat->slug === $category->slug ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-sky-400 font-semibold' : '' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                {{-- Waktu & Hari Lokal --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 text-center shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Waktu Lokal</h3>
                    <div id="local-clock" class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">00:00:00</div>
                    <div id="local-date" class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">---</div>
                </div>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="col-span-12 md:col-span-8 lg:col-span-6">

            {{-- Category Header --}}
            <div class="mb-6 pb-5 border-b border-slate-200 dark:border-slate-700">
                <nav class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition-colors">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-300">{{ $category->name }}</span>
                </nav>
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $category->name }}</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $articles->total() }} artikel ditemukan</p>
                    </div>
                </div>

                {{-- Sub-category chips --}}
                @if($category->children->isNotEmpty())
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('category.show', $category->slug) }}"
                       class="px-4 py-1.5 rounded-full text-[12px] font-bold border border-blue-600 bg-blue-600 text-white">
                        Semua
                    </a>
                    @foreach($category->children as $child)
                    <a href="{{ route('category.show', $child->slug) }}"
                       class="px-4 py-1.5 rounded-full text-[12px] font-bold border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-blue-600 hover:text-blue-600 dark:hover:text-sky-400 transition-all">
                        {{ $child->name }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            @if($articles->isNotEmpty())

            {{-- Featured first article --}}
            @if($articles->currentPage() === 1)
            @php $first = $articles->first(); @endphp
            <a href="{{ route('article.show', $first->slug) }}" class="group block mb-7 pb-7 border-b border-slate-200 dark:border-slate-700">
                <div class="relative w-full aspect-[16/9] rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 mb-4">
                    @if($first->cover_image)
                    <img src="{{ $first->cover_image_url }}" alt="{{ $first->title }}"
                         class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @endif
                </div>
                <span class="text-[10px] font-bold text-blue-600 dark:text-sky-400 uppercase tracking-widest">{{ $first->category->name }} • {{ $first->published_at?->format('d M Y') }}</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white leading-[1.3] tracking-tight group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors mt-2 mb-2">{{ $first->title }}</h2>
                @if($first->excerpt ?? false)
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">{{ $first->excerpt }}</p>
                @endif
            </a>
            @endif

            {{-- Article list --}}
            <div class="grid grid-cols-12 gap-4 md:block">
                <div class="col-span-8 sm:col-span-9 md:col-span-12 divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach(($articles->currentPage() === 1 ? $articles->skip(1) : $articles) as $art)
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
                @if(isset($adLeftTop) && $adLeftTop)
                <div class="col-span-4 sm:col-span-3 md:hidden flex flex-col justify-start pt-2">
                    <a href="{{ $adLeftTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adLeftTop->image_url }}" alt="{{ $adLeftTop->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                    </a>
                </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-center">
                {{ $articles->links() }}
            </div>
            @endif

            @else
            <div class="text-center py-16 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-200 dark:border-slate-700">
                <p class="text-slate-500 dark:text-slate-400 font-medium mb-4">Belum ada artikel di kategori ini.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2 rounded-full bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-colors">Kembali ke Beranda</a>
            </div>
            @endif

        </main>

        {{-- ===== SIDEBAR KANAN ===== --}}
        <aside class="col-span-12 md:col-span-4 lg:col-span-4">
            <div class="md:sticky md:top-24 space-y-6">

                {{-- Tablet Sidebar Ad 1 (sidebar_mid on Tablet) --}}
                @if(isset($adLeftTop) && $adLeftTop)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adLeftTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adLeftTop->image_url }}" alt="{{ $adLeftTop->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                    </a>
                </div>
                @endif

                {{-- Terpopuler --}}
                <div class="grid grid-cols-12 gap-4 md:block">
                    <div class="col-span-8 sm:col-span-9 md:col-span-12 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                            <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                            <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Terpopuler</h3>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @foreach(App\Models\Article::where('status','published')->orderByDesc('views_count')->limit(6)->get() as $i => $pop)
                            <a href="{{ route('article.show', $pop->slug) }}"
                               class="group flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <span class="text-xl font-black leading-none flex-shrink-0 w-6 text-center mt-0.5 {{ $i === 0 ? 'text-red-500' : 'text-slate-200 dark:text-slate-700' }}">{{ $i+1 }}</span>
                                <p class="text-[12px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.4] line-clamp-3 group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">{{ $pop->title }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @if(isset($adRightTop) && $adRightTop)
                    <div class="col-span-4 sm:col-span-3 md:hidden flex flex-col justify-start">
                        <a href="{{ $adRightTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                            <img src="{{ $adRightTop->image_url }}" alt="{{ $adRightTop->title }}" class="w-full h-auto max-h-[240px] object-contain mx-auto">
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Tablet Sidebar Ad 2 (article_mid on Tablet) --}}
                @if(isset($adLeftBottom) && $adLeftBottom)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adLeftBottom->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adLeftBottom->image_url }}" alt="{{ $adLeftBottom->title }}" class="w-full h-auto max-h-[240px] object-contain mx-auto">
                    </a>
                </div>
                @endif

                {{-- Topik Populer --}}
                <div class="grid grid-cols-12 gap-4 md:block">
                    <div class="col-span-8 sm:col-span-9 md:col-span-12 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-1 h-4 bg-blue-600 dark:bg-sky-500 rounded-full"></span>
                            <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Topik Populer</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('search', ['q' => 'Pilkada2026']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#Pilkada2026</a>
                            <a href="{{ route('search', ['q' => 'TimnasIndonesia']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#TimnasIndonesia</a>
                            <a href="{{ route('search', ['q' => 'Crypto']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#Crypto</a>
                            <a href="{{ route('search', ['q' => 'Teknologi']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#Teknologi</a>
                            <a href="{{ route('search', ['q' => 'GayaHidup']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#GayaHidup</a>
                            <a href="{{ route('search', ['q' => 'Otomotif']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#Otomotif</a>
                        </div>
                    </div>
                    @if(isset($adRightBottom) && $adRightBottom)
                    <div class="col-span-4 sm:col-span-3 md:hidden flex flex-col justify-start">
                        <a href="{{ $adRightBottom->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                            <img src="{{ $adRightBottom->image_url }}" alt="{{ $adRightBottom->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Tablet Sidebar Ad 3 (sidebar_top on Tablet) --}}
                @if(isset($adRightTop) && $adRightTop)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adRightTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adRightTop->image_url }}" alt="{{ $adRightTop->title }}" class="w-full h-auto max-h-[240px] object-contain mx-auto">
                    </a>
                </div>
                @endif

                {{-- Tablet Sidebar Ad 4 (article_bottom on Tablet) --}}
                @if(isset($adRightBottom) && $adRightBottom)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adRightBottom->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adRightBottom->image_url }}" alt="{{ $adRightBottom->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                    </a>
                </div>
                @endif

            </div>
        </aside>

    </div>
</div>
@endsection
