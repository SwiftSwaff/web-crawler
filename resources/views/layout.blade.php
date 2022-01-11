<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Web Crawler 01102022</title>
        <link href="{{ asset("css/app.css", \App::environment() == 'production') }}" rel="stylesheet">
        <script src="{{ asset("js/app.js", \App::environment() == 'production') }}"></script>
    </head>
    <body>
        <main>@yield("main")</main>
    </body>
</html>