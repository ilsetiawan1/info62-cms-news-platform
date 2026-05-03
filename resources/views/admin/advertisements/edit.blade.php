@extends('layouts.admin')

@section('header', 'Edit Iklan')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ route('advertisements.update', $advertisement->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Judul / Nama Iklan</label>
                <input type="text" name="title" required value="{{ old('title', $advertisement->title) }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Gambar / Banner</label>
                <div class="mb-3">
                    <img src="{{ $advertisement->image_url }}" alt="Preview" class="h-20 w-auto object-contain border rounded-lg bg-slate-50 dark:bg-gray-900 p-1">
                </div>
                <input type="file" name="image_path" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">URL / Link Tujuan (Opsional)</label>
                <input type="url" name="url" value="{{ old('url', $advertisement->url) }}" placeholder="https://" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Posisi</label>
                    <select name="position" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="sidebar_top" {{ $advertisement->position === 'sidebar_top' ? 'selected' : '' }}>Sidebar Kanan (Atas)</option>
                        <option value="sidebar_mid" {{ $advertisement->position === 'sidebar_mid' ? 'selected' : '' }}>Sidebar Kiri (160x200)</option>
                        <option value="article_mid" {{ $advertisement->position === 'article_mid' ? 'selected' : '' }}>Tengah Konten (728x90)</option>
                        <option value="article_bottom" {{ $advertisement->position === 'article_bottom' ? 'selected' : '' }}>Bawah Halaman / Footer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="active" {{ $advertisement->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $advertisement->status === 'inactive' ? 'selected' : '' }}>Inaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Mulai Tayang (Opsional)</label>
                    <input type="datetime-local" name="start_date" value="{{ $advertisement->start_date ? $advertisement->start_date->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Akhir Tayang (Opsional)</label>
                    <input type="datetime-local" name="end_date" value="{{ $advertisement->end_date ? $advertisement->end_date->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('advertisements.index') }}" class="px-5 py-2.5 text-slate-600 dark:text-gray-300 font-medium hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-all">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-all">Update Iklan</button>
            </div>
        </form>
    </div>
</div>
@endsection
