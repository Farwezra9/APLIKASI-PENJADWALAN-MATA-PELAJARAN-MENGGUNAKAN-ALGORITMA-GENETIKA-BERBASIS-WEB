<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Penjadwalan Mata Pelajaran SMKN 2 Kuningan</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
 <!-- Mazer CSS -->
 <link rel="stylesheet" href="{{ asset('compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('compiled/css/iconly.css') }}">

    <!-- Datatables CSS -->
    <link rel="stylesheet" href="{{ asset('extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('compiled/css/table-datatable-jquery.css') }}">

    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="{{ asset('extensions/bootstrap-select/bootstrap-select/css/bootstrap-select.css') }}">


    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('extensions/sweetalert2/sweetalert2.min.css') }}">

</head>

<body>
    <script src="{{ asset('static/js/initTheme.js') }}"></script>
    <div id="app">
    <div id="main" class="layout-horizontal">
    <header class="mb-0">
            @include('user.partials.header')
    </header>
    <div id="main-content">
                @yield('content')
            </div>
            @include('user.partials.footer')
        </div>
    </div>

    <!-- Bootstrap JS (5.x, popper.js included) -->
    <script src="{{ asset('extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('extensions/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('extensions/bootstrap-select/bootstrap-select/js/bootstrap-select.min.js') }}"></script>

    <!-- Datatables JS -->
    <script src="{{ asset('extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('static/js/pages/datatables.js') }}"></script>

    <!-- Parsley JS -->
    <script src="{{ asset('extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('static/js/pages/parsley.js') }}"></script>

    <!-- Mazer Scripts -->
    <script src="{{ asset('static/js/components/dark.js') }}"></script>
    <script src="{{ asset('static/js/pages/horizontal-layout.js') }}"></script>
    <script src="{{ asset('extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('compiled/js/app.js') }}"></script>

    <!-- SweetAlert2 JS -->
    <script src="{{ asset('extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('static/js/pages/sweetalert2.js') }}"></script>

    
    @yield('scripts')
</body>

</html>
