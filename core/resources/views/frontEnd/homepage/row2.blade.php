<?php
$HomeTopicsLimit = 12; // 0 = all
$HomeTopics = Helper::Topics(Helper::GeneralWebmasterSettings("home_content1_section_id"), 0, $HomeTopicsLimit);
$require_mp3_player = 0;
?>
@if(count($HomeTopics)>0)
    <section id="topics" class="section-bg">

        <div class="container">

            <div class="section-title">
                <h2>{{ __('frontend.homeContents1Title') }}</h2>
                <p>{{ __('frontend.homeContents1desc') }}</p>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="owl-slider" class="owl-carousel owl-theme listing">
                        <?php
                        $section_url = "";
                        ?>
                        @foreach($HomeTopics as $Topic)
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
                            <div class="item">
                                @include("frontEnd.topic.card",["Topic"=>$Topic])
                            </div>
                        @endforeach

                    </div>
                </div>
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
    @if ($require_mp3_player)
        @push('before-styles')
            <link rel="stylesheet"
                  href="{{ URL::asset('assets/frontend/vendor/green-audio-player/css/green-audio-player.min.css') }}?v={{ Helper::system_version() }}"/>
        @endpush
        @push('after-scripts')
            <script
                src="{{ URL::asset('assets/frontend/vendor/green-audio-player/js/green-audio-player.min.js') }}?v={{ Helper::system_version() }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    GreenAudioPlayer.init({
                        selector: '.audio-player',
                        stopOthersOnPlay: true,
                        showTooltips: true,
                    });
                });
            </script>
        @endpush
    @endif
@endif
