@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <?php
        $title_var = "title_".@Helper::currentLanguage()->code;
        $title_var2 = "title_".config('smartend.default_language');

        $webmaster_section_title = "";
        $category_title = "";
        $page_title = "";
        $category_image = "";

        $custom_css_code = @$WebmasterSection->css_code;
        $custom_js_code = @$WebmasterSection->js_code;
        $custom_body_code = @$WebmasterSection->body_code;

        if (@$WebmasterSection != "none") {
            if (@$WebmasterSection->$title_var != "") {
                $webmaster_section_title = @$WebmasterSection->$title_var;
            } else {
                $webmaster_section_title = @$WebmasterSection->$title_var2;
            }
            $page_title = $webmaster_section_title;
            if (@$WebmasterSection->photo != "") {
                $category_image = route("fileView",["path" =>'topics/'.@$WebmasterSection->photo ]);
            }
        }
        if (@$CurrentCategory != "none") {
            if (!empty(@$CurrentCategory)) {
                if (@$CurrentCategory->$title_var != "") {
                    $category_title = @$CurrentCategory->$title_var;
                } else {
                    $category_title = @$CurrentCategory->$title_var2;
                }
                $page_title = $category_title;
                if (@$CurrentCategory->photo != "") {
                    $category_image = route("fileView",["path" =>'sections/'.@$CurrentCategory->photo ]);
                }

                $custom_css_code .= @$CurrentCategory->css_code;
                $custom_js_code .= @$CurrentCategory->js_code;
                $custom_body_code .= @$CurrentCategory->body_code;

            }
        }
        ?>
        <?php
        /*
        @if($category_image !="")
            @include("frontEnd.topic.cover")
        @endif
         */
        ?>
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>{{ $page_title }}</h2>
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __("backend.home") }}</a></li>
                        @if($webmaster_section_title !="")
                            <li class="active">{!! $webmaster_section_title !!}</li>
                        @elseif(@$search_word!="")
                            <li class="active">{{ @$search_word }}</li>
                        @else
                            <li class="active">{{ @$User->name }}</li>
                        @endif
                        @if($category_title !="")
                            <li class="active">{{ $category_title }}
                            </li>
                        @endif
                    </ol>
                </div>

            </div>
        </section>
        <section id="content">
            <?php
            $title_var = "title_".@Helper::currentLanguage()->code;
            $title_var2 = "title_".config('smartend.default_language');
            $details_var = "details_".@Helper::currentLanguage()->code;
            $details_var2 = "details_".config('smartend.default_language');
            $slug_var = "seo_url_slug_".@Helper::currentLanguage()->code;
            $slug_var2 = "seo_url_slug_".config('smartend.default_language');
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-5 col-sm-12 col-xs-12">
                        <div class="page-sidebar">
                            <div class="fixed-area-menu" id="fixed-area-menu">
                                @if(count($Categories) >0)
                                    <div>
                                        @foreach($Categories as $Category)
                                                <?php
                                                if ($Category->$title_var != "") {
                                                    $Category_title = $Category->$title_var;
                                                } else {
                                                    $Category_title = $Category->$title_var2;
                                                }
                                                $Topics = Helper::Topics(@$WebmasterSection->id, $Category->id, 0);
                                                ?>
                                            <div class="mb-4">
                                                <a href="#{{ $Category->$slug_var }}">
                                                    <h5>
                                                        @if($Category->icon !="")
                                                            <i class="{{$Category->icon}}"></i>
                                                        @endif
                                                        {{ $Category_title }}
                                                    </h5>
                                                </a>
                                                <div class="border-start px-3 ms-2">
                                                    @foreach($Topics as $Topic)
                                                            <?php
                                                            if ($Topic->$title_var != "") {
                                                                $title = $Topic->$title_var;
                                                            } else {
                                                                $title = $Topic->$title_var2;
                                                            }
                                                            $div_id = $Topic->$slug_var;
                                                            ?>
                                                        <div class="opacity-75 mb-2">
                                                            <a href="#{{ $div_id }}">
                                                                @if($Topic->icon !="")
                                                                    <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                                @endif
                                                                {{ $title }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                        <?php
                                        $Topics = Helper::Topics(@$WebmasterSection->id, 0, 0);
                                        ?>
                                    @foreach($Topics as $Topic)
                                            <?php
                                            if ($Topic->$title_var != "") {
                                                $title = $Topic->$title_var;
                                            } else {
                                                $title = $Topic->$title_var2;
                                            }
                                            $div_id = $Topic->$slug_var;
                                            ?>
                                        <div class="mb-2">
                                            <a href="#{{ $div_id }}">
                                                @if($Topic->icon !="")
                                                    <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                @endif
                                                {{ $title }}
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-7 col-sm-12 col-xs-12">
                        <div class="row">
                            @if(count($Categories) >0)
                                @foreach($Categories as $Category)
                                        <?php
                                        if ($Category->$title_var != "") {
                                            $Category_title = $Category->$title_var;
                                        } else {
                                            $Category_title = $Category->$title_var2;
                                        }
                                        ?>
                                    <div class="col-lg-12 col-sm-12">
                                        <h2 class="mb-4 text-primary" id="{{ $Category->$slug_var }}">
                                            @if($Category->icon !="")
                                                <i class="{{$Category->icon}}"></i> &nbsp;
                                            @endif
                                            {{ $Category_title }}
                                        </h2>
                                    </div>
                                        <?php
                                        $i = 0;
                                        $Topics = Helper::Topics(@$WebmasterSection->id, $Category->id, 0);
                                        ?>
                                    @if($Topics->count() > 0)
                                        @foreach($Topics as $Topic)
                                                <?php
                                                if ($i == 1) {
                                                    echo "</div><div class='row'>";
                                                    $i = 0;
                                                }
                                                $i++;
                                                ?>
                                            <div class="col-lg-12 col-sm-12">
                                                    <?php
                                                    if ($Topic->$title_var != "") {
                                                        $title = $Topic->$title_var;
                                                    } else {
                                                        $title = $Topic->$title_var2;
                                                    }
                                                    if ($Topic->$details_var != "") {
                                                        $details = $details_var;
                                                    } else {
                                                        $details = $details_var2;
                                                    }
                                                    $div_id = $Topic->$slug_var;
                                                    ?>
                                                <div class="mb-5 px-4">
                                                    <div>
                                                        <h3 id="{{ $div_id }}">
                                                            @if($Topic->icon !="")
                                                                <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                            @endif
                                                            {{ $title }}
                                                        </h3>
                                                        <div id="{{ $div_id }}-post"
                                                             aria-labelledby="{{ $div_id }}">
                                                            <div>
                                                                {{--Additional Feilds--}}
                                                                @include("frontEnd.topic.fields",["cols"=>12,"Fields"=>@$WebmasterSection->customFields->where("in_listing",true)])

                                                                @if(@$WebmasterSection->editor_status)
                                                                    {!! $Topic->$details !!}
                                                                @else
                                                                    {!! nl2br($Topic->$details) !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-lg-12 col-sm-12">
                                            <div class="mb-5 px-4">
                                                <div class="p-5 card text-center no-data">
                                                    <i class="fa fa-desktop fa-5x opacity-50"></i>
                                                    <h5 class="mt-3 text-muted">{{ __('frontend.noData') }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                    <?php
                                    $i = 0;
                                    $Topics = Helper::Topics(@$WebmasterSection->id, 0, 0);
                                    ?>
                                @if($Topics->count() > 0)
                                    @foreach($Topics as $Topic)
                                            <?php
                                            if ($i == 1) {
                                                echo "</div><div class='row'>";
                                                $i = 0;
                                            }
                                            $i++;
                                            ?>
                                        <div class="col-lg-12 col-sm-12">
                                                <?php
                                                if ($Topic->$title_var != "") {
                                                    $title = $Topic->$title_var;
                                                } else {
                                                    $title = $Topic->$title_var2;
                                                }
                                                if ($Topic->$details_var != "") {
                                                    $details = $details_var;
                                                } else {
                                                    $details = $details_var2;
                                                }
                                                $div_id = $Topic->$slug_var;
                                                ?>
                                            <div class="mb-5">
                                                <div>
                                                    <h2 id="{{ $div_id }}">
                                                        @if($Topic->icon !="")
                                                            <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                        @endif
                                                        {{ $title }}
                                                    </h2>
                                                    <div id="{{ $div_id }}-post"
                                                         aria-labelledby="{{ $div_id }}">
                                                        <div>
                                                            {{--Additional Feilds--}}
                                                            @include("frontEnd.topic.fields",["cols"=>12,"Fields"=>@$WebmasterSection->customFields->where("in_listing",true)])
                                                            @if(@$WebmasterSection->editor_status)
                                                                {!! $Topic->$details !!}
                                                            @else
                                                                {!! nl2br($Topic->$details) !!}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="mb-5">
                                            <div class="p-5 card text-center no-data">
                                                <i class="fa fa-desktop fa-5x opacity-50"></i>
                                                <h5 class="mt-3 text-muted">{{ __('frontend.noData') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('frontEnd.layouts.popup',['Popup'=>@$Popup])
@endsection
@if (@in_array(@$WebmasterSection->type, [3]))
    @push('before-styles')
        <link rel="stylesheet"
              href="{{ URL::asset('assets/frontend/vendor/green-audio-player/css/green-audio-player.min.css') }}?v={{ Helper::system_version() }}"/>
    @endpush
    @push('after-scripts')
        <script
            src="{{ URL::asset('assets/frontend/vendor/green-audio-player/js/green-audio-player.min.js') }}?v={{ Helper::system_version() }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                GreenAudioPlayer.init({
                    selector: '.audio-player',
                    stopOthersOnPlay: true,
                    showTooltips: true,
                });
            });
        </script>
    @endpush
@endif
@push('after-scripts')
    <script>
        document.addEventListener("scroll", function () {
            const anchoredCtaWeb = document.getElementById("fixed-area-menu");
            if (window.pageYOffset > 70) {
                anchoredCtaWeb.classList.add("fixed-min-top");
            }
            if (window.pageYOffset < 70) {
                anchoredCtaWeb.classList.remove("fixed-min-top");
            }
        });
        $(".page-sidebar a[href*=\\#]:not([href=\\#])").click(function () {
            if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") || location.hostname == this.hostname) {
                var e = $(this.hash);
                $("html,body").animate({scrollTop: e.offset().top - 120}, 500)
            }
        });

    </script>
@endpush
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

