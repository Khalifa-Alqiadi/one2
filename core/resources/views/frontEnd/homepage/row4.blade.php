<?php
$HomePhotosLimit = 12; ; // 0 = all
$HomePhotos = Helper::Topics(Helper::GeneralWebmasterSettings("home_content2_section_id"), 0, $HomePhotosLimit, 1);
?>
@if(count($HomePhotos)>0)
    <section id="gallery" class="gallery">
        <div class="container">

            <div class="section-title">
                <h2>{{ __('frontend.homeContents2Title') }}</h2>
                <p>{{ __('frontend.homeContents2desc') }}</p>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row g-0 mb-2">
                <?php
                $section_url = "";
                $ph_count = 0;
                ?>
                @foreach($HomePhotos as $HomePhoto)
                    <?php
                    if ($HomePhoto->$title_var != "") {
                        $title = $HomePhoto->$title_var;
                    } else {
                        $title = $HomePhoto->$title_var2;
                    }

                    if ($section_url == "") {
                        $section_url = Helper::sectionURL($HomePhoto->webmaster_id);
                    }
                    ?>
                    @foreach($HomePhoto->photos as $photo)
                        @if($ph_count<$HomePhotosLimit)
                            <div class="col-lg-3 col-md-4">
                                <div class="gallery-item">
                                    <a href="{{ route("fileView",["path" =>'topics/'.$photo->file ]) }}"
                                       class="galelry-lightbox" title="{{ $title }}">
                                        <img src="{{ route("fileView",["path" =>'topics/'.$photo->file ]) }}?w=400&h=400" width="100%" height="210"  loading="lazy"
                                             alt="{{ $title }}" class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        @else
                            @break
                        @endif
                        <?php
                        $ph_count++;
                        ?>
                    @endforeach
                @endforeach

            </div>
            <div class="row mt-3">
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

