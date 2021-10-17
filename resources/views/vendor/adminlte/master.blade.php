<!DOCTYPE html>
<html lang = "{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <meta name = "csrf-token" content = "{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel = "stylesheet" href = "{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel = "stylesheet" href = "{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel = "stylesheet" href = "{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        <link rel = "stylesheet"
              href = "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @else
        <link rel = "stylesheet" href = "{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel = "shortcut icon" href = "{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel = "shortcut icon" href = "{{ asset('favicons/favicon.ico') }}" />
        <link rel = "apple-touch-icon" sizes = "57x57" href = "{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel = "apple-touch-icon" sizes = "60x60" href = "{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel = "apple-touch-icon" sizes = "72x72" href = "{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel = "apple-touch-icon" sizes = "76x76" href = "{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel = "apple-touch-icon" sizes = "114x114" href = "{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel = "apple-touch-icon" sizes = "120x120" href = "{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel = "apple-touch-icon" sizes = "144x144" href = "{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel = "apple-touch-icon" sizes = "152x152" href = "{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel = "apple-touch-icon" sizes = "180x180" href = "{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel = "icon" type = "image/png" sizes = "16x16" href = "{{ asset('favicons/favicon-16x16.png') }}">
        <link rel = "icon" type = "image/png" sizes = "32x32" href = "{{ asset('favicons/favicon-32x32.png') }}">
        <link rel = "icon" type = "image/png" sizes = "96x96" href = "{{ asset('favicons/favicon-96x96.png') }}">
        <link rel = "icon" type = "image/png" sizes = "192x192"
              href = "{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel = "manifest" href = "{{ asset('favicons/manifest.json') }}">
        <meta name = "msapplication-TileColor" content = "#ffffff">
        <meta name = "msapplication-TileImage" content = "{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif


    {{--   <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href = "https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" rel = "stylesheet">

</head>

<body class = "@yield('classes_body')" @yield('body_data')>

{{-- Body Content --}}
@yield('body')

{{-- Base Scripts --}}
@if(!config('adminlte.enabled_laravel_mix'))
    <script src = "{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src = "{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src = "{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    {{-- Configured Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    <script src = "{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@else
    <script src = "{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
@endif

{{-- Livewire Script --}}
@if(config('adminlte.livewire'))
    @if(app()->version() >= 7)
        @livewireScripts
    @else
        <livewire:scripts />
    @endif
@endif

{{-- Custom Scripts --}}
@yield('adminlte_js')
<script src = "{{ mix('js/app.js') }}"></script>
<script src = "{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>


<script src = "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity = "sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin = "anonymous"></script>
{{--
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
--}}
<script>
    $('#flash-overlay-modal').modal();
</script>


<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
<script src = "https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

@stack('datatable_script')

</body>

</html>
