<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Web Crawler</title>
        <link href="{{asset("css/app.css")}}" rel="stylesheet">
        <script src="{{asset("js/app.js")}}"></script>
    </head>
    <body>
        <header></header>
        <main>@yield("main")</main>
        <footer></footer>
    </body>
</html>