@extends('layouts.admin')

@section('header', 'Tulis Artikel')

@section('content')
<style>
    .ck-editor__editable_inline {
        min-height: 320px;
    }
    .ck.ck-editor {
        width: 100% !important;
    }
    /* Batasan maksimal lebar pada .ck-content .image dan .ck-content .ck-media__wrapper sebesar 100% agar tidak overflow */
    .ck-content .image,
    .ck-content .ck-media__wrapper {
        max-width: 100% !important;
    }
    /* Rule CSS agar secara default video embed (iframe) memiliki max-width 700px dan terpusat (margin: auto) */
    .ck-content .ck-media__wrapper iframe {
        max-width: 700px !important;
        width: 100% !important;
        margin: 0 auto !important;
        display: block !important;
    }

    /* ==================================================================
       CSS ANTI-SCROLL: MEMBUAT MEDIA MINI KHUSUS DI SISI EDITOR ADMIN 
       ================================================================== */

    /* 1. Otomatis kecilkan kontainer VIDEO YouTube di dalam area edit admin */
    .ck-editor__editable .ck-media__wrapper {
        max-width: 450px !important; /* Dikunci mini agar admin nyaman melihat form lain */
        margin: 15px 0 !important;   /* Rata kiri serasi dengan teks, beri jarak atas bawah */
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    /* Memastikan iframe video di dalam kontainer mini ikut presisi 100% dari 450px */
    .ck-editor__editable .ck-media__wrapper iframe {
        width: 100% !important;
        height: auto !important;
        aspect-ratio: 16 / 9;
    }

    /* 2. Otomatis kecilkan FOTO yang di-paste/diupload di dalam area edit admin */
    .ck-editor__editable .image img {
        max-width: 350px !important; /* Foto otomatis dibuat berukuran mini di admin */
        height: auto !important;
        margin: 10px 0 !important;   /* Rata kiri bersama teks */
        border-radius: 6px;
    }

    /* Mengatur agar block widget pembungkus foto dari CKEditor tidak memakan space lebar */
    .ck-editor__editable .ck-widget.image {
        max-width: 350px !important;
    }
</style>

<div x-data="{
    scrapingMode: false,
    fetchUrl: '',
    loading: false,
    fetchError: '',
    fetchSuccess: false,
    formFieldIds: ['title','slug','excerpt','content','meta_title','meta_description','keywords','source_url','category_id','status','cover_image','published_at','cover_image_alt'],

    toggleScraping() {
        this.scrapingMode = !this.scrapingMode;
        this.setFormDisabled(this.scrapingMode);
        if (!this.scrapingMode) {
            this.fetchUrl = '';
            this.fetchError = '';
            this.fetchSuccess = false;
        }
    },

    setFormDisabled(disabled) {
        this.formFieldIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.disabled = disabled;
                el.style.opacity = disabled ? '0.5' : '1';
            }
        });
    },

    async fetchArticle() {
        if (!this.fetchUrl) return;

        this.loading      = true;
        this.fetchError   = '';
        this.fetchSuccess = false;

        try {
            const res = await fetch('{{ route('articles.fetch') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ url: this.fetchUrl })
            });

            const contentType = res.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                const text = await res.text();
                console.error('Non-JSON response from server:', text.substring(0, 500));
                this.fetchError = `Server error (HTTP ${res.status}). `
                    + 'Kemungkinan Guzzle belum terinstall. '
                    + 'Jalankan: composer require guzzlehttp/guzzle';
                return;
            }

            const data = await res.json();

            if (!res.ok || data.error) {
                this.fetchError = data.error || `Gagal (HTTP ${res.status}).`;
                return;
            }

            if (data.warning) {
                this.fetchError = '⚠️ ' + data.warning;
            }

            // ── Isi form fields ───────────────────────────────────────────
            const set = (id, val) => {
                if (id === 'content' && window.editorInstance) {
                    window.editorInstance.setData(val || '');
                } else {
                    const el = document.getElementById(id);
                    if (el && val != null && val !== '') el.value = val;
                }
            };

            set('title',            data.title);
            set('content',          data.content);
            set('excerpt',          data.excerpt);
            set('source_url',       data.source_url);
            set('meta_title',       data.meta_title || data.title);
            set('meta_description', data.meta_description || data.excerpt);
            set('keywords',         data.keywords);
            set('cover_image_url',  data.cover_image_url);

            if (data.cover_image_url) {
                const preview = document.getElementById('image-preview');
                const container = document.getElementById('image-preview-container');
                const wrapper = document.getElementById('image-preview-wrapper');
                if (preview && container && wrapper) {
                    preview.src = data.cover_image_url;
                    container.classList.add('hidden');
                    wrapper.classList.remove('hidden');
                }
            }

            // Trigger slug auto-generate dari title
            const titleEl = document.getElementById('title');
            if (titleEl) titleEl.dispatchEvent(new Event('input'));

            // Unlock form
            this.scrapingMode = false;
            this.setFormDisabled(false);
            this.fetchSuccess = !data.warning;

        } catch (networkErr) {
            console.error('Fetch network error:', networkErr);
            this.fetchError = 'Koneksi gagal. Pastikan server Laravel berjalan dan coba lagi.';
        } finally {
            this.loading = false;
        }
    },

    importXml(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const text = e.target.result;
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(text, 'text/xml');
                
                const parserError = xmlDoc.getElementsByTagName('parsererror');
                if (parserError.length > 0) {
                    alert('Format file XML tidak valid atau rusak.');
                    return;
                }

                // Cari elemen item (RSS) atau entry (Atom) atau root
                let itemNode = xmlDoc.getElementsByTagName('item')[0] 
                            || xmlDoc.getElementsByTagName('entry')[0] 
                            || xmlDoc.documentElement;

                if (!itemNode) {
                    alert('Tidak dapat menemukan artikel/item berita di dalam XML.');
                    return;
                }

                const getTagValue = (node, tagName) => {
                    let elements = node.getElementsByTagName(tagName);
                    if (elements.length === 0 && tagName.includes(':')) {
                        const localName = tagName.split(':')[1];
                        elements = node.getElementsByTagName(localName);
                    }
                    if (elements.length > 0) {
                        return elements[0].textContent || '';
                    }
                    return '';
                };

                const title = getTagValue(itemNode, 'title');
                const excerpt = getTagValue(itemNode, 'description') || getTagValue(itemNode, 'summary') || getTagValue(itemNode, 'excerpt') || '';
                
                let content = getTagValue(itemNode, 'content:encoded') 
                           || getTagValue(itemNode, 'content') 
                           || getTagValue(itemNode, 'description') 
                           || '';

                const sourceUrl = getTagValue(itemNode, 'link') 
                               || getTagValue(itemNode, 'guid') 
                               || '';

                const metaTitle = getTagValue(itemNode, 'meta_title') || title;
                const metaDescription = getTagValue(itemNode, 'meta_description') || (excerpt.substring(0, 160));
                const keywords = getTagValue(itemNode, 'keywords') || '';

                const titleEl = document.getElementById('title');
                if (titleEl) {
                    titleEl.value = title;
                    titleEl.dispatchEvent(new Event('input'));
                }

                const excerptEl = document.getElementById('excerpt');
                if (excerptEl) excerptEl.value = excerpt;

                const sourceUrlEl = document.getElementById('source_url');
                if (sourceUrlEl) sourceUrlEl.value = sourceUrl;

                const metaTitleEl = document.getElementById('meta_title');
                if (metaTitleEl) metaTitleEl.value = metaTitle;

                const metaDescEl = document.getElementById('meta_description');
                if (metaDescEl) metaDescEl.value = metaDescription;

                const keywordsEl = document.getElementById('keywords');
                if (keywordsEl) keywordsEl.value = keywords;

                if (window.editorInstance) {
                    window.editorInstance.setData(content);
                } else {
                    const contentEl = document.getElementById('content');
                    if (contentEl) contentEl.value = content;
                }

                alert('Berhasil mengimpor data dari file XML!');

            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat menguraikan XML: ' + err.message);
            } finally {
                event.target.value = '';
            }
        };
        reader.readAsText(file);
    }
}">

    {{-- PAGE HEADER --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tulis Artikel Baru</h2>
            <p class="text-sm text-slate-500">Publikasikan informasi, berita, atau cerita Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" @click="toggleScraping()"
                :class="scrapingMode
                    ? 'bg-primary text-white border-primary shadow-md'
                    : 'bg-white text-slate-700 border-gray-200 hover:bg-slate-50'"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-semibold transition-all duration-200 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                <span x-text="scrapingMode ? '✓ Mode Scraping Aktif' : 'Scraping Article'"></span>
            </button>

            <!-- Import XML Button -->
            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-semibold bg-white text-slate-700 border-gray-200 hover:bg-slate-50 transition-all duration-200 focus:outline-none">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Import XML</span>
                <input type="file" accept=".xml" class="hidden" @change="importXml($event)">
            </label>

            <a href="{{ route('articles.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- AUTO-FETCH PANEL --}}
    <div x-show="scrapingMode"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         style="display:none"
         class="mb-6 bg-gradient-to-r from-primary/5 to-blue-50 border border-primary/30 rounded-2xl p-6">

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-slate-800 mb-0.5">Auto-Fetch dari URL</p>
                <p class="text-xs text-slate-500">Tempel URL berita, semua form akan terisi otomatis. Anda tetap bisa mengedit setelah berhasil.</p>
            </div>
            <div class="flex w-full sm:w-auto items-center gap-2">
                <input type="url"
                       x-model="fetchUrl"
                       placeholder="https://kompas.com/berita/..."
                       @keydown.enter.prevent="fetchArticle()"
                       class="flex-1 sm:w-72 rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary text-sm px-3 py-2.5 placeholder:text-slate-400">
                <button type="button"
                        @click="fetchArticle()"
                        :disabled="loading || !fetchUrl"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-all whitespace-nowrap">
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span x-text="loading ? 'Mengambil...' : 'Ambil Konten'"></span>
                </button>
            </div>
        </div>

        {{-- Error message --}}
        <div x-show="fetchError" style="display:none" class="mt-4">
            <div class="flex items-start gap-2 p-3 rounded-xl bg-red-50 border border-red-200">
                <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <p x-text="fetchError" class="text-sm text-red-600 font-medium"></p>
            </div>
        </div>

        {{-- Success message --}}
        <div x-show="fetchSuccess" style="display:none" class="mt-4">
            <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-emerald-600 font-medium">
                    Berhasil! Form telah terisi. Silakan periksa dan edit sesuai kebutuhan.
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data"
          class="flex flex-col lg:flex-row gap-6">
        @csrf

        {{-- MAIN CONTENT (LEFT) --}}
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6 sm:p-8">

                {{-- Title --}}
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Judul Artikel
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                        placeholder="Masukkan judul artikel yang menarik..."
                        class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary shadow-sm transition-colors px-4 py-3 text-lg font-semibold placeholder:font-normal">
                    @error('title')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Slug --}}
                <div class="mb-6 bg-slate-50 p-4 rounded-xl border border-gray-100">
                    <label for="slug" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Permalink (URL)
                    </label>
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-200 bg-gray-50 text-gray-500 sm:text-sm">
                            {{ url('/artikel') }}/
                        </span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="terisi-otomatis"
                            class="flex-1 px-3 py-2 rounded-none rounded-r-xl border border-gray-200 bg-white text-slate-900 focus:ring-primary focus:border-primary text-sm font-mono">
                    </div>
                    @error('slug')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Excerpt --}}
                <div class="mb-6">
                    <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Kutipan / Ringkasan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea id="excerpt" name="excerpt" rows="2"
                        placeholder="Tuliskan ringkasan singkat artikel ini..."
                        class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary shadow-sm px-4 py-2.5 resize-none">{{ old('excerpt') }}</textarea>
                    @error('excerpt')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Content --}}
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Isi Artikel
                    </label>
                    <textarea id="content" name="content" rows="15" required
                        placeholder="Tulis artikel Anda di sini..."
                        class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary shadow-sm px-4 py-3 leading-relaxed">{{ old('content') }}</textarea>
                    @error('content')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- SEO Card --}}
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Pengaturan SEO
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-1">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                            placeholder="Judul khusus untuk mesin pencari (max 60 karakter)"
                            class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary text-sm px-3 py-2">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-1">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="2"
                            placeholder="Deskripsi untuk hasil pencarian (max 160 karakter)"
                            class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary text-sm px-3 py-2 resize-none">{{ old('meta_description') }}</textarea>
                    </div>
                    <div>
                        <label for="keywords" class="block text-sm font-medium text-slate-700 mb-1">Keywords</label>
                        <input type="text" id="keywords" name="keywords" value="{{ old('keywords') }}"
                            placeholder="berita, politik, nasional"
                            class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary text-sm px-3 py-2">
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR (RIGHT) --}}
        <div class="w-full lg:w-80 flex-shrink-0 space-y-6">

            {{-- Category + Subcategory --}}
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6"
                 x-data="{
                    initialCategoryId: '{{ old('category_id') }}',
                    categories: {{ Js::from($categories) }},
                    parentId: '',
                    
                    init() {
                        if (this.initialCategoryId) {
                            if (this.categories.some(c => String(c.id) === String(this.initialCategoryId))) {
                                this.parentId = String(this.initialCategoryId);
                            } else {
                                const parent = this.categories.find(c => c.children && c.children.some(child => String(child.id) === String(this.initialCategoryId)));
                                if (parent) {
                                    this.parentId = String(parent.id);
                                }
                            }
                        }
                    },
                    
                    get subcategories() {
                        if (!this.parentId) return [];
                        const parent = this.categories.find(c => String(c.id) === String(this.parentId));
                        return parent ? parent.children : [];
                    }
                 }">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Kategori
                </h3>

                {{-- Parent category --}}
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Utama</label>
                <select x-model="parentId"
                    class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary px-4 py-2.5 mb-3">
                    <option value="">-- Pilih Kategori Utama --</option>
                    @foreach($categories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>

                {{-- Subcategory (shown when parent has children) --}}
                <div x-show="subcategories.length > 0" style="display:none">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Sub Kategori</label>
                    <select id="category_id" name="category_id" required
                        class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary px-4 py-2.5">
                        <option value="">-- Pilih Sub Kategori --</option>
                        <template x-for="sub in subcategories" :key="sub.id">
                            <option :value="sub.id" x-text="sub.name"
                                :selected="String(sub.id) === '{{ old('category_id') }}'"></option>
                        </template>
                    </select>
                </div>

                {{-- If parent has no children, parent itself is the category --}}
                <div x-show="subcategories.length === 0 && parentId">
                    <input type="hidden" id="category_id" name="category_id" :value="parentId">
                    <p class="text-xs text-slate-400 mt-1">Kategori ini tidak memiliki sub kategori.</p>
                </div>

                @error('category_id')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Cover Image --}}
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Cover Image
                </h3>
                
                <input type="hidden" id="cover_image_url" name="cover_image_url" value="{{ old('cover_image_url') }}">
                
                <div id="image-preview-container" class="flex items-center justify-center w-full">
                    <label for="cover_image"
                        class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm text-gray-500">
                            <span class="font-semibold">Klik upload</span> atau drag & drop
                        </p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP (maks 2MB)</p>
                        <input id="cover_image" name="cover_image" type="file" class="hidden"
                                accept="image/*" onchange="previewImage(event)"/>
                    </label>
                </div>
                <div id="image-preview-wrapper" class="hidden relative w-full h-44 rounded-xl overflow-hidden border border-gray-200">
                    <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                    <button type="button" onclick="removeImage()"
                        class="absolute top-2 right-2 bg-black/50 hover:bg-black/70 text-white rounded-full p-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @error('cover_image')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror

                {{-- Alt Teks Foto Cover --}}
                <div class="mt-4">
                    <label for="cover_image_alt" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Alt Teks Foto Cover <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" id="cover_image_alt" name="cover_image_alt" value="{{ old('cover_image_alt') }}"
                        placeholder="Deskripsi singkat gambar untuk SEO..."
                        class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary text-sm px-3 py-2">
                    @error('cover_image_alt')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Source URL --}}
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Sumber Referensi
                </h3>
                <label for="source_url" class="block text-sm font-medium text-slate-700 mb-1.5">
                    URL Sumber <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <input type="url" id="source_url" name="source_url" value="{{ old('source_url') }}"
                    placeholder="https://..."
                    class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary text-sm px-3 py-2">
            </div>

            {{-- Publikasi --}}
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.02)] border border-gray-100 p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Publikasi
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Status
                        </label>
                        <select id="status" name="status"
                            class="w-full rounded-xl border-gray-200 bg-slate-50 text-slate-900 focus:border-primary focus:ring-primary font-medium px-4 py-2.5">
                            <option value="draft"     {{ old('status','draft') == 'draft'     ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status')         == 'published' ? 'selected' : '' }}>Published (Terbit)</option>
                            <option value="archived"  {{ old('status')         == 'archived'  ? 'selected' : '' }}>Archived (Arsip)</option>
                        </select>
                    </div>

                    {{-- Jadwal Publikasi --}}
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Jadwal Publikasi <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}"
                            class="w-full rounded-xl border-gray-200 bg-white text-slate-900 focus:border-primary focus:ring-primary text-sm px-3 py-2">
                        @error('published_at')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Artikel
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

<script>
// Auto-generate Slug dari Title
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('title');
    const slugInput  = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function () {
            slugInput.value = titleInput.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .trim()
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    }

    // Initialize CKEditor 5
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                'insertTable', 'mediaEmbed', 'imageUpload', '|',
                'imageTextAlternative', 'toggleImageCaption', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                'undo', 'redo'
            ],
            image: {
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        label: 'Original',
                        value: null
                    },
                    {
                        name: 'resizeImage:25',
                        label: '25%',
                        value: '25'
                    },
                    {
                        name: 'resizeImage:50',
                        label: '50%',
                        value: '50'
                    },
                    {
                        name: 'resizeImage:75',
                        label: '75%',
                        value: '75'
                    }
                ],
                toolbar: [
                    'imageStyle:inline', 'imageStyle:block', 'imageStyle:side',
                    '|',
                    'toggleImageCaption', 'imageTextAlternative',
                    '|',
                    'resizeImage:25', 'resizeImage:50', 'resizeImage:75', 'resizeImage:original'
                ]
            },
            mediaEmbed: {
                previewsInData: true
            }
        })
        .then(editor => {
            window.editorInstance = editor;
            
            // Sync content on change
            editor.model.document.on('change:data', () => {
                document.querySelector('#content').value = editor.getData();
            });
        })
        .catch(error => {
            console.error('CKEditor error:', error);
        });
});

function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('image-preview-wrapper').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('cover_image').value = '';
    const coverImageUrl = document.getElementById('cover_image_url');
    if (coverImageUrl) coverImageUrl.value = '';
    document.getElementById('image-preview').src = '#';
    document.getElementById('image-preview-wrapper').classList.add('hidden');
    document.getElementById('image-preview-container').classList.remove('hidden');
}
</script>
@endsection