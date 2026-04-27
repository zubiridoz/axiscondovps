const CACHE_NAME = 'condominet-security-v6';
const ASSETS_TO_CACHE = [
  '/',
  '/index.html',
  '/login.html',
  '/entradas.html',
  '/unidades.html',
  '/paqueteria.html',
  '/configuracion.html',
  '/scan-qr.html',
  '/manual-entry.html',
  '/salidas.html',
  '/access-log.html',
  '/assets/css/style.css',
  '/assets/js/app.js',
  '/manifest.json'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS_TO_CACHE))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) return caches.delete(cacheName);
        })
      );
    })
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.url.includes('/api/v1/')) return;

  event.respondWith(
    fetch(event.request).catch(() => caches.match(event.request))
  );
});

// ==========================================
// PUSH EVENT HANDLER - CASETA DE SEGURIDAD
// ==========================================
self.addEventListener('push', (event) => {
    let payload = {};
    if (event.data) {
        payload = event.data.json();
    }
    
    const title = payload.notification?.title || 'Caseta de Vigilancia';
    const body = payload.notification?.body || 'Nueva alerta de sistema.';
    const clickUrl = payload.data?.url || '/pwa/security/entradas.html';

    const options = {
        body: body,
        icon: '../resident/assets/icons/icon-192.png', 
        vibrate: [500, 200, 500], // Vibración extendida y agresiva para emergencias
        data: { url: clickUrl }
    };

    event.waitUntil( self.registration.showNotification(title, options) );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});
