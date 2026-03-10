@if(count($WebmasterSection->customFields) >0)
        <?php
        $cf_title_var = "title_".@Helper::currentLanguage()->code;
        $cf_title_var2 = "title_".config('smartend.default_language');
        $load_editor = 0;
        $PhoneFieldsIds = [];
        ?>
    @foreach($WebmasterSection->customFields as $customField)
            <?php
            $intersection = [];
            if (!empty(@$cat_ids) && $customField->categories != "") {
                $intersection = array_intersect($cat_ids, @explode(",", $customField->categories));
            }
            ?>
        @if($customField->categories=="" || @$cat_ids=="" || !empty($intersection))
                <?php
                // check permission
                $edit_permission_groups = [];
                if ($customField->edit_permission_groups != "") {
                    $edit_permission_groups = explode(",",
                        $customField->edit_permission_groups);
                }
            if (in_array(Auth::user()->permissions_id, $edit_permission_groups) || in_array(0,
                    $edit_permission_groups) || $customField->edit_permission_groups == "") {
                // have permission & continue
                if ($customField->$cf_title_var != "") {
                    $cf_title = $customField->$cf_title_var;
                } else {
                    $cf_title = $customField->$cf_title_var2;
                }

                // check field language status
                $cf_land_identifier = "";
                $cf_land_active = false;
                $cf_land_dir = @Helper::currentLanguage()->direction;
                if ($customField->lang_code != "all") {
                    $ct_language = @Helper::LangFromCode($customField->lang_code);
                    $cf_land_identifier = @Helper::languageName($ct_language);
                    $cf_land_dir = $ct_language->direction;
                    if ($ct_language->box_status) {
                        $cf_land_active = true;
                    }
                }
                if ($customField->lang_code == "all") {
                    $cf_land_active = true;
                }
                // required Status
                $cf_required = "";
                $cf_required_star = "";
                if ($customField->required) {
                    $cf_required = "required";
                    $cf_required_star = '<span class="text-danger">*</span>';
                }
                $cf_saved_val = "";
                $cf_saved_val_array = array();
                if (!empty(@$Topic)) {
                    if (count($Topic->fields) > 0) {
                        foreach ($Topic->fields as $t_field) {
                            if ($t_field->field_id == $customField->id) {
                                if (in_array($customField->type, [7, 17])) {
                                    // if multi check
                                    $cf_saved_val_array = explode(", ", $t_field->field_value);
                                } else {
                                    $cf_saved_val = $t_field->field_value;
                                }
                            }
                        }
                    }
                } else {
                    $cf_saved_val = $customField->default_value;
                    if (@$oldInputs['customField_'.$customField->id] != "") {
                        if (in_array($customField->type, [7, 17])) {
                            // if multi check
                            $cf_saved_val_array = @$oldInputs['customField_'.$customField->id];
                        } else {
                            $cf_saved_val = @$oldInputs['customField_'.$customField->id];
                        }
                    }
                }


                ?>

            @if($cf_land_active)
                @if($customField->type ==99)
                    <div class="m-t-2 m-b-1 fixed_text">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                {!! str_replace(["<p>","<p ","</p>"],["<div>","<div ","</div>"],$customField->{"details_" . @Helper::currentLanguage()->code}) !!}
                            </div>
                        </div>
                    </div>
                @elseif($customField->type ==12)
                    {{--Vimeo Video Link--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!} <i class="fa fa-vimeo"></i>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" value="{{ $cf_saved_val }}" placeholder="" maxlength="191" autocomplete="off" {{ $cf_required }} dir="ltr">
                        </div>
                    </div>
                @elseif($customField->type ==11)
                    {{--Youtube Video Link--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!} <i class="fa fa-youtube"></i>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" value="{{ $cf_saved_val }}" placeholder="" maxlength="191" autocomplete="off" {{ $cf_required }} dir="ltr">
                        </div>
                    </div>
                @elseif($customField->type ==10)
                    {{--Video File--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}</label>
                        <div class="col-sm-10">
                            @if($cf_saved_val !="")
                                    <?php
                                    $file_name_id = 'topic_file_'.$customField->id;
                                    $file_del_id = 'file_delete_'.$customField->id;
                                    $file_old_id = 'file_old_'.$customField->id;
                                    $file_undo_id = 'undo_'.$customField->id;
                                    ?>
                                <div id="{{$file_name_id}}" class="col-sm-4 box p-a-xs">
                                    <video width="380" height="230" controls>
                                        <source src="{{ route("fileView",["path" =>'topics/'.$cf_saved_val]) }}"
                                                type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <a target="_blank"
                                       href="{{ route("fileView",["path" =>'topics/'.$cf_saved_val]) }}">
                                        {{ $cf_saved_val }}
                                    </a>
                                    <br>
                                    <a onclick="document.getElementById('{{$file_name_id}}').style.display='none';document.getElementById('{{$file_del_id}}').value='1';document.getElementById('{{$file_undo_id}}').style.display='block';"
                                       class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                </div>
                                <div id="{{$file_undo_id}}" class="col-sm-4 p-a-xs"
                                     style="display: none">
                                    <a onclick="document.getElementById('{{$file_name_id}}').style.display='block';document.getElementById('{{$file_del_id}}').value='0';document.getElementById('{{$file_undo_id}}').style.display='none';">
                                        <i class="material-icons">
                                            &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                </div>

                                <input type="hidden" name="{{ $file_del_id }}" value="0" id="{{ $file_del_id }}">
                                <input type="hidden" name="{{ $file_old_id }}" value="{{ $cf_saved_val }}" id="{{ $file_old_id }}">
                            @endif
                            <input type="file" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" accept="*" {{ ($cf_saved_val=="")?$cf_required:"" }}>
                        </div>
                    </div>
                @elseif($customField->type ==9)
                    {{--Attach File--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}</label>
                        <div class="col-sm-10">
                            @if($cf_saved_val !="")
                                    <?php
                                    $file_name_id = 'topic_file_'.$customField->id;
                                    $file_del_id = 'file_delete_'.$customField->id;
                                    $file_old_id = 'file_old_'.$customField->id;
                                    $file_undo_id = 'undo_'.$customField->id;
                                    ?>
                                <div id="{{$file_name_id}}" class="col-sm-4 box p-a-xs">
                                    <a target="_blank"
                                       href="{{ route("fileView",["path" =>'topics/'.$cf_saved_val])}}">
                                        {{ $cf_saved_val }}
                                    </a>
                                    <br>
                                    <a onclick="document.getElementById('{{$file_name_id}}').style.display='none';document.getElementById('{{$file_del_id}}').value='1';document.getElementById('{{$file_undo_id}}').style.display='block';"
                                       class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                </div>
                                <div id="{{$file_undo_id}}" class="col-sm-4 p-a-xs"
                                     style="display: none">
                                    <a onclick="document.getElementById('{{$file_name_id}}').style.display='block';document.getElementById('{{$file_del_id}}').value='0';document.getElementById('{{$file_undo_id}}').style.display='none';">
                                        <i class="material-icons">
                                            &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                </div>
                                <input type="hidden" name="{{ $file_del_id }}" value="0" id="{{ $file_del_id }}">
                                <input type="hidden" name="{{ $file_old_id }}" value="{{ $cf_saved_val }}" id="{{ $file_old_id }}">
                            @endif
                            <input type="file" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" accept="*" {{ ($cf_saved_val=="")?$cf_required:"" }}>
                        </div>
                    </div>
                @elseif($customField->type ==8)
                    {{--Photo File--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}</label>
                        <div class="col-sm-10">
                            @if($cf_saved_val !="")
                                    <?php
                                    $file_name_id = 'topic_file_'.$customField->id;
                                    $file_del_id = 'file_delete_'.$customField->id;
                                    $file_old_id = 'file_old_'.$customField->id;
                                    $file_undo_id = 'undo_'.$customField->id;
                                    ?>
                                <div id="{{$file_name_id}}" class="col-sm-4 box p-a-xs">
                                    <a target="_blank"
                                       href="{{ route("fileView",["path" =>'topics/'.$cf_saved_val]) }}"><img
                                            src="{{ route("fileView",["path" =>'topics/'.$cf_saved_val]) }}"
                                            class="img-responsive">
                                        {{ $cf_saved_val }}
                                    </a>
                                    <br>
                                    <a onclick="document.getElementById('{{$file_name_id}}').style.display='none';document.getElementById('{{$file_del_id}}').value='1';document.getElementById('{{$file_undo_id}}').style.display='block';"
                                       class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                </div>
                                <div id="{{$file_undo_id}}" class="col-sm-4 p-a-xs"
                                     style="display: none">
                                    <a onclick="document.getElementById('{{$file_name_id}}').style.display='block';document.getElementById('{{$file_del_id}}').value='0';document.getElementById('{{$file_undo_id}}').style.display='none';">
                                        <i class="material-icons">
                                            &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                </div>

                                <input type="hidden" name="{{ $file_del_id }}" value="0" id="{{ $file_del_id }}">
                                <input type="hidden" name="{{ $file_old_id }}" value="{{ $cf_saved_val }}" id="{{ $file_old_id }}">
                            @endif
                            <input type="file" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" accept="image/*" {{ ($cf_saved_val=="")?$cf_required:"" }}>
                        </div>
                    </div>
                @elseif($customField->type ==17)
                    {{--Checkboxes--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}</label>
                        <div class="col-sm-10">
                                <?php
                                $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                $cf_details_var2 = "details_".config('smartend.default_language');
                                if ($customField->$cf_details_var != "") {
                                    $cf_details = $customField->$cf_details_var;
                                } else {
                                    $cf_details = $customField->$cf_details_var2;
                                }
                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                $line_num = 1;
                                ?>
                            @foreach ($cf_details_lines as $cf_details_line)
                                <div class="m-t-sm">
                                    <label class="md-check">
                                        <input type="checkbox" value="{{ $line_num }}"
                                               name="{{'customField_'.$customField->id}}[]"
                                               id="{{'customField_'.$customField->id}}_{{$line_num}}"
                                               {{ (in_array($line_num,$cf_saved_val_array)) ? "checked":""  }} class="has-value">
                                        <i class="primary"></i>
                                        {{ $cf_details_line }}
                                    </label>
                                </div>
                                    <?php
                                    $line_num++;
                                    ?>
                            @endforeach
                        </div>
                    </div>
                @elseif($customField->type ==13)
                    {{--Radio--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}</label>
                        <div class="col-sm-10">
                                <?php
                                $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                $cf_details_var2 = "details_".config('smartend.default_language');
                                if ($customField->$cf_details_var != "") {
                                    $cf_details = $customField->$cf_details_var;
                                } else {
                                    $cf_details = $customField->$cf_details_var2;
                                }
                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                $line_num = 1;
                                ?>
                            @foreach ($cf_details_lines as $cf_details_line)
                                <div class="m-t-sm">
                                    <label class="md-check">
                                        <input type="radio" value="{{ $line_num }}"
                                               name="{{'customField_'.$customField->id}}"
                                               {{$cf_required}}
                                               id="{{'customField_'.$customField->id}}_{{$line_num}}"
                                               {{ ($cf_saved_val == $line_num) ? "checked":""  }} class="has-value">
                                        <i class="primary"></i>
                                        {{ $cf_details_line }}
                                    </label>
                                </div>
                                    <?php
                                    $line_num++;
                                    ?>
                            @endforeach
                        </div>
                    </div>
                @elseif($customField->type ==14)
                    {{--Checkbox--}}
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <label class="md-check">
                                <input type="checkbox" name="{{'customField_'.$customField->id}}"
                                       {{ ($cf_saved_val == 1) ? "checked":""  }} value="1"
                                       id="{{'customField_'.$customField->id}}" class="has-value" {{$cf_required}}>
                                <i class="primary"></i>
                                {!!  $cf_title !!}
                                {!! $cf_land_identifier !!}
                            </label>
                        </div>
                    </div>
                @elseif(in_array($customField->type,[7,17]))
                    {{--Multi Check--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}</label>
                        <div class="col-sm-10">
                            <select name="{{'customField_'.$customField->id}}[]"
                                    id="{{'customField_'.$customField->id}}"
                                    class="form-control form-select2-multiple" multiple
                                    ui-jp="select2"
                                    ui-options="{theme: 'bootstrap'}" {{$cf_required}}>
                                    <?php
                                    $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                    $cf_details_var2 = "details_".config('smartend.default_language');
                                    if ($customField->$cf_details_var != "") {
                                        $cf_details = $customField->$cf_details_var;
                                    } else {
                                        $cf_details = $customField->$cf_details_var2;
                                    }
                                    $cf_details_lines = preg_split('/\r\n|[\r\n]/',
                                        $cf_details);
                                    $line_num = 1;
                                    ?>
                                @foreach ($cf_details_lines as $cf_details_line)
                                    <option
                                        value="{{ $line_num  }}" {{ (in_array($line_num,$cf_saved_val_array)) ? "selected='selected'":""  }}>{{ $cf_details_line }}</option>
                                        <?php
                                        $line_num++;
                                        ?>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @elseif($customField->type ==6)
                    {{--Select--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}</label>
                        <div class="col-sm-10">
                            <select name="{{'customField_'.$customField->id}}"
                                    id="{{'customField_'.$customField->id}}"
                                    class="form-control form-select2"
                                    ui-jp="select2"
                                    ui-options="{theme: 'bootstrap'}" {{$cf_required}}>
                                <option value="">- - {!!  $cf_title !!} - -</option>
                                    <?php
                                    $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                    $cf_details_var2 = "details_".config('smartend.default_language');
                                    if ($customField->$cf_details_var != "") {
                                        $cf_details = $customField->$cf_details_var;
                                    } else {
                                        $cf_details = $customField->$cf_details_var2;
                                    }
                                    $cf_details_lines = preg_split('/\r\n|[\r\n]/',
                                        $cf_details);
                                    $line_num = 1;
                                    ?>
                                @foreach ($cf_details_lines as $cf_details_line)
                                    <option
                                        value="{{ $line_num  }}" {{ ($cf_saved_val == $line_num) ? "selected='selected'":""  }}>{{ $cf_details_line }}</option>
                                        <?php
                                        $line_num++;
                                        ?>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @elseif($customField->type ==5)
                    {{--Date & Time--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ Helper::formatDate($cf_saved_val)." ".date("h:i A", strtotime($cf_saved_val)) }}" maxlength="100" placeholder="" class="form-control form_datetime" dir="{{ $cf_land_dir }}" {{ $cf_required }}/>
                        </div>
                    </div>
                @elseif($customField->type ==4)
                    {{--Date--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ Helper::formatDate($cf_saved_val)." ".date("h:i A", strtotime($cf_saved_val)) }}" maxlength="100" placeholder="" class="form-control form_date" dir="{{ $cf_land_dir }}" {{ $cf_required }}/>
                        </div>
                    </div>
                @elseif($customField->type ==3)
                    {{--Email Address--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="email" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" value="{{ $cf_saved_val }}" placeholder="" autocomplete="off" {{ $cf_required }} dir="{{ $cf_land_dir }}">
                        </div>
                    </div>

                @elseif($customField->type ==2)
                    {{--Number--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}
                            {!! $cf_land_identifier !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="number" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" value="{{ $cf_saved_val }}" min="0" step="0.01" autocomplete="off" {{ $cf_required }} dir="{{ $cf_land_dir }}">
                        </div>
                    </div>
                @elseif($customField->type ==16)
                    {{--Text Editor--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}
                        </label>
                        <div class="col-sm-10">
                                <?php
                                $load_editor = 1;
                                if (Helper::GeneralWebmasterSettings("text_editor") == 2) {
                                    $editor = "ckeditor";
                                    $frame_class = "";
                                } elseif (Helper::GeneralWebmasterSettings("text_editor") == 1) {
                                    $editor = "tinymce";
                                    $frame_class = "";
                                } else {
                                    $editor = "summernote";
                                    $frame_class = "box p-a-xs";
                                }
                                ?>
                            <div class="{{ $frame_class }}">
                                <textarea name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" dir="{{ $cf_land_dir }}" rows="5" {{ $cf_required }} class="form-control {{ $editor }}">{{ $cf_saved_val }}</textarea>
                            </div>
                        </div>
                    </div>
                @elseif($customField->type ==1)
                    {{--Text Area--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}
                        </label>
                        <div class="col-sm-10">
                            <textarea name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" dir="{{ $cf_land_dir }}" rows="5" {{ $cf_required }} class="form-control">{{ $cf_saved_val }}</textarea>
                        </div>
                    </div>
                @else
                        <?php
                        if ($customField->type == 15 && $cf_saved_val == "") {
                            $PhoneFieldsIds[] = $customField->id;
                        }
                        ?>
                    {{--Text Box--}}
                    <div class="form-group row">
                        <label for="{{'customField_'.$customField->id}}" class="col-sm-2 form-control-label">{!!  $cf_title !!}  {!! $cf_required_star !!}
                            {!! $cf_land_identifier !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" value="{{ $cf_saved_val }}" autocomplete="off" {{ $cf_required }} dir="{{ $cf_land_dir }}">
                        </div>
                    </div>
                @endif
            @endif
                <?php
            }
                ?>
        @endif
    @endforeach

    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard/js/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') }}?v={{ Helper::system_version() }}"/>
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard/js/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.dark.css') }}?v={{ Helper::system_version() }}"/>
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard/js/select2-bootstrap-theme/dist/select2-bootstrap.css') }}?v={{ Helper::system_version() }}"/>
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard/js/select2-bootstrap-theme/dist/select2-bootstrap.4.css') }}?v={{ Helper::system_version() }}"/>
    <script src="{{ URL::asset('assets/dashboard/js/select2/dist/js/select2.min.js') }}?v={{ Helper::system_version() }}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}?v={{ Helper::system_version() }}"></script>
    <script>
        $(".form-select2").select2({
            theme: 'bootstrap'
        });
        $(".form-select2-multiple").select2({
            tags: true,
            theme: 'bootstrap',
        });
        $('.datetime').datetimepicker({
            format: '{{ Helper::jsDateFormat() }}',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            },
            allowInputToggle: true,
            locale: '{{ @Helper::currentLanguage()->code }}'
        });
        $('.form_datetime').datetimepicker({
            format: '{{ Helper::jsDateFormat() }} hh:mm A',
            allowInputToggle: true,
            locale: '{{ @Helper::currentLanguage()->code }}'
        });
        $('.form_date').datetimepicker({
            format: '{{ Helper::jsDateFormat() }}',
            allowInputToggle: true,
            locale: '{{ @Helper::currentLanguage()->code }}'
        });
    </script>
    @if(count($PhoneFieldsIds) >0)
        <link rel="stylesheet"
              href="{{ URL::asset('assets/frontend/vendor/intl-tel-input/css/intlTelInput.min.css') }}?v={{ Helper::system_version() }}"/>
        <script
            src="{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/intlTelInput.min.js') }}?v={{ Helper::system_version() }}"></script>
    @endif
    <script>
        @foreach($PhoneFieldsIds as $PhoneFieldId)
        var iti = window.intlTelInput(document.querySelector("#customField_{{ $PhoneFieldId }}"), {
            showSelectedDialCode: true,
            countrySearch: true,
            initialCountry: "auto",
            separateDialCode: true,
            hiddenInput: function () {
                return {
                    phone: "customField_{{ $PhoneFieldId }}_phone_full",
                    country: "customField_{{ $PhoneFieldId }}_country_code"
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
        @endforeach
    </script>
@endif
@if($WebmasterSection->type != 10)
    @include('dashboard.layouts.editor',['StopEditorCode'=>1])
@endif
