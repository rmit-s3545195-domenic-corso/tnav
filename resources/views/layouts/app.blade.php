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
            <div id="header_left"></div>
            <div id="header_middle">
                <a href="{{ url('/') }}">
                    <img id="logo" alt="TNav" />
                </a>
            </div>
            <div id="header_right">
                <a class="btn btn-info" href="{{ url('/') }}" title="Home">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
                <a class="btn btn-success" href="{{ url('/add') }}" title="Add Restroom">
                    <span class="glyphicon glyphicon-plus"></span>
                </a>

                @if(!Session::has("admin_logged_in"))
                <a class="btn btn-danger" href="{{ url('/admin-login') }}" title="Admin Login">
                    <span class="glyphicon glyphicon-log-in"></span>
                </a>
                @endif

                @if(Session::has("admin_logged_in"))
                    <a class="btn btn-warning" href="{{ url('/search') }}" title="Delete Restroom">
                        <span class="glyphicon glyphicon-search"></span>
                    </a>

                    <a class="btn btn-danger" href="{{ url('/admin-logout') }}" title="Admin Logout">
                        <span class="glyphicon glyphicon-log-out"></span>
                    </a>

                @endif
            </div>
        </div>
        <div id="content">
            @yield('content')
        </div>
        <script src="{{ url('/js/lib/BL.js') }}"></script>
        <script src="{{ url('/js/tnav.js') }}"></script>
        @yield('scripts')
    </body>
</html>
