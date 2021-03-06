const staticCacheName = 'site-static-v2';
const dynamicCacheName = 'site-dynamic-v1';
var assets = [
  '/',
  'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700',
  'https://fonts.googleapis.com/css?family=Libre+Baskerville:400,400i,700',
  'https://use.fontawesome.com/releases/v5.8.2/css/all.css',
  'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.3/css/mdb.min.css" rel="stylesheet',
  'https://code.jquery.com/jquery-3.3.1.min.js',
  'https://widget.cloudinary.com/v2.0/global/all.js',
  '/js/jquery.stateLga.js',
  '/js/jquery.ucfirst.js'

];

// cache size limit function
const limitCacheSize = (name, size) => {
  caches.open(name).then(cache => {
    cache.keys().then(keys => {
      if (keys.length > size) {
        cache.delete(keys[0]).then(limitCacheSize(name, size));
      }
    });
  });
};

// install event
self.addEventListener('install', evt => {
  //console.log('service worker installed');
  evt.waitUntil(
    caches.open(staticCacheName).then((cache) => {
      console.log('caching shell assets');
      cache.addAll(assets);
    })
  );
});

// activate event
self.addEventListener('activate', evt => {
  //console.log('service worker activated');
  evt.waitUntil(
    caches.keys().then(keys => {
      //console.log(keys);
      return Promise.all(keys
        .filter(key => key !== staticCacheName && key !== dynamicCacheName)
        .map(key => caches.delete(key))
      );
    })
  );
});

// fetch event
self.addEventListener('fetch', evt => {
  //console.log('fetch event', evt);
  evt.respondWith(
    caches.match(evt.request).then(cacheRes => {
      return cacheRes || fetch(evt.request).then(fetchRes => {
        return caches.open(dynamicCacheName).then(cache => {
          cache.put(evt.request.url, fetchRes.clone());
          // check cached items size
          limitCacheSize(dynamicCacheName, 15);
          return fetchRes;
        })
      });
    }).catch(() => {
      if (evt.request.url.indexOf('/') > -1) {
        return caches.match('/');
      }
    })
  );
});