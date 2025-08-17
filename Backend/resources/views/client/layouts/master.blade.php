<!DOCTYPE html>
<html class="no-js" lang="en">

<!-- Mirrored from uiuxom.com/ulina/html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 29 Aug 2024 17:26:51 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <meta name="description" content="Fashion Ecommerce Responsive HTML Template.">
    <meta name="keywords"
        content="HTML, CSS, JavaScript, jQuery, Animation, Bootstrap, Font Awesome, Revolution Slider, Fasion, Ecommerce, Shop, WooCommerce">
    <meta name="author" content="uiuxom">

    <!-- BEGIN: CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('client/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/lightcase.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/settings.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/ulina-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/ignore_for_wp.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/preset.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />


    @yield('css')
    <!-- END: CSS -->

    <!-- BEGIN: Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('client/images/favicon.png') }}">
    <!-- END: Favicon -->
</head>

<body>
    <!-- BEGIN: PreLoder Section -->
    {{-- <section class="preloader" id="preloader">
        <div class="spinner-eff spinner-eff-1">
            <div class="bar bar-top"></div>
            <div class="bar bar-right"></div>
            <div class="bar bar-bottom"></div>
            <div class="bar bar-left"></div>
        </div>
    </section> --}}
    <!-- END: PreLoder Section -->

    @include('client.partials.header')

    @include('client.partials.search')



    @yield('content')

    @include('client.partials.footer')

    <!-- Dialogflow chatbot -->
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger intent="" chat-title="Bot Tư Vấn" agent-id="0fc7ed94-b173-499d-852f-d4cb8410ce77" language-code="vi"></df-messenger>

    <!-- BEGIN: JS -->
    <!-- jQuery (toastr cần) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('client/js/jquery.js') }}"></script>
    <script src="{{ asset('client/js/jquery-ui.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('client/js/shuffle.min.js') }}"></script>
    <script src="{{ asset('client/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('client/js/owl.carousel.filter.js') }}"></script>
    <script src="{{ asset('client/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('client/js/lightcase.js') }}"></script>
    <script src="{{ asset('client/js/jquery.nice-select.js') }}"></script>
    <script src="{{ asset('client/js/slick.js') }}"></script>
    <script src="{{ asset('client/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('client/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('client/js/circle-progress.js') }}"></script>
    <script src="{{ asset('client/js/gmaps.js') }}"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCA_EDGVQleQtHIp2fZ-V56QFRbRL8cXT8"></script>

    <script src="{{ asset('client/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ asset('client/js/jquery.themepunch.revolution.min.js') }}"></script>

    <script src="{{ asset('client/js/extensions/revolution.extension.actions.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.carousel.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.migration.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.navigation.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.parallax.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
    <script src="{{ asset('client/js/extensions/revolution.extension.video.min.js') }}"></script>

    <script src="{{ asset('client/js/theme.js') }}"></script>
    <script>
        function updateCartCount(count) {
                const countElement = document.getElementById('cart-count');
                if (countElement) {
                    countElement.textContent = count;
                }
            }
    </script>
    @yield('script')
    <!-- END: JS -->
</body>

</html>
