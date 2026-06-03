const CACHE = 'bantujiran-v1';
const OFFLINE_URLS = ['/', '/feed', '/modules', '/login', '/offline.html'];

// Install — cache key pages
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE).then(c => c.addAll(OFFLINE_URLS.map(u => new Request(u, {credentials:'include'})).filter(Boolean)).catch(() => {}))
    );
    self.skipWaiting();
});

// Activate — remove old caches
self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys => Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k))))
    );
    self.clients.claim();
});

// Fetch — cache-first for static assets, network-first for pages
self.addEventListener('fetch', e => {
    const url = new URL(e.request.url);

    // Skip non-GET and cross-origin
    if (e.request.method !== 'GET' || url.origin !== location.origin) return;

    // Static assets — cache first
    if (url.pathname.match(/\.(css|js|woff2?|png|jpg|ico|svg)$/)) {
        e.respondWith(caches.match(e.request).then(r => r || fetch(e.request).then(res => {
            const clone = res.clone();
            caches.open(CACHE).then(c => c.put(e.request, clone));
            return res;
        })));
        return;
    }

    // HTML pages — network first, fallback to cache
    e.respondWith(
        fetch(e.request)
            .then(res => {
                const clone = res.clone();
                if (res.ok) caches.open(CACHE).then(c => c.put(e.request, clone));
                return res;
            })
            .catch(() => caches.match(e.request).then(r => r || caches.match('/offline.html')))
    );
});

// Push notifications
self.addEventListener('push', e => {
    if (!e.data) return;
    let data = {};
    try { data = e.data.json(); } catch { data = {title:'BantuJiran', body: e.data.text()}; }

    e.waitUntil(
        self.registration.showNotification(data.title || 'BantuJiran', {
            body:    data.body  || '',
            icon:    data.icon  || '/icons/icon-192.png',
            badge:   '/icons/badge-72.png',
            data:  { url: data.url || '/' },
            actions: [{ action:'open', title:'Buka' }],
            vibrate: [200, 100, 200],
        })
    );
});

// Notification click
self.addEventListener('notificationclick', e => {
    e.notification.close();
    const url = e.notification.data?.url || '/';
    e.waitUntil(clients.matchAll({type:'window'}).then(list => {
        for (const c of list) { if (c.url === url && 'focus' in c) return c.focus(); }
        if (clients.openWindow) return clients.openWindow(url);
    }));
});

// Background sync
self.addEventListener('sync', e => {
    if (e.tag === 'send-message') {
        e.waitUntil(syncMessages());
    }
});

async function syncMessages() {
    const db = await openDB();
    const pending = await getPending(db);
    for (const msg of pending) {
        try {
            await fetch(msg.url, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':msg.token}, body: JSON.stringify({body:msg.body}) });
            await deletePending(db, msg.id);
        } catch {}
    }
}
// Simple IndexedDB helpers for offline message queue
function openDB() {
    return new Promise((res,rej) => {
        const req = indexedDB.open('bantujiran',1);
        req.onupgradeneeded = e => e.target.result.createObjectStore('pending',{keyPath:'id',autoIncrement:true});
        req.onsuccess = e => res(e.target.result);
        req.onerror = rej;
    });
}
function getPending(db) {
    return new Promise(res => { const tx=db.transaction('pending','readonly'); const all=[]; tx.objectStore('pending').openCursor().onsuccess=e=>{const c=e.target.result;if(c){all.push(c.value);c.continue();}else res(all);}; });
}
function deletePending(db,id) {
    return new Promise(res => { const tx=db.transaction('pending','readwrite'); tx.objectStore('pending').delete(id); tx.oncomplete=res; });
}
