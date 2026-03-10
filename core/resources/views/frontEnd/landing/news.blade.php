<?php
$title_var = 'title_' . @Helper::currentLanguage()->code;
$title_var2 = 'title_' . config('smartend.default_language');
$details_var = 'details_' . @Helper::currentLanguage()->code;
$details_var2 = 'details_' . config('smartend.default_language');

$block_style = '';
if (@$TopicBlock->bg_color != '') {
    $block_style = 'background-color: ' . @$TopicBlock->bg_color . ';';
}
if (@$TopicBlock->divider_status) {
    @$TopicBlock->css_classes .= ' divider';
}
if (@$TopicBlock->image_status && @$TopicBlockContents->{'bg_' . @Helper::currentLanguage()->code} != '') {
    $block_style .= 'background-image: url(' . route('fileView', ['path' => 'topics/' . @$TopicBlockContents->{'bg_' . @Helper::currentLanguage()->code}]) . ');background-size:cover;background-repeat: no-repeat;background-position: center top;';
}
$BlockTopics = [];
$section_url = '';
if (@$TopicBlockContents->module_id) {
    $BlockTopics = Helper::Topics(@$TopicBlockContents->module_id, @$TopicBlockContents->category_ids, @$TopicBlockContents->records_count, 0, @$TopicBlockContents->records_order);
    $section_url = Helper::sectionURL(@$TopicBlockContents->module_id);
}
?>

@if (count($BlockTopics) > 0)
    <section id="landing-block-{{ @$TopicBlock->id }}" class="news landing-block {{ @$TopicBlock->css_classes }}"
        style="{{ $block_style }}">
        <div class="container">
            @if (@$TopicBlock->title_status || @$TopicBlock->desc_status)
                <div class="section-title">
                    @if (@$TopicBlock->title_status)
                        <h2>{{ @$TopicBlockContents->{'title_' . @Helper::currentLanguage()->code} }}</h2>
                    @endif
                    @if (@$TopicBlock->desc_status)
                        <p>{!! nl2br(@$TopicBlockContents->{'desc_' . @Helper::currentLanguage()->code}) !!}</p>
                    @endif
                </div>
            @endif

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
                @foreach ($BlockTopics as $Topic)
                    <?php
                    if ($Topic->$title_var != '') {
                        $title = $Topic->$title_var;
                    } else {
                        $title = $Topic->$title_var2;
                    }
                    if ($Topic->$details_var != '') {
                        $details = $details_var;
                    } else {
                        $details = $details_var2;
                    }
                    $topic_link_url = Helper::topicURL($Topic->id, '', $Topic);
                    $HomeSectionType = @$Topic->webmasterSection->type;
                    if (!@$require_mp3_player && $HomeSectionType == 3) {
                        $require_mp3_player = 1;
                    }
                    $Category = @$Topic->category($Topic->id);
                    ?>
                    <div class="col mb-4">
                        <div class="card border-0 p-0 ba-transparent">
                            @if ($Topic->photo_file != '')
                                <a href="{{ $topic_link_url }}" class="">
                                    <div class="card-header p-0 ba-transparent">
                                        <img class="img-fluid" loading="lazy"
                                            src="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}?w=140&h=140&r=fit"
                                            alt="{{ $title }}" />
                                    </div>
                                </a>
                            @endif
                            <div class="card-body px-0">
                                @if ($Category)
                                    <strong
                                        class="d-inline-block mb-1 text-muted">{{ @$Topic->category($Topic->id)->$title_var }}</strong>
                                @endif
                                <h4 class="mb-2 text-primary item-title">
                                    <a href="{{ $topic_link_url }}">
                                        {!! $title !!}
                                    </a>
                                </h4>
                                {{-- @if (strip_tags($Topic->$details) != '')
                                    <p class="card-text mb-auto">
                                        {!! mb_substr(strip_tags($Topic->$details), 0, 120) . '...' !!}
                                    </p>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <div class="row">
                <div class="col-md-6">
                    <?php
                    $Topic = @$BlockTopics->first();
                    if (@$Topic->$title_var != '') {
                        $title = @$Topic->$title_var;
                    } else {
                        $title = @$Topic->$title_var2;
                    }
                    if (@$Topic->$details_var != '') {
                        $details = $details_var;
                    } else {
                        $details = $details_var2;
                    }
                    $topic_link_url = Helper::topicURL(@$Topic->id, '', $Topic);
                    $HomeSectionType = @$Topic->webmasterSection->type;
                    if (!@$require_mp3_player && $HomeSectionType == 3) {
                        $require_mp3_player = 1;
                    }
                    $Category = @$Topic->category(@$Topic->id);
                    ?>
                    <div class="news-big border-0 p-0">
                        @if (@$Topic->photo_file != '')
                            <a href="{{ $topic_link_url }}" class="col-auto d-lg-block">
                                <div class="pic mb-3">
                                    <img class="img-fluid" loading="lazy"
                                        src="{{ route('fileView', ['path' => 'topics/' . @$Topic->photo_file]) }}?w=700&h=500&r=fit"
                                        alt="{{ $title }}" />
                                </div>
                            </a>
                        @endif
                        <div class="px-0">
                            @if ($Category)
                                <div class="text-muted fw-semibold text-decoration-none">
                                    {{ @$Topic->category(@$Topic->id)->$title_var }}</div>
                            @endif
                            <h3 class="fw-semibold my-2 ">
                                <a class="text-decoration-none text-primary"
                                    href="{{ $topic_link_url }}">{!! $title !!}</a>
                            </h3>
                            @include('frontEnd.topic.fields', [
                                'cols' => 12,
                                'Fields' => @$Topic->webmasterSection->customFields->where('in_listing', true),
                            ])
                            @if (strip_tags(@$Topic->$details) != '')
                                <p class="mb-auto">
                                    {!! mb_substr(strip_tags(@$Topic->$details), 0, 300) . '...' !!}
                                    <a href="{{ $topic_link_url }}">{{ __('frontend.moreDetails') }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        @foreach ($BlockTopics->slice(1) as $Topic)
                            <?php
                            if ($Topic->$title_var != '') {
                                $title = $Topic->$title_var;
                            } else {
                                $title = $Topic->$title_var2;
                            }
                            if ($Topic->$details_var != '') {
                                $details = $details_var;
                            } else {
                                $details = $details_var2;
                            }
                            $topic_link_url = Helper::topicURL($Topic->id, '', $Topic);
                            $HomeSectionType = @$Topic->webmasterSection->type;
                            if (!@$require_mp3_player && $HomeSectionType == 3) {
                                $require_mp3_player = 1;
                            }
                            $Category = @$Topic->category($Topic->id);
                            ?>
                            <div class="col-md-12">
                                <div class="news-item mb-3">
                                    <div class="row">
                                        @if ($Topic->photo_file != '')
                                            <a href="{{ $topic_link_url }}" class="col-12 col-md-5 d-lg-block">
                                                <div class="pic">
                                                    <img class="img-fluid" loading="lazy"
                                                        src="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}?w=140&h=140&r=fit"
                                                        alt="{{ $title }}" />
                                                </div>
                                            </a>
                                        @endif
                                        <div class="col p-1 d-flex flex-column position-static">
                                            @if ($Category)
                                                <strong
                                                    class="d-inline-block mb-1 text-muted">{{ @$Topic->category($Topic->id)->$title_var }}</strong>
                                            @endif
                                            <a href="{{ $topic_link_url }}">
                                                <h3 class="mb-2 text-primary item-title">{!! $title !!}</h3>
                                            </a>
                                            @include('frontEnd.topic.fields', [
                                                'cols' => 12,
                                                'Fields' => @$Topic->webmasterSection->customFields->where(
                                                    'in_listing',
                                                    true),
                                            ])
                                            @if (strip_tags($Topic->$details) != '')
                                                <p class="card-text mb-auto">
                                                    {!! mb_substr(strip_tags($Topic->$details), 0, 80) . '...' !!}
                                                    <a
                                                        href="{{ $topic_link_url }}">{{ __('frontend.moreDetails') }}</a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div> --}}
            @if (@$TopicBlock->more_btn_status)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="more-btn">
                            <a href="{{ url($section_url) }}" class="btn btn-theme"><i
                                    class="fa fa-angle-left"></i>&nbsp; {{ __('frontend.viewMore') }}
                                &nbsp;<i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
