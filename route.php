<?php




?>

<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <!-- Color theme for statusbar (Android only) -->
    <meta name="theme-color" content="#2196f3">
    <!-- Your app title -->
    <title>My App</title>
    <!-- Path to Framework7 Library Bundle CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/framework7/5.1.2/css/framework7.bundle.min.css" integrity="sha512-zA83zl8VlOpHCTJFNQxLd5TolYAjCiRo7qk/d//TCP462s9hpFbkrIElHLfPtnnEDlxCpbR9k8+eztffSJrBTw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- Path to your custom app styles-->

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
    </script>
</head>



<!-- App root element -->

<body>
    <div id="app">

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


    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/framework7/5.1.2/js/framework7.min.js" integrity="sha512-o+4OTkULRHhBkQRC9qEALs5OscxTBH1vloPm7L2vdbQx5ZJJpqxU3+8L3a6dmN01xB8htwmy6ZjmoMyiPfDc5A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


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
            },
            routes: [{
                path: '/(.*)',
                async: function(routeTo, routeFrom, resolve, reject) {
                    const page = routeTo.params[0] || 'home';
                    resolve({
                        url: `/${page}`,
                    });
                }
            }],
        });



        console.log('Current route URL:', app.views.current?.router?.currentRoute?.url);

        const mainView = app.views.create('#main-view');


        let mode;
        if (app.device.webView) mode = 'Standalone';
        else if (app.device.desktop) mode = 'Browser';
        else mode = 'Mobile Browser';
        console.log('Running in:', mode);

        app.on('routeChange', (route) => {
            console.log('Route changed to:', route.url);
        });

        // Page-level events
        app.on('pageInit', ({
            route
        }) => console.log('pageInit →', route.url));
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
    </script>


    <script src="/assets/js/app.js"></script>


    <!-- Framework7 Core JS -->



</body>

</html>