<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $name }} - Coming Soon</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-[#0b1329] text-white overflow-hidden h-screen flex flex-col justify-between">
    <!-- Top Nav Line -->
    <header class="py-6 px-8 max-w-7xl mx-auto w-full flex justify-between items-center z-10">
        <a href="/" class="flex items-center gap-2">
            <span class="text-xl font-black bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent tracking-tight">Info Seputar +62</span>
        </a>
        <a href="/" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors duration-200 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </header>

    <!-- Main Hero Section -->
    <main class="flex-grow flex items-center justify-center px-4 relative">
        <!-- Glow effects -->
        <div class="absolute w-[300px] h-[300px] bg-blue-500/10 rounded-full blur-[120px] top-1/2 left-1/4 -translate-y-1/2"></div>
        <div class="absolute w-[300px] h-[300px] bg-purple-500/10 rounded-full blur-[120px] top-1/2 right-1/4 -translate-y-1/2"></div>

        <div class="text-center z-10 max-w-2xl px-6 py-12 rounded-3xl bg-white/[0.02] border border-white/[0.05] backdrop-blur-xl shadow-2xl">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-8 animate-pulse">
                Portal Jaringan Baru
            </div>
            
            <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 text-white">
                {{ $name }}
            </h1>
            
            <p class="text-xl font-bold bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent uppercase tracking-wider mb-6">
                (COOMING SOON)
            </p>
            
            <p class="text-slate-400 text-sm md:text-base max-w-md mx-auto mb-8 leading-relaxed">
                Kami sedang menyiapkan portal berita terbaik untuk menyajikan berita regional, olahraga, hobi, dan gaya hidup terbaik untuk Anda. Nantikan segera!
            </p>

            <a href="/" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-sm font-semibold shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 transition-all duration-300 transform hover:-translate-y-0.5">
                Kembali ke Portal Utama
            </a>
        </div>
    </main>

    <!-- Bottom Footer Line -->
    <footer class="py-6 text-center text-xs text-slate-500 z-10 border-t border-white/[0.03] bg-black/10">
        &copy; {{ date('Y') }} Info Seputar +62 Group. All rights reserved.
    </footer>
</body>
</html>
