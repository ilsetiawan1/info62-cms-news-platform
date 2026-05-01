@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
    <!-- Welcome Card -->
    <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 p-6 flex items-center justify-between transition-all">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50 mb-1">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-slate-500 dark:text-gray-400 text-sm">Selamat datang di Panel Admin Info Seputar +62. Hari ini adalah {{ now()->translatedFormat('l, d F Y') }}.</p>
        </div>
        <div class="hidden sm:block">
            <div class="w-12 h-12 rounded-full bg-primary/10 dark:bg-primary-500/20 text-primary dark:text-primary-500 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stats Grid (Placeholders for now) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Stat Card 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 dark:text-gray-400 text-sm font-medium mb-1">Total Artikel</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-gray-50">0</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-sm text-slate-500 dark:text-gray-400">
                <span class="text-emerald-500 font-medium flex items-center mr-2">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    0%
                </span>
                dari bulan lalu
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 dark:text-gray-400 text-sm font-medium mb-1">Total Pengunjung</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-gray-50">0</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-sm text-slate-500 dark:text-gray-400">
                <span class="text-emerald-500 font-medium flex items-center mr-2">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    0%
                </span>
                dari bulan lalu
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 dark:text-gray-400 text-sm font-medium mb-1">Kategori Aktif</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-gray-50">0</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-gray-700 text-orange-600 dark:text-orange-400 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-sm text-slate-500 dark:text-gray-400">
                <span class="text-slate-400 font-medium mr-2">-</span>
                tetap sama
            </div>
        </div>
    </div>

    <!-- Recent Activity Placeholder -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-gray-50">Aktivitas Terkini</h3>
            <button class="text-primary dark:text-primary-500 hover:text-primary/80 dark:hover:text-primary-500/80 text-sm font-medium">Lihat Semua</button>
        </div>
        <div class="p-8 text-center text-slate-500 dark:text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Belum ada aktivitas yang direkam.</p>
        </div>
    </div>
@endsection
