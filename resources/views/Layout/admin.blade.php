<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Employee Management</title>
    <!-- css links -->
    <link rel="stylesheet" href="{{ asset('public/asset/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/style.css') }}">    
    <link rel="stylesheet" href="{{ asset('public/asset/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/swiperbundle.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/font_awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/font_awesome/css/fontawesome_5.14.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/gijgo.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/parsley.css') }}">   
    <link rel="stylesheet" href="{{ asset('public/asset/css/dataTables.dataTables.min.css') }}"> 

</head>
<body>
<script src="{{ asset('public/asset/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/asset/js/ionicons.js') }}"></script>
<script src="{{ asset('public/asset/js/popper.min.js') }}"></script>
<script src="{{ asset('public/asset/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/asset/js/velocity.min.js') }}"></script>
<script src="{{ asset('public/asset/js/velocity.ui.min.js') }}"></script>
<script src="{{ asset('public/asset/js/select2.min.js') }}"></script>
<script src="{{ asset('public/asset/js/gijgo.min.js') }}"></script>
<script src="{{ asset('public/asset/js/sweetalert.2.10.js') }}"></script>
<script src="{{ asset('public/asset/js/apexcharts.js') }}"></script>
<script src="{{ asset('public/asset/js/SweetAlert_Function.js') }}"></script>
<script src="{{ asset('public/asset/js/parsley.js') }}"></script>
<script src="{{ asset('public/asset/js/dataTables.min.js') }}"></script>
@yield('content')
</body>
</html>