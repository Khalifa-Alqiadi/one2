@php($matches = Helper::getMatchHome(3))
@php($name_var = 'name_' . @Helper::currentLanguage()->code)
@php($locale = @Helper::currentLanguage()->code)
@if (count($matches) > 0)
    <section class="matches matches-home matches-home2 py-5">
        <div class="container">
            <div
                class="section-title d-flex justify-content-between align-items-center mb-0 section-title-with-line p-0">
                <h2 class="d-flex align-items-center gap-4 ">
                    <img src="{{ URL::to('uploads/settings/league1.svg') }}" alt="">
                    {{ __('frontend.matches') }}
                </h2>
                <a href="{{ route('matches') }}" class="section-title-btn">
                    {{ __('frontend.viewMore') }}
                </a>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <?php
                    $yesterday = now()->subDay()->toDateString();
                    $today = now()->toDateString();
                    $tomorrow = now()->addDay()->toDateString();
                    ?>
                    <div class="tabs-wrapper d-flex">
                        <button type="button" class="tab-item match-tab active" data-date="key_matches">
                            {{ __('frontend.key_matches') }}
                        </button>
                        <button type="button" class="tab-item match-tab" data-date="{{ $yesterday }}">
                            {{ __('frontend.yesterdays_matches') }}
                        </button>
                        <button type="button" class="tab-item match-tab" data-date="{{ $today }}">
                            {{ __('frontend.todays_matches') }}
                        </button>
                        <button type="button" class="tab-item match-tab" data-date="{{ $tomorrow }}">
                            {{ __('frontend.tomorrows_matches') }}
                        </button>
                    </div>
                    <div class="items-matches">
                        <div id="matches-container">
                            @include('frontEnd.homepage.swiper-home', ['matches' => $matches])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('after-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.match-tab');
                const matchesContainer = document.getElementById('matches-container');

                let swiperMatches = null;

                function initSwiper() {
                    const swiperElement = document.querySelector('.swiper-matches');

                    if (!swiperElement) return;

                    if (swiperMatches) {
                        swiperMatches.destroy(true, true);
                        swiperMatches = null;
                    }

                    swiperMatches = new Swiper('.swiper-matches', {
                        slidesPerView: 1.2,
                        spaceBetween: 10,

                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 1.5,
                                spaceBetween: 10,
                                loop: false,
                                centeredSlides: false,
                            },
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 15,
                                loop: false,
                                centeredSlides: false,
                            },
                            992: {
                                slidesPerView: 3.40,
                                spaceBetween: 40,
                                loop: true,
                                centeredSlides: true,
                                slideToClickedSlide: true,
                            },
                        },
                    });
                }

                function fetchMatches(date) {
                    matchesContainer.innerHTML = `
                <div class="text-center py-4 text-white">
                    جاري تحميل المباريات...
                </div>
            `;

                    fetch(`{{ route('matches.by.date') }}`, {
                            method: 'POST',
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
                            if (result.html) {
                                matchesContainer.innerHTML = result.html;
                                initSwiper();
                            } else {
                                matchesContainer.innerHTML = `
                        <div class="text-center py-4 text-danger">
                            لا توجد بيانات متاحة
                        </div>
                    `;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching matches:', error);

                            matchesContainer.innerHTML = `
                    <div class="text-center py-4 text-danger">
                        حصل خطأ أثناء تحميل المباريات
                    </div>
                `;
                        });
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        tabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');

                        const date = this.getAttribute('data-date');
                        fetchMatches(date);
                    });
                });

                initSwiper();
            });
        </script>
        @include('frontEnd.layouts.match')
    @endpush
@endif
