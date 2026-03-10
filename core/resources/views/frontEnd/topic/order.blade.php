<div id="order">
    <div class="row">
        <div class="col-lg-12">
            <br>
            <h4><i class="fa-solid fa-cart-plus"></i> {{ __('frontend.orderForm') }}
            </h4>
            <div class="bottom-article">
                <form method="POST" action="{{ route("orderSubmit") }}" class="orderForm" id="orderForm">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="order_name"
                                   class="form-control-label">{!!  __('frontend.name') !!}</label>
                            <input type="text" autocomplete="off" name="order_name" id="order_name" value="{{ @Auth::user()->name }}" required maxlength="100" placeholder="{{ __('frontend.yourName') }}" class="form-control"/>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="order_phone"
                                   class="form-control-label">{!!  __('frontend.phone') !!}</label>
                            <input type="text" autocomplete="off" name="order_phone" id="order_phone" value="" required minlength="5" maxlength="15" placeholder="{{ __('frontend.phone') }}" class="form-control"  dir="ltr"/>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="order_email"
                                   class="form-control-label">{!!  __('frontend.email') !!}</label>
                            <input type="email" autocomplete="off" name="order_email" id="order_email" value="{{ @Auth::user()->email }}" required maxlength="100" placeholder="{{ __('frontend.yourEmail') }}" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="order_message" id="order_message" class="form-control" placeholder="{{ __('frontend.notes') }}" rows="5" required></textarea>
                    </div>

                    @if(config('smartend.nocaptcha_status'))
                        <div class="form-group mb-3">
                            {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                            {!! NoCaptcha::display() !!}
                        </div>
                    @endif
                    <div class="submit-message"></div>
                    <div>
                        <input type="hidden" name="topic_id" value="{{$Topic->id}}">
                        <button type="submit" id="orderFormSubmit"
                                class="btn btn-lg btn-theme"><i
                                class="fa-solid fa-paper-plane"></i> {{ __('frontend.sendOrder') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('before-styles')
    <link rel="stylesheet"
          href="{{ URL::asset('assets/frontend/vendor/intl-tel-input/css/intlTelInput.min.css') }}?v={{ Helper::system_version() }}"/>
@endpush
@push('after-scripts')
    <script
        src="{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/intlTelInput.min.js') }}?v={{ Helper::system_version() }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#orderForm').submit(function (evt) {
                evt.preventDefault();
                let btn = $('#orderFormSubmit');
                btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('frontend.sendOrder') !!}");
                btn.prop('disabled', true);
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route("orderSubmit") }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        let stat = 'alert-danger';
                        if (result.stat === 'success') {
                            stat = 'alert-success';
                            $('#orderForm')[0].reset();
                        }
                        let confirm = '<div class="alert ' + stat + ' alert-dismissible fade show mt-3" role="alert">' + result.msg + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        $("#orderForm .submit-message").html(confirm);
                        btn.html('<i class="fa-solid fa-paper-plane"></i> {!! __('frontend.sendOrder') !!}');
                        btn.prop('disabled', false);
                    }
                });
                return false;
            });
        });

        var iti = window.intlTelInput(document.querySelector("#order_phone"), {
            showSelectedDialCode: true,
            countrySearch: true,
            initialCountry: "auto",
            separateDialCode: true,
            hiddenInput: function () {
                return {
                    phone: "order_phone_full",
                    country: "order_phone_country_code"
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
