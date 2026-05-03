@extends('layouts.admin')

@section('header', 'Edit Artikel')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-gray-50">Edit Artikel</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400">Perbarui informasi, konten, atau status publikasi.</p>
        </div>
        <div>
            <a href="{{ route('articles.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-6">
        @csrf
        @method('PUT')

        <!-- Main Content Column (Left) -->
        <div class="flex-1 space-y-6">
            <!-- Content Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Judul Artikel</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required placeholder="Masukkan judul artikel yang menarik..."
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-3 text-lg font-semibold placeholder:font-normal">
                    @error('title')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-6 bg-slate-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                    <label for="slug" class="block text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Permalink (URL)</label>
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 sm:text-sm">
                            {{ url('/artikel') }}/
                        </span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug) }}" placeholder="terisi-otomatis"
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-xl focus:ring-primary focus:border-primary border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 sm:text-sm font-mono">
                    </div>
                    @error('slug')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div class="mb-6">
                    <label for="excerpt" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Kutipan / Ringkasan (Excerpt) <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <textarea id="excerpt" name="excerpt" rows="2" placeholder="Tuliskan ringkasan singkat artikel ini..."
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-2.5 resize-none">{{ old('excerpt', $article->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Isi Artikel</label>
                    <textarea id="content" name="content" rows="15" required placeholder="Tulis artikel Anda di sini..."
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary dark:focus:border-primary-500 focus:ring-primary dark:focus:ring-primary-500 shadow-sm transition-colors duration-200 px-4 py-3 font-sans leading-relaxed">{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Settings Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-gray-50 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                    Pengaturan SEO
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}" placeholder="Judul khusus untuk mesin pencari"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary focus:ring-primary text-sm px-3 py-2">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="2" placeholder="Deskripsi untuk hasil pencarian"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary focus:ring-primary text-sm px-3 py-2 resize-none">{{ old('meta_description', $article->meta_description) }}</textarea>
                    </div>
                    <div>
                        <label for="keywords" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Keywords</label>
                        <input type="text" id="keywords" name="keywords" value="{{ old('keywords', $article->keywords) }}" placeholder="berita, politik, nasional"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary focus:ring-primary text-sm px-3 py-2">
                    </div>
                </div>
            </div>

        <!-- Sidebar Column (Right) -->
        <div class="w-full lg:w-80 flex-shrink-0 space-y-6">
            
            <!-- Category + Subcategory Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6"
                 x-data="{
                    // Initialize with article's category. If it has a parent, use parent_id. If no parent, use its id.
                    initialCategoryId: '{{ old('category_id', $article->category_id) }}',
                    categories: {{ Js::from($categories->where('parent_id', null)) }},
                    allCategories: {{ Js::from($categories) }},
                    
                    parentId: '',
                    
                    init() {
                        const currentCat = this.allCategories.find(c => String(c.id) === String(this.initialCategoryId));
                        if (currentCat) {
                            if (currentCat.parent_id) {
                                this.parentId = String(currentCat.parent_id);
                            } else {
                                this.parentId = String(currentCat.id);
                            }
                        }
                    },
                    
                    get subcategories() {
                        if (!this.parentId) return [];
                        return this.allCategories.filter(c => String(c.parent_id) === String(this.parentId));
                    }
                 }">
                <h3 class="text-base font-bold text-slate-900 dark:text-gray-50 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Kategori
                </h3>
                
                {{-- Parent category --}}
                <label class="block text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kategori Utama</label>
                <select x-model="parentId"
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary focus:ring-primary px-4 py-2.5 mb-3">
                    <option value="">-- Pilih Kategori Utama --</option>
                    @foreach($categories->where('parent_id', null) as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>

                {{-- Subcategory (shown when parent has children) --}}
                <div x-show="subcategories.length > 0" style="display:none">
                    <label class="block text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Sub Kategori</label>
                    <select id="category_id" name="category_id" required
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary focus:ring-primary px-4 py-2.5">
                        <option value="">-- Pilih Sub Kategori --</option>
                        <template x-for="sub in subcategories" :key="sub.id">
                            <option :value="sub.id" x-text="sub.name"
                                :selected="String(sub.id) === String(initialCategoryId)"></option>
                        </template>
                    </select>
                </div>

                {{-- If parent has no children, parent itself is the category --}}
                <div x-show="subcategories.length === 0 && parentId">
                    <input type="hidden" id="category_id" name="category_id" :value="parentId">
                    <p class="text-xs text-slate-400 dark:text-gray-500 mt-1">Kategori ini tidak memiliki sub kategori.</p>
                </div>

                @error('category_id')
                    <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cover Image Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-gray-50 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Cover Image
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-center w-full {{ $article->cover_image ? 'hidden' : '' }}" id="image-preview-container">
                        <label for="cover_image" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span> atau drag</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, WEBP (Maks. 2MB)</p>
                            </div>
                            <input id="cover_image" name="cover_image" type="file" class="hidden" accept="image/*" onchange="previewImage(event)"/>
                        </label>
                    </div>
                    <!-- Image Preview -->
                    <div id="image-preview-wrapper" class="{{ $article->cover_image ? '' : 'hidden' }} relative w-full h-48 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                        <img id="image-preview" src="{{ $article->cover_image ? $article->cover_image_url : '#' }}" alt="Preview" class="w-full h-full object-cover">
                        <button type="button" onclick="removeImage()" class="absolute top-2 right-2 bg-black/50 hover:bg-black/70 text-white rounded-full p-1.5 transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    @error('cover_image')
                        <p class="mt-2 text-sm text-accent dark:text-accent-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Source URL Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-gray-50 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    Sumber Referensi
                </h3>
                
                <div>
                    <label for="source_url" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">URL Sumber <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="url" id="source_url" name="source_url" value="{{ old('source_url', $article->source_url) }}" placeholder="https://..."
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary focus:ring-primary text-sm px-3 py-2">
                </div>
            </div>

            <!-- Publish Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-gray-50 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Publikasi
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1.5">Status</label>
                        <select id="status" name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 text-slate-900 dark:text-gray-50 focus:border-primary focus:ring-primary font-medium px-4 py-2.5">
                            <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published (Terbit)</option>
                            <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>Archived (Arsip)</option>
                        </select>
                    </div>
                    
                    @if($article->published_at)
                    <div class="text-xs text-slate-500 dark:text-gray-400 flex items-center bg-slate-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Dipublikasikan: <br> {{ $article->published_at->format('d M Y, H:i') }}
                    </div>
                    @endif

                    <div class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary hover:bg-primary/90 dark:bg-primary-500 dark:hover:bg-primary-500/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                            Perbarui Artikel
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        // Auto-generate Slug logic
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            let isUserEditedSlug = false;

            slugInput.addEventListener('input', function() {
                isUserEditedSlug = true;
            });

            titleInput.addEventListener('input', function() {
                if (!isUserEditedSlug) {
                    let slug = titleInput.value
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .trim()
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    
                    slugInput.value = slug;
                }
            });
        });

        // Image Preview Logic
        function previewImage(event) {
            const input = event.target;
            const container = document.getElementById('image-preview-container');
            const wrapper = document.getElementById('image-preview-wrapper');
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.add('hidden');
                    wrapper.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const input = document.getElementById('cover_image');
            const container = document.getElementById('image-preview-container');
            const wrapper = document.getElementById('image-preview-wrapper');
            const preview = document.getElementById('image-preview');

            input.value = "";
            preview.src = "#";
            wrapper.classList.add('hidden');
            container.classList.remove('hidden');
        }
    </script>
@endsection
