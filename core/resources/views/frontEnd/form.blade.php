@if(Session::has('doneMessage'))
    <div class="padding p-b-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! Session::get('doneMessage') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
@endif
@if(Session::has('errorMessage'))
    <div class="padding p-b-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! Session::get('errorMessage') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
@endif
@if(!empty(@$errors))
    @if(@$errors->any())
        <div class="padding p-b-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <ul class="m-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
@if(@$FormSectionID >0)
        <?php
        // Get banners list array by settings ID (You can get settings ID from Webmaster >> Banners settings)
        $WebmasterSection = Helper::WebmasterSection($FormSectionID);
        $fatherSections = Helper::SectionCategories($FormSectionID);
        $PhoneFieldsIds = [];
        ?>
    @if(!empty($WebmasterSection))
        <div class="form-block">
            @if(@$viewStyle =="simple")
            @else
                <h4 class="pt-3 text-muted form-title">
                    {!!  $WebmasterSection->{"title_".@Helper::currentLanguage()->code} !!}
                </h4>
            @endif
            <div class="{{ (@$viewStyle =="simple")?"":"bottom-article" }}">
                <form method="POST" action="{{ route("formSubmit") }}" enctype="multipart/form-data" class="custom-form" id="custom-form">
                    @csrf
                    @honeypot
                    <div class="form-group row">
                        @if($WebmasterSection->date_status)
                            <div class="col-lg-4 col-md-12">
                                <label for="form_date" class="form-control-label">{!!  __('backend.topicDate') !!}</label>
                                <input type="text" autocomplete="off" name="date" id="form_date" value="{{ Helper::formatDate(date("Y-m-d")) }}" required maxlength="100" placeholder="" class="form-control form_date"/>

                            </div>
                        @else
                            <input type="hidden" name="date" value="{{ date("Y-m-d") }}" class="form-control form_date">
                        @endif

                        @if($WebmasterSection->expire_date_status)
                            <div class="col-lg-4 col-md-12">
                                <label for="form_expire_date" class="form-control-label">{!!  __('backend.expireDate') !!}</label>
                                <input type="text" autocomplete="off" name="expire_date" id="form_expire_date" value="" required maxlength="100" placeholder="" class="form-control form_date"/>
                            </div>
                        @endif

                        @if($WebmasterSection->sections_status!=0)
                            <div class="col-lg-4 col-md-12">
                                <label for="section_id"
                                       class="form-control-label">{!!  __('backend.categories') !!} </label>
                                <select name="section_id" id="section_id" class="form-control c-select" required>
                                    <option value=""> - - {!!  __('backend.select') !!} - -</option>
                                        <?php
                                        $title_var = "title_".@Helper::currentLanguage()->code;
                                        $title_var2 = "title_".config('smartend.default_language');
                                        $t_arrow = "&raquo;";
                                        ?>
                                    @foreach ($fatherSections as $fatherSection)
                                            <?php
                                            if ($fatherSection->$title_var != "") {
                                                $ftitle = $fatherSection->$title_var;
                                            } else {
                                                $ftitle = $fatherSection->$title_var2;
                                            }
                                            ?>
                                        <option value="{{ $fatherSection->id  }}">{!! $ftitle !!}</option>
                                        @foreach ($fatherSection->fatherSections as $subFatherSection)
                                                <?php
                                                if ($subFatherSection->$title_var != "") {
                                                    $title = $subFatherSection->$title_var;
                                                } else {
                                                    $title = $subFatherSection->$title_var2;
                                                }
                                                ?>
                                            <option
                                                value="{{ $subFatherSection->id  }}">{!! $ftitle !!} {!! $t_arrow !!} {!! $title !!}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="section_id" value="0" class="form-control">
                        @endif

                        @if($WebmasterSection->title_status)
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="col-lg-12 col-md-12">
                                        <label for="form_title_{{ @$ActiveLanguage->code }}"
                                               class="form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        </label>
                                        <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="form_title_{{ @$ActiveLanguage->code }}" value="" required maxlength="100"
                                               placeholder="" class="form-control"/>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        @if($WebmasterSection->longtext_status)
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="col-sm-12">
                                        <label class="form-control-label" for="form_details_{{ @$ActiveLanguage->code }}">{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        </label>
                                        <textarea name="details_{{ @$ActiveLanguage->code }}" id="form_details_{{ @$ActiveLanguage->code }}" class="form-control" placeholder="" rows="8" required></textarea>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        @if($WebmasterSection->photo_status)
                            <div class="col-lg-4 col-md-12">
                                <label for="form_photo_file"
                                       class="form-control-label">{!!  __('backend.topicPhoto') !!}</label>
                                <input type="file" autocomplete="off" name="photo_file" id="form_photo_file" value="" accept="image/*" class="form-control"/>
                                <small>
                                    {!!  __('backend.imagesTypes') !!}
                                </small>
                            </div>
                        @endif

                        @if($WebmasterSection->attach_file_status)
                            <div class="col-lg-4 col-md-12">
                                <label for="form_attach_file"
                                       class="form-control-label">{!!  __('backend.topicAttach') !!}</label>
                                <input type="file" autocomplete="off" name="attach_file" id="form_attach_file" value="" class="form-control"/>
                                <small>
                                    {!!  __('backend.attachTypes') !!}
                                </small>
                            </div>
                        @endif

                        {{--Additional Feilds--}}
                        @if(count($WebmasterSection->customFields) >0)
                                <?php
                                $cf_title_var = "title_".@Helper::currentLanguage()->code;
                                $cf_title_var2 = "title_".config('smartend.default_language');
                                ?>
                            @foreach($WebmasterSection->customFields as $customField)
                                    <?php
                                    // check permission
                                    $add_permission_groups = [];
                                    if ($customField->add_permission_groups != "") {
                                        $add_permission_groups = explode(",", $customField->add_permission_groups);
                                    }
                                    // have permission & continue
                                    if ($customField->$cf_title_var != "") {
                                        $cf_title = $customField->$cf_title_var;
                                    } else {
                                        $cf_title = $customField->$cf_title_var2;
                                    }

                                    // check field language status
                                    $cf_lang_identifier = "";
                                    $cf_land_active = false;
                                    $cf_land_dir = @Helper::currentLanguage()->direction;
                                    if ($customField->lang_code != "all") {
                                        $ct_language = @Helper::LangFromCode($customField->lang_code);
                                        $cf_lang_identifier = @Helper::languageName($ct_language);
                                        $cf_land_dir = $ct_language->direction;
                                        if ($ct_language->box_status) {
                                            $cf_land_active = true;
                                        }
                                    }
                                    if ($customField->lang_code == "all") {
                                        $cf_land_active = true;
                                    }

                                    if (@count($add_permission_groups) > 0) {
                                        if (!in_array(0, $add_permission_groups)) {
                                            $cf_land_active = false;
                                        }
                                    }

                                    // required Status
                                    $cf_required = '';
                                    $cf_required_star = '';
                                    if ($customField->required) {
                                        $cf_required = 'required';
                                        $cf_required_star = '<span class="text-danger">*</span>';
                                    }
                                    if (@old('customField_'.$customField->id) != "") {
                                        $customField->default_value = @old('customField_'.$customField->id);
                                    }

                                    ?>

                                @if($cf_land_active)
                                    @if($customField->type ==99)
                                        <div class="mt-2 mb-2 fixed_text">
                                            {!! str_replace(["<p>","<p ","</p>"],["<div>","<div ","</div>"],$customField->{"details_" . @Helper::currentLanguage()->code}) !!}
                                        </div>

                                    @elseif($customField->type ==15)
                                        {{--phone number with country code--}}
                                            <?php
                                            $PhoneFieldsIds[] = $customField->id;
                                            ?>
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}
                                            </label>
                                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" {{ $cf_required }} minlength="5" maxlength="15" placeholder="" dir="ltr" class="form-control"/>
                                        </div>
                                    @elseif($customField->type ==17)
                                        {{--Checkboxes--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}</label>
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
                                            <div class="mb-3 form-checkboxes">
                                                @foreach ($cf_details_lines as $cf_details_line)
                                                    <div class="m-t-sm">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   value="{{ $line_num }}"
                                                                   name="{{'customField_'.$customField->id}}[]"
                                                                   id="{{'customField_'.$customField->id}}_{{$line_num}}" {{ ($customField->default_value == $line_num) ? "checked":""  }}>
                                                            <label class="form-check-label"
                                                                   for="{{'customField_'.$customField->id}}_{{$line_num}}">
                                                                {{ $cf_details_line }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                        <?php
                                                        $line_num++;
                                                        ?>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($customField->type ==13)
                                        {{--Radio--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}</label>
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
                                            <div class="mb-3 form-radios">
                                                @foreach ($cf_details_lines as $cf_details_line)
                                                    <div class="m-t-sm">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                   value="{{ $line_num }}"
                                                                   name="{{'customField_'.$customField->id}}"
                                                                   {{$cf_required}}
                                                                   id="{{'customField_'.$customField->id}}_{{$line_num}}" {{ ($customField->default_value == $line_num) ? "checked":""  }}>
                                                            <label class="form-check-label"
                                                                   for="{{'customField_'.$customField->id}}_{{$line_num}}">
                                                                {{ $cf_details_line }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                        <?php
                                                        $line_num++;
                                                        ?>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($customField->type ==14)
                                        {{--Checkbox--}}
                                        <div class="col-sm-12">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                       name="{{'customField_'.$customField->id}}"
                                                       id="{{'customField_'.$customField->id}}" {{$cf_required}} value="1">
                                                <label class="form-check-label"
                                                       for="{{'customField_'.$customField->id}}">{!!  $cf_title !!}
                                                    {!! $cf_lang_identifier !!}</label>
                                            </div>
                                        </div>
                                    @elseif($customField->type ==12)
                                        {{--Vimeo Video Link--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!} <i class="fa fa-vimeo"></i>
                                            </label>
                                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" {{ $cf_required }} maxlength="191" placeholder="" dir="ltr" class="form-control"/>
                                        </div>
                                    @elseif($customField->type ==11)
                                        {{--Youtube Video Link--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!} <i class="fa fa-youtube"></i>
                                            </label>
                                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" {{ $cf_required }} maxlength="191" placeholder="" dir="ltr" class="form-control"/>
                                        </div>
                                    @elseif($customField->type ==10)
                                        {{--Video File--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}</label>

                                            <input type="file" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" {{ $cf_required }} accept="*" class="form-control"/>
                                        </div>
                                    @elseif($customField->type ==9)
                                        {{--Attach File--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}</label>
                                            <input type="file" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" {{ $cf_required }} accept="*" class="form-control"/>
                                        </div>
                                    @elseif($customField->type ==8)
                                        {{--Photo File--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}</label>
                                            <input type="file" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" {{ $cf_required }} accept="image/*" class="form-control"/>
                                        </div>
                                    @elseif(in_array($customField->type,[7,17]))
                                        {{--Multi Check--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}</label>
                                            <select name="{{'customField_'.$customField->id}}[]"
                                                    id="{{'customField_'.$customField->id}}"
                                                    class="form-control select2-multiple" multiple {{$cf_required}}>
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
                                                    <option
                                                        value="{{ $line_num  }}" {{ (is_array($customField->default_value))?((@in_array($line_num,$customField->default_value)) ? "selected='selected'":""):""  }}>{{ $cf_details_line }}</option>
                                                        <?php
                                                        $line_num++;
                                                        ?>
                                                @endforeach
                                            </select>
                                        </div>
                                    @elseif($customField->type ==6)
                                        {{--Select--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}</label>
                                            <select name="{{'customField_'.$customField->id}}"
                                                    id="{{'customField_'.$customField->id}}"
                                                    class="form-control select2" {{$cf_required}}>
                                                <option value="">- - {!!  $cf_title !!} - -</option>
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
                                                    <option
                                                        value="{{ $line_num  }}" {{ ($customField->default_value == $line_num) ? "selected='selected'":""  }}>{{ $cf_details_line }}</option>
                                                        <?php
                                                        $line_num++;
                                                        ?>
                                                @endforeach
                                            </select>
                                        </div>
                                    @elseif($customField->type ==5)
                                        {{--Date & Time--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}
                                            </label>
                                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="" maxlength="100" placeholder="" class="form-control form_datetime" dir="{{ $cf_land_dir }}" {{ $cf_required }}/>
                                        </div>
                                    @elseif($customField->type ==4)
                                        {{--Date--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}
                                            </label>
                                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ Helper::formatDate($customField->default_value) }}" maxlength="100" placeholder="" class="form-control form_date" dir="{{ $cf_land_dir }}" {{ $cf_required }}/>

                                        </div>
                                    @elseif($customField->type ==3)
                                        {{--Email Address--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}
                                            </label>
                                            <input type="email" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" maxlength="100" placeholder="" class="form-control" dir="{{ $cf_land_dir }}" {{ $cf_required }}/>
                                        </div>
                                    @elseif($customField->type ==2)
                                        {{--Number--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}
                                            </label>
                                            <input type="number" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" min="0" step="0.01" placeholder="" class="form-control" dir="{{ $cf_land_dir }}" {{ $cf_required }}/>
                                        </div>
                                    @elseif($customField->type ==1 || $customField->type ==16)
                                        {{--Text Area--}}
                                        <div class="col-sm-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}
                                            </label>
                                            <textarea name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" class="form-control" placeholder="" rows="5" dir="{{ $cf_land_dir }}" {{ $cf_required }}>{{ $customField->default_value }}</textarea>
                                        </div>
                                    @else
                                        {{--Text Box--}}
                                        <div class="col-lg-4 col-md-12">
                                            <label for="{{'customField_'.$customField->id}}"
                                                   class="form-control-label">{!!  $cf_title !!} {!! $cf_required_star !!}
                                                {!! $cf_lang_identifier !!}
                                            </label>

                                            <input type="text" autocomplete="off" name="customField_{{ $customField->id }}" id="customField_{{ $customField->id }}" value="{{ $customField->default_value }}" maxlength="190" placeholder="" class="form-control" dir="{{ $cf_land_dir }}" {{ $cf_required }}/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        {{--End of -- Additional Feilds--}}


                        <div class="col-lg-4 col-md-12">
                            @if(config('smartend.nocaptcha_status'))
                                <div class="form-group mb-3">
                                    {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                                    {!! NoCaptcha::display() !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="TopicID" value="{{ @$Topic->id }}">
                    <input type="hidden" name="WebmasterSectionId" value="{{ encrypt($WebmasterSection->id) }}">
                    <button type="submit"
                            class="btn btn-lg submit-btn btn-theme"><i
                            class="fa-solid fa-paper-plane"></i> {{ __('backend.submit') }}</button>
                </form>
            </div>
        </div>

        @push('after-styles')
            <link rel="stylesheet"
                  href="{{ URL::asset('assets/frontend/vendor/datetime-picker/css/bootstrap-datetimepicker.min.css') }}?v={{ Helper::system_version() }}"/>
            <link rel="stylesheet"
                  href="{{ URL::asset('assets/frontend/vendor/select2/css/select2.min.css') }}?v={{ Helper::system_version() }}"/>
            @if(count($PhoneFieldsIds) >0)
                <link rel="stylesheet"
                      href="{{ URL::asset('assets/frontend/vendor/intl-tel-input/css/intlTelInput.min.css') }}?v={{ Helper::system_version() }}"/>
            @endif
        @endpush
        @push('after-scripts')
            <script
                src="{{ URL::asset('assets/frontend/vendor/datetime-picker/js/bootstrap-datetimepicker.min.js') }}?v={{ Helper::system_version() }}"></script>
            <script
                src="{{ URL::asset('assets/frontend/vendor/datetime-picker/js/locales/bootstrap-datetimepicker.'.@Helper::currentLanguage()->code.'.js') }}?v={{ Helper::system_version() }}"></script>
            <script
                src="{{ URL::asset('assets/frontend/vendor/select2/js/select2.min.js') }}?v={{ Helper::system_version() }}"></script>
            @if(count($PhoneFieldsIds) >0)
                <script
                    src="{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/intlTelInput.min.js') }}?v={{ Helper::system_version() }}"></script>
            @endif
            <script type="text/javascript">
                $(function () {
                    $(".select2").select2();
                    $(".select2-multiple").select2({
                        tags: true
                    });
                    $(".form_datetime").datetimepicker({
                        language: '{{ @Helper::currentLanguage()->code }}',
                        rtl: {{ (@Helper::currentLanguage()->direction=="rtl")?"true":"false" }},
                        autoclose: true,
                        todayHighlight: false,
                        showMeridian: true,
                        pickerPosition: 'bottom-{{ @Helper::currentLanguage()->right }}',
                    });
                    $(".form_date").datetimepicker({
                        format: 'yyyy-mm-dd',
                        language: '{{ @Helper::currentLanguage()->code }}',
                        rtl: {{ (@Helper::currentLanguage()->direction=="rtl")?"true":"false" }},
                        autoclose: true,
                        todayHighlight: false,
                        pickerPosition: 'bottom-right',
                        viewSelect: 2,
                        startView: 2,
                        maxView: 0,
                    }).on('changeDate', function (ev) {
                        $('.form_date').datetimepicker('hide');
                    });
                });

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
        @endpush
    @endif
@endif
