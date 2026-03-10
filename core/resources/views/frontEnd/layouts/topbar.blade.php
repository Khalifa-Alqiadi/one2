<div id="topbar"
     class="d-flex align-items-center {{ (Helper::GeneralSiteSettings("style_header"))?"fixed-top":"" }} {{ (Helper::GeneralSiteSettings("style_bg_type"))?"topbar-no-bg":"header-bg" }}">
    <div class="container d-flex justify-content-between">
        <div class="contact-info d-flex align-items-center">
            @if(Helper::GeneralSiteSettings("contact_t5") !="")
                <span
                    class="{{ (Helper::GeneralWebmasterSettings("header_search_status") && Helper::GeneralSiteSettings("style_change"))?"d-none d-lg-block":"" }}">
                <i class="fa fa-phone"></i> &nbsp;<a
                        href="tel:{{ Helper::GeneralSiteSettings("contact_t5") }}"><span
                            dir="ltr">{{ Helper::GeneralSiteSettings("contact_t5") }}</span></a>
                    </span>
            @endif
            @if(Helper::GeneralSiteSettings("contact_t6") !="")
                <span class="top-email d-none d-lg-block ">
                    @if(Helper::GeneralSiteSettings("contact_t3") !="")
                        &nbsp; | &nbsp;
                    @endif
                    <i class="fa fa-envelope"></i> &nbsp;<a
                        href="mailto:{{ Helper::GeneralSiteSettings("contact_t6") }}">{{ Helper::GeneralSiteSettings("contact_t6") }}</a>
                    </span>
            @endif
        </div>
        <div class="controls d-flex align-items-center">
            @if(Helper::GeneralWebmasterSettings("header_search_status"))
                <a class="header-search-btn" href="#"><i class="fa fa-search"></i> <span>{{ __('backend.search') }}</span></a>
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

            @if(Helper::GeneralSiteSettings("style_change"))
                <div class="appearance-toggle">
                    <input type="checkbox" class="checkbox" id="appearance-toggle-checkbox">
                    <label for="appearance-toggle-checkbox" class="checkbox-label">
                        <i class="fas fa-moon"></i> <small id="appearance-toggle-title"
                                                           class="title">{{ __('backend.themes1') }}</small>
                        <i class="fas fa-sun"></i>
                        <span class="ball"></span>
                    </label>
                </div>
                <script>
                    if (parseInt(localStorage.getItem('appearance'))) {
                        document.getElementById("appearance-toggle-title").innerHTML = "{{ __('backend.themes3') }}";
                        document.getElementById("appearance-toggle-checkbox").checked = true;
                        document.body.classList.add("dark");
                    } else {
                        document.getElementById("appearance-toggle-title").innerHTML = "{{ __('backend.themes1') }}";
                    }
                    let default_appearance = 0;
                    @if(Helper::GeneralSiteSettings("style_type"))
                    if (localStorage.getItem('appearance') === null) {
                        document.getElementById("appearance-toggle-title").innerHTML = "{{ __('backend.themes3') }}";
                        document.getElementById("appearance-toggle-checkbox").checked = true;
                        document.body.classList.add("dark");
                        default_appearance = 1;
                    }
                    @endif
                    @if(!Helper::GeneralSiteSettings("style_change") && !Helper::GeneralSiteSettings("style_type"))
                    document.body.classList.remove("dark");
                    default_appearance = 0;
                    @endif
                </script>
                @push('after-scripts')
                    <script>
                        $(function () {
                            $('#appearance-toggle-checkbox').change(function () {
                                document.body.classList.toggle("dark");
                                if (parseInt(localStorage.getItem('appearance'))) {
                                    localStorage.setItem('appearance', '0');
                                    $('.appearance-toggle .title').html("{{ __('backend.themes1') }}");
                                } else if (localStorage.getItem('appearance') === null && default_appearance) {
                                    localStorage.setItem('appearance', '0');
                                    $('.appearance-toggle .title').html("{{ __('backend.themes1') }}");
                                } else {
                                    localStorage.setItem('appearance', '1');
                                    $('.appearance-toggle .title').html("{{ __('backend.themes3') }}");
                                }
                            });
                        });
                    </script>
                @endpush
            @endif

            @if(Helper::GeneralWebmasterSettings("dashboard_link_status"))
                @if(Auth::check())
                    <div class="dropdown header-dropdown d-none d-sm-block">
                        <button class="btn dropdown-toggle" type="button" id="dropdownDashboardBtn"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownDashboardBtn">
                            <li><a class="dropdown-item" href="{{ route("adminHome") }}"><i
                                        class="fa fa-cog"></i> {{__('frontend.dashboard')}}</a></li>
                            @if(Auth::user()->permissions ==0 || Auth::user()->permissions ==1)
                                <a class="dropdown-item"
                                   href="{{ route('usersEdit',Auth::user()->id) }}"> <i
                                        class="fa fa-user"></i> {{ __('backend.profile') }}</a>
                            @endif
                            @if(Helper::GeneralWebmasterSettings("inbox_status"))
                                @if(@Auth::user()->permissionsGroup->inbox_status)
                                    <a href="{{ route('webmails') }}" class="dropdown-item">
                                        <i class="fa fa-envelope"></i> {{ __('backend.siteInbox') }}
                                    </a>
                                @endif
                            @endif
                            <a class="dropdown-item" href="{{ route('adminLogout') }}"><i
                                    class="fa fa-sign-out"></i> {{ __('backend.logout') }}</a>
                        </ul>
                    </div>
                @else
                    <span class="d-none d-sm-block dashboard_Link">
                        <a href="{{ route("adminHome") }}" target="_blank"><i
                                class="fa fa-cog"></i> {{__('frontend.dashboard')}}
                        </a>
                    </span>
                @endif
            @endif
            @if(count(Helper::languagesList()) >1)
                <div class="dropdown header-dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownLangBtn"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        @if(@Helper::currentLanguage()->icon !="")
                            <img
                                src="{{ asset('assets/dashboard/images/flags/'.@Helper::currentLanguage()->icon.".svg") }}"
                                alt="{{ @Helper::currentLanguage()->code }}" loading="lazy">
                        @endif
                        {{ @Helper::currentLanguage()->title }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownLangBtn">
                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            <a href="{{ Helper::languageURL($ActiveLanguage->code, @$page_type , @$page_id) }}"
                               class="dropdown-item">
                                @if($ActiveLanguage->icon !="")
                                    <img
                                        src="{{ asset('assets/dashboard/images/flags/'.$ActiveLanguage->icon.".svg") }}"
                                        alt=" {{ $ActiveLanguage->code }}" loading="lazy">
                                @endif
                                {{ $ActiveLanguage->title }}
                            </a>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!Helper::GeneralWebmasterSettings("dashboard_link_status") && count(Helper::languagesList()) ==1)
                @include("frontEnd.layouts.social",["tt_position"=>"bottom"])
            @endif
        </div>
    </div>
</div>
