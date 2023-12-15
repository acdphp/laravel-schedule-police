<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laravel Schedule Police</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('/vendor/schedule-police/favicon.ico') }}">
    <link href="{{ asset('/vendor/schedule-police/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/schedule-police/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container w-75 px-5">
    <div class="py-5">
        @yield('content')
    </div>
</div>

<script src="{{ asset('/vendor/schedule-police/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/vendor/schedule-police/js/app.js') }}"></script>
</body>
</html>
