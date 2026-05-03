@extends('layouts.admin')

@section('header', 'Kelola Artikel')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Daftar Artikel</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Kelola publikasi, draft, dan konten portal berita Anda.</p>
        </div>
        <div class="flex-shrink-0 flex items-center gap-3">
            <!-- Filter Form -->
            <form action="{{ route('articles.index') }}" method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="rounded-xl border-gray-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 text-sm text-slate-700 dark:text-gray-300 focus:border-primary focus:ring-primary shadow-sm py-2.5">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </form>
            
            <a href="{{ route('articles.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-500 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tulis Artikel
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase tracking-wider text-slate-500 dark:text-gray-400 font-bold">
                        <th class="px-6 py-5 w-16">Cover</th>
                        <th class="px-6 py-5">Judul Artikel</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5">Status</th>
                        <th class="px-6 py-5">Tanggal</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($articles as $article)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-gray-700/30 transition-colors group">
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
                                    {{ $article->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($article->status === 'published')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Published
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
                                    <!-- Edit Button -->
                                    <a href="{{ route('articles.edit', $article->id) }}" class="p-2 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 border border-transparent hover:border-blue-200 dark:hover:border-blue-800/50 transition-all duration-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 border border-transparent hover:border-red-200 dark:hover:border-red-800/50 transition-all duration-200" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
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
@endsection
