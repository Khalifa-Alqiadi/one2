@php
    $featureds = Helper::getFeaturedTopic(7);
    $name_var = 'name_' . @Helper::currentLanguage()->code;
    $title_var = 'title_' . @Helper::currentLanguage()->code;
@endphp

@if (count($featureds) > 0)
    @php
        $featureds = collect($featureds);
        $heroTopic = $featureds->get(0);
        $flankRight = $featureds->get(1);
        $flankLeft = $featureds->get(2);
        $miniTopics = $featureds->slice(3, 4);

        $getImg = function ($topic, $w, $h) {
            $img = $topic->photo_file
                ? route('fileView', ['path' => 'topics/' . $topic->photo_file]) . "?w={$w}&h={$h}"
                : '';
            if (!$img && $topic->video_type == 1) {
                $t = Helper::getThumbnail($topic->video_file);
                $img = $t['url'] ?? ($t['webp'] ?? '');
            }
            return $img;
        };

        $getCat = function ($topic) use ($name_var, $title_var) {
            return $topic->league
                ? $topic->league->$name_var
                : ($topic->topicCategories
                    ? $topic->topicCategories->section->$title_var
                    : $topic->webmasterSection->$title_var);
        };
    @endphp

    <section class="one2tv">
        <div class="container">

            {{-- ── Header ── --}}
            <div class="section-title d-flex justify-content-between align-items-center mb-3 section-title-with-line p-0">
                <h2 class="d-flex align-items-center gap-4 ">
                    <img src="{{ URL::to('uploads/settings/tv.svg') }}" alt="">
                    OneTwo TV
                </h2>
                <a href="{{ route('matches') }}" class="section-title-btn">
                    {{ __('frontend.viewMore') }}
                </a>
            </div>

            {{-- ── Top Row ── --}}
            <div class="row one2tv__top-row">

                {{-- Flank Right --}}
                @if ($flankRight)
                    @php
                        $img = $getImg($flankRight, 500, 360);
                        $cat = $getCat($flankRight);
                        $url = Helper::topicURL($flankRight->id, '', $flankRight);
                        $hasVideo = $flankRight->video_file || $flankRight->video_url;
                    @endphp
                    <div class="col-md-3">
                        <a href="{{ $url }}" class="one2tv__flank h-100">
                            @if ($img)
                                <img src="{{ $img }}" alt="{{ $flankRight->$title_var }}">
                            @else
                                <div class="one2tv__img-empty"></div>
                            @endif
                            <div class="one2tv__flank-ov"></div>
                            @if ($hasVideo)
                                <span class="one2tv__flank-play">
                                    <i class="bi bi-play-fill"></i>
                                </span>
                            @endif
                            <div class="one2tv__flank-foot">
                                <span class="one2tv__pill">{{ $cat }}</span>
                                <p class="one2tv__flank-title mt-3">
                                    {{ \Illuminate\Support\Str::limit($flankRight->$title_var, 70) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Hero Center --}}
                @if ($heroTopic)
                    @php
                        $img = $getImg($heroTopic, 900, 360);
                        $cat = $getCat($heroTopic);
                        $url = Helper::topicURL($heroTopic->id, '', $heroTopic);
                        $hasVideo = $heroTopic->video_file || $heroTopic->video_url;
                    @endphp
                    <div class="col-md-6">
                        <a href="{{ $url }}" class="one2tv__hero">
                            @if ($img)
                                <img src="{{ $img }}" alt="{{ $heroTopic->$title_var }}">
                            @else
                                <div class="one2tv__img-empty"></div>
                            @endif
                            <div class="one2tv__hero-ov"></div>
                            @if ($hasVideo)
                                <span class="one2tv__hero-play">
                                    <i class="bi bi-play-fill"></i>
                                </span>
                            @endif
                            <div class="one2tv__hero-foot">
                                <span class="one2tv__pill">{{ $cat }}</span>
                                <h3 class="one2tv__hero-title">
                                    {{ \Illuminate\Support\Str::limit($heroTopic->$title_var, 90) }}
                                </h3>
                                <span class="one2tv__hero-more">
                                    اقرأ المزيد <i class="bi bi-chevron-left"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Flank Left --}}
                @if ($flankLeft)
                    @php
                        $img = $getImg($flankLeft, 500, 360);
                        $cat = $getCat($flankLeft);
                        $url = Helper::topicURL($flankLeft->id, '', $flankLeft);
                        $hasVideo = $flankLeft->video_file || $flankLeft->video_url;
                    @endphp
                    <div class="col-md-3">
                        <a href="{{ $url }}" class="one2tv__flank h-100">
                            @if ($img)
                                <img src="{{ $img }}" alt="{{ $flankLeft->$title_var }}">
                            @else
                                <div class="one2tv__img-empty"></div>
                            @endif
                            <div class="one2tv__flank-ov"></div>
                            @if ($hasVideo)
                                <span class="one2tv__flank-play">
                                    <i class="bi bi-play-fill"></i>
                                </span>
                            @endif
                            <div class="one2tv__flank-foot">
                                <span class="one2tv__pill">{{ $cat }}</span>
                                <p class="one2tv__flank-title mt-3">
                                    {{ \Illuminate\Support\Str::limit($flankLeft->$title_var, 70) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endif

            </div>{{-- /top-row --}}

            <div class="one2tv__sep mt-3"></div>

            {{-- ── Bottom Row ── --}}
            <div class="row mt-3">
                @php $n = 1; @endphp
                @foreach ($miniTopics as $item)
                    @php
                        $img = $getImg($item, 200, 72);
                        $cat = $getCat($item);
                        $url = Helper::topicURL($item->id, '', $item);
                        $hasVideo = $item->video_file || $item->video_url;
                        $num = str_pad($n, 2, '0', STR_PAD_LEFT);
                        $n++;
                    @endphp
                    <div class="col-md-3">
                        <a href="{{ $url }}" class="one2tv__mini h-100 py-3 px-2">
                            <div class="one2tv__mini-thumb">
                                @if ($img)
                                    <img src="{{ $img }}" alt="{{ $item->$title_var }}" loading="lazy">
                                @else
                                    <div class="one2tv__img-empty"></div>
                                @endif
                                @if ($hasVideo)
                                    <div class="one2tv__mini-thumb-ov">
                                        <span class="one2tv__mini-play">
                                            <i class="bi bi-play-fill"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="one2tv__mini-body">
                                <span class="one2tv__pill one2tv__pill--xs">{{ $cat }}</span>
                                <p class="one2tv__mini-title">
                                    {{ \Illuminate\Support\Str::limit($item->$title_var, 65) }}
                                </p>
                                <div class="one2tv__mini-meta">
                                    <span class="one2tv__mini-time">
                                        {{ \Carbon\Carbon::parse($item->date)->diffForHumans() }}
                                    </span>
                                    <span class="one2tv__mini-dot"></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>{{-- /bot-row --}}

        </div>
    </section>
@endif
