<?php

?>

<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#2196f3">
    <meta name="description" content="Your app description here">
    <meta name="keywords" content="Framework7, PHP, SSR, Mobile App">
    <meta name="author" content="Your Name">
    <title>Sushan</title>

    <!-- Path to Framework7 Library Bundle CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/framework7/5.1.2/css/framework7.bundle.min.css"
        integrity="sha512-zA83zl8VlOpHCTJFNQxLd5TolYAjCiRo7qk/d//TCP462s9hpFbkrIElHLfPtnnEDlxCpbR9k8+eztffSJrBTw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- Path to your custom app styles-->
    <link href="https://cdn.jsdelivr.net/npm/framework7-icons@5.0.5/css/framework7-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/root.css">
    <!--
    <script type="module">
        import {
            SplashScreen
        } from 'https://cdn.jsdelivr.net/npm/@capacitor/splash-screen@5.0.6/dist/index.js';

        window.hideSplash = async function() {
            try {
                await SplashScreen.hide();
                console.log('Splash screen hidden.');
            } catch (e) {
                console.warn('SplashScreen not available.', e);
            }
        };
    </script> -->
</head>



<!-- App root element -->

<body>
    <div id="app" class="<?php echo $app['device'] ?>">
        <?php if ($app['device'] !== 'browser') { ?>
            <div id="main-view" class="view view-main safe-areas" data-url="/">
                <?php echo $pageContent ?>
                <div class="toolbar toolbar-bottom">
                    <div class="toolbar-inner">
                        <a href="/" class="link">Home</a>
                        <a href="/about" class="link">About</a>
                        <a href="#" class="link">Link 3</a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div id="main-view" class="view view-main safe-areas" data-url="/">

                <style>
                    .logo {
                        max-width: 200px;
                    }
                </style>

                <div class="navbar  navbar-transparent">
                    <div class="navbar-bg"></div>
                    <!-- Additional "sliding" class -->
                    <div class="navbar-inner sliding">
                        <div class="left padding-left">
                            <div class="padding-right display-flex justify-content-center align-items-center gap-sm">
                                <i class="f7-icons">menu</i>
                                <div class="">Category</div>
                            </div>

                        </div>

                        <div class="title padding-vertical">

                            <!-- Additional "searchbar-inline" class -->

                            <div class="searchbar-backdrop"></div>
                            <form class="searchbar">
                                <div class="searchbar-inner">
                                    <div class="searchbar-input-wrap">
                                        <input type="search" placeholder="Search" />
                                        <i class="searchbar-icon"></i>
                                        <span class="input-clear-button"></span>
                                    </div>
                                    <span class="searchbar-disable-button">Cancel</span>
                                </div>
                            </form>
                        </div>
                        <div class="right padding-right">
                            <a href="#" class="link">Sign in</a>
                        </div>
                    </div>
                </div>
                <?php echo $pageContent ?>
            </div>
        <?php } ?>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/framework7/5.1.2/js/framework7.min.js"
        integrity="sha512-o+4OTkULRHhBkQRC9qEALs5OscxTBH1vloPm7L2vdbQx5ZJJpqxU3+8L3a6dmN01xB8htwmy6ZjmoMyiPfDc5A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="/assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/framework7-icons@5.0.5/react/framework7-icons-react.cjs.min.js"></script>
    <script>
        const app = new Framework7({
            root: '#app',
            name: 'F7 PHP SSR',
            theme: 'auto',
            view: {
                pushState: true,
                pushStateSeparator: '',
                animate: true,
                iosSwipeBack: true,
                stackPages: true,
            }
        });

        app.on('routeChange', (route) => {
            console.log('Route changed to:', route.url);
        });

        let mode;
        if (app.device.webView) mode = 'standalone';
        else if (app.device.desktop) mode = 'browser';
        else mode = 'mobile';
        console.log('Running in:', mode);


        console.log('Current route URL:', app.views.current?.router?.currentRoute?.url);

        const mainView = app.views.create('#main-view', {
            routes: [{
                path: '/(.*)',
                async: function(routeTo, routeFrom, resolve, reject) {
                    const page = routeTo.url;
                    console.log('Loading page:', page);
                    try {
                        resolve({
                            url: page + '?device=' + mode + '&timestamp=' + new Date().getTime(),
                        });
                    } catch (error) {
                        console.error('Error loading page:', error);
                        reject();
                    }
                },
            }, ],
        });




        // Page-level events
        app.on('pageInit', ({
            route,
            el
        }) => {
            console.log('pageInit →', route.url);

            const searchbarEl = el.querySelector('.searchbar');
            if (searchbarEl) {
                app.searchbar.create({
                    el: searchbarEl,
                    on: {
                        enable: function() {
                            console.log('Searchbar enabled');
                        }
                    }
                });
            }
        });

        app.on('pageBeforeIn', ({
            route
        }) => console.log('pageBeforeIn →', route.url));
        app.on('pageAfterIn', ({
            route
        }) => console.log('pageAfterIn →', route.url));
        app.on('pageBeforeOut', ({
            route
        }) => console.log('pageBeforeOut →', route.url));
        app.on('pageAfterOut', ({
            route
        }) => console.log('pageAfterOut →', route.url));
        app.on('pageBeforeRemove', ({
            route
        }) => console.log('pageBeforeRemove →', route.url));

        document.addEventListener('deviceready', () => {
            console.log('Device is ready');
            if (window.hideSplash) window.hideSplash();
        });

        self.addEventListener('install', (event) => {
            console.log('Service Worker installing.');
            event.waitUntil(
                caches.open('my-app-cache').then((cache) => {
                    return cache.addAll([
                        '/',
                        '/assets/css/custom-styles.css',
                        '/assets/js/app.js',
                        'https://cdnjs.cloudflare.com/ajax/libs/framework7/5.1.2/css/framework7.bundle.min.css',
                        'https://cdnjs.cloudflare.com/ajax/libs/framework7/5.1.2/js/framework7.min.js',
                    ]);
                })
            );
        });

        self.addEventListener('fetch', (event) => {
            event.respondWith(
                caches.match(event.request).then((response) => {
                    return response || fetch(event.request);
                })
            );
        });
    </script>
</body>

</html>