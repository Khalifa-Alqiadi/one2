<?php


namespace App\Http\Controllers\Dashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\TopicBlock;
use App\Models\Section;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\TopicTag;
use App\Models\WebmasterSection;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Redirect;
use Helper;

class TopicBlockController extends Controller
{
    private string $uploadPath = "topics";
    private string $allowed_file_types = "png,gif,jpg,jpeg,svg,webp,psd,pdf,doc,docx,txt,rtf,xls,xlsx,ppt,pptx,mp3,mp4,wav,zip,rar";


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function blocks_list(Request $request)
    {
        if ($request->topic_id != "") {
            $Topic = Topic::find(decrypt($request->topic_id));
            if (!empty($Topic)) {
                $WebmasterSection = $Topic->WebmasterSection;
                return view('dashboard.topics.landing.list', compact('Topic', 'WebmasterSection'));
            }
        }
        return "<div class='p-a-2 text-danger'>".__("backend.error")."</div>";
    }

    public function block_create(Request $request)
    {
        if ($request->topic_id != "" && $request->content_type != "") {
            $Topic = Topic::find(decrypt($request->topic_id));
            if (!empty($Topic)) {
                $content_type = $request->content_type;
                return view('dashboard.topics.landing.blocks.'.$content_type, compact('Topic', "content_type"));
            }
        }
        return "<div class='p-a-2 text-danger'>".__("backend.error")."</div>";
    }

    public function block_store(Request $request)
    {
        if ($request->topic_id != "" && $request->content_type != "") {
            $Topic = Topic::find(decrypt($request->topic_id));
            if (!empty($Topic)) {

                $type = ($request->content_type > 0) ? $request->content_type : 0;
                $content = [];

                // upload BG image
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    $fileFinalName = null;
                    $formFileName = "photo_".$ActiveLanguage->code;
                    // delete old
                    if ($request->hasFile($formFileName) != "" || ($request->{"photo_".$ActiveLanguage->code."_delete"} && $request->{"old_photo_".$ActiveLanguage->code} != "")) {
                        $$formFileName = null;
                        FileHelper::deleteFile($this->uploadPath."/".$request->{"old_photo_".$ActiveLanguage->code});
                    } else {
                        $$formFileName = $request->{"old_photo_".$ActiveLanguage->code};
                    }
                    // upload new
                    if ($request->hasFile($formFileName)) {
                        // validate image
                        $this->validate($request, [
                            $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                        ]);
                        $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 1);
                        $fileFinalName = @$FileInfo['name'];
                        $$formFileName = $fileFinalName;
                    }
                }


                if ($type == 4) {
                    // Form
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        if ($ActiveLanguage->box_status) {
                            $content["title_".$ActiveLanguage->code] = strip_tags($request->{"title_".$ActiveLanguage->code});
                            $content["desc_".$ActiveLanguage->code] = strip_tags($request->{"desc_".$ActiveLanguage->code});
                            $formFileName = "photo_".$ActiveLanguage->code;
                            $content["bg_".$ActiveLanguage->code] = @$$formFileName;
                        }
                    }
                    $content["module_id"] = $request->module_id;
                    $content["view_style"] = $request->view_style;
                } elseif ($type == 3) {
                    // dynamic
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        if ($ActiveLanguage->box_status) {
                            $content["title_".$ActiveLanguage->code] = strip_tags($request->{"title_".$ActiveLanguage->code});
                            $content["desc_".$ActiveLanguage->code] = strip_tags($request->{"desc_".$ActiveLanguage->code});
                            $formFileName = "photo_".$ActiveLanguage->code;
                            $content["bg_".$ActiveLanguage->code] = @$$formFileName;
                        }
                    }
                    $content["module_id"] = $request->module_id;
                    $content["category_ids"] = ($request->category_ids) ? @implode(",", $request->category_ids) : null;
                    $content["records_count"] = $request->records_count;
                    $content["records_order"] = $request->records_order;
                    $content["view_style"] = $request->view_style;
                } elseif ($type == 2) {
                    // banners
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        if ($ActiveLanguage->box_status) {
                            $content["title_".$ActiveLanguage->code] = strip_tags($request->{"title_".$ActiveLanguage->code});
                            $content["desc_".$ActiveLanguage->code] = strip_tags($request->{"desc_".$ActiveLanguage->code});
                            $formFileName = "photo_".$ActiveLanguage->code;
                            $content["bg_".$ActiveLanguage->code] = @$$formFileName;
                        }
                    }
                    $content["banner_area_id"] = $request->banner_area_id;
                    $content["banner_style"] = $request->banner_style;
                } elseif ($type == 1) {
                    // code
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        if ($ActiveLanguage->box_status) {
                            $content["title_".$ActiveLanguage->code] = strip_tags($request->{"title_".$ActiveLanguage->code});
                            $content["desc_".$ActiveLanguage->code] = strip_tags($request->{"desc_".$ActiveLanguage->code});
                            $content["code_".$ActiveLanguage->code] = $request->{"code_".$ActiveLanguage->code};
                            $formFileName = "photo_".$ActiveLanguage->code;
                            $content["bg_".$ActiveLanguage->code] = @$$formFileName;
                        }
                    }
                } else {
                    // static
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        if ($ActiveLanguage->box_status) {
                            $content["title_".$ActiveLanguage->code] = strip_tags($request->{"title_".$ActiveLanguage->code});
                            $content["desc_".$ActiveLanguage->code] = strip_tags($request->{"desc_".$ActiveLanguage->code});
                            $content["details_".$ActiveLanguage->code] = $request->{"details_".$ActiveLanguage->code};
                            $formFileName = "photo_".$ActiveLanguage->code;
                            $content["bg_".$ActiveLanguage->code] = @$$formFileName;
                        }
                    }
                }

                $TopicBlock = [];
                if ($request->block_id > 0) {
                    $TopicBlock = TopicBlock::find($request->block_id);
                }
                if (empty($TopicBlock)) {
                    $next_row_no = TopicBlock::where('topic_id', $Topic->id)->max('row_no');
                    if ($next_row_no < 1) {
                        $next_row_no = 1;
                    } else {
                        $next_row_no++;
                    }
                    $TopicBlock = new TopicBlock;
                    $TopicBlock->row_no = $next_row_no;
                    $TopicBlock->topic_id = $Topic->id;
                    $TopicBlock->type = $type;
                    $TopicBlock->created_by = Auth::user()->id;
                } else {
                    $TopicBlock->updated_by = Auth::user()->id;
                }
                $TopicBlock->title_status = ($request->title_status) ? 1 : 0;
                $TopicBlock->desc_status = ($request->desc_status) ? 1 : 0;
                $TopicBlock->image_status = ($request->image_status) ? 1 : 0;
                $TopicBlock->divider_status = ($request->divider_status) ? 1 : 0;
                $TopicBlock->more_btn_status = ($request->more_btn_status) ? 1 : 0;
                $TopicBlock->bg_color = $request->bg_color;
                $TopicBlock->css_classes = $request->css_classes;
                $TopicBlock->block_name = $request->block_name;

                $TopicBlock->status = ($request->status) ? 1 : 0;
                $TopicBlock->content = json_encode($content);
                $TopicBlock->save();

                return response()->json(array("stat" => "success", "msg" => __("backend.addDone")));
            }
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function block_edit(Request $request)
    {
        if ($request->topic_id != "" && $request->block_id != "") {
            $Topic = Topic::find(decrypt($request->topic_id));
            if (!empty($Topic)) {
                $TopicBlock = TopicBlock::find(decrypt($request->block_id));
                if (!empty($TopicBlock)) {
                    $content_type = "static";
                    if ($TopicBlock->type == 1) {
                        $content_type = "code";
                    }
                    if ($TopicBlock->type == 2) {
                        $content_type = "banners";
                    }
                    if ($TopicBlock->type == 3) {
                        $content_type = "dynamic";
                    }
                    if ($TopicBlock->type == 4) {
                        $content_type = "form";
                    }

                    // parse block content
                    $TopicBlockContents = [];
                    if ($TopicBlock->content != "") {
                        try {
                            $TopicBlockContents = json_decode($TopicBlock->content);
                        } catch (\Exception $e) {

                        }
                    }

                    return view('dashboard.topics.landing.blocks.'.$content_type,
                        compact('Topic', 'TopicBlock', "TopicBlockContents", "content_type"));
                }
            }
        }
        return "<div class='p-a-2 text-danger'>".__("backend.error")."</div>";
    }

    public function block_clone(Request $request)
    {
        if ($request->topic_id != "" && $request->block_id != "") {
            $Topic = Topic::find(decrypt($request->topic_id));
            if (!empty($Topic)) {
                $TopicBlock = TopicBlock::find(decrypt($request->block_id));
                if (!empty($TopicBlock)) {
                    $NewTopicBlock = $TopicBlock->replicate();
                    $NewTopicBlock->block_name = $NewTopicBlock->block_name." - Copy";
                    $NewTopicBlock->created_by = Auth::user()->id;
                    $NewTopicBlock->updated_by = null;
                    $NewTopicBlock->created_at = date('Y-m-d H:i:s');
                    $NewTopicBlock->updated_at = date('Y-m-d H:i:s');
                    $NewTopicBlock->save();
                    return response()->json(array("stat" => "success", "msg" => __("backend.addDone")));
                }
            }
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function block_delete(Request $request)
    {
        if ($request->topic_id != "" && $request->block_id != "") {
            $Topic = Topic::find(decrypt($request->topic_id));
            if (!empty($Topic)) {
                $TopicBlock = TopicBlock::find(decrypt($request->block_id));
                if (!empty($TopicBlock)) {
                    $TopicBlockContents = [];
                    if ($TopicBlock->content != "") {
                        try {
                            $TopicBlockContents = json_decode($TopicBlock->content);
                        } catch (\Exception $e) {

                        }
                    }
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        if ($ActiveLanguage->box_status) {
                            if (@$TopicBlockContents->{"bg_".@$ActiveLanguage->code} != "") {
                                FileHelper::deleteFile($this->uploadPath."/".@$TopicBlockContents->{"bg_".@$ActiveLanguage->code});
                            }
                        }
                    }
                    $TopicBlock->delete();
                    return response()->json(array("stat" => "success", "msg" => __("backend.deleteDone")));
                }
            }
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function updateAll(Request $request)
    {
        if ($request->topic_id != "") {
            $Topic = Topic::find(decrypt($request->topic_id));
            if (!empty($Topic)) {
                if ($request->action == "order") {
                    foreach ($request->row_ids as $rowId) {
                        $TopicBlock = TopicBlock::find($rowId);
                        if (!empty($TopicBlock)) {
                            $row_no_val = "row_no_".$rowId;
                            $TopicBlock->row_no = $request->$row_no_val;
                            $TopicBlock->save();
                        }
                    }
                    return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
                } else {
                    if ($request->ids != "") {
                        if ($request->action == "activate") {
                            TopicBlock::wherein('id', $request->ids)->update(['status' => 1]);

                        } elseif ($request->action == "block") {
                            TopicBlock::wherein('id', $request->ids)->update(['status' => 0]);
                        } elseif ($request->action == "delete") {
                            $TopicBlocks = TopicBlock::wherein('id', $request->ids)->get();
                            foreach ($TopicBlocks as $TopicBlock) {
                                $TopicBlockContents = [];
                                if ($TopicBlock->content != "") {
                                    try {
                                        $TopicBlockContents = json_decode($TopicBlock->content);
                                    } catch (\Exception $e) {

                                    }
                                }
                                foreach (Helper::languagesList() as $ActiveLanguage) {
                                    if ($ActiveLanguage->box_status) {
                                        if (@$TopicBlockContents->{"bg_".@$ActiveLanguage->code} != "") {
                                            FileHelper::deleteFile($this->uploadPath."/".@$TopicBlockContents->{"bg_".@$ActiveLanguage->code});
                                        }
                                    }
                                }
                            }
                            TopicBlock::wherein('id', $request->ids)->delete();
                        }
                        return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
                    }
                }
            }
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function module_cats($module_id = 0, $sel = "")
    {
        if ($module_id > 0) {
            $WebmasterSection = WebmasterSection::find($module_id);
            if (!empty($WebmasterSection)) {
                $title_var = "title_".@Helper::currentLanguage()->code;
                $t_arrow = "&raquo;";
                $selected = [];
                if ($sel != "") {
                    $selected = @explode(",", $sel);
                }
                $fatherSections = Section::where('webmaster_id', $WebmasterSection->id)->where('father_id',
                    0)->orderby('row_no', 'asc')->get();
                $HtmlSelect = '<select name="category_ids[]" id="category_ids" class="form-control form-select2-multiple" multiple>';
                foreach ($fatherSections as $fatherSection) {
                    $f1_title = $fatherSection->$title_var;
                    $HtmlSelect .= '<option value="'.$fatherSection->id.'" '.((@in_array(@$fatherSection->id,
                            $selected)) ? "selected" : "").'>'.$f1_title.'</option>';
                    foreach ($fatherSection->fatherSections as $subFatherSection) {
                        $f2_title = $subFatherSection->$title_var;
                        $HtmlSelect .= '<option value="'.$subFatherSection->id.'" '.((@in_array(@$subFatherSection->id,
                                $selected)) ? "selected" : "").'>'.$f1_title." ".$t_arrow." ".$f2_title.'</option>';
                        foreach ($subFatherSection->fatherSections as $sub2FatherSection) {
                            $HtmlSelect .= '<option value="'.$sub2FatherSection->id.'" '.((@in_array(@$sub2FatherSection->id,
                                    $selected)) ? "selected" : "").'>'.$f1_title." ".$t_arrow." ".$f2_title." ".$t_arrow." ".$sub2FatherSection->$title_var.'</option>';
                        }
                    }
                }
                $HtmlSelect .= '</select>';
                return $HtmlSelect;
            }
        }
        return "<div class='p-a-2 text-danger'>".__("backend.error")."</div>";
    }
}
