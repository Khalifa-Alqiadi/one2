@if(Helper::GeneralWebmasterSettings("cookie_policy_status"))
    <div class="col-md-3 col-sm-12 cookies-accept" style="display: none">
        <div class="cookies-accept-box text-white">
            <p>
                {!! __("frontend.cookiesAcceptMessage") !!}
                <?php
                $Policy = Helper::Topic(3);
                ?>
                @if(!empty($Policy))
                    <br>[ <a href="{{ Helper::topicURL(3) }}"><span
                            class="text-light"><i class="fa-solid fa-shield-halved"></i> {{ $Policy->{"title_" . @Helper::currentLanguage()->code} }}</span></a> ]
                @endif
            </p>
            <button type="button" id="cookies-accept-btn" class="btn btn-danger w-100"><i class="fa fa-check"
                                                                                          aria-hidden="true"></i> {!! __("frontend.cookiesAcceptBtn") !!}
            </button>
        </div>
    </div>
    <script>
        $("#cookies-accept-btn").on('click', function () {
            var d = new Date();
            d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
            document.cookie = 'cookie_notice_accepted=true' + ';expires=' + d.toGMTString();
            $(".cookies-accept").fadeOut("slow");
        });
        if (document.cookie.indexOf('cookie_notice_accepted') === -1) {
            $(".cookies-accept").fadeIn("slow");
        }
    </script>
@endif
