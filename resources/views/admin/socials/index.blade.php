@extends('layouts.admin')

@section('header', 'Sosial Media')

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
}" class="p-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sosial Media</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola tautan sosial media yang tampil di footer halaman publik.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Search Bar --}}
            <form action="{{ route('socials.index') }}" method="GET" class="relative">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sosial media..."
                       class="pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-sm text-slate-700 bg-white focus:ring-primary focus:border-primary w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>

            @if($status !== 'trash')
            <a href="{{ route('socials.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:opacity-90 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Sosial Media
            </a>
            @endif
        </div>
    </div>

    {{-- Tab Filter Status --}}
    <div class="flex border-b border-slate-200 mb-6 gap-2">
        <a href="{{ route('socials.index', ['status' => 'all', 'search' => request('search')]) }}"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-all {{ $status === 'all' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('socials.index', ['status' => 'active', 'search' => request('search')]) }}"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-all {{ $status === 'active' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Aktif ({{ $counts['active'] }})
        </a>
        <a href="{{ route('socials.index', ['status' => 'inactive', 'search' => request('search')]) }}"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-all {{ $status === 'inactive' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Nonaktif ({{ $counts['inactive'] }})
        </a>
        <a href="{{ route('socials.index', ['status' => 'trash', 'search' => request('search')]) }}"
           class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-all {{ $status === 'trash' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Sampah ({{ $counts['trash'] }})
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
        {{ session('error') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3.5 text-center w-12">
                        <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                    </th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-8">#</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Platform</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">URL Link</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-28">Status</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($socials as $social)
                <tr class="hover:bg-slate-50 transition-colors" :class="{ 'bg-primary/5': selectedIds.includes('{{ $social->id }}') }">
                    <td class="px-5 py-4 text-center">
                        <input type="checkbox" value="{{ $social->id }}" x-model="selectedIds" @change="updateSelectAll()" class="row-checkbox rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                    </td>
                    <td class="px-5 py-4 text-slate-400 text-xs">{{ $loop->iteration + ($socials->currentPage() - 1) * $socials->perPage() }}</td>
                    <td class="px-5 py-4 font-semibold text-slate-800">{{ $social->platform }}</td>
                    <td class="px-5 py-4 text-slate-600">
                        <a href="{{ $social->url }}" target="_blank" class="hover:text-blue-600 transition-colors break-all">{{ $social->url }}</a>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                            {{ $social->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $social->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if($status === 'trash')
                                {{-- Restore Button --}}
                                <form action="{{ route('socials.restore', $social->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Kembalikan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                        </svg>
                                    </button>
                                </form>
                                {{-- Force Delete Button --}}
                                <form action="{{ route('socials.force-delete', $social->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sosial media ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Permanen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                {{-- Edit --}}
                                <a href="{{ route('socials.edit', $social) }}"
                                   class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                {{-- Move to Trash --}}
                                <form action="{{ route('socials.destroy', $social) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin memindahkan sosial media ini ke Sampah?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Buang ke Sampah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="text-sm">Tidak ada data sosial media.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($socials->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $socials->links() }}
        </div>
        @endif
    </div>

    {{-- Floating Bulk Action Bar --}}
    <div x-show="selectedIds.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         style="display: none;"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900/95 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl flex flex-col sm:flex-row items-center gap-4 sm:gap-6 border border-slate-800 min-w-[300px] sm:min-w-[450px] justify-between">
        <div class="text-sm font-semibold flex items-center gap-2">
            <span class="flex h-3.5 w-3.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary/40 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-primary"></span>
            </span>
            <span x-text="selectedIds.length" class="text-primary font-bold text-base"></span> sosial media terpilih
        </div>
        <div class="flex items-center gap-3">
            @if($status === 'trash')
                <form action="{{ route('socials.bulk-action') }}" method="POST" class="inline-block">
                    @csrf
                    <input type="hidden" name="action" value="restore">
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg">
                        Kembalikan
                    </button>
                </form>
                <form action="{{ route('socials.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus permanen sosial media yang dipilih?')">
                    @csrf
                    <input type="hidden" name="action" value="force-delete">
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition-all shadow-lg">
                        Hapus Permanen
                    </button>
                </form>
            @else
                <form action="{{ route('socials.bulk-action') }}" method="POST" class="inline-block" onsubmit="return confirm('Pindahkan sosial media terpilih ke Sampah?')">
                    @csrf
                    <input type="hidden" name="action" value="delete">
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition-all shadow-lg">
                        Hapus Terpilih
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection
