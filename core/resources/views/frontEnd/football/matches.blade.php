@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp


@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">
        <div class="container">
            <div class="section-title text-start mb-4">
                <h2 class="d-flex align-items-center gap-2">
                    <img src="{{ URL::to('uploads/settings/Vector.svg') }}" alt="">
                    {{ __('frontend.matches') }}
                </h2>
            </div>
            @php
                $locale = $locale ?? 'ar';
                $activeTab = $activeTab ?? 'today';
            @endphp
            <div class="mb-4 mb-md-5 row row-filter">
                <div class="col-md-8">
                    <div class="tabs-wrapper d-flex">
                        @foreach ($dates as $day)
                            <button type="button"
                                class="tab-item match-tab bg-transparent border-0 {{ $activeTab === $day['key'] ? 'active' : '' }}"
                                data-date="{{ $day['key'] }}">

                                <div class="tab-label">{{ $day['label'] }}</div>
                                <div class="tab-date">{{ $day['date'] }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-matches">
                        <select name="filter_leagues" id="filter-leagues" class="form-control">
                            <option value="0">{{ __('frontend.all_competitions') }}</option>
                            @if (count($leagues) > 0)
                                @foreach ($leagues as $league)
                                    <option value="{{ $league->id }}">{{ $league->$name_var }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div id="matches-container">
                @include('frontEnd.football.partials.matches-list', ['matches' => $matches])
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.match-tab');
            const matchesContainer = document.getElementById('matches-container');
            const leagueSelect = document.getElementById('filter-leagues');

            let activeDate = document.querySelector('.match-tab.active')?.dataset.date ||
                "{{ $activeTab ?? now()->toDateString() }}";
            let activeLeagueId = leagueSelect ? leagueSelect.value : '0';

            function loadMatches() {
                matchesContainer.innerHTML = `
                <div class="text-center py-5">
                    <span>Loading...</span>
                </div>
            `;

                fetch("{{ route('matches.filter') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({
                            date: activeDate,
                            league_id: activeLeagueId
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status) {
                            matchesContainer.innerHTML = result.html;
                        } else {
                            matchesContainer.innerHTML = `
                        <div class="alert alert-danger">
                            فشل في تحميل المباريات
                        </div>
                    `;
                        }
                    })
                    .catch(() => {
                        matchesContainer.innerHTML = `
                    <div class="alert alert-danger">
                        حصل خطأ أثناء تحميل البيانات
                    </div>
                `;
                    });
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    activeDate = this.dataset.date;

                    tabs.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');

                    loadMatches();
                });
            });

            if (leagueSelect) {
                leagueSelect.addEventListener('change', function() {
                    activeLeagueId = this.value;
                    loadMatches();
                });
            }
        });
    </script>

    @include('frontEnd.layouts.match')
@endpush
