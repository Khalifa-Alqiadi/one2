@php
    $name_var = 'name_' . Helper::currentLanguage()->code;
    $isRtl = Helper::currentLanguage()->code === 'ar';
    $majorCompetitions = Helper::majorCompetitionsTeams();
    $majorNationalTeams = Helper::majorNationalTeams();
    $teamsSections = [
        [
            'key' => 'competitions',
            'title' => __('frontend.major_competitions'),
            'items' => $majorCompetitions,
            'icon' => URL::to('uploads/settings/major_competitions.svg'),
            'accent' => 'gold',
        ],
        [
            'key' => 'national',
            'title' => __('frontend.major_national_teams'),
            'items' => $majorNationalTeams,
            'icon' => URL::to('uploads/settings/major_competitions.svg'),
            'accent' => 'gold',
        ],
    ];
@endphp

@if (collect($teamsSections)->pluck('items')->flatten()->count() > 0)
    <section class="teams-home py-4">
        <div class="container">
            <div class="row g-4">
                @foreach ($teamsSections as $section)
                    @continue(count($section['items']) === 0)

                    <div class="col-lg-6">
                        <article class="teams-home__panel teams-home__panel--{{ $section['accent'] }}">
                            <div class="teams-home__panel-bg"></div>

                            <div class="teams-home__header">
                                <div class="teams-home__headline">
                                    <div class="teams-home__title-row">
                                        <span class="teams-home__icon-wrap">
                                            <img src="{{ $section['icon'] }}" alt="{{ $section['title'] }}">
                                        </span>
                                        <div>
                                            <h3 class="teams-home__title">{{ $section['title'] }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="teams-home__controls">
                                    <span class="teams-home__count">{{ count($section['items']) }}</span>
                                    <div class="teams-home__navs">
                                        <button type="button"
                                            class="teams-home__nav teams-home__nav--prev js-teams-prev-{{ $section['key'] }}"
                                            aria-label="Previous">
                                            <i class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
                                        </button>
                                        <button type="button"
                                            class="teams-home__nav teams-home__nav--next js-teams-next-{{ $section['key'] }}"
                                            aria-label="Next">
                                            <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="teams-home__swiper-wrap">
                                <div class="swiper js-teams-swiper" dir="{{ $isRtl ? 'rtl' : 'ltr' }}"
                                    data-next=".js-teams-next-{{ $section['key'] }}"
                                    data-prev=".js-teams-prev-{{ $section['key'] }}"
                                    data-pagination=".js-teams-pagination-{{ $section['key'] }}">
                                    <div class="swiper-wrapper">
                                        @foreach ($section['items'] as $index => $team)
                                            @php
                                                $teamName = $team->$name_var ?? $team->name_ar ?? $team->name_en ?? '';
                                            @endphp
                                            <div class="swiper-slide">
                                                <a href="{{ route('team.details', ['id' => $team->id]) }}"
                                                    class="teams-home__team-card">
                                                    <span class="teams-home__team-rank">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                                    <span class="teams-home__team-logo">
                                                        <img src="{{ $team->image_path }}" alt="{{ $teamName }}">
                                                    </span>
                                                    <span class="teams-home__team-name">{{ $teamName }}</span>
                                                    <span class="teams-home__team-link">
                                                        <span>{{ $isRtl ? 'استعرض الفريق' : 'View Team' }}</span>
                                                        <i class="fas fa-arrow-{{ $isRtl ? 'left' : 'right' }}"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="teams-home__footer">
                                <div class="teams-home__pagination js-teams-pagination-{{ $section['key'] }}"></div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('after-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.js-teams-swiper').forEach(function(swiperEl) {
                    if (swiperEl.dataset.swiperInitialized === '1') {
                        return;
                    }

                    const nextEl = document.querySelector(swiperEl.dataset.next);
                    const prevEl = document.querySelector(swiperEl.dataset.prev);
                    const paginationEl = document.querySelector(swiperEl.dataset.pagination);

                    if (!nextEl || !prevEl || !paginationEl) {
                        return;
                    }

                    new Swiper(swiperEl, {
                        slidesPerView: 1.60,
                        spaceBetween: 14,
                        speed: 700,
                        grabCursor: true,
                        watchOverflow: true,
                        navigation: {
                            nextEl: nextEl,
                            prevEl: prevEl,
                        },
                        pagination: {
                            el: paginationEl,
                            clickable: true,
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
                                slidesPerView: 2.35,
                                spaceBetween: 20,
                            },
                        },
                    });

                    swiperEl.dataset.swiperInitialized = '1';
                });
            });
        </script>
    @endpush
@endif
