@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <?php
        $title_var = "title_".@Helper::currentLanguage()->code;
        $title_var2 = "title_".config('smartend.default_language');
        $details_var = "details_".@Helper::currentLanguage()->code;
        $details_var2 = "details_".config('smartend.default_language');
        $slug_var = "seo_url_slug_".@Helper::currentLanguage()->code;
        $slug_var2 = "seo_url_slug_".config('smartend.default_language');

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
        if ($CurrentCategory != "none") {
            if (!empty($CurrentCategory)) {
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
        if (!empty(@$DBTag)) {
            $page_title = $DBTag->title;
        }
        $Category_description = null;
        if(trim(@$CurrentCategory->$details_var) !=""){
            $Category_description = @$CurrentCategory->$details_var;
        }
        if(@$page_type == "tag" && trim(@$TagDescription)){
            $Category_description = @$TagDescription;
        }
        ?>
        @if($category_image !="")
            {{-- @include("frontEnd.topic.cover") --}}
        @endif
        <section class="breadcrumbs d-flex align-items-center justify-content-center" style="background-image: url({{$category_image}})">
            <div class="container">
                <div class="">
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __("backend.home") }}</a></li>
                        @if(@$search_word !="")
                            <li class="active">{!! __("backend.search") !!}</li>
                        @elseif($webmaster_section_title !="")
                            <li class="active"><a
                                    href="{{ Helper::sectionURL(@$WebmasterSection->id) }}">{!! $webmaster_section_title !!}</a>
                            </li>
                        @elseif(@$search_word!="")
                            <li class="active">{{ @$search_word }}</li>
                        @elseif(!empty(@$DBTag))
                            <li class="active">{{ @$DBTag->title }}</li>
                        @else
                            <li class="active">{{ @$User->name }}</li>
                        @endif
                        @if($category_title !="")
                            <li class="active"><a
                                    href="{{ Helper::categoryURL(@$CurrentCategory->id) }}">{{ $category_title }}</a>
                            </li>
                        @endif
                    </ol>
                </div>
                @if($Category_description)
                    <div class="text-muted mt-2 category-details">
                        {!! nl2br($Category_description) !!}
                    </div>
                @endif
            </div>
        </section>
        <section id="content" class="
            {{@$WebmasterSection->id == 17 ? 'slider-swiper-rails' : ''}}
            {{@$WebmasterSection->id == 5 ? 'slider-swiper-home' : ''}}
            ">
            <div class="container">
                
                    <div class="row">
                        @if(@$WebmasterSection->id == 17)
                            <div class="col-md-12">
                                <div class="row">
                                    {{-- @foreach($Topics as $Topic)
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

                                        $topic_link_url = Helper::topicURL($Topic->id, "", $Topic);
                                        ?>
                                        <div class="col mb-4">
                                            @include("frontEnd.topic.card-home",["Topic"=>$Topic])
                                        </div>
                                    @endforeach --}}
                                    @include("frontEnd.topic.short-video")
                                </div>
                            </div>
                        @elseif(@$WebmasterSection->id == 5)
                            <div class="col-md-12">
                                <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2">
                                    @foreach($Topics as $Topic)
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

                                        $topic_link_url = Helper::topicURL($Topic->id, "", $Topic);
                                        ?>
                                        <div class="col mb-4">
                                            @include("frontEnd.topic.card-home",["Topic"=>$Topic])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @if(@count($Categories)>0)
                                @include('frontEnd.layouts.side')
                            @endif
                            <div
                                class="col-lg-{{(@count($Categories)>1)? "9":"12"}} col-md-{{(@count($Categories)>1)? "7":"12"}} col-sm-12 col-xs-12">
                                @if($Topics->total() == 0)
                                    <div class="p-5 card text-center no-data">
                                        <i class="fa fa-desktop fa-5x opacity-50"></i>
                                        <h5 class="mt-3 text-muted">{{ __('frontend.noData') }}</h5>
                                    </div>
                                @else
                                    <div class="row {{@$WebmasterSection->id == 8 ? 'products page-products' : ''}}">
                                        @if($Topics->total() > 0)

                                                <?php
                                                $i = 0;
                                                $cols_lg = 4;
                                                $cols_md = 6;
                                                if (count($Categories) > 0) {
                                                    $cols_lg = 6;
                                                    $cols_md = 12;
                                                }
                                                ?>
                                            @foreach($Topics as $Topic)
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

                                                    $topic_link_url = Helper::topicURL($Topic->id, "", $Topic);
                                                    ?>
                                                <div
                                                    class="col-lg-{{$cols_lg}} col-md-{{$cols_md}}">
                                                    @include("frontEnd.topic.card",["Topic"=>$Topic])
                                                </div>
                                                    <?php
                                                    $i++;
                                                    ?>
                                            @endforeach

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            {!! $Topics->appends($_GET)->links() !!}
                                        </div>
                                    </div>
                                @endif
                                @endif
                            </div>
                        @endif
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
