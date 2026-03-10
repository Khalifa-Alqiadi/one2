@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <?php
        $cf_title_var = "title_".@Helper::currentLanguage()->code;
        $cf_title_var2 = "title_".config('smartend.default_language');
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
                            <li class="active">{{ $User->name }}</li>
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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="search-box">
                            <?php
                            $cf_title_var = "title_".@Helper::currentLanguage()->code;
                            $field_title = "";
                            $field_id = 0;
                            foreach ($WebmasterSection->customFields->whereIn("type", [0, 2]) as $customField) {
                                if (!empty($customField)) {
                                    $field_title = $customField->$cf_title_var;
                                    $field_id = $customField->id;
                                    break;
                                }
                            }
                            ?>
                            @if($field_id >0)
                                <h2 class="mb-3">{{ __('backend.type') }} {{ $field_title }} {{ __('backend.toSearch') }}</h2>
                                <form method="GET" action="#" class="form-search" id="search-page-form">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input placeholder="{{ __('backend.searchFor') }}..." autocomplete="off" class="form-control mb-0" required="" name="q" type="text">
                                        <button class="btn btn-lg btn-secondary" type="submit">
                                            <i class="fa fa-search"></i> {{ __('backend.search') }}
                                        </button>
                                    </div>
                                </form>
                                @if(\request()->input('q') !="")
                                    <div class="alert alert-danger" style="padding: 10px;margin-top: 10px">
                                        <strong>{{ __('backend.noResults') }}</strong></div>
                                @endif
                            @else
                                <h3 class="text-center">{{ __('backend.error') }}</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('frontEnd.layouts.popup',['Popup'=>@$Popup])
@endsection
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
