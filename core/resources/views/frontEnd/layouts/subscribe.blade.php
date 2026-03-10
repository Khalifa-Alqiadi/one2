@if(Helper::GeneralSiteSettings("style_subscribe"))
    <div class="col-lg-4 col-md-12 footer-newsletter">
        <div class="footer-title">
            <h4>{{ __('frontend.newsletter') }}</h4>
        </div>
        <p>{{ __('frontend.subscribeToOurNewsletter') }}</p>
        <form method="POST" action="{{ route("subscribeSubmit") }}" id="subscribeForm">
            @csrf
            @honeypot
            <input type="email" name="subscribe_email" id="subscribe_email" placeholder="{{ __('frontend.yourEmail') }}" class="form-control" required="required" autocomplete="off">
            <button type="submit" id="subscribeFormSubmit">{{ __('frontend.subscribe') }}</button>
        </form>
    </div>
@endif
