@extends('layouts.admin')

@section('header', 'Kelola Kategori')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Kategori</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola hierarki kategori untuk pengelompokkan artikel.</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-6 py-5">Kategori Utama (Parent)</th>
                        <th class="px-6 py-5">Sub Kategori (Child)</th>
                        <th class="px-6 py-5">Slug</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <!-- Kolom 1: Kategori Utama (Parent) -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($category->parent_id)
                                    <div class="text-sm text-slate-500">
                                        {{ $category->parent->name ?? '-' }}
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-primary mr-2.5"></div>
                                        <div class="text-sm font-bold text-slate-900">
                                            {{ $category->name }}
                                        </div>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Kolom 2: Sub Kategori (Child) -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($category->parent_id)
                                    <div class="flex items-center text-sm font-semibold text-slate-800">
                                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        {{ $category->name }}
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium">-</span>
                                @endif
                            </td>

                            <!-- Kolom 3: Slug -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm text-slate-500 font-mono bg-slate-50 px-2 py-1 rounded border border-slate-100 inline-block">
                                    {{ $category->slug }}
                                </div>
                            </td>

                            <!-- Kolom 4: Aksi -->
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('categories.edit', $category->id) }}" class="p-2 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 border border-transparent hover:border-blue-200 transition-all duration-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Sub-kategori akan kehilangan parent-nya.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 border border-transparent hover:border-red-200 transition-all duration-200" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                Tidak ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
