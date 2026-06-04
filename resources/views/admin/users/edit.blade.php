@extends('layouts.admin')

@section('header', 'Edit Pengguna')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Edit Pengguna</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400">Perbarui informasi pengguna: <strong>{{ $user->name }}</strong>.</p>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden max-w-3xl">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                @error('name')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                @error('email')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Password Baru <span class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" id="password" name="password"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                    @error('password')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Status Akun</label>
                    <select id="status" name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5 disabled:cursor-not-allowed disabled:bg-slate-50 dark:disabled:bg-gray-800 disabled:text-slate-500" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                        <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @if($user->id === Auth::id())
                        <p class="mt-1 text-xs text-slate-400">Anda tidak bisa menonaktifkan akun sendiri.</p>
                        <!-- Hidden input to still submit the true status -->
                        <input type="hidden" name="status" value="{{ $user->status }}">
                    @endif
                    @error('status')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>

                 <!-- Role (Only for Super Admin) -->
                @if(Auth::user()->isSuperAdmin())
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Role Pengguna</label>
                    <select id="role" name="role" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5 disabled:cursor-not-allowed disabled:bg-slate-50 dark:disabled:bg-gray-800 disabled:text-slate-500" {{ auth()->user()->id == $user->id ? 'disabled' : '' }}>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    @if(auth()->user()->id == $user->id)
                        <p class="mt-1 text-xs text-slate-400">Anda tidak bisa mengubah role sendiri.</p>
                        <!-- Hidden input to still submit the true role -->
                        <input type="hidden" name="role" value="{{ $user->role }}">
                    @endif
                    @error('role')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>
                @else
                    <!-- Just show role text for normal admin without select -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Role Pengguna</label>
                        <div class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-slate-500 dark:text-gray-400 cursor-not-allowed">
                            {{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
