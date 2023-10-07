<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @tallStackUiStyles
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <x-dialog />
    <x-toast />
    {!! $slot !!}
    @tallStackUiScripts
</body>
</html>
