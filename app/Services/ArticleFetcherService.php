<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use DOMDocument;
use DOMXPath;
use DOMNode;

class ArticleFetcherService
{
    // ──────────────────────────────────────────────────────────
    // CONFIG
    // ──────────────────────────────────────────────────────────

    /** User-Agent yang lebih mirip browser asli */
    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36';

    /** Minimum panjang plain text agar dianggap konten valid */
    private const MIN_CONTENT_LENGTH = 150;

    // ──────────────────────────────────────────────────────────
    // PUBLIC
    // ──────────────────────────────────────────────────────────

    /**
     * Fetch dan parse artikel dari URL eksternal.
     *
     * Strategi:
     * 1. Coba URL asli
     * 2. Jika gagal (Cloudflare/block), coba versi AMP
     * 3. Fallback ke RSS feed (khusus Kompas, Detik, dll)
     *
     * @throws \RuntimeException
     */
    public function fetch(string $url): array
    {
        $html = null;
        $usedUrl = $url;

        // ── Strategi 1: URL asli ──────────────────────────────
        try {
            $html = $this->getHtml($url);
        } catch (\RuntimeException $e) {
            $originalError = $e->getMessage();
        }

        // ── Strategi 2: AMP URL (jika situs news Indonesia) ──
        if (! $html) {
            $ampUrl = $this->toAmpUrl($url);
            if ($ampUrl && $ampUrl !== $url) {
                try {
                    $html = $this->getHtml($ampUrl);
                    $usedUrl = $ampUrl;
                } catch (\RuntimeException) {
                    // AMP juga gagal, lanjut ke fallback berikutnya
                }
            }
        }

        // ── Strategi 3: RSS / sitemap fallback ───────────────
        if (! $html) {
            $rssData = $this->tryRssFallback($url);
            if ($rssData) {
                return $rssData;
            }
        }

        // ── Tidak ada yang berhasil ───────────────────────────
        if (! $html) {
            throw new \RuntimeException(
                isset($originalError)
                    ? $originalError
                    : 'Gagal mengambil konten dari URL ini.'
            );
        }

        return $this->parseHtml($html, $url);
    }

    // ──────────────────────────────────────────────────────────
    // HTTP
    // ──────────────────────────────────────────────────────────

    private function makeClient(array $extra = []): Client
    {
        return new Client(array_merge([
            'timeout'         => 20,
            'connect_timeout' => 10,
            'allow_redirects' => ['max' => 5, 'track_redirects' => true],
            'verify'          => false,
            'headers'         => [
                'User-Agent'      => self::UA,
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                'Accept-Encoding' => 'gzip, deflate',
                'Cache-Control'   => 'max-age=0',
                'Connection'      => 'keep-alive',
                // Referer penting untuk beberapa situs yang cek origin
                'Referer'         => 'https://www.google.com/',
            ],
        ], $extra));
    }

    private function getHtml(string $url): string
    {
        $client = $this->makeClient();

        try {
            $response = $client->get($url);
            $status   = $response->getStatusCode();

            if ($status === 403 || $status === 429) {
                throw new \RuntimeException(
                    "Situs memblokir akses otomatis (HTTP {$status}). "
                    . "Coba salin konten secara manual."
                );
            }

            if ($status >= 400) {
                throw new \RuntimeException("Halaman tidak ditemukan (HTTP {$status}).");
            }

            $body = (string) $response->getBody();

            if (empty(trim($body))) {
                throw new \RuntimeException('Halaman kosong.');
            }

            // Deteksi Cloudflare challenge page
            if ($this->isCloudflareChallenge($body)) {
                throw new \RuntimeException(
                    "Situs dilindungi Cloudflare dan memblokir akses otomatis. "
                    . "Coba gunakan URL versi AMP atau salin konten manual."
                );
            }

            return $body;

        } catch (RequestException $e) {
            $code = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;
            throw new \RuntimeException(
                "Gagal mengambil halaman (HTTP {$code}). Periksa URL dan coba lagi."
            );
        }
    }

    /** Deteksi apakah response adalah Cloudflare challenge */
    private function isCloudflareChallenge(string $html): bool
    {
        return str_contains($html, 'cf-browser-verification')
            || str_contains($html, 'checking your browser')
            || str_contains($html, '__cf_chl_')
            || (str_contains($html, 'cloudflare') && strlen(strip_tags($html)) < 500);
    }

    // ──────────────────────────────────────────────────────────
    // AMP URL CONVERTER
    // ──────────────────────────────────────────────────────────

    /**
     * Konversi URL artikel ke versi AMP.
     * Versi AMP biasanya tidak diproteksi Cloudflare.
     *
     * Contoh:
     * kompas.com/tren/read/... → amp.kompas.com/tren/read/...
     * detik.com/...           → m.detik.com/amp/...
     */
    private function toAmpUrl(string $url): ?string
    {
        $parsed = parse_url($url);
        if (! $parsed) return null;

        $host = $parsed['host'] ?? '';
        $path = $parsed['path'] ?? '/';
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';

        // Kompas → amp.kompas.com
        if (str_contains($host, 'kompas.com') && ! str_starts_with($host, 'amp.')) {
            return 'https://amp.' . ltrim($host, 'www.') . $path . $query;
        }

        // Detik → m.detik.com/amp/...
        if (str_contains($host, 'detik.com') && ! str_contains($host, 'm.detik')) {
            $newHost = 'm.' . ltrim($host, 'www.');
            return "https://{$newHost}/amp{$path}{$query}";
        }

        // Liputan6 → /amp/ prefix
        if (str_contains($host, 'liputan6.com')) {
            if (! str_contains($path, '/amp/')) {
                return 'https://' . $host . '/amp' . $path . $query;
            }
        }

        // Tribun → m.tribunnews.com
        if (str_contains($host, 'tribunnews.com') && ! str_starts_with($host, 'm.')) {
            return 'https://m.' . ltrim($host, 'www.') . $path . $query;
        }

        return null;
    }

    // ──────────────────────────────────────────────────────────
    // RSS FALLBACK
    // ──────────────────────────────────────────────────────────

    /**
     * Coba ambil data artikel dari RSS feed.
     * Beberapa situs Indonesia menyediakan RSS yang mudah diakses.
     */
    private function tryRssFallback(string $url): ?array
    {
        $parsed = parse_url($url);
        $host   = $parsed['host'] ?? '';

        $rssMap = [
            'kompas.com'      => 'https://rss.kompas.com/rss/news/indonesia',
            'detik.com'       => 'https://rss.detik.com/index.php/detikcom',
            'liputan6.com'    => 'https://www.liputan6.com/rss',
            'tribunnews.com'  => 'https://www.tribunnews.com/rss',
            'cnnindonesia.com' => 'https://www.cnnindonesia.com/rss',
        ];

        foreach ($rssMap as $domain => $rssUrl) {
            if (str_contains($host, $domain)) {
                try {
                    $rssHtml = $this->getHtml($rssUrl);
                    return $this->parseRss($rssHtml, $url);
                } catch (\Throwable) {
                    // RSS juga gagal
                }
            }
        }

        return null;
    }

    private function parseRss(string $xml, string $targetUrl): ?array
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($xml);
        libxml_clear_errors();

        $items = $dom->getElementsByTagName('item');
        if (! $items->length) return null;

        // Ambil item pertama dari RSS sebagai sampel
        $item = $items->item(0);
        $xpath = new DOMXPath($dom);

        $title   = $this->getTagContent($item, 'title');
        $desc    = $this->getTagContent($item, 'description');
        $content = $this->getTagContent($item, 'content:encoded') ?: $desc;
        $link    = $this->getTagContent($item, 'link') ?: $targetUrl;

        if (empty($title) && empty($content)) return null;

        // Bersihkan content dari HTML tags
        $cleanContent = strip_tags($content ?? '');

        return [
            'title'      => $title ?? '',
            'content'    => $cleanContent,
            'excerpt'    => mb_substr(strip_tags($desc ?? ''), 0, 250),
            'source_url' => $link,
            'warning'    => 'Data diambil dari RSS feed (bukan halaman penuh). Judul dan konten mungkin tidak lengkap.',
        ];
    }

private function getTagContent(\DOMElement $parent, string $tagName): string
{
    $tags = $parent->getElementsByTagName(
        str_contains($tagName, ':') ? explode(':', $tagName)[1] : $tagName
    );
    return $tags->length ? trim($tags->item(0)->textContent ?? '') : '';
}

    // ──────────────────────────────────────────────────────────
    // HTML PARSER
    // ──────────────────────────────────────────────────────────

    private function parseHtml(string $html, string $url): array
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            mb_convert_encoding(
                $html,
                'HTML-ENTITIES',
                mb_detect_encoding($html, 'UTF-8,ISO-8859-1,Windows-1252', true) ?: 'UTF-8'
            ),
            LIBXML_NOWARNING | LIBXML_NOERROR
        );
        libxml_clear_errors();

        $xpath   = new DOMXPath($dom);
        $title   = $this->extractTitle($xpath, $dom);
        $contentRaw = $this->extractContent($xpath);
        
        // Convert block elements to newlines to preserve paragraphs
        $cleanContent = preg_replace('/<(p|div|h[1-6]|li)[^>]*>/i', "\n\n", $contentRaw);
        $cleanContent = preg_replace('/<br[^>]*>/i', "\n", $cleanContent);
        $cleanContent = strip_tags($cleanContent);
        $cleanContent = preg_replace("/\n[ \t]+/", "\n", $cleanContent);
        $cleanContent = preg_replace("/\n{3,}/", "\n\n", $cleanContent);
        $cleanContent = trim($cleanContent);

        $excerpt = $this->extractExcerpt($xpath, $contentRaw);
        $imageUrl = $this->extractImage($xpath);

        return [
            'title'           => $title,
            'content'         => $cleanContent,
            'excerpt'         => $excerpt,
            'cover_image_url' => $imageUrl,
            'source_url'      => $url,
        ];
    }

    // ──────────────────────────────────────────────────────────
    // IMAGE EXTRACTOR
    // ──────────────────────────────────────────────────────────

    private function extractImage(DOMXPath $xpath): ?string
    {
        $selectors = [
            '//meta[@property="og:image"]/@content',
            '//meta[@name="twitter:image"]/@content',
            '//article//img[contains(@class, "read__photo")]/@src',
            '//article//img/@src',
        ];

        foreach ($selectors as $sel) {
            $nodes = $xpath->query($sel);
            if ($nodes && $nodes->length > 0) {
                $url = trim($nodes->item(0)->nodeValue ?? '');
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    return $url;
                }
            }
        }

        return null;
    }

    // ──────────────────────────────────────────────────────────
    // TITLE EXTRACTOR
    // ──────────────────────────────────────────────────────────

    private function extractTitle(DOMXPath $xpath, DOMDocument $dom): string
    {
        $selectors = [
            '//meta[@property="og:title"]/@content',
            '//meta[@name="twitter:title"]/@content',
            '//h1[contains(@class,"article__title")]',
            '//h1[contains(@class,"read__title")]',
            '//h1[contains(@class,"title--main")]',
            '//h1[contains(@itemprop,"headline")]',
            '//h1',
            '//title',
        ];

        foreach ($selectors as $sel) {
            $nodes = $xpath->query($sel);
            if ($nodes && $nodes->length > 0) {
                $val = trim($nodes->item(0)->nodeValue ?? '');
                if (mb_strlen($val) > 5) {
                    return $this->cleanText($val);
                }
            }
        }

        return '';
    }

    // ──────────────────────────────────────────────────────────
    // CONTENT EXTRACTOR
    // ──────────────────────────────────────────────────────────

    private function getContentSelectors(): array
    {
        return [
            // Kompas.com & amp.kompas.com
            '//*[contains(@class,"read__content")]',
            '//*[contains(@class,"article-content")]',
            '//*[@data-cy="article-body"]',

            // Detik.com
            '//*[contains(@class,"detail__body-text")]',
            '//*[contains(@class,"itp_bodycontent")]',

            // Liputan6.com
            '//*[contains(@class,"article-content-body")]',
            '//*[@itemprop="articleBody"]',

            // Tribun
            '//*[@id="content-artikel"]',
            '//*[contains(@class,"side-article")]',

            // CNN Indonesia
            '//*[contains(@class,"detail-wrap")]',
            '//*[contains(@class,"expander-body")]',

            // CNBC Indonesia
            '//*[contains(@class,"detail_text")]',

            // Tempo
            '//*[contains(@class,"detail-konten")]',

            // Antara
            '//*[contains(@class,"post-body")]',

            // Generic schema
            '//*[@itemprop="text"]',
            '//article//div[contains(@class,"content")]',
            '//article//div[contains(@class,"body")]',
            '//article//div[contains(@class,"text")]',
            '//article',

            // Generic WordPress / news CMS
            '//*[contains(@class,"post-content")]',
            '//*[contains(@class,"entry-content")]',
            '//*[contains(@class,"story-body")]',
            '//*[contains(@class,"news-body")]',
            '//*[@id="article-body"]',
            '//*[@id="post-body"]',
            '//main//div[contains(@class,"content")]',
        ];
    }

    private function extractContent(DOMXPath $xpath): string
    {
        foreach ($this->getContentSelectors() as $selector) {
            $nodes = $xpath->query($selector);
            if (! $nodes || ! $nodes->length) continue;

            $node = $nodes->item(0);
            $html = $this->nodeToCleanHtml($node);

            if (mb_strlen(strip_tags($html)) >= self::MIN_CONTENT_LENGTH) {
                return $html;
            }
        }

        // Last resort: kumpulkan semua <p>
        return $this->extractParagraphs($xpath);
    }

    private function extractParagraphs(DOMXPath $xpath): string
    {
        // Coba dalam article atau main dulu, baru global
        $queries = ['//article//p', '//main//p', '//p'];

        foreach ($queries as $q) {
            $nodes = $xpath->query($q);
            if (! $nodes || ! $nodes->length) continue;

            $parts = [];
            foreach ($nodes as $p) {
                $text = trim($p->textContent);
                if (mb_strlen($text) > 80) {
                    $parts[] = '<p>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p>';
                }
            }

            if (count($parts) >= 3) {
                return implode("\n", $parts);
            }
        }

        return '';
    }

    // ──────────────────────────────────────────────────────────
    // DOM CLEANER
    // ──────────────────────────────────────────────────────────

    private function nodeToCleanHtml(DOMNode $node): string
    {
        // Clone agar tidak memodifikasi DOM asli
        $clone = $node->cloneNode(true);
        $owner = new DOMDocument('1.0', 'UTF-8');
        $owner->appendChild($owner->importNode($clone, true));

        // Hapus tag yang tidak diinginkan
        // ⚠️ WAJIB pakai iterator_to_array() dulu sebelum hapus
        // untuk menghindari bug live NodeList
        $removeTags = [
            'script', 'style', 'a', 'ins', 'iframe', 'noscript',
            'form', 'button', 'nav', 'aside', 'figure', 'figcaption',
            'header', 'footer', 'table', 'ul', 'ol',
            'svg', 'canvas', 'video', 'audio',
        ];

        foreach ($removeTags as $tag) {
            $elements = iterator_to_array($owner->getElementsByTagName($tag));
            foreach ($elements as $el) {
                $el->parentNode?->removeChild($el);
            }
        }

        // Serialize ke HTML
        $html = $owner->saveHTML($owner->documentElement) ?: '';

        // Strip wrapper html/body yang ditambah DOMDocument
        $html = preg_replace('~^.*?<body[^>]*>~si', '', $html) ?? $html;
        $html = preg_replace('~</body>.*$~si', '', $html) ?? $html;

        // Bersihkan atribut
        $html = preg_replace('/\s+style="[^"]*"/i', '', $html) ?? $html;
        $html = preg_replace('/\s+class="[^"]*"/i', '', $html) ?? $html;
        $html = preg_replace('/\s+id="[^"]*"/i', '', $html) ?? $html;
        $html = preg_replace('/\s+data-[a-z-]+="[^"]*"/i', '', $html) ?? $html;

        // Hapus div/span kosong dan wrapper tidak perlu
        $html = preg_replace('/<(div|span)[^>]*>\s*<\/\1>/i', '', $html) ?? $html;
        $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html) ?? $html;

        // Collapse whitespace
        $html = preg_replace('/[ \t]+/', ' ', $html) ?? $html;
        $html = preg_replace('/\n{3,}/', "\n\n", $html) ?? $html;

        return trim($html);
    }

    // ──────────────────────────────────────────────────────────
    // EXCERPT EXTRACTOR
    // ──────────────────────────────────────────────────────────

    private function extractExcerpt(DOMXPath $xpath, string $content): string
    {
        $selectors = [
            '//meta[@property="og:description"]/@content',
            '//meta[@name="description"]/@content',
            '//meta[@name="twitter:description"]/@content',
        ];

        foreach ($selectors as $sel) {
            $nodes = $xpath->query($sel);
            if ($nodes && $nodes->length > 0) {
                $val = trim($nodes->item(0)->nodeValue ?? '');
                if (mb_strlen($val) > 30) {
                    return $this->cleanText(mb_substr($val, 0, 300));
                }
            }
        }

        // Fallback: 250 karakter pertama dari konten
        $plain = preg_replace('/\s+/', ' ', strip_tags($content)) ?? '';
        return mb_substr(trim($plain), 0, 250);
    }

    // ──────────────────────────────────────────────────────────
    // UTILS
    // ──────────────────────────────────────────────────────────

    private function cleanText(string $text): string
    {
        $text = preg_replace('/[\r\n\t]+/', ' ', $text) ?? $text;
        $text = preg_replace('/\s{2,}/', ' ', $text) ?? $text;
        return trim($text);
    }
}