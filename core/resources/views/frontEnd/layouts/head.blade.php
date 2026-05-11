<meta charset="utf-8">
<title>
    {{ @$PageTitle != '' ? @$PageTitle : Helper::GeneralSiteSettings('site_title_' . @Helper::currentLanguage()->code) }}
</title>
<meta name="description"
    content="{{ @$PageDescription != '' ? @$PageDescription : Helper::GeneralSiteSettings('site_desc_' . @Helper::currentLanguage()->code) }}" />
<meta name="keywords"
    content="{{ @$PageKeywords != '' ? @$PageKeywords : Helper::GeneralSiteSettings('site_keywords_' . @Helper::currentLanguage()->code) }}" />
<meta name="author" content="{{ URL::to('') }}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@if (!request()->hasCookie('user_timezone'))
    <script>
        (function() {
            try {
                var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                if (timezone) {
                    document.cookie = 'user_timezone=' + encodeURIComponent(timezone) +
                        ';path=/;max-age=2592000;SameSite=Lax';
                }
            } catch (e) {}
        })();
    </script>
@endif

{{-- Google Fonts Preconnect --}}
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

{{-- Google Fonts - Non-blocking --}}
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400&display=swap" as="style"
    onload="this.onload=null;this.rel='stylesheet'" />
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" as="style"
    onload="this.onload=null;this.rel='stylesheet'" />
<noscript>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet" />
</noscript>

{{-- Bootstrap - Non-blocking --}}
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript>
    <link rel="stylesheet"
        href="{{ URL::asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}?v={{ Helper::system_version() }}" />
</noscript>

{{-- Vendor CSS - Non-blocking --}}
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/fontawesome/css/all.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/fontawesome/css/font-awesome.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/animate.css/animate.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/glightbox/css/glightbox.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/swiper/swiper-bundle.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/owl-carousel/assets/owl.carousel.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
    href="{{ URL::asset('assets/frontend/vendor/owl-carousel/assets/owl.theme.default.min.css') }}?v={{ Helper::system_version() }}"
    as="style" onload="this.onload=null;this.rel='stylesheet'">

{{-- Vendor CSS Noscript Fallback --}}
<noscript>
    <link
        href="{{ URL::asset('assets/frontend/vendor/fontawesome/css/all.min.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
    <link
        href="{{ URL::asset('assets/frontend/vendor/fontawesome/css/font-awesome.min.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
    <link
        href="{{ URL::asset('assets/frontend/vendor/animate.css/animate.min.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
    <link
        href="{{ URL::asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
    <link
        href="{{ URL::asset('assets/frontend/vendor/glightbox/css/glightbox.min.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
    <link
        href="{{ URL::asset('assets/frontend/vendor/swiper/swiper-bundle.min.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
    <link
        href="{{ URL::asset('assets/frontend/vendor/owl-carousel/assets/owl.carousel.min.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
    <link
        href="{{ URL::asset('assets/frontend/vendor/owl-carousel/assets/owl.theme.default.min.css') }}?v={{ Helper::system_version() }}"
        rel="stylesheet">
</noscript>

{{-- App CSS - Non-blocking --}}
<link rel="preload" href="{{ URL::asset('assets/frontend/css/style.css') }}?v=2" as="style"
    onload="this.onload=null;this.rel='stylesheet'" />
<link rel="preload" href="{{ URL::asset('assets/frontend/css/custom.css') }}?v=94" as="style"
    onload="this.onload=null;this.rel='stylesheet'" />
<link rel="preload" href="{{ URL::asset('assets/frontend/css/matches.css') }}?v=26" as="style"
    onload="this.onload=null;this.rel='stylesheet'" />

@if (@Helper::currentLanguage()->direction == 'rtl')
    <link rel="preload" href="{{ URL::asset('assets/frontend/css/rtl.css') }}?v=19" as="style"
        onload="this.onload=null;this.rel='stylesheet'" />
@endif

{{-- App CSS Noscript Fallback --}}
<noscript>
    <link href="{{ URL::asset('assets/frontend/css/style.css') }}?v=2" rel="stylesheet" />
    <link href="{{ URL::asset('assets/frontend/css/custom.css') }}?v=94" rel="stylesheet" />
    <link href="{{ URL::asset('assets/frontend/css/matches.css') }}?v=26" rel="stylesheet" />
    @if (@Helper::currentLanguage()->direction == 'rtl')
        <link href="{{ URL::asset('assets/frontend/css/rtl.css') }}?v=19" rel="stylesheet" />
    @endif
</noscript>

{{-- loadCSS Polyfill for Safari & older browsers --}}
<script>
    ! function(n) {
        "use strict";
        n.loadCSS || (n.loadCSS = function() {});
        var o = loadCSS.relpreload = {};
        o.support = function() {
            var e = {};
            try {
                e = n.document.createElement("link").relList.supports("preload")
            } catch (t) {
                e = !1
            }
            return function() {
                return e
            }
        }();
        o.bindMediaToggle = function(t) {
            var e = t.media || "all";

            function a() {
                t.addEventListener ? t.removeEventListener("load", a) : t.attachEvent && t.detachEvent("onload", a);
                t.setAttribute("onload", null);
                t.media = e
            }
            t.addEventListener ? t.addEventListener("load", a) : t.attachEvent && t.attachEvent("onload", a);
            setTimeout(function() {
                t.rel = "stylesheet";
                t.media = "only x"
            });
            setTimeout(a, 3e3)
        };
        o.poly = function() {
            if (!o.support())
                for (var t = n.document.getElementsByTagName("link"), e = 0; e < t.length; e++) {
                    var a = t[e];
                    "preload" !== a.rel || "style" !== a.getAttribute("as") || a.getAttribute("data-loadcss") || (a
                        .setAttribute("data-loadcss", !0), o.bindMediaToggle(a))
                }
        };
        !o.support() && n.setInterval(o.poly, 500);
        n.addEventListener ? n.addEventListener("load", o.poly) : n.attachEvent && n.attachEvent("onload", o.poly);
        if ("true" === n.document.readyState || "complete" === n.document.readyState) o.poly()
    }(this);
</script>

<!-- Favicon and Touch Icons -->
@if (Helper::GeneralSiteSettings('style_fav') != '')
    <link href="{{ route('fileView', ['path' => 'settings/' . Helper::GeneralSiteSettings('style_fav')]) }}"
        rel="shortcut icon" type="image/png">
@else
    <link href="{{ route('fileView', ['path' => 'settings/nofav.png']) }}" rel="shortcut icon" type="image/png">
@endif
@if (Helper::GeneralSiteSettings('style_apple') != '')
    <link href="{{ route('fileView', ['path' => 'settings/' . Helper::GeneralSiteSettings('style_apple')]) }}"
        rel="apple-touch-icon">
    <link href="{{ route('fileView', ['path' => 'settings/' . Helper::GeneralSiteSettings('style_apple')]) }}"
        rel="apple-touch-icon" sizes="72x72">
    <link href="{{ route('fileView', ['path' => 'settings/' . Helper::GeneralSiteSettings('style_apple')]) }}"
        rel="apple-touch-icon" sizes="114x114">
    <link href="{{ route('fileView', ['path' => 'settings/' . Helper::GeneralSiteSettings('style_apple')]) }}"
        rel="apple-touch-icon" sizes="144x144">
@else
    <link href="{{ route('fileView', ['path' => 'settings/nofav.png']) }}" rel="apple-touch-icon">
    <link href="{{ route('fileView', ['path' => 'settings/nofav.png']) }}" rel="apple-touch-icon" sizes="72x72">
    <link href="{{ route('fileView', ['path' => 'settings/nofav.png']) }}" rel="apple-touch-icon" sizes="114x114">
    <link href="{{ route('fileView', ['path' => 'settings/nofav.png']) }}" rel="apple-touch-icon" sizes="144x144">
@endif

<meta property='og:title'
    content='{{ @$PageTitle }} {{ @$PageTitle == '' ? Helper::GeneralSiteSettings('site_title_' . @Helper::currentLanguage()->code) : '' }}' />
@if (@$Topic->photo_file != '')
    <meta property='og:image' content='{{ route('fileView', ['path' => 'topics/' . @$Topic->photo_file]) }}' />
@elseif(Helper::GeneralSiteSettings('style_apple') != '')
    <meta property='og:image'
        content='{{ route('fileView', ['path' => 'settings/' . Helper::GeneralSiteSettings('style_apple')]) }}' />
@else
    <meta property='og:image' content='{{ route('fileView', ['path' => 'settings/nofav.png']) }}' />
@endif
<meta property="og:site_name"
    content="{{ Helper::GeneralSiteSettings('site_title_' . @Helper::currentLanguage()->code) }}">
<meta property="og:description" content="{{ @$PageDescription }}" />
<meta property="og:url" content="{{ url()->full() }}" />
<meta property="og:type" content="website" />

<link rel="canonical" href="{{ url()->current() }}">

{{-- Google Tags and google analytics --}}
@if (
    @Helper::GeneralWebmasterSettings('google_tags_status') &&
        @Helper::GeneralWebmasterSettings('google_tags_id') != '')
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{!! @Helper::GeneralWebmasterSettings('google_tags_id') !!}');
    </script>
    <!-- End Google Tag Manager -->
@endif
