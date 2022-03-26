<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Administration | {{ config('app.name') }}</title>

    <!-- Styles -->
    @if ($stylePath = html_asset('backend', 'backend.css'))
        <link rel="stylesheet" href="{{ $stylePath }}">
    @endif
    <link rel="shortcut icon" href="{{asset('favicon.png')}}">

    <!-- CDN -->
{{--    <script defer src="/popper.min.js"></script>--}}
    <script defer src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    {{--<script defer src="//cdn.ckeditor.com/ckeditor5/10.0.0/classic/ckeditor.js"></script>--}}

    <!-- Scripts -->
    <script defer src="{{ html_asset('backend', 'vendor-backend.js') }}"></script>
    <script defer src="{{ html_asset('backend', 'backend.js') }}"></script>

    <!-- JS settings -->
    <script type="application/json" data-settings-selector="settings-json">
        {!! json_encode([
            'locale' => app()->getLocale(),
            'appName' => config('app.name'),
            'adminHomePath' => route('home', [], false),
            'editorName' => config('app.editor_name'),
            'editorSiteUrl' => config('app.editor_site_url'),
            'pusher_config' => [
                'broadcaster'=> 'pusher',
                'key'=> 'mysepecialrandomkey',
                'wsHost'=> '',
                'wsPort'=> 6001,
                'disableStats'=> true,
                'enabledTransports'=> ['ws', 'wss']
            ],
            //'locales' => LaravelLocalization::getSupportedLocales(),
            'user' => $loggedInUser,
            'permissions' => session()->get('permissions'),
        ]) !!}
{{--                        'wssPort'=> 6001,--}}
{{--                        'encrypted'=> true,--}}

    </script>

    @include('ckfinder::setup')

    <!-- Named routes -->
    @routes()
</head>
<body class="@yield('body_class')">
@yield('body')

@stack('scripts')
</body>
</html>
