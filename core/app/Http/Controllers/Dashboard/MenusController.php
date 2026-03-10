<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\WebmasterSection;
use Auth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Redirect;
use Helper;

class MenusController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($ParentMenuId = 0)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->menus_status || !Helper::GeneralWebmasterSettings("menus_status")) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        if ($ParentMenuId > 0) {
            $EditedMenu = Menu::find($ParentMenuId);
            $Menus = Menu::where('father_id', $ParentMenuId)->orderby('row_no',
                'asc')->paginate(config('smartend.backend_pagination'));
        } else {
            $MenusCount = Menu::where('father_id', '0')->count();
            if ($MenusCount > 0) {
                $Menusfirst = Menu::where('father_id', '0')->orderby('row_no', 'asc')->first();
                $ParentMenuId = $Menusfirst->id;
                $Menus = Menu::where('father_id', $Menusfirst->id)->orderby('row_no',
                    'asc')->paginate(config('smartend.backend_pagination'));
                $EditedMenu = Menu::find($Menusfirst->id);
            } else {
                $Menus = Menu::where('father_id', '0')->orderby('row_no',
                    'asc')->paginate(config('smartend.backend_pagination'));
                $EditedMenu = "";
            }
        }
        //Parent Menus
        $ParentMenus = Menu::where('father_id', '0')->orderby('row_no', 'asc')->get();

        return view("dashboard.menus.list",
            compact("Menus", "GeneralWebmasterSections", "ParentMenus", "EditedMenu", "ParentMenuId"));
    }

    public function create($ParentMenuId = 0)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //Father Menus
        $FatherMenus = Menu::where('father_id', $ParentMenuId)->where('type', 0)->orderby('row_no', 'asc')->get();

        return view("dashboard.menus.create",
            compact("GeneralWebmasterSections", "ParentMenuId", "FatherMenus"));
    }

    public function store(Request $request, $ParentMenuId = 0)
    {
        //
        $father = $ParentMenuId;
        if ($request->father_id > 0) {
            $father = $request->father_id;
        }
        $next_nor_no = Menu::where('father_id', $father)->max('row_no');
        if ($next_nor_no < 1) {
            $next_nor_no = 1;
        } else {
            $next_nor_no++;
        }

        $Menu = new Menu;
        $Menu->row_no = $next_nor_no;
        $Menu->father_id = $father;
        foreach (Helper::languagesList() as $ActiveLanguage) {
            if ($ActiveLanguage->box_status) {
                $Menu->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                $Menu->{"link_".$ActiveLanguage->code} = $request->{"link_".$ActiveLanguage->code};
            }
        }
        $Menu->type = $request->type;
        $Menu->link = $request->link;
        $Menu->icon = $request->icon;
        $Menu->target = ($request->target > 0) ? $request->target : 0;
        $Menu->cat_id = $request->cat_id;
        $Menu->status = 1;
        $Menu->created_by = Auth::user()->id;
        $Menu->save();

        Cache::forget('_Loader_MenuList');

        return redirect()->action([MenusController::class, 'edit'],
            ["id" => $Menu->id, "ParentMenuId" => $request->ParentMenuId])->with('doneMessage',
            __('backend.addDone'));
    }

    public function storeMenu(Request $request)
    {
        //
        $next_nor_no = Menu::where('father_id', "0")->max('row_no');
        if ($next_nor_no < 1) {
            $next_nor_no = 1;
        } else {
            $next_nor_no++;
        }

        $Menu = new Menu;
        $Menu->row_no = $next_nor_no;
        $Menu->type = 0;
        $Menu->father_id = 0;
        foreach (Helper::languagesList() as $ActiveLanguage) {
            if ($ActiveLanguage->box_status) {
                $Menu->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
            }
        }
        $Menu->created_by = Auth::user()->id;
        $Menu->target = 0;
        $Menu->status = 1;
        $Menu->save();

        Cache::forget('_Loader_MenuList');
        return redirect()->action([MenusController::class, 'index']);
    }

    public function edit($id, $ParentMenuId)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //Father Menus
        $FatherMenus = Menu::where('father_id', $ParentMenuId)->where('type', 0)->where('id', "!=",
            $id)->orderby('row_no', 'asc')->get();

        $Menus = Menu::find($id);
        if (!empty($Menus)) {

            foreach (Helper::languagesList() as $ActiveLanguage) {
                try {
                    $code = $ActiveLanguage->code;
                    // menus table
                    Schema::table('menus', function (Blueprint $table) use ($code) {
                        $table->string('link_'.$code)->nullable();
                    });

                    // copy data to new language columns
                    Menu::where('id', '>', 0)->update(['link_'.$code => DB::raw('link')]);
                } catch (\Exception $e) {
                }
            }

            return view("dashboard.menus.edit",
                compact("Menus", "GeneralWebmasterSections", "ParentMenuId", "FatherMenus"));
        } else {
            return redirect()->action([MenusController::class, 'index']);
        }
    }

    public function editMenu($id)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        $Menus = Menu::find($id);
        if (!empty($Menus)) {
            return redirect()->action([MenusController::class, 'index'], ["ParentMenuId" => $id])->with('EditMenu', "Yes");
        } else {
            return redirect()->action([MenusController::class, 'index']);
        }
    }

    public function update(Request $request, $id)
    {
        //
        $Menu = Menu::find($id);
        if (!empty($Menu)) {

            $Menu->father_id = $request->father_id;
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $Menu->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                    $Menu->{"link_".$ActiveLanguage->code} = $request->{"link_".$ActiveLanguage->code};
                }
            }
            $Menu->type = $request->type;
            $Menu->link = $request->link;
            $Menu->icon = $request->icon;
            $Menu->target = ($request->target > 0) ? $request->target : 0;
            $Menu->cat_id = $request->cat_id;
            $Menu->status = $request->status;
            $Menu->updated_by = Auth::user()->id;
            $Menu->save();

            Cache::forget('_Loader_MenuList');

            return redirect()->action([MenusController::class, 'edit'], ["id" => $Menu->id,"ParentMenuId"=>$request->ParentMenuId])->with('doneMessage', __('backend.addDone'));
        } else {
            return redirect()->action([MenusController::class, 'index']);
        }
    }

    public function updateMenu(Request $request, $id)
    {
        //
        $Menu = Menu::find($id);
        if (!empty($Menu)) {
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $Menu->{"title_".$ActiveLanguage->code} = strip_tags($request->{"title_".$ActiveLanguage->code});
                }
            }
            $Menu->target = 0;
            $Menu->updated_by = Auth::user()->id;
            $Menu->save();

            Cache::forget('_Loader_MenuList');

            return redirect()->action([MenusController::class, 'index'], ["ParentMenuId" => $Menu->id])->with('doneMessage2',
                __('backend.saveDone'));
        } else {
            return redirect()->action([MenusController::class, 'index']);
        }
    }

    public function destroy($id)
    {
        //
        $Menu = Menu::find($id);
        if (!empty($Menu)) {
            $Menu->delete();

            Cache::forget('_Loader_MenuList');

            return redirect()->action([MenusController::class, 'index'])->with('doneMessage', __('backend.deleteDone'));
        } else {
            return redirect()->action([MenusController::class, 'index']);
        }
    }

    public function destroyMenu($id)
    {
        //
        $Menu = Menu::find($id);
        if (!empty($Menu)) {
            $subMenus = Menu::where('father_id', $Menu->id)->get();
            foreach ($subMenus as $subMenu) {
                Menu::where('father_id', $subMenu->id)->delete();
            }
            Menu::where('father_id', $Menu->id)->delete();
            $Menu->delete();

            Cache::forget('_Loader_MenuList');

            return redirect()->action([MenusController::class, 'index'])->with('doneMessage2',
                __('backend.deleteDone'));
        } else {
            return redirect()->action([MenusController::class, 'index']);
        }
    }

    public function updateAll(Request $request)
    {
        //
        if ($request->action == "order") {
            foreach ($request->row_ids as $rowId) {
                $Menu = Menu::find($rowId);
                if (!empty($Menu)) {
                    $row_no_val = "row_no_".$rowId;
                    $Menu->row_no = $request->$row_no_val;
                    $Menu->save();
                }
            }

        } else {
            if ($request->ids != "") {
                if ($request->action == "activate") {
                    Menu::wherein('id', $request->ids)
                        ->update(['status' => 1]);

                } elseif ($request->action == "block") {
                    Menu::wherein('id', $request->ids)
                        ->update(['status' => 0]);

                } elseif ($request->action == "delete") {

                    Menu::wherein('father_id', $request->ids)->delete();
                    Menu::wherein('id', $request->ids)
                        ->delete();

                }
            }
        }

        Cache::forget('_Loader_MenuList');

        return redirect()->action([MenusController::class, 'index'], ["ParentMenuId" => $request->ParentMenuId])->with('doneMessage2',
            __('backend.saveDone'));
    }

}
