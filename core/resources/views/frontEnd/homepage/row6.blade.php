<?php
$TestimonialsLimit = 0; // 0 = all
$Testimonials = Helper::Topics(Helper::GeneralWebmasterSettings("home_content6_section_id"), 0, $TestimonialsLimit, 1);
?>
@if(count($Testimonials)>0)
    <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('frontend.homeTestimonialsTitle') }}</h2>
                <p>{{ __('frontend.homeTestimonialsDesc') }}</p>
            </div>

            <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    <?php
                    $section_url = "";
                    ?>
                    @foreach($Testimonials as $Topic)
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
                        <div class="swiper-slide">
                            <div class="testimonial-wrap">
                                <div class="testimonial-item">
                                    @if($Topic->photo_file !="")
                                        <img class="testimonial-img"
                                             src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}?w=90&h=90&r=fit" width="90" height="90"  loading="lazy"
                                             alt="{{ $title }}"/>
                                    @endif
                                    <h3>{{ $title }}</h3>
                                    {{--Additional Feilds--}}
                                    @include("frontEnd.topic.fields",["cols"=>12,"Fields"=>@$Topic->webmasterSection->customFields->where("in_listing",true)])
                                    @if(strip_tags($Topic->$details) !="")
                                        <p>
                                            <i class="fa-solid fa-quote-left quote-icon-left"></i>
                                            {!! strip_tags($Topic->$details) !!}
                                            <i class="fa-solid fa-quote-right quote-icon-right"></i>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>
    </section>
@endif
