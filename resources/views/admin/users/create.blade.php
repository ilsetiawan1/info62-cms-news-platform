@extends('layouts.admin')

@section('header', 'Tambah Pengguna')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Tambah Pengguna Baru</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400">Silakan lengkapi form di bawah ini untuk menambahkan pengguna.</p>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden max-w-3xl">
        <form action="{{ route('users.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                @error('name')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                @error('email')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                    @error('password')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Status Akun</label>
                    <select id="status" name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role (Only for Super Admin) -->
                @if(Auth::user()->isSuperAdmin())
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Role Pengguna</label>
                    <select id="role" name="role" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>
                @else
                    <input type="hidden" name="role" value="admin">
                @endif
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
@endsection
