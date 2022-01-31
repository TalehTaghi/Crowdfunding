<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', "CF - CrowdFunding")</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" />

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/bootstrap-theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/fontAwesome/all.min.css') }}" rel="stylesheet" type="text/css" />

    <link class="alt" href="{{ asset('assets/colors/color1.css') }}" rel="stylesheet" type="text/css" />
    @yield('css')
</head>
