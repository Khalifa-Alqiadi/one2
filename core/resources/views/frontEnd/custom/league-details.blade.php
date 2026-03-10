
@php
$name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp
@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">

        <div class="container">

            {{-- Header --}}
            <div class="league-header mb-3">
                @if (data_get($league, 'image_path'))
                    <div class="logo rounded-circle bg-white">
                        <img src="{{ data_get($league, 'image_path') }}" alt="">
                    </div>
                @endif
                <h4 class="mb-0 fw-bold">{{ data_get($league, $name_var, 'League') }}</h4>
            </div>


            {{-- Tabs --}}
            <ul class="nav nav-pills league-pills mb-4 px-0" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-fixtures" type="button">
                        {{ $locale == 'ar' ? 'المباريات' : 'Fixtures' }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-players" type="button">
                        {{ $locale == 'ar' ? 'اللاعبون' : 'Players' }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-standings" type="button">
                        {{ $locale == 'ar' ? 'الترتيب' : 'Standings' }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-stats" type="button">
                        {{ $locale == 'ar' ? 'الإحصائيات' : 'Statistics' }}
                    </button>
                </li>
            </ul>


            <div class="tab-content cardx border-0 p-3 px-0">
                {{-- Fixtures --}}
                <div class="tab-pane fade show active" id="t-fixtures" role="tabpanel">
                    @include('frontEnd.custom.tabs.fixtures', [
                        'fixtures' => $fixtures ?? [],
                        'locale' => $locale,
                    ])
                </div>

                {{-- Players --}}
                {{-- @include('frontEnd.custom.tabs.players', [
                    'players' => $playersBlocks
                ]) --}}

                {{-- Standings --}}
                @include('frontEnd.custom.tabs.standings', [
                    'standings' => $standings
                ])


                {{-- Stats --}}
                {{-- <div class="tab-pane fade" id="t-stats">
                    <div class="text-muted">{{ $stats['note'] ?? '' }}</div>
                </div> --}}
            </div>

        </div>



    </section>
@endsection
@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@push('after-styles')
    <style>
        /* ====== Theme ====== */
        #content {
            margin-top: 140px !important;
        }

        @media (max-width: 992px) {
            #content {
                margin-top: 120px !important;
            }
        }


    </style>
@endpush
