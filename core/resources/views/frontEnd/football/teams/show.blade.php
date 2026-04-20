@php
    $isRtl = (@Helper::currentLanguage()->code ?? 'ar') === 'ar';
    $name_var = 'name_' . @Helper::currentLanguage()->code;
    $title_var = 'title_' . @Helper::currentLanguage()->code;

@endphp
@extends('frontEnd.layouts.master')
@section('content')
    <section id="content" class="football football-match details-match team-details" style="margin-top: 100px">
        <div class="container my-4">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <div class="d-flex align-items-center">
                        @if ($team?->image_path)
                            <div class="logo rounded-circle">
                                <img src="{{ $team?->image_path }}" alt="">
                            </div>
                        @endif
                        <h4 class="mb-0 fw-bold mx-3">{{ $team->$name_var }}</h4>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center row-details">

                <div class="col-lg-8 mb-4">
                    {{-- Tabs --}}
                    <ul class="nav nav-tabs nav-fill mb-3 league-pills p-0" role="tablist"
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

                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card bg-dark text-light shadow-sm mb-3" style="border-radius:14px;">
                        <div class="card-body">
                            <h5 class="card-title mb-3">{{ __('frontend.details') }}</h5>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                @if (!empty($team->venue->image_path))
                                    <img src="{{ $team->venue->image_path }}" alt="Venue Image" class=""
                                        style="width:50px;height:50px;border-radius:8px;object-fit:cover;">
                                @else
                                    <div
                                        style="width:50px;height:50px;border-radius:8px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-building" style="color:rgba(255,255,255,.6);"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="">{{ $team->venue->$name_var ?? __('frontend.unknown_venue') }}
                                    </div>
                                    <div class="text-muted" dir="{{ Helper::currentLanguage()->direction }}"
                                        style="font-size:14px;">
                                        <span
                                            dir="{{ Helper::currentLanguage()->direction }}">{{ $team->venue->city_name ?? '' }}</span>
                                        @if (!empty($team->venue->capacity))
                                            • {{ number_format($team->venue->capacity) }}
                                            {{ __('frontend.capacity') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- ممكن تضيف معلومات إضافية عن الملعب أو الفريقين إذا حابب --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
