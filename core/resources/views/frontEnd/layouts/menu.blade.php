@if(Helper::GeneralWebmasterSettings("header_menu_id") >0)
    <?php
    // Get list of main menu links
    $MenuLinks = \App\Helpers\SiteMenu::List(Helper::GeneralWebmasterSettings("header_menu_id"));
    ?>
    @if(count($MenuLinks)>0)
        <nav id="navbar" class="navbar order-last order-lg-0 ">
            <ul>
                @foreach($MenuLinks as $MenuLink)
                    <li class="{{ (@$MenuLink->sub)?"dropdown":"" }}"><a
                            class="nav-link {{ \App\Helpers\SiteMenu::ActiveLink(url()->current(),@$MenuLink,@$WebmasterSection) }}"
                            href="{{ @$MenuLink->url }}" target="{{ @$MenuLink->target }}">
                            {!! (@$MenuLink->icon)?"<i class='".@$MenuLink->icon."'></i> ":"" !!} {{ @$MenuLink->title }}
                            @if(@$MenuLink->sub)
                                <i class="drop-arrow bi bi-chevron-down"></i>
                            @endif
                        </a>

                        @if(@$MenuLink->sub)
                            <ul>
                                @foreach($MenuLink->sub as $SubLink)
                                    <li class="{{ (@$SubLink->sub)?"dropdown":"" }}"><a class="nav-link"
                                                                                        href="{{ @$SubLink->url }}"
                                                                                        target="{{ @$SubLink->target }}">{!! (@$SubLink->icon)?"<i class='".@$SubLink->icon."'></i> ":"" !!} {{ @$SubLink->title }}
                                            @if(@$SubLink->sub)
                                                <i class="drop-arrow bi bi-chevron-{{ @Helper::currentLanguage()->right }}"></i>
                                            @endif
                                        </a>
                                        @if(@$SubLink->sub)
                                            <ul>
                                                @foreach($SubLink->sub as $SubLink2)
                                                    <li class="{{ (@$SubLink2->sub)?"dropdown":"" }}"><a
                                                            class="nav-link"
                                                            href="{{ @$SubLink2->url }}"
                                                            target="{{ @$SubLink2->target }}">{!! (@$SubLink2->icon)?"<i class='".@$SubLink2->icon."'></i> ":"" !!} {{ @$SubLink2->title }}
                                                            @if(@$SubLink2->sub)
                                                                <i class="drop-arrow bi bi-chevron-{{ @Helper::currentLanguage()->right }}"></i>
                                                            @endif
                                                        </a>
                                                        @if(@$SubLink2->sub)
                                                            <ul>
                                                                @foreach($SubLink2->sub as $SubLink3)
                                                                    <li><a
                                                                            class="nav-link"
                                                                            href="{{ @$SubLink3->url }}"
                                                                            target="{{ @$SubLink3->target }}">{!! (@$SubLink3->icon)?"<i class='".@$SubLink3->icon."'></i> ":"" !!} {{ @$SubLink3->title }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <li class="d-block d-md-none">
                    <a href="" class="nav-link">{{__('frontend.login')}}</a>
                </li>
                <li class="d-block d-md-none">
                    @if(@Helper::currentLanguage()->code == 'ar')
                        <a href="{{Helper::languageURL('en', @$page_type , @$page_id) }}" class="nav-link">English</a>
                    @else
                        <a href="{{Helper::languageURL('ar', @$page_type , @$page_id) }}" class="nav-link">عربي</a>
                    @endif
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        
    @endif
@endif
