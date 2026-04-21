@php
    $isRtl = (@Helper::currentLanguage()->code ?? 'ar') === 'ar';
    $name_var = 'name_' . @Helper::currentLanguage()->code;
    $title_var = 'title_' . @Helper::currentLanguage()->code;

@endphp
@extends('frontEnd.layouts.master')
@section('content')
    <section id="content" class="football football-match details-match player-details">
        <div class="container my-4">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <div class="d-flex align-items-center title-header">
                        @if ($player?->image_path)
                            <div class="logo rounded-circle">
                                <img src="{{ $player?->image_path }}" class="w-100 h-100" alt="">
                            </div>
                        @endif
                        <div class="mx-3">
                            <h4 class="mb-0 fw-bold ">{{ $player->$name_var }}</h4>
                            <span>{{$player->country->$title_var}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center row-details">

                <div class="col-lg-8 mb-4">
                    {{-- Tabs --}}
                    {{-- <ul class="nav nav-tabs nav-fill mb-3 league-pills p-0" role="tablist"
                        style="border-color: rgba(255,255,255,.08);">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-overview" type="button"
                                role="tab">{{ __('frontend.overview') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " data-bs-toggle="tab" data-bs-target="#t-matches" type="button"
                                role="tab">{{ __('frontend.matches') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " data-bs-toggle="tab" data-bs-target="#t-news" type="button"
                                role="tab">{{ __('frontend.news') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-standings" type="button">
                                {{ __('frontend.standings') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-players_list" type="button">
                                {{ __('frontend.players_list') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        @include('frontEnd.football.teams.tabs.overview')
                        @include('frontEnd.football.teams.tabs.players_list')

                    </div> --}}
                </div>
                <div class="col-lg-4 mb-4">
                </div>
            </div>
        </div>
    </section>
@endsection
