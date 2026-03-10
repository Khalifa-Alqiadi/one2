@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <?php
        $title_var = "title_" . @Helper::currentLanguage()->code;
        $title_var2 = "title_" . config('smartend.default_language');
        $webmaster_section_title = "";
        $category_title = "";
        $page_title = "";
        $category_image = "";
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
                    </ol>
                </div>

            </div>
        </section>

        <section id="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        @include('frontEnd.form',["FormSectionID"=>@$WebmasterSection->id])
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('frontEnd.layouts.popup',['Popup'=>@$Popup])
@endsection
