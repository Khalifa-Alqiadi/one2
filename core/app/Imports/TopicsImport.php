<?php

namespace App\Imports;

use App\Models\AttachFile;
use App\Models\Comment;
use App\Models\Map;
use App\Models\Photo;
use App\Models\RelatedTopic;
use App\Models\Section;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\TopicField;
use App\Models\WebmasterSection;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Helper;
use Auth;

class TopicsImport implements ToCollection
{
    protected $Data;
    protected $from_row = 0;
    protected $step_rows = 50;
    protected $rows_imported = 0;
    protected $exist_count = 0;
    protected $failed_count = 0;
    protected $failed_log = [];
    protected $finished = 0;
    protected $section_id = 0;

    public function __construct($request, $section_id)
    {
        $this->Data = $request;
        $this->from_row = ($request->from_row > 0) ? $request->from_row : 0;
        $this->section_id = $section_id;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        if ($this->section_id > 0) {
            $WebmasterSection = WebmasterSection::find($this->section_id);
            if (!empty($WebmasterSection)) {
                $request = @$this->Data;
                $i = 0;
                $rows_passed = 0;
                $total_rows_count = 0;

                $cf_details_var = "details_" . @Helper::currentLanguage()->code;
                $cf_details_var2 = "details_" . config('smartend.default_language');

                foreach ($rows as $row) {
                    if ($row->filter()->isNotEmpty()) {
                        $total_rows_count++;
                        if ($i > $this->from_row && $i <= ($this->from_row + $this->step_rows)) {
                            $rows_passed++;
                            try {
                                $next_nor_no = Topic::where('webmaster_id', $WebmasterSection->id)->max('row_no');
                                if ($next_nor_no < 1) {
                                    $next_nor_no = 1;
                                } else {
                                    $next_nor_no++;
                                }

                                // create new topic
                                $Topic = new Topic;
                                $Topic->row_no = $next_nor_no;
                                foreach (Helper::languagesList() as $ActiveLanguage) {
                                    if ($ActiveLanguage->box_status) {
                                        $field_value = @$row[@$this->Data->{"ex_col_title_" . $ActiveLanguage->code}];
                                        $field_value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $field_value);
                                        $Topic->{"title_" . $ActiveLanguage->code} = addslashes($field_value);

                                        $field_value = @$row[@$this->Data->{"ex_col_details_" . $ActiveLanguage->code}];
                                        $field_value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $field_value);
                                        $Topic->{"details_" . $ActiveLanguage->code} = addslashes($field_value);

                                        // meta info
                                        $Topic->{"seo_title_" . $ActiveLanguage->code} = $Topic->{"title_" . $ActiveLanguage->code};
                                        $Topic->{"seo_description_" . $ActiveLanguage->code} = mb_substr(strip_tags(stripslashes($Topic->{"details_" . $ActiveLanguage->code})), 0, 165, 'UTF-8');
                                        $Topic->{"seo_url_slug_" . $ActiveLanguage->code} = Helper::URLSlug($Topic->{"title_" . $ActiveLanguage->code}, "topic", 0);
                                    }
                                }

                                if ($WebmasterSection->date_status) {
                                    $field_value = @$row[@$this->Data->{"ex_col_date"}];
                                    $Topic->date = Helper::dateForDB($this->transformDate($field_value));
                                } else {
                                    $Topic->date = Helper::dateForDB(date("Y-m-d"));
                                }

                                if ($WebmasterSection->expire_date_status) {
                                    $expire_date = @$row[@$this->Data->{"ex_col_expire_date"}];
                                    if (@$expire_date != "") {
                                        $Topic->expire_date = Helper::dateForDB($this->transformDate($expire_date));
                                    }
                                }

                                if ($WebmasterSection->photo_status) {
                                    $photo_file = @$row[@$this->Data->{"ex_col_photo"}];
                                    if (@$photo_file != "") {
                                        $Topic->photo_file = $photo_file;
                                    }
                                }

                                $Topic->webmaster_id = $WebmasterSection->id;
                                $Topic->created_by = Auth::user()->id;
                                $Topic->visits = 0;
                                $Topic->section_id = 0;
                                if (@Auth::user()->permissionsGroup->active_status) {
                                    $Topic->status = 1;
                                } else {
                                    $Topic->status = 0;
                                }
                                $Topic->save();

                                $this->rows_imported = $this->rows_imported + 1;

                                if ($WebmasterSection->sections_status != 0) {
                                    $categories = @$request->{"ex_col_categories"};
                                    if ($categories != "" && $categories != 0) {
                                        // Save categories
                                        try {
                                            foreach ($categories as $category) {
                                                if ($category > 0) {
                                                    $TopicCategory = new TopicCategory;
                                                    $TopicCategory->topic_id = $Topic->id;
                                                    $TopicCategory->section_id = $category;
                                                    $TopicCategory->save();
                                                }
                                            }
                                        } catch (\Exception $e) {

                                        }
                                    }
                                }

                                // Save additional Fields
                                if (count($WebmasterSection->customFields) > 0) {
                                    foreach ($WebmasterSection->customFields as $customField) {
                                        // check permission
                                        $add_permission_groups = [];
                                        if ($customField->add_permission_groups != "") {
                                            $add_permission_groups = explode(",", $customField->add_permission_groups);
                                        }
                                        if (in_array(Auth::user()->permissions_id, $add_permission_groups) || in_array(0, $add_permission_groups) || $customField->add_permission_groups == "") {
                                            // have permission & continue

                                            $field_value = @$row[@$this->Data->{"ex_col_" . $customField->id}];

                                            if ($customField->type == 14) {
                                                $field_value = ($field_value == 1) ? 1 : 0;
                                            } elseif ($customField->type == 5) {
                                                if ($field_value != "") {
                                                    $field_value = Helper::dateForDB($this->transformDate($field_value), 1);
                                                }
                                            } elseif ($customField->type == 4) {
                                                if ($field_value != "") {
                                                    $field_value = Helper::dateForDB($field_value);
                                                }
                                            } elseif ($customField->type == 6 || $customField->type == 13) {
                                                // if select
                                                if ($customField->$cf_details_var != "") {
                                                    $cf_details = $customField->$cf_details_var;
                                                } else {
                                                    $cf_details = $customField->$cf_details_var2;
                                                }
                                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                $line_num = 1;
                                                $selected_no = 0;
                                                foreach ($cf_details_lines as $cf_details_line) {
                                                    if (trim($field_value) == trim($cf_details_line)) {
                                                        $field_value = $line_num;
                                                        $selected_no = $line_num;
                                                    }
                                                    $line_num++;
                                                }
                                                if ($selected_no == 0) {
                                                    foreach (Helper::languagesList() as $ActiveLanguage) {
                                                        $cf_details = $customField->{"details_" . @$ActiveLanguage->code};
                                                        $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                        $line_num = 1;
                                                        foreach ($cf_details_lines as $cf_details_line) {
                                                            if (trim($field_value) == trim($cf_details_line)) {
                                                                $field_value = $line_num;
                                                            }
                                                            $line_num++;
                                                        }
                                                    }
                                                }
                                            } elseif (in_array($customField->type,[7,17])) {
                                                // if multi check
                                                $field_values = [];
                                                // if multi check
                                                if ($customField->$cf_details_var != "") {
                                                    $cf_details = $customField->$cf_details_var;
                                                } else {
                                                    $cf_details = $customField->$cf_details_var2;
                                                }
                                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                $line_num = 1;
                                                $selected_no = 0;
                                                try {
                                                    $cf_saved_val_array = explode("|", $field_value);
                                                } catch (\Exception $e) {
                                                    $cf_saved_val_array = [];
                                                }

                                                foreach ($cf_details_lines as $cf_details_line) {
                                                    if ((in_array(trim($cf_details_line), $cf_saved_val_array))) {
                                                        $field_values[] = $line_num;
                                                        $selected_no = $line_num;
                                                    }
                                                    $line_num++;
                                                }

                                                if ($selected_no == 0) {
                                                    foreach (Helper::languagesList() as $ActiveLanguage) {
                                                        $cf_details = $customField->{"details_" . @$ActiveLanguage->code};
                                                        $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                        $line_num = 1;
                                                        try {
                                                            $cf_saved_val_array = explode("|", $field_value);
                                                        } catch (\Exception $e) {
                                                            $cf_saved_val_array = [];
                                                        }

                                                        foreach ($cf_details_lines as $cf_details_line) {
                                                            if ((in_array(trim($cf_details_line), $cf_saved_val_array))) {
                                                                $field_values[] = $line_num;
                                                            }
                                                            $line_num++;
                                                        }
                                                    }
                                                }

                                                if (count($field_values) > 0) {
                                                    $field_value = implode(",", $field_values);
                                                }
                                            }

                                            $field_value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $field_value);
                                            $field_value = addslashes($field_value);

                                            $TopicField = new TopicField;
                                            $TopicField->topic_id = $Topic->id;
                                            $TopicField->field_id = $customField->id;
                                            $TopicField->field_value = $field_value;
                                            $TopicField->save();

                                        }
                                    }
                                }


                            } catch (\Exception $e) {
                                $this->failed_count = $this->failed_count + 1;
                                // save log
                                $failed_log = $this->failed_log;
                                $failed_log[] = [
                                    "row_no" => (1 + $this->from_row + $rows_passed),
                                    "type" => "document",
                                    "class" => "danger",
                                    "code" => "",
                                    "title" => "",
                                    "error" => "",
                                ];
                                $this->failed_log = $failed_log;
                            }
                        }
                        $i++;
                    }
                }
                $this->from_row = $this->from_row + $rows_passed;
                if ((1 + $this->from_row) >= $total_rows_count) {
                    $this->finished = 1;
                }
            }
        }
    }


    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function getResult()
    {
        return [
            "success_count" => $this->rows_imported,
            "exist_count" => $this->exist_count,
            "failed_count" => $this->failed_count,
            "failed_log" => $this->failed_log,
            "from_row" => $this->from_row,
            "finished" => $this->finished
        ];
    }
}
