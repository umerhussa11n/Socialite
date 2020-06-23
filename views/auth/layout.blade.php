<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Title -->
    <title>@yield('title')</title>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('public/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/sweetalert2/sweetalert2.min.css') }}">

    <!-- Neptune CSS -->
    <link rel="stylesheet" href="{{ asset('public/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/extra.css') }}">

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="img-cover" style="background-image: url('@yield('bg')');">
<div class="preloader" style="display: none"></div>
<div class="container-fluid">
    <div class="sign-form">
        <div class="row">
            <div class="col-md-4 offset-md-4 p-x-3">
                <div class="box b-a-0">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor JS -->
<script type="text/javascript" src="{{ asset('public/vendor/jquery/jquery-1.12.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/vendor/tether/js/tether.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
@yield('extra')
</body>
</html>