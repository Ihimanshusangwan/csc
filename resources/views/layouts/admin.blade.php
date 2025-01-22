<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/tailwind.js') }}" defer></script>
</head>
<body class="bg-gray-100">
    @include('admin.header')
    <div class="container mx-auto mt-8">
        @yield('content')
    </div>
</body>
</html>