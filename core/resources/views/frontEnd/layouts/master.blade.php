<!DOCTYPE html>
<html class="{{ @Helper::currentLanguage()->code }}" lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">

<head>
    <!-- ======= Meta & CSS ======= -->
    @stack('before-styles')
    @include('frontEnd.layouts.head')
    @include('frontEnd.layouts.colors')
    @yield('headInclude')
    @stack('after-styles')
    @if(Helper::GeneralSiteSettings("css")!="")
        <style type="text/css">
            {!! Helper::GeneralSiteSettings("css") !!}
        </style>
    @endif
    {!! Helper::GeneralSiteSettings("js") !!}
</head>

<body class="dir-{{ @Helper::currentLanguage()->direction }} lang-{{ @Helper::currentLanguage()->code }} {{ (!Helper::GeneralSiteSettings("style_change") && Helper::GeneralSiteSettings("style_type"))?"dark":"" }}">

@if(!@$HideHeader)
<!-- ======= Top Bar ======= -->
{{--@include('frontEnd.layouts.topbar')--}}

<!-- ======= Header ======= -->
@include('frontEnd.layouts.header')
@endif

<!-- ======= Main contents ======= -->
<main id="main" class="{{ (Helper::GeneralSiteSettings("style_header"))?"fixed-top-margin":"" }}">
    @yield('content')
</main>
<!-- ======= Footer ======= -->
@stack('before-footer')

@if(!@$HideFooter)
@include('frontEnd.layouts.footer')
@endif
@include('frontEnd.layouts.mobile-menu')
@if(Helper::GeneralSiteSettings("style_preload"))
    <div id="preloader"></div>
@endif
<!-- ======= JS Including ======= -->
@stack('before-scripts')
@include('frontEnd.layouts.foot')
@yield('footInclude')
@stack('after-scripts')
{!! Helper::GeneralSiteSettings("body") !!}
</body>
</html>
