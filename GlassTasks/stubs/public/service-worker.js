self.addEventListener('install', (event) => {
  event.waitUntil(caches.open('glasstasks-v1').then((cache)=> cache.addAll(['/','/manifest.webmanifest'])));
});
self.addEventListener('fetch', (event) => {
  event.respondWith(caches.match(event.request).then((res)=> res || fetch(event.request)));
});
self.addEventListener('push', (event) => {
  const data = event.data?.json() || { title: 'GlassTasks', body: 'Reminder' }
  event.waitUntil(self.registration.showNotification(data.title, { body: data.body, icon: '/icons/icon-192.png' }))
});