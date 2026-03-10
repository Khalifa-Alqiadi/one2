<?php
$title_var = "title_".@Helper::currentLanguage()->code;
$title_var2 = "title_".config('smartend.default_language');
$details_var = "details_".@Helper::currentLanguage()->code;
$details_var2 = "details_".config('smartend.default_language');

$block_style = "";
if (@$TopicBlock->bg_color != "") {
    $block_style = "background-color: ".@$TopicBlock->bg_color.";";
}
if (@$TopicBlock->divider_status) {
    @$TopicBlock->css_classes .= " divider";
}
if (@$TopicBlock->image_status && @$TopicBlockContents->{"bg_".@Helper::currentLanguage()->code} != "") {
    $block_style .= "background-image: url(".route("fileView",
            ["path" => 'topics/'.@$TopicBlockContents->{"bg_".@Helper::currentLanguage()->code}]).");background-size:cover;background-repeat: no-repeat;background-position: center top;";
}
$BlockTopics = [];
$section_url = "";
if (@$TopicBlockContents->module_id) {
    $BlockTopics = Helper::Topics(@$TopicBlockContents->module_id, @$TopicBlockContents->category_ids,
        @$TopicBlockContents->records_count, 0, @$TopicBlockContents->records_order);
    $section_url = Helper::sectionURL(@$TopicBlockContents->module_id);
}
?>

@if(count($BlockTopics) >0)
    <section id="landing-block-{{ @$TopicBlock->id }}" class="partners landing-block {{ @$TopicBlock->css_classes  }}" style="{{ $block_style }}">
        <div class="container">
            @if(@$TopicBlock->title_status || @$TopicBlock->desc_status)
                <div class="section-title">
                    @if(@$TopicBlock->title_status)
                        <h2>{{ @$TopicBlockContents->{"title_".@Helper::currentLanguage()->code} }}</h2>
                    @endif
                    @if(@$TopicBlock->desc_status)
                        <p>{!! nl2br(@$TopicBlockContents->{"desc_".@Helper::currentLanguage()->code}) !!}</p>
                    @endif
                </div>
            @endif
            <div class="partners-slider d-none"></div>
            <div class="partner-padding">
                <div class="border">
                    <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6">
                        @foreach($BlockTopics as $HomePartner)
                                <?php
                                if ($HomePartner->$title_var != "") {
                                    $title = $HomePartner->$title_var;
                                } else {
                                    $title = $HomePartner->$title_var2;
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
                            <div class="col mb-1">
                                <div class="thumbnail">
                                    @if($URL !="")
                                        <a href="{{ $URL }}" target="_blank">
                                            <img
                                                src="{{ route("fileView",["path" =>'topics/'.$HomePartner->photo_file ]) }}?w=200&h=200"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="{{ $title }}" width="100%" height="100%" loading="lazy"
                                                alt="{{ $title }}">
                                        </a>
                                    @else
                                        <img
                                            src="{{ route("fileView",["path" =>'topics/'.$HomePartner->photo_file ]) }}?w=200&h=200"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="{{ $title }}" width="100%" height="100%" loading="lazy"
                                            alt="{{ $title }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>                
                {{-- <div class="swiper-pagination"></div> --}}
            </div>

            @if (@$TopicBlock->more_btn_status)
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
            @endif
        </div>
    </section>
@endif
