<!DOCTYPE html>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">

<head>
    <!-- ======= Meta & CSS ======= -->
    @stack('before-styles')
    @include('frontEnd.layouts.head')
    @include('frontEnd.layouts.colors')
    @yield('headInclude')
    @stack('after-styles')
</head>

<body class="maintenance-mode dir-{{ @Helper::currentLanguage()->direction }} lang-{{ @Helper::currentLanguage()->code }}">
<!-- ======= Main contents ======= -->
<main id="main" class="{{ (Helper::GeneralSiteSettings("style_header"))?"fixed-top-margin":"" }}">
    {!! @$close_message !!}
</main>
@if(Helper::GeneralSiteSettings("style_preload"))
    <div id="preloader"></div>
@endif
<!-- ======= JS Including ======= -->
@stack('before-scripts')
@include('frontEnd.layouts.foot')
@yield('footInclude')
@stack('after-scripts')
</body>
</html>
