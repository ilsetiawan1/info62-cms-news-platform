@extends('layouts.public')

@section('meta_title', ($article->meta_title ?: $article->title) . ' — Info Seputar +62')
@section('meta_description', $article->meta_description ?: $article->excerpt)
@section('meta_keywords', $article->keywords)
@section('canonical', route('article.show', $article->slug))
@section('og_title', $article->meta_title ?: $article->title)
@section('og_description', $article->meta_description ?: $article->excerpt)
@section('og_image', $article->cover_image ? Storage::url($article->cover_image) : asset('images/og-default.png'))
@section('og_type', 'article')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="flex flex-col lg:flex-row gap-8">

    {{-- ===== MAIN ARTICLE ===== --}}
    <article class="flex-1 min-w-0">

        {{-- Ads Top --}}
        <div class="ad-box w-full h-20 mb-6 flex-row gap-3">
            <svg class="w-5 h-5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            <span>Ruang Iklan &bull; 728 × 90</span>
        </div>

        {{-- Breadcrumb --}}
        <nav class="flex items-center flex-wrap text-sm text-slate-500 dark:text-gray-400 mb-5 gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-primary dark:hover:text-primary-500 transition-colors">Beranda</a>
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('category.show', $article->category->slug) }}" class="hover:text-primary dark:hover:text-primary-500 transition-colors">{{ $article->category->name }}</a>
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-800 dark:text-gray-200 font-medium line-clamp-1 max-w-xs">{{ $article->title }}</span>
        </nav>

        {{-- Category Badge --}}
        <a href="{{ route('category.show', $article->category->slug) }}" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary dark:bg-primary-500/10 dark:text-primary-500 mb-4 hover:bg-primary/20 transition-colors">
            {{ $article->category->name }}
        </a>

        {{-- Title --}}
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-gray-50 leading-tight mb-5">
            {{ $article->title }}
        </h1>

        {{-- Meta Row + Voice Reader --}}
        <div class="flex flex-wrap items-center gap-4 mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
            <span class="flex items-center gap-1.5 text-sm font-semibold text-slate-700 dark:text-gray-300">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ $article->author->name }} — Info Seputar+62
            </span>
            <span class="flex items-center gap-1.5 text-sm text-slate-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ $article->published_at?->isoFormat('D MMMM YYYY') }}
            </span>
            <span class="flex items-center gap-1.5 text-sm text-slate-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                {{ number_format($article->views_count) }} dibaca
            </span>

            {{-- Voice Reader Button --}}
            <button id="voice-btn" onclick="toggleVoice()"
                class="ml-auto flex items-center gap-2 px-4 py-2 rounded-xl bg-primary/10 hover:bg-primary/20 dark:bg-primary-500/10 dark:hover:bg-primary-500/20 text-primary dark:text-primary-500 text-sm font-semibold transition-all border border-primary/20 dark:border-primary-500/20">
                <svg id="voice-icon-play" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m-3.536-9.536a5 5 0 000 7.072M8.464 8.464a5 5 0 000 7.072M19.071 4.929a10 10 0 010 14.142"></path></svg>
                <svg id="voice-icon-stop" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h6v4H9z"></path></svg>
                <span id="voice-label">Dengarkan</span>
            </button>
        </div>

        {{-- Cover Image --}}
        @if($article->cover_image)
            <div class="mb-8 rounded-2xl overflow-hidden shadow-md border border-gray-100 dark:border-gray-700">
                <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}"
                     class="w-full h-auto max-h-[520px] object-cover">
            </div>
        @endif

        {{-- Excerpt callout --}}
        @if($article->excerpt)
            <div class="bg-primary/5 dark:bg-primary-500/5 border-l-4 border-primary dark:border-primary-500 rounded-r-xl px-5 py-4 mb-8 text-slate-700 dark:text-gray-300 italic text-base leading-relaxed">
                {{ $article->excerpt }}
            </div>
        @endif

        {{-- Article Content --}}
        <div id="article-text" class="article-content text-base sm:text-lg text-slate-700 dark:text-gray-300 leading-relaxed mb-10">
            {!! $article->content !!}
        </div>

        {{-- Source --}}
        @if($article->source_url)
            <div class="mb-8 p-4 bg-slate-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 text-sm">
                <span class="font-semibold text-slate-700 dark:text-gray-300">Sumber: </span>
                <a href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer" class="text-primary dark:text-primary-500 hover:underline break-all">{{ $article->source_url }}</a>
            </div>
        @endif

        {{-- Share Buttons --}}
        <div class="mb-10 p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm" x-data="{ copied: false }">
            <p class="text-sm font-bold text-slate-700 dark:text-gray-200 mb-3">Bagikan Artikel Ini:</p>
            <div class="flex flex-wrap gap-3">
                <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('article.show', $article->slug)) }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    WhatsApp
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('article.show', $article->slug)) }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                    X (Twitter)
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('article.show', $article->slug)) }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
                    Facebook
                </a>
                <button @click="navigator.clipboard.writeText('{{ route('article.show', $article->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-800 text-sm font-semibold transition-colors shadow-sm">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <svg x-show="copied" style="display:none" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
                </button>
            </div>
        </div>

        {{-- Related Same Category --}}
        @if($relatedSameCategory->isNotEmpty())
        <section class="mb-8">
            <div class="flex items-center gap-2.5 mb-5">
                <div class="w-1 h-5 bg-primary dark:bg-primary-500 rounded-full"></div>
                <h2 class="text-lg font-black text-slate-900 dark:text-gray-50">Artikel Serupa — {{ $article->category->name }}</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($relatedSameCategory as $rel)
                    <a href="{{ route('article.show', $rel->slug) }}" class="group flex gap-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 hover:shadow-md transition-all">
                        @if($rel->cover_image)
                            <div class="w-20 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                                <img src="{{ Storage::url($rel->cover_image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-gray-100 line-clamp-2 group-hover:text-primary dark:group-hover:text-primary-500 transition-colors leading-snug">{{ $rel->title }}</p>
                            <p class="text-xs text-slate-400 dark:text-gray-500 mt-1">{{ $rel->published_at?->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Related Other Categories --}}
        @if($relatedOther->isNotEmpty())
        <section>
            <div class="flex items-center gap-2.5 mb-5">
                <div class="w-1 h-5 bg-slate-300 dark:bg-gray-600 rounded-full"></div>
                <h2 class="text-lg font-black text-slate-900 dark:text-gray-50">Berita Menarik Lainnya</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($relatedOther as $rel)
                    <a href="{{ route('article.show', $rel->slug) }}" class="group flex gap-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-3 hover:shadow-md transition-all">
                        @if($rel->cover_image)
                            <div class="w-20 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                                <img src="{{ Storage::url($rel->cover_image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="min-w-0">
                            <span class="text-xs text-primary dark:text-primary-500 font-semibold">{{ $rel->category->name }}</span>
                            <p class="text-sm font-semibold text-slate-800 dark:text-gray-100 line-clamp-2 group-hover:text-primary dark:group-hover:text-primary-500 transition-colors leading-snug mt-0.5">{{ $rel->title }}</p>
                            <p class="text-xs text-slate-400 dark:text-gray-500 mt-1">{{ $rel->published_at?->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif
    </article>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="hidden lg:block w-80 flex-shrink-0 space-y-6">

        {{-- Ads Top Right --}}
        <div class="ad-box w-full h-64" id="ads-article-top">
            <svg class="w-8 h-8 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            <span>Ruang Iklan<br>300 × 250</span>
        </div>

        {{-- Most Read --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-5 bg-red-500 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-900 dark:text-gray-50 uppercase tracking-wider">Paling Banyak Dibaca</h3>
            </div>
            <ol class="space-y-4">
                @foreach(App\Models\Article::where('status','published')->orderByDesc('views_count')->limit(5)->get() as $i => $top)
                    <li class="flex gap-3 items-start group">
                        <span class="text-2xl font-black leading-none flex-shrink-0 {{ $i === 0 ? 'text-primary dark:text-primary-500' : 'text-slate-200 dark:text-gray-700' }} w-6">{{ $i + 1 }}</span>
                        <a href="{{ route('article.show', $top->slug) }}" class="text-sm font-semibold text-slate-700 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-500 transition-colors line-clamp-3 leading-snug">{{ $top->title }}</a>
                    </li>
                @endforeach
            </ol>
        </div>

        {{-- Ads Mid --}}
        <div class="ad-box w-full h-60" id="ads-article-mid">
            <svg class="w-8 h-8 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            <span>Ruang Iklan<br>300 × 250</span>
        </div>

        {{-- Categories --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-5 bg-primary dark:bg-primary-500 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-900 dark:text-gray-50 uppercase tracking-wider">Kategori</h3>
            </div>
            <ul class="space-y-1">
                @foreach($navCategories as $cat)
                    <li>
                        <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-slate-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-500 hover:bg-primary/5 dark:hover:bg-primary-500/10 font-medium transition-all">
                            {{ $cat->name }}
                            <svg class="w-4 h-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </aside>
</div>
</div>
@endsection

@push('scripts')
<script>
const synth = window.speechSynthesis;
let utterance = null;
let isReading = false;

function getReadableText() {
    const title = document.querySelector('h1') ? document.querySelector('h1').innerText : '';
    const excerpt = `{{ addslashes(strip_tags($article->excerpt ?? '')) }}`;
    const body = document.getElementById('article-text') ? document.getElementById('article-text').innerText : '';
    return title + '. ' + (excerpt ? excerpt + '. ' : '') + body;
}

function getIdVoice() {
    const voices = synth.getVoices();
    return voices.find(v => v.lang === 'id-ID')
        || voices.find(v => v.lang.startsWith('id'))
        || voices.find(v => v.name.toLowerCase().includes('indonesia'))
        || null;
}

function toggleVoice() {
    if (isReading) {
        synth.cancel();
        isReading = false;
        updateVoiceBtn(false);
        return;
    }
    if (!synth) { alert('Browser tidak mendukung Text-to-Speech.'); return; }
    const text = getReadableText();
    utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'id-ID';
    utterance.rate = 0.92;
    utterance.pitch = 1;

    function speak() {
        const v = getIdVoice();
        if (v) utterance.voice = v;
        utterance.onend = () => { isReading = false; updateVoiceBtn(false); };
        utterance.onerror = () => { isReading = false; updateVoiceBtn(false); };
        synth.speak(utterance);
        isReading = true;
        updateVoiceBtn(true);
    }

    synth.getVoices().length === 0 ? (synth.onvoiceschanged = speak) : speak();
}

function updateVoiceBtn(reading) {
    document.getElementById('voice-icon-play').classList.toggle('hidden', reading);
    document.getElementById('voice-icon-stop').classList.toggle('hidden', !reading);
    document.getElementById('voice-label').textContent = reading ? 'Stop' : 'Dengarkan';
}

window.addEventListener('beforeunload', () => { if (synth) synth.cancel(); });
</script>
@endpush
