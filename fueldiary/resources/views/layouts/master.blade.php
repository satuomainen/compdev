<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tankkeri</title>

    <link href='//fonts.googleapis.com/css?family=Trocchi' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <script src="/js/app.js"></script>
    @yield('header-scripts')
</head>
<body>
    @yield('navigation')
    <div class="container">
        @yield('content')
    </div>
    @yield('footer')
    @yield('scripts')
</body>
</html>
