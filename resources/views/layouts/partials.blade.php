<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{URL('/favicon.png')}}" type="image/x-icon"/>

        <title>EzConference</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body>
        @include('layouts.nav')
        @yield('content')

        <script src="{{ mix('/js/jquery.min.js') }}"></script>
        <script src="{{ mix('/js/popper.min.js') }}"></script>
        <script src="{{ mix('/js/bootstrap.min.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
