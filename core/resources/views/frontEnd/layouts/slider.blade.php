@if(@$BannersSettingsId >0)
        <?php
        $SliderBanners = Helper::BannersList($BannersSettingsId);
        ?>
    @if(count($SliderBanners)>0)
        <section id="hero">
            <div class="hero-container hero-slider">
                @foreach($SliderBanners->slice(0,1) as $SliderBanner)
                        <?php
                        try {
                            $SliderBanner_type = $SliderBanner->webmasterBanner->type;
                        } catch (Exception $e) {
                            $SliderBanner_type = 0;
                        }
                        ?>
                @endforeach
                    <?php
                    $title_var = "title_".@Helper::currentLanguage()->code;
                    $title_var2 = "title_".config('smartend.default_language');
                    $details_var = "details_".@Helper::currentLanguage()->code;
                    $details_var2 = "details_".config('smartend.default_language');
                    $file_var = "file_".@Helper::currentLanguage()->code;
                    $file_var2 = "file_".config('smartend.default_language');
                    $link_var = "link_".@Helper::currentLanguage()->code;
                    ?>
                @if($SliderBanner_type==0)
                    {{-- Text/Code Banners--}}
                    <div class="text-center">
                        @foreach($SliderBanners as $SliderBanner)
                                <?php
                                if ($SliderBanner->$details_var != "") {
                                    $BDetails = $SliderBanner->$details_var;
                                } else {
                                    $BDetails = $SliderBanner->$details_var2;
                                }
                                ?>
                            @if($BDetails !="")
                                <div>{!! $BDetails !!}</div>
                            @endif
                        @endforeach
                    </div>
                @elseif($SliderBanner_type==1)
                    {{-- Photo Slider Banners--}}
                    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel"
                         data-bs-interval="5000">

                        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

                        <div class="carousel-inner">

                            @php($i=0)
                            @foreach($SliderBanners as $SliderBanner)
                                    <?php
                                    if ($SliderBanner->$title_var != "") {
                                        $BTitle = $SliderBanner->$title_var;
                                    } else {
                                        $BTitle = $SliderBanner->$title_var2;
                                    }
                                    $BDetails = $SliderBanner->$details_var;
                                    if ($SliderBanner->$file_var != "") {
                                        $BFile = $SliderBanner->$file_var;
                                    } else {
                                        $BFile = $SliderBanner->$file_var2;
                                    }
                                    ?>
                                <div
                                    class="lazyload carousel-item {{ ($i==0)?"active":"" }} {{ ($BDetails =="" && $SliderBanner->$link_var=="")?"carousel-item-clear":"" }}"
                                    style="background-image: url('{{ route("fileView",["path" =>'banners/'.$BFile ]) }}');">
                                    <div class="carousel-container">
                                        <div class="carousel-content container">
                                            <div class="slider-content">
                                                @if($BDetails !="" || $SliderBanner->$link_var!="")
                                                    @if($BTitle !="")
                                                        <h2 class="animate__animated animate__fadeInDown slider-title">{!! $BTitle !!}</h2>
                                                    @endif

                                                    @if($BDetails !="")
                                                        <p class="animate__animated animate__fadeInUp slider-details">{!! nl2br($BDetails) !!}</p>
                                                    @endif

                                                    @if($SliderBanner->$link_var !="")
                                                        <a href="{!! $SliderBanner->$link_var !!}"
                                                           class="btn-theme animate__animated animate__fadeInUp slider-link">{{ __('frontend.moreDetails') }}</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php($i++)
                            @endforeach
                        </div>
                        @if(count($SliderBanners) >1)
                            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
                            </a>
                            <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
                                <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
                            </a>
                        @else
                            <style>
                                .carousel-indicators {
                                    display: none;
                                }
                            </style>
                        @endif
                    </div>
                @else
                    {{-- Video Banners--}}
                    <div class="text-center">
                        @foreach($SliderBanners as $SliderBanner)
                                <?php
                                if ($SliderBanner->$title_var != "") {
                                    $BTitle = $SliderBanner->$title_var;
                                } else {
                                    $BTitle = $SliderBanner->$title_var2;
                                }
                                if ($SliderBanner->$details_var != "") {
                                    $BDetails = $SliderBanner->$details_var;
                                } else {
                                    $BDetails = $SliderBanner->$details_var2;
                                }
                                if ($SliderBanner->$file_var != "") {
                                    $BFile = $SliderBanner->$file_var;
                                } else {
                                    $BFile = $SliderBanner->$file_var2;
                                }
                                ?>
                            @if($SliderBanner->youtube_link !="")
                                @if($SliderBanner->video_type ==1)
                                        <?php
                                        $Youtube_id = Helper::Get_youtube_video_id($SliderBanner->youtube_link);
                                        ?>
                                    @if($Youtube_id !="")
                                        {{-- Youtube Video --}}
                                        <iframe width="100%" height="500" frameborder="0" allowfullscreen
                                                src="https://www.youtube.com/embed/{{ $Youtube_id }}?autoplay=1&mute=1"
                                                allow="autoplay">
                                        </iframe>
                                    @endif
                                @elseif($SliderBanner->video_type ==2)
                                        <?php
                                        $Vimeo_id = Helper::Get_vimeo_video_id($SliderBanner->youtube_link);
                                        ?>
                                    @if($Vimeo_id !="")
                                        {{-- Vimeo Video --}}
                                        <iframe width="100%" height="500" frameborder="0" allowfullscreen
                                                src="https://player.vimeo.com/video/{{ $Vimeo_id }}?title=0&amp;byline=0">
                                        </iframe>
                                    @endif
                                @endif
                            @endif
                            @if($SliderBanner->video_type ==0)
                                @if($BFile !="")
                                    {{-- Direct Video --}}
                                    <video width="100%" height="500" controls autoplay>
                                        <source src="{{ route("fileView",["path" =>'banners/'.$BFile ]) }}"
                                                type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            @endif
                            @if($BDetails !="")
                                <div>{!! $BDetails !!}</div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif
    @push('after-styles')
        @if(Helper::GeneralSiteSettings("style_header") && Helper::GeneralSiteSettings("style_bg_type"))
            <style>
                .fixed-top-margin {
                    margin-top: 0 !important;
                }


                .header-bg, .header-bg a {
                    color: #444444;
                }

                @media (min-width: 968px) {

                    .header-no-bg, .header-no-bg a, .topbar-no-bg, .topbar-no-bg a {
                        color: #fff;
                    }

                    .header-no-bg .navbar a, .topbar-no-bg .header-dropdown .btn {
                        color: #fff;
                    }

                    .dropdown-item {
                        color: #212529 !important;
                    }

                    .header-scrolled .navbar a, .header-scrolled .header-dropdown .btn {
                        color: #444444;
                    }
                }

                .topbar-no-bg {
                    box-shadow: 0 0 1px rgba(255, 255, 255, 0.5) !important;
                }
            </style>
        @endif
    @endpush
@endif
