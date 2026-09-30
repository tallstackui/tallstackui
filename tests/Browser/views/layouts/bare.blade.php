<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
    <tallstackui:setup />
</head>
<body>
    {!! $slot !!}
</body>
</html>
