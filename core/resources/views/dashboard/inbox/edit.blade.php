@if($group_id=="draft" || $cat_id ==2)
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
                <form method="POST" action="{{ route("webmailsUpdate",["id"=>Session::get('WebmailToEdit')->id]) }}" class="dashboard-form" enctype="multipart/form-data">
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
                            $from_email = Session::get('WebmailToEdit')->from_email;
                            $msg_title = Session::get('WebmailToEdit')->title;
                            $msg_details = Session::get('WebmailToEdit')->details;
                            $to_cc = Session::get('WebmailToEdit')->to_cc;
                            $to_bcc = Session::get('WebmailToEdit')->to_bcc;
                        } catch (Exception $e) {
                            $from_email = "";
                            $msg_title = "";
                            $msg_details = "";
                            $to_cc = "";
                            $to_bcc = "";
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

                        <?php
                        $cc = "display: none";
                        $bcc = "display: none";
                        if ($to_cc !== "") {
                            $cc = "";
                            $bcc = "";
                        }
                        ?>
                    <div id="cc" style="<?php echo $cc;?>" class="form-group row">
                        <label for="to_cc"
                               class="col-sm-2 form-control-label">{!!  __('backend.sendCc') !!}
                        </label>
                        <div class="col-sm-9">
                            <input type="email" name="to_cc" id="to_cc" value="{{ $to_cc }}" class="form-control">
                        </div>
                        <div class="col-sm-1">
                            <a onclick="document.getElementById('to_cc').value='';document.getElementById('cc').style.display='none'"><i
                                    class="material-icons md-18">×</i></a>
                        </div>
                    </div>
                    <div id="bcc" style="<?php echo $bcc;?>" class="form-group row">
                        <label for="to_bcc"
                               class="col-sm-2 form-control-label">{!!  __('backend.sendBcc') !!}
                        </label>
                        <div class="col-sm-9">
                            <input type="email" name="to_bcc" id="to_bcc" value="{{ $to_bcc }}" class="form-control">
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

@else
    <div class="row hidden-print">
        <div class="col-sm-12">
            <div class="p-b-1 pull-right">
                <a href="{{ route("webmails",["group_id"=>"create","stat"=>"replay","wid"=>Session::get('WebmailToEdit')->id]) }}"
                   class="btn btn-sm success p-r-1"><i
                        class="fa fa-mail-reply"></i> {!! __('backend.replay') !!}</a>

                <a href="{{ route("webmails",["group_id"=>"create","stat"=>"forward","wid"=>Session::get('WebmailToEdit')->id]) }}"
                   class="btn btn-sm btn-info p-r-1 "><i
                        class="fa fa-mail-forward"></i> {!! __('backend.forward') !!}</a>

                <a class="btn btn-sm btn-default p-r-1"
                   onClick="window.print();"><i
                        class="fa fa-print"></i> {!! __('backend.print') !!}
                </a>
            </div>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="box p-a dark" style="min-height: 100px">
                <strong>{!! __('backend.sendFrom') !!}:
                    &nbsp; {!! Session::get('WebmailToEdit')->from_name !!}</strong>
                <p class="m-b-0">
                    @if(Session::get('WebmailToEdit')->from_phone !="")
                        {!! __('backend.contactPhone') !!}:
                        &nbsp; (+) {!! Session::get('WebmailToEdit')->from_phone !!}<br>
                    @endif
                    @if(Session::get('WebmailToEdit')->from_email !="")
                        {!! __('backend.contactEmail') !!}:
                        &nbsp; {!! Session::get('WebmailToEdit')->from_email !!}
                    @endif
                </p>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box p-a" style="min-height: 100px">
                <strong class="text-muted">{!! __('backend.sendTo') !!}:
                    &nbsp; {!! Session::get('WebmailToEdit')->to_name !!}</strong>
                <p class="text-muted">
                    @if(Session::get('WebmailToEdit')->to_email !="")
                        {!! __('backend.contactEmail') !!}:
                        &nbsp; {!! Session::get('WebmailToEdit')->to_email !!}<br>

                    @endif
                    @if(Session::get('WebmailToEdit')->to_cc !="")
                        {!! __('backend.sendCc') !!}:
                        &nbsp; {!! Session::get('WebmailToEdit')->to_cc !!}<br>
                    @endif
                    @if(Session::get('WebmailToEdit')->to_bcc !="")
                        {!! __('backend.sendBcc') !!}:
                        &nbsp; {!! Session::get('WebmailToEdit')->to_bcc !!}
                    @endif

                </p>
            </div>
        </div>
    </div>
        <?php

        $dtformated = date('d M Y h:i A', strtotime(Session::get('WebmailToEdit')->date));

        ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box p-a">
                <h4>{!! Session::get('WebmailToEdit')->title !!}</h4>
                <small><i class="fa fa-calendar"></i> {{$dtformated}}</small>
                @if(Session::get('WebmailToEdit')->details)
                    <hr>
                    <p></p>
                    {!! nl2br(Session::get('WebmailToEdit')->details) !!}
                    </p>
                @endif

            </div>
        </div>
    </div>
    @if(count(Session::get('WebmailToEdit')->files) > 0)
        <div class="row">
            <div class="col-sm-12">
                <div class="box p-a">
                    <h6>{{ __('backend.AttachFiles') }}</h6>
                    <hr>
                    <div class="row">
                            <?php
                            $img_type = array(".gif", ".jpeg", ".png", ".jpg", ".svg", ".webp");
                            ?>
                        @foreach(Session::get('WebmailToEdit')->files as $file)
                            <div class="col-sm-3">
                                    <?php
                                    $ext = strrchr($file->file, ".");
                                    $ext = strtolower($ext);
                                if (!in_array($ext, $img_type)) {
                                    ?>
                                <a href="{{ route("fileView",["path" =>'inbox/'.$file->file]) }}"
                                   style="display: block;padding: 10px;border: 1px solid #eee"
                                   target="_blank"><strong>{!! $file->file !!}</strong></a>
                                    <?php
                                }else {
                                    ?>
                                <a href="{{ route("fileView",["path" =>'inbox/'.$file->file]) }}"
                                   target="_blank"><img
                                        src="{{ route("fileView",["path" =>'inbox/'.$file->file]) }}"
                                        style="max-width: 100%" alt=""></a>
                                    <?php
                                }
                                    ?>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
