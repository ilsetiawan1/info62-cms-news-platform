<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Info Seputar +62</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-700 dark:text-gray-200 bg-slate-50 dark:bg-navy-900 antialiased min-h-screen flex items-center justify-center p-4 transition-colors duration-300">
    
    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/logo-infoseputar62.png') }}" alt="Info Seputar +62" class="h-16 w-auto object-contain">
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-200 dark:border-gray-700 p-6 sm:p-8 transition-all duration-300">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-gray-50 mb-2">Selamat Datang Kembali</h1>
                <p class="text-slate-500 dark:text-gray-400 text-sm">Masuk ke panel admin untuk mengelola portal berita.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-accent dark:text-accent-500 bg-accent/10 dark:bg-accent-500/10 p-3 rounded-lg text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-accent dark:text-accent-500" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-gray-200">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-primary dark:text-primary-500 hover:text-primary/80 dark:hover:text-primary-500/80 transition-colors" href="{{ route('password.request') }}">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password" 
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-accent dark:text-accent-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-primary dark:text-primary-500 shadow-sm focus:ring-primary dark:focus:ring-primary-500 dark:bg-gray-900" name="remember">
                        <span class="ms-2 text-sm text-slate-600 dark:text-gray-300">Ingat Saya</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-500 transition-all duration-200">
                        Masuk
                    </button>
                </div>
            </form>
        </div>

        <!-- Dark Mode Toggle Helper Script (Since it's a standalone page) -->
        <script>
            // Check for saved theme preference or use system preference
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </div>
</body>
</html>
