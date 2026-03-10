<?php

namespace App\Http\Controllers\Dashboard;


use App\Http\Controllers\Controller;
use App\Models\WebmasterSection;
use Auth;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FileManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->file_manager_status || !Helper::GeneralWebmasterSettings("file_manager_status")) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        return view('dashboard.settings.files_manager', compact("GeneralWebmasterSections"));
    }

    public function manager(Request $request)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->file_manager_status || !Helper::GeneralWebmasterSettings("file_manager_status")) {
            return redirect()->route("NoPermission");
        }

        return view('dashboard.settings.file_manager');
    }
}
