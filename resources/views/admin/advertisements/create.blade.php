@extends('layouts.admin')

@section('header', 'Tambah Iklan')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Judul / Nama Iklan</label>
                <input type="text" name="title" required value="{{ old('title') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Gambar / Banner</label>
                <input type="file" name="image_path" required accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">URL / Link Tujuan (Opsional)</label>
                <input type="url" name="url" value="{{ old('url') }}" placeholder="https://" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Posisi</label>
                    <select name="position" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="sidebar_mid">Iklan Sayap Kiri Atas (160x380)</option>
                        <option value="article_mid">Iklan Sayap Kiri Bawah (160x204)</option>
                        <option value="sidebar_top">Iklan Sayap Kanan Atas (160x204)</option>
                        <option value="article_bottom">Iklan Sayap Kanan Bawah (160x380)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="active">Aktif</option>
                        <option value="inactive">Inaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Mulai Tayang (Opsional)</label>
                    <input type="datetime-local" name="start_date" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Akhir Tayang (Opsional)</label>
                    <input type="datetime-local" name="end_date" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('advertisements.index') }}" class="px-5 py-2.5 text-slate-600 dark:text-gray-300 font-medium hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-all">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-all">Simpan Iklan</button>
            </div>
        </form>
    </div>
</div>
@endsection
