@extends('layouts.admin')

@section('header', 'Kelola Artikel')

@section('content')
<div x-data="{ 
    openImportModal: false,
    selectedIds: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selectedIds = Array.from(document.querySelectorAll('.row-checkbox')).map(el => el.value);
        } else {
            this.selectedIds = [];
        }
    },
    updateSelectAll() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        this.selectAll = checkboxes.length > 0 && this.selectedIds.length === checkboxes.length;
    }
}">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Daftar Artikel</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Kelola publikasi, draft, dan konten portal berita Anda.</p>
        </div>
        <div class="flex-shrink-0 flex items-center gap-3">
            <!-- Search Form -->
            <form action="{{ route('articles.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel..." 
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 text-sm text-slate-700 dark:text-gray-300 focus:border-primary focus:ring-primary shadow-sm w-64" style="padding-left: 2.75rem;">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </form>
            
            <!-- Sorting Switch Button -->
            @if($sortBy === 'title')
                <a href="{{ route('articles.index', array_merge(request()->only(['status', 'search']), ['sort' => 'date'])) }}" 
                   class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white transition-colors shadow-sm"
                   title="Urutkan berdasarkan: Terakhir Diupdate (Terbaru)">
                    <svg class="w-5 h-5 mr-1.5 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                    </svg>
                    <span class="text-xs font-bold text-slate-700 dark:text-gray-300">A-Z</span>
                </a>
            @else
                <a href="{{ route('articles.index', array_merge(request()->only(['status', 'search']), ['sort' => 'title'])) }}" 
                   class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white transition-colors shadow-sm"
                   title="Urutkan berdasarkan: Judul Artikel (A-Z)">
                    <svg class="w-5 h-5 mr-1.5 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-bold text-slate-700 dark:text-gray-300">Terbaru</span>
                </a>
            @endif

            <!-- Bulk Action Buttons Above Table -->
            <div x-show="selectedIds.length > 0" x-transition style="display: none;" class="flex items-center gap-2">
                @if($status === 'trash')
                    <form action="{{ route('articles.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan artikel yang dipilih dari Sampah?');">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="restore">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl shadow-sm text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition-all duration-200">
                            Restore Terpilih (<span x-text="selectedIds.length"></span>)
                        </button>
                    </form>
                    
                    <form action="{{ route('articles.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel yang dipilih secara PERMANEN?');">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="force-delete">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl shadow-sm text-xs font-bold text-white bg-red-600 hover:bg-red-500 transition-all duration-200">
                            Hapus Permanen (<span x-text="selectedIds.length"></span>)
                        </button>
                    </form>
                @else
                    <form action="{{ route('articles.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin memindahkan artikel yang dipilih ke Sampah?');">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl shadow-sm text-xs font-bold text-white bg-red-600 hover:bg-red-500 transition-all duration-200">
                            Hapus Terpilih (<span x-text="selectedIds.length"></span>)
                        </button>
                    </form>
                @endif
            </div>

            <!-- Import XML Button -->
            <button type="button" @click="openImportModal = true" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700 font-semibold text-sm transition-all duration-200 shadow-sm focus:outline-none">
                <svg class="w-5 h-5 mr-2 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import XML
            </button>

            <a href="{{ route('articles.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-500 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tulis Artikel
            </a>
        </div>
    </div>

    <!-- Tabs Filter -->
    <div class="mb-6 flex flex-wrap gap-2 border-b border-gray-100 dark:border-gray-700 pb-4">
        <a href="{{ route('articles.index', ['status' => 'all', 'search' => request('search'), 'sort' => request('sort')]) }}" 
           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ $status === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
            Semua
            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-400' }}">
                {{ $counts['all'] }}
            </span>
        </a>
        <a href="{{ route('articles.index', ['status' => 'published', 'search' => request('search'), 'sort' => request('sort')]) }}" 
           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ $status === 'published' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
            Publish
            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'published' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-400' }}">
                {{ $counts['published'] }}
            </span>
        </a>
        <a href="{{ route('articles.index', ['status' => 'draft', 'search' => request('search'), 'sort' => request('sort')]) }}" 
           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ $status === 'draft' ? 'bg-slate-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
            Draft
            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'draft' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-400' }}">
                {{ $counts['draft'] }}
            </span>
        </a>
        <a href="{{ route('articles.index', ['status' => 'scheduled', 'search' => request('search'), 'sort' => request('sort')]) }}" 
           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ $status === 'scheduled' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
            Scheduled
            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'scheduled' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-400' }}">
                {{ $counts['scheduled'] }}
            </span>
        </a>
        <a href="{{ route('articles.index', ['status' => 'trash', 'search' => request('search'), 'sort' => request('sort')]) }}" 
           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ $status === 'trash' ? 'bg-red-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
            Sampah (Trash)
            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'trash' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-400' }}">
                {{ $counts['trash'] }}
            </span>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase tracking-wider text-slate-500 dark:text-gray-400 font-bold">
                        <th class="px-6 py-5 w-12 text-center">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-primary focus:ring-primary h-4 w-4">
                        </th>
                        <th class="px-6 py-5 w-16">Cover</th>
                        <th class="px-6 py-5">Judul Artikel</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5">Sub Kategori</th>
                        <th class="px-6 py-5">Status</th>
                        <th class="px-6 py-5">Tanggal</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($articles as $article)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-gray-700/30 transition-colors group" :class="{ 'bg-primary/5 dark:bg-gray-700/50': selectedIds.includes('{{ $article->id }}') }">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="checkbox" value="{{ $article->id }}" x-model="selectedIds" @change="updateSelectAll()" class="row-checkbox rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-primary focus:ring-primary h-4 w-4">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($article->cover_image)
                                    <div x-data="{ open: false }" class="relative">
                                        <div @click="open = true" class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm cursor-pointer hover:opacity-80 transition-opacity">
                                            <img src="{{ $article->cover_image_url }}" alt="Cover" class="w-full h-full object-cover">
                                        </div>
                                        
                                        <!-- Modal -->
                                        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" x-transition.opacity>
                                            <div @click.away="open = false" class="relative max-w-4xl w-full">
                                                <button @click="open = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 focus:outline-none">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                                <img src="{{ $article->cover_image_url }}" alt="Cover Besar" class="w-full h-auto rounded-xl shadow-2xl">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-gray-800 border border-dashed border-slate-300 dark:border-gray-600 flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-900 dark:text-gray-50 line-clamp-1" title="{{ $article->title }}">
                                    {{ $article->title }}
                                </div>
                                <div class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                                    Oleh: <span class="font-medium text-slate-700 dark:text-gray-300">{{ $article->author->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 dark:bg-gray-800 dark:text-gray-300 border border-slate-200 dark:border-gray-700">
                                    {{ $article->category->parent_id ? ($article->category->parent->name ?? '-') : ($article->category->name ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($article->category && $article->category->parent_id)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 border border-blue-100 dark:border-blue-800/30">
                                        {{ $article->category->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-gray-500 font-medium text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($article->trashed())
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span> Sampah (Trash)
                                    </span>
                                @elseif($article->status === 'published')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Published
                                    </span>
                                @elseif($article->status === 'scheduled')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2"></span> Scheduled
                                    </span>
                                @elseif($article->status === 'archived')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 border border-orange-200 dark:border-orange-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-2"></span> Archived
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 dark:bg-gray-800 dark:text-gray-300 border border-slate-200 dark:border-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-2"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-gray-200">
                                    {{ $article->updated_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-500 dark:text-gray-400">
                                    {{ $article->updated_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($article->trashed())
                                        <!-- Restore Button -->
                                        <form action="{{ route('articles.restore', $article->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-50 text-emerald-500 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-900/40 border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-200" title="Restore Artikel">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.578M12 8v4l3 3"></path></svg>
                                            </button>
                                        </form>

                                        <!-- Force Delete Button -->
                                        <form action="{{ route('articles.force-delete', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini secara PERMANEN? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 border border-transparent hover:border-red-200 dark:hover:border-red-800/50 transition-all duration-200" title="Hapus Permanen">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Edit Button -->
                                        <a href="{{ route('articles.edit', $article->id) }}" class="p-2 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 border border-transparent hover:border-blue-200 dark:hover:border-blue-800/50 transition-all duration-200" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <!-- Soft Delete Button -->
                                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin memindahkan artikel ini ke Sampah?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 border border-transparent hover:border-red-200 dark:hover:border-red-800/50 transition-all duration-200" title="Hapus ke Sampah">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-gray-700">
                                        <svg class="w-8 h-8 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-gray-200 mb-1">Belum ada artikel</h3>
                                    <p class="text-sm text-slate-500 dark:text-gray-400">Mulai publikasikan berita atau informasi pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($articles->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Pop-up Modern (Tailwind + Alpine.js) -->
    <div x-show="openImportModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;" 
         role="dialog" 
         aria-modal="true">
        <!-- Backdrop -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openImportModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" 
                 @click="openImportModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="openImportModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                
                <form action="{{ route('articles.import-xml') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Import Massal XML
                        </h3>
                        <button type="button" @click="openImportModal = false" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6 space-y-5">
                        
                        <!-- File XML Uploader -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih File XML</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="xml_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-sm text-slate-500 font-semibold" id="xml_filename">Klik untuk mengupload file XML</p>
                                        <p class="text-xs text-slate-400 mt-1">Hanya file berekstensi .xml</p>
                                    </div>
                                    <input id="xml_file" name="xml_file" type="file" accept=".xml" class="hidden" required 
                                        onchange="document.getElementById('xml_filename').innerText = this.files[0] ? this.files[0].name : 'Klik untuk mengupload file XML'">
                                </label>
                            </div>
                        </div>



                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="openImportModal = false"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-semibold shadow-md transition-colors">
                            Mulai Import
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Floating Bulk Action Bar -->
    <div x-show="selectedIds.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         style="display: none;"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900/95 dark:bg-gray-900/95 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl flex flex-col sm:flex-row items-center gap-4 sm:gap-6 border border-slate-800 dark:border-gray-800 min-w-[300px] sm:min-w-[450px] justify-between">
        <div class="text-sm font-semibold flex items-center gap-2">
            <span class="flex h-3.5 w-3.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary/40 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-primary"></span>
            </span>
            <span x-text="selectedIds.length" class="text-primary font-bold text-base"></span> artikel terpilih
        </div>
        <div class="flex items-center gap-3">
            @if($status === 'trash')
                <form action="{{ route('articles.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan artikel yang dipilih dari Sampah?');">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <input type="hidden" name="action" value="restore">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg hover:shadow-emerald-500/20">
                        Restore
                    </button>
                </form>
                
                <form action="{{ route('articles.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel yang dipilih secara PERMANEN?');">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <input type="hidden" name="action" value="force-delete">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition-all shadow-lg hover:shadow-red-500/20">
                        Hapus Permanen
                    </button>
                </form>
            @else
                <form action="{{ route('articles.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin memindahkan artikel yang dipilih ke Sampah?');">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition-all shadow-lg hover:shadow-red-500/20">
                        Hapus ke Sampah
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
