

// service-worker.js
self.addEventListener('install', (event) => {
    console.log('[ServiceWorker] Install');
  });
  
  self.addEventListener('activate', (event) => {
    console.log('[ServiceWorker] Activate');
  });
  
  self.addEventListener('fetch', (event) => {
    console.log('[ServiceWorker] Fetch:', event.request.url);
    event.respondWith(fetch(event.request));
  });

  