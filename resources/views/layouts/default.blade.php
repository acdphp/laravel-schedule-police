<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laravel Schedule Control</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('/vendor/schedule-control/favicon.ico') }}">
    <link href="{{ asset('/vendor/schedule-control/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/schedule-control/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container w-75 px-5">
    <div class="py-5">
        @yield('content')
    </div>
</div>

<script src="{{ asset('/vendor/schedule-control/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/vendor/schedule-control/js/app.js') }}"></script>
</body>
</html>
