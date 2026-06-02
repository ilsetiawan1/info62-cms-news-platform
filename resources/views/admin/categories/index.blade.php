@extends('layouts.admin')

@section('header', 'Kelola Kategori')

@section('content')
<div x-data="{ 
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
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Kategori</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola hierarki kategori untuk pengelompokkan artikel.</p>
        </div>
        <div class="flex-shrink-0 flex items-center gap-3">
            <!-- Search Form -->
            <form action="{{ route('categories.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." 
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 text-sm text-slate-700 focus:border-primary focus:ring-primary shadow-sm w-64" style="padding-left: 2.75rem;">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </form>

            <!-- Sorting Switch Button -->
            @if($sortBy === 'name')
                <a href="{{ route('categories.index', array_merge(request()->except('sort'), ['sort' => 'date'])) }}" 
                   class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 transition-colors shadow-sm"
                   title="Urutkan berdasarkan: Tanggal dibuat (Terbaru)">
                    <svg class="w-5 h-5 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                    </svg>
                    <span class="text-xs font-bold text-slate-700">A-Z</span>
                </a>
            @else
                <a href="{{ route('categories.index', array_merge(request()->except('sort'), ['sort' => 'name'])) }}" 
                   class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 transition-colors shadow-sm"
                   title="Urutkan berdasarkan: Nama Kategori (A-Z)">
                    <svg class="w-5 h-5 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-bold text-slate-700">Terbaru</span>
                </a>
            @endif

            <!-- Bulk Action Buttons Above Table -->
            <div x-show="selectedIds.length > 0" x-transition style="display: none;" class="flex items-center gap-2">
                <form action="{{ route('categories.bulk-delete') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori yang dipilih? Artikel dalam kategori ini akan ikut TERHAPUS permanen!');">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl shadow-sm text-xs font-bold text-white bg-red-600 hover:bg-red-500 transition-all duration-200">
                        Hapus Terpilih (<span x-text="selectedIds.length"></span>)
                    </button>
                </form>
            </div>

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
                        <th class="px-6 py-5 w-12 text-center">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        </th>
                        <th class="px-6 py-5 w-12 text-center"></th>
                        <th class="px-6 py-5">Nama Kategori</th>
                        <th class="px-6 py-5">Slug</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                @forelse($categories as $category)
                    <tbody x-data="{ open: {{ request('search') ? 'true' : 'false' }} }" class="divide-y divide-gray-100">
                        <!-- Parent Row -->
                        <tr class="hover:bg-slate-50/80 transition-colors group" :class="{ 'bg-primary/5': selectedIds.includes('{{ $category->id }}') }">
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                <input type="checkbox" value="{{ $category->id }}" x-model="selectedIds" @change="updateSelectAll()" class="row-checkbox rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                @if($category->children->count() > 0)
                                    <button @click="open = !open" class="text-slate-400 hover:text-slate-600 focus:outline-none transition-transform duration-200 transform" :class="{ 'rotate-90': open }">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-slate-300 select-none">•</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-primary mr-2.5"></div>
                                    <div class="text-sm font-bold text-slate-900">
                                        {{ $category->name }}
                                    </div>
                                    @if($category->children->count() > 0)
                                        <span class="ml-2 px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 rounded-full border border-slate-200">
                                            {{ $category->children->count() }} sub
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm text-slate-500 font-mono bg-slate-50 px-2 py-1 rounded border border-slate-100 inline-block">
                                    {{ $category->slug }}
                                </div>
                            </td>
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

                        <!-- Sub-categories (Children Rows) -->
                        @if($category->children->count() > 0)
                            @foreach($category->children as $child)
                                <tr x-show="open" x-transition class="bg-slate-50/30 hover:bg-slate-50 transition-colors" :class="{ 'bg-primary/5': selectedIds.includes('{{ $child->id }}') }">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" value="{{ $child->id }}" x-model="selectedIds" @change="updateSelectAll()" class="row-checkbox rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap pl-12">
                                        <div class="flex items-center text-sm text-slate-700">
                                            <svg class="w-3.5 h-3.5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            {{ $child->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs text-slate-400 font-mono bg-white px-2 py-0.5 rounded border border-slate-100 inline-block">
                                            {{ $child->slug }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('categories.edit', $child->id) }}" class="p-1.5 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 border border-transparent hover:border-blue-200 transition-all duration-200" title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('categories.destroy', $child->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sub-kategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 border border-transparent hover:border-red-200 transition-all duration-200" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                @empty
                    <tbody>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                Tidak ada data kategori.
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Floating Bulk Action Bar for Categories -->
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
            <span x-text="selectedIds.length" class="text-primary font-bold text-base"></span> kategori terpilih
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('categories.bulk-delete') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori yang dipilih? Artikel dalam kategori ini akan ikut TERHAPUS permanen!');">
                @csrf
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition-all shadow-lg hover:shadow-red-500/20">
                    Hapus Terpilih
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
