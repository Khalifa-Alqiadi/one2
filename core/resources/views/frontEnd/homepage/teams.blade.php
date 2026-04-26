@php
    $name_var = 'name_' . Helper::currentLanguage()->code;
    $isRtl = Helper::currentLanguage()->code === 'ar';
    $majorCompetitions = Helper::majorCompetitions();
    $majorNationalTeams = Helper::majorNationalTeams();
//     $teamsSections = [
//         [
//             'key' => 'competitions',
//             'title' => __('frontend.major_competitions'),
//             'items' => $majorCompetitions,
//             'icon' => URL::to('uploads/settings/major_competitions.svg'),
//             'accent' => 'gold',
//         ],
//         [
//             'key' => 'national',
//             'title' => __('frontend.major_national_teams'),
//             'items' => $majorNationalTeams,
//             'icon' => URL::to('uploads/settings/major_competitions.svg'),
//             'accent' => 'gold',
//         ],
//     ]
@endphp

{{-- @if (collect($teamsSections)->pluck('items')->flatten()->count() > 0) --}}
<section class="teams-home py-4 mt-4">
    <div class="container">
        <div class="row g-4">
            @if($majorCompetitions->count() > 0)
            <div class="col-lg-6">
                <article class="teams-home__panel teams-home__panel--gold">
                    <div class="teams-home__panel-bg"></div>
                    <div
                        class="section-title d-flex justify-content-between align-items-center mb-4 section-title-with-line p-0">
                        <h2 class="d-flex align-items-center gap-4 ">
                            <img src="{{ URL::to('uploads/settings/major_competitions.svg') }}" alt="{{ __('frontend.major_competitions') }}">
                            {{ __('frontend.major_competitions') }}
                        </h2>
                        <div class="teams-home__controls">
                            <div class="teams-home__navs">
                                <button type="button"
                                    class="teams-home__nav teams-home__nav--prev js-teams-prev-competitions"
                                    aria-label="Previous">
                                    <i class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
                                </button>
                                <button type="button"
                                    class="teams-home__nav teams-home__nav--next js-teams-next-competitions"
                                    aria-label="Next">
                                    <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="teams-home__header">
                        <div class="teams-home__headline">
                            <div class="teams-home__title-row">
                                <span class="teams-home__icon-wrap">
                                    <img src="{{ URL::to('uploads/settings/major_competitions.svg') }}" alt="{{ __('frontend.major_competitions') }}">
                                </span>
                                <div>
                                    <h3 class="teams-home__title">{{ __('frontend.major_competitions') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="teams-home__controls">
                            <div class="teams-home__navs">
                                <button type="button"
                                    class="teams-home__nav teams-home__nav--prev js-teams-prev-competitions"
                                    aria-label="Previous">
                                    <i class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
                                </button>
                                <button type="button"
                                    class="teams-home__nav teams-home__nav--next js-teams-next-competitions"
                                    aria-label="Next">
                                    <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
                                </button>
                            </div>
                        </div>
                    </div> --}}

                    <div class="teams-home__swiper-wrap">
                        <div class="swiper js-teams-swiper" dir="{{ $isRtl ? 'rtl' : 'ltr' }}"
                            data-next=".js-teams-next-competitions"
                            data-prev=".js-teams-prev-competitions"
                            data-pagination=".js-teams-pagination-competitions">
                            <div class="swiper-wrapper">
                                @foreach ($majorCompetitions as $index => $league)
                                    @php
                                        $leagueName = $league->$name_var ?? ($league->name_ar ?? ($league->name_en ?? ''));
                                    @endphp
                                    <div class="swiper-slide">
                                        <a href="{{ route('league.rounds', ['id' => $league->id]) }}"
                                            class="teams-home__team-card">
                                            <span class="teams-home__team-logo">
                                                <img src="{{ $league->image_path }}" alt="{{ $leagueName }}">
                                            </span>
                                            <span class="teams-home__team-name">{{ $leagueName }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="teams-home__footer">
                        <div class="teams-home__pagination js-teams-pagination-competitions"></div>
                    </div>
                </article>
            </div>
            @endif
            @if($majorNationalTeams->count() > 0)
                <div class="col-lg-6">
                    <article class="teams-home__panel teams-home__panel--gold">
                        <div class="teams-home__panel-bg"></div>

                        <div
                            class="section-title d-flex justify-content-between align-items-center mb-4 section-title-with-line p-0">
                            <h2 class="d-flex align-items-center gap-4 ">
                                <img src="{{ URL::to('uploads/settings/major_competitions.svg') }}" alt="{{ __('frontend.major_national_teams') }}">
                                {{ __('frontend.major_national_teams') }}
                            </h2>
                            <div class="teams-home__controls">
                                <div class="teams-home__navs">
                                    <button type="button"
                                        class="teams-home__nav teams-home__nav--prev js-teams-prev-national"
                                        aria-label="Previous">
                                        <i class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
                                    </button>
                                    <button type="button"
                                        class="teams-home__nav teams-home__nav--next js-teams-next-national"
                                        aria-label="Next">
                                        <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="teams-home__header">
                            <div class="teams-home__headline">
                                <div class="teams-home__title-row">
                                    <span class="teams-home__icon-wrap">
                                        <img src="{{ URL::to('uploads/settings/major_competitions.svg') }}" alt="{{ __('frontend.major_national_teams') }}">
                                    </span>
                                    <div>
                                        <h3 class="teams-home__title">{{ __('frontend.major_national_teams') }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="teams-home__controls">
                                <div class="teams-home__navs">
                                    <button type="button"
                                        class="teams-home__nav teams-home__nav--prev js-teams-prev-national"
                                        aria-label="Previous">
                                        <i class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
                                    </button>
                                    <button type="button"
                                        class="teams-home__nav teams-home__nav--next js-teams-next-national"
                                        aria-label="Next">
                                        <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
                                    </button>
                                </div>
                            </div>
                        </div> --}}

                        <div class="teams-home__swiper-wrap">
                            <div class="swiper js-teams-swiper" dir="{{ $isRtl ? 'rtl' : 'ltr' }}"
                                data-next=".js-teams-next-national"
                                data-prev=".js-teams-prev-national"
                                data-pagination=".js-teams-pagination-national">
                                <div class="swiper-wrapper">
                                    @foreach ($majorNationalTeams as $index => $team)
                                        @php
                                            $teamName = $team->$name_var ?? ($team->name_ar ?? ($team->name_en ?? ''));
                                        @endphp
                                        <div class="swiper-slide">
                                            <a href="{{ route('team.details', ['id' => $team->id]) }}"
                                                class="teams-home__team-card">
                                                <span class="teams-home__team-logo">
                                                    <img src="{{ $team->image_path }}" alt="{{ $teamName }}">
                                                </span>
                                                <span class="teams-home__team-name">{{ $teamName }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="teams-home__footer">
                            <div class="teams-home__pagination js-teams-pagination-national"></div>
                        </div>
                    </article>
                </div>
            @endif
        </div>
    </div>
</section>

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swiper === 'undefined') {
                return;
            }

            document.querySelectorAll('.js-teams-swiper').forEach(function(swiperEl) {
                if (swiperEl.dataset.swiperInitialized === '1') {
                    return;
                }

                const panelEl = swiperEl.closest('.teams-home__panel') || document;
                const nextEl = panelEl.querySelector(swiperEl.dataset.next);
                const prevEl = panelEl.querySelector(swiperEl.dataset.prev);
                const paginationEl = panelEl.querySelector(swiperEl.dataset.pagination);

                if (!nextEl || !prevEl) {
                    return;
                }

                const swiperOptions = {
                    slidesPerView: 1.60,
                    spaceBetween: 14,
                    speed: 700,
                    grabCursor: true,
                    watchOverflow: true,
                    navigation: {
                        nextEl: nextEl,
                        prevEl: prevEl,
                    },
                    breakpoints: {
                        480: {
                            slidesPerView: 1.60,
                            spaceBetween: 16,
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 18,
                        },
                        1200: {
                            slidesPerView: 3.15,
                            spaceBetween: 20,
                        },
                    },
                };

                if (paginationEl) {
                    swiperOptions.pagination = {
                        el: paginationEl,
                        clickable: true,
                    };
                }

                new Swiper(swiperEl, swiperOptions);

                swiperEl.dataset.swiperInitialized = '1';
            });
        });
    </script>
@endpush
{{-- @endif --}}
