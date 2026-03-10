<div class="tab-pane {{  ( Session::get('active_tab') == 'emailTab') ? 'active' : '' }}"
     id="tab-6">
    <div class="p-a-md"><h5><i class="material-icons">&#xe0be;</i>
            &nbsp; {!!  __('backend.emailNotifications') !!}</h5></div>
    <div class="p-a-md col-md-12">
        <div class="form-group">
            <label for="site_webmails">{!!  __('backend.websiteNotificationEmail') !!}</label>
            <input type="text" autocomplete="off" name="site_webmails" id="site_webmails" value="{{ $Setting->site_webmails }}" dir="ltr"
                   placeholder="email@sitename.com" class="form-control"/>
        </div>
        <div class="form-group">
            <label for="notify_messages_status1">{{ __('backend.websiteNotificationEmail1') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="notify_messages_status" value="1" class="has-value" {{ ($Setting->notify_messages_status==1)?"checked":"" }} id="notify_messages_status1">
                    <i class="primary"></i>
                    {{ __('backend.yes') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="notify_messages_status" value="0" class="has-value" {{ ($Setting->notify_messages_status==0)?"checked":"" }} id="notify_messages_status2">
                    <i class="danger"></i>
                    {{ __('backend.no') }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label id="notify_comments_status1">{{ __('backend.websiteNotificationEmail2') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="notify_comments_status" value="1" class="has-value" {{ ($Setting->notify_comments_status==1)?"checked":"" }} id="notify_comments_status1">
                    <i class="primary"></i>
                    {{ __('backend.yes') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="notify_comments_status" value="0" class="has-value" {{ ($Setting->notify_comments_status==0)?"checked":"" }} id="notify_comments_status2">
                    <i class="danger"></i>
                    {{ __('backend.no') }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="notify_orders_status1">{{ __('backend.websiteNotificationEmail3') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="notify_orders_status" value="1" class="has-value" {{ ($Setting->notify_orders_status==1)?"checked":"" }} id="notify_orders_status1">
                    <i class="primary"></i>
                    {{ __('backend.yes') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="notify_orders_status" value="0" class="has-value" {{ ($Setting->notify_orders_status==0)?"checked":"" }} id="notify_orders_status2">
                    <i class="danger"></i>
                    {{ __('backend.no') }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="notify_table_status1">{{ __('backend.websiteNotificationEmail4') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="notify_table_status" value="1" class="has-value" {{ ($Setting->notify_table_status==1)?"checked":"" }} id="notify_table_status1">
                    <i class="primary"></i>
                    {{ __('backend.yes') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="notify_table_status" value="0" class="has-value" {{ ($Setting->notify_table_status==0)?"checked":"" }} id="notify_table_status2">
                    <i class="danger"></i>
                    {{ __('backend.no') }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="notify_private_status1">{{ __('backend.websiteNotificationEmail5') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="notify_private_status" value="1" class="has-value" {{ ($Setting->notify_private_status==1)?"checked":"" }} id="notify_private_status1">
                    <i class="primary"></i>
                    {{ __('backend.yes') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="notify_private_status" value="0" class="has-value" {{ ($Setting->notify_private_status==0)?"checked":"" }} id="notify_private_status2">
                    <i class="danger"></i>
                    {{ __('backend.no') }}
                </label>
            </div>
        </div>
    </div>
</div>
