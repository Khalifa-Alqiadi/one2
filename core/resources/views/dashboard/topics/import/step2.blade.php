
<link rel="stylesheet" href="{{ URL::asset('assets/frontend/css/select2.min.css') }}?v={{ Helper::system_version() }}"/>
<link rel="stylesheet" href="{{ URL::asset('assets/frontend/css/select2-bootstrap.min.css') }}?v={{ Helper::system_version() }}"/>
<script src="{{ URL::asset('assets/frontend/js/select2.min.js') }}?v={{ Helper::system_version() }}"></script>
<script>
    $(".select2").select2();
    $(".select2-multiple").select2({
        tags: true
    });
</script>
<style>
    .select2-container{
        width: 100% !important;
    }
</style>

<div class="row" style="margin: 0;">
    <div class="col-sm-4 text-center primary h6 text-white wizard-step m-b-0" style="padding: 0.7rem">
        <i class="material-icons">&#xe24d;</i> {{__("backend.uploadDataFile")}}
    </div>
    <div class="col-sm-4 text-center primary h6 text-white wizard-step" style="padding: 0.7rem">
        <i class="material-icons">&#xe8b5;</i> {{__("backend.chooseDataFields")}}
    </div>
    <div class="col-sm-4 text-center grey-300 dk h6 final_step" style="padding: 0.7rem">
        <span class="f_icon2"><i
                class="material-icons">&#xe8b5;</i></span> {{__("backend.importData")}}
    </div>
</div>
<div class="p-a-2">
    <table class="table table-bordered table-striped m-a-0">
        <thead>
        <tr>
            <th width="30%">
                {!!  __('backend.field') !!}
            </th>
            <th width="70%">
                {!!  __('backend.importFrom') !!}
            </th>
        </tr>
        </thead>
        <?php
        $cf_title_var = "title_" . @Helper::currentLanguage()->code;
        $cf_title_var2 = "title_" . config('smartend.default_language');
        function selectColumn($name, $ExcelColumns)
        {
            $select_excel_columns = "<select name=\"" . $name . "\" class=\"form-control c-select\">";
            $select_excel_columns .= "<option value=''>" . __('backend.select') . "</option>";
            $x = 'A';
            foreach ($ExcelColumns as $key => $ExcelColumn) {
                $select_excel_columns .= "<option value='" . $key . "'>[ " . $x . " ]  " . $ExcelColumn . "</option>";
                $x++;
            }
            $select_excel_columns .= "</select>";
            return $select_excel_columns;
        }
        ?>
        <tbody>
        @if ($WebmasterSection->sections_status != 0)
            <tr>
                <td class="dker text-muted opacity100">
                            <span>{!!  __('backend.categories') !!}</span> <span class="text-danger">*</span>
                <td>
                    <select name="ex_col_categories[]" id="ex_col_categories" class="form-control select2-multiple" multiple
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}" required>
                        <?php
                        $title_var = "title_" . @Helper::currentLanguage()->code;
                        $title_var2 = "title_" . config('smartend.default_language');
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
                </td>
            </tr>
        @endif
        @if($WebmasterSection->date_status)
            <tr>
                <td class="dker text-muted opacity100">
                            <span>{!!  __('backend.topicDate') !!}</span> <span class="text-danger">*</span>
                <td>
                    {!! selectColumn("ex_col_date",$ExcelColumns); !!}
                </td>
            </tr>
        @endif
        @if($WebmasterSection->expire_date_status)
            <tr>
                <td class="dker text-muted opacity100">
                            <span>{!!  __('backend.expireDate') !!}</span> <span class="text-danger">*</span>
                <td>
                    {!! selectColumn("ex_col_expire_date",$ExcelColumns); !!}
                </td>
            </tr>
        @endif
        @if($WebmasterSection->title_status)
            @foreach(Helper::languagesList() as $ActiveLanguage)
                @if($ActiveLanguage->box_status)
                    <tr>
                        <td class="dker text-muted opacity100">
                            <span>{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}</span> <span class="text-danger">*</span>
                        <td>
                            {!! selectColumn("ex_col_title_".@$ActiveLanguage->code,$ExcelColumns); !!}
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if($WebmasterSection->longtext_status)
            @foreach(Helper::languagesList() as $ActiveLanguage)
                @if($ActiveLanguage->box_status)
                    <tr>
                        <td class="dker text-muted opacity100">
                            <span>{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}</span> <span class="text-danger">*</span>
                        <td>
                            {!! selectColumn("ex_col_details_".@$ActiveLanguage->code,$ExcelColumns); !!}
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        @if($WebmasterSection->photo_status)
            <tr>
                <td class="dker text-muted opacity100">
                    <span>{!!  __('backend.topicPhoto') !!}</span>
                <td>
                    {!! selectColumn("ex_col_photo",$ExcelColumns); !!}
                </td>
            </tr>
        @endif
        @if(count($WebmasterSection->customFields) >0)
            @foreach($WebmasterSection->customFields as $customField)
                <?php
                $required = "";
                $required_star = "";
                if ($customField->required) {
                    $required = "required";
                    $required_star = "<span class='text-danger'>*</span>";
                }
                $col_name = 'col_' . $customField->id;
                if ($customField->$cf_title_var != "") {
                    $cf_title = $customField->$cf_title_var;
                } else {
                    $cf_title = $customField->$cf_title_var2;
                }
                ?>
                <tr>
                    <td class="dker text-muted opacity100">
                            <span>{{ $cf_title }}</span> {!! $required_star !!}
                    <td>
                        {!! selectColumn("ex_".$col_name,$ExcelColumns); !!}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<input type="hidden" name="step" value="2">
<input type="hidden" name="section_id" value="{{ encrypt($WebmasterSection->id) }}">
<input type="hidden" name="row_key" value="{{ @$row_key }}">
<input type="hidden" name="file_name" value="{{ @$ExcelFinalName }}">

