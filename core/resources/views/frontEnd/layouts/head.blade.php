<meta charset="utf-8">
<title>{{(@$PageTitle !="")? @$PageTitle:Helper::GeneralSiteSettings("site_title_" . @Helper::currentLanguage()->code)}}</title>
<meta name="description" content="{{(@$PageDescription !="")? @$PageDescription:Helper::GeneralSiteSettings("site_desc_" . @Helper::currentLanguage()->code)}}"/>
<meta name="keywords" content="{{(@$PageKeywords !="")? @$PageKeywords:Helper::GeneralSiteSettings("site_keywords_" . @Helper::currentLanguage()->code)}}"/>
<meta name="author" content="{{ URL::to('') }}"/>
<?php if (!request()->hasCookie('user_timezone')) {
        Helper::detectAndStoreUserTimezone();
    }
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="{{ URL::asset('assets/frontend/vendor/fontawesome/css/all.min.css') }}?v={{ Helper::system_version() }}"  rel="stylesheet" media/>
<link href="{{ URL::asset('assets/frontend/vendor/fontawesome/css/font-awesome.min.css') }}?v={{ Helper::system_version() }}" rel="stylesheet" media/>
<link href="{{ URL::asset('assets/frontend/vendor/animate.css/animate.min.css') }}?v={{ Helper::system_version() }}" rel="stylesheet">
<link href="{{ URL::asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}?v={{ Helper::system_version() }}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}?v={{ Helper::system_version() }}" media rel="stylesheet"/>

<link rel="stylesheet" href="{{ URL::asset('assets/frontend/vendor/glightbox/css/glightbox.min.css') }}?v={{ Helper::system_version() }}">
<link rel="stylesheet" href="{{ URL::asset('assets/frontend/vendor/swiper/swiper-bundle.min.css') }}?v={{ Helper::system_version() }}">

<link rel="stylesheet" href="{{ URL::asset('assets/frontend/vendor/owl-carousel/assets/owl.carousel.min.css') }}?v={{ Helper::system_version() }}">
<link rel="stylesheet" href="{{ URL::asset('assets/frontend/vendor/owl-carousel/assets/owl.theme.default.min.css') }}?v={{ Helper::system_version() }}">

<link href="{{ URL::asset('assets/frontend/css/style.css') }}?v=2" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/css/custom.css') }}?v=89" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/css/matches.css') }}?v=21" rel="stylesheet"/>


@if( @Helper::currentLanguage()->direction=="rtl")
<link href="{{ URL::asset('assets/frontend/css/rtl.css') }}?v=18" rel="stylesheet"/>
@endif

<!-- Favicon and Touch Icons -->
@if(Helper::GeneralSiteSettings("style_fav") !="")
    <link href="{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_fav") ]) }}" rel="shortcut icon"
          type="image/png">
@else
    <link href="{{ route("fileView",["path" =>'settings/nofav.png' ]) }}" rel="shortcut icon" type="image/png">
@endif
@if(Helper::GeneralSiteSettings("style_apple") !="")
    <link href="{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_apple") ]) }}" rel="apple-touch-icon">
    <link href="{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_apple") ]) }}" rel="apple-touch-icon"
          sizes="72x72">
    <link href="{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_apple") ]) }}" rel="apple-touch-icon"
          sizes="114x114">
    <link href="{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_apple") ]) }}" rel="apple-touch-icon"
          sizes="144x144">
@else
    <link href="{{ route("fileView",["path" =>'settings/nofav.png' ]) }}" rel="apple-touch-icon">
    <link href="{{ route("fileView",["path" =>'settings/nofav.png' ]) }}" rel="apple-touch-icon" sizes="72x72">
    <link href="{{ route("fileView",["path" =>'settings/nofav.png' ]) }}" rel="apple-touch-icon" sizes="114x114">
    <link href="{{ route("fileView",["path" =>'settings/nofav.png' ]) }}" rel="apple-touch-icon" sizes="144x144">
@endif

<meta property='og:title'
      content='{{@$PageTitle}} {{(@$PageTitle =="")? Helper::GeneralSiteSettings("site_title_" . @Helper::currentLanguage()->code):""}}'/>
@if(@$Topic->photo_file !="")
    <meta property='og:image' content='{{ route("fileView",["path" =>'topics/'.@$Topic->photo_file ]) }}'/>
@elseif(Helper::GeneralSiteSettings("style_apple") !="")
    <meta property='og:image'
          content='{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_apple") ]) }}'/>
@else
    <meta property='og:image'
          content='{{ route("fileView",["path" =>'settings/nofav.png' ]) }}'/>
@endif
<meta property="og:site_name" content="{{ Helper::GeneralSiteSettings("site_title_" . @Helper::currentLanguage()->code) }}">
<meta property="og:description" content="{{@$PageDescription}}"/>
<meta property="og:url" content="{{ url()->full()  }}"/>
<meta property="og:type" content="website"/>

<link rel="canonical" href="{{ url()->current() }}">

{{-- Google Tags and google analytics --}}
@if(@Helper::GeneralWebmasterSettings("google_tags_status") && @Helper::GeneralWebmasterSettings("google_tags_id") !="")
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{!! @Helper::GeneralWebmasterSettings("google_tags_id") !!}');</script>
    <!-- End Google Tag Manager -->
@endif
