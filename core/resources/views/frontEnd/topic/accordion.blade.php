<?php
if ($Topic->$title_var != "") {
    $title = $Topic->$title_var;
} else {
    $title = $Topic->$title_var2;
}
if ($Topic->$details_var != "") {
    $details = $details_var;
} else {
    $details = $details_var2;
}
$accordion_id = "accordion".@$CatId."-".$Topic->id;
?>
<div class="mb-2">
    <div class="accordion-item">
        <h2 class="accordion-header" id="{{ $accordion_id }}-link">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $accordion_id }}-topic"
                    aria-expanded="false"
                    aria-controls="{{ $accordion_id }}-topic">
                @if($Topic->icon !="")
                    <i class="{!! $Topic->icon !!} "></i>&nbsp;
                @endif
                {{ $title }}
            </button>
        </h2>
        <div id="{{ $accordion_id }}-topic" class="accordion-collapse collapse"
             aria-labelledby="{{ $accordion_id }}-link">
            <div class="accordion-body">
                @if(@$WebmasterSection->editor_status)
                    {!! str_replace('"#','"'.Request::url().'#',$Topic->$details) !!}
                @else
                    {!! nl2br($Topic->$details) !!}
                @endif


                {{--Additional Feilds--}}
                @include("frontEnd.topic.fields",["cols"=>12,"Fields"=>@$Topic->webmasterSection->customFields->where("in_listing",true)])

                @include("frontEnd.topic.files")

                @include("frontEnd.topic.maps")

            </div>
        </div>
    </div>
</div>
