<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $pageTitle ?? '-' }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/letter-w.png') }}" rel="icon">
    <link href="{{ asset('assets/letter-w.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/landing/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/landing/vendor/owlCarousel/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/landing/vendor/owlCarousel/css/owl.theme.default.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/landing/css/style.css') }}" rel="stylesheet">

    @yield('css')
</head>

<body>

    <!-- ======= Header ======= -->
    @include('landing.layouts.components.header')
    <!-- End Header -->

    <main id="main">

        @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('landing.layouts.components.footer')
    <!-- End #footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/dashboard/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/landing/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/landing/vendor/owlCarousel/js/owl.carousel.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/landing/js/main.js') }}"></script>

    @yield('js')
</body>

</html>
