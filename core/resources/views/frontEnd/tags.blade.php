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

        ?>
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>{{  $PageTitle }}</h1>
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __("backend.home") }}</a></li>
                        <li class="active">{!! $PageTitle !!}</li>
                    </ol>
                </div>
            </div>
        </section>
        <section id="content">
            <div class="container">
                @if($Tags->total() > 0)
                    <div class="row mb-5">
                        <div class="col-lg-12">
                            <div class="tags-list">
                                @foreach($Tags as $Tag)
                                    <a href="{{ route("tag",@$Tag->seo_url) }}" class="btn btn-lg btn-outline-theme m-1">#{{ @$Tag->title }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            {!! $Tags->links() !!}
                        </div>
                    </div>
                @else
                    <div class="p-5 card text-center no-data">
                        <i class="fa fa-desktop fa-5x opacity-50"></i>
                        <h5 class="mt-3 text-muted">{{ __('frontend.noData') }}</h5>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
