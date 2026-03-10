<a href="#" title="{{ __('frontend.toTop') }}" class="back-to-top d-flex align-items-center justify-content-center" aria-label="Go to Top"><i
        class="bi bi-arrow-up-short"></i></a>

<script type="text/javascript">
    var page_dir = "{{ @Helper::currentLanguage()->direction }}";
</script>
<!-- Vendor JS Files -->
<script src="{{ URL::asset('assets/frontend/js/jquery.min.js') }}?v={{ Helper::system_version() }}"></script>
<script
    src="{{ URL::asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}?v={{ Helper::system_version() }}"></script>
<script
    src="{{ URL::asset('assets/frontend/vendor/glightbox/js/glightbox.min.js') }}?v={{ Helper::system_version() }}"></script>
<script
    src="{{ URL::asset('assets/frontend/vendor/swiper/swiper-bundle.min.js') }}?v={{ Helper::system_version() }}"></script>
<script
    src="{{ URL::asset('assets/frontend/vendor/owl-carousel/owl.carousel.js') }}?v={{ Helper::system_version() }}"></script>

<!-- Template Main JS File -->
<script src="{{ URL::asset('assets/frontend/js/main.js') }}?v={{ Helper::system_version() }}"></script>

{{--ajax subscribe to news letter--}}
@if(Helper::GeneralSiteSettings("style_subscribe"))
    <script type="text/javascript">
        $(document).ready(function () {
            //Subscribe
            $('#subscribeForm').submit(function (evt) {
                evt.preventDefault();
                let btn = $('#subscribeFormSubmit');
                btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('frontend.subscribe') !!}");
                btn.prop('disabled', true);
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route("subscribeSubmit") }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        let stat = 'alert-warning';
                        if (result.stat === 'success') {
                            stat = 'alert-success';
                            $('#subscribeForm')[0].reset();
                        }
                        let confirm = '<div class="alert alert-dismissible ' + stat + ' alert-dismissible fade show mt-3" role="alert">' + result.msg + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        $(".footer-newsletter .alert").remove();
                        $(".footer-newsletter").append(confirm);
                        btn.html("{!! __('frontend.subscribe') !!}");
                        btn.prop('disabled', false);
                    }
                });
                return false;
            });

        });
    </script>
@endif

{{-- Google Tags and google analytics --}}
@if(@Helper::GeneralWebmasterSettings("google_tags_status") && @Helper::GeneralWebmasterSettings("google_tags_id") !="")
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id={!! @Helper::GeneralWebmasterSettings("google_tags_id") !!}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif

<?php
if (@$PageTitle == "") {
    $PageTitle = Helper::GeneralSiteSettings("site_title_".@Helper::currentLanguage()->code);
}
?>
@include("frontEnd.layouts.cookie")
{!! Helper::SaveVisitorInfo($PageTitle) !!}
