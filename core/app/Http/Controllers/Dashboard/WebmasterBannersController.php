<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\WebmasterBanner;
use App\Models\WebmasterSection;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Redirect;
use File;
use Helper;

class WebmasterBannersController extends Controller
{


    private $uploadPath = "banners";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->banners_status) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        if (@Auth::user()->permissionsGroup->view_status) {
            $WebmasterBanners = WebmasterBanner::where('created_by', '=', Auth::user()->id)->orderby('row_no',
                'asc')->paginate(config('smartend.backend_pagination'));
        } else {
            $WebmasterBanners = WebmasterBanner::orderby('row_no',
                'asc')->paginate(config('smartend.backend_pagination'));
        }
        return view("dashboard.banners.banners_settings.list", compact("WebmasterBanners", "GeneralWebmasterSections"));
    }

    public function create()
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        return view("dashboard.banners.banners_settings.create", compact("GeneralWebmasterSections"));
    }

    public function store(Request $request)
    {
        //

        $next_nor_no = WebmasterBanner::max('row_no');
        if ($next_nor_no < 1) {
            $next_nor_no = 1;
        } else {
            $next_nor_no++;
        }
        $WebmasterBanner = new WebmasterBanner;
        $WebmasterBanner->row_no = $next_nor_no;
        foreach (Helper::languagesList() as $ActiveLanguage) {
            $WebmasterBanner->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
        }
        $WebmasterBanner->width = $request->width;
        $WebmasterBanner->height = $request->height;
        $WebmasterBanner->desc_status = $request->desc_status;
        $WebmasterBanner->link_status = $request->link_status;
        $WebmasterBanner->icon_status = $request->icon_status;
        $WebmasterBanner->type = $request->type;
        $WebmasterBanner->status = 1;
        $WebmasterBanner->created_by = Auth::user()->id;
        $WebmasterBanner->save();

        Cache::forget('_Loader_BannersList');

        return redirect()->action([WebmasterBannersController::class, 'index'])->with('doneMessage',
            __('backend.addDone'));
    }

    public function edit($id)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END
        if (@Auth::user()->permissionsGroup->view_status) {
            $WebmasterBanners = WebmasterBanner::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $WebmasterBanners = WebmasterBanner::find($id);
        }
        if (!empty($WebmasterBanners)) {
            return view("dashboard.banners.banners_settings.edit",
                compact("WebmasterBanners", "GeneralWebmasterSections"));
        } else {
            return redirect()->action([WebmasterBannersController::class, 'index']);
        }
    }

    public function update(Request $request, $id)
    {
        //
        $WebmasterBanner = WebmasterBanner::find($id);
        if (!empty($WebmasterBanner)) {
            foreach (Helper::languagesList() as $ActiveLanguage) {
                $WebmasterBanner->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
            }
            $WebmasterBanner->width = $request->width;
            $WebmasterBanner->height = $request->height;
            $WebmasterBanner->desc_status = $request->desc_status;
            $WebmasterBanner->link_status = $request->link_status;
            $WebmasterBanner->icon_status = $request->icon_status;
            $WebmasterBanner->type = $request->type;
            $WebmasterBanner->status = $request->status;
            $WebmasterBanner->updated_by = Auth::user()->id;
            $WebmasterBanner->save();

            Cache::forget('_Loader_BannersList');

            return redirect()->action([WebmasterBannersController::class, 'index'])->with('doneMessage',
                __('backend.saveDone'));

        } else {
            return redirect()->action([WebmasterBannersController::class, 'index']);
        }
    }

    public function destroy($id)
    {
        //
        if (@Auth::user()->permissionsGroup->view_status) {
            $WebmasterBanner = WebmasterBanner::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $WebmasterBanner = WebmasterBanner::find($id);
        }
        if (!empty($WebmasterBanner)) {

            //delete banners
            if (count($WebmasterBanner->banners) > 0) {
                foreach ($WebmasterBanner->banners as $Banner) {
                    // Delete a banner file
                    foreach (Helper::languagesList() as $ActiveLanguage) {
                        if ($ActiveLanguage->box_status) {
                            if ($Banner->{"file_".$ActiveLanguage->code} != "" && $Banner->{"file_".$ActiveLanguage->code} != "noimg.png") {
                                FileHelper::deleteFile($this->uploadPath."/".$Banner->{"file_".$ActiveLanguage->code});
                            }
                        }
                    }
                    $Banner->delete();
                }
            }

            $WebmasterBanner->delete();

            Cache::forget('_Loader_BannersList');
            return redirect()->action([WebmasterBannersController::class, 'index'])->with('doneMessage',
                __('backend.deleteDone'));
        } else {
            return redirect()->action([WebmasterBannersController::class, 'index']);
        }
    }

    public function updateAll(Request $request)
    {
        //
        if ($request->action == "order") {
            foreach ($request->row_ids as $rowId) {
                $WebmasterBanner = WebmasterBanner::find($rowId);
                if (!empty($WebmasterBanner)) {
                    $row_no_val = "row_no_".$rowId;
                    $WebmasterBanner->row_no = $request->$row_no_val;
                    $WebmasterBanner->save();
                }
            }

        } else {
            if ($request->ids != "") {
                if ($request->action == "activate") {
                    WebmasterBanner::wherein('id', $request->ids)
                        ->update(['status' => 1]);

                } elseif ($request->action == "block") {
                    WebmasterBanner::wherein('id', $request->ids)
                        ->update(['status' => 0]);

                } elseif ($request->action == "delete") {

                    $WebmasterBanners = WebmasterBanner::wherein('id', $request->ids)->get();
                    foreach ($WebmasterBanners as $WebmasterBanner) {
                        //delete banners
                        if (count($WebmasterBanner->banners) > 0) {
                            foreach ($WebmasterBanner->banners as $Banner) {
                                // Delete a banner file
                                foreach (Helper::languagesList() as $ActiveLanguage) {
                                    if ($ActiveLanguage->box_status) {
                                        if ($Banner->{"file_".$ActiveLanguage->code} != "" && $Banner->{"file_".$ActiveLanguage->code} != "noimg.png") {
                                            FileHelper::deleteFile($this->uploadPath."/".$Banner->{"file_".$ActiveLanguage->code});
                                        }
                                    }
                                }
                                $Banner->delete();
                            }
                        }
                    }

                    WebmasterBanner::wherein('id', $request->ids)
                        ->delete();

                }
            }
        }

        Cache::forget('_Loader_BannersList');
        return redirect()->action([WebmasterBannersController::class, 'index'])->with('doneMessage',
            __('backend.saveDone'));
    }


}
