<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @tasteUiStyles
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <x-dialog />
    <x-toast />
    {!! $slot !!}
    @tasteUiScripts
</body>
</html>
