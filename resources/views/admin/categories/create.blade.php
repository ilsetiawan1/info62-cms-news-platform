@extends('layouts.admin')

@section('header', 'Tambah Kategori')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Tambah Kategori Baru</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400">Silakan lengkapi form di bawah ini untuk menambahkan kategori artikel.</p>
        </div>
        <div>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden max-w-3xl">
        <form action="{{ route('categories.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Parent Category -->
            <div>
                <label for="parent_id" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Kategori Induk <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <select id="parent_id" name="parent_id" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                    <option value="">-- Jadikan Kategori Utama --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
                <p class="mt-1.5 text-xs text-slate-500 dark:text-gray-400">Pilih kategori induk jika ini adalah sub-kategori.</p>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Nama Kategori</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                @error('name')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Slug URL <span class="text-slate-400 font-normal">(Terisi Otomatis)</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5 font-mono text-sm">
                @error('slug')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
                <p class="mt-1.5 text-xs text-slate-500 dark:text-gray-400">Slug digunakan untuk URL kategori (contoh: nama-kategori). Kosongkan untuk men-generate otomatis.</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Deskripsi Singkat <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5 resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Script to Auto-generate Slug -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', function() {
                // If slug input is empty or user is typing the name, auto-generate slug
                // We only auto-fill if the user hasn't manually modified the slug too much,
                // but for simplicity we'll just update it if they are typing the name.
                let slug = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '') // remove non-word chars
                    .trim()
                    .replace(/[\s_-]+/g, '-') // swap spaces with dashes
                    .replace(/^-+|-+$/g, ''); // remove leading/trailing dashes
                
                slugInput.value = slug;
            });
        });
    </script>
@endsection
