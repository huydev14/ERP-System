<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        {{-- Header --}}
        <header class="header">
            @include('partials/header')
        </header>

        {{-- Sidebar --}}
        <aside class="sidebar">
            @include('partials/sidebar')
        </aside>

        {{-- Content --}}
        <main class="content">
            {{-- Content header --}}
            <div class="content-header">
                <div class="container-fluid">
                    @yield('page-header')
                </div>
            </div>

            {{-- Content body --}}
            <div class="content-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="footer tw-text-gray-400 tw-shadow-lg">
            @include('partials/footer')
        </footer>

        {{-- Bootstrap --}}
        <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE -->
        <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
        <!-- OPTIONAL SCRIPTS -->
        <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ asset('adminlte/dist/js/pages/dashboard3.js') }}"></script>

        @stack('scripts')
</body>

</html>
