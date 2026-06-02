<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleFetcherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $sortBy = $request->input('sort', 'date');

        if ($status === 'trash') {
            $query = Article::onlyTrashed()->with(['category.parent', 'author']);
        } else {
            $query = Article::with(['category.parent', 'author']);
            if (in_array($status, ['draft', 'published', 'scheduled', 'archived'])) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($sortBy === 'title') {
            $query->orderBy('title', 'asc');
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        $articles = $query->paginate(10)->withQueryString();

        $counts = [
            'all'       => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'draft'     => Article::where('status', 'draft')->count(),
            'scheduled' => Article::where('status', 'scheduled')->count(),
            'trash'     => Article::onlyTrashed()->count(),
        ];

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.articles.index', compact('articles', 'status', 'counts', 'sortBy', 'categories'));
    }

    // ─────────────────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────────────────
    public function create()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.articles.create', compact('categories'));
    }

    // ─────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:articles,slug'],
            'excerpt'          => ['nullable', 'string'],
            'content'          => ['required', 'string'],
            'category_id'      => ['required', 'exists:categories,id'],
            'status'           => ['required', Rule::in(['draft', 'published', 'scheduled', 'archived'])],
            'cover_image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_image_alt'  => ['nullable', 'string', 'max:255'],
            'cover_image_url'  => ['nullable', 'url'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'keywords'         => ['nullable', 'string'],
            'source_url'       => ['nullable', 'url'],
            'published_at'     => ['nullable', 'date'],
        ]);

        $validated['slug']      = $this->generateUniqueSlug($validated['title'], $validated['slug'] ?? null);
        $validated['author_id'] = Auth::id();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        } elseif ($request->filled('cover_image_url')) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(15)
                     ->withoutVerifying()
                     ->withHeaders([
                         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                         'Accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                         'Referer' => parse_url($request->cover_image_url, PHP_URL_SCHEME) . '://' . parse_url($request->cover_image_url, PHP_URL_HOST),
                     ])
                     ->get($request->cover_image_url);

                if ($response->successful()) {
                    $pathInfo = pathinfo(parse_url($request->cover_image_url, PHP_URL_PATH));
                    $ext = $pathInfo['extension'] ?? 'jpg';
                    $ext = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? strtolower($ext) : 'jpg';
                    $filename = 'articles/' . \Illuminate\Support\Str::random(40) . '.' . $ext;
                    Storage::disk('public')->put($filename, $response->body());
                    $validated['cover_image'] = $filename;
                } else {
                    Log::warning('Failed to auto-download cover image: HTTP ' . $response->status());
                    $validated['cover_image'] = $request->cover_image_url;
                }
            } catch (\Exception $e) {
                Log::warning('Failed to auto-download cover image: ' . $e->getMessage());
                $validated['cover_image'] = $request->cover_image_url;
            }
        }
        unset($validated['cover_image_url']);

        if ($validated['status'] === 'published') {
            if ($validated['published_at'] && \Carbon\Carbon::parse($validated['published_at'])->isFuture()) {
                $validated['status'] = 'scheduled';
            } else {
                $validated['published_at'] = $validated['published_at'] ?: now();
            }
        } elseif ($validated['status'] === 'scheduled') {
            if (empty($validated['published_at']) || \Carbon\Carbon::parse($validated['published_at'])->isPast()) {
                $validated['status'] = 'published';
                $validated['published_at'] = $validated['published_at'] ?: now();
            }
        }

        Article::create($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────
    public function edit(Article $article)
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    // ─────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', Rule::unique('articles')->ignore($article->id)],
            'excerpt'          => ['nullable', 'string'],
            'content'          => ['required', 'string'],
            'category_id'      => ['required', 'exists:categories,id'],
            'status'           => ['required', Rule::in(['draft', 'published', 'scheduled', 'archived'])],
            'cover_image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_image_alt'  => ['nullable', 'string', 'max:255'],
            'cover_image_url'  => ['nullable', 'url'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'keywords'         => ['nullable', 'string'],
            'source_url'       => ['nullable', 'url'],
            'published_at'     => ['nullable', 'date'],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['title'],
            $validated['slug'] ?? null,
            $article->id
        );

        if ($request->hasFile('cover_image')) {
            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        } elseif ($request->filled('cover_image_url') && empty($article->cover_image)) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(15)
                    ->withoutVerifying()
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                        'Referer' => parse_url($request->cover_image_url, PHP_URL_SCHEME) . '://' . parse_url($request->cover_image_url, PHP_URL_HOST),
                    ])
                    ->get($request->cover_image_url);
                    
                if ($response->successful()) {
                    $pathInfo = pathinfo(parse_url($request->cover_image_url, PHP_URL_PATH));
                    $ext = $pathInfo['extension'] ?? 'jpg';
                    $ext = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? strtolower($ext) : 'jpg';
                    $filename = 'articles/' . \Illuminate\Support\Str::random(40) . '.' . $ext;
                    Storage::disk('public')->put($filename, $response->body());
                    $validated['cover_image'] = $filename;
                } else {
                    Log::warning('Failed to auto-download cover image on update: HTTP ' . $response->status());
                    $validated['cover_image'] = $request->cover_image_url;
                }
            } catch (\Exception $e) {
                Log::warning('Failed to auto-download cover image on update: ' . $e->getMessage());
                $validated['cover_image'] = $request->cover_image_url;
            }
        }
        unset($validated['cover_image_url']);

        if ($validated['status'] === 'published') {
            if ($validated['published_at'] && \Carbon\Carbon::parse($validated['published_at'])->isFuture()) {
                $validated['status'] = 'scheduled';
            } else {
                $validated['published_at'] = $validated['published_at'] ?: ($article->published_at ?: now());
            }
        } elseif ($validated['status'] === 'scheduled') {
            if (empty($validated['published_at']) || \Carbon\Carbon::parse($validated['published_at'])->isPast()) {
                $validated['status'] = 'published';
                $validated['published_at'] = $validated['published_at'] ?: ($article->published_at ?: now());
            }
        }

        $article->update($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil dipindahkan ke Sampah.');
    }

    public function restore($id)
    {
        $article = Article::onlyTrashed()->findOrFail($id);
        $article->restore();

        return redirect()->route('articles.index', ['status' => 'trash'])
            ->with('success', 'Artikel berhasil dikembalikan dari Sampah.');
    }

    public function forceDelete($id)
    {
        $article = Article::onlyTrashed()->findOrFail($id);

        if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->forceDelete();

        return redirect()->route('articles.index', ['status' => 'trash'])
            ->with('success', 'Artikel berhasil dihapus secara permanen.');
    }

    // ─────────────────────────────────────────────────────────
    // FETCH FROM URL (AJAX)
    // POST /seputaradmin/articles/fetch-url → articles.fetch
    //
    // ⚠️ Route ini HARUS didefinisikan SEBELUM Route::resource('articles')
    //    di routes/web.php agar tidak tertangkap sebagai {article} parameter.
    // ─────────────────────────────────────────────────────────
    public function fetchFromUrl(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url', 'max:2048'],
        ]);

        // Pastikan URL bisa diakses (scheme http/https saja)
        $parsed = parse_url($request->url);
        if (! isset($parsed['scheme']) || ! in_array($parsed['scheme'], ['http', 'https'])) {
            return response()->json(['error' => 'URL tidak valid. Hanya http/https yang didukung.'], 422);
        }

        try {
            $fetcher = app(ArticleFetcherService::class);
            $result  = $fetcher->fetch($request->url);

            // Validasi: pastikan ada konten yang berhasil diambil
            if (empty(trim($result['title'])) && empty(strip_tags($result['content']))) {
                return response()->json([
                    'error' => 'Tidak dapat mengekstrak konten dari URL ini. '
                             . 'Pastikan URL mengarah ke halaman artikel, bukan halaman daftar/kategori.',
                ], 422);
            }

            // Jika title kosong tapi konten ada, beri warning tapi tetap return
            if (empty(trim($result['title']))) {
                $result['warning'] = 'Judul tidak dapat diambil otomatis, silakan isi manual.';
            }

            if (empty(strip_tags($result['content']))) {
                $result['warning'] = 'Konten tidak dapat diambil otomatis, silakan isi manual.';
            }

            return response()->json($result);

        } catch (\RuntimeException $e) {
            // Error yang sudah diketahui (timeout, 403, dll)
            return response()->json(['error' => $e->getMessage()], 422);

        } catch (\Throwable $e) {
            // Error tidak terduga — log untuk debugging
            Log::error('ArticleFetcher unexpected error', [
                'url'   => $request->url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan tidak terduga. Coba lagi atau gunakan URL yang berbeda.',
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    // IMPORT XML
    // ─────────────────────────────────────────────────────────
    public function importXml(Request $request)
    {
        set_time_limit(0);

        $request->validate([
            'xml_file' => ['required', 'file', 'max:20480'],
        ]);

        $file = $request->file('xml_file');
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension !== 'xml') {
            return redirect()->back()->with('error', 'File harus berupa dokumen XML (.xml).');
        }

        // Find a default category in case mapping fails (since category_id is NOT NULL in database)
        $defaultCategory = Category::where('slug', 'lainnya')
            ->orWhere('slug', 'uncategorized')
            ->orWhere('name', 'like', '%lainnya%')
            ->orWhere('name', 'like', '%umum%')
            ->first();

        if (!$defaultCategory) {
            $defaultCategory = Category::first();
        }
        $defaultCategoryId = $defaultCategory ? $defaultCategory->id : 1;

        try {
            $xmlContent = file_get_contents($file->getRealPath());

            // Remove default namespace to avoid SimpleXML element matching issues
            $xmlContent = preg_replace('/\sxmlns=["\'][^"\']+["\']/', ' ', $xmlContent, 1);

            $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($xml === false) {
                return redirect()->back()->with('error', 'Gagal memparsing file XML. Pastikan format XML valid.');
            }

            // Find items (support standard RSS feed and Atom structure)
            $items = [];
            if (isset($xml->channel->item)) {
                $items = $xml->channel->item;
            } elseif (isset($xml->item)) {
                $items = $xml->item;
            } elseif (isset($xml->entry)) {
                $items = $xml->entry;
            } else {
                $items = $xml->xpath('//item') ?: ($xml->xpath('//entry') ?: []);
            }

            if (count($items) === 0) {
                return redirect()->back()->with('error', 'Tidak ditemukan elemen <item> atau <entry> dalam file XML.');
            }

            $importedCount = 0;

            foreach ($items as $item) {
                // Check wp:post_type if available (to skip revisions, attachments, pages, menus in WXR)
                $postType = '';
                $namespaces = $item->getNamespaces(true);
                if (isset($namespaces['wp'])) {
                    $wp = $item->children($namespaces['wp']);
                    if (isset($wp->post_type)) {
                        $postType = (string)$wp->post_type;
                    }
                }
                if (empty($postType)) {
                    $wp = $item->children('wp', true);
                    if (isset($wp->post_type)) {
                        $postType = (string)$wp->post_type;
                    }
                }

                // If post type is defined and is not 'post', skip this item
                if (!empty($postType) && $postType !== 'post') {
                    continue;
                }

                // 1. Judul
                $title = isset($item->title) ? trim((string)$item->title) : '';
                if (empty($title)) {
                    continue; // Skip articles without titles
                }

                // 2. Isi Artikel (Content)
                $content = '';
                if (isset($namespaces['content'])) {
                    $content = (string)$item->children($namespaces['content'])->encoded;
                }
                if (empty($content)) {
                    $content = (string)$item->children('content', true)->encoded;
                }
                if (empty($content)) {
                    $content = isset($item->description) ? (string)$item->description : '';
                }
                if (empty($content)) {
                    $content = isset($item->summary) ? (string)$item->summary : '';
                }

                // 3. Kutipan / Ringkasan (Excerpt) - Clean style/script tags first
                $cleanContentForExcerpt = preg_replace('/<(style|script)\b[^>]*>(.*?)<\/\1>/is', '', $content);
                $plainText = strip_tags(html_entity_decode($cleanContentForExcerpt, ENT_QUOTES, 'UTF-8'));
                $plainText = preg_replace('/\s+/', ' ', $plainText);
                $plainText = trim($plainText);
                $excerpt = mb_substr($plainText, 0, 150);

                // 4. Slug / Permalink
                $slug = $this->generateUniqueSlug($title);

                // 5. Meta Title & Description
                $metaTitle = $title;
                $metaDescription = $excerpt;

                // 6. Resolve Category
                $xmlCategoryName = '';
                if (isset($item->category)) {
                    foreach ($item->category as $catNode) {
                        $domain = isset($catNode['domain']) ? (string)$catNode['domain'] : '';
                        if (empty($domain) || $domain === 'category') {
                            $xmlCategoryName = (string)$catNode;
                            break;
                        }
                    }
                }

                $finalCategoryId = null;
                $categoryName = '';
                if (!empty($xmlCategoryName)) {
                    $matchedCategory = Category::where('name', 'like', $xmlCategoryName)
                        ->orWhere('slug', 'like', Str::slug($xmlCategoryName))
                        ->first();
                    if ($matchedCategory) {
                        $finalCategoryId = $matchedCategory->id;
                        $categoryName = $matchedCategory->name;
                    }
                }

                if (!$finalCategoryId) {
                    $finalCategoryId = $defaultCategoryId;
                    $categoryName = $defaultCategory ? $defaultCategory->name : '';
                }

                // 7. Keywords
                $cleanTitleForKeywords = preg_replace('/[^\w\s]/', '', $title);
                $words = array_slice(array_filter(explode(' ', $cleanTitleForKeywords)), 0, 3);
                $firstThreeWords = implode(' ', $words);
                $keywords = trim($categoryName . ($firstThreeWords ? ', ' . $firstThreeWords : ''));

                // 8. Cover Image
                $coverImage = null;
                if (isset($item->enclosure)) {
                    $enclosure = $item->enclosure;
                    if (isset($enclosure['url'])) {
                        $coverImage = (string)$enclosure['url'];
                    }
                }
                if (!$coverImage && isset($namespaces['media'])) {
                    $media = $item->children($namespaces['media']);
                    if (isset($media->content) && isset($media->content['url'])) {
                        $coverImage = (string)$media->content['url'];
                    } elseif (isset($media->thumbnail) && isset($media->thumbnail['url'])) {
                        $coverImage = (string)$media->thumbnail['url'];
                    }
                }
                if (!$coverImage) {
                    $media = $item->children('media', true);
                    if (isset($media->content) && isset($media->content['url'])) {
                        $coverImage = (string)$media->content['url'];
                    } elseif (isset($media->thumbnail) && isset($media->thumbnail['url'])) {
                        $coverImage = (string)$media->thumbnail['url'];
                    }
                }
                if (!$coverImage && !empty($content)) {
                    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches)) {
                        $coverImage = $matches[1];
                    }
                }
                if (!$coverImage) {
                    $coverImage = 'https://picsum.photos/seed/placeholder/800/500';
                }

                // 9. Alt Text
                $coverImageAlt = $title;

                // 10. Source URL
                $sourceUrl = 'Imported XML File';
                if (isset($item->link)) {
                    $sourceUrl = (string)$item->link;
                } elseif (isset($item->guid) && filter_var((string)$item->guid, FILTER_VALIDATE_URL)) {
                    $sourceUrl = (string)$item->guid;
                }

                // Create the article
                Article::create([
                    'title'            => $title,
                    'slug'             => $slug,
                    'excerpt'          => $excerpt,
                    'content'          => $content,
                    'cover_image'      => $coverImage,
                    'cover_image_alt'  => $coverImageAlt,
                    'category_id'      => $finalCategoryId,
                    'author_id'        => Auth::id(),
                    'status'           => 'draft',
                    'published_at'     => null,
                    'meta_title'       => $metaTitle,
                    'meta_description' => $metaDescription,
                    'keywords'         => $keywords,
                    'source_url'       => $sourceUrl,
                    'views_count'      => 0,
                ]);

                $importedCount++;
            }

            return redirect()->route('articles.index')
                ->with('success', "Sukses! Berhasil mengimpor {$importedCount} artikel secara massal ke dalam draf.");

        } catch (\Exception $e) {
            Log::error('Error importing XML: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses file XML: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────

    /**
     * Generate slug unik dari judul.
     * Jika slug kosong → generate dari title.
     * Jika sudah ada di DB → tambahkan suffix angka.
     */
    private function generateUniqueSlug(string $title, ?string $slug = null, ?int $ignoreId = null): string
    {
        $base = $slug ? Str::slug($slug) : Str::slug($title);

        if (empty($base)) {
            $base = 'artikel-' . time();
        }

        $final   = $base;
        $counter = 1;

        while (true) {
            $query = Article::where('slug', $final);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (! $query->exists()) break;
            $final = $base . '-' . $counter++;
        }

        return $final;
    }
}