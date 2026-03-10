<?php
$HomePartnersLimit = 0; ; // 0 = all
$HomePartners = Helper::Topics(Helper::GeneralWebmasterSettings("home_content3_section_id"), 0, $HomePartnersLimit, 1);
?>
@if(count($HomePartners)>0)
    <section id="partners" class="partners section-bg">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('frontend.partners') }}</h2>
                <p>{{ __('frontend.partnersMsg') }}</p>
            </div>
            <div class="partners-slider swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    <?php
                    $ii = 0;
                    $title_var = "title_" . @Helper::currentLanguage()->code;
                    $title_var2 = "title_" . config('smartend.default_language');
                    $details_var = "details_" . @Helper::currentLanguage()->code;
                    $details_var2 = "details_" . config('smartend.default_language');
                    $section_url = "";
                    ?>

                    @foreach($HomePartners as $HomePartner)
                        <?php
                        if ($HomePartner->$title_var != "") {
                            $title = $HomePartner->$title_var;
                        } else {
                            $title = $HomePartner->$title_var2;
                        }

                        if ($section_url == "") {
                            $section_url = Helper::sectionURL($HomePartner->webmaster_id);
                        }
                        $URL = "";
                        if (count($HomePartner->fields) > 0) {
                            foreach ($HomePartner->fields as $t_field) {
                                if ($t_field->field_value != "") {
                                    if (@filter_var($t_field->field_value, FILTER_VALIDATE_URL)) {
                                        $URL = $t_field->field_value;
                                    }
                                }
                            }
                        }
                        ?>
                        <div class="swiper-slide">
                            <div class="thumbnail">
                                @if($URL !="")
                                    <a href="{{ $URL }}" target="_blank">
                                        <img
                                            src="{{ route("fileView",["path" =>'topics/'.$HomePartner->photo_file ]) }}?w=200&h=200"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="{{ $title }}" width="100%" height="100%"  loading="lazy"
                                            alt="{{ $title }}">
                                    </a>
                                @else
                                    <img
                                        src="{{ route("fileView",["path" =>'topics/'.$HomePartner->photo_file ]) }}?w=200&h=200"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="{{ $title }}"  width="100%" height="100%"  loading="lazy"
                                        alt="{{ $title }}">
                                @endif
                            </div>
                        </div>
                        <?php
                        $ii++;
                        ?>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
@endif
