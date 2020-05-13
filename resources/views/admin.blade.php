<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->

        <!-- Styles -->
        <style>
            html,
            body,
            #app {
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>

        {{-- app element --}}
        <div id="app">
        </div>

        {{-- 前端 --}}
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
