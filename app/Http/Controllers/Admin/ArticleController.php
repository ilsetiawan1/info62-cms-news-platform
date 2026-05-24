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
        $query = Article::with(['category', 'author']);

        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'archived'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $articles = $query->latest('updated_at')->paginate(10)->withQueryString();

        return view('admin.articles.index', compact('articles'));
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
            'status'           => ['required', Rule::in(['draft', 'published', 'archived'])],
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
            $validated['published_at'] = $validated['published_at'] ?: now();
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
            'status'           => ['required', Rule::in(['draft', 'published', 'archived'])],
            'cover_image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_image_url'  => ['nullable', 'url'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'keywords'         => ['nullable', 'string'],
            'source_url'       => ['nullable', 'url'],
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

        if ($validated['status'] === 'published' && ! $article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────
    public function destroy(Article $article)
    {
        if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
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