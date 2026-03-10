<div class="tab-pane {{  ( Session::get('active_tab') == 'systemUpdate') ? 'active' : '' }}"
     id="tab-13">
    <div class="p-a-md"><h5>{!!  __('backend.systemLicenseAndUpdate') !!}</h5></div>
    <div class="p-a-md col-md-12">

        @if(Helper::GeneralWebmasterSettings("license") && Helper::GeneralWebmasterSettings("purchase_code")!="")
            <div id="system_updates"></div>
        @else
            <h5>{!!  __('backend.activateLicenceForUpdate') !!}</h5>
            <hr>
            <div class="form-group">
                <label for="domain">{!!  __('backend.domainName') !!}</label>
                <input type="text" autocomplete="off" name="domain" id="domain" value="{{ @$_SERVER['SERVER_NAME'] }}" disabled class="form-control" dir="ltr"/>
            </div>

            <div class="form-group">
                <label for="purchase_code">{!!  __('backend.purchaseCode') !!}</label>
                <textarea name="purchase_code" id="purchase_code" cols="30" rows="3" dir="ltr" class="form-control"></textarea>
                <div class="m-t-xs">
                    <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"
                       target="_blank"><i class="material-icons">&#xe88f;</i> {!!  __('backend.howToGetPurchaseCode') !!}</a>
                </div>
            </div>

            <button type="button" class="btn primary" id="purchase_btn"><i class="material-icons">&#xe31b;</i> {{ __('backend.activateNow') }}</button>
        @endif
        <br>
        <br>
        <div>
            <a href="https://smartend.app/update-guide"
               class="btn white dk w-full" target="_blank"><i class="fa fa-support"></i> {!!  __('backend.updateGuide') !!}</a>
        </div>
    </div>
</div>
