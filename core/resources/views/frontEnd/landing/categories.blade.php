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
$BlockCategories = [];
$section_url = "";
if (@$TopicBlockContents->module_id) {
    $BlockCategories = Helper::SectionCategories(@$TopicBlockContents->module_id, @$TopicBlockContents->records_count,
        @$TopicBlockContents->category_ids);
    $section_url = Helper::sectionURL(@$TopicBlockContents->module_id);
}
?>

@if(count($BlockCategories) >0)
    <section id="landing-block-{{ @$TopicBlock->id }}" class="categories landing-block {{ @$TopicBlock->css_classes  }}" style="{{ $block_style }}">
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
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($BlockCategories as $Category)
                        <?php
                        if ($Category->$title_var != "") {
                            $title = $Category->$title_var;
                        } else {
                            $title = $Category->$title_var2;
                        }
                        if ($Category->$details_var != "") {
                            $details = $details_var;
                        } else {
                            $details = $details_var2;
                        }
                        $category_link_url = Helper::categoryURL($Category->id, "", $Category);
                        $HomeSectionType = @$Topic->webmasterSection->type;
                        if (!@$require_mp3_player && $HomeSectionType == 3) {
                            $require_mp3_player = 1;
                        }
                        ?>
                    <div class="col">
                        <a href="{{ $category_link_url }}">
                            <div class="card h-100 border-0">
                                <div class="card-body text-center p-4">
                                    @if($Category->photo !="")
                                        <div class="pic mb-3">
                                            <img class="img-fluid rounded-circle" loading="lazy"
                                                 src="{{ route("fileView",["path" =>'sections/'.$Category->photo ]) }}?w=200&h=200&r=fit" alt="{{ $title }}"/>
                                        </div>
                                    @elseif($Category->icon !="")
                                        <i class="{{ $Category->icon }} fa-3x text-primary mb-3"></i>
                                    @endif
                                    <h5 class="card-title fw-bold">{{ $title }}</h5>
                                    <p class="card-text text-muted">{!! nl2br($Category->$details) !!}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            @if (@$TopicBlock->more_btn_status)
                <div class="row mt-4">
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
