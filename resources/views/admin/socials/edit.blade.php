@extends('layouts.admin')

@section('header', 'Edit Sosial Media')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('socials.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">Edit Sosial Media</h1>
            <p class="text-sm text-slate-500">Ubah detail tautan sosial media untuk footer publik.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('socials.update', $social) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Platform <span class="text-red-500">*</span></label>
                <select name="platform" class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:ring-primary focus:border-primary px-4 py-3 text-sm @error('platform') border-red-400 @enderror">
                    <option value="Facebook" {{ old('platform', $social->platform) == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="X / Twitter" {{ old('platform', $social->platform) == 'X / Twitter' ? 'selected' : '' }}>X / Twitter</option>
                    <option value="Instagram" {{ old('platform', $social->platform) == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="YouTube" {{ old('platform', $social->platform) == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                    <option value="TikTok" {{ old('platform', $social->platform) == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                    <option value="Telegram" {{ old('platform', $social->platform) == 'Telegram' ? 'selected' : '' }}>Telegram</option>
                </select>
                @error('platform')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">URL Link <span class="text-red-500">*</span></label>
                <input type="url" name="url" value="{{ old('url', $social->url) }}"
                    placeholder="https://instagram.com/infoseputar62"
                    class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:ring-primary focus:border-primary px-4 py-3 text-sm @error('url') border-red-400 @enderror">
                @error('url')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-slate-400">Harus berupa link URL valid lengkap (diawali dengan https:// atau http://).</p>
            </div>

            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $social->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-primary border-gray-300 focus:ring-primary">
                <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">Aktifkan sosial media ini</label>
                <span class="text-xs text-slate-400">(Akan langsung ditayangkan di footer)</span>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:opacity-90 transition">
                    Perbarui Sosial Media
                </button>
                <a href="{{ route('socials.index') }}"
                    class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
