@extends('layouts.admin')

@section('header', 'Kelola Iklan')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Daftar Iklan</h2>
        <p class="text-sm text-slate-500 dark:text-gray-400">Kelola banner iklan yang akan tampil di portal publik.</p>
    </div>
    <a href="{{ route('advertisements.create') }}" class="px-5 py-2.5 rounded-xl bg-primary text-white font-semibold hover:bg-primary/90 transition-all">Tambah Iklan</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-gray-800/50 text-xs uppercase text-slate-500 dark:text-gray-400 font-bold border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-4">Preview</th>
                    <th class="px-6 py-4">Judul & Link</th>
                    <th class="px-6 py-4">Posisi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($advertisements as $ad)
                <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="h-12 w-auto max-w-[100px] object-contain bg-slate-100 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900 dark:text-gray-100">{{ $ad->title }}</div>
                        <a href="{{ $ad->url ?? '#' }}" target="_blank" class="text-xs text-primary hover:underline truncate inline-block max-w-[200px]">{{ $ad->url ?? 'Tidak ada link' }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 rounded text-xs font-semibold">{{ Str::title(str_replace('_', ' ', $ad->position)) }}</span>
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
                <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada data iklan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $advertisements->links() }}
    </div>
</div>
@endsection
