@extends('layouts.admin')

@section('header', 'Sosial Media')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sosial Media</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola tautan sosial media yang tampil di footer halaman publik.</p>
        </div>
        <a href="{{ route('socials.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:opacity-90 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Sosial Media
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-8">#</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Platform</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">URL Link</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-28">Status</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($socials as $social)
                <tr class="hover:bg-slate-50 transition-colors">
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
                            <a href="{{ route('socials.edit', $social) }}"
                               class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('socials.destroy', $social) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus sosial media ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="text-sm">Belum ada data sosial media. <a href="{{ route('socials.create') }}" class="text-primary hover:underline">Tambah sekarang</a>.</span>
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
</div>
@endsection
