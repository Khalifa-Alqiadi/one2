@php
$MenuLinks = \App\Helpers\SiteMenu::List(Helper::GeneralWebmasterSettings("header_menu_id"));
$i = 0;
@endphp
<header id="header" class="one-two-header {{ (Helper::GeneralSiteSettings("style_header"))?"fixed-top":"" }} {{ (Helper::GeneralSiteSettings("style_bg_type"))?"header-no-bg":"header-bg" }}">
    <nav class="one-two-header__inner" aria-label="Main navigation">
        @if(count($MenuLinks)>0)
            @foreach($MenuLinks as $MenuLink)
                @if($i == 3)
                    <a href="{{ Helper::homeURL() }}" class="one-two-header__brand" aria-label="One Two home">
                        <img src="{{ URL::to('uploads/settings/'.Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code)) }}" alt="One Two">
                    </a>
                @endif
                @php $i++; @endphp
                <a href="{{ @$MenuLink->url }}" class="one-two-header__item {{ \App\Helpers\SiteMenu::ActiveLink(url()->current(),@$MenuLink,@$WebmasterSection) }}" target="{{ @$MenuLink->target }}" aria-label="{{ @$MenuLink->title }}">
                    <img src="{{URL::to('uploads/settings/'.@$MenuLink->id . '.svg')}}" alt="{{ @$MenuLink->title }}">
                    <span class="visually-hidden">{{ @$MenuLink->title }}</span>
                </a>
            @endforeach
        @endif

    </nav>
    {{-- <nav class="one-two-header__inner" aria-label="Main navigation">
        <a href="{{ Helper::homeURL() }}" class="one-two-header__item" aria-label="Home">
            <img src="{{URL::to('uploads/settings/home.svg')}}" alt="">
            <span class="visually-hidden">{{__('frontend.home')}}</span>
        </a>
        <a href="{{ route('matches') }}" class="one-two-header__item" aria-label="Fixtures">
            <img src="{{URL::to('uploads/settings/fixtures.svg')}}" alt="">
            <span class="visually-hidden">{{__('frontend.matches')}}</span>
        </a>
        <a href="{{ Helper::sectionURL(5) }}" class="one-two-header__item" aria-label="Videos">
            <img src="{{URL::to('uploads/settings/videos.svg')}}" alt="Videos">
            <span class="visually-hidden">{{__('frontend.videos')}}</span>
        </a>
        <a href="{{ Helper::homeURL() }}" class="one-two-header__brand" aria-label="One Two home">
            <img src="{{ URL::to('uploads/settings/'.Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code)) }}" alt="One Two">
        </a>
        <a href="{{ Helper::sectionURL(3) }}" class="one-two-header__item" aria-label="News">
            <img src="{{URL::to('uploads/settings/news_cat.svg')}}" alt="News">
            <span class="visually-hidden">{{__('frontend.news')}}</span>
        </a>
        <a href="{{ route('leagues.show') }}" class="one-two-header__item" aria-label="Leagues">
            <img src="{{URL::to('uploads/settings/major_competitions2.svg')}}" alt="Leagues">
            <span class="visually-hidden">{{__('frontend.leagues')}}</span>
        </a>
        <a href="{{ route('live.matches') }}" class="one-two-header__item" aria-label="Live matches">
            <img src="{{URL::to('uploads/settings/live.svg')}}" alt="Live matches">
            <span class="visually-hidden">{{__('frontend.live_matches')}}</span>
        </a>
    </nav> --}}
</header>
