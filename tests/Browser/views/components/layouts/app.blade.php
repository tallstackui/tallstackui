<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @tallStackUiScripts
    @tallStackUiStyles
</head>
<body>
    <x-dialog />
    <x-toast />
    {!! $slot !!}
</body>
</html>
