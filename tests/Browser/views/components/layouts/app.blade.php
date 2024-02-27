<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
    <script src="/storage/dayjs/dayjs.min.js"></script>
    <script src="/storage/dayjs/en.js"></script>
    <script>
        window.dayjs = dayjs.locale('en');
    </script>
    @tallStackUiScript
    @tallStackUiStyle
</head>
<body>
    <x-dialog />
    <x-toast />
    {!! $slot !!}
    {{-- It was necessary move the component down to $slot due xpath browser tests --}}
    <x-banner wire />
</body>
</html>
