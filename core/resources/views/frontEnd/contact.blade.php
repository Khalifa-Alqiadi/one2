@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <?php
        $title_var = "title_".@Helper::currentLanguage()->code;
        $title_var2 = "title_".config('smartend.default_language');
        $details_var = "details_".@Helper::currentLanguage()->code;
        $details_var2 = "details_".config('smartend.default_language');
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
        $section = "";
        $category_image = "";
        try {
            if ($Topic->section->$title_var != "") {
                $section = $Topic->section->$title_var;
            } else {
                $section = $Topic->section->$title_var2;
            }
        } catch (Exception $e) {
            $section = "";
        }
        if (@$WebmasterSection != "none") {
            if (@$WebmasterSection->$title_var != "") {
                $webmaster_section_title = @$WebmasterSection->$title_var;
            } else {
                $webmaster_section_title = @$WebmasterSection->$title_var2;
            }
            $page_title = $webmaster_section_title;
            if (@$WebmasterSection->photo != "") {
                $category_image = route("fileView", ["path" => 'topics/'.@$WebmasterSection->photo]);
            }
        }

        $custom_css_code = $Topic->css_code;
        $custom_js_code = $Topic->js_code;
        $custom_body_code = $Topic->body_code;
        ?>
        <section class="breadcrumbs d-flex align-items-end justify-content-center" style="background-image: url({{$category_image}})">
            <div class="container">

                <div class="">
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __("backend.home") }}</a></li>
                        <li>{{ $title }}</li>
                    </ol>
                </div>

            </div>
        </section>
        @if (@$WebmasterSection->type == 10)
            @if($Topic->topicBlocks->where("status",1)->count() >0)
                @foreach($Topic->topicBlocks->where("status",1) as $TopicBlock)
                    @if ($TopicBlock->content != "")
                            <?php
                            try {
                                $TopicBlockContents = json_decode($TopicBlock->content);
                            } catch (Exception $e) {
                                $TopicBlockContents = [];
                            }
                            ?>
                        @if($TopicBlock->type == 4)
                            {{--Form--}}
                            @if(@$TopicBlockContents->view_style !="")
                                @if(@$TopicBlockContents->module_id >0)
                                    @include('frontEnd.landing.'.strtolower(str_replace(" ","",@$TopicBlockContents->view_style)))
                                @else
                                    @include('frontEnd.landing.contact')
                                @endif
                            @endif
                        @endif
                        @if($TopicBlock->type == 3)
                            {{--Dynamic--}}
                            @if(@$TopicBlockContents->view_style !="")
                                @include('frontEnd.landing.'.strtolower(str_replace(" ","",@$TopicBlockContents->view_style)))
                            @endif
                        @elseif($TopicBlock->type == 2)
                            {{--Banners--}}
                            @if(@$TopicBlockContents->banner_area_id >0 && @$TopicBlockContents->banner_style !="")
                                @include('frontEnd.layouts.'.@$TopicBlockContents->banner_style,["BannersSettingsId"=>@$TopicBlockContents->banner_area_id])
                            @endif
                        @elseif($TopicBlock->type == 1)
                            {{--Custom Code--}}
                            @include('frontEnd.landing.code')
                        @elseif($TopicBlock->type == 0)
                            {{--Static Content--}}
                            @include('frontEnd.landing.static')
                        @endif
                    @endif
                @endforeach
            @endif
        @endif
        <section id="contact" class="contact">
            @if (@$WebmasterSection->type == 0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <article class="mb-5">
                                @if($WebmasterSection->type==2 && $Topic->video_file!="")
                                    {{--video--}}
                                    <div class="post-video">
                                        <div class="video-container">
                                            @if($Topic->video_type ==1)
                                                    <?php
                                                    $Youtube_id = Helper::Get_youtube_video_id($Topic->video_file);
                                                    ?>
                                                @if($Youtube_id !="")
                                                    {{-- Youtube Video --}}
                                                    <iframe allowfullscreen
                                                            src="https://www.youtube.com/embed/{{ $Youtube_id }}">
                                                    </iframe>
                                                @endif
                                            @elseif($Topic->video_type ==2)
                                                    <?php
                                                    $Vimeo_id = Helper::Get_vimeo_video_id($Topic->video_file);
                                                    ?>
                                                @if($Vimeo_id !="")
                                                    {{-- Vimeo Video --}}
                                                    <iframe allowfullscreen
                                                            src="https://player.vimeo.com/video/{{ $Vimeo_id }}?title=0&amp;byline=0">
                                                    </iframe>
                                                @endif

                                            @else
                                                <video width="100%" height="300" controls>
                                                    <source src="{{ route("fileView",["path" =>'topics/'.$Topic->video_file ]) }}"
                                                            type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif


                                        </div>
                                    </div>
                                @elseif($WebmasterSection->type==3 && $Topic->audio_file!="")
                                    {{--audio--}}
                                    <div class="post-video">
                                        <div class="video-container">
                                            <audio controls>
                                                <source src="{{ route("fileView",["path" =>'topics/'.$Topic->audio_file ]) }}"
                                                        type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>

                                        </div>
                                    </div>

                                @elseif(count($Topic->photos)>0)
                                    {{--photo slider--}}
                                    <div class="post-slider">
                                        <!-- start flexslider -->
                                        <div id="post-slider" class="flexslider">
                                            <ul class="slides">
                                                @if($Topic->photo_file !="")
                                                    <li>
                                                        <img src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}"
                                                             alt="{{ $title }}"/>
                                                    </li>
                                                @endif
                                                @foreach($Topic->photos as $photo)
                                                    <li>
                                                        <img src="{{ route("fileView",["path" =>'topics/'.$photo->file ]) }}"
                                                             alt="{{ $photo->title  }}"/>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                        <!-- end flexslider -->
                                    </div>

                                @else
                                    {{--one photo--}}
                                    <div class="post-image">
                                        @if($Topic->photo_file !="")
                                            <img src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file ]) }}"
                                                 alt="{{ $title }}"/>
                                        @endif
                                    </div>
                                @endif

                                {!! $Topic->$details !!}
                                @if($Topic->attach_file !="")
                                        <?php
                                        $file_ext = strrchr($Topic->attach_file, ".");
                                        $file_ext = strtolower($file_ext);
                                        ?>
                                    <div class="bottom-article">
                                        @if($file_ext ==".jpg"|| $file_ext ==".jpeg"|| $file_ext ==".png"|| $file_ext ==".gif")
                                            <div class="text-center">
                                                <img src="{{ route("fileView",["path" =>'topics/'.$Topic->attach_file ]) }}"
                                                     alt="{{ $title }}"/>
                                            </div>
                                        @else
                                            <a href="{{ route("fileView",["path" =>'topics/'.$Topic->attach_file ]) }}">
                                                <strong><i class="fa fa-paperclip"></i>
                                                    &nbsp;{{ __('frontend.downloadAttach') }}</strong>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @include("frontEnd.topic.files")
                            </article>
                        </div>
                    </div>
                    @include("frontEnd.topic.maps")
                </div>
            @endif
            <div class="container">

                @if($custom_body_code !="")
                    {!! Blade::render($custom_body_code) !!}
                @endif

                <div class="row">

                    <div class="col-lg-4">
                        <h3 class="sub-title">{{ __('frontend.contactDetails') }}</h3>
                        <div class="info">
                            @if(Helper::GeneralSiteSettings("contact_t1_" . @Helper::currentLanguage()->code) !="")
                                <div class="address">
                                    <i class="bi bi-geo-alt"></i>
                                    <h4>{{ __('frontend.address') }}:</h4>
                                    <p>{{ Helper::GeneralSiteSettings("contact_t1_" . @Helper::currentLanguage()->code) }}</p>
                                </div>
                            @endif
                            @if(Helper::GeneralSiteSettings("contact_t3") !="")
                                <div class="phone">
                                    <i class="bi bi-telephone"></i>
                                    <h4>{{ __('frontend.callPhone') }}:</h4>
                                    <p><span
                                            dir="ltr">{{ Helper::GeneralSiteSettings("contact_t3") }}</span></p>
                                </div>
                            @endif
                            @if(Helper::GeneralSiteSettings("contact_t5") !="")
                                <div class="phone">
                                    <i class="bi bi-telephone"></i>
                                    <h4>{{ __('frontend.callMobile') }}:</h4>
                                    <p><span
                                            dir="ltr">{{ Helper::GeneralSiteSettings("contact_t5") }}</span></p>
                                </div>
                            @endif
                            @if(Helper::GeneralSiteSettings("contact_t4") !="")
                                <div class="phone">
                                    <i class="fa fa-fax"></i>
                                    <h4>{{ __('frontend.callFax') }}:</h4>
                                    <p><span
                                            dir="ltr">{{ Helper::GeneralSiteSettings("contact_t4") }}</span></p>
                                </div>
                            @endif
                            @if(Helper::GeneralSiteSettings("contact_t6") !="")
                                <div class="email">
                                    <i class="bi bi-envelope"></i>
                                    <h4>{{ __('frontend.email') }}:</h4>
                                    <p>{{ Helper::GeneralSiteSettings("contact_t6") }}</p>
                                </div>
                            @endif
                            @if(Helper::GeneralSiteSettings("contact_t7_" . @Helper::currentLanguage()->code) !="")
                                <div class="email">
                                    <i class="bi bi-clock"></i>
                                    <h4>{{ __('frontend.callTimes') }}:</h4>
                                    <p>{{ Helper::GeneralSiteSettings("contact_t7_" . @Helper::currentLanguage()->code) }}</p>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col-lg-8 mt-5 mt-lg-0" id="contact-form-container">
                        <h3 class="sub-title">{{ __('frontend.getInTouch') }}</h3>
                        <form method="POST" action="{{ route("contactPageSubmit") }}" class="php-email-form" id="contactForm">
                            @csrf
                            @honeypot
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <input type="text" autocomplete="off" name="contact_name" id="contact_name" value="" required maxlength="100"
                                           placeholder="{{ __('frontend.yourName') }}" class="form-control"/>
                                </div>
                                <div class="col-md-4 form-group mt-3 mt-md-0">
                                    <input type="email" autocomplete="off" name="contact_email" id="contact_email" value="" required maxlength="100"
                                           placeholder="{{ __('frontend.yourEmail') }}" class="form-control"/>
                                </div>
                                <div class="col-md-4 form-group mt-3 mt-md-0">
                                    <input type="text" autocomplete="off" name="contact_phone" id="contact_phone" value="" required minlength="5" maxlength="15" placeholder="{{ __('frontend.phone') }}" class="form-control" dir="ltr"/>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <input type="text" autocomplete="off" name="contact_subject" id="contact_subject" value="" required maxlength="100"
                                       placeholder="{{ __('frontend.subject') }}" class="form-control"/>
                            </div>
                            <div class="form-group mt-3">
                                <textarea name="contact_message" id="contact_message" class="form-control" placeholder="{{ __('frontend.message') }}" rows="10" required></textarea>
                            </div>

                            @if(config('smartend.nocaptcha_status'))
                                <div class="form-group mb-3">
                                    {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                                    {!! NoCaptcha::display() !!}
                                </div>
                            @endif
                            <div class="submit-message"></div>
                            <div>
                                <button type="submit" id="contactFormSubmit" class="btn btn-lg btn-theme"><i
                                        class="fa-solid fa-paper-plane"></i> {{ __('frontend.sendMessage') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('frontEnd.layouts.popup',['Popup'=>@$Popup])
@endsection

@push('before-styles')
    <link rel="stylesheet"
          href="{{ URL::asset('assets/frontend/vendor/intl-tel-input/css/intlTelInput.min.css') }}?v={{ Helper::system_version() }}"/>
@endpush
@push('after-scripts')
    <script
        src="{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/intlTelInput.min.js') }}?v={{ Helper::system_version() }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#contactForm').submit(function (evt) {
                evt.preventDefault();
                let btn = $('#contactFormSubmit');
                btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('frontend.sendMessage') !!}");
                btn.prop('disabled', true);
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route("contactPageSubmit") }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        let stat = 'alert-danger';
                        if (result.stat === 'success') {
                            stat = 'alert-success';
                            $('#contactForm')[0].reset();
                        }
                        let confirm = '<div class="alert ' + stat + ' alert-dismissible fade show mt-3" role="alert">' + result.msg + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        $("#contactForm .submit-message").html(confirm);
                        btn.html('<i class="fa-solid fa-paper-plane"></i> {!! __('frontend.sendMessage') !!}');
                        btn.prop('disabled', false);
                    }
                });
                return false;
            });
        });

        var iti = window.intlTelInput(document.querySelector("#contact_phone"), {
            showSelectedDialCode: true,
            countrySearch: true,
            initialCountry: "auto",
            separateDialCode: true,
            hiddenInput: function () {
                return {
                    phone: "contact_phone_full",
                    country: "contact_phone_country_code"
                };
            },
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "us";
                    callback(countryCode.toLowerCase());
                    iti.setCountry(countryCode.toLowerCase());
                });
            },
            utilsScript: "{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/utils.js') }}?v={{ Helper::system_version() }}",
        });
    </script>
@endpush
@if($custom_css_code !="" || $custom_js_code !="")
    @push('after-styles')
        @if($custom_css_code !="")
            <style>
                {!! $custom_css_code !!}
            </style>
        @endif
        {!! $custom_js_code !!}
    @endpush
@endif
