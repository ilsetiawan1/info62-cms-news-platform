@extends('layouts.admin')

@section('header', 'Kelola Iklan')

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
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Daftar Iklan</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400">Kelola banner iklan yang akan tampil di portal publik.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Bulk Action Buttons Above Table -->
            <div x-show="selectedIds.length > 0" x-transition style="display: none;" class="flex items-center gap-2">
                <form action="{{ route('advertisements.bulk-delete') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan yang dipilih?');">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl shadow-sm text-xs font-bold text-white bg-red-600 hover:bg-red-500 transition-all duration-200">
                        Hapus Terpilih (<span x-text="selectedIds.length"></span>)
                    </button>
                </form>
            </div>

            <a href="{{ route('advertisements.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 w-full sm:w-auto">
                Tambah Iklan
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-gray-800/50 text-xs uppercase text-slate-500 dark:text-gray-400 font-bold border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        </th>
                        <th class="px-6 py-4">Preview</th>
                        <th class="px-6 py-4">Judul & Link</th>
                        <th class="px-6 py-4">Posisi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($advertisements as $ad)
                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/50" :class="{ 'bg-primary/5': selectedIds.includes('{{ $ad->id }}') }">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <input type="checkbox" value="{{ $ad->id }}" x-model="selectedIds" @change="updateSelectAll()" class="row-checkbox rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        </td>
                        <td class="px-6 py-4">
                            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="h-12 w-auto max-w-[100px] object-contain bg-slate-100 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700">
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 dark:text-gray-100">{{ $ad->title }}</div>
                            <a href="{{ $ad->url ?? '#' }}" target="_blank" class="text-xs text-primary hover:underline truncate inline-block max-w-[200px]">{{ $ad->url ?? 'Tidak ada link' }}</a>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $friendlyPositions = [
                                    'slot1' => 'Slot 1 (Grid Kiri Atas)',
                                    'slot2' => 'Slot 2 (Grid Kiri Bawah)',
                                    'slot3' => 'Slot 3 (Grid Tengah - Bawah Slider)',
                                    'slot4' => 'Slot 4 (Grid Tengah - Sela Card)',
                                    'slot5' => 'Slot 5 (Grid Tengah - Atas Terkini)',
                                    'slot6' => 'Slot 6 (Grid Tengah - Sela Terkini)',
                                    'slot7' => 'Slot 7 (Grid Tengah - Atas Footer)',
                                    'slot8' => 'Slot 8 (Grid Kanan - Bawah Terpopuler)',
                                    'slot9' => 'Slot 9 (Grid Kanan - Bawah Topik)',
                                    'slot10' => 'Slot 10 (Grid Kanan - Bawah Sorotan)',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 rounded text-xs font-semibold whitespace-nowrap">
                                {{ $friendlyPositions[$ad->position] ?? Str::title(str_replace('_', ' ', $ad->position)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($ad->status === 'active')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded text-xs font-semibold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded text-xs font-semibold">Inaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('advertisements.edit', $ad->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg dark:hover:bg-blue-900/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('advertisements.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Hapus iklan ini?');">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg dark:hover:bg-red-900/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">Belum ada data iklan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($advertisements->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $advertisements->links() }}
        </div>
        @endif
    </div>

    <!-- Floating Bulk Action Bar for Advertisements -->
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
            <span x-text="selectedIds.length" class="text-primary font-bold text-base"></span> iklan terpilih
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('advertisements.bulk-delete') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan yang dipilih?');">
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
