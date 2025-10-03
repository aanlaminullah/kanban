<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiTask - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
</head>

<body>
    @yield('content')

    <!-- Pass current user data to JavaScript -->
    @if (Auth::check())
        <script>
            window.currentUser = {
                id: {{ Auth::id() }},
                name: "{{ Auth::user()->name }}",
                email: "{{ Auth::user()->email }}",
                avatar: "{{ Auth::user()->avatar }}",
                color: "{{ Auth::user()->color }}",
                role: "{{ Auth::user()->role }}"
            };
        </script>
    @endif

    <script src="{{ asset('js/kanban.js') }}"></script>
    <script src="{{ asset('js/kanban-integration.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
