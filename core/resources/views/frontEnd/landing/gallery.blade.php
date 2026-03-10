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
$PhotosLimit = @$TopicBlockContents->records_count;
if (@$TopicBlockContents->module_id) {
    $BlockTopics = Helper::Topics(@$TopicBlockContents->module_id, @$TopicBlockContents->category_ids,
        @$TopicBlockContents->records_count, 0, @$TopicBlockContents->records_order);
    $section_url = Helper::sectionURL(@$TopicBlockContents->module_id);
}
?>

@if(count($BlockTopics) >0)
    <section id="landing-block-{{ @$TopicBlock->id }}" class="gallery landing-block {{ @$TopicBlock->css_classes  }}" style="{{ $block_style }}">
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
        </div>

        <div class="container-fluid">
            <div class="row g-0">
                    <?php
                    $section_url = "";
                    $ph_count = 0;
                    ?>
                @foreach($BlockTopics as $BlockTopic)
                        <?php
                        if ($BlockTopic->$title_var != "") {
                            $title = $BlockTopic->$title_var;
                        } else {
                            $title = $BlockTopic->$title_var2;
                        }

                        if ($section_url == "") {
                            $section_url = Helper::sectionURL($BlockTopic->webmaster_id);
                        }
                        ?>
                    @foreach($BlockTopic->photos as $photo)
                        @if($ph_count<$PhotosLimit)
                            <div class="col-lg-3 col-md-4">
                                <div class="gallery-item">
                                    <a href="{{ route("fileView",["path" =>'topics/'.$photo->file ]) }}"
                                       class="galelry-lightbox" title="{{ $title }}">
                                        <img src="{{ route("fileView",["path" =>'topics/'.$photo->file ]) }}?w=400&h=400" width="100%" height="210" loading="lazy"
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
            @if (@$TopicBlock->more_btn_status)
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
            @endif
        </div>
    </section>
@endif
