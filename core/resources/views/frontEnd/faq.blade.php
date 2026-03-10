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
                    $category_image = route("fileView",["path" =>'topics/'.@$CurrentCategory->photo ]);
                }

                $custom_css_code .= @$CurrentCategory->css_code;
                $custom_js_code .= @$CurrentCategory->js_code;
                $custom_body_code .= @$CurrentCategory->body_code;
            }
        }
        ?>
        @if($category_image !="")
            @include("frontEnd.topic.cover")
        @endif
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
            <div class="container">
                <div class="accordion">
                    <div id="accordion_search_results">
                        <div class="row">
                            <?php
                            $title_var = "title_".@Helper::currentLanguage()->code;
                            $title_var2 = "title_".config('smartend.default_language');
                            $details_var = "details_".@Helper::currentLanguage()->code;
                            $details_var2 = "details_".config('smartend.default_language');
                            $slug_var = "seo_url_slug_".@Helper::currentLanguage()->code;
                            $slug_var2 = "seo_url_slug_".config('smartend.default_language');
                            $i = 0;
                            $c = 0;
                            $cats_count = count($Categories);
                            ?>
                            @if($cats_count)
                                @foreach($Categories as $Category)
                                        <?php
                                        if ($Category->$title_var != "") {
                                            $Category_title = $Category->$title_var;
                                        } else {
                                            $Category_title = $Category->$title_var2;
                                        }
                                        if ($i == 2) {
                                            echo "</div><div class='row'>";
                                            $i = 0;
                                        }
                                        $i++;
                                        $c++;
                                        $cols = 6;
                                        if ($c == $cats_count && $i == 1) {
                                            $cols = 12;
                                        }
                                        ?>
                                    <div class="col-lg-{{ $cols }} col-sm-12">
                                        <div class="mb-5">
                                            <h3 class="accordion-cat-title mb-3 text-primary">
                                                @if($Category->icon !="")
                                                    <i class="{{$Category->icon}}"></i> &nbsp;
                                                @endif
                                                {{ $Category_title }}
                                            </h3>
                                                <?php
                                                $Topics = Helper::Topics(@$WebmasterSection->id, $Category->id, 0);
                                                ?>
                                            @if($Topics->count() > 0)
                                                @foreach($Topics as $Topic)
                                                    @include("frontEnd.topic.accordion",["Topic"=>$Topic,"CatId"=>$Category->id])
                                                @endforeach
                                            @else
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="accordion-item">
                                                        <div class="p-5 text-center no-data">
                                                            <i class="fa fa-desktop fa-5x opacity-50"></i>
                                                            <h5 class="mt-3 text-muted">{{ __('frontend.noData') }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                    <?php
                                    $Topics = Helper::Topics(@$WebmasterSection->id, 0, 0);
                                    $i = 0;
                                    ?>
                                @if($Topics->count() > 0)
                                    @foreach($Topics as $Topic)
                                            <?php
                                            if ($i == 2) {
                                                echo "</div><div class='row'>";
                                                $i = 0;
                                            }
                                            $i++;
                                            ?>
                                        <div class="col-lg-6 col-sm-12">
                                            @include("frontEnd.topic.accordion",["Topic"=>$Topic,"CatId"=>0])
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="accordion-item">
                                            <div class="p-5 text-center no-data">
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
