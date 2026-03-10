<div class="dker b-b displayNone" id="filter_div">
    <div class="p-a">
        <form method="GET" action="{{ route("topics",$WebmasterSection->id) }}" class="dashboard-form" id="filter_form" target="">
            <input type="hidden" name="stat" id="search_submit_stat" value="">
            <div class="filter_div">
                <div class="row">
                    @if($WebmasterSection->title_status || $WebmasterSection->longtext_status)
                        <div class="col-md-3 col-xs-6 m-b-5p">
                            <input type="text" name="find_q" id="find_q" class="form-control" value="{{ @$_GET['find_q'] }}" placeholder="{{ __('backend.searchFor') }}" autocomplete="off">
                        </div>
                    @endif
                    @if($WebmasterSection->sections_status!=0)
                        <div class="col-md-3 col-xs-6 m-b-5p">
                            <div class="form-group m-b-0">
                                <select name="section_id" id="find_section_id" class="form-control select2"
                                        ui-jp="select2"
                                        ui-options="{theme: 'bootstrap'}">
                                    <option value="">{{ __('backend.category') }} ( {{ __('backend.all') }} )
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
                                        <option
                                            value="{{ $fatherSection->id  }}">{{ $ftitle }}</option>
                                        @foreach ($fatherSection->fatherSections as $subFatherSection)
                                                <?php
                                                if ($subFatherSection->$title_var != "") {
                                                    $title = $subFatherSection->$title_var;
                                                } else {
                                                    $title = $subFatherSection->$title_var2;
                                                }
                                                ?>
                                            <option
                                                value="{{ $subFatherSection->id  }}"> {!! $ftitle !!} {!! $t_arrow !!} {!! $title !!}</option>

                                            @foreach ($subFatherSection->fatherSections as $sub2FatherSection)
                                                    <?php
                                                    if ($sub2FatherSection->$title_var != "") {
                                                        $title2 = $sub2FatherSection->$title_var;
                                                    } else {
                                                        $title2 = $sub2FatherSection->$title_var2;
                                                    }
                                                    ?>
                                                <option
                                                    value="{{ $sub2FatherSection->id  }}"> {!! $ftitle !!} {!! $t_arrow !!} {!! $title !!} {!! $t_arrow !!} {!! $title2 !!}</option>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    @if($WebmasterSection->date_status)
                        <div class="col-md-3 col-xs-6 m-b-5p">
                            <div class="form-group m-b-0">
                                <div class='input-group date' ui-jp="datetimepicker" ui-options="{
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
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                    <input type="text" name="date" id="find_date" class="form-control" value="{{ ((@$_GET['date']!="")?Helper::formatDate(@$_GET['date']):"") }}" placeholder="{{ __('backend.topicDate') }}" autocomplete="off">
                                    <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @foreach($WebmasterSection->customFields as $customField)
                        @if($customField->in_search)
                                <?php
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
                                ?>
                            @if($cf_land_active)
                                @if($customField->type ==12)

                                @elseif($customField->type ==11)

                                @elseif($customField->type ==10)

                                @elseif($customField->type ==9)

                                @elseif($customField->type ==8)
                                @elseif($customField->type ==14)
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <select name="{{'customField_'.$customField->id}}"
                                                id="{{'customField_'.$customField->id}}"
                                                class="form-control c-select" {{$cf_required}}>
                                            <option value="">- - {!!  $cf_title !!} - -</option>
                                            <option value="1">{{ __('backend.yes') }}</option>
                                            <option value="0">{{ __('backend.no') }}</option>
                                        </select>
                                    </div>
                                @elseif(in_array($customField->type,[6,7,13,17]))
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <select name="{{'customField_'.$customField->id}}"
                                                id="{{'customField_'.$customField->id}}"
                                                class="form-control c-select" {{$cf_required}}>
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
                                                    value="{{ $line_num  }}" {{ (@$_GET['customField_'.$customField->id] == $line_num) ? "selected='selected'":""  }}>{{ $cf_details_line }}</option>
                                                    <?php
                                                    $line_num++;
                                                    ?>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif($customField->type ==5 || $customField->type ==4)
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <div class="form-group m-b-0">
                                            <div class='input-group date' ui-jp="datetimepicker"
                                                 ui-options="{
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
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                                <input type="text" name="customField_{{ $customField->id }}" id="customField_ {{ $customField->id }}" class="form-control" value="{{ (@$_GET['customField_'.$customField->id]!="")?Helper::formatDate(@$_GET['customField_'.$customField->id]):"" }}" placeholder="{{$cf_title }}" autocomplete="off" maxlength="191" dir="{{ $cf_land_dir }}" {{ $cf_required }}>
                                                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($customField->type ==3)
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <input type="email" name="customField_{{ $customField->id }}" id="customField_ {{ $customField->id }}" class="form-control" value="{{ @$_GET['customField_'.$customField->id] }}" placeholder="{{$cf_title }}" autocomplete="off" maxlength="191" dir="{{ $cf_land_dir }}" {{ $cf_required }}>
                                    </div>
                                @elseif($customField->type ==2)
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <input type="email" name="customField_{{ $customField->id }}" id="customField_ {{ $customField->id }}" class="form-control" value="{{ @$_GET['customField_'.$customField->id] }}" placeholder="{{$cf_title }}" min="0" step="0.01" dir="{{ $cf_land_dir }}" {{ $cf_required }}>
                                    </div>
                                @else
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <input type="text" name="customField_{{ $customField->id }}" id="customField_ {{ $customField->id }}" class="form-control" value="{{ @$_GET['customField_'.$customField->id] }}" placeholder="{{$cf_title }}" autocomplete="off" maxlength="191" dir="{{ $cf_land_dir }}" {{ $cf_required }}>
                                    </div>
                                @endif
                            @endif
                        @endif
                    @endforeach

                    <div class="col-md-3 col-xs-6 m-b-5p">
                        <select name="created_by"
                                id="find_created_by"
                                class="form-control select2"
                                ui-jp="select2"
                                ui-options="{theme: 'bootstrap'}">
                            <option value="">{{ __('backend.createdBy') }} ( {{ __('backend.all') }} )
                            </option>
                            @foreach ($UsersList as $Usr)
                                <option
                                    value="{{ $Usr->id  }}">{{ $Usr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 col-xs-6 m-b-5p">
                        <button class="btn white w-full" id="search-btn" type="button"><i
                                class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
