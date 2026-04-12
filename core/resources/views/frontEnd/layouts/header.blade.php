<header id="header" class="{{ (Helper::GeneralSiteSettings("style_header"))?"fixed-top":"" }} {{ (Helper::GeneralSiteSettings("style_bg_type"))?"header-no-bg":"header-bg" }}">
    <div class="container d-flex align-items-center mt-3 justify-content-between">
        <div class="search-mobile">
            @if(Helper::GeneralWebmasterSettings("header_search_status"))
                <a class="header-search-btn" href="#"><i class="fa fa-search"></i></a>
                <div id="header-search-box">
                    <button type="button" name="close" title="close" class="close"><i class="fas fa-close"></i></button>
                    <form method="GET" action="{{ Helper::sectionURL(1) }}" class="header-form-search">
                        <input type="search" autocomplete="off" name="search_word" value="" required maxlength="50"
                               placeholder="{{ __('backend.typeToSearch') }}"/>

                        <button type="submit" class="btn btn-lg btn-theme"><i
                                class="fas fa-search"></i> {{ __('backend.search') }}</button>
                    </form>
                </div>
                @push('after-scripts')
                    <script>
                        $(function () {
                            $('.header-search-btn').on('click', function (event) {
                                event.preventDefault();
                                $('#header-search-box').addClass('open');
                                $('#header-search-box > form > input[type="search"]').focus();
                            });

                            $('#header-search-box .close').on('click', function () {
                                $("#header-search-box").removeClass('open');
                            });
                        });
                    </script>
                @endpush
            @endif
            <div class="live-header mx-1">
                {{-- @if(Helper::GeneralWebmasterSettings("football_live_status")) --}}
                    <a href="{{ route('live.matches') }}" class="btn-header text-white bg-transparent d-flex align-items-center"
                       style="color: #fff !important;">
                        <img src="{{ URL::to('uploads/settings/live-icon-red1.svg') }}" alt="">
                    </a>
                {{-- @endif --}}
            </div>
        </div>
        <div class="header-content d-flex align-items-center">
            <a class="logo" href="{{ Helper::homeURL() }}">
                @if(Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code) !="")
                    <img alt="{{ Helper::GeneralSiteSettings("site_title_" . @Helper::currentLanguage()->code) }}"
                        src="{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code) ]) }}" class="img-fluid main-logo" width="230" height="50">
                @else
                    <img alt="{{ Helper::GeneralSiteSettings("site_title_" . @Helper::currentLanguage()->code) }}" src="{{ route("fileView",["path" =>'settings/nologo.png' ]) }}" class="img-fluid" width="172" height="50">
                @endif
                {{-- <img src="{{URL::to('uploads/settings/one-two-logo-B.png')}}" class="scroll-logo d-none" alt=""> --}}
                <img src="{{URL::to('uploads/settings/logo-website.png')}}" class="mobile-logo d-none" alt="">
            </a>

            @include('frontEnd.layouts.menu')
        </div>

        <div class="content-bottom d-flex align-items-center">
            <div class="live-header mx-3">
                {{-- @if(Helper::GeneralWebmasterSettings("football_live_status")) --}}
                    <a href="{{ route('live.matches') }}" class="btn-header text-white bg-transparent d-flex align-items-center"
                       style="color: #fff !important;
                       width: 100px;">
                        <img src="{{ URL::to('uploads/settings/live-icon-red1.svg') }}" alt="">
                        {{ __('frontend.live') }}
                    </a>
                {{-- @endif --}}
            </div>
            <div class="header-search mx-2">
                @if(Helper::GeneralWebmasterSettings("header_search_status"))
                    <div id="header-search">
                        <form method="GET" action="{{ Helper::sectionURL(1) }}" class="header-form-search">
                            <input type="search" autocomplete="off" name="search_word" value="" required maxlength="100"
                                />

                            <button type="submit" class=""><i
                                    class="fas fa-search"></i></button>
                        </form>
                    </div>
                @endif
            </div>
            {{-- <div class="header-account mx-3 ">
                <a href="" class="btn-header px-5">{{__('frontend.login')}}</a>
            </div> --}}
            <div class="lang">
                @if(@Helper::currentLanguage()->code == 'ar')
                    <a href="{{Helper::languageURL('en', @$page_type , @$page_id) }}" class="btn-header">
                        <i class="fa-solid fa-globe mx-1"></i>
                        EN
                    </a>
                @else
                    <a href="{{Helper::languageURL('ar', @$page_type , @$page_id) }}">
                        <i class="fa-solid fa-globe mx-1"></i>
                        AR
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
