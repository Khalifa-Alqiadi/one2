<div class="tab-pane {{  ( Session::get('active_tab') == 'mailSettingsTab') ? 'active' : '' }}"
     id="tab-7">
    <div class="p-a-md"><h5>{!!  __('backend.mailSettings') !!}</h5></div>

    <div class="p-a-md col-md-12 b-b m-b-2">
        <div class="row">
            <div class="col-sm-5 form-group">
                <label>{!!  __('backend.mailDriver') !!}</label>
                <select name="mail_driver" id="mail_driver" class="form-control c-select">
                    <option
                        value="" {{ (config('smartend.mail_driver')== "") ? "selected='selected'":""  }}>
                        None
                    </option>
                    <option
                        value="sendmail" {{ (config('smartend.mail_driver')== "sendmail") ? "selected='selected'":""  }}>
                        sendmail - PHP mail()
                    </option>
                    <option
                        value="smtp" {{ (config('smartend.mail_driver')== "smtp") ? "selected='selected'":""  }}>
                        SMTP ( Recommended )
                    </option>
                    <option
                        value="mailgun" {{ (config('smartend.mail_driver')== "mailgun") ? "selected='selected'":""  }}>
                        Mailgun
                    </option>
                    <option
                        value="ses" {{ (config('smartend.mail_driver')== "ses") ? "selected='selected'":""  }}>
                        Amazon SES
                    </option>
                    <option
                        value="postmark" {{ (config('smartend.mail_driver')== "postmark") ? "selected='selected'":""  }}>
                        Postmark
                    </option>
                </select>
            </div>
            <div class="col-sm-5 form-group {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}"
                 id="mail_host_div">
                <label for="mail_host">{!!  __('backend.mailHost') !!}</label>
                <input type="text" autocomplete="off" name="mail_host" id="mail_host" value="{{ config('smartend.mail_host') }}" class="form-control" dir="ltr"/>
            </div>
            <div class="col-sm-2 form-group {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}"
                 id="mail_port_div">
                <label for="mail_port">{!!  __('backend.mailPort') !!}</label>
                <input type="text" autocomplete="off" name="mail_port" id="mail_port" value="{{ config('smartend.mail_port') }}" class="form-control" dir="ltr"/>
            </div>

            <div class="col-sm-5 form-group {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}"
                 id="mail_username_div">
                <label for="mail_username">{!!  __('backend.mailUsername') !!}</label>
                <input type="text" autocomplete="off" name="mail_username" id="mail_username" value="{{ config('smartend.mail_username') }}" class="form-control" dir="ltr"/>
            </div>
            <div class="col-sm-7 form-group {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}"
                 id="mail_password_div">
                <label for="mail_password">{!!  __('backend.mailPassword') !!}</label>
                <input type="text" autocomplete="off" name="mail_password" id="mail_password" value="{{ config('smartend.mail_password') }}" class="form-control" dir="ltr"/>
            </div>

            <div class="col-sm-5 form-group {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}"
                 id="mail_encryption_div">
                <label>{!!  __('backend.mailEncryption') !!}</label>
                <select name="mail_encryption" id="mail_encryption" class="form-control c-select">
                    <option
                        value="" {{ (config('smartend.mail_encryption') == "") ? "selected='selected'":""  }}>
                        none
                    </option>
                    <option
                        value="ssl" {{ (config('smartend.mail_encryption') == "ssl") ? "selected='selected'":""  }}>
                        ssl
                    </option>
                    <option
                        value="tls" {{ (config('smartend.mail_encryption') == "tls") ? "selected='selected'":""  }}>
                        tls
                    </option>
                </select>
            </div>
            <div class="col-sm-7 form-group {{ (config('smartend.mail_driver') == "")?"displayNone":"" }}" id="mail_from_div">
                <label for="mail_no_replay">{!!  __('backend.mailNoReplay') !!}</label>
                <input type="email" autocomplete="off" name="mail_no_replay" id="mail_no_replay" value="{{ config('smartend.mail_from_address') }}" class="form-control" dir="ltr"/>
            </div>
        </div>
        <button id="smtp_check" type="button"
                class="btn pull-right btn-sm info {{ (config('smartend.mail_driver') == "smtp")?"":"displayNone" }}">
            <i class="fa fa-bolt"></i> &nbsp;{{ __("backend.smtpCheck") }}
        </button>

        <button id="send_test" type="button" class="btn btn-sm info {{ (config('smartend.mail_driver') == "")?"displayNone":"" }}">
            <i class="fa fa-envelope"></i> &nbsp;{{ __("backend.sendTestMail") }}
        </button>
        <input type="hidden" name="mail_test" id="to_email" value="">
    </div>

    <div class="p-a-md"><h5>{!!  __('backend.messageTemplate') !!}</h5></div>
    <div class="p-x-md col-md-12">
        <div class="form-group">
            <span class="pull-right">{title}</span>
            <label for="mail_title">{!!  __('backend.messageTitle') !!}</label>
            <input type="text" autocomplete="off" name="mail_title" id="mail_title" value="{{ $WebmasterSetting->mail_title }}" class="form-control"/>
        </div>
        <div class="form-group">
            <span class="pull-right">{details}</span>
            <label class="mail_template">{!!  __('backend.messageDetails') !!}</label>
            <div>
                @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                    <div>
                        <textarea name="mail_template" id="mail_template" class="form-control ckeditor">{{ $WebmasterSetting->mail_template }}</textarea>
                    </div>
                @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                    <div>
                        <textarea name="mail_template" id="mail_template" class="form-control tinymce">{{ $WebmasterSetting->mail_template }}</textarea>
                    </div>
                @else
                    <div class="box p-a-xs">
                        <textarea name="mail_template" id="mail_template" class="form-control summernote">{{ $WebmasterSetting->mail_template }}</textarea>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
