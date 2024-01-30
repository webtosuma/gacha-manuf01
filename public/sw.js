// インストールされたとき
self.addEventListener('install', e => {
    // 👇 基本ファイルのキャッシュを保存
    caches.open(appKey)
    .then(cache => {

        cache.addAll([
            '/',
            '/css/app.css',
            '/js/app.js',
        ]);

    })
})

// ?
self.addEventListener('activate', e => {
    console.log('ServiceWorker activate')
})

// データ取得するとき
self.addEventListener('fetch', e => {
    e.respondWith(
        caches.match(e.request).then(response => {
          return response || fetch(e.request);
        })
    );
})
