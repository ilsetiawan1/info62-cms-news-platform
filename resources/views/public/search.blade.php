@extends('layouts.public')

@section('meta_title', 'Hasil Pencarian: ' . $query . ' - Info Seputar +62')
@section('meta_description', 'Hasil pencarian berita untuk ' . $query . ' di Info Seputar +62.')
@section('canonical', request()->fullUrl())

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    <div class="grid grid-cols-12 gap-6">

        {{-- ===== SIDEBAR KIRI (Desktop only) ===== --}}
        <aside class="hidden lg:block lg:col-span-2">
            <div class="sticky top-[136px] flex flex-col gap-6">
                {{-- Prakiraan Cuaca +62 --}}
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-blue-100 dark:border-slate-700 p-4 text-white shadow-md relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 opacity-10">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75zM6.16 5.1a.75.75 0 0 1 1.06 0l1.59 1.59a.75.75 0 1 1-1.06 1.06L6.16 6.16a.75.75 0 0 1 0-1.06zm11.68 0a.75.75 0 0 1 0 1.06l-1.59 1.59a.75.75 0 1 1-1.06-1.06l1.59-1.59a.75.75 0 0 1 1.06 0zM12 5.25a6.75 6.75 0 1 0 6.75 6.75A6.75 6.75 0 0 0 12 5.25zM3 12a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12zm15 0a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5h-2.25A.75.75 0 0 1 18 12zm-11.84 5.25a.75.75 0 0 1 1.06 0l1.59 1.59a.75.75 0 1 1-1.06 1.06l-1.59-1.59a.75.75 0 0 1 0-1.06zm9.68 0a.75.75 0 0 1 0 1.06l-1.59 1.59a.75.75 0 1 1-1.06-1.06l1.59-1.59a.75.75 0 0 1 1.06 0zM12 18.75a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V19.5a.75.75 0 0 1 .75-.75z"/></svg>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black tracking-wider uppercase text-blue-100/90">Cuaca Nusantara</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-white/20 text-white backdrop-blur-sm">Live</span>
                    </div>
                    <div class="space-y-3" x-data="{ 
                        cities: [
                            { name: 'Jakarta', temp: '32°C', desc: 'Cerah Berawan', icon: '⛅' },
                            { name: 'Surabaya', temp: '34°C', desc: 'Cerah', icon: '☀️' },
                            { name: 'Bandung', temp: '25°C', desc: 'Hujan Ringan', icon: '🌧️' },
                            { name: 'Medan', temp: '30°C', desc: 'Berawan', icon: '☁️' }
                        ],
                        activeIdx: 0,
                        init() {
                            setInterval(() => {
                                this.activeIdx = (this.activeIdx + 1) % this.cities.length;
                            }, 5000);
                        }
                    }">
                        <div class="bg-white/10 dark:bg-slate-900/40 rounded-xl p-3 backdrop-blur-sm transition-all duration-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-extrabold tracking-tight" x-text="cities[activeIdx].name">Jakarta</h4>
                                    <p class="text-[11px] text-blue-100/80" x-text="cities[activeIdx].desc">Cerah Berawan</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black" x-text="cities[activeIdx].temp">32°C</div>
                                    <div class="text-lg" x-text="cities[activeIdx].icon">⛅</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trivia Nusantara --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm relative overflow-hidden" x-data="{
                    facts: [
                        'Indonesia memiliki garis pantai terpanjang kedua di dunia setelah Kanada, membentang lebih dari 54.000 kilometer.',
                        'Komodo adalah kadal terbesar dan terberat di dunia yang masih hidup, and hanya dapat ditemukan di habitat aslinya di NTT, Indonesia.',
                        'Candi Borobudur di Magelang, Jawa Tengah, merupakan candi Buddha terbesar di dunia dan salah satu monumen Buddha terbesar di bumi.',
                        'Indonesia adalah negara kepulauan terbesar di dunia, dengan jumlah pulau mencapai lebih dari 17.000 pulau resmi.',
                        'Danau Toba di Sumatera Utara merupakan danau vulkanik terbesar di dunia, terbentuk dari letusan supervolcano dahsyat ribuan tahun lalu.',
                        'Rafflesia arnoldii, bunga tunggal terbesar di dunia dengan diameter mencapai 1 meter, tumbuh di hutan hujan Sumatra.',
                        'Puncak Jaya di Papua adalah salah satu dari sedikit tempat di dekat garis khatulistiwa yang memiliki gletser es abadi.',
                        'Indonesia merupakan salah satu negara megabiodiversitas terbesar, menampung sekitar 10-15% dari seluruh spesies tumbuhan, mamalia, dan burung di dunia.',
                        'Garis imajiner Wallace membagi fauna Indonesia menjadi tipe Asiatis di bagian barat dan tipe Australis di bagian timur.',
                        'Indonesia memiliki lebih dari 700 bahasa daerah aktif, menjadikannya salah satu negara dengan keragaman bahasa terbanyak di dunia.'
                    ],
                    factIdx: 0,
                    init() {
                        setInterval(() => {
                            this.factIdx = (this.factIdx + 1) % this.facts.length;
                        }, 12000);
                    }
                }">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-red-500 text-sm">💡</span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Fakta Nusantara</h3>
                    </div>
                    <p class="text-[12px] text-slate-600 dark:text-slate-300 leading-relaxed font-medium transition-all duration-500 min-h-[72px]" x-text="facts[factIdx]">
                        Indonesia memiliki garis pantai terpanjang kedua di dunia setelah Kanada, membentang lebih dari 54.000 kilometer.
                    </p>
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100 dark:border-slate-700/50">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500" x-text="`${factIdx + 1} / ${facts.length}`">1 / 10</span>
                        <div class="flex items-center gap-1.5">
                            <button @click="factIdx = (factIdx - 1 + facts.length) % facts.length" 
                                    class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-700 transition-colors"
                                    aria-label="Fakta sebelumnya">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button @click="factIdx = (factIdx + 1) % facts.length" 
                                    class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-700 transition-colors"
                                    aria-label="Fakta berikutnya">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Waktu & Hari Lokal --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 text-center shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Waktu Lokal</h3>
                    <div id="local-clock" class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">00:00:00</div>
                    <div id="local-date" class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">---</div>
                </div>

                {{-- Kurs Nusantara --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm relative overflow-hidden">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-emerald-500 text-sm">💱</span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Kurs Rupiah</h3>
                    </div>
                    <div class="flex flex-col gap-2">
                        {{-- USD --}}
                        <div class="flex flex-col p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 rounded">USD</span>
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">USD / IDR</span>
                                </div>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-black {{ ($financialData['usd_change'] ?? 0) >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ ($financialData['usd_change'] ?? 0) >= 0 ? '▲' : '▼' }}{{ abs($financialData['usd_change'] ?? 0) }}%
                                </span>
                            </div>
                            <div class="text-[14px] font-black text-slate-900 dark:text-white">
                                Rp{{ number_format($financialData['usd_to_idr'] ?? 16250, 0, ',', '.') }}
                            </div>
                        </div>
                        {{-- SGD --}}
                        <div class="flex flex-col p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 rounded">SGD</span>
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">SGD / IDR</span>
                                </div>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-black {{ ($financialData['sgd_change'] ?? 0) >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ ($financialData['sgd_change'] ?? 0) >= 0 ? '▲' : '▼' }}{{ abs($financialData['sgd_change'] ?? 0) }}%
                                </span>
                            </div>
                            <div class="text-[14px] font-black text-slate-900 dark:text-white">
                                Rp{{ number_format($financialData['sgd_to_idr'] ?? 12050, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Logam Mulia --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-bl from-amber-500/10 to-transparent pointer-events-none rounded-bl-full"></div>
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-amber-500 text-sm">✨</span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Harga Emas</h3>
                    </div>
                    <div class="flex flex-col p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Antam / Gram</span>
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[9px] font-black {{ ($financialData['gold_change'] ?? 0) >= 0 ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500' : 'bg-rose-50 dark:bg-rose-950/20 text-rose-500' }}">
                                {{ ($financialData['gold_change'] ?? 0) >= 0 ? '▲' : '▼' }}{{ abs($financialData['gold_change'] ?? 0) }}%
                            </span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <div class="text-[15px] font-black text-slate-900 dark:text-white">
                                Rp{{ number_format($financialData['gold_price'] ?? 1350000, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-amber-500 dark:text-amber-400 font-bold">LM 24 Karat</div>
                        </div>
                    </div>
                    <div class="mt-3 text-[9px] text-slate-400 dark:text-slate-500 flex flex-col gap-0.5 border-t border-slate-100 dark:border-slate-700/50 pt-2">
                        <div class="flex items-center justify-between">
                            <span>Pembaruan otomatis</span>
                            <span class="font-bold text-slate-500 dark:text-slate-400">{{ $financialData['updated_at'] ?? now()->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="col-span-12 md:col-span-8 lg:col-span-6">

            {{-- Search Header --}}
            <div class="mb-8">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-medium">
                        <li><a href="{{ route('home') }}" class="text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400">Beranda</a></li>
                        <li><span class="text-slate-300 dark:text-slate-600 mx-1">/</span></li>
                        <li aria-current="page">
                            <span class="text-slate-700 dark:text-slate-300">Pencarian</span>
                        </li>
                    </ol>
                </nav>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Hasil Pencarian</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                            Menampilkan hasil untuk: "<span class="font-bold text-slate-900 dark:text-white">{{ $query }}</span>" ({{ $articles->total() }} artikel ditemukan)
                        </p>
                    </div>
                </div>
            </div>

            @if($articles->isNotEmpty())

            {{-- Featured first article --}}
            @if($articles->currentPage() === 1)
            @php $first = $articles->first(); @endphp
            <a href="{{ route('article.show', $first->slug) }}" class="group block mb-7 pb-7 border-b border-slate-200 dark:border-slate-700">
                <div class="relative w-full aspect-[16/9] rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 mb-4">
                    @if($first->cover_image)
                    <img src="{{ $first->cover_image_url }}" alt="{{ $first->title }}"
                         class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @endif
                </div>
                <span class="text-[10px] font-bold text-blue-600 dark:text-sky-400 uppercase tracking-widest">{{ $first->category->name }} • {{ $first->published_at?->format('d M Y') }}</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white leading-[1.3] tracking-tight group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors mt-2 mb-2">{{ $first->title }}</h2>
                @if($first->excerpt ?? false)
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">{{ $first->excerpt }}</p>
                @endif
            </a>
            @endif

            {{-- Article list --}}
            <div class="grid grid-cols-12 gap-4 md:block">
                <div class="col-span-8 sm:col-span-9 md:col-span-12 divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach(($articles->currentPage() === 1 ? $articles->skip(1) : $articles) as $art)
                    <a href="{{ route('article.show', $art->slug) }}" class="group flex gap-4 py-4 first:pt-0">
                        <div class="w-24 h-[70px] flex-shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            @if($art->cover_image)
                            <img src="{{ $art->cover_image_url }}" alt="{{ $art->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold text-blue-600 dark:text-sky-400 uppercase tracking-wider">{{ $art->category->name }}</span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500">{{ $art->published_at?->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-[14px] font-semibold text-slate-900 dark:text-slate-100 leading-[1.4] tracking-tight group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors line-clamp-2">
                                {{ $art->title }}
                            </h3>
                        </div>
                    </a>
                    @endforeach
                </div>
                @if(isset($adLeftTop) && $adLeftTop)
                <div class="col-span-4 sm:col-span-3 md:hidden flex flex-col justify-start pt-2">
                    <a href="{{ $adLeftTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adLeftTop->image_url }}" alt="{{ $adLeftTop->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                    </a>
                </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-center">
                {{ $articles->links() }}
            </div>
            @endif

            @else
            <div class="text-center py-16 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-200 dark:border-slate-700">
                <p class="text-slate-500 dark:text-slate-400 font-medium mb-4">Belum ada artikel di kategori ini.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2 rounded-full bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-colors">Kembali ke Beranda</a>
            </div>
            @endif

        </main>

        {{-- ===== SIDEBAR KANAN ===== --}}
        <aside class="col-span-12 md:col-span-4 lg:col-span-4">
            <div class="md:sticky md:top-[136px] flex flex-col gap-6">

                {{-- Tablet Sidebar Ad 1 (sidebar_mid on Tablet) --}}
                @if(isset($adLeftTop) && $adLeftTop)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adLeftTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adLeftTop->image_url }}" alt="{{ $adLeftTop->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                    </a>
                </div>
                @endif

                {{-- Terpopuler --}}
                <div class="grid grid-cols-12 gap-4 md:block">
                    <div class="col-span-8 sm:col-span-9 md:col-span-12 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                            <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                            <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Terpopuler</h3>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @foreach(App\Models\Article::where('status','published')->orderByDesc('views_count')->limit(6)->get() as $i => $pop)
                            <a href="{{ route('article.show', $pop->slug) }}"
                               class="group flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <span class="text-xl font-black leading-none flex-shrink-0 w-6 text-center mt-0.5 {{ $i === 0 ? 'text-red-500' : 'text-slate-200 dark:text-slate-700' }}">{{ $i+1 }}</span>
                                <p class="text-[12px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.4] line-clamp-3 group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">{{ $pop->title }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @if(isset($adRightTop) && $adRightTop)
                    <div class="col-span-4 sm:col-span-3 md:hidden flex flex-col justify-start">
                        <a href="{{ $adRightTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                            <img src="{{ $adRightTop->image_url }}" alt="{{ $adRightTop->title }}" class="w-full h-auto max-h-[240px] object-contain mx-auto">
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Tablet Sidebar Ad 2 (article_mid on Tablet) --}}
                @if(isset($adLeftBottom) && $adLeftBottom)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adLeftBottom->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adLeftBottom->image_url }}" alt="{{ $adLeftBottom->title }}" class="w-full h-auto max-h-[240px] object-contain mx-auto">
                    </a>
                </div>
                @endif

                {{-- Topik Populer --}}
                <div class="grid grid-cols-12 gap-4 md:block">
                    <div class="col-span-8 sm:col-span-9 md:col-span-12 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-1 h-4 bg-blue-600 dark:bg-sky-500 rounded-full"></span>
                            <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Topik Populer</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if(isset($popularTopics) && $popularTopics->isNotEmpty())
                                @foreach($popularTopics as $topic)
                                    <a href="{{ route('category.show', $topic->slug) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 transition-colors border border-slate-100 dark:border-slate-700">#{{ str_replace(' ', '', $topic->name) }}</a>
                                @endforeach
                            @else
                                <span class="text-xs text-slate-500 dark:text-slate-400">Tidak ada topik populer.</span>
                            @endif
                        </div>
                    </div>
                    @if(isset($adRightBottom) && $adRightBottom)
                    <div class="col-span-4 sm:col-span-3 md:hidden flex flex-col justify-start">
                        <a href="{{ $adRightBottom->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                            <img src="{{ $adRightBottom->image_url }}" alt="{{ $adRightBottom->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Tablet Sidebar Ad 3 (sidebar_top on Tablet) --}}
                @if(isset($adRightTop) && $adRightTop)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adRightTop->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adRightTop->image_url }}" alt="{{ $adRightTop->title }}" class="w-full h-auto max-h-[240px] object-contain mx-auto">
                    </a>
                </div>
                @endif

                {{-- Tablet Sidebar Ad 4 (article_bottom on Tablet) --}}
                @if(isset($adRightBottom) && $adRightBottom)
                <div class="hidden md:block lg:!hidden w-full flex justify-center">
                    <a href="{{ $adRightBottom->url ?? '#' }}" target="_blank" rel="noopener" class="block w-full max-w-[280px] overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/40 p-1 shadow-sm">
                        <img src="{{ $adRightBottom->image_url }}" alt="{{ $adRightBottom->title }}" class="w-full h-auto max-h-[380px] object-contain mx-auto">
                    </a>
                </div>
                @endif

            </div>
        </aside>

    </div>
</div>
@endsection
