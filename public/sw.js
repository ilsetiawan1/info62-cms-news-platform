const CACHE_NAME = 'info62-cache-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/images/android-chrome-192x192.png',
  '/images/android-chrome-512x512.png',
  '/images/favicon.ico',
  '/images/logo-infoseputar62.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(ASSETS_TO_CACHE))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  // Only handle GET requests
  if (event.request.method !== 'GET') return;

  const url = new URL(event.request.url);

  // Exclude non-http/https protocols (like chrome-extension://)
  if (!url.protocol.startsWith('http')) return;

  // Exclude administrative routes to prevent caching admin panel
  if (url.pathname.startsWith('/seputaradmin') || url.pathname.includes('/login') || url.pathname.includes('/logout')) {
    return;
  }

  // Network First Strategy for HTML navigation requests
  if (event.request.mode === 'navigate' || (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html'))) {
    event.respondWith(
      fetch(event.request)
        .then(networkResponse => {
          if (networkResponse && networkResponse.status === 200) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseToCache);
            });
          }
          return networkResponse;
        })
        .catch(() => {
          return caches.match(event.request)
            .then(cachedResponse => {
              if (cachedResponse) return cachedResponse;
              return caches.match('/'); // Fallback to offline shell
            });
        })
    );
    return;
  }

  // Stale-While-Revalidate Strategy for other static resources (CSS, JS, images, fonts)
  event.respondWith(
    caches.match(event.request).then(cachedResponse => {
      const fetchPromise = fetch(event.request)
        .then(networkResponse => {
          if (networkResponse && networkResponse.status === 200) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseToCache);
            });
          }
          return networkResponse;
        })
        .catch(() => {
          // Fail silently on network errors
        });

      return cachedResponse || fetchPromise;
    })
  );
});
