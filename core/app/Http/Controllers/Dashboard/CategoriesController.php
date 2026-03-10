<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\FileHelper;
use App\Helpers\GoogleIndexing;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\WebmasterSection;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;

class CategoriesController extends Controller
{
    private $uploadPath = "sections";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($webmasterId)
    {
        // Check Permissions
        $data_sections_arr = explode(",", Auth::user()->permissionsGroup->data_sections);
        if (!in_array($webmasterId, $data_sections_arr)) {
            return redirect()->route("NoPermission");
        }
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //Webmaster Section Details
        $WebmasterSection = WebmasterSection::find($webmasterId);

        if (@Auth::user()->permissionsGroup->view_status) {
            $Sections = Section::where('created_by', '=', Auth::user()->id)->where('webmaster_id', '=',
                $webmasterId)->where('father_id', '0');
        } else {
            $Sections = Section::where('webmaster_id', '=', $webmasterId)->where('father_id', '=', '0');
        }
        $search_word = \request()->input("q");
        if ($search_word != "") {
            $Sections = $Sections->where(function ($query) use ($search_word) {
                $query->where('title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%')
                    ->orwhere('seo_title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%');
            });
        }

        $Sections = $Sections->orderby('row_no', 'asc')->paginate(config('smartend.backend_pagination'));

        return view("dashboard.categories.list", compact("Sections", "GeneralWebmasterSections", "WebmasterSection"));
    }

    public function create($webmasterId)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //Webmaster Section Details
        $WebmasterSection = WebmasterSection::find($webmasterId);

        $fatherSections = Section::where('webmaster_id', '=', $webmasterId)->where('father_id', '=',
            '0')->orderby('row_no', 'asc')->get();

        return view("dashboard.categories.create",
            compact("GeneralWebmasterSections", "WebmasterSection", "fatherSections"));
    }

    public function store(Request $request, $webmasterId)
    {
        //
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            $next_nor_no = Section::where('webmaster_id', '=', $webmasterId)->where('father_id', '=',
                $request->father_id)->max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }

            // Start of Upload Files
            $fileFinalName = null;
            $formFileName = "photo";
            if ($request->hasFile($formFileName)) {
                // validate image
                $this->validate($request, [
                    $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                ]);
                $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 1);
                $fileFinalName = @$FileInfo['name'];
            }

            $Section = new Section;
            $Section->row_no = $next_nor_no;

            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $Section->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                    $Section->{"details_".$ActiveLanguage->code} = strip_tags($request->{"details_".$ActiveLanguage->code});

                    // meta info
                    $Section->{"seo_title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                    $Section->{"seo_url_slug_".$ActiveLanguage->code} = Helper::URLSlug(strip_tags($request->{"title_".$ActiveLanguage->code}),
                        "category", 0);

                }
            }
            $Section->icon = $request->icon;
            if ($fileFinalName != "") {
                $Section->photo = $fileFinalName;
            }
            $Section->webmaster_id = $webmasterId;
            $Section->father_id = $request->father_id;
            $Section->popup_id = $request->popup_id;
            $Section->visits = 0;
            $Section->status = 1;
            $Section->created_by = Auth::user()->id;

            $Section->save();


            // send to instant indexing
            if (Helper::GeneralWebmasterSettings("instant_index")) {
                if (Helper::GeneralWebmasterSettings("instant_index_on_create")) {
                    if ($WebmasterSection->index_status) {
                        $urls = [];
                        foreach (Helper::languagesList() as $ActiveLanguage) {
                            $urls[] = Helper::categoryURL($Section->id, $ActiveLanguage->code, $Section);
                        }
                        $indexer = new GoogleIndexing();
                        $indexer->addOrRemove($urls, 0);
                    }
                }
            }

            return redirect()->action([CategoriesController::class, 'index'],
                ["webmasterId" => $webmasterId])->with('doneMessage',
                __('backend.addDone'));
        }
        return redirect()->route('NotFound');
    }

    public function clone($webmasterId, $id)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->add_status) {
            return redirect()->route('NoPermission');
        }
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            if (@Auth::user()->permissionsGroup->view_status) {
                $Category = Section::where('created_by', '=', Auth::user()->id)->find($id);
            } else {
                $Category = Section::find($id);
            }
            if (!empty($Category)) {
                $NewCategory = $Category->replicate();

                $next_nor_no = Section::where('webmaster_id', '=', $webmasterId)->where('father_id', '=',
                    $Category->father_id)->max('row_no');
                if ($next_nor_no < 1) {
                    $next_nor_no = 1;
                } else {
                    $next_nor_no++;
                }

                $NewCategory->row_no = $next_nor_no;
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $NewCategory->{"title_".$ActiveLanguage->code} = $Category->{"title_".$ActiveLanguage->code}." - Copy";
                        // meta info
                        if ($Category->{"seo_title_".$ActiveLanguage->code} != "") {
                            $NewCategory->{"seo_title_".$ActiveLanguage->code} = $Category->{"seo_title_".$ActiveLanguage->code}." - Copy";
                        }
                        $NewCategory->{"seo_url_slug_".$ActiveLanguage->code} = Helper::URLSlug($Category->{"title_".$ActiveLanguage->code},
                            "topic", 0);
                    }
                }
                $NewCategory->visits = 0;
                $NewCategory->created_by = Auth::user()->id;
                $NewCategory->updated_by = null;
                $NewCategory->save();

                if (@Auth::user()->permissionsGroup->edit_status) {
                    return redirect()->action([CategoriesController::class, 'edit'],
                        ["webmasterId" => $webmasterId, "id" => $NewCategory->id])->with('doneMessage',
                        __('backend.addDone'));
                } else {
                    return redirect()->action([CategoriesController::class, 'index'],
                        ["webmasterId" => $webmasterId])->with('doneMessage',
                        __('backend.addDone'));
                }
            }
        }
    }

    public function edit($webmasterId, $id)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        if (@Auth::user()->permissionsGroup->view_status) {
            $Sections = Section::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $Sections = Section::find($id);
        }
        if (!empty($Sections)) {
            //Section Sections Details
            $WebmasterSection = WebmasterSection::find($Sections->webmaster_id);

            $fatherSections = Section::where('webmaster_id', '=', $webmasterId)->where('id', '!=',
                $id)->where('father_id', '0')->orderby('row_no', 'asc')->get();

            return view("dashboard.categories.edit",
                compact("Sections", "GeneralWebmasterSections", "WebmasterSection", "fatherSections"));
        } else {
            return redirect()->action([CategoriesController::class, 'index'], ["webmasterId" => $webmasterId]);
        }
    }

    public function update(Request $request, $webmasterId, $id)
    {
        //
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            $Section = Section::find($id);
            if (!empty($Section)) {

                // Start of Upload Files
                $fileFinalName = null;
                $formFileName = "photo";
                if ($request->hasFile($formFileName)) {
                    // validate image
                    $this->validate($request, [
                        $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                    ]);
                    $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 1);
                    $fileFinalName = @$FileInfo['name'];
                    if ($Section->photo != "" && $Section->photo != "noimg.png") {
                        FileHelper::deleteFile($this->uploadPath."/".$Section->photo);
                    }
                }

                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $Section->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                        $Section->{"details_".$ActiveLanguage->code} = strip_tags($request->{"details_".$ActiveLanguage->code});
                    }
                }
                $Section->icon = $request->icon;
                if ($request->photo_delete == 1) {
                    // Delete photo
                    if ($Section->photo != "" && $Section->photo != "noimg.png") {
                        FileHelper::deleteFile($this->uploadPath."/".$Section->photo);
                    }
                    $Section->photo = "";
                }

                if ($fileFinalName != "") {
                    $Section->photo = $fileFinalName;
                }
                $Section->father_id = $request->father_id;
                $Section->popup_id = $request->popup_id;
                $Section->status = $request->status;
                $Section->updated_by = Auth::user()->id;
                $Section->save();


                // send to instant indexing
                if (Helper::GeneralWebmasterSettings("instant_index")) {
                    if (Helper::GeneralWebmasterSettings("instant_index_on_update")) {
                        if ($WebmasterSection->index_status) {
                            $urls = [];
                            foreach (Helper::languagesList() as $ActiveLanguage) {
                                $urls[] = Helper::categoryURL($Section->id, $ActiveLanguage->code, $Section);
                            }
                            $indexer = new GoogleIndexing();
                            $indexer->addOrRemove($urls, 0);
                        }
                    }
                }

                return redirect()->action([CategoriesController::class, 'edit'],
                    ["webmasterId" => $webmasterId, "id" => $id])->with('doneMessage',
                    __('backend.saveDone'));
            }

            return redirect()->action([CategoriesController::class, 'index'], ["webmasterId" => $webmasterId]);
        }
        return redirect()->route('NotFound');
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        //
        $Section = Section::find($id);
        if (!empty($Section)) {
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $Section->{"seo_title_".$ActiveLanguage->code} = strip_tags($request->{"seo_title_".$ActiveLanguage->code});
                    $Section->{"seo_description_".$ActiveLanguage->code} = strip_tags($request->{"seo_description_".$ActiveLanguage->code});
                    $Section->{"seo_keywords_".$ActiveLanguage->code} = strip_tags($request->{"seo_keywords_".$ActiveLanguage->code});
                    $Section->{"seo_url_slug_".$ActiveLanguage->code} = Helper::URLSlug(strip_tags($request->{"seo_url_slug_".$ActiveLanguage->code}),
                        "category", $id);
                }
            }
            $Section->updated_by = Auth::user()->id;
            $Section->save();
            return redirect()->action([CategoriesController::class, 'edit'],
                ["webmasterId" => $webmasterId, "id" => $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'seo');
        } else {
            return redirect()->action([CategoriesController::class, 'index'], ["webmasterId" => $webmasterId]);
        }
    }

    public function custom_code(Request $request, $webmasterId, $id)
    {
        //
        $Section = Section::find($id);
        if (!empty($Section)) {
            $Section->css_code = $request->css_code;
            $Section->js_code = $request->js_code;
            $Section->body_code = $request->body_code;
            $Section->updated_by = Auth::user()->id;
            $Section->save();
            return redirect()->action([CategoriesController::class, 'edit'],
                ["webmasterId" => $webmasterId, "id" => $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'code');
        } else {
            return redirect()->action([CategoriesController::class, 'index'], ["webmasterId" => $webmasterId]);
        }
    }

    public function destroy($webmasterId, $id = 0)
    {
        //
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            if (@Auth::user()->permissionsGroup->view_status) {
                $Sections = Section::where('created_by', '=', Auth::user()->id)->find($id);
            } else {
                $Category = Section::find($id);
            }

            if (!empty($Category)) {
                // Delete a Section photo
                if ($Category->photo != "" && $Category->photo != "noimg.png") {
                    FileHelper::deleteFile($this->uploadPath."/".$Category->photo);
                }
                TopicCategory::where('section_id', $Category->id)->delete();
                Section::where('father_id', $Category->id)->delete();


                // send to instant indexing
                if (Helper::GeneralWebmasterSettings("instant_index")) {
                    if (Helper::GeneralWebmasterSettings("instant_index_on_delete")) {
                        if ($WebmasterSection->index_status) {
                            $urls = [];
                            foreach (Helper::languagesList() as $ActiveLanguage) {
                                $urls[] = Helper::categoryURL($Category->id, $ActiveLanguage->code, $Category);
                            }
                            $indexer = new GoogleIndexing();
                            $indexer->addOrRemove($urls, 1);
                        }
                    }
                }

                $Category->delete();
                return redirect()->action([CategoriesController::class, 'index'],
                    ["webmasterId" => $webmasterId])->with('doneMessage',
                    __('backend.deleteDone'));
            } else {
                return redirect()->action([CategoriesController::class, 'index'], ["webmasterId" => $webmasterId]);
            }
        }
        return redirect()->route('NotFound');
    }

    public function updateAll(Request $request, $webmasterId)
    {
        //
        if ($request->action == "order") {
            foreach ($request->row_ids as $rowId) {
                $Section = Section::find($rowId);
                if (!empty($Section)) {
                    $row_no_val = "row_no_".$rowId;
                    $Section->row_no = $request->$row_no_val;
                    $Section->save();
                }
            }

        } else {
            if ($request->ids != "") {
                if ($request->action == "activate") {
                    Section::wherein('id', $request->ids)
                        ->update(['status' => 1]);

                } elseif ($request->action == "block") {
                    Section::wherein('id', $request->ids)
                        ->update(['status' => 0]);
                } elseif ($request->action == "index_add" || $request->action == "index_remove") {
                    $Categories = Section::wherein('id', $request->ids)->get();
                    $urls = [];
                    foreach ($Categories as $Category) {
                        foreach (Helper::languagesList() as $ActiveLanguage) {
                            $urls[] = Helper::categoryURL($Category->id, $ActiveLanguage->code, $Category);
                        }
                    }
                    $indexer = new GoogleIndexing();
                    $response = $indexer->addOrRemove($urls, (($request->action == "index_remove") ? 1 : 0));
                    return redirect()->action([CategoriesController::class, 'index'],
                        ["webmasterId" => $webmasterId])->with(@$response["status"], @$response["message"]);

                } elseif ($request->action == "delete") {
                    // Delete Sections photo
                    $Categories = Section::wherein('id', $request->ids)->get();
                    foreach ($Categories as $Category) {
                        if ($Category->photo != "" && $Category->photo != "noimg.png") {
                            FileHelper::deleteFile($this->uploadPath."/".$Category->photo);
                        }
                    }
                    TopicCategory::wherein('section_id', $request->ids)->delete();
                    Section::wherein('father_id', $request->ids)->delete();
                    Section::wherein('id', $request->ids)
                        ->delete();

                }
            }
        }
        return redirect()->action([CategoriesController::class, 'index'],
            ["webmasterId" => $webmasterId])->with('doneMessage',
            __('backend.saveDone'));
    }


}
