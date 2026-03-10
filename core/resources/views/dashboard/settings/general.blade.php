<div
    class="tab-pane {{ ( Session::get('active_tab') == 'infoTab') ? 'active' : '' }}"
    id="tab-1">
    <div class="p-a-md"><h5><i class="material-icons">&#xe30c;</i>
            &nbsp; {!!  __('backend.siteInfoSettings') !!}</h5></div>
    <div class="p-a-md col-md-12">
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label for="site_title_{{ @$ActiveLanguage->code }}">{!!  __('backend.websiteTitle') !!}  {!! @Helper::languageName($ActiveLanguage) !!}</label>
                <input type="text" autocomplete="off" name="site_title_{{ @$ActiveLanguage->code }}" id="site_title_{{ @$ActiveLanguage->code }}" value="{{ $Setting->{'site_title_'.@$ActiveLanguage->code} }}" dir="{{ @$ActiveLanguage->direction }}" class="form-control" maxlength="191" required/>
            </div>
        @endforeach
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label for="site_desc_{{ @$ActiveLanguage->code }}">{!!  __('backend.metaDescription') !!} {!! @Helper::languageName($ActiveLanguage) !!}</label>
                <textarea name="site_desc_{{ @$ActiveLanguage->code }}" id="site_desc_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="2"> {{ $Setting->{'site_desc_'.@$ActiveLanguage->code} }}</textarea>
            </div>
        @endforeach
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label for="site_keywords_{{ @$ActiveLanguage->code }}">{!!  __('backend.metaKeywords') !!} {!! @Helper::languageName($ActiveLanguage) !!}</label>
                <textarea name="site_keywords_{{ @$ActiveLanguage->code }}" id="site_keywords_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="2"> {{ $Setting->{'site_keywords_'.@$ActiveLanguage->code} }}</textarea>
            </div>
        @endforeach
        <div class="form-group">
            <label id="site_url">{!!  __('backend.websiteUrl') !!}</label>
            <input type="text" autocomplete="off" name="site_url" id="site_url" value="{{ $Setting->site_url }}" placeholder="http//:www.sitename.com/"
                   dir="ltr" class="form-control"/>
        </div>
    </div>

</div>
