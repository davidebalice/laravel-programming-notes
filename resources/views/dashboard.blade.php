<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <title>User dashboard</title>
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:title" content="" />
        <meta property="og:type" content="" />
        <meta property="og:url" content="" />
        <meta property="og:image" content="" />
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/imgs/theme/favicon.ico') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css?v=5.3') }}" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@10.7.2/styles/default.min.css">
        <script src="https://cdn.jsdelivr.net/npm/highlight.js@10.7.2/lib/highlight.min.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </head>
    
    <body>
        @include('frontend.body.header')

        <main class="main pages">
            @php
                echo redirect()->route('admin.dashboard');
            @endphp

            @yield('user')
        </main>
    
        @include('frontend.body.footer')
    
        <div id="preloader-active">
            <div class="preloader d-flex align-items-center justify-content-center">
                <div class="preloader-inner position-relative">
                    <div class="text-center">
                        <img src="{{ asset('frontend/assets/imgs/theme/loading.gif') }}" alt="" />
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('frontend/assets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/slick.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/jquery.syotimer.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/waypoints.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/wow.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/magnific-popup.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/select2.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/counterup.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/jquery.countdown.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/images-loaded.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/isotope.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/scrollup.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/jquery.vticker-min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/jquery.theia.sticky.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/plugins/jquery.elevatezoom.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/main.js?v=5.3') }}"></script>
        <script src="{{ asset('frontend/assets/js/shop.js?v=5.3') }}"></script>
        <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            @if(Session::has('message'))
            var type = "{{ Session::get('alert-type','info') }}"
            switch(type){
                case 'info':
                toastr.info(" {{ Session::get('message') }} ");
                break;
                case 'success':
                toastr.success(" {{ Session::get('message') }} ");
                break;
                case 'warning':
                toastr.warning(" {{ Session::get('message') }} ");
                break;
                case 'error':
                toastr.error(" {{ Session::get('message') }} ");
                break; 
            }
            @endif 
        </script>
    </body>
</html>