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
                    $yesterday = now(Helper::getUserTimezone() ?: 'UTC')->subDay()->toDateString();
                    $today = now(Helper::getUserTimezone() ?: 'UTC')->toDateString();
                    $tomorrow = now(Helper::getUserTimezone() ?: 'UTC')->addDay()->toDateString();
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
                const section = document.querySelector('.matches-home2');
                const tabs = section ? section.querySelectorAll('.match-tab') : [];
                const matchesContainer = section ? section.querySelector('#matches-container') : null;
                const loadingMessage = @json(__('backend.loading') . '...');
                const noDataMessage = @json(__('frontend.no_data'));
                const errorMessage = @json(__('backend.error'));

                let swiperMatches = null;
                let matchesRequest = null;

                if (!matchesContainer) {
                    return;
                }

                function destroySwiper() {
                    if (swiperMatches) {
                        swiperMatches.destroy(true, true);
                        swiperMatches = null;
                    }
                }

                function showMessage(message, className) {
                    destroySwiper();

                    const messageEl = document.createElement('div');
                    messageEl.className = 'text-center py-4 ' + className;
                    messageEl.textContent = message;

                    matchesContainer.innerHTML = '';
                    matchesContainer.appendChild(messageEl);
                }

                function initSwiper() {
                    if (typeof Swiper === 'undefined') {
                        return;
                    }

                    const swiperElement = matchesContainer.querySelector('.js-matches-swiper');

                    if (!swiperElement) {
                        return;
                    }

                    destroySwiper();

                    const slideCount = swiperElement.querySelectorAll('.swiper-slide').length;

                    if (!slideCount) {
                        return;
                    }

                    const nextEl = swiperElement.querySelector(swiperElement.dataset.next);
                    const prevEl = swiperElement.querySelector(swiperElement.dataset.prev);
                    const paginationEl = swiperElement.querySelector(swiperElement.dataset.pagination);
                    const enableLoop = slideCount > 4;

                    const swiperOptions = {
                        slidesPerView: 1.2,
                        spaceBetween: 10,
                        speed: 650,
                        grabCursor: slideCount > 1,
                        watchOverflow: true,
                        loop: enableLoop,
                        rewind: !enableLoop && slideCount > 1,
                        centeredSlides: false,
                        slideToClickedSlide: enableLoop,
                        observer: true,
                        observeParents: true,
                        breakpoints: {
                            576: {
                                slidesPerView: 1.5,
                                spaceBetween: 10,
                                centeredSlides: false,
                            },
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 15,
                                centeredSlides: false,
                            },
                            992: {
                                slidesPerView: 3.40,
                                spaceBetween: 40,
                                centeredSlides: enableLoop,
                            },
                        },
                    };

                    if (nextEl && prevEl) {
                        swiperOptions.navigation = {
                            nextEl: nextEl,
                            prevEl: prevEl,
                        };
                    }

                    if (paginationEl) {
                        swiperOptions.pagination = {
                            el: paginationEl,
                            clickable: true,
                        };
                    }

                    swiperMatches = new Swiper(swiperElement, swiperOptions);
                }

                function fetchMatches(date) {
                    if (matchesRequest) {
                        matchesRequest.abort();
                    }

                    const controller = new AbortController();
                    matchesRequest = controller;

                    showMessage(loadingMessage, 'text-white');

                    fetch(`{{ route('matches.by.date') }}`, {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "X-Requested-With": "XMLHttpRequest"
                            },
                            body: JSON.stringify({
                                date: date,
                                view: 'home_swiper'
                            }),
                            signal: controller.signal
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Request failed');
                            }

                            return response.json();
                        })
                        .then(result => {
                            if (result.status && result.html) {
                                matchesContainer.innerHTML = result.html;
                                initSwiper();
                            } else {
                                showMessage(noDataMessage, 'text-danger');
                            }
                        })
                        .catch(error => {
                            if (error.name === 'AbortError') {
                                return;
                            }

                            showMessage(errorMessage, 'text-danger');
                        })
                        .finally(() => {
                            if (matchesRequest === controller) {
                                matchesRequest = null;
                            }
                        });
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        tabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');

                        fetchMatches(this.getAttribute('data-date'));
                    });
                });

                initSwiper();
            });
        </script>
        @include('frontEnd.layouts.match')
    @endpush
@endif
