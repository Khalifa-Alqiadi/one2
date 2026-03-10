<div>
    <div class="box">
        <div class="box-header dker">
            <h3><i class="material-icons">
                    &#xe02e;</i> {{ __('backend.compose') }}
            </h3>
        </div>
        <div class="box-tool">
            <ul class="nav">
                <li class="nav-item inline">
                    <a class="nav-link" href="{{ route('webmails') }}">
                        <i class="material-icons md-18">×</i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="box-body">
            <form method="POST" action="{{ route("webmailsStore") }}" class="dashboard-form" enctype="multipart/form-data">
                @csrf
                <?php
                $siteTitle_var = "site_title_".@Helper::currentLanguage()->code;
                $siteTitle_var2 = "site_title_".config('smartend.default_language');
                if ($SiteSetting->$siteTitle_var != "") {
                    $siteTitle = $SiteSetting->$siteTitle_var;
                } else {
                    $siteTitle = $SiteSetting->$siteTitle_var2;
                }
                try {
                    $from_email = $WebmailToreply->from_email;
                    $msg_title = $WebmailToreply->title;
                    $msg_details = $WebmailToreply->details;
                } catch (Exception $e) {
                    $from_email = $contact_email;
                    $msg_title = "";
                    $msg_details = "";
                }
                if ($stat == "replay") {
                    $msg_title = "Re: ".$msg_title;
                    $msg_details = "<br><hr><small>".$msg_details."</small>";
                }
                if ($stat == "forward") {
                    $from_email = "";
                    $msg_title = "Forward: ".$msg_title;
                }
                ?>
                <input type="hidden" name="contact_id">
                <input type="hidden" name="father_id">
                <input type="hidden" name="from_email" value="{{config('smartend.mail_from_address')}}">
                <input type="hidden" name="from_name" value="{{$siteTitle}}">
                <input type="hidden" name="from_phone">
                <input type="hidden" name="to_name">

                <div class="form-group row">
                    <label for="to_email"
                           class="col-sm-2 form-control-label">{!!  __('backend.sendTo') !!}
                    </label>
                    <div class="col-sm-9">
                        <input type="email" name="to_email" id="to_email" value="{{ $from_email }}" class="form-control" required>
                    </div>
                    <div class="col-sm-1">
                        <small>
                            <a onclick="document.getElementById('cc').style.display='block'">{!!  __('backend.sendCc') !!}</a><br>
                            <a onclick="document.getElementById('bcc').style.display='block'">{!!  __('backend.sendBcc') !!}</a>
                        </small>
                    </div>
                </div>
                <div id="cc" style="display: none" class="form-group row">
                    <label for="to_cc"
                           class="col-sm-2 form-control-label">{!!  __('backend.sendCc') !!}
                    </label>
                    <div class="col-sm-9">
                        <input type="email" name="to_cc" id="to_cc" value="" class="form-control">
                    </div>
                    <div class="col-sm-1">
                        <a onclick="document.getElementById('to_cc').value='';document.getElementById('cc').style.display='none'"><i
                                class="material-icons md-18">×</i></a>
                    </div>
                </div>
                <div id="bcc" style="display: none" class="form-group row">
                    <label for="to_bcc"
                           class="col-sm-2 form-control-label">{!!  __('backend.sendBcc') !!}
                    </label>
                    <div class="col-sm-9">
                        <input type="email" name="to_bcc" id="to_bcc" value="" class="form-control">
                    </div>
                    <div class="col-sm-1">
                        <a onclick="document.getElementById('to_bcc').value='';document.getElementById('bcc').style.display='none'"><i
                                class="material-icons md-18">×</i></a>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email_title"
                           class="col-sm-2 form-control-label">{!!  __('backend.sendTitle') !!}
                    </label>
                    <div class="col-sm-10">
                        <input type="text" name="title" id="email_title" value="{{ $msg_title }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <div>
                            @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                <div>
                                    <textarea name="details" id="email_details" class="form-control ckeditor">{{ $msg_details }}</textarea>
                                </div>
                            @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                <div>
                                    <textarea name="details" id="email_details" class="form-control tinymce">{{ $msg_details }}</textarea>
                                </div>
                            @else
                                <div class="box p-a-xs">
                                    <textarea name="details" id="email_details" class="form-control summernote">{{ $msg_details }}</textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="attach_files"
                           class="col-sm-2 form-control-label">{!!  __('backend.AttachFiles') !!}</label>
                    <div class="col-sm-10">
                        <input type="file" name="attach_files[]" id="attach_files" class="form-control" multiple>
                    </div>
                </div>


                <div class="form-group row m-t-md">
                    <div class="offset-sm-2 col-sm-10">
                        <input type="hidden" name="btn_clicked" id="btn_clicked">
                        <button type="submit" name="btn_send" onclick="document.getElementById('btn_clicked').value=''" class="btn btn-lg btn-primary m-t">
                            <i
                                class="material-icons">
                                &#xe31b;</i> {!! __('backend.send') !!}</button>
                        <button type="submit" name="btn_draft" onclick="document.getElementById('btn_clicked').value='draft'" class="btn btn-lg btn-default m-t">
                            <i
                                class="material-icons">
                                &#xe161;</i> {!! __('backend.SaveToDraft') !!}</button>
                        <a href="{{ route('webmails') }}"
                           class="btn btn-lg btn-default m-t"><i class="material-icons">
                                &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
