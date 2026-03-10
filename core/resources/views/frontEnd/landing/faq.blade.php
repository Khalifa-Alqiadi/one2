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
        <?php
        $half = round(count($BlockTopics) / 2);
        $FAQs1 = @$BlockTopics->slice(0, $half);
        $FAQs2 = @$BlockTopics->slice($half, $half);
        ?>
    <section id="landing-block-{{ @$TopicBlock->id }}" class="faq landing-block {{ @$TopicBlock->css_classes  }}" style="{{ $block_style }}">
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

            <div class="accordion">
                <div class="row">
                        <?php
                        $i = 0;
                        ?>
                    <div class="col-lg-6 col-sm-12">
                        <div class="row">
                            @foreach($FAQs1 as $Topic)
                                    <?php
                                    if ($i == 2) {
                                        echo "</div><div class='row'>";
                                        $i = 0;
                                    }
                                    $i++;
                                    ?>
                                @include("frontEnd.topic.accordion",["Topic"=>$Topic,"CatId"=>0])
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="row">
                            @foreach($FAQs2 as $Topic)
                                    <?php
                                    if ($i == 2) {
                                        echo "</div><div class='row'>";
                                        $i = 0;
                                    }
                                    $i++;
                                    ?>
                                @include("frontEnd.topic.accordion",["Topic"=>$Topic,"CatId"=>0])
                            @endforeach
                        </div>
                    </div>
                </div>
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
