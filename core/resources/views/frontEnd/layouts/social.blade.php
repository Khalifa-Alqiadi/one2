<?php
$socialLinks = [];
if (Helper::GeneralSiteSettings('social_links') != "") {
    try {
        $socialLinks = json_decode(Helper::GeneralSiteSettings('social_links'), true);
    } catch (Exception $e) {

    }
}
?>
@if(count($socialLinks) > 0)
    <div class="social-links text-center text-md-right pt-3 pt-md-0">
        @foreach($socialLinks as $link)
            <a href="{{ @$link['url'] }}" data-bs-toggle="tooltip" title="{{ @$link['title'] }}" data-bs-placement="{{ @$tt_position }}"
               target="_blank" class="" style="background-color:{{ @$link['color'] }}">
                <i class="{{ $link['icon'] }}"></i>
            </a>
        @endforeach
    </div>
@endif
