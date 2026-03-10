@extends('frontEnd.layouts.master')

@section('content')
    <div class="home-page">
        <?php
        $title_var = 'title_' . @Helper::currentLanguage()->code;
        $title_var2 = 'title_' . config('smartend.default_language');
        $details_var = 'details_' . @Helper::currentLanguage()->code;
        $details_var2 = 'details_' . config('smartend.default_language');
        ?>

        @include('frontEnd.layouts.slider', [
            'BannersSettingsId' => Helper::GeneralWebmasterSettings('home_banners_section_id'),
        ])

        @include('frontEnd.homepage.row1', ['Topic' => $Topic])
        @include('frontEnd.homepage.row2')
        @include('frontEnd.homepage.row3')
        @include('frontEnd.homepage.row4')
        @include('frontEnd.homepage.row5')
        @include('frontEnd.homepage.row6')
        @include('frontEnd.homepage.row7')

        @include('frontEnd.layouts.popup', ['Popup' => @$Popup])
    </div>
@endsection
