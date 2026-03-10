<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\FileHelper;
use App\Helpers\GoogleIndexing;
use App\Http\Controllers\Controller;
use App\Models\AttachFile;
use App\Models\Comment;
use App\Models\Map;
use App\Models\Permissions;
use App\Models\Photo;
use App\Models\RelatedTopic;
use App\Models\Section;
use App\Models\TopicBlock;
use App\Models\TopicCategory;
use App\Models\TopicField;
use App\Models\User;
use App\Models\WebmasterSection;
use App\Models\WebmasterSectionField;
use App\Models\Menu;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Redirect;

class WebmasterSectionsController extends Controller
{
    private $uploadPath = "topics";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        // Check Permissions
        if (!@Auth::user()->permissionsGroup->modules_status) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END
        if (@Auth::user()->permissionsGroup->view_status) {
            $WebmasterSections = WebmasterSection::where('created_by', '=', Auth::user()->id)->orderby('row_no',
                'asc')->paginate(config('smartend.backend_pagination'));
        } else {
            $WebmasterSections = WebmasterSection::orderby('row_no',
                'asc')->paginate(config('smartend.backend_pagination'));
        }
        return view("dashboard.modules.list", compact("WebmasterSections", "GeneralWebmasterSections"));
    }

    public function create()
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END
        return view("dashboard.modules.create", compact("GeneralWebmasterSections"));
    }

    public function store(Request $request)
    {
        $next_nor_no = WebmasterSection::max('row_no');
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
        // End of Upload Files

        $WebmasterSection = new WebmasterSection;
        $WebmasterSection->row_no = $next_nor_no;
        foreach (Helper::languagesList() as $ActiveLanguage) {
            $WebmasterSection->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
            $WebmasterSection->{"seo_url_slug_".$ActiveLanguage->code} = Helper::URLSlug(strip_tags($request->{"title_".$ActiveLanguage->code}),
                "section", 0);
        }
        $WebmasterSection->type = $request->type;
        $WebmasterSection->sections_status = $request->sections_status;
        if ($request->type == 10) {
            $WebmasterSection->title_status = 1;
            $WebmasterSection->case_status = 1;
            $WebmasterSection->seo_status = 1;
            $WebmasterSection->no_status = 1;
            $WebmasterSection->photo_status = 1;
            $WebmasterSection->visits_status = 1;
            $WebmasterSection->featured_status = 1;
            $WebmasterSection->index_status = 1;
            $WebmasterSection->tags_status = 1;
            $WebmasterSection->code_status = 1;

            $WebmasterSection->comments_status = 0;
            $WebmasterSection->date_status = 0;
            $WebmasterSection->expire_date_status = 0;
            $WebmasterSection->longtext_status = 0;
            $WebmasterSection->editor_status = 0;
            $WebmasterSection->attach_file_status = 0;
            $WebmasterSection->extra_attach_file_status = 0;
            $WebmasterSection->multi_images_status = 0;
            $WebmasterSection->maps_status = 0;
            $WebmasterSection->order_status = 0;
            $WebmasterSection->section_icon_status = 0;
            $WebmasterSection->icon_status = 0;
            $WebmasterSection->related_status = 0;
        } else {
            $WebmasterSection->comments_status = $request->comments_status;
            $WebmasterSection->title_status = $request->title_status;
            $WebmasterSection->photo_status = $request->photo_status;
            $WebmasterSection->case_status = $request->case_status;
            $WebmasterSection->featured_status = $request->featured_status;
            $WebmasterSection->index_status = $request->index_status;
            $WebmasterSection->tags_status = $request->tags_status;
            $WebmasterSection->visits_status = $request->visits_status;
            $WebmasterSection->date_status = $request->date_status;
            $WebmasterSection->expire_date_status = $request->expire_date_status;
            $WebmasterSection->longtext_status = $request->longtext_status;
            $WebmasterSection->editor_status = $request->editor_status;
            $WebmasterSection->attach_file_status = $request->attach_file_status;
            $WebmasterSection->extra_attach_file_status = $request->extra_attach_file_status;
            $WebmasterSection->multi_images_status = $request->multi_images_status;
            $WebmasterSection->maps_status = $request->maps_status;
            $WebmasterSection->order_status = $request->order_status;
            $WebmasterSection->section_icon_status = $request->section_icon_status;
            $WebmasterSection->icon_status = $request->icon_status;
            $WebmasterSection->related_status = $request->related_status;
            $WebmasterSection->seo_status = $request->seo_status;
            $WebmasterSection->code_status = $request->code_status;
            $WebmasterSection->no_status = $request->no_status;
        }
        $WebmasterSection->popup_id = $request->popup_id;

        $WebmasterSection->status = 1;
        if ($fileFinalName != "") {
            $WebmasterSection->photo = $fileFinalName;
        }
        $WebmasterSection->created_by = Auth::user()->id;
        $WebmasterSection->save();

        $Permissions = Permissions::find(Auth::user()->permissionsGroup->id);
        if (!empty($Permissions)) {
            $Permissions->data_sections = $Permissions->data_sections.",".$WebmasterSection->id;
            $Permissions->save();
        }
        if (Auth::user()->permissionsGroup->id != 1) {
            $Permissions = Permissions::find(1);
            if (!empty($Permissions)) {
                $Permissions->data_sections = $Permissions->data_sections.",".$WebmasterSection->id;
                $Permissions->save();
            }
        }


        // send to instant indexing
        if (Helper::GeneralWebmasterSettings("instant_index")) {
            if (Helper::GeneralWebmasterSettings("instant_index_on_create")) {
                if ($WebmasterSection->index_status) {
                    $urls = [];
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        $urls[] = Helper::sectionURL($WebmasterSection->id, $ActiveLanguage->code, $WebmasterSection);
                    }
                    $indexer = new GoogleIndexing();
                    $indexer->addOrRemove($urls, 0);
                }
            }
        }

        Cache::forget('_Loader_WebmasterSections');
        Cache::forget('_Loader_Landing_Modules');

        return redirect()->action([WebmasterSectionsController::class, 'edit'],
            ['id' => $WebmasterSection->id])->with('doneMessage', __('backend.addDone'));
    }

    public function edit($id)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        if (@Auth::user()->permissionsGroup->view_status) {
            $WebmasterSections = WebmasterSection::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $WebmasterSections = WebmasterSection::find($id);
        }
        if (!empty($WebmasterSections)) {
            $PermissionsList = Permissions::orderby('id', 'asc')->get();

            $Categories = Section::where('webmaster_id', '=', $WebmasterSections->id)->where('father_id', '=',
                '0')->orderby('row_no', 'asc')->get();

            return view("dashboard.modules.edit",
                compact("WebmasterSections", "GeneralWebmasterSections", "PermissionsList", "Categories"));
        } else {
            return redirect()->action([WebmasterSectionsController::class, 'index']);
        }
    }

    public function update(Request $request, $id)
    {
        $WebmasterSection = WebmasterSection::find($id);
        if (!empty($WebmasterSection)) {
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
                // Delete photo
                if ($WebmasterSection->photo != "" && $WebmasterSection->photo != "nav-bg.png") {
                    FileHelper::deleteFile($this->uploadPath."/".$WebmasterSection->photo);
                }
            }

            // End of Upload Files
            if ($request->photo_delete == 1) {
                // Delete photo
                if ($WebmasterSection->photo != "" && $WebmasterSection->photo != "nav-bg.png") {
                    FileHelper::deleteFile($this->uploadPath."/".$WebmasterSection->photo);
                }
                $WebmasterSection->photo = "";
            }
            foreach (Helper::languagesList() as $ActiveLanguage) {
                $WebmasterSection->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
            }
            $WebmasterSection->type = $request->type;
            $WebmasterSection->sections_status = $request->sections_status;
            if ($request->type == 10) {
                $WebmasterSection->title_status = 1;
                $WebmasterSection->case_status = 1;
                $WebmasterSection->seo_status = 1;
                $WebmasterSection->no_status = 1;
                $WebmasterSection->photo_status = 1;
                $WebmasterSection->visits_status = 1;
                $WebmasterSection->featured_status = 1;
                $WebmasterSection->index_status = 1;
                $WebmasterSection->tags_status = 1;
                $WebmasterSection->code_status = 1;

                $WebmasterSection->comments_status = 0;
                $WebmasterSection->date_status = 0;
                $WebmasterSection->expire_date_status = 0;
                $WebmasterSection->longtext_status = 0;
                $WebmasterSection->editor_status = 0;
                $WebmasterSection->attach_file_status = 0;
                $WebmasterSection->extra_attach_file_status = 0;
                $WebmasterSection->multi_images_status = 0;
                $WebmasterSection->maps_status = 0;
                $WebmasterSection->order_status = 0;
                $WebmasterSection->section_icon_status = 0;
                $WebmasterSection->icon_status = 0;
                $WebmasterSection->related_status = 0;
            } else {
                $WebmasterSection->comments_status = $request->comments_status;
                $WebmasterSection->title_status = $request->title_status;
                $WebmasterSection->photo_status = $request->photo_status;
                $WebmasterSection->case_status = $request->case_status;
                $WebmasterSection->featured_status = $request->featured_status;
                $WebmasterSection->index_status = $request->index_status;
                $WebmasterSection->tags_status = $request->tags_status;
                $WebmasterSection->visits_status = $request->visits_status;
                $WebmasterSection->date_status = $request->date_status;
                $WebmasterSection->expire_date_status = $request->expire_date_status;
                $WebmasterSection->longtext_status = $request->longtext_status;
                $WebmasterSection->editor_status = $request->editor_status;
                $WebmasterSection->attach_file_status = $request->attach_file_status;
                $WebmasterSection->extra_attach_file_status = $request->extra_attach_file_status;
                $WebmasterSection->multi_images_status = $request->multi_images_status;
                $WebmasterSection->maps_status = $request->maps_status;
                $WebmasterSection->order_status = $request->order_status;
                $WebmasterSection->section_icon_status = $request->section_icon_status;
                $WebmasterSection->icon_status = $request->icon_status;
                $WebmasterSection->related_status = $request->related_status;
                $WebmasterSection->seo_status = $request->seo_status;
                $WebmasterSection->code_status = $request->code_status;
                $WebmasterSection->no_status = $request->no_status;
            }
            $WebmasterSection->popup_id = $request->popup_id;
            $WebmasterSection->status = $request->status;
            if ($fileFinalName != "") {
                $WebmasterSection->photo = $fileFinalName;
            }
            $WebmasterSection->updated_by = Auth::user()->id;
            $WebmasterSection->save();


            // send to instant indexing
            if (Helper::GeneralWebmasterSettings("instant_index")) {
                if (Helper::GeneralWebmasterSettings("instant_index_on_update")) {
                    if ($WebmasterSection->index_status) {
                        $urls = [];
                        foreach (Helper::languagesList() as $ActiveLanguage) {
                            $urls[] = Helper::sectionURL($WebmasterSection->id, $ActiveLanguage->code,
                                $WebmasterSection);
                        }
                        $indexer = new GoogleIndexing();
                        $indexer->addOrRemove($urls, 0);
                    }
                }
            }

            Cache::forget('_Loader_WebmasterSections');
            Cache::forget('_Loader_Landing_Modules');
            return redirect()->action([WebmasterSectionsController::class, 'edit'], ['id' => $id])->with('doneMessage',
                __('backend.saveDone'));

        } else {
            return redirect()->action([WebmasterSectionsController::class, 'index']);
        }
    }

    public function seo(Request $request, $id)
    {
        //
        $WebmasterSection = WebmasterSection::find($id);
        if (!empty($WebmasterSection)) {
            foreach (Helper::languagesList() as $ActiveLanguage) {
                $WebmasterSection->{"seo_title_".$ActiveLanguage->code} = strip_tags($request->{"seo_title_".$ActiveLanguage->code});
                $WebmasterSection->{"seo_description_".$ActiveLanguage->code} = strip_tags($request->{"seo_description_".$ActiveLanguage->code});
                $WebmasterSection->{"seo_keywords_".$ActiveLanguage->code} = strip_tags($request->{"seo_keywords_".$ActiveLanguage->code});
                $WebmasterSection->{"seo_url_slug_".$ActiveLanguage->code} = Helper::URLSlug(strip_tags($request->{"seo_url_slug_".$ActiveLanguage->code}),
                    "section", $id);
            }
            $WebmasterSection->updated_by = Auth::user()->id;
            $WebmasterSection->save();

            Cache::forget('_Loader_WebmasterSections');
            Cache::forget('_Loader_Landing_Modules');
            return redirect()->action([WebmasterSectionsController::class, 'edit'], ['id' => $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'seo');
        } else {
            return redirect()->action([WebmasterSectionsController::class, 'index']);
        }
    }

    public function custom_code(Request $request, $id)
    {
        //
        $WebmasterSection = WebmasterSection::find($id);
        if (!empty($WebmasterSection)) {
            $WebmasterSection->css_code = $request->css_code;
            $WebmasterSection->js_code = $request->js_code;
            $WebmasterSection->body_code = $request->body_code;
            $WebmasterSection->updated_by = Auth::user()->id;
            $WebmasterSection->save();

            Cache::forget('_Loader_WebmasterSections');
            Cache::forget('_Loader_Landing_Modules');
            return redirect()->action([WebmasterSectionsController::class, 'edit'], ['id' => $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'code');
        } else {
            return redirect()->action([WebmasterSectionsController::class, 'index']);
        }
    }

    public function destroy($id = 0)
    {
        //
        if (@Auth::user()->permissionsGroup->view_status) {
            $WebmasterSection = WebmasterSection::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $WebmasterSection = WebmasterSection::find($id);
        }
        if (!empty($WebmasterSection) && $id != 1) {

            if (count($WebmasterSection->topics) > 0) {
                foreach ($WebmasterSection->topics as $Topic) {
                    //delete topics
                    // Delete a Topic photo
                    if ($Topic->photo_file != "" && $Topic->photo_file != "default.png") {
                        FileHelper::deleteFile($this->uploadPath."/".$Topic->photo_file);
                    }
                    if ($Topic->attach_file != "") {
                        FileHelper::deleteFile($this->uploadPath."/".$Topic->attach_file);
                    }
                    if ($Topic->audio_file != "") {
                        FileHelper::deleteFile($this->uploadPath."/".$Topic->audio_file);
                    }
                    if ($Topic->video_type == 0 && $Topic->video_file != "") {
                        FileHelper::deleteFile($this->uploadPath."/".$Topic->video_file);
                    }
                    //delete additional fields
                    TopicField::where('topic_id', $Topic->id)->delete();
                    //delete Related Topics
                    RelatedTopic::where('topic_id', $Topic->id)->delete();
                    // Remove categories
                    TopicCategory::where('topic_id', $Topic->id)->delete();
                    // Remove comments
                    Comment::where('topic_id', $Topic->id)->delete();
                    // Remove maps
                    Map::where('topic_id', $Topic->id)->delete();
                    //remove blocks
                    $TopicBlocks = TopicBlock::where('topic_id', $Topic->id)->get();
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
                    TopicBlock::where('topic_id', $Topic->id)->delete();
                    // Remove Photos
                    $PhotoFiles = Photo::where('topic_id', $Topic->id)->get();
                    if (count($PhotoFiles) > 0) {
                        foreach ($PhotoFiles as $PhotoFile) {
                            if ($PhotoFile->file != "") {
                                FileHelper::deleteFile($this->uploadPath."/".$PhotoFile->file);
                            }
                        }
                    }
                    Photo::where('topic_id', $Topic->id)->delete();
                    // Remove Attach Files
                    $AttachFiles = AttachFile::where('topic_id', $Topic->id)->get();
                    if (count($AttachFiles) > 0) {
                        foreach ($AttachFiles as $AttachFile) {
                            if ($AttachFile->file != "") {
                                FileHelper::deleteFile($this->uploadPath."/".$AttachFile->file);
                            }
                        }
                    }
                    AttachFile::where('topic_id', $Topic->id)->delete();

                    //Remove Topic
                    $Topic->delete();
                }
            }
            //delete categories
            if (count($WebmasterSection->sections) > 0) {
                foreach ($WebmasterSection->sections as $Section) {
                    $Section->delete();
                }
            }
            //delete menus
            if (count($WebmasterSection->menus) > 0) {
                foreach ($WebmasterSection->menus as $Menu) {
                    $Menu->delete();
                }
            }
            Menu::where('cat_id', $id)->delete();
            //delete additional fields
            WebmasterSectionField::where('webmaster_id', $id)->delete();
            //delete section
            if ($WebmasterSection->photo != "" && $WebmasterSection->photo != "nav-bg.png") {
                FileHelper::deleteFile($this->uploadPath."/".$WebmasterSection->photo);
            }


            // send to instant indexing
            if (Helper::GeneralWebmasterSettings("instant_index")) {
                if (Helper::GeneralWebmasterSettings("instant_index_on_delete")) {
                    if ($WebmasterSection->index_status) {
                        $urls = [];
                        foreach (Helper::languagesList() as $ActiveLanguage) {
                            $urls[] = Helper::sectionURL($WebmasterSection->id, $ActiveLanguage->code,
                                $WebmasterSection);
                        }
                        $indexer = new GoogleIndexing();
                        $indexer->addOrRemove($urls, 1);
                    }
                }
            }

            $WebmasterSection->delete();

            Cache::forget('_Loader_WebmasterSections');
            Cache::forget('_Loader_Landing_Modules');
            return redirect()->action([WebmasterSectionsController::class, 'index'])->with('doneMessage',
                __('backend.deleteDone'));

        } else {
            return redirect()->action([WebmasterSectionsController::class, 'index']);
        }
    }

    public function clone($id = 0)
    {
        //
        if (@Auth::user()->permissionsGroup->view_status) {
            $WebmasterSection = WebmasterSection::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $WebmasterSection = WebmasterSection::find($id);
        }
        if (!empty($WebmasterSection)) {
            $NewSection = $WebmasterSection->replicate();

            $next_nor_no = WebmasterSection::max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }
            $NewSection->row_no = $next_nor_no;
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $NewSection->{"title_".$ActiveLanguage->code} = $WebmasterSection->{"title_".$ActiveLanguage->code}." - Copy";
                    // meta info
                    if ($WebmasterSection->{"seo_title_".$ActiveLanguage->code} != "") {
                        $NewSection->{"seo_title_".$ActiveLanguage->code} = $WebmasterSection->{"seo_title_".$ActiveLanguage->code}." - Copy";
                    }
                    $NewSection->{"seo_url_slug_".$ActiveLanguage->code} = Helper::URLSlug($WebmasterSection->{"title_".$ActiveLanguage->code},
                        "section", 0);
                }
            }
            $NewSection->created_by = Auth::user()->id;
            $NewSection->updated_by = null;
            $NewSection->save();

            if (count($WebmasterSection->allCustomFields) > 0) {
                foreach ($WebmasterSection->allCustomFields as $customField) {
                    $NewCustomField = $customField->replicate();

                    $next_nor_no = WebmasterSectionField::where('webmaster_id', '=', $NewSection->id)->max('row_no');
                    if ($next_nor_no < 1) {
                        $next_nor_no = 1;
                    } else {
                        $next_nor_no++;
                    }
                    $NewCustomField->row_no = $next_nor_no;
                    $NewCustomField->webmaster_id = $NewSection->id;

                    $NewCustomField->created_by = Auth::user()->id;
                    $NewCustomField->updated_by = null;
                    $NewCustomField->save();

                }
            }


            $Permissions = Permissions::find(Auth::user()->permissionsGroup->id);
            if (!empty($Permissions)) {
                $Permissions->data_sections = $Permissions->data_sections.",".$NewSection->id;
                $Permissions->save();
            }
            if (Auth::user()->permissionsGroup->id != 1) {
                $Permissions = Permissions::find(1);
                if (!empty($Permissions)) {
                    $Permissions->data_sections = $Permissions->data_sections.",".$NewSection->id;
                    $Permissions->save();
                }
            }

            Cache::forget('_Loader_WebmasterSections');
            Cache::forget('_Loader_Landing_Modules');
            return redirect()->action([WebmasterSectionsController::class, 'edit'],
                ['id' => $NewSection->id])->with('doneMessage',
                __('backend.saveDone'));

        } else {
            return redirect()->action([WebmasterSectionsController::class, 'index']);
        }
    }

    public function updateAll(Request $request)
    {
        //
        if ($request->action == "order") {
            foreach ($request->row_ids as $rowId) {
                $WebmasterSection = WebmasterSection::find($rowId);
                if (!empty($WebmasterSection)) {
                    $row_no_val = "row_no_".$rowId;
                    $WebmasterSection->row_no = $request->$row_no_val;
                    $WebmasterSection->save();
                }
            }
        } else {
            if ($request->ids != "") {
                if ($request->action == "activate") {
                    WebmasterSection::wherein('id', $request->ids)
                        ->update(['status' => 1]);

                } elseif ($request->action == "block") {
                    WebmasterSection::wherein('id', $request->ids)
                        ->update(['status' => 0]);
                } elseif ($request->action == "index_add" || $request->action == "index_remove") {
                    $WebmasterSections = WebmasterSection::wherein('id', $request->ids)->get();
                    $urls = [];
                    foreach ($WebmasterSections as $WebmasterSection) {
                        foreach (Helper::languagesList() as $ActiveLanguage) {
                            $urls[] = Helper::sectionURL($WebmasterSection->id, $ActiveLanguage->code,
                                $WebmasterSection);
                        }
                    }
                    $indexer = new GoogleIndexing();
                    $response = $indexer->addOrRemove($urls, (($request->action == "index_remove") ? 1 : 0));
                    return redirect()->action([WebmasterSectionsController::class, 'index'])->with(@$response["status"],
                        @$response["message"]);

                } elseif ($request->action == "delete") {

                    $WebmasterSections = WebmasterSection::wherein('id', $request->ids)->get();
                    foreach ($WebmasterSections as $WebmasterSection) {
                        if ($WebmasterSection->id != 1) {
                            if (count($WebmasterSection->topics) > 0) {
                                foreach ($WebmasterSection->topics as $Topic) {
                                    //delete topics
                                    // Delete a Topic photo
                                    if ($Topic->photo_file != "") {
                                        FileHelper::deleteFile($this->uploadPath."/".$Topic->photo_file);
                                    }
                                    if ($Topic->attach_file != "") {
                                        FileHelper::deleteFile($this->uploadPath."/".$Topic->attach_file);
                                    }
                                    if ($Topic->audio_file != "") {
                                        FileHelper::deleteFile($this->uploadPath."/".$Topic->audio_file);
                                    }
                                    if ($Topic->video_type == 0 && $Topic->video_file != "") {
                                        FileHelper::deleteFile($this->uploadPath."/".$Topic->video_file);
                                    }
                                    //delete additional fields
                                    TopicField::where('topic_id', $Topic->id)->delete();
                                    //delete Related Topics
                                    RelatedTopic::where('topic_id', $Topic->id)->delete();
                                    // Remove categories
                                    TopicCategory::where('topic_id', $Topic->id)->delete();
                                    // Remove comments
                                    Comment::where('topic_id', $Topic->id)->delete();
                                    // Remove maps
                                    Map::where('topic_id', $Topic->id)->delete();
                                    //remove blocks
                                    $TopicBlocks = TopicBlock::where('topic_id', $Topic->id)->get();
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
                                    TopicBlock::where('topic_id', $Topic->id)->delete();
                                    // Remove Photos
                                    $PhotoFiles = Photo::where('topic_id', $Topic->id)->get();
                                    if (count($PhotoFiles) > 0) {
                                        foreach ($PhotoFiles as $PhotoFile) {
                                            if ($PhotoFile->file != "") {
                                                FileHelper::deleteFile($this->uploadPath."/".$PhotoFile->file);
                                            }
                                        }
                                    }
                                    Photo::where('topic_id', $Topic->id)->delete();
                                    // Remove Attach Files
                                    $AttachFiles = AttachFile::where('topic_id', $Topic->id)->get();
                                    if (count($AttachFiles) > 0) {
                                        foreach ($AttachFiles as $AttachFile) {
                                            if ($AttachFile->file != "") {
                                                FileHelper::deleteFile($this->uploadPath."/".$AttachFile->file);
                                            }
                                        }
                                    }
                                    AttachFile::where('topic_id', $Topic->id)->delete();

                                    //Remove Topic
                                    $Topic->delete();
                                }
                            }
                            //delete categories
                            if (count($WebmasterSection->sections) > 0) {
                                foreach ($WebmasterSection->sections as $Section) {
                                    $Section->delete();
                                }
                            }
                            //delete menus
                            if (count($WebmasterSection->menus) > 0) {
                                foreach ($WebmasterSection->menus as $Menu) {
                                    $Menu->delete();
                                }
                            }
                        }

                        Menu::wherein('cat_id', $request->ids)->delete();
                        //delete additional fields
                        WebmasterSectionField::wherein('webmaster_id', $request->ids)->delete();
                        //delete section
                        WebmasterSection::wherein('id', $request->ids)
                            ->delete();
                    }
                }
            }
        }
        Cache::forget('_Loader_WebmasterSections');
        Cache::forget('_Loader_Landing_Modules');
        return redirect()->action([WebmasterSectionsController::class, 'index'])->with('doneMessage',
            __('backend.saveDone'));
    }


    // Fields Functions

    public function webmasterFields($webmasterId)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            return redirect()->action([WebmasterSectionsController::class, 'edit'],
                ['id' => $webmasterId])->with('activeTab', 'fields');
        } else {
            return redirect()->route('NotFound');
        }
    }

    public function fieldsCreate($webmasterId)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            return redirect()->action([WebmasterSectionsController::class, 'edit'],
                ['id' => $webmasterId])->with('activeTab', 'fields')->with('fieldST', 'create');
        } else {
            return redirect()->route('NotFound');
        }
    }

    public function fieldsStore(Request $request, $webmasterId)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $this->validate($request, [
                'type' => 'required'
            ]);

            $next_nor_no = WebmasterSectionField::where('webmaster_id', '=', $webmasterId)->max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }

            $WebmasterSectionField = new WebmasterSectionField;
            $WebmasterSectionField->webmaster_id = $webmasterId;
            $WebmasterSectionField->row_no = $next_nor_no;
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $WebmasterSectionField->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                    if ($request->type == 99) {
                        $WebmasterSectionField->{"details_".$ActiveLanguage->code} = $request->{"fixed_details_".$ActiveLanguage->code};
                    } else {
                        $WebmasterSectionField->{"details_".$ActiveLanguage->code} = $request->{"details_".$ActiveLanguage->code};
                    }
                }
            }
            $WebmasterSectionField->default_value = $request->default_value;
            $WebmasterSectionField->lang_code = $request->lang_code;
            $WebmasterSectionField->categories = ($request->categories != "") ? implode(",",
                $request->categories) : null;
            $WebmasterSectionField->css_class = $request->css_class;
            $WebmasterSectionField->type = $request->type;
            $WebmasterSectionField->required = ($request->required) ? 1 : 0;
            $WebmasterSectionField->in_table = ($request->in_table) ? 1 : 0;
            $WebmasterSectionField->in_search = ($request->in_search) ? 1 : 0;
            $WebmasterSectionField->in_listing = ($request->in_listing) ? 1 : 0;
            $WebmasterSectionField->in_page = ($request->in_page) ? 1 : 0;
            $WebmasterSectionField->in_statics = ($request->in_statics) ? 1 : 0;
            $WebmasterSectionField->status = 1;
            $WebmasterSectionField->created_by = Auth::user()->id;

            if ($request->type == 99) {
                $WebmasterSectionField->required = 0;
                $WebmasterSectionField->in_table = 0;
                $WebmasterSectionField->in_search = 0;
                $WebmasterSectionField->in_listing = 0;
                $WebmasterSectionField->in_statics = 0;
            }

            $view_permission_groups_values = "";
            if (@$request->view_permission_groups != "") {
                foreach ($request->view_permission_groups as $key => $val) {
                    if ($val == 0) {
                        $view_permission_groups_values = "";
                        break;
                    } else {
                        $view_permission_groups_values = $val.",".$view_permission_groups_values;
                    }
                }
                $view_permission_groups_values = substr($view_permission_groups_values, 0, -1);
            }
            if ($view_permission_groups_values == "") {
                $view_permission_groups_values = 0;
            }
            $WebmasterSectionField->view_permission_groups = $view_permission_groups_values;

            $add_permission_groups_values = "";
            if (@$request->add_permission_groups != "") {
                foreach ($request->add_permission_groups as $key => $val) {
                    if ($val == 0) {
                        $add_permission_groups_values = "";
                        break;
                    } else {
                        $add_permission_groups_values = $val.",".$add_permission_groups_values;
                    }
                }
                $add_permission_groups_values = substr($add_permission_groups_values, 0, -1);
            }
            if ($add_permission_groups_values == "") {
                $add_permission_groups_values = 0;
            }
            $WebmasterSectionField->add_permission_groups = $add_permission_groups_values;

            $edit_permission_groups_values = "";
            if (@$request->edit_permission_groups != "") {
                foreach ($request->edit_permission_groups as $key => $val) {
                    if ($val == 0) {
                        $edit_permission_groups_values = "";
                        break;
                    } else {
                        $edit_permission_groups_values = $val.",".$edit_permission_groups_values;
                    }
                }
                $edit_permission_groups_values = substr($edit_permission_groups_values, 0, -1);
            }
            if ($edit_permission_groups_values == "") {
                $edit_permission_groups_values = 0;
            }
            $WebmasterSectionField->edit_permission_groups = $edit_permission_groups_values;

            $WebmasterSectionField->save();

            // add new column to end of users custom cols list
            if ($WebmasterSectionField->in_table) {
                $Users = User::all();
                foreach ($Users as $User) {
                    $new_cols_sort = [];
                    $table_columns = [];
                    if ($User->table_columns != "") {
                        try {
                            $table_columns = json_decode($User->table_columns);
                        } catch (\Exception $e) {

                        }
                    }
                    if (!empty($table_columns)) {
                        foreach ($table_columns as $k => $v) {
                            if ($k == "sec_".$WebmasterSection->id) {
                                if (!empty($v)) {
                                    foreach ($v as $k2 => $v2) {
                                        $new_cols_sort[$k2] = $v2;
                                    }
                                    $new_cols_sort["col_custom_".$WebmasterSectionField->id] = 1;
                                }
                            }
                        }
                        $table_columns->{"sec_".$WebmasterSection->id} = $new_cols_sort;
                        $User->table_columns = json_encode($table_columns);
                        $User->save();
                    }
                }
            }
            Cache::forget('_Loader_WebmasterSections');
            Cache::forget('_Loader_Landing_Modules');

            return redirect()->action([WebmasterSectionsController::class, 'edit'],
                ['id' => $webmasterId])->with('doneMessage', __('backend.saveDone'))->with('activeTab', 'fields');

        } else {
            return redirect()->route('NotFound');
        }
    }

    public function fieldsEdit($webmasterId, $field_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            $WebmasterSectionField = WebmasterSectionField::find($field_id);
            if (!empty($WebmasterSectionField)) {

                return redirect()->action([WebmasterSectionsController::class, 'edit'],
                    ['id' => $webmasterId])->with('activeTab',
                    'fields')->with('fieldST', 'edit')->with('WebmasterSectionField', $WebmasterSectionField);

            } else {
                return redirect()->action([WebmasterSectionsController::class, 'edit'],
                    ['id' => $webmasterId])->with('activeTab', 'fields');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    public function fieldsUpdate(Request $request, $webmasterId, $file_id)
    {

        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //

            $WebmasterSectionField = WebmasterSectionField::find($file_id);
            if (!empty($WebmasterSectionField)) {
                $was_in_table = $WebmasterSectionField->in_table;
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $WebmasterSectionField->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                        if ($request->type == 99) {
                            $WebmasterSectionField->{"details_".$ActiveLanguage->code} = $request->{"fixed_details_".$ActiveLanguage->code};
                        } else {
                            $WebmasterSectionField->{"details_".$ActiveLanguage->code} = $request->{"details_".$ActiveLanguage->code};
                        }
                    }
                }
                $WebmasterSectionField->default_value = $request->default_value;
                $WebmasterSectionField->lang_code = $request->lang_code;
                $WebmasterSectionField->categories = ($request->categories != "") ? implode(",",
                    $request->categories) : null;
                $WebmasterSectionField->css_class = $request->css_class;
                $WebmasterSectionField->type = $request->type;
                $WebmasterSectionField->required = ($request->required) ? 1 : 0;
                $WebmasterSectionField->in_table = ($request->in_table) ? 1 : 0;
                $WebmasterSectionField->in_search = ($request->in_search) ? 1 : 0;
                $WebmasterSectionField->in_listing = ($request->in_listing) ? 1 : 0;
                $WebmasterSectionField->in_page = ($request->in_page) ? 1 : 0;
                $WebmasterSectionField->in_statics = ($request->in_statics) ? 1 : 0;
                $WebmasterSectionField->status = $request->status;
                $WebmasterSectionField->updated_by = Auth::user()->id;

                if ($request->type == 99) {
                    $WebmasterSectionField->required = 0;
                    $WebmasterSectionField->in_table = 0;
                    $WebmasterSectionField->in_search = 0;
                    $WebmasterSectionField->in_listing = 0;
                    $WebmasterSectionField->in_statics = 0;
                }
                $view_permission_groups_values = "";
                if (@$request->view_permission_groups != "") {
                    foreach ($request->view_permission_groups as $key => $val) {
                        if ($val == 0) {
                            $view_permission_groups_values = "";
                            break;
                        } else {
                            $view_permission_groups_values = $val.",".$view_permission_groups_values;
                        }
                    }
                    $view_permission_groups_values = substr($view_permission_groups_values, 0, -1);
                }
                if ($view_permission_groups_values == "") {
                    $view_permission_groups_values = 0;
                }
                $WebmasterSectionField->view_permission_groups = $view_permission_groups_values;

                $add_permission_groups_values = "";
                if (@$request->add_permission_groups != "") {
                    foreach ($request->add_permission_groups as $key => $val) {
                        if ($val == 0) {
                            $add_permission_groups_values = "";
                            break;
                        } else {
                            $add_permission_groups_values = $val.",".$add_permission_groups_values;
                        }
                    }
                    $add_permission_groups_values = substr($add_permission_groups_values, 0, -1);
                }
                if ($add_permission_groups_values == "") {
                    $add_permission_groups_values = 0;
                }
                $WebmasterSectionField->add_permission_groups = $add_permission_groups_values;

                $edit_permission_groups_values = "";
                if (@$request->edit_permission_groups != "") {
                    foreach ($request->edit_permission_groups as $key => $val) {
                        if ($val == 0) {
                            $edit_permission_groups_values = "";
                            break;
                        } else {
                            $edit_permission_groups_values = $val.",".$edit_permission_groups_values;
                        }
                    }
                    $edit_permission_groups_values = substr($edit_permission_groups_values, 0, -1);
                }
                if ($edit_permission_groups_values == "") {
                    $edit_permission_groups_values = 0;
                }
                $WebmasterSectionField->edit_permission_groups = $edit_permission_groups_values;

                $WebmasterSectionField->save();


                // add new column to end of users custom cols list
                if (!$was_in_table && $WebmasterSectionField->in_table) {
                    $Users = User::all();
                    foreach ($Users as $User) {
                        $new_cols_sort = [];
                        $table_columns = [];
                        if ($User->table_columns != "") {
                            try {
                                $table_columns = json_decode($User->table_columns);
                            } catch (\Exception $e) {

                            }
                        }
                        if (!empty($table_columns)) {
                            foreach ($table_columns as $k => $v) {
                                if ($k == "sec_".$WebmasterSection->id) {
                                    if (!empty($v)) {
                                        foreach ($v as $k2 => $v2) {
                                            $new_cols_sort[$k2] = $v2;
                                        }
                                        $new_cols_sort["col_custom_".$WebmasterSectionField->id] = 1;
                                    }
                                }
                            }
                            $table_columns->{"sec_".$WebmasterSection->id} = $new_cols_sort;
                            $User->table_columns = json_encode($table_columns);
                            $User->save();
                        }
                    }
                }
                Cache::forget('_Loader_WebmasterSections');
                Cache::forget('_Loader_Landing_Modules');

                return redirect()->action([WebmasterSectionsController::class, 'edit'],
                    ['id' => $webmasterId])->with('doneMessage',
                    __('backend.saveDone'))->with('activeTab', 'fields');

            } else {
                return redirect()->action([WebmasterSectionsController::class, 'edit'],
                    ['id' => $webmasterId])->with('activeTab', 'fields');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    public function fieldsDestroy($webmasterId, $field_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            $WebmasterSectionField = WebmasterSectionField::find($field_id);
            if (!empty($WebmasterSectionField)) {
                TopicField::where("field_id", $field_id)->delete();
                $WebmasterSectionField->delete();
                Cache::forget('_Loader_WebmasterSections');
                Cache::forget('_Loader_Landing_Modules');
                return redirect()->action([WebmasterSectionsController::class, 'edit'],
                    ['id' => $webmasterId])->with('doneMessage',
                    __('backend.deleteDone'))->with('activeTab', 'fields');
            } else {
                return redirect()->action([WebmasterSectionsController::class, 'edit'],
                    ['id' => $webmasterId])->with('activeTab', 'fields');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    public function fieldsUpdateAll(Request $request, $webmasterId)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            if ($request->action == "order") {
                foreach ($request->row_ids as $rowId) {
                    $WebmasterSectionField = WebmasterSectionField::find($rowId);
                    if (!empty($WebmasterSectionField)) {
                        $row_no_val = "row_no_".$rowId;
                        $WebmasterSectionField->row_no = $request->$row_no_val;
                        $WebmasterSectionField->save();
                    }
                }
            } else {
                if ($request->ids != "") {
                    if ($request->action == "activate") {
                        WebmasterSectionField::wherein('id', $request->ids)
                            ->update(['status' => 1]);

                    } elseif ($request->action == "block") {
                        WebmasterSectionField::wherein('id', $request->ids)
                            ->update(['status' => 0]);

                    } elseif ($request->action == "delete") {
                        TopicField::wherein("field_id", $request->ids)->delete();
                        WebmasterSectionField::wherein('id', $request->ids)
                            ->delete();

                    }
                }
            }
            Cache::forget('_Loader_WebmasterSections');
            Cache::forget('_Loader_Landing_Modules');
            return redirect()->action([WebmasterSectionsController::class, 'edit'],
                ['id' => $webmasterId])->with('doneMessage', __('backend.saveDone'))->with('activeTab', 'fields');
        } else {
            return redirect()->route('NotFound');
        }
    }


}
