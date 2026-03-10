@if (Session::get('fieldST') == "edit")
    <div>
        <form method="POST" action="{{ route("webmasterFieldsUpdate",["webmasterId"=>$WebmasterSections->id,"field_id"=>Session::get('WebmasterSectionField')->id]) }}" class="dashboard-form" enctype="multipart/form-data">
            @csrf
            @foreach(Helper::languagesList() as $ActiveLanguage)
                @if($ActiveLanguage->box_status)
                    <div class="form-group row">
                        <label
                            class="col-sm-2 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="{{ Session::get('WebmasterSectionField')->{'title_'.@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="form-group row">
                <label for="type0"
                       class="col-sm-2 form-control-label">{!!  __('backend.customFieldsType') !!}</label>
                <div class="col-sm-3">
                    <div class="radio fields-types">
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="0" class="has-value" {{ (Session::get('WebmasterSectionField')->type==0)?"checked":"" }} id="type0">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType0') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="1" class="has-value" {{ (Session::get('WebmasterSectionField')->type==1)?"checked":"" }} id="type1">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType1') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="16" class="has-value" {{ (Session::get('WebmasterSectionField')->type==16)?"checked":"" }} id="type16">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType16') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="2" class="has-value" {{ (Session::get('WebmasterSectionField')->type==2)?"checked":"" }} id="type2">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType2') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="15" class="has-value" {{ (Session::get('WebmasterSectionField')->type==15)?"checked":"" }} id="type15">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType15') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="3" class="has-value" {{ (Session::get('WebmasterSectionField')->type==3)?"checked":"" }} id="type3">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType3') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="4" class="has-value" {{ (Session::get('WebmasterSectionField')->type==4)?"checked":"" }} id="type4">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType4') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="5" class="has-value" {{ (Session::get('WebmasterSectionField')->type==5)?"checked":"" }} id="type5">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType5') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="6" class="has-value" {{ (Session::get('WebmasterSectionField')->type==6)?"checked":"" }} id="type6">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType6') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="13" class="has-value" {{ (Session::get('WebmasterSectionField')->type==13)?"checked":"" }} id="type13">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType13') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="7" class="has-value" {{ (Session::get('WebmasterSectionField')->type==7)?"checked":"" }} id="type7">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType7') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="14" class="has-value" {{ (Session::get('WebmasterSectionField')->type==14)?"checked":"" }} id="type14">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType14') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="17" class="has-value" {{ (Session::get('WebmasterSectionField')->type==17)?"checked":"" }} id="type17">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType17') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="8" class="has-value" {{ (Session::get('WebmasterSectionField')->type==8)?"checked":"" }} id="type8">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType8') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="9" class="has-value" {{ (Session::get('WebmasterSectionField')->type==9)?"checked":"" }} id="type9">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType9') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="10" class="has-value" {{ (Session::get('WebmasterSectionField')->type==10)?"checked":"" }} id="type10">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType10') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="11" class="has-value" {{ (Session::get('WebmasterSectionField')->type==11)?"checked":"" }} id="type11">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType11') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="12" class="has-value" {{ (Session::get('WebmasterSectionField')->type==12)?"checked":"" }} id="type12">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType12') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="99" class="has-value" {{ (Session::get('WebmasterSectionField')->type==99)?"checked":"" }} id="type99">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType99') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-sm-7">

                    <div id="fixed_text"
                         style="display: {{(Session::get('WebmasterSectionField')->type==99) ? "inline" : "none"}}">
                        <div>
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="m-b-1">
                                        {!!  __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div>
                                            @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                                <div>
                                                    <textarea name="fixed_details_{{ @$ActiveLanguage->code }}" id="fixed_details_{{ @$ActiveLanguage->code }}" class="form-control ckeditor" dir="{{ @$ActiveLanguage->direction }}">{{ Session::get('WebmasterSectionField')->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                                </div>
                                            @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                                <div>
                                                    <textarea name="fixed_details_{{ @$ActiveLanguage->code }}" id="fixed_details_{{ @$ActiveLanguage->code }}" class="form-control tinymce" dir="{{ @$ActiveLanguage->direction }}">{{ Session::get('WebmasterSectionField')->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                                </div>
                                            @else
                                                <div class="box p-a-xs">
                                                    <textarea name="fixed_details_{{ @$ActiveLanguage->code }}" id="fixed_details_{{ @$ActiveLanguage->code }}" class="form-control summernote summernote_{{ @$ActiveLanguage->code }}" dir="{{ @$ActiveLanguage->direction }}">{{ Session::get('WebmasterSectionField')->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div id="options"
                         style="display: {{(in_array(Session::get('WebmasterSectionField')->type,[6,7,13,17])) ? "inline" : "none"}}">
                        <div class="row">
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="col-sm-3 col-xs-5">
                                        <div class="m-b-sm">
                                            {!!  __('backend.customFieldsOptions') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                            :
                                            <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control" rows="12" style="white-space: nowrap;" dir="{{ @$ActiveLanguage->direction }}">{{ Session::get('WebmasterSectionField')->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                        </div>
                                    </div>

                                @endif
                            @endforeach
                        </div>
                        <small>
                            <i class="material-icons">&#xe8fd;</i> {!!  __('backend.customFieldsOptionsHelp') !!}
                        </small>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="required2"
                       class="col-sm-2 form-control-label">{!!  __('backend.customFieldsRequired') !!}</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="required" value="0" class="has-value" id="required2" {{ (Session::get('WebmasterSectionField')->required==0)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.customFieldsOptional') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="required" value="1" class="has-value" id="required1" {{ (Session::get('WebmasterSectionField')->required==1)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.customFieldsRequired') }} (*)
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="in_table1"
                       class="col-sm-2 form-control-label">{!!  __('backend.showFieldInTable') !!}</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="in_table" value="1" class="has-value" id="in_table1" {{ (Session::get('WebmasterSectionField')->in_table==1)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_table" value="0" class="has-value" id="in_table2" {{ (Session::get('WebmasterSectionField')->in_table==0)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.no') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="in_search1"
                       class="col-sm-2 form-control-label">{!!  __('backend.showFieldInSearch') !!}</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="in_search" value="1" class="has-value" id="in_search1" {{ (Session::get('WebmasterSectionField')->in_search==1)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_search" value="0" class="has-value" id="in_search2" {{ (Session::get('WebmasterSectionField')->in_search==0)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.no') }}
                        </label>
                    </div>
                </div>
            </div>

            @if($WebmasterSections->type != 4)
                <div class="form-group row">
                    <label for="in_listing1"
                           class="col-sm-2 form-control-label">{!!  __('backend.showFieldInListing') !!}</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label class="md-check">
                                <input type="radio" name="in_listing" value="1" class="has-value" id="in_listing1" {{ (Session::get('WebmasterSectionField')->in_listing==1)?"checked":"" }}>
                                <i class="primary"></i>
                                {{ __('backend.yes') }}
                            </label>
                            &nbsp; &nbsp;
                            <label class="md-check">
                                <input type="radio" name="in_listing" value="0" class="has-value" id="in_listing2" {{ (Session::get('WebmasterSectionField')->in_listing==0)?"checked":"" }}>
                                <i class="primary"></i>
                                {{ __('backend.no') }}
                            </label>
                        </div>
                    </div>
                </div>
            @endif
            <div class="form-group row">
                <label for="in_page1"
                       class="col-sm-2 form-control-label">{!!  __('backend.showFieldInPage') !!}</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="in_page" value="1" class="has-value" id="in_page1" {{ (Session::get('WebmasterSectionField')->in_page==1)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_page" value="0" class="has-value" id="in_page2" {{ (Session::get('WebmasterSectionField')->in_page==0)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.no') }}
                        </label>
                    </div>
                </div>
            </div>
            <div
                class="form-group row  in_statics_div {{ (Session::get('WebmasterSectionField')->type==6 || Session::get('WebmasterSectionField')->type==7)?"":"displayNone" }}">
                <label for="in_statics1"
                       class="col-sm-2 form-control-label">{!!  __('backend.showStatics') !!}</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="in_statics" value="1" class="has-value" id="in_statics1" {{ (Session::get('WebmasterSectionField')->in_statics==1)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_statics" value="0" class="has-value" id="in_statics2" {{ (Session::get('WebmasterSectionField')->in_statics==0)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.no') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row" id="default_val"
                 style="display: {{(Session::get('WebmasterSectionField')->type==8 || Session::get('WebmasterSectionField')->type==9 || Session::get('WebmasterSectionField')->type==10) ? "none" : "block"}}">
                <label for="default_value"
                       class="col-sm-2 form-control-label">{!!  __('backend.customFieldsDefault') !!}
                </label>
                <div class="col-sm-10">
                    <input type="text" name="default_value" id="default_value" class="form-control" value="{{ Session::get('WebmasterSectionField')->default_value }}">
                </div>
            </div>

            @if($WebmasterSections->sections_status!=0)
                <div class="form-group row">
                    <label for="categories"
                           class="col-sm-2 form-control-label">{!!  __('backend.fieldCategory') !!}</label>
                    <div class="col-sm-10">
                        <select name="categories[]" id="categories" class="form-control select2-multiple" multiple
                                ui-jp="select2"
                                ui-options="{theme: 'bootstrap'}">
                                <?php
                                $title_var = "title_".@Helper::currentLanguage()->code;
                                $title_var2 = "title_".config('smartend.default_language');
                                $t_arrow = "&raquo;";

                                $categories = array();
                                if (Session::get('WebmasterSectionField')->categories != "") {
                                    $categories = explode(",", Session::get('WebmasterSectionField')->categories);
                                }
                                ?>
                            @foreach ($Categories as $Category)
                                    <?php
                                    if ($Category->$title_var != "") {
                                        $ftitle = $Category->$title_var;
                                    } else {
                                        $ftitle = $Category->$title_var2;
                                    }
                                    ?>
                                <option value="{{ $Category->id  }}" {{ (in_array($Category->id,$categories)) ? "selected='selected'":""  }}>{!! $ftitle !!}</option>
                                @foreach ($Category->fatherSections as $SubCategory)
                                        <?php
                                        if ($SubCategory->$title_var != "") {
                                            $title = $SubCategory->$title_var;
                                        } else {
                                            $title = $SubCategory->$title_var2;
                                        }
                                        ?>
                                    <option value="{{ $SubCategory->id  }}" {{ (in_array($SubCategory->id,$categories)) ? "selected='selected'":""  }}>{!! $ftitle !!} {!! $t_arrow !!} {!! $title !!}</option>
                                    @foreach ($SubCategory->fatherSections as $SubCategory2)
                                            <?php
                                            if ($SubCategory2->$title_var != "") {
                                                $title2 = $SubCategory2->$title_var;
                                            } else {
                                                $title2 = $SubCategory2->$title_var2;
                                            }
                                            ?>
                                        <option value="{{ $SubCategory2->id  }}" {{ (in_array($SubCategory2->id,$categories)) ? "selected='selected'":""  }}> {!! $ftitle !!} {!! $t_arrow !!} {!! $title !!} {!! $t_arrow !!} {!! $title2 !!}</option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                        <small>
                            <i class="material-icons">&#xe8fd;</i> {!!  __('backend.fieldCategoryDesc') !!}
                        </small>
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label for="lang_code"
                       class="col-sm-2 form-control-label">{!!  __('backend.language') !!}
                </label>
                <div class="col-sm-10">
                    <select name="lang_code" id="lang_code" class="form-control c-select">
                        <option
                            value="all" {{ (Session::get('WebmasterSectionField')->lang_code=="all")?"selected='selected'":"" }}>{{ __('backend.customFieldsForAllLang') }}</option>
                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            @if($ActiveLanguage->box_status)
                                <option
                                    value="{{ $ActiveLanguage->code }}" {{ (Session::get('WebmasterSectionField')->lang_code==$ActiveLanguage->code)?"selected='selected'":"" }}>{{ $ActiveLanguage->title }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="css_class"
                       class="col-sm-2 form-control-label"> CSS Class </label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="text" name="css_class" id="css_class" class="form-control" value="{{ Session::get('WebmasterSectionField')->css_class }}">
                        <span class="input-group-btn">
            <button class="btn white" type="button" data-toggle="modal" data-target="#predefined_css_classes"
                    ui-toggle-class="bounce" ui-target="#animate">{!!  __('backend.predefinedCssClasses') !!}</button>
          </span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="css_class"
                       class="col-sm-2 form-control-label"> {!!  __('backend.viewPermission') !!}</label>
                <div class="col-sm-10">
                        <?php
                        $view_permission_groups = [];
                        if (Session::get('WebmasterSectionField')->view_permission_groups != "") {
                            $view_permission_groups = explode(",",
                                Session::get('WebmasterSectionField')->view_permission_groups);
                        }
                        ?>
                    <select name="view_permission_groups[]"
                            class="form-control select2-multiple" multiple
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option
                            value="0" {!! (in_array(0,$view_permission_groups)?"selected":"") !!}>{!!  __('backend.all') !!}</option>
                        @foreach($PermissionsList as $PermissionItem)
                            <option
                                value="{{ $PermissionItem->id }}" {!! (in_array($PermissionItem->id,$view_permission_groups)?"selected":"") !!}>{!!  $PermissionItem->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="css_class"
                       class="col-sm-2 form-control-label"> {!!  __('backend.addPermission') !!}</label>
                <div class="col-sm-10">
                        <?php
                        $add_permission_groups = [];
                        if (Session::get('WebmasterSectionField')->add_permission_groups != "") {
                            $add_permission_groups = explode(",",
                                Session::get('WebmasterSectionField')->add_permission_groups);
                        }
                        ?>
                    <select name="add_permission_groups[]"
                            class="form-control select2-multiple" multiple
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option
                            value="0" {!! (in_array(0,$add_permission_groups)?"selected":"") !!}>{!!  __('backend.all') !!}</option>
                        @foreach($PermissionsList as $PermissionItem)
                            <option
                                value="{{ $PermissionItem->id }}" {!! (in_array($PermissionItem->id,$add_permission_groups)?"selected":"") !!}>{!!  $PermissionItem->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="css_class"
                       class="col-sm-2 form-control-label"> {!!  __('backend.editPermission') !!}</label>
                <div class="col-sm-10">
                        <?php
                        $edit_permission_groups = [];
                        if (Session::get('WebmasterSectionField')->edit_permission_groups != "") {
                            $edit_permission_groups = explode(",",
                                Session::get('WebmasterSectionField')->edit_permission_groups);
                        }
                        ?>
                    <select name="edit_permission_groups[]"
                            class="form-control select2-multiple" multiple
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option
                            value="0" {!! (in_array(0,$edit_permission_groups)?"selected":"") !!}>{!!  __('backend.all') !!}</option>
                        @foreach($PermissionsList as $PermissionItem)
                            <option
                                value="{{ $PermissionItem->id }}" {!! (in_array($PermissionItem->id,$edit_permission_groups)?"selected":"") !!}>{!!  $PermissionItem->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="status1"
                       class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="status" value="1" class="has-value" {{ (Session::get('WebmasterSectionField')->status==1)?"checked":"" }} id="status1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="status" value="0" class="has-value" {{ (Session::get('WebmasterSectionField')->status==0)?"checked":"" }} id="status2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row m-t-md">
                <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-lg btn-primary m-t"><i
                            class="material-icons">
                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                    <a href="{{ route('webmasterFields',[$WebmasterSections->id]) }}"
                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                </div>
            </div>

        </form>
    </div>
    @include('dashboard.modules.fields.css_classes')
    @push("after-scripts")
        @include('dashboard.layouts.editor')
    @endpush
@endif
