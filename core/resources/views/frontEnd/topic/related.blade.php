@if($WebmasterSection->related_status)
    @if(count($Topic->relatedTopics))
        <div id="Related">
            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <h4 class="mb-3 mt-4"><i class="fa-solid fa-tags"></i> {{ __('backend.relatedTopics') }}
                    </h4>

                    <?php
                    $title_var = "title_" . @Helper::currentLanguage()->code;
                    $title_var2 = "title_" . config('smartend.default_language');
                    $slug_var = "seo_url_slug_" . @Helper::currentLanguage()->code;
                    $slug_var2 = "seo_url_slug_" . config('smartend.default_language');
                    ?>
                    <div class="row">
                        @foreach($Topic->relatedTopics as $relatedTopic)
                            <?php
                            $Post = @$relatedTopic->topic;
                            ?>
                            @if(!empty($Post))
                                <div class="col-lg-4 col-md-12">
                                    @include("frontEnd.topic.card2",["Post"=>$Post])
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    @endif
@endif
