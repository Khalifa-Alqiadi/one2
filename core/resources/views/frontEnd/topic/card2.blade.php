@if(!empty($Post))
        <?php
        $post_title_var = "title_".@Helper::currentLanguage()->code;
        $post_title_var2 = "title_".config('smartend.default_language');
        $details_var = "details_".@Helper::currentLanguage()->code;
        $details_var2 = "details_".config('smartend.default_language');
        $slug_var = "seo_url_slug_".@Helper::currentLanguage()->code;
        $slug_var2 = "seo_url_slug_".config('smartend.default_language');

        if ($Post->$post_title_var != "") {
            $post_title = $Post->$post_title_var;
        } else {
            $post_title = $Post->$post_title_var2;
        }
        if ($Post->$details_var != "") {
            $post_details = $details_var;
        } else {
            $post_details = $details_var2;
        }
        $topic_mv_link_url = Helper::topicURL($Post->id, "", $Post);

        if ($Post->webmasterSection->type == 3) {
            echo '<div class="card sidebar-card mb-3">';
        } else {
            echo '<a class="card sidebar-card mb-3" href="'.$topic_mv_link_url.'">';
        }
        ?>
    @if($Post->webmasterSection->type==2 && $Post->video_file!="")
        {{--video--}}
        <div class="video-container position-relative">
            @if ($Post->photo_file != "")
                <img class="card-img-top"
                     src="{{ route("fileView",["path" =>'topics/'.$Post->photo_file ]) }}?w=300&h=300" loading="lazy" width="100%" height="100%"
                     alt="{{ $post_title }}"/>
            @else
                    <?php
                    $img_url = "";
                    ?>
                @if($Post->video_type ==1)
                        <?php
                        $Youtube_id = Helper::Get_youtube_video_id($Post->video_file);
                        $img_url = "http://img.youtube.com/vi/".$Youtube_id."/0.jpg";
                        ?>
                    <img class="card-img-top" src="{{ $img_url }}" loading="lazy" width="100%" height="100%"
                         alt="{{ $post_title }}"/>
                @else
                    <div class="bg-secondary w-100 rounded-top h-200px"></div>
                @endif
            @endif
            <h2 class="position-absolute top-50 start-50 translate-middle">
                                                                    <span
                                                                        class="badge  rounded opacity-75  bg-white text-primary"><i
                                                                            class="fa-solid fa-play"></i></span>
            </h2>
        </div>
    @elseif($Post->webmasterSection->type==3 && $Post->audio_file!="")
        {{--audio--}}
        <div class="audio-container position-relative">
            @if($Post->photo_file !="")
                <img class="card-img-top" loading="lazy" width="100%" height="100%"
                     src="{{ route("fileView",["path" =>'topics/'.$Post->photo_file ]) }}?w=300&h=300"
                     alt="{{ $post_title }}"/>
            @endif
            @if($Post->video_type ==3)
                <div class="audio-embed">
                    {!! $Post->audio_file !!}
                </div>
            @else
                <div class="audio-player">
                    <audio crossorigin preload="none">
                        <source
                            src="{{ route("fileView",["path" =>'topics/'.$Post->audio_file ]) }}"
                            type="audio/mpeg">
                    </audio>
                </div>
            @endif
        </div>
    @elseif(count($Post->photos)>0)
        {{--photo slider--}}
        <div class="image-container position-relative">
            @if($Post->photo_file !="")
                <img class="card-img-top" loading="lazy" width="100%" height="100%"
                     src="{{ route("fileView",["path" =>'topics/'.$Post->photo_file ]) }}?w=300&h=300"
                     alt="{{ $post_title }}"/>

            @else
                @foreach($Post->photos->random(1) as $photo)
                    <img class="card-img-top" loading="lazy" width="100%" height="100%"
                         src="{{ route("fileView",["path" =>'topics/'.$photo->file ]) }}?w=300&h=300"
                         alt="{{ $post_title }}"/>
                @endforeach
            @endif
            @if($Post->photos->count() >1)
                <span class="extra-images-count"><span
                        class="badge  rounded-pill text-primary bg-light"><i class="fa-solid fa-images"></i> {{ $Post->photos->count() }}</span>
                </span>
            @endif
        </div>
    @else
        {{--one photo--}}
        <div class="image-container position-relative">
            @if($Post->photo_file !="")
                <img class="card-img-top" loading="lazy" width="100%" height="100%"
                     src="{{ route("fileView",["path" =>'topics/'.$Post->photo_file ]) }}?w=300&h=300"
                     alt="{{ $post_title }}"/>
            @else
                @foreach(@$Post->webmasterSection->customFields->where("type", 8) as $PostPhotoCustomField)
                    @if($PostPhotoCustomField->lang_code == "all" || $PostPhotoCustomField->lang_code == @Helper::currentLanguage()->code)
                        @foreach(@$Post->fields->where("field_id", $PostPhotoCustomField->id) as $Photo)
                            @if($loop->first)
                                @if(@$Photo->field_value !="")
                                    <img class="card-img-top" loading="lazy" width="100%" height="100%"
                                         src="{{ route("fileView",["path" =>'topics/'.@$Photo->field_value ]) }}?w=300&h=300"
                                         alt="{{ $post_title }}"/>
                                    @break
                                @endif
                            @endif
                        @endforeach
                        @break
                    @endif
                @endforeach
            @endif
        </div>
    @endif
    <div class="mt-3 mb-1">
        <h4 class="card-title mb-1">{{ $post_title }}</h4>
        @if(strip_tags($Post->$post_details) !="")
            <p class="card-text mb-0 mt-2">{!! mb_substr(strip_tags($Post->$post_details),0, 110)."..." !!}</p>
        @endif
    </div>
    @if ($Post->webmasterSection->type == 3)
        <a href="{{ $topic_mv_link_url }}" class="btn btn-sm btn-primary">{{ __("frontend.moreDetails") }}</a>
    @endif
        <?php
        if ($Post->webmasterSection->type == 3) {
            echo '</div>';
        } else {
            echo '</a>';
        }
        ?>
@endif
