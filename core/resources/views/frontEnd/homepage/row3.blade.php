<?php
$StaffLimit = 6; // 0 = all
$Staff = Helper::Topics(Helper::GeneralWebmasterSettings("home_content5_section_id"), 0, $StaffLimit, 0);
?>
@if(count($Staff)>0)
    <section id="staff" class="staff">
        <div class="container">

            <div class="section-title">
                <h2>{{ __('frontend.homeStaffTitle') }}</h2>
                <p>{{ __('frontend.homeStaffDesc') }}</p>
            </div>

            <div class="row">
                <?php
                $section_url = "";
                ?>
                @foreach($Staff as $Topic)
                    <?php
                    if ($Topic->$title_var != "") {
                        $title = $Topic->$title_var;
                    } else {
                        $title = $Topic->$title_var2;
                    }
                    if ($Topic->$details_var != "") {
                        $details = $details_var;
                    } else {
                        $details = $details_var2;
                    }
                    if ($section_url == "") {
                        $section_url = Helper::sectionURL($Topic->webmaster_id);
                    }
                    $topic_link_url = Helper::topicURL($Topic->id,"",$Topic);
                    $HomeSectionType = @$Topic->webmasterSection->type;
                    if (!@$require_mp3_player && $HomeSectionType == 3) {
                        $require_mp3_player = 1;
                    }
                    ?>
                    <div class="col-lg-6 mb-4">
                        <div class="member d-flex align-items-start">
                            @if($Topic->photo_file !="")
                                <a href="{{ $topic_link_url }}">
                                    <div class="pic">
                                        <img class="img-fluid" loading="lazy"
                                             src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}?w=120&h=120&r=fit" width="120" height="120"
                                             alt="{{ $title }}"/>
                                    </div>
                                </a>
                            @endif
                            <div class="member-info">
                                <a href="{{ $topic_link_url }}"><h3>{!! $title !!}</h3></a>
                                {{--Additional Feilds--}}
                                @include("frontEnd.topic.fields",["cols"=>12,"Fields"=>@$Topic->webmasterSection->customFields->where("in_listing",true)])
                                <span></span>

                                @if(strip_tags($Topic->$details) !="")
                                    <p>
                                        {!! mb_substr(strip_tags($Topic->$details),0, 140)."..." !!}
                                        <a href="{{ $topic_link_url }}">{{ __("frontend.moreDetails") }}</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="more-btn">
                        <a href="{{ url($section_url) }}" class="btn btn-theme"><i
                                class="fa fa-angle-left"></i>&nbsp; {{ __('frontend.viewMore') }}
                            &nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
