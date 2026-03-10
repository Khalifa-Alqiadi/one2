<?php
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
?>
<section id="landing-block-{{ @$TopicBlock->id }}" class="landing-block {{ @$TopicBlock->css_classes  }}" style="{{ $block_style }}">
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
        @include('frontEnd.form',["FormSectionID"=>@$TopicBlockContents->module_id,"viewStyle"=>"simple"])
    </div>
</section>
