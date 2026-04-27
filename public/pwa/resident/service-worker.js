const CACHE_NAME = 'condominet-resident-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/index.html',
  '/login.html',
  '/dashboard.html',
  '/account.html',
  '/tickets.html',
  '/amenities.html',
  '/visitor-qr.html',
  '/assets/css/style.css',
  '/assets/js/app.js',
  '/manifest.json'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(ASSETS_TO_CACHE))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Estrategia Network First, fallback to cache
self.addEventListener('fetch', (event) => {
  // Ignorar peticiones a la API para no cachear datos dinámicos sensibles
  if (event.request.url.includes('/api/v1/')) {
      return;
  }

  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request);
    })
  );
});

// ==========================================
// PUSH EVENT HANDLER (FIREBASE CLOUD MESSAGING)
// ==========================================
self.addEventListener('push', (event) => {
    console.log('[Service Worker] Push Recibido.');
    
    // Tratamos de parsear el JSON de FCM
    let payload = {};
    if (event.data) {
        payload = event.data.json(); // Depende del formato exacto de Firebase: data.notification
    }
    
    // Para adaptabilidad con la API V1 de Firebase que extrae notification/data por separado
    const title = payload.notification?.title || 'Notificación Condominet';
    const body = payload.notification?.body || 'Tienes un nuevo mensaje';
    const clickUrl = payload.data?.url || '/'; // Redirección condicional

    const options = {
        body: body,
        icon: '/assets/icons/icon-192.png',
        badge: '/assets/icons/icon-192.png', // Icono monocolor para barra en Android
        vibrate: [200, 100, 200, 100, 200, 100, 200],
        data: {
            url: clickUrl
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// ==========================================
// CLICK EVENT EN LA NOTIFICACIÓN
// ==========================================
self.addEventListener('notificationclick', (event) => {
    console.log('[Service Worker] Notificación Clickeada.');
    event.notification.close();

    const targetUrl = event.notification.data.url;

    // Al darle tap a la notificación, abre la PWA en esa pantalla específica
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            // Verificar si la app ya está abierta para simplemente enfocarla
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            // Si no está abierta, se abre una nueva pestaña de la PWA
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
