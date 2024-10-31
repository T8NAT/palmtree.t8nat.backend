<!DOCTYPE html>
@php
    $menuFixed = $configData['layout'] === 'vertical' ? $menuFixed ?? '' : ($configData['layout'] === 'front' ? '' : $configData['headerType']);
    $navbarType = $configData['layout'] === 'vertical' ? $configData['navbarType'] ?? '' : ($configData['layout'] === 'front' ? 'layout-navbar-fixed' : '');
    $isFront = ($isFront ?? '') == true ? 'Front' : '';
    $contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';
@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
    class="{{ $configData['style'] }}-style {{ $contentLayout ?? '' }} {{ $navbarType ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $menuFlipped ?? '' }} {{ $menuOffcanvas ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
    dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}"
    data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{ url('/') }}" data-framework="laravel"
    data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['styleOpt'] }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') |
        {{ config('variables.templateName') ? config('variables.templateName') : 'TemplateName' }}
    </title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />



    <!-- Include Styles -->
    <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
    @include('layouts/sections/styles' . $isFront)

    <!-- Include Scripts for customizer, helper, analytics, config -->
    <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
    @include('layouts/sections/scriptsIncludes' . $isFront)
    <style>
        .swal2-icon.swal2-success{
            font-size: 10px;
        }
        .swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation{
            z-index: 100000;
        }
        .menu-vertical .menu-inner>.menu-item{
            padding: 10px;
        }
        .bg-menu-theme.menu-vertical .menu-item>.menu-link:not(.menu-toggle){
            padding: 15px;
        }
        .bg-menu-theme.menu-vertical .menu-item>.menu-link{
            font-weight: bold;
        }
        .bg-menu-theme .menu-inner .menu-item:not(.active) .menu-link{
            background-color: #eeeeee85;
            padding: 17px;
        }
        .menu-sub .menu-item .menu-link{
            padding: 10px !important;
            font-weight: 500 !important;
        }
        .bg-menu-theme .menu-sub>.menu-item>.menu-link:before{
            padding: 0px 10px;
            position: initial;
        }
        .menu-vertical .app-brand{
            height: 106px !important;
        }
        .app-brand-logo.demo{
            width: 235px !important;
            height: 100px !important;
            margin-top: 7px !important;
        }
        
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: "Tajawal", sans-serif !important;
        }
        .bg-menu-theme .menu-link,
        .bg-menu-theme .menu-inner .menu-item .menu-link
        .bg-menu-theme .menu-link:hover, .bg-menu-theme .menu-link:focus, .bg-menu-theme .menu-horizontal-prev:hover, .bg-menu-theme .menu-horizontal-prev:focus, .bg-menu-theme .menu-horizontal-next:hover, .bg-menu-theme .menu-horizontal-next:focus {
            color: #000000 !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    @stack('style')
</head>
    <body>
        <!-- Layout Content -->
        @yield('layoutContent')
        <!--/ Layout Content -->



        <!-- Include Scripts -->
        <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
        @include('layouts/sections/scripts' . $isFront)
        @include('sweetalert::alert')
        <!-- Add this to your HTML head section -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        @stack('footer_script')
    </body>

</html>
