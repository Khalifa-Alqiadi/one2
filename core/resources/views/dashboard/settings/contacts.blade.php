<div class="tab-pane {{  ( Session::get('active_tab') == 'contactsTab') ? 'active' : '' }}"
     id="tab-2">
    <div class="p-a-md"><h5><i class="material-icons">&#xe0ba;</i>
            &nbsp; {!!  __('backend.siteContactsSettings') !!}</h5></div>
    <div class="p-a-md col-md-12">
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label for="contact_t1_{{ @$ActiveLanguage->code }}">{!!  __('backend.contactAddress') !!} {!! @Helper::languageName($ActiveLanguage) !!}</label>
                <input type="text" autocomplete="off" name="contact_t1_{{ @$ActiveLanguage->code }}" id="contact_t1_{{ @$ActiveLanguage->code }}" value="{{ $Setting->{'contact_t1_'.@$ActiveLanguage->code} }}" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
            </div>
        @endforeach
        <div class="form-group">
            <label for="contact_t3">{!!  __('backend.contactPhone') !!}</label>
            <input type="text" autocomplete="off" name="contact_t3" id="contact_t3" value="{{ $Setting->contact_t3 }}" class="form-control" dir="ltr"/>
        </div>
        <div class="form-group">
            <label for="contact_t4">{!!  __('backend.contactFax') !!}</label>
            <input type="text" autocomplete="off" name="contact_t4" id="contact_t4" value="{{ $Setting->contact_t4 }}" class="form-control" dir="ltr"/>
        </div>
        <div class="form-group">
            <label for="contact_t5">{!!  __('backend.contactMobile') !!}</label>
            <input type="text" autocomplete="off" name="contact_t5" id="contact_t5" value="{{ $Setting->contact_t5 }}" class="form-control" dir="ltr"/>
        </div>
        <div class="form-group">
            <label for="contact_t6">{!!  __('backend.contactEmail') !!}</label>
            <input type="text" autocomplete="off" name="contact_t6" id="contact_t6" value="{{ $Setting->contact_t6 }}" class="form-control" dir="ltr"/>
        </div>
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label for="contact_t7_{{ @$ActiveLanguage->code }}">{!!  __('backend.worksTime') !!} {!! @Helper::languageName($ActiveLanguage) !!}</label>
                <input type="text" autocomplete="off" name="contact_t7_{{ @$ActiveLanguage->code }}" id="contact_t7_{{ @$ActiveLanguage->code }}" value="{{ $Setting->{'contact_t7_'.@$ActiveLanguage->code} }}" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
            </div>
        @endforeach
    </div>
</div>
