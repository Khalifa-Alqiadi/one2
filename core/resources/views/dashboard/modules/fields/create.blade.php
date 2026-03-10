@if (Session::get('fieldST') == "create")

    <div>
        <form method="POST" action="{{ route("webmasterFieldsStore",$WebmasterSections->id) }}" class="dashboard-form">
            @csrf
            @foreach(Helper::languagesList() as $ActiveLanguage)
                @if($ActiveLanguage->box_status)
                    <div class="form-group row">
                        <label
                            class="col-sm-2 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
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
                                <input type="radio" name="type" value="0" class="has-value" checked id="type0">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType0') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="1" class="has-value" id="type1">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType1') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="16" class="has-value" id="type16">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType16') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="2" class="has-value" id="type2">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType2') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="15" class="has-value" id="type15">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType15') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="3" class="has-value" id="type3">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType3') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="4" class="has-value" id="type4">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType4') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="5" class="has-value" id="type5">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType5') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="6" class="has-value" id="type6">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType6') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="13" class="has-value" id="type13">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType13') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="7" class="has-value" id="type7">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType7') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="14" class="has-value" id="type14">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType14') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="17" class="has-value" id="type17">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType17') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="8" class="has-value" id="type8">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType8') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="9" class="has-value" id="type9">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType9') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="10" class="has-value" id="type10">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType10') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="11" class="has-value" id="type11">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType11') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="12" class="has-value" id="type12">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType12') }}
                            </label>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label class="md-check">
                                <input type="radio" name="type" value="99" class="has-value" id="type99">
                                <i class="primary"></i>
                                {{ __('backend.customFieldsType99') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div id="fixed_text" style="display: none">
                        <div>
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="m-b-1">
                                        {!!  __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div>
                                            @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                                <div>
                                                    <textarea name="fixed_details_{{ @$ActiveLanguage->code }}" id="fixed_details_{{ @$ActiveLanguage->code }}" class="form-control ckeditor" dir="{{ @$ActiveLanguage->direction }}"></textarea>
                                                </div>
                                            @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                                <div>
                                                    <textarea name="fixed_details_{{ @$ActiveLanguage->code }}" id="fixed_details_{{ @$ActiveLanguage->code }}" class="form-control tinymce" dir="{{ @$ActiveLanguage->direction }}"></textarea>
                                                </div>
                                            @else
                                                <div class="box p-a-xs">
                                                    <textarea name="fixed_details_{{ @$ActiveLanguage->code }}" id="fixed_details_{{ @$ActiveLanguage->code }}" class="form-control summernote summernote_{{ @$ActiveLanguage->code }}" dir="{{ @$ActiveLanguage->direction }}"></textarea>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div id="options" style="display: none">
                        <div class="row">
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="col-sm-3 col-xs-5">
                                        <div class="m-b-sm">
                                            {!!  __('backend.customFieldsOptions') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                            :
                                            <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control" rows="12" style="white-space: nowrap;" dir="{{ @$ActiveLanguage->direction }}"></textarea>
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
                            <input type="radio" name="required" value="0" class="has-value" id="required2">
                            <i class="primary"></i>
                            {{ __('backend.customFieldsOptional') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="required" value="1" class="has-value" checked id="required1">
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
                            <input type="radio" name="in_table" value="1" class="has-value" id="in_table1">
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_table" value="0" class="has-value" id="in_table2" checked>
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
                            <input type="radio" name="in_search" value="1" class="has-value" id="in_search1" checked>
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_search" value="0" class="has-value" id="in_search2">
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
                                <input type="radio" name="in_listing" value="1" class="has-value" id="in_listing1">
                                <i class="primary"></i>
                                {{ __('backend.yes') }}
                            </label>
                            &nbsp; &nbsp;
                            <label class="md-check">
                                <input type="radio" name="in_listing" value="0" class="has-value" id="in_listing2" checked>
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
                            <input type="radio" name="in_page" value="1" class="has-value" id="in_page1" checked>
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_page" value="0" class="has-value" id="in_page2">
                            <i class="primary"></i>
                            {{ __('backend.no') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row in_statics_div displayNone">
                <label for="in_statics1"
                       class="col-sm-2 form-control-label">{!!  __('backend.showStatics') !!}</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="in_statics" value="1" class="has-value" id="in_statics1">
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="in_statics" value="0" class="has-value" id="in_statics2" checked>
                            <i class="primary"></i>
                            {{ __('backend.no') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row" id="default_val">
                <label for="default_value"
                       class="col-sm-2 form-control-label">{!!  __('backend.customFieldsDefault') !!}
                </label>
                <div class="col-sm-10">
                    <input type="text" name="default_value" id="default_value" class="form-control" value="">
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
                                ?>
                            @foreach ($Categories as $Category)
                                    <?php
                                    if ($Category->$title_var != "") {
                                        $ftitle = $Category->$title_var;
                                    } else {
                                        $ftitle = $Category->$title_var2;
                                    }
                                    ?>
                                <option value="{{ $Category->id  }}">{!! $ftitle !!}</option>
                                @foreach ($Category->fatherSections as $SubCategory)
                                        <?php
                                        if ($SubCategory->$title_var != "") {
                                            $title = $SubCategory->$title_var;
                                        } else {
                                            $title = $SubCategory->$title_var2;
                                        }
                                        ?>
                                    <option
                                        value="{{ $SubCategory->id  }}">{!! $ftitle !!} {!! $t_arrow !!} {!! $title !!}</option>
                                    @foreach ($SubCategory->fatherSections as $SubCategory2)
                                            <?php
                                            if ($SubCategory2->$title_var != "") {
                                                $title2 = $SubCategory2->$title_var;
                                            } else {
                                                $title2 = $SubCategory2->$title_var2;
                                            }
                                            ?>
                                        <option
                                            value="{{ $SubCategory2->id  }}"> {!! $ftitle !!} {!! $t_arrow !!} {!! $title !!} {!! $t_arrow !!} {!! $title2 !!}</option>
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
                        <option value="all">{{ __('backend.customFieldsForAllLang') }}</option>
                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            @if($ActiveLanguage->box_status)
                                <option
                                    value="{{ $ActiveLanguage->code }}">{{ $ActiveLanguage->title }}</option>
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
                        <input type="text" name="css_class" id="css_class" class="form-control" value="">
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

                    <select name="view_permission_groups[]"
                            class="form-control select2-multiple" multiple
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option value="0" selected="selected">{!!  __('backend.all') !!}</option>
                        @foreach($PermissionsList as $PermissionItem)
                            <option value="{{ $PermissionItem->id }}">{!!  $PermissionItem->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="css_class"
                       class="col-sm-2 form-control-label"> {!!  __('backend.addPermission') !!}</label>
                <div class="col-sm-10">

                    <select name="add_permission_groups[]"
                            class="form-control select2-multiple" multiple
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option value="0" selected="selected">{!!  __('backend.all') !!}</option>
                        @foreach($PermissionsList as $PermissionItem)
                            <option value="{{ $PermissionItem->id }}">{!!  $PermissionItem->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="css_class"
                       class="col-sm-2 form-control-label"> {!!  __('backend.editPermission') !!}</label>
                <div class="col-sm-10">

                    <select name="edit_permission_groups[]"
                            class="form-control select2-multiple" multiple
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option value="0" selected="selected">{!!  __('backend.all') !!}</option>
                        @foreach($PermissionsList as $PermissionItem)
                            <option value="{{ $PermissionItem->id }}">{!!  $PermissionItem->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row m-t-md">
                <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-lg btn-primary m-t"><i
                            class="material-icons">
                            &#xe31b;</i> {!! __('backend.add') !!}</button>
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
