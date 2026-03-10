@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <?php
        $title_var = 'title_' . @Helper::currentLanguage()->code;
        $title_var2 = 'title_' . config('smartend.default_language');
        $details_var = 'details_' . @Helper::currentLanguage()->code;
        $details_var2 = 'details_' . config('smartend.default_language');
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
        $section = '';
        try {
            if ($Topic->section->$title_var != '') {
                $section = $Topic->section->$title_var;
            } else {
                $section = $Topic->section->$title_var2;
            }
        } catch (Exception $e) {
            $section = '';
        }
        
        $webmaster_section_title = '';
        $category_title = '';
        $page_title = '';
        $category_image = '';
        
        $custom_css_code = @$WebmasterSection->css_code;
        $custom_js_code = @$WebmasterSection->js_code;
        $custom_body_code = @$WebmasterSection->body_code;
        
        if (@$WebmasterSection != 'none') {
            if (@$WebmasterSection->$title_var != '') {
                $webmaster_section_title = @$WebmasterSection->$title_var;
            } else {
                $webmaster_section_title = @$WebmasterSection->$title_var2;
            }
            $page_title = $webmaster_section_title;
            if (@$WebmasterSection->photo != '') {
                $category_image = route('fileView', ['path' => 'topics/' . @$WebmasterSection->photo]);
            }
        }
        if (!empty($CurrentCategory)) {
            if (@$CurrentCategory->$title_var != '') {
                $category_title = @$CurrentCategory->$title_var;
            } else {
                $category_title = @$CurrentCategory->$title_var2;
            }
            $page_title = $category_title;
            if (@$CurrentCategory->photo != '') {
                $category_image = route('fileView', ['path' => 'sections/' . @$CurrentCategory->photo]);
            }
            $custom_css_code .= @$CurrentCategory->css_code;
            $custom_js_code .= @$CurrentCategory->js_code;
            $custom_body_code .= @$CurrentCategory->body_code;
        }
        
        $custom_css_code .= $Topic->css_code;
        $custom_js_code .= $Topic->js_code;
        $custom_body_code .= $Topic->body_code;
        
        $attach_file = @$Topic->attach_file;
        if (@$Topic->attach_file != '') {
            $file_ext = strrchr($Topic->attach_file, '.');
            $file_ext = strtolower($file_ext);
            if (in_array($file_ext, ['.jpg', '.jpeg', '.png', '.gif', '.webp'])) {
                $category_image = route('fileView', ['path' => 'topics/' . @$Topic->attach_file]);
                $attach_file = '';
            }
        }
        if ($title != '') {
            $page_title = $title;
        }
        if ($WebmasterSection->type == 2 && $Topic->video_file != '') {
            if ($Topic->video_type == 1) {
                $url = Helper::getThumbnail($Topic->video_file);
                $category_image = $url['url'] ?? $url['webp'];
            }
        }
        ?>
        @if ($category_image != '')
            {{-- @include("frontEnd.topic.cover") --}}
        @endif
        <section class="breadcrumbs d-flex align-items-end justify-content-end"
            style="background-image: url({{ $category_image }})">
            <div class="container">
                <div class="">
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __('backend.home') }}</a></li>
                        @if ($webmaster_section_title != '')
                            <li class="active"><a
                                    href="{{ Helper::sectionURL(@$WebmasterSection->id) }}">{!! @$WebmasterSection->id == 1 ? $title : $webmaster_section_title !!}</a>
                            </li>
                        @else
                            <li class="active">{{ $title }}</li>
                        @endif
                        @if ($category_title != '')
                            <li class="active"><a
                                    href="{{ Helper::categoryURL(@$CurrentCategory->id) }}">{{ $category_title }}</a>
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
        </section>
        @if (!$Topic->status)
            <div class="container">
                <div class="alert alert-warning alert-dismissible fade show mt-3 mb-0" role="alert">
                    <i class="fa-solid fa-warning"></i> {{ __('backend.pageIsNotPublished') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        <section id="content">
            <div class="container topic-page">
                <div class="row">
                    {{-- @if ($Categories->count() > 1)
                        @include('frontEnd.layouts.side')
                    @endif --}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @if (@$WebmasterSection->id == 5)
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="video-container video-youtube">

                                        @if ($Topic->video_type == 1)
                                            <?php
                                            // $Youtube_id = Helper::Get_youtube_video_id($Topic->video_file);
                                            // $url = "https://www.youtube.com/embed/$Youtube_id?autoplay=1&mute=1";
                                            $url = $Topic->video_file; // مثال
                                            $video = Helper::oembed($url);
                                            ?>
                                            
                                            @if ($url != '')
                                                {{-- Youtube Video --}}
                                                {!! $video['embed_html'] !!}
                                            @endif
                                        @elseif($Topic->video_type == 2)
                                            <?php
                                            $Vimeo_id = Helper::Get_vimeo_video_id($Topic->video_file);
                                            ?>
                                            @if ($Vimeo_id != '')
                                                {{-- Vimeo Video --}}
                                                <iframe allowfullscreen class="video-iframe"
                                                    src="https://player.vimeo.com/video/{{ $Vimeo_id }}?title=0&amp;byline=0">
                                                </iframe>
                                            @endif
                                        @elseif($Topic->video_type == 3)
                                            @if ($Topic->video_file != '')
                                                {{-- Embed Video --}}
                                                {!! $Topic->video_file !!}
                                            @endif
                                        @else
                                            <video class="video-js" controls autoplay preload="auto" width="100%"
                                                height="500"
                                                poster="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}"
                                                data-setup="{}">
                                                <source
                                                    src="{{ route('fileView', ['path' => 'topics/' . $Topic->video_file]) }}"
                                                    type="video/mp4" />
                                                <p class="vjs-no-js">
                                                    To view this video please enable JavaScript, and
                                                    consider
                                                    upgrading
                                                    to a
                                                    web browser that
                                                    <a href="https://videojs.com/html5-video-support/"
                                                        target="_blank">supports
                                                        HTML5 video</a>
                                                </p>
                                            </video>
                                        @endif

                                    </div>
                                    @if ($WebmasterSection->title_status)
                                        <div class="post-heading">
                                            <h1>
                                                @if ($Topic->icon != '')
                                                    <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                @endif
                                                {{ $title }}
                                            </h1>
                                        </div>
                                    @endif
                                    @include('frontEnd.topic.fields', [
                                        'cols' => 6,
                                        'Fields' => @$WebmasterSection->customFields->where('in_page', true),
                                    ])

                                    <div class="article-body">
                                        @if (@$WebmasterSection->editor_status)
                                            {!! str_replace('"#', '"' . Request::url() . '#', $Topic->$details) !!}
                                        @else
                                            {!! nl2br($Topic->$details) !!}
                                        @endif
                                        @if ($custom_body_code != '')
                                            {!! Blade::render($custom_body_code) !!}
                                        @endif
                                    </div>
                                </div>
                                @include('frontEnd.topic.side-video')
                            @elseif (@$WebmasterSection->id == 17)
                                <?php
                                $url = $Topic->video_file; // مثال
                                $video = Helper::oembed($url);
                                ?>
                                <article class="mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="meta d-flex h-100 align-items-end justify-content-end">
                                                <div class="top-line">
                                                    <div style="min-width:0;">
                                                        <p class="title">{{ $video['title'] }}</p>

                                                        <div class="channel">
                                                            <div class="avatar">
                                                                {{-- نستخدم Thumbnail كصورة رمزية بشكل سريع --}}
                                                                <img src="{{ $video['thumbnail'] }}" alt="">
                                                            </div>

                                                            <a href="{{ $video['channel_url'] }}" target="_blank"
                                                                style="color:inherit; text-decoration:none; font-weight:800;">
                                                                {{ $video['channel'] }}
                                                            </a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="video-container video-short">
                                                {{-- iframe جاهز من oEmbed --}}
                                                {!! $video['embed_html'] !!}

                                                <div class="actions" data-video-url="{{ $url }}">

                                                    <button class="action js-share" type="button" aria-label="Share">
                                                        ↗️
                                                    </button>
                                                </div>


                                                <!-- Modal بسيط للتعليقات -->
                                                <div class="modal" id="commentModal" aria-hidden="true">
                                                    <div class="modal-card">
                                                        <div class="modal-head">
                                                            <strong class="py-4 m-auto">{{ __('frontend.share') }}</strong>
                                                            <button class="x" id="closeModal" type="button">✕</button>
                                                        </div>
                                                        @include('frontEnd.topic.share-video')
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @else
                                <article class="mb-5">

                                    @if ($WebmasterSection->type == 2 && $Topic->video_file != '')
                                        {{-- video --}}
                                        <div class="post-video">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @if ($WebmasterSection->title_status)
                                                        <div class="post-heading">
                                                            <h1>
                                                                @if ($Topic->icon != '')
                                                                    <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                                @endif
                                                                {{ $title }}
                                                            </h1>
                                                        </div>
                                                    @endif
                                                    @include('frontEnd.topic.fields', [
                                                        'cols' => 6,
                                                        'Fields' => @$WebmasterSection->customFields->where(
                                                            'in_page',
                                                            true),
                                                    ])

                                                    <div class="article-body">
                                                        @if (@$WebmasterSection->editor_status)
                                                            {!! str_replace('"#', '"' . Request::url() . '#', $Topic->$details) !!}
                                                        @else
                                                            {!! nl2br($Topic->$details) !!}
                                                        @endif
                                                        @if ($custom_body_code != '')
                                                            {!! Blade::render($custom_body_code) !!}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="video-container text-center {{ $WebmasterSection->id == 17 ? 'video-short' : ($Topic->video_type == 1 ? 'video-youtube' : '') }}">

                                                        @if ($Topic->video_type == 1)
                                                            <?php
                                                            $Youtube_id = Helper::Get_youtube_video_id($Topic->video_file);
                                                            $url = "https://www.youtube.com/embed/$Youtube_id?autoplay=1&mute=1";
                                                            if ($WebmasterSection->id == 17) {
                                                                $img_url = Helper::getThumbnail($Topic->video_file);
                                                                $url = 'https://www.youtube.com/embed/' . $img_url['id'] . '?autoplay=1&mute=1';
                                                            }
                                                            
                                                            ?>
                                                            @if ($url != '')
                                                                {{-- Youtube Video --}}
                                                                <iframe allowfullscreen class="video-iframe"
                                                                    src="{{ $url }}" allow="autoplay">
                                                                </iframe>
                                                            @endif
                                                        @elseif($Topic->video_type == 2)
                                                            <?php
                                                            $Vimeo_id = Helper::Get_vimeo_video_id($Topic->video_file);
                                                            ?>
                                                            @if ($Vimeo_id != '')
                                                                {{-- Vimeo Video --}}
                                                                <iframe allowfullscreen class="video-iframe"
                                                                    src="https://player.vimeo.com/video/{{ $Vimeo_id }}?title=0&amp;byline=0">
                                                                </iframe>
                                                            @endif
                                                        @elseif($Topic->video_type == 3)
                                                            @if ($Topic->video_file != '')
                                                                {{-- Embed Video --}}
                                                                {!! $Topic->video_file !!}
                                                            @endif
                                                        @else
                                                            <video class="video-js" controls autoplay preload="auto"
                                                                width="100%" height="500"
                                                                poster="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}"
                                                                data-setup="{}">
                                                                <source
                                                                    src="{{ route('fileView', ['path' => 'topics/' . $Topic->video_file]) }}"
                                                                    type="video/mp4" />
                                                                <p class="vjs-no-js">
                                                                    To view this video please enable JavaScript, and
                                                                    consider
                                                                    upgrading
                                                                    to a
                                                                    web browser that
                                                                    <a href="https://videojs.com/html5-video-support/"
                                                                        target="_blank">supports
                                                                        HTML5 video</a>
                                                                </p>
                                                            </video>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @elseif($WebmasterSection->type == 3 && $Topic->audio_file != '')
                                        {{-- audio --}}
                                        <div class="post-audio">
                                            @if ($WebmasterSection->title_status)
                                                <div class="post-heading">
                                                    <h1>
                                                        @if ($Topic->icon != '')
                                                            <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                        @endif
                                                        {{ $title }}
                                                    </h1>
                                                </div>
                                            @endif
                                            @if ($Topic->photo_file != '')
                                                <img src="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}"
                                                    loading="lazy" alt="{{ $title }}" />
                                            @endif
                                            @if ($Topic->video_type == 3)
                                                <div class="audio-embed">
                                                    {!! $Topic->audio_file !!}
                                                </div>
                                            @else
                                                {{-- <div class="audio-player">
                                                <audio crossorigin preload="none">
                                                    <source
                                                        src="{{ route("fileView",["path" =>'topics/'.$Topic->audio_file ]) }}"
                                                        type="audio/mpeg">
                                                </audio>
                                            </div> --}}
                                            @endif
                                        </div>
                                        @include('frontEnd.topic.fields', [
                                            'cols' => 6,
                                            'Fields' => @$WebmasterSection->customFields->where('in_page', true),
                                        ])

                                        <div class="article-body">
                                            @if (@$WebmasterSection->editor_status)
                                                {!! str_replace('"#', '"' . Request::url() . '#', $Topic->$details) !!}
                                            @else
                                                {!! nl2br($Topic->$details) !!}
                                            @endif
                                            @if ($custom_body_code != '')
                                                {!! Blade::render($custom_body_code) !!}
                                            @endif
                                        </div>
                                        <br>
                                    @elseif(count($Topic->photos) > 0)
                                        {{-- photo slider --}}
                                        <div>
                                            @if ($WebmasterSection->title_status)
                                                <div class="post-heading">
                                                    <h1>
                                                        @if ($Topic->icon != '')
                                                            <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                        @endif
                                                        {{ $title }}
                                                    </h1>
                                                </div>
                                            @endif

                                            @if ($Topic->photo_file != '')
                                                <div class="post-image mb-2">
                                                    <a href="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}"
                                                        class="galelry-lightbox" title="{{ $title }}">
                                                        <img loading="lazy"
                                                            src="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}"
                                                            alt="{{ $title }}" class="post-main-photo">
                                                    </a>
                                                </div>
                                            @endif

                                            <div id="gallery" class="gallery line-frame mb-3 post-gallery">
                                                <div class="row g-0 m-0">
                                                    <?php
                                                    $cols_lg = 3;
                                                    $cols_md = 4;
                                                    if ($Categories->count() > 1) {
                                                        $cols_lg = 4;
                                                        $cols_md = 6;
                                                    }
                                                    if ($Topic->photos->count() == 3) {
                                                        $cols_lg = 4;
                                                        $cols_md = 4;
                                                    }
                                                    if ($Topic->photos->count() == 2) {
                                                        $cols_lg = 6;
                                                        $cols_md = 6;
                                                    }
                                                    ?>
                                                    @foreach ($Topic->photos as $photo)
                                                        <div
                                                            class="col-lg-{{ $cols_lg }} col-md-{{ $cols_md }}">
                                                            <div class="gallery-item">
                                                                <a href="{{ route('fileView', ['path' => 'topics/' . $photo->file]) }}"
                                                                    class="galelry-lightbox" title="{{ $photo->title }}">
                                                                    <img src="{{ route('fileView', ['path' => 'topics/' . $photo->file]) }}"
                                                                        loading="lazy" alt="{{ $photo->title }}"
                                                                        class="img-fluid">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @include('frontEnd.topic.fields', [
                                            'cols' => 6,
                                            'Fields' => @$WebmasterSection->customFields->where('in_page', true),
                                        ])

                                        <div class="article-body">
                                            @if (@$WebmasterSection->editor_status)
                                                {!! str_replace('"#', '"' . Request::url() . '#', $Topic->$details) !!}
                                            @else
                                                {!! nl2br($Topic->$details) !!}
                                            @endif
                                            @if ($custom_body_code != '')
                                                {!! Blade::render($custom_body_code) !!}
                                            @endif
                                        </div>
                                    @else
                                        {{-- one photo --}}
                                        <div class="post-image">
                                            <div class="row">
                                                @if ($Topic->photo_file != '')
                                                    <div class="col-md-6">
                                                        @if ($WebmasterSection->title_status)
                                                            <div class="post-heading">
                                                                <h1>
                                                                    @if ($Topic->icon != '')
                                                                        <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                                    @endif
                                                                    {{ $title }}
                                                                </h1>
                                                            </div>
                                                        @endif
                                                        @include('frontEnd.topic.fields', [
                                                            'cols' => 6,
                                                            'Fields' => @$WebmasterSection->customFields->where(
                                                                'in_page',
                                                                true),
                                                        ])

                                                        <div class="article-body">
                                                            @if (@$WebmasterSection->editor_status)
                                                                {!! str_replace('"#', '"' . Request::url() . '#', $Topic->$details) !!}
                                                            @else
                                                                {!! nl2br($Topic->$details) !!}
                                                            @endif
                                                            @if ($custom_body_code != '')
                                                                {!! Blade::render($custom_body_code) !!}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="image">
                                                            <img src="{{ route('fileView', ['path' => 'topics/' . $Topic->photo_file]) }}"
                                                                loading="lazy" alt="{{ $title }}"
                                                                title="{{ $title }}" class="post-main-photo" />
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($WebmasterSection->title_status)
                                                        <div class="post-heading">
                                                            <h1>
                                                                @if ($Topic->icon != '')
                                                                    <i class="{!! $Topic->icon !!} "></i>&nbsp;
                                                                @endif
                                                                {{ $title }}
                                                            </h1>
                                                        </div>
                                                    @endif
                                                    @include('frontEnd.topic.fields', [
                                                        'cols' => 6,
                                                        'Fields' => @$WebmasterSection->customFields->where(
                                                            'in_page',
                                                            true),
                                                    ])

                                                    <div class="article-body">
                                                        @if (@$WebmasterSection->editor_status)
                                                            {!! str_replace('"#', '"' . Request::url() . '#', $Topic->$details) !!}
                                                        @else
                                                            {!! nl2br($Topic->$details) !!}
                                                        @endif
                                                        @if ($custom_body_code != '')
                                                            {!! Blade::render($custom_body_code) !!}
                                                        @endif
                                                    </div>
                                                    @foreach (@$Topic->webmasterSection->customFields->where('type', 8) as $TopicPhotoCustomField)
                                                        @if ($TopicPhotoCustomField->lang_code == 'all' || $TopicPhotoCustomField->lang_code == @Helper::currentLanguage()->code)
                                                            @foreach (@$Topic->fields->where('field_id', $TopicPhotoCustomField->id) as $Photo)
                                                                @if ($loop->first)
                                                                    @if (@$Photo->field_value != '')
                                                                        <img src="{{ route('fileView', ['path' => 'topics/' . @$Photo->field_value]) }}"
                                                                            loading="lazy" alt="{{ $title }}"
                                                                            title="{{ $title }}"
                                                                            class="post-main-photo" />
                                                                        <br>
                                                                        @break
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                            @break

                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endif


                                    @if ($attach_file != '')
                                        <?php
                                        $file_ext = strrchr($Topic->attach_file, '.');
                                        $file_ext = strtolower($file_ext);
                                        ?>
                                        <div class="bottom-article">
                                            <a href="{{ route('fileView', ['path' => 'topics/' . $Topic->attach_file]) }}"
                                                target="_blank">
                                                <strong>
                                                    {!! Helper::GetIcon(route('fileView', ['path' => 'topics/' . $Topic->attach_file])) !!}
                                                    &nbsp;{{ __('frontend.downloadAttach') }}</strong>
                                            </a>
                                        </div>
                                    @endif
                                </article>
                                @include('frontEnd.topic.files')

                                @include('frontEnd.topic.maps')

                                @include('frontEnd.topic.tags')

                                @if ($WebmasterSection->type == 7)
                                    <a href="{!! Helper::sectionURL($Topic->webmaster_id) !!}" class="btn btn-lg btn-secondary"
                                        style="width: 100%"><i class="fa-solid fa-reply"></i>
                                        {{ __('backend.clickToNewSearch') }}
                                    </a>
                                @else
                                    @include('frontEnd.topic.share')
                                @endif

                                @include('frontEnd.topic.comments')

                                @if (@$Topic->form_id > 0)
                                    <br>
                                    @include('frontEnd.form', ['FormSectionID' => @$Topic->form_id])
                                @elseif($WebmasterSection->order_status)
                                    @include('frontEnd.topic.order')
                                @endif

                                @include('frontEnd.topic.related')
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('frontEnd.layouts.popup', ['Popup' => @$Popup])
@endsection
@if (@$WebmasterSection->id == 17)
    @push('before-scripts')
        <script>
            // if (!actions){ return};


            const shareBtn = document.querySelector('.js-share');

            const modal = document.getElementById('commentModal');
            const closeModal = document.getElementById('closeModal');

            function openModal() {
                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false');
                loadComments();
                setTimeout(() => commentInput?.focus(), 50);
            }

            function closeIt() {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
            }

            shareBtn.addEventListener('click', openModal);
            closeModal.addEventListener('click', closeIt);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeIt();
            });
        </script>
    @endpush
@endif
@if (@$WebmasterSection->id == 5)
    @push('before-scripts')
        <script>
            // if (!actions){ return};

            // سجل أي تفاعل مرة واحدة
            window.addEventListener('pointerdown', () => {
            localStorage.setItem('user_interacted', '1');
            }, { once: true });

        </script>
    @endpush
@endif
@if (@in_array(@$WebmasterSection->type, [2]))
    @push('before-styles')
        <link rel="stylesheet"
            href="{{ URL::asset('assets/frontend/vendor/video-js/css/video-js.min.css') }}?v={{ Helper::system_version() }}" />
    @endpush
    @push('after-scripts')
        <script
            src="{{ URL::asset('assets/frontend/vendor/video-js/js/video-js.min.css') }}?v={{ Helper::system_version() }}">
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                GreenAudioPlayer.init({
                    selector: '.audio-player',
                    stopOthersOnPlay: true,
                    showTooltips: true,
                });
            });
        </script>
    @endpush
@endif
@if (@in_array(@$WebmasterSection->type, [3]) && !@$Topic->video_type)
    @push('before-styles')
        <link rel="stylesheet"
            href="{{ URL::asset('assets/frontend/vendor/green-audio-player/css/green-audio-player.min.css') }}?v={{ Helper::system_version() }}" />
    @endpush
    @push('after-scripts')
        <script
            src="{{ URL::asset('assets/frontend/vendor/green-audio-player/js/green-audio-player.min.js') }}?v={{ Helper::system_version() }}">
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                GreenAudioPlayer.init({
                    selector: '.audio-player',
                    stopOthersOnPlay: true,
                    showTooltips: true,
                });
            });
        </script>
    @endpush
@endif
@if ($custom_css_code != '' || $custom_js_code != '')
    @push('after-styles')
        @if ($custom_css_code != '')
            <style>
                {!! $custom_css_code !!}
            </style>
        @endif
        {!! $custom_js_code !!}
    @endpush
@endif

@push('after-styles')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const share = document.querySelector('.fa-square-share-nodes');
            const socialNetwork = document.querySelector('.social-network');

            if (!share || !socialNetwork) return;

            share.addEventListener('click', function() {
                socialNetwork.classList.toggle('active');
            });
        });
    </script>
@endpush
