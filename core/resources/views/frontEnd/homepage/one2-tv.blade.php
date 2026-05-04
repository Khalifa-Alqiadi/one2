@php
    $featureds = Helper::getFeaturedTopic(5);
    $name_var  = 'name_'  . @Helper::currentLanguage()->code;
    $title_var = 'title_' . @Helper::currentLanguage()->code;
@endphp

@if(count($featureds) > 0)
@php
    $featureds  = collect($featureds);
    $mainTopic  = $featureds->first();
    $sideTopics = $featureds->slice(1, 4);
@endphp

<section class="one2tv">
    <div class="container">

        {{-- ── Header ── --}}
        <div class="one2tv__head">
            <div class="one2tv__head-brand">
                <span class="one2tv__head-icon">
                    <img src="{{ URL::to('uploads/settings/tv.svg') }}" alt="OneTwo TV">
                </span>
                <h2 class="one2tv__head-title">OneTwo TV</h2>
            </div>
            <a href="#" class="one2tv__see-all">
                عرض الكل <i class="bi bi-arrow-left"></i>
            </a>
        </div>

        {{-- ── Grid ── --}}
        <div class="one2tv__grid">

            {{-- Main --}}
            @if($mainTopic)
            @php
                $url     = Helper::topicURL($mainTopic->id, '', $mainTopic);
                $title   = $mainTopic->$title_var;
                $cat     = $mainTopic->league
                    ? $mainTopic->league->$name_var
                    : ($mainTopic->category($mainTopic->id)
                        ? $mainTopic->category($mainTopic->id)->$title_var
                        : $mainTopic->webmasterSection->$title_var);
                $img = $mainTopic->photo_file
                    ? route('fileView', ['path' => 'topics/'.$mainTopic->photo_file]).'?w=900&h=506'
                    : '';
                if (!$img && $mainTopic->video_type == 1) {
                    $t   = Helper::getThumbnail($mainTopic->video_file);
                    $img = $t['url'] ?? $t['webp'] ?? '';
                }
                $hasVideo = $mainTopic->video_file || $mainTopic->video_url;
            @endphp
            <a href="{{ $url }}" class="one2tv__main">
                <div class="one2tv__main-media">
                    @if($img)
                        <img src="{{ $img }}" alt="{{ $title }}" class="one2tv__main-img">
                    @else
                        <div class="one2tv__main-img--empty"></div>
                    @endif
                    <div class="one2tv__main-overlay"></div>
                    @if($hasVideo)
                        <span class="one2tv__play"><i class="bi bi-play-fill"></i></span>
                    @endif
                </div>
                <div class="one2tv__main-foot">
                    <span class="one2tv__cat">{{ $cat }}</span>
                    <h3 class="one2tv__main-title">{{ \Illuminate\Support\Str::limit($title, 95) }}</h3>
                    <span class="one2tv__more">اقرأ المزيد <i class="bi bi-arrow-left"></i></span>
                </div>
            </a>
            @endif

            {{-- Side --}}
            <div class="one2tv__side">
                @foreach($sideTopics as $item)
                @php
                    $url  = Helper::topicURL($item->id, '', $item);
                    $ttl  = $item->$title_var;
                    $cat  = $item->league
                        ? $item->league->$name_var
                        : ($item->category($item->id)
                            ? $item->category($item->id)->$title_var
                            : $item->webmasterSection->$title_var);
                    $img  = $item->photo_file
                        ? route('fileView', ['path' => 'topics/'.$item->photo_file]).'?w=400&h=225'
                        : '';
                    if (!$img && $item->video_type == 1) {
                        $t   = Helper::getThumbnail($item->video_file);
                        $img = $t['url'] ?? $t['webp'] ?? '';
                    }
                    $hasV = $item->video_file || $item->video_url;
                @endphp
                <a href="{{ $url }}" class="one2tv__card">
                    <div class="one2tv__card-media">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $ttl }}" loading="lazy">
                        @else
                            <div class="one2tv__card-media--empty"></div>
                        @endif
                        @if($hasV)
                            <span class="one2tv__card-play"><i class="bi bi-play-fill"></i></span>
                        @endif
                    </div>
                    <div class="one2tv__card-body">
                        <span class="one2tv__cat one2tv__cat--sm">{{ $cat }}</span>
                        <p class="one2tv__card-title">{{ \Illuminate\Support\Str::limit($ttl, 72) }}</p>
                    </div>
                </a>
                @endforeach
            </div>

        </div>{{-- /grid --}}
    </div>
</section>
@endif
