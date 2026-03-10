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
        <div class="row g-5">
            <!-- Contact Information -->
            <div class="col-lg-5">
                <div class="pe-lg-4">
                    @if(@$TopicBlock->title_status)
                        <h3 class="fw-bold mb-4">{{ @$TopicBlockContents->{"title_".@Helper::currentLanguage()->code} }}</h3>
                    @endif
                    @if(@$TopicBlock->desc_status)
                        <p class="text-muted mb-4">{!! nl2br(@$TopicBlockContents->{"desc_".@Helper::currentLanguage()->code}) !!}</p>
                    @endif
                    @if(Helper::GeneralSiteSettings("contact_t1_" . @Helper::currentLanguage()->code) !="")
                        <!-- Address -->
                        <div class="d-flex mb-4 contact-card">
                            <div>
                                <div class="bg-primary p-3 rounded-circle contact-icon">
                                    <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="fw-bold">{{ __('frontend.address') }}</h5>
                                <p class="text-muted">{{ Helper::GeneralSiteSettings("contact_t1_" . @Helper::currentLanguage()->code) }}</p>
                            </div>
                        </div>
                    @endif

                    @if(Helper::GeneralSiteSettings("contact_t3") !="" || Helper::GeneralSiteSettings("contact_t5") !="")
                        <!-- Phone -->
                        <div class="d-flex mb-4 contact-card">
                            <div>
                                <div class="bg-warning p-3 rounded-circle contact-icon">
                                    <i class="fas fa-phone fa-2x text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="fw-bold">{{ __('frontend.callPhone') }}</h5>
                                @if(Helper::GeneralSiteSettings("contact_t3") !="")
                                    <p class="text-muted mb-0">
                                        <span dir="ltr">{{ Helper::GeneralSiteSettings("contact_t3") }}</span></p>
                                @endif
                                @if(Helper::GeneralSiteSettings("contact_t5") !="")
                                    <p class="text-muted">
                                        <span dir="ltr">{{ Helper::GeneralSiteSettings("contact_t5") }}</span></p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(Helper::GeneralSiteSettings("contact_t6") !="")
                        <!-- Email -->
                        <div class="d-flex mb-4 contact-card">
                            <div>
                                <div class="bg-info p-3 rounded-circle contact-icon">
                                    <i class="fas fa-envelope fa-2x text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="fw-bold">{{ __('frontend.email') }}</h5>
                                <p class="text-muted">{{ Helper::GeneralSiteSettings("contact_t6") }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Social Media -->
                    <div class="mt-5">
                        <h5 class="fw-bold mb-3">{{ __("frontend.followUs") }}</h5>
                        @include("frontEnd.layouts.social",["tt_position"=>"top"])
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body p-5">
                        <div id="contact-form-container">
                            <h3 class="sub-title">{{ __('frontend.getInTouch') }}</h3>
                            <form method="POST" action="{{ route("contactPageSubmit") }}" class="php-email-form" id="contactForm">
                                @csrf
                                @honeypot
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <input type="text" autocomplete="off" name="contact_name" id="contact_name" value="" required maxlength="100"
                                               placeholder="{{ __('frontend.yourName') }}" class="form-control"/>
                                    </div>
                                    <div class="col-md-6 form-group mt-3 mt-md-0">
                                        <input type="text" autocomplete="off" name="contact_phone" id="contact_phone" value="" required minlength="5" maxlength="15" placeholder="{{ __('frontend.phone') }}" class="form-control" dir="ltr"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" autocomplete="off" name="contact_email" id="contact_email" value="" required maxlength="100"
                                           placeholder="{{ __('frontend.yourEmail') }}" class="form-control"/>
                                </div>
                                <div class="form-group mt-3">
                                    <input type="text" autocomplete="off" name="contact_subject" id="contact_subject" value="" required maxlength="100"
                                           placeholder="{{ __('frontend.subject') }}" class="form-control"/>
                                </div>
                                <div class="form-group mt-3">
                                    <textarea name="contact_message" id="contact_message" class="form-control" placeholder="{{ __('frontend.message') }}" rows="6" required></textarea>
                                </div>

                                @if(config('smartend.nocaptcha_status'))
                                    <div class="form-group mb-3">
                                        {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                                        {!! NoCaptcha::display() !!}
                                    </div>
                                @endif
                                <div class="submit-message"></div>
                                <div>
                                    <button type="submit" id="contactFormSubmit" class="btn btn-lg btn-theme"><i
                                            class="fa-solid fa-paper-plane"></i> {{ __('frontend.sendMessage') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('before-styles')
    <link rel="stylesheet"
          href="{{ URL::asset('assets/frontend/vendor/intl-tel-input/css/intlTelInput.min.css') }}?v={{ Helper::system_version() }}"/>
@endpush
@push('after-scripts')
    <script
        src="{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/intlTelInput.min.js') }}?v={{ Helper::system_version() }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#contactForm').submit(function (evt) {
                evt.preventDefault();
                let btn = $('#contactFormSubmit');
                btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('frontend.sendMessage') !!}");
                btn.prop('disabled', true);
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route("contactPageSubmit") }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        let stat = 'alert-danger';
                        if (result.stat === 'success') {
                            stat = 'alert-success';
                            $('#contactForm')[0].reset();
                        }
                        let confirm = '<div class="alert ' + stat + ' alert-dismissible fade show mt-3" role="alert">' + result.msg + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        $("#contactForm .submit-message").html(confirm);
                        btn.html('<i class="fa-solid fa-paper-plane"></i> {!! __('frontend.sendMessage') !!}');
                        btn.prop('disabled', false);
                    }
                });
                return false;
            });
        });

        var iti = window.intlTelInput(document.querySelector("#contact_phone"), {
            showSelectedDialCode: true,
            countrySearch: true,
            initialCountry: "auto",
            separateDialCode: true,
            hiddenInput: function () {
                return {
                    phone: "contact_phone_full",
                    country: "contact_phone_country_code"
                };
            },
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "us";
                    callback(countryCode.toLowerCase());
                    iti.setCountry(countryCode.toLowerCase());
                });
            },
            utilsScript: "{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/utils.js') }}?v={{ Helper::system_version() }}",
        });
    </script>
@endpush
