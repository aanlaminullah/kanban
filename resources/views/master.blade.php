<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiTask - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>
    @yield('content')
    <script src="{{ asset('js/kanban.js') }}"></script>
</body>

</html>
