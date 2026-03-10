<div class="bottom-article-2 py-3">
    <div class="d-flex align-items-center justify-content-center">
        <ul class="social-network share d-flex">
            <li><a href="{{ Helper::SocialShare("facebook", @$PageTitle)}}"
                   class="facebook"
                   data-bs-toggle="tooltip"
                   title="Facebook" target="_blank"><i
                        class="fa-brands fa-facebook"></i></a>
            </li>
            <li><a href="{{ Helper::SocialShare("whatsapp", @$PageTitle)}}"
                   class="twitter"
                   data-bs-toggle="tooltip" title="Whatsapp"
                   style="background-color:#067f4b "
                   target="_blank"><i
                        class="fa-brands fa-whatsapp"></i></a></li>
            <li><a href="{{ Helper::SocialShare("twitter", @$PageTitle)}}"
                   class="twitter"
                   data-bs-toggle="tooltip" title="Twitter"
                   target="_blank"><i
                        class="bi bi-twitter-x"></i></a></li>
            <li><a href="{{ Helper::SocialShare("linkedin", @$PageTitle)}}"
                   class="linkedin"
                   data-bs-toggle="tooltip" title="linkedin"
                   target="_blank"><i
                        class="fa-brands fa-linkedin"></i></a></li>
            <li><a href="{{ Helper::SocialShare("tumblr", @$PageTitle)}}" class="tumblr"
                   data-bs-toggle="tooltip" title="Tumblr"
                   target="_blank"><i
                        class="fa-brands fa-tumblr"></i></a></li>
        </ul>
    </div>
</div>