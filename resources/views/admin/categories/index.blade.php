@extends('layouts.admin')

@section('header', 'Kelola Kategori')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Daftar Kategori</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Kelola hierarki kategori untuk pengelompokkan artikel.</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-500 transition-all duration-200 w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase tracking-wider text-slate-500 dark:text-gray-400 font-bold">
                        <th class="px-6 py-5">Nama Kategori</th>
                        <th class="px-6 py-5">Slug</th>
                        <th class="px-6 py-5 text-center">Sub Kategori</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($category->parent_id)
                                        <svg class="w-5 h-5 text-slate-300 dark:text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        <div class="text-sm font-medium text-slate-700 dark:text-gray-300">{{ $category->name }}</div>
                                        <span class="ml-2 text-xs text-slate-400 dark:text-gray-500 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700">Child dari: {{ $category->parent->name }}</span>
                                    @else
                                        <div class="w-2 h-2 rounded-full bg-primary dark:bg-primary-500 mr-3"></div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-gray-50">{{ $category->name }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm text-slate-500 dark:text-gray-400 font-mono bg-slate-50 dark:bg-gray-900 px-2 py-1 rounded border border-slate-100 dark:border-gray-700 inline-block">
                                    {{ $category->slug }}
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $category->children_count > 0 ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-slate-100 text-slate-500 dark:bg-gray-800 dark:text-gray-400' }} text-sm font-semibold">
                                    {{ $category->children_count }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('categories.edit', $category->id) }}" class="p-2 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 border border-transparent hover:border-blue-200 dark:hover:border-blue-800/50 transition-all duration-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Sub-kategori akan kehilangan parent-nya.');">
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
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500 dark:text-gray-400">
                                Tidak ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
