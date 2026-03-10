<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Tag;
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

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->tags_status || !Helper::GeneralWebmasterSettings("tags_status")) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        return view("dashboard.tags.list", compact("GeneralWebmasterSections"));
    }

    public function list(Request $request)
    {
        $limit = $request->input('length');
        $start = $request->input('start');
        $dir = $request->input('order.0.dir');

        $find_q = $request->input('find_q');

        if (@Auth::user()->permissionsGroup->view_status) {
            $Tags = Tag::where('created_by', '=', Auth::user()->id);
        } else {
            $Tags = Tag::where('id', '>', 0);
        }
        if ($find_q != "") {
            $Tags = $Tags->where(function ($query) use ($find_q) {
                $query->where('title', 'like', '%'.$find_q.'%')
                    ->orwhere('details', 'like', '%'.$find_q.'%');
            });
        }
        $columns = [];
        if (@Auth::user()->permissionsGroup->edit_status) {
            $columns[] = "id";
        }
        $columns[] = "title";
        $columns[] = "contents";
        $columns[] = "visits";
        $columns[] = "status";
        $columns[] = "created_at";
        $order = @$columns[$request->input('order.0.column')];

        $totalData = $Tags->count();
        $totalFiltered = $totalData;
        //order, paginate
        if ($limit > 0) {
            $Tags = $Tags->offset($start)->limit($limit);
        }
        $Tags = $Tags->orderBy($order, $dir)->orderBy("id", "desc")->get();

        $data = array();
        if ($totalFiltered > 0) {
            foreach ($Tags as $Tag) {

                $nestedData['check'] = "<div class='row_checker'><label class=\"ui-check m-a-0\">
                                                <input type=\"checkbox\" name=\"ids[]\" value=\"".$Tag->id."\"><i
                                                        class=\"dark-white\"></i>
                                                        <input type='hidden' name='row_ids[]' value='".$Tag->id."' class='form-control row_no'>
                                            </label>
                                        </div>";
                if (@Auth::user()->permissionsGroup->edit_status) {
                    $nestedData['title'] = '<a href="#" onclick="UpdateTag(\''.$Tag->id.'\');return false;">'."<div class='h6 m-b-0'>".$Tag->title."</div>".'</a>';
                } else {
                    $nestedData['title'] = "<div class='h6 m-b-0'>".$Tag->title."</div>";
                }
                $contents = TopicTag::where("tag_id", $Tag->id)->count();
                $nestedData['contents'] = "<div class='text-center'><a href='".route("adminSearch")."?q=%23".$Tag->title."'><strong>".$contents."</strong></a></div>";
                $nestedData['visits'] = "<div class='text-center'>".$Tag->visits."</div>";
                $nestedData['status'] = "<div class='text-center'> <i class=\"fa ".(($Tag->status == 1) ? "fa-check text-success" : "fa-times text-danger")." inline\"></i></div>";

                $nestedData['created_at'] = "<div class='text-center'>".Helper::formatDate($Tag->created_at)." ".date('h:i A',
                        strtotime($Tag->created_at))."</div>";
                $options = '
                      <div class="dropdown">
        <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i> '.__('backend.options').'</button>
        <div class="dropdown-menu pull-right">
          <a class="dropdown-item" href="'.route("tag",
                        $Tag->seo_url).'" target="_blank"><i class="material-icons">&#xe8f4;</i> '.__('backend.preview').'</a>';
                if (@Auth::user()->permissionsGroup->edit_status) {
                    $options .= '<a class="dropdown-item" onclick="UpdateTag(\''.$Tag->id.'\')"><i class="material-icons">&#xe3c9;</i> '.__('backend.edit').'</a>';
                }
                if (@Auth::user()->permissionsGroup->delete_status) {
                    $options .= '<a class="dropdown-item text-danger" onclick="DeleteTag(\''.$Tag->id.'\')"><i class="material-icons">&#xe872;</i> '.__('backend.delete').'</a>';
                }
                $options .= '</div></div>';

                $nestedData['options'] = "<div class='text-center'>".$options."</div>";

                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }

    public function store(Request $request)
    {
        $fields_to_validate = [
            "title" => "required",
            "seo_url" => "required",
        ];
        $validator = Validator::make($request->all(), $fields_to_validate);
        if ($validator->passes()) {
            $ExistTag = Tag::where("title", $request->title)->count();
            if ($ExistTag > 0) {
                return response()->json(array("stat" => "error", "msg" => __("backend.recordExist")));
            }
            $Tag = new Tag;
            $Tag->title = strip_tags($request->title);
            $Tag->seo_url = Helper::URLSlug(strip_tags($request->seo_url));
            $Tag->details = strip_tags($request->details);
            $Tag->status = 1;
            $Tag->visits = 0;
            $Tag->created_by = Auth::user()->id;
            $Tag->save();

            return response()->json(array("stat" => "success", "msg" => __("backend.addDone")));
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function edit($id = 0)
    {
        if ($id > 0) {
            // Check Permissions
            if (@Auth::user()->permissionsGroup->edit_status) {
                if (@Auth::user()->permissionsGroup->view_status) {
                    $Tag = Tag::where('created_by', '=', Auth::user()->id)->find($id);
                } else {
                    $Tag = Tag::find($id);
                }
                if (!empty($Tag)) {
                    return view("dashboard.tags.edit", compact("Tag"));
                }
            }
        }
        return "<div class='p-a-2 text-danger'>".__("backend.error")."</div>";

    }

    public function update(Request $request)
    {
        $fields_to_validate = [
            "tag_id" => "required",
            "title" => "required",
            "seo_url" => "required",
        ];
        $validator = Validator::make($request->all(), $fields_to_validate);
        if ($validator->passes()) {
            $ExistTag = Tag::where("title", $request->title)->where("id", "!=", $request->tag_id)->count();
            if ($ExistTag > 0) {
                return response()->json(array("stat" => "error", "msg" => __("backend.recordExist")));
            }
            if (@Auth::user()->permissionsGroup->view_status) {
                $Tag = Tag::where('created_by', '=', Auth::user()->id)->find($request->tag_id);
            } else {
                $Tag = Tag::find($request->tag_id);
            }
            if (!empty($Tag)) {
                $Tag->title = strip_tags($request->title);
                $Tag->seo_url = Helper::URLSlug(strip_tags($request->seo_url));
                $Tag->details = strip_tags($request->details);
                $Tag->status = 1;
                $Tag->updated_by = Auth::user()->id;
                $Tag->save();
                return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
            }
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function destroy($id = 0)
    {
        if ($id > 0) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return response()->json(array("stat" => "error", "msg" => __("backend.error")));
            }
            //
            if (@Auth::user()->permissionsGroup->view_status) {
                $Tag = Tag::where('created_by', '=', Auth::user()->id)->find($id);
            } else {
                $Tag = Tag::find($id);
            }
            if (!empty($Tag)) {
                //delete connected tags
                TopicTag::where('tag_id', $Tag->id)->delete();

                //Remove Topic
                $Tag->delete();
                return response()->json(array("stat" => "success", "msg" => __("backend.deleteDone")));
            }
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function updateAll(Request $request)
    {
        if ($request->action == "activate") {
            if ($request->ids != "") {
                Tag::wherein('id', $request->ids)->update(['status' => 1]);
                return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
            }
        } elseif ($request->action == "block") {
            if ($request->ids != "") {
                Tag::wherein('id', $request->ids)->update(['status' => 0]);
                return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
            }
        } elseif ($request->action == "delete") {
            if ($request->ids != "") {
                // Check Permissions
                if (!@Auth::user()->permissionsGroup->delete_status) {
                    return response()->json(array("stat" => "error", "msg" => __("backend.error")));
                }
                TopicTag::wherein('tag_id', $request->ids)->delete();
                Tag::wherein('id', $request->ids)->delete();
                return response()->json(array("stat" => "success", "msg" => __("backend.deleteDone")));
            }
        }

        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }
}
