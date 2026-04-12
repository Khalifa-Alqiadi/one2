<?php
$bg_color = Helper::GeneralSiteSettings("style_color2");
$footer_style = "background: ".$bg_color;
if (Helper::GeneralSiteSettings("style_footer_bg") != "") {
    $bg_file = route("fileView", ["path" => 'settings/'.Helper::GeneralSiteSettings("style_footer_bg")]);
    $footer_style = "style='background-image: url($bg_file);'";
}
if (Helper::GeneralSiteSettings("style_footer") != 1) {
    $footer_style = "style=padding:0";
}
$contacts_cols = 3;
if (!Helper::GeneralSiteSettings("style_subscribe")) {
    $contacts_cols = 3;
}
?>
<footer id="footer" {!!  $footer_style !!}>
    @if(Helper::GeneralSiteSettings("style_footer")==1)
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="footer-logo mb-1 text-center">
                            <img alt="{{ Helper::GeneralSiteSettings("site_title_" . @Helper::currentLanguage()->code) }}" src="{{ route("fileView",["path" =>'settings/logo-website.png' ]) }}" class="">
                        </div>
                        @include("frontEnd.layouts.social",["tt_position"=>"top"])
                    </div>
                    @if(Helper::GeneralWebmasterSettings("footer_menu_id") >0)
                            <?php
                            // Get list of footer menu links by group Id
                            $MenuLinks = \App\Helpers\SiteMenu::List(Helper::GeneralWebmasterSettings("footer_menu_id"));
                            $max_menu_cols = 2;
                            $fixed_cols = 0;
                            if (!Helper::GeneralSiteSettings("style_subscribe")) {
                                $max_menu_cols = 4;
                                $fixed_cols = 3;
                            }
                            $mi = 0;
                            ?>
                        @if(count($MenuLinks) <= $max_menu_cols)
                            @foreach($MenuLinks as $MenuLink)
                                <div class="col-lg-{{($fixed_cols >0)?$fixed_cols:(($mi==0)?3:2)}} col-md-6 col-6 footer-links">
                                    <div class="footer-title">
                                        <h3>{{ @$MenuLink->title }}</h3>
                                    </div>
                                    @if(@$MenuLink->sub)
                                        <ul>
                                            @foreach($MenuLink->sub as $SubLink)
                                                <li><a class="nav-link" href="{{ @$SubLink->url }}"
                                                       target="{{ @$SubLink->target }}">{!! (@$SubLink->icon)?"<i class='".@$SubLink->icon."'></i> ":"" !!} {{ @$SubLink->title }}
                                                    </a>
                                                </li>
                                                @if(@$SubLink->sub)
                                                    @foreach($SubLink->sub as $SubLink2)
                                                        <li><a
                                                                class="nav-link"
                                                                href="{{ @$SubLink2->url }}"
                                                                target="{{ @$SubLink2->target }}">
                                                                &nbsp;&nbsp; {!! (@Helper::currentLanguage()->direction=="rtl")?"&#8617;":"&#8618;" !!} {!! (@$SubLink2->icon)?"<i class='".@$SubLink2->icon."'></i> ":"" !!} {{ @$SubLink2->title }}</a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                @php($mi++)
                            @endforeach
                        @elseif(count($MenuLinks) > $max_menu_cols)
                            <div class="col-lg-3 col-md-6 footer-links">
                                <div class="footer-title">
                                    <h3>{{ __('frontend.quickLinks') }}</h3>
                                </div>
                                <ul>
                                    @foreach($MenuLinks as $MenuLink)
                                        <li><a class="nav-link" href="{{ @$MenuLink->url }}"
                                               target="{{ @$MenuLink->target }}">{!! (@$MenuLink->icon)?"<i class='".@$MenuLink->icon."'></i> ":"" !!} {{ @$MenuLink->title }}
                                            </a>
                                        </li>
                                        @if(@$MenuLink->sub)
                                            @foreach($MenuLink->sub as $SubLink)
                                                <li><a
                                                        class="nav-link"
                                                        href="{{ @$SubLink->url }}"
                                                        target="{{ @$SubLink->target }}">
                                                        &nbsp;&nbsp; {!! (@Helper::currentLanguage()->direction=="rtl")?"&#8617;":"&#8618;" !!} {!! (@$SubLink->icon)?"<i class='".@$SubLink->icon."'></i> ":"" !!} {{ @$SubLink->title }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                    @include('frontEnd.layouts.subscribe')
                    <div class="col-md-12">
                        <div class="pt-4 mt-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="me-md-auto text-center">
                                        <div class="copyright">
                                            <?php
                                            $site_title_var = "site_title_".@Helper::currentLanguage()->code;
                                            ?>
                                            {{ __('frontend.AllRightsReserved') }} &copy; <?php echo date("Y") ?>
                                            . {{Helper::GeneralSiteSettings($site_title_var)}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</footer>
@if(Helper::GeneralSiteSettings('whatsapp_no') !="")
    <a href="https://wa.me/{{Helper::GeneralSiteSettings('whatsapp_no')}}" class="whatsapp_float" target="_blank" aria-label="Whatsapp"
       rel="noopener noreferrer">
        <i class="fa fa-whatsapp"></i>
    </a>
@endif
@if (@Auth::check())
    @if(!Helper::GeneralSiteSettings("site_status"))
        <div class="text-center alert alert-warning m-0">
            <div class="h6 mb-0">
                {{__('backend.websiteClosedForVisitors')}}
            </div>
        </div>
    @endif
@endif
