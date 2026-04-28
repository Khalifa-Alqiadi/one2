@php
$name_var = 'name_' . @Helper::currentLanguage()->code;
$title_var = 'title_' . @Helper::currentLanguage()->code;
@endphp
@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football leagues-page">
        <div class="leagues-page__ambient" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="leagues-directory">
                <header class="leagues-directory__head">
                    <div class="leagues-directory__title-wrap">
                        <h2 class="leagues-directory__title">{{ __('frontend.leagues') }}</h2>
                        <p class="leagues-directory__kicker">{{ __('frontend.all_competitions') }}</p>
                    </div>
                    @if(isset($count) && $count > 0)
                        <span class="leagues-directory__count">{{ $count }}</span>
                    @endif
                </header>

                <div class="leagues-grid">
                    @forelse($leagues as $league)
                        <a href="{{ route('league.rounds', ['id' => $league->id]) }}" class="league-tile">
                            <span class="league-tile__sheen" aria-hidden="true"></span>
                            <div class="league-tile__body">
                                <div class="league-tile__main">
                                    <div class="league-tile__logo">
                                        <span class="league-tile__logo-ring"></span>
                                        <img src="{{ $league->image_path ?? '' }}" alt="{{ $league->$name_var ?? '' }}" loading="lazy" width="44" height="44">
                                    </div>
                                    <h3 class="league-tile__name">{{ $league->$name_var ?? '' }}</h3>
                                </div>
                                <div class="league-tile__foot">
                                    @php $countryLabel = optional($league->country)->{$title_var} ?? ''; @endphp
                                    @if($countryLabel !== '')
                                        <span class="league-tile__country">{{ $countryLabel }}</span>
                                    @endif
                                    <span class="league-tile__go" aria-hidden="true">
                                        <i class="bi bi-arrow-up-right"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="leagues-empty">{{ __('frontend.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
