<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\WebmasterSection;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->calendar_status || !Helper::GeneralWebmasterSettings("calendar_status")) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List of Events
        if (@Auth::user()->permissionsGroup->view_status) {
            $Events = Event::where('created_by', '=', Auth::user()->id)->orderby('start_date', 'asc')->get();
        } else {
            $Events = Event::orderby('start_date', 'asc')->get();
        }
        $DefaultDate = date('Y-m-d');
        $EStatus = "";

        return view("dashboard.events.list", compact("GeneralWebmasterSections", "Events", "DefaultDate", "EStatus"));
    }

    public function create()
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List of Events
        if (@Auth::user()->permissionsGroup->view_status) {
            $Events = Event::where('created_by', '=', Auth::user()->id)->orderby('start_date', 'asc')->get();
        } else {
            $Events = Event::orderby('start_date', 'asc')->get();
        }
        $DefaultDate = date('Y-m-d');
        $EStatus = "new";

        return view("dashboard.events.list", compact("GeneralWebmasterSections", "Events", "DefaultDate", "EStatus"));
    }

    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'type' => 'required',
            'title' => 'required'
        ]);

        $Event = new Event;
        $Event->user_id = Auth::user()->id;
        $Event->created_by = Auth::user()->id;
        $Event->type = $request->type;
        $Event->title = strip_tags($request->title);
        $Event->details = strip_tags($request->details);
        if ($request->type == 3) {
            // Task
            $Event->start_date = Helper::dateForDB($request->date_start);
            $Event->end_date = Helper::dateForDB($request->date_end);
        } elseif ($request->type == 2) {
            // Event
            $Event->start_date = Helper::dateForDB($request->time_start, 1);
            $Event->end_date = Helper::dateForDB($request->time_end, 1);
        } elseif ($request->type == 1) {
            // Meeting
            $Event->start_date = Helper::dateForDB($request->date_at, 1);
            $Event->end_date = Helper::dateForDB($request->date_at, 1);
        } else {
            // Note
            $Event->start_date = Helper::dateForDB($request->date);
            $Event->end_date = Helper::dateForDB($request->date);
        }
        $Event->save();
        Cache::forget('_Loader_Events');

        return redirect()->action([EventsController::class, 'index'])->with('doneMessage', __('backend.addDone'));
    }

    public function edit($id)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List of Events
        if (@Auth::user()->permissionsGroup->view_status) {
            $Events = Event::where('created_by', '=', Auth::user()->id)->orderby('start_date', 'asc')->get();
            $EditEvent = Event::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $Events = Event::orderby('start_date', 'asc')->get();
            $EditEvent = Event::find($id);
        }

        if (!empty($EditEvent)) {
            $DefaultDate = $EditEvent->start_date;
            $EStatus = "edit";
            return view("dashboard.events.list",
                compact("GeneralWebmasterSections", "Events", "EditEvent", "DefaultDate", "EStatus"));
        } else {
            return redirect()->action([EventsController::class, 'index']);
        }
    }

    public function update(Request $request, $id)
    {
        //
        $Event = Event::find($id);
        if (!empty($Event)) {

            $this->validate($request, [
                'type' => 'required',
                'title' => 'required'
            ]);

            $Event->type = $request->type;
            $Event->title = strip_tags($request->title);
            $Event->details = strip_tags($request->details);
            if ($request->type == 3) {
                // Task
                $Event->start_date = Helper::dateForDB($request->date_start);
                $Event->end_date = Helper::dateForDB($request->date_end);
            } elseif ($request->type == 2) {
                // Event
                $Event->start_date = Helper::dateForDB($request->time_start, 1);
                $Event->end_date = Helper::dateForDB($request->time_end, 1);
            } elseif ($request->type == 1) {
                // Meeting
                $Event->start_date = Helper::dateForDB($request->date_at, 1);
                $Event->end_date = Helper::dateForDB($request->date_at, 1);
            } else {
                // Note
                $Event->start_date = Helper::dateForDB($request->date);
                $Event->end_date = Helper::dateForDB($request->date);
            }
            $Event->updated_by = Auth::user()->id;
            $Event->save();
            Cache::forget('_Loader_Events');

            return redirect()->action([EventsController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
        } else {
            return redirect()->action([EventsController::class, 'index']);
        }
    }

    public function destroy($id)
    {
        //
        if (@Auth::user()->permissionsGroup->view_status) {
            $Event = Event::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $Event = Event::find($id);
        }
        if (!empty($Event)) {
            $Event->delete();
            Cache::forget('_Loader_Events');
            return redirect()->action([EventsController::class, 'index'])->with('doneMessage', __('backend.deleteDone'));
        } else {
            return redirect()->action([EventsController::class, 'index']);
        }
    }

    public function updateAll()
    {
        //
        Event::where('user_id', "=", Auth::user()->id)->delete();
        Cache::forget('_Loader_Events');
        return redirect()->action([EventsController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
    }

    public function extend(Request $request, $id)
    {
        //
        $Event = Event::find($id);
        if (!empty($Event)) {
            if ($Event->type == 0 && $request->ended_on != "") {
                $Event->type = 3;
            }
            if ($request->ended_on != "") {
                $Event->end_date = date("Y-m-d", strtotime($request->ended_on));
            } else {
                if ($Event->end_date != "") {
                    $dif = strtotime($request->started_on) - strtotime($Event->start_date);
                    $n = strtotime($Event->end_date) + $dif;
                    $Event->end_date = date("Y-m-d", $n);
                }
            }
            if ($request->started_on != "") {
                $Event->start_date = date("Y-m-d H:i:s", strtotime($request->started_on));
            }
            $Event->updated_by = Auth::user()->id;
            $Event->save();
            Cache::forget('_Loader_Events');
        }
    }

}
