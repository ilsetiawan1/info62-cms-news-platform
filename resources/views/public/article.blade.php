@extends('layouts.public')

@section('meta_title', ($article->meta_title ?: $article->title) . ' — Info Seputar +62')
@section('meta_description', $article->meta_description ?: $article->excerpt)
@section('meta_keywords', $article->keywords)
@section('canonical', route('article.show', $article->slug))
@section('og_title', $article->meta_title ?: $article->title)
@section('og_description', $article->meta_description ?: $article->excerpt)
@section('og_image', $article->cover_image ? $article->cover_image_url : asset('images/og-default.png'))
@section('og_type', 'article')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    <div class="grid grid-cols-12 gap-6">

        {{-- ===== SIDEBAR KIRI (3 cols, desktop only) ===== --}}
        <aside class="hidden lg:block lg:col-span-3">
            <div class="sticky top-24 space-y-6">

                {{-- Author info --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Penulis</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center font-black text-blue-600 dark:text-blue-400 text-lg flex-shrink-0">
                            {{ substr($article->author->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $article->author->name }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Jurnalis</p>
                        </div>
                    </div>
                </div>

                {{-- Categories nav --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4">
                    <h3 class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Kategori</h3>
                    <ul class="space-y-1">
                        @foreach($navCategories->take(7) as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}"
                               class="block px-3 py-2 rounded-xl text-[13px] font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-sky-400 transition-colors {{ $cat->id === $article->category->id ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-sky-400 font-semibold' : '' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </aside>

        {{-- ===== ARTICLE MAIN (6 cols) ===== --}}
        <main class="col-span-12 md:col-span-8 lg:col-span-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-5">
                <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('category.show', $article->category->slug) }}" class="text-blue-600 dark:text-sky-400 hover:opacity-75 transition-opacity">{{ $article->category->name }}</a>
            </nav>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white leading-[1.25] tracking-tight mb-5">
                {{ $article->title }}
            </h1>

            {{-- Meta Row --}}
            <div class="flex items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center font-bold text-blue-600 dark:text-blue-400 flex-shrink-0">
                        {{ substr($article->author->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $article->author->name }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $article->published_at?->isoFormat('D MMMM YYYY, HH:mm') }} WIB · {{ number_format($article->views_count) }} dibaca</p>
                    </div>
                </div>

                {{-- Voice Reader --}}
                <button id="voice-btn" onclick="toggleVoice()"
                    class="flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:border-blue-400 hover:text-blue-600 dark:hover:text-sky-400 transition-all shadow-sm active:scale-95 flex-shrink-0">
                    <div class="w-5 h-5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg id="voice-icon-play" class="w-2.5 h-2.5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        <svg id="voice-icon-stop" class="w-2.5 h-2.5 hidden" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h12v12H6z"/></svg>
                    </div>
                    <span id="voice-label">Dengarkan</span>
                </button>
            </div>

            {{-- Cover Image --}}
            @if($article->cover_image)
            <div class="relative w-full aspect-[16/9] rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 mb-7">
                <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                <span class="absolute bottom-3 right-3 px-2.5 py-1 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold rounded-full">Foto: Info Seputar +62</span>
            </div>
            @endif

            {{-- Share buttons --}}
            <div class="flex items-center gap-2 mb-7" x-data="{ copied: false }">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mr-1">Bagikan</span>
                <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('article.show', $article->slug)) }}" target="_blank"
                   class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-green-500 hover:bg-green-500 hover:text-white hover:border-green-500 transition-all">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('article.show', $article->slug)) }}" target="_blank"
                   class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-700 dark:text-slate-300 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <button @click="navigator.clipboard.writeText('{{ route('article.show', $article->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <svg x-show="copied" style="display:none" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>

            {{-- Excerpt --}}
            @if($article->excerpt)
            <div class="mb-7 pl-4 border-l-4 border-blue-500 text-base font-medium text-slate-600 dark:text-slate-400 leading-relaxed italic">
                {{ $article->excerpt }}
            </div>
            @endif

            {{-- Article Body --}}
            <div id="article-text" class="article-content mb-10">
                {!! $article->content !!}
            </div>

            {{-- Mid-article Ad --}}
            @if($ads_article_mid)
            <div class="flex justify-center my-8">
                <a href="{{ $ads_article_mid->url ?? '#' }}" target="_blank" rel="noopener">
                    <img src="{{ $ads_article_mid->image_url }}" alt="{{ $ads_article_mid->title }}" class="max-w-full rounded-xl border border-slate-200 dark:border-slate-700">
                </a>
            </div>
            @endif

            {{-- Source --}}
            @if($article->source_url)
            <div class="mb-8 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-500 dark:text-slate-400">
                Sumber: <a href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-sky-400 hover:underline break-all">{{ $article->source_url }}</a>
            </div>
            @endif

            {{-- Related articles --}}
            @if($relatedSameCategory->isNotEmpty())
            <div class="border-t border-slate-200 dark:border-slate-700 pt-7">
                <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-5">Baca Juga</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($relatedSameCategory as $rel)
                    <a href="{{ route('article.show', $rel->slug) }}" class="group flex gap-3">
                        <div class="w-20 h-[60px] flex-shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            @if($rel->cover_image)
                            <img src="{{ $rel->cover_image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @endif
                        </div>
                        <h3 class="text-[13px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.4] line-clamp-3 group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">{{ $rel->title }}</h3>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </main>

        {{-- ===== SIDEBAR KANAN (3 cols) ===== --}}
        <aside class="col-span-12 md:col-span-4 lg:col-span-3">
            <div class="md:sticky md:top-24 space-y-6">

                {{-- Terpopuler --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                        <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Terpopuler</h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach(App\Models\Article::where('status','published')->orderByDesc('views_count')->limit(6)->get() as $i => $pop)
                        <a href="{{ route('article.show', $pop->slug) }}"
                           class="group flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <span class="text-lg font-black leading-none flex-shrink-0 w-5 text-center mt-0.5 {{ $i === 0 ? 'text-red-500' : 'text-slate-200 dark:text-slate-700' }}">{{ $i+1 }}</span>
                            <p class="text-[12px] font-semibold text-slate-800 dark:text-slate-200 leading-[1.4] line-clamp-3 group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">{{ $pop->title }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Sidebar Ad --}}
                <div class="ad-placeholder h-[250px] w-full">
                    @if($ads_sidebar_top)
                    <a href="{{ $ads_sidebar_top->url ?? '#' }}" target="_blank" rel="noopener" class="w-full h-full flex items-center justify-center">
                        <img src="{{ $ads_sidebar_top->image_url }}" alt="{{ $ads_sidebar_top->title }}" class="max-h-full object-contain rounded-xl">
                    </a>
                    @else
                    <span class="opacity-40 text-[11px]">Iklan 300×250</span>
                    @endif
                </div>

            </div>
        </aside>

    </div>
</div>

@push('scripts')
<script>
const synth = window.speechSynthesis;
let utterance = null;
let isReading = false;

function getReadableText() {
    const title = document.querySelector('h1')?.innerText || '';
    const body  = document.getElementById('article-text')?.innerText || '';
    return title + '. ' + body;
}

function getIdVoice() {
    const voices = synth.getVoices();
    return voices.find(v => v.name === 'Google Bahasa Indonesia')
        || voices.find(v => /Andika|Gadis|Ardi/i.test(v.name))
        || voices.find(v => /id[-_]ID/i.test(v.lang))
        || voices[0];
}

if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = () => synth.getVoices();
}

function toggleVoice() {
    if (isReading) { synth.cancel(); isReading = false; updateVoiceBtn(false); return; }
    const text = getReadableText();
    utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'id-ID';
    utterance.rate = 0.95;
    utterance.onend = () => { isReading = false; updateVoiceBtn(false); };

    const speak = () => {
        const v = getIdVoice(); if (v) utterance.voice = v;
        synth.speak(utterance); isReading = true; updateVoiceBtn(true);
    };

    synth.getVoices().length === 0
        ? setTimeout(speak, 200)
        : speak();
}

function updateVoiceBtn(reading) {
    document.getElementById('voice-icon-play').classList.toggle('hidden', reading);
    document.getElementById('voice-icon-stop').classList.toggle('hidden', !reading);
    document.getElementById('voice-label').textContent = reading ? 'Stop' : 'Dengarkan';
}
window.addEventListener('beforeunload', () => synth?.cancel());
</script>
@endpush
@endsection
