
@if(!empty($Topic))
        <?php
        $page_form = @$Topic->form;
        $custom_css_code = $Topic->css_code;
        $custom_js_code = $Topic->js_code;
        $custom_body_code = $Topic->body_code;
        ?>
    @if(@$Topic->$details_var !="")
        <section id="welcome" class="content-row-no-bg home-welcome">
            <div class="container">
                {!! @$Topic->$details_var !!}
                @if(!empty($page_form))
                        <?php
                        $form_url = Helper::sectionURL($page_form->id);
                        ?>
                    <div class="text-center mt-3">
                        <a href="{{ $form_url }}" class="btn btn-lg btn-primary">
                            <i class="fa-solid fa-send-o"></i> {{ __('backend.submit') }} {!!  $page_form->{"title_".@Helper::currentLanguage()->code} !!}
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif
    @if($custom_css_code !="" || $custom_js_code !="")
        @push('after-styles')
            @if($custom_css_code !="")
                <style>
                    {!! $custom_css_code !!}
                </style>
            @endif
            {!! $custom_js_code !!}
        @endpush
    @endif
    @if($custom_body_code !="")
        @push('before-footer')
            {!! Blade::render($custom_body_code) !!}
        @endpush
    @endif
@endif}}

<?php
$TextBanners = Helper::BannersList(Helper::GeneralWebmasterSettings("home_text_banners_section_id"));
?>
@if(count($TextBanners)>0)
    @foreach($TextBanners->slice(0,1) as $TextBanner)
            <?php
            try {
                $TextBanner_type = $TextBanner->webmasterBanner->type;
            } catch (Exception $e) {
                $TextBanner_type = 0;
            }
            ?>
    @endforeach
        <?php
        $title_var = "title_".@Helper::currentLanguage()->code;
        $title_var2 = "title_".config('smartend.default_language');
        $details_var = "details_".@Helper::currentLanguage()->code;
        $details_var2 = "details_".config('smartend.default_language');
        $file_var = "file_".@Helper::currentLanguage()->code;
        $file_var2 = "file_".config('smartend.default_language');
        $link_var = "link_".@Helper::currentLanguage()->code;

        $col_width = 12;
        if (count($TextBanners) == 2) {
            $col_width = 6;
        }
        if (count($TextBanners) == 3) {
            $col_width = 4;
        }
        if (count($TextBanners) > 3) {
            $col_width = 3;
        }
        ?>
    <section id="services" class="services">
        <div class="container">
            <div class="row">
                @foreach($TextBanners as $TextBanner)
                        <?php
                        if ($TextBanner->$title_var != "") {
                            $BTitle = $TextBanner->$title_var;
                        } else {
                            $BTitle = $TextBanner->$title_var2;
                        }
                        if ($TextBanner->$details_var != "") {
                            $BDetails = $TextBanner->$details_var;
                        } else {
                            $BDetails = $TextBanner->$details_var2;
                        }
                        if ($TextBanner->$file_var != "") {
                            $BFile = $TextBanner->$file_var;
                        } else {
                            $BFile = $TextBanner->$file_var2;
                        }
                        ?>
                    <div class="col-lg-{{$col_width}} col-md-6 d-flex align-items-stretch mb-3">

                        @if($TextBanner->$link_var !="")
                            <a href="{!! $TextBanner->$link_var !!}">
                                @endif
                                <div class="icon-box">
                                    @if($TextBanner->code !="")
                                        {!! $TextBanner->code !!}
                                    @else

                                        @if($TextBanner->icon !="")
                                            <div class="icon">
                                                <i class="{{$TextBanner->icon}} fa-3x"></i>
                                            </div>
                                        @elseif($BFile !="")
                                            <img src="{{ route("fileView",["path" =>'banners/'.$BFile ]) }}" loading="lazy"
                                                 alt="{{ $BTitle }}"/>
                                        @endif
                                        <h2>{!! $BTitle !!}</h2>
                                        @if($BDetails !="")
                                            <p>{!! nl2br($BDetails) !!}</p>
                                        @endif

                                    @endif
                                </div>
                                @if($TextBanner->$link_var !="")
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif
