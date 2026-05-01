<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found - Info Seputar +62</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-700 dark:text-gray-200 bg-slate-50 dark:bg-navy-900 antialiased min-h-screen flex items-center justify-center p-4 transition-colors duration-300 relative overflow-hidden">
    
    <!-- Background Decor (iOS Style Blobs) -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 dark:bg-primary-500/10 rounded-full mix-blend-multiply filter blur-[80px] animate-blob"></div>
    <div class="absolute top-[20%] right-[-10%] w-[30rem] h-[30rem] bg-blue-300/20 dark:bg-blue-600/10 rounded-full mix-blend-multiply filter blur-[100px] animate-blob [animation-delay:2000ms]"></div>
    <div class="absolute bottom-[-20%] left-[20%] w-[25rem] h-[25rem] bg-indigo-300/20 dark:bg-indigo-600/10 rounded-full mix-blend-multiply filter blur-[100px] animate-blob [animation-delay:4000ms]"></div>

    <div class="w-full max-w-2xl relative z-10">
        <!-- Glass Card Container -->
        <div class="bg-white/70 dark:bg-gray-900/50 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)] border border-white/50 dark:border-gray-700/50 p-10 sm:p-16 text-center">
            
            <!-- Illustration / Icon -->
            <div class="flex justify-center mb-8 relative">
                <div class="w-24 h-24 bg-gradient-to-tr from-slate-100 to-white dark:from-gray-800 dark:to-gray-700 rounded-3xl shadow-[inset_0_-2px_6px_rgba(0,0,0,0.05),0_10px_20px_rgba(0,0,0,0.05)] dark:shadow-[inset_0_-2px_6px_rgba(255,255,255,0.05),0_10px_20px_rgba(0,0,0,0.2)] flex items-center justify-center border border-white/60 dark:border-gray-600/50 rotate-3 transition-transform hover:rotate-6">
                    <svg class="w-10 h-10 text-slate-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <!-- Mini decorative shapes -->
                <div class="absolute -top-4 -right-4 w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full blur-sm"></div>
                <div class="absolute -bottom-2 -left-4 w-6 h-6 bg-purple-100 dark:bg-purple-900/30 rounded-full blur-sm"></div>
            </div>

            <!-- Error Code -->
            <h1 class="text-7xl sm:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-br from-slate-800 to-slate-400 dark:from-white dark:to-slate-500 tracking-tight mb-2">
                404
            </h1>

            <!-- Error Message -->
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-gray-100 mb-4 tracking-tight">Halaman Tidak Ditemukan</h2>
            <p class="text-base sm:text-lg text-slate-500 dark:text-gray-400 mb-10 max-w-lg mx-auto leading-relaxed">
                Maaf, halaman yang Anda tuju sepertinya sedang tidak tersedia, telah dipindahkan, atau tidak pernah ada.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent rounded-2xl shadow-[0_8px_20px_rgba(30,58,138,0.2)] dark:shadow-[0_8px_20px_rgba(59,130,246,0.15)] text-base font-semibold text-white bg-gradient-to-b from-primary to-[#162d6b] hover:from-primary/90 hover:to-primary dark:from-primary-500 dark:to-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-500 transition-all duration-300 w-full sm:w-auto group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Ke Beranda
                </a>
                
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-slate-200/80 dark:border-gray-700/80 rounded-2xl shadow-sm text-base font-semibold text-slate-700 dark:text-gray-300 bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 focus:outline-none transition-all duration-300 w-full sm:w-auto backdrop-blur-md">
                    Kembali
                </a>
            </div>
        </div>
        
        <!-- Footer info -->
        <p class="text-center text-sm text-slate-400 dark:text-gray-500 mt-8">
            Info Seputar +62 &copy; {{ date('Y') }}
        </p>
    </div>

    <!-- Dark Mode Script -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</body>
</html>
