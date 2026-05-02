@extends('layouts.public')

@section('meta_title', 'Kategori: ' . $category->name . ' — Info Seputar +62')
@section('meta_description', 'Baca berita dan artikel terbaru seputar ' . $category->name . ' di Info Seputar +62.')
@section('canonical', route('category.show', $category->slug))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Category Header --}}
    <div class="mb-8">
        <nav class="flex items-center text-sm text-slate-500 dark:text-gray-400 mb-4 gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-primary dark:hover:text-primary-500 transition-colors">Beranda</a>
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-800 dark:text-gray-200 font-medium">{{ $category->name }}</span>
        </nav>

        <div class="flex items-center gap-4 mb-5">
            <div class="w-1.5 h-10 bg-primary dark:bg-primary-500 rounded-full"></div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-gray-50">{{ $category->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-gray-400 mt-0.5">{{ $articles->total() }} artikel tersedia</p>
            </div>
        </div>

        {{-- Sub-category chips --}}
        @if($category->children->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach($category->children as $child)
                    <a href="{{ route('category.show', $child->slug) }}"
                       class="px-4 py-1.5 rounded-full text-sm font-semibold border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 hover:bg-primary/5 dark:hover:bg-primary-500/10 hover:border-primary dark:hover:border-primary-500 hover:text-primary dark:hover:text-primary-500 transition-all shadow-sm">
                        {{ $child->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Articles Grid --}}
        <div class="flex-1 min-w-0">
            @if($articles->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                    @foreach($articles as $article)
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
                                <h2 class="font-bold text-slate-900 dark:text-gray-50 text-base leading-snug mb-2 group-hover:text-primary dark:group-hover:text-primary-500 transition-colors line-clamp-2">{{ $article->title }}</h2>
                                @if($article->excerpt)
                                    <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-2 mb-3 flex-1">{{ $article->excerpt }}</p>
                                @endif
                                <div class="flex items-center justify-between text-xs text-slate-400 dark:text-gray-500 mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <span>{{ $article->author->name }} — Info Seputar+62</span>
                                    <span>{{ $article->published_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($articles->hasPages())
                    <div class="flex justify-center">{{ $articles->links() }}</div>
                @endif
            @else
                <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20"></path></svg>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-gray-100 mb-2">Belum ada artikel</h3>
                    <p class="text-slate-500 dark:text-gray-400 mb-6">Belum ada artikel di kategori ini.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">Kembali ke Beranda</a>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="hidden lg:block w-72 flex-shrink-0 space-y-6">
            <div class="ad-box w-full h-64">
                <svg class="w-8 h-8 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span>Ruang Iklan<br>300 × 250</span>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-red-500 rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-gray-50 uppercase tracking-wider">Terpopuler</h3>
                </div>
                <ol class="space-y-4">
                    @foreach(App\Models\Article::where('status','published')->orderByDesc('views_count')->limit(5)->get() as $i => $top)
                        <li class="flex gap-3 items-start group">
                            <span class="text-xl font-black leading-none flex-shrink-0 {{ $i===0 ? 'text-primary dark:text-primary-500' : 'text-slate-200 dark:text-gray-700' }} w-6">{{ $i+1 }}</span>
                            <a href="{{ route('article.show', $top->slug) }}" class="text-sm font-semibold text-slate-700 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-500 transition-colors line-clamp-3 leading-snug">{{ $top->title }}</a>
                        </li>
                    @endforeach
                </ol>
            </div>
        </aside>
    </div>
</div>
@endsection
