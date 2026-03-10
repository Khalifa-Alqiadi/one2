<div class="tab-pane {{  ( Session::get('active_tab') == 'appsSettingsTab') ? 'active' : '' }}"
     id="tab-1">
    <div class="p-a-md"><h5>{!!  __('backend.appsSettings') !!}</h5></div>
    <div class="p-a-md col-md-12">

        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="analytics_status" id="analytics_status" value="1" {{ ($WebmasterSetting->analytics_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.visitorsAnalytics') }}
            </label>
        </div>

        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="newsletter_status" id="newsletter_status" value="1" {{ ($WebmasterSetting->newsletter_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.newsletter') }}
            </label>
        </div>

        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="inbox_status" id="inbox_status" value="1" {{ ($WebmasterSetting->inbox_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.siteInbox') }}
            </label>
        </div>

        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="calendar_status" id="calendar_status" value="1" {{ ($WebmasterSetting->calendar_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.calendar') }}
            </label>
        </div>

        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="banners_status" id="banners_status" value="1" {{ ($WebmasterSetting->banners_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.adsBanners') }}
            </label>
        </div>


        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="popups_status" id="popups_status" value="1" {{ ($WebmasterSetting->popups_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.popups') }}
            </label>
        </div>

        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="tags_status" id="tags_status" value="1" {{ ($WebmasterSetting->tags_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.tags') }}
            </label>
        </div>
        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="menus_status" id="menus_status" value="1" {{ ($WebmasterSetting->menus_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.siteMenus') }}
            </label>
        </div>
        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="file_manager_status" id="file_manager_status" value="1" {{ ($WebmasterSetting->file_manager_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.fileManager') }}
            </label>
        </div>
        <div class="checkbox">
            <label class="md-switch">
                <input type="checkbox" class="has-value" name="settings_status" id="settings_status" value="1" {{ ($WebmasterSetting->settings_status)?"checked":"" }}>
                <i class="primary"></i>
                {{ __('backend.generalSiteSettings') }}
            </label>
        </div>
    </div>
</div>
