@php
    $moreViewed = Helper::moreView(9);
    $name_var = 'name_' . @Helper::currentLanguage()->code;
    $title_var = 'title_' . @Helper::currentLanguage()->code;
    $i = 0;
@endphp
@if (count($moreViewed) > 0)
    <section class="more-viewed py-5">
        <div class="container">
            <div class="section-title section-title-with-line">
                <h2>{{__('frontend.mostViewed')}}</h2>
            </div>
            <div class="mostv-layout">
                @foreach ($moreViewed as $item)
                    <?php
                    $topic_link_url = Helper::topicURL($item->id, '', $item);
                    $title = $item->$title_var;
                    $league = $item->league;
                    $team = $item->team;
                    $match = $item->match;
                    $i++;
                    ?>
                    @if ($i == 1)
                        <div class="mostv-big">
                            <div class="image">
                                @if ($item->photo_file != '')
                                    <img class="card-img-top"
                                        src="{{ route('fileView', ['path' => 'topics/' . $item->photo_file]) }}?w=450&h=450"
                                        width="100%" height="100%" alt="{{ $title }}" loading="lazy" />
                                @else
                                    <?php
                                    $img_url = '';
                                    ?>
                                    @if ($item->video_type == 1)
                                        <?php
                                        $url = Helper::getThumbnail($item->video_file);
                                        $img_url = $url['url'] ?? $url['webp'];
                                        ?>
                                        <img class="card-img-top" src="{{ $img_url }}" alt="{{ $title }}"
                                            loading="lazy" />
                                    @else
                                        <div class="bg-secondary w-100 rounded-top h-200px"></div>
                                    @endif
                                @endif
                            </div>
                            <a href="{{ $topic_link_url }}" class="mv-play-big"><i class="bi bi-play-fill"></i></a>
                            <div class="mv-big-info">
                                <span class="gold-badge mb-2">
                                    @if ($item->league)
                                        {{ $item->league->$name_var }}
                                    @elseif($item->category($item->id))
                                        {{ $item->category($item->id)->$title_var }}
                                    @else
                                        {{ $item->webmasterSection->$title_var }}
                                    @endif
                                </span>
                                <h3>{{ $item->$title_var }}</h3>
                            </div>
                        </div>
                    @endif
                @endforeach
                <!-- Left col: 2x2 grid -->
                <div class="mostv-left">
                    <?php $i = 0; ?>
                    @foreach ($moreViewed as $item)
                        <?php
                        $topic_link_url = Helper::topicURL($item->id, '', $item);
                        $title = $item->$title_var;
                        $league = $item->league;
                        $team = $item->team;
                        $match = $item->match;
                        $i++;
                        ?>

                        @if ($i > 1 && $i <= 5)
                            <div class="mv-card-sm">
                                @if ($item->photo_file != '')
                                    <img class="card-img-top"
                                        src="{{ route('fileView', ['path' => 'topics/' . $item->photo_file]) }}?w=450&h=450"
                                        width="100%" height="100%" alt="{{ $title }}" loading="lazy" />
                                @else
                                    <?php
                                    $img_url = '';
                                    ?>
                                    @if ($item->video_type == 1)
                                        <?php
                                        $url = Helper::getThumbnail($item->video_file);
                                        $img_url = $url['url'] ?? $url['webp'];
                                        ?>
                                        <img class="card-img-top" src="{{ $img_url }}" alt="{{ $title }}"
                                            loading="lazy" />
                                    @else
                                        <div class="bg-secondary w-100 rounded-top h-200px"></div>
                                    @endif
                                @endif
                                <a href="{{ $topic_link_url }}" class="mv-play-sm"><i class="bi bi-play-fill"></i></a>
                                <div class="mv-info-sm ">
                                     <span class="gold-badge mb-2">
                                        @if ($item->league)
                                            {{ $item->league->$name_var }}
                                        @elseif($item->category($item->id))
                                            {{ $item->category($item->id)->$title_var }}
                                        @else
                                            {{ $item->webmasterSection->$title_var }}
                                        @endif
                                     </span>
                                     <h5>{{ $item->$title_var }}</h5>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Right: big -->

            </div>
            <!-- Mini strip -->
            <div class="mini-strip">
                <?php $i = 0; ?>
                @foreach ($moreViewed as $item)
                    <?php
                    $topic_link_url = Helper::topicURL($item->id, '', $item);
                    $title = $item->$title_var;
                    $league = $item->league;
                    $team = $item->team;
                    $match = $item->match;
                    $i++;
                    ?>
                    @if ($i > 5 && $i <= 10)
                        <div class="ms-item"><span class="ms-badge gold-badge">
                                @if ($item->league)
                                    {{ $item->league->$name_var }}
                                @elseif($item->category($item->id))
                                    {{ $item->category($item->id)->$title_var }}
                                @else
                                    {{ $item->webmasterSection->$title_var }}
                                @endif
                            </span>
                            <a href="{{ $topic_link_url }}">
                                <p>{{ $item->$title_var }}</p>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
