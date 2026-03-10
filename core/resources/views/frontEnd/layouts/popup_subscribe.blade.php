<form method="POST" action="{{ route("subscribeSubmit") }}" class="popupSubscribeForm" id="popupSubscribeForm">
    @csrf
    @honeypot
    <div class="popup-subscribe">
        <div class="row">
            <div class="col-12 col-lg-8">
                <label for="email-newsletter-component" class="visually-hidden">Email Address</label>
                <input type="email" name="subscribe_email" class="form-control form-control-lg" value=""
                       placeholder="{{  __('frontend.yourEmail') }}" autocomplete="off" required>
            </div>
            <div class="col-12 col-lg-4 text-center text-lg-start">
                <button type="submit" class="btn btn-primary btn-lg w-100"
                        id="popupSubscribeFormSubmit">{{ __('frontend.subscribe') }}</button>
            </div>
        </div>
    </div>
</form>
@push('after-scripts')
    <script type="text/javascript">
        $('#popupSubscribeForm').submit(function (evt) {
            evt.preventDefault();
            let btn = $('#popupSubscribeFormSubmit');
            btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 16px\"/> <small>{!! __('frontend.subscribe') !!}</small>");
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
                        $('#popupSubscribeForm')[0].reset();
                        setTimeout(function () {
                            $('#page-popup-{{ @$PopupID }}').modal('hide');
                        }, 5000);
                    }
                    let confirm = '<div class="alert ' + stat + ' alert-dismissible fade show mt-1" role="alert">' + result.msg + '</div>';
                    $(".popup-subscribe .alert").remove();
                    $(".popup-subscribe").append(confirm);
                    btn.html("{!! __('frontend.subscribe') !!}");
                    btn.prop('disabled', false);
                }
            });
            return false;
        });
    </script>
@endpush
