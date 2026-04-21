@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp


@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football">
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

            <div class="tabs-wrapper d-flex mb-5">
                @foreach ($dates as $day)
                    {{-- <a href="{{ route('matches', ['date' => $day['key']]) }}"
                        class="tab-item {{ $activeTab === $day['key'] ? 'active' : '' }}" data-date="{{ $day['key'] }}">

                        <div class="tab-label">{{ $day['label'] }}</div>
                        <div class="tab-date">{{ $day['date'] }}</div>
                    </a> --}}
                    <button type="button"
                        class="tab-item match-tab bg-transparent {{ $activeTab === $day['key'] ? 'active' : '' }}"
                        data-date="{{ $day['key'] }}">

                        <div class="tab-label">{{ $day['label'] }}</div>
                        <div class="tab-date">{{ $day['date'] }}</div>
                    </button>
                @endforeach
            </div>
            <div id="matches-container">
                @include('frontEnd.football.partials.matches-list', ['matches' => $matches])
            </div>

            {{-- <div class="matches matches-home">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($matches as $match)
                        <?php
                        $isFinished = (bool) $match->is_finished;
                        $timezone = Helper::getUserTimezone();
                        $isTimeLive = false;
                        if (!$isFinished && $match->starting_at) {
                            try {
                                $start = \Carbon\Carbon::parse($match->starting_at);
                                $isTimeLive = now()
                                    ->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
                            } catch (\Throwable $e) {
                            }
                        }

                        $dt = $match->starting_at ? \Carbon\Carbon::parse($match->starting_at)->timezone($timezone) : null;
                        $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                        $timeLabel = $dt ? $dt->format('H:i') : '';

                        $minute = is_numeric($match->minute) ? (int) $match->minute : null;
                        ?>
                        <div class="col mb-3">
                            @include('frontEnd.football.partials.match', [
                                'match' => $match,
                                'isFinished' => $isFinished,
                                'isTimeLive' => $isTimeLive,
                                'dateLabel' => $dateLabel,
                                'timeLabel' => $timeLabel,
                                'minute' => $minute,
                            ])
                        </div>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </section>
@endsection

{{-- @push('after-scripts')
    @include('frontEnd.layouts.match')
@endpush --}}

@push('after-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.match-tab');
        const matchesContainer = document.getElementById('matches-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                const date = this.dataset.date;

                tabs.forEach(item => item.classList.remove('active'));
                this.classList.add('active');

                matchesContainer.innerHTML = `
                    <div class="text-center py-5">
                        <span>Loading...</span>
                    </div>
                `;

                fetch("{{ route('matches.filter') }}", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        date: date
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
            });
        });
    });
</script>

@include('frontEnd.layouts.match')
@endpush
