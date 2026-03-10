@php($mostView = Helper::mustView(@$WebmasterSection->id, $Topic->id, 6))
@if(count($mostView) > 0)
    <div class="col-md-4 side-video">
        @foreach($mostView as $item)
            <?php
            if ($item->$title_var != "") {
                $title = $item->$title_var;
            } else {
                $title = $item->$title_var2;
            }
            $img_url = "";
            if($item->video_type ==1 && $item?->video_file != ""){
                $url = Helper::getThumbnail($item?->video_file);
                $img_url = $url['url'] ?? ""; 
            }             
            ?>
            <div class="card bg-transparent border-0 p-0 mb-2 d-flex flex-row">
                <div class="card-header border-0 p-0">
                    <img loading="lazy"
                        src="{{ $img_url }}"
                        alt="{{ $title }}" class="post-main-photo">
                </div>
                <div class="card-body py-0">
                    <h4>
                        <a href="{{Helper::topicURL($item->id, "", $item)}}">{{$title}}</a>
                    </h4>
                </div>
            </div>
        @endforeach
    </div>
@endif