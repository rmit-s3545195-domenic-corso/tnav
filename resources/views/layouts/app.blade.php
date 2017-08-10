<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <title>@yield('title') | TNav</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ url('/css/app_layout.css') }}" />
        @yield('styles') 
    </head>
    <body>
        <div id="header">
            <div id="header_left">

            </div>
            <div id="header_middle">
                <img id="logo" alt="TNav" />
            </div>
            <div id="header_right">
            </div>
        </div>
        <div id="content">
            @yield('content')
        </div>
        @yield('scripts')
    </body>
</html>
