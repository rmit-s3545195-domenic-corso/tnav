<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <title>@yield('title') | TNav</title>
        <script src="{{ url('/gapi') }}" type="text/javascript"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Fjalla+One|Raleway" rel="stylesheet">
        <link rel="stylesheet" href="{{ url('/css/app_layout.css') }}" />
        @yield('styles')
    </head>
    <body>
        <div id="header">
            <div id="header_left">

            </div>
            <div id="header_middle">
                <a href="{{ url('/') }}">
                    <img id="logo" alt="TNav" />
                </a>
            </div>
            <div id="header_right">
                <a class="btn btn-info" href="{{ url('/add') }}">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add Restroom
                </a>
            </div>
        </div>
        <div id="content">
            @yield('content')
        </div>
        <script src="{{ url('/js/tnav.js') }}"></script>
        @yield('scripts')
    </body>
</html>
