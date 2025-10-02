<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiTask - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
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

    <!-- Load original kanban.js first -->
    <script src="{{ asset('js/kanban.js') }}"></script>

    <!-- Then load the API integration layer -->
    <script src="{{ asset('js/kanban-integration.js') }}"></script>
</body>

</html>
