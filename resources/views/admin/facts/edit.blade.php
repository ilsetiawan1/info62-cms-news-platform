@extends('layouts.admin')

@section('header', 'Edit Fakta Nusantara')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('facts.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">Edit Fakta</h1>
            <p class="text-sm text-slate-500">Perbarui isi fakta atau ubah statusnya.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('facts.update', $fact) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Fakta <span class="text-red-500">*</span></label>
                <textarea name="content" rows="5" maxlength="1000"
                    placeholder="Tulis fakta menarik tentang Indonesia di sini..."
                    class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:ring-primary focus:border-primary px-4 py-3 text-sm resize-none @error('content') border-red-400 @enderror">{{ old('content', $fact->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-slate-400">Maksimal 1000 karakter.</p>
            </div>

            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $fact->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-primary border-gray-300 focus:ring-primary">
                <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">Aktifkan fakta ini</label>
                <span class="text-xs text-slate-400">(Hanya fakta aktif yang tampil di halaman depan)</span>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:opacity-90 transition">
                    Perbarui Fakta
                </button>
                <a href="{{ route('facts.index') }}"
                    class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
