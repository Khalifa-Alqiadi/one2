@if($WebmasterSection->tags_status)
    <?php
    $tags = [];
    foreach ($Topic->tags as $tag_item) {
        if (!empty($tag_item->tag)) {
            $tags[] = [
                "title"=>$tag_item->tag->title,
                "seo_url"=>$tag_item->tag->seo_url,
            ];
        }
    }
    ?>
    @if(@count($tags) >0)
        <div class="row mb-5">
            <div class="col-lg-12">
                <div id="topic-tags">
                    @foreach($tags as $tag)
                        <a href="{{ route("tag",@$tag['seo_url']) }}" class="btn btn-outline-theme mb-1">#{{ @$tag['title'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endif
