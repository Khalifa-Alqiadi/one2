@if(!empty($Topic))
    <article>
        <div class="card post-card pa-0 mb-4 border-0 p-0">
            @if($Topic->webmasterSection->type==2 && $Topic->video_file!="")
                {{--video--}}
                <a href="{{ $topic_link_url }}">
                    <div class="video-container position-relative">
                        @if ($Topic->photo_file != "")
                            <img class="card-img-top"
                                 src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}?w=450&h=450" width="100%" height="100%"
                                 alt="{{ $title }}" loading="lazy"/>
                        @else
                                <?php
                                $img_url = "";
                                ?>
                            @if($Topic->video_type ==1)
                                    <?php
                                    $Youtube_id = Helper::Get_youtube_video_id($Topic->video_file);
                                    $img_url = "http://img.youtube.com/vi/".$Youtube_id."/0.jpg";
                                    ?>
                                <img class="card-img-top" src="{{ $img_url }}" width="100%" height="100%"
                                     alt="{{ $title }}" loading="lazy"/>
                            @else
                                <div
                                    class="bg-secondary w-100 rounded-top h-200px"></div>
                            @endif
                        @endif
                        <h2 class="position-absolute top-50 start-50 translate-middle">
                                                                    <span
                                                                        class="badge  rounded opacity-75  bg-white text-primary"><i
                                                                            class="fa-solid fa-play"></i></span>
                        </h2>
                    </div>
                </a>
            @elseif($Topic->webmasterSection->type==3 && $Topic->audio_file!="")
                {{--audio--}}
                <div class="audio-container position-relative">
                    @if($Topic->photo_file !="")
                        <img class="card-img-top" loading="lazy" width="100%" height="100%"
                             src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}?w=450&h=450"
                             alt="{{ $title }}"/>
                    @endif
                    @if($Topic->video_type ==3)
                        <div class="audio-embed">
                            {!! $Topic->audio_file !!}
                        </div>
                    @else
                        <div class="audio-player">
                            <audio crossorigin preload="none">
                                <source
                                    src="{{ route("fileView",["path" =>'topics/'.$Topic->audio_file ]) }}"
                                    type="audio/mpeg">
                            </audio>
                        </div>
                    @endif
                </div>
            @elseif(count($Topic->photos)>0)
                {{--photo slider--}}
                <a href="{{ $topic_link_url }}">
                    <div class="image-container position-relative">
                        @if($Topic->photo_file !="")
                            <img class="card-img-top" loading="lazy" width="100%" height="100%"
                                 src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}?w=450&h=450"
                                 alt="{{ $title }}"/>

                        @else
                            @foreach($Topic->photos->random(1) as $photo)
                                <img class="card-img-top" loading="lazy"
                                     src="{{ route("fileView",["path" =>'topics/'.$photo->file ]) }}?w=450&h=450" width="100%" height="100%"
                                     alt="{{ $title }}"/>
                            @endforeach
                        @endif
                        @if($Topic->photos->count() >1)
                            <span class="extra-images-count"><span
                                    class="badge  rounded-pill text-primary bg-light"><i class="fa-solid fa-images"></i> {{ $Topic->photos->count() }}</span>
                            </span>
                        @endif
                    </div>
                </a>
            @else
                {{--one photo--}}
                {{-- <a href="{{ $topic_link_url }}"> --}}
                    <div class="image-container position-relative">
                        @if($Topic->photo_file !="")
                            <img class="card-img-top" loading="lazy" width="100%" height="100%"
                                 src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}?w=450&h=450"
                                 alt="{{ $title }}"/>
                        @else
                            @foreach(@$Topic->webmasterSection->customFields->where("type", 8) as $TopicPhotoCustomField)
                                @if($TopicPhotoCustomField->lang_code == "all" || $TopicPhotoCustomField->lang_code == @Helper::currentLanguage()->code)
                                    @foreach(@$Topic->fields->where("field_id", $TopicPhotoCustomField->id) as $Photo)
                                        @if($loop->first)
                                            @if(@$Photo->field_value !="")
                                                <img class="card-img-top" loading="lazy" width="100%" height="100%"
                                                     src="{{ route("fileView",["path" =>'topics/'.$Photo->field_value ]) }}?w=450&h=450"
                                                     alt="{{ $title }}"/>
                                                @break
                                            @endif
                                        @endif
                                    @endforeach
                                    @break
                                @endif
                            @endforeach
                        @endif
                    </div>
                {{-- </a> --}}

            @endif
            <div class="mt-3">
                <h4 class="card-title">
                    <a href="{{ $topic_link_url }}" class="stretched-link">
                        @if($Topic->icon !="")
                            <i class="{!! $Topic->icon !!} "></i>&nbsp;
                        @endif
                        {{ $title }}
                    </a>
                    @if(@$Topic->webmasterSection->id == 8)
                        <i class="fa-solid fa-arrow-right"></i>
                    @endif
                </h4>  
                              
                {{--Additional Feilds--}}
                {{-- @include("frontEnd.topic.fields",["cols"=>12,"Fields"=>@$Topic->webmasterSection->customFields->where("in_listing",true)])


                <p class="card-text mb-0 mt-2">
                    @if(strip_tags($Topic->$details) !="")
                        {!! mb_substr(strip_tags($Topic->$details),0, 180)."..." !!}
                    @endif
                    <a href="{{ $topic_link_url }}" class="read-more-link">{{ __("frontend.moreDetails") }}</a>
                </p> --}}

            </div>
        </div>
    </article>
@endif
