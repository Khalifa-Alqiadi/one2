<?php
$cf_title_var = "title_" . @Helper::currentLanguage()->code;
$cf_title_var2 = "title_" . config('smartend.default_language');

$title_var = "title_" . @Helper::currentLanguage()->code;
$title_var2 = "title_" . config('smartend.default_language');
if ($WebmasterSection->$title_var != "") {
    $WebmasterSectionTitle = $WebmasterSection->$title_var;
} else {
    $WebmasterSectionTitle = $WebmasterSection->$title_var2;
}
?>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $WebmasterSectionTitle }} | {{ __('backend.print') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/print.css') }}" type="text/css"/>
</head>
<body class="{{ @Helper::currentLanguage()->direction }}">

@if(count($Topics) >0)
    <?php
    $Cols = Helper::get_webmaster_columns($WebmasterSection);
    $WebmasterCustomFields = \App\Models\WebmasterSectionField::where("webmaster_id", $WebmasterSection->id)->get();
    ?>
    <div class="container" id="container">
        <table class="responsive-table center-table" style="width: 100%">
            <thead class="dker">
            @foreach($Cols as $KEY=>$COL)
                <th>{{ @$COL['title'] }}</th>
            @endforeach
            </thead>
            <tbody>
            @foreach ($Topics as $Topic)
                <?php
                $ExtraFieldsData = [];
                foreach ($WebmasterCustomFields as $customField) {
                    // check permission
                    $view_permission_groups = [];
                    if ($customField->view_permission_groups != "") {
                        $view_permission_groups = explode(",", $customField->view_permission_groups);
                    }
                    if (in_array(Auth::user()->permissions_id, $view_permission_groups) || in_array(0, $view_permission_groups) || $customField->view_permission_groups == "") {
                        // have permission & continue

                        $cf_saved_val = "";
                        $cf_saved_val_array = array();
                        $TField = $Topic->fields->where("field_id", $customField->id)->first();
                        if (!empty($TField)) {
                            if (in_array($customField->type,[7,17])) {
                                // if multi check
                                $cf_saved_val_array = explode(", ", $TField->field_value);
                            } else {
                                $cf_saved_val = $TField->field_value;
                            }
                        } else {
                            $cf_saved_val = " ";
                        }

                        $cf_data = "";
                        if (($cf_saved_val != "" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == @Helper::currentLanguage()->code)) {
                            if ($customField->type == 12) {
                                $CF_Vimeo_id = Helper::Get_vimeo_video_id($cf_saved_val);
                                $cf_data = "<a target='_blank' href='https://player.vimeo.com/video/$CF_Vimeo_id?title=0&amp;byline=0'><i class='fa fa-play'></i></a>";

                            } elseif ($customField->type == 11) {
                                $CF_Youtube_id = Helper::Get_youtube_video_id($cf_saved_val);
                                $cf_data = "<a target='_blank' href='https://www.youtube.com/embed/$CF_Youtube_id'><i class='fa fa-play'></i></a>";

                            } elseif ($customField->type == 10) {
                                $cf_data = "<a target='_blank' href='" . route("fileView",["path" =>'topics/'.$cf_saved_val]) . "'><i class='fa fa-play'></i></a>";
                            } elseif ($customField->type == 9) {
                                $cf_data = "<a target='_blank' href='" . route("fileView",["path" =>'topics/'.$cf_saved_val]) . "'><i class='fa fa-play'></i></a>";
                            } elseif ($customField->type == 8) {
                                $cf_data = "<a target='_blank' href='" . route("fileView",["path" =>'topics/'.$cf_saved_val]) . "'><i class='fa fa-picture-o'></i></a>";
                            } elseif ($customField->type == 14) {
                                $cf_data = (($cf_saved_val == 1) ? ("&check; " . __('backend.yes')) : ("&#x2A09; " . __('backend.no')));
                            } elseif (in_array($customField->type,[7,17])) {
                                $cf_details_var = "details_" . @Helper::currentLanguage()->code;
                                $cf_details_var2 = "details_" . config('smartend.default_language');
                                if ($customField->$cf_details_var != "") {
                                    $cf_details = $customField->$cf_details_var;
                                } else {
                                    $cf_details = $customField->$cf_details_var2;
                                }
                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                $line_num = 1;
                                $x = 0;
                                foreach ($cf_details_lines as $cf_details_line) {
                                    if (in_array($line_num, $cf_saved_val_array)) {
                                        $cf_data .= (($x > 0) ? "," : "") . $cf_details_line;
                                        $x++;
                                    }
                                    $line_num++;
                                }
                            } elseif ($customField->type == 6 || $customField->type == 13) {
                                $cf_details_var = "details_" . @Helper::currentLanguage()->code;
                                $cf_details_var2 = "details_" . config('smartend.default_language');
                                if ($customField->$cf_details_var != "") {
                                    $cf_details = $customField->$cf_details_var;
                                } else {
                                    $cf_details = $customField->$cf_details_var2;
                                }
                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                $line_num = 1;
                                foreach ($cf_details_lines as $cf_details_line) {
                                    if ($line_num == $cf_saved_val) {
                                        $cf_data .= $cf_details_line;
                                    }
                                    $line_num++;
                                }
                            } elseif ($customField->type == 5) {
                                $cf_data = Helper::dateForDB($cf_saved_val, 1);
                            } elseif ($customField->type == 4) {
                                $cf_data = Helper::dateForDB($cf_saved_val);
                            } else {
                                $cf_data = $cf_saved_val;
                            }
                            $ExtraFieldsData["col_custom_" . $customField->id] = $cf_data;
                            $ExtraFieldsData["class_col_custom_" . $customField->id] = $customField->css_class;
                        }
                    }
                }
                ?>
                <tr>
                    @foreach($Cols as $KEY=>$COL)
                        <?php
                        $Cell_Class = "";
                        $Cell_Value = "";
                        if ($KEY == "col_id") {
                            $Cell_Value = $Topic->id;
                        } elseif ($KEY == "col_title") {

                            if ($Topic->$title_var != "") {
                                $title = $Topic->$title_var;
                            } else {
                                $title = $Topic->$title_var2;
                            }

                            // Get Categories list
                            $section = "";
                            if ($WebmasterSection->sections_status != 0) {
                                foreach ($Topic->categories as $category) {
                                    try {
                                        if (@$category->section->$title_var != "") {
                                            $cat_title = @$category->section->$title_var;
                                        } else {
                                            $cat_title = @$category->section->$title_var2;
                                        }
                                        if ($cat_title != "") {
                                            $section .= "<span class='label dker b-a text-sm'>" . $cat_title . "</span> ";
                                        }

                                    } catch (Exception $e) {

                                    }

                                }
                                if ($section == "") {
                                    $section = "<span style='color: orangered'><i>" . __('backend.topicDeletedSection') . "</i></span>";
                                }
                            }

                            //comments
                            $comments = "";
                            if (count($Topic->newComments) > 0) {
                                $comments = "<div><a href='" . route('topicsComments', [$WebmasterSection->id, $Topic->id]) . "'><span style='color:red'>" . __('backend.comments') . " <span class='label rounded label-sm danger'>" . count($Topic->newComments) . "</span></span></a></div>";
                            }


                            $photo = "";
                            if ($Topic->photo_file != "") {
                                $photo = " <div class=\"pull-right\"><img src=\"" . route("fileView",["path" =>'topics/'.$Topic->photo_file]) . "\" style=\"height: 40px\" alt=\"" . $title . "\"></div>";
                            }

                            $icon = "";
                            if ($Topic->icon != "") {
                                $icon = "<i class=\"fa " . $Topic->icon . "\"></i> ";
                            }

                            $Cell_Value = $photo . "<div class='h6'>" . $icon . $title . "</div>" . $section . $comments;
                        } elseif ($KEY == "col_date") {
                            $Cell_Value = Helper::formatDate($Topic->date);
                        } elseif ($KEY == "col_expire_date") {
                            $Cell_Value = Helper::formatDate($Topic->expire_date);
                        } elseif ($KEY == "col_visits") {
                            $Cell_Value = $Topic->visits;
                        } elseif ($KEY == "col_status") {
                            $Cell_Value = (($Topic->status == 1) ? "&#10003;" : "&#10005;");
                        } elseif ($KEY == "col_created_by") {
                            $Cell_Value = @$Topic->user->name;
                        } elseif ($KEY == "col_created_at") {
                            $Cell_Value = Helper::formatDate($Topic->created_at) . " " . date("h:i A", strtotime($Topic->created_at));
                        } elseif ($KEY == "col_updated_by") {
                            $Cell_Value = @$Topic->updated_user->name;
                        } elseif ($KEY == "col_updated_at") {
                            $Cell_Value = Helper::formatDate($Topic->updated_at) . " " . date("h:i A", strtotime($Topic->updated_at));
                        } else {
                            $Cell_Value = @$ExtraFieldsData[$KEY];
                            $Cell_Class = @$ExtraFieldsData["class_" . $KEY];
                        }
                        ?>
                        <td style="{{ ($KEY=="col_title")?"text-align:".@Helper::currentLanguage()->left:"" }} !important;"
                            class="{{ $Cell_Class }}">{!!$Cell_Value !!}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if(@$stat == "excel")
        <script src="{{ asset('assets/dashboard/js/xlsx.full.min.js') }}"></script>
        <script type="text/javascript">
            var elt = document.getElementById('container');
            var wb = XLSX.utils.table_to_book(elt, {sheet: "sheet1"});
            XLSX.writeFile(wb, '{{ $WebmasterSectionTitle }}.xlsx');
            window.onfocus = function () {
                setTimeout(function () {
                    window.close();
                }, 100);
            }
        </script>
    @elseif(@$stat == "pdf")
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.1/html2pdf.bundle.min.js"></script>
        <script type="text/javascript">
            var pdf_content = document.getElementById("container");
            var options = {
                margin: 1,
                filename: '{{ $WebmasterSectionTitle }}.pdf',
                image: {type: 'jpeg', quality: 0.99},
                html2canvas: {scale: 2},
                jsPDF: {unit: 'in', format: 'letter', orientation: 'landscape'}
            };
            html2pdf(pdf_content, options);
            window.onfocus = function () {
                setTimeout(function () {
                    window.close();
                }, 100);
            }
        </script>
    @else
        <script type="text/javascript">
            setTimeout(function () {
                window.print();
            }, 200);
            window.onfocus = function () {
                setTimeout(function () {
                    window.close();
                }, 100);
            }
        </script>
    @endif
@else
    <div style="border: 1px solid #ddd;padding: 10px 20px;text-align: center;">
        <h5>{{ __("backend.noData") }}</h5>
    </div>
@endif
</body>
</html>
