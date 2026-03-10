<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsPage;
use App\Models\AnalyticsVisitor;
use App\Models\WebmasterSection;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($stat = "date")
    {

        // Check Permissions
        if (!@Auth::user()->permissionsGroup->analytics_status || !Helper::GeneralWebmasterSettings("analytics_status")) {
            return redirect()->route("NoPermission");
        }

        // check @stat var
        $analyticsVars = array("date", "country", "city", "os", "browser", "referrer", "hostname", "org");
        if (!in_array($stat, $analyticsVars)) {
            return redirect()->action([AnalyticsController::class, 'index']);
        }
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List Min & Max
        $AnalyticsMin = AnalyticsVisitor::min("date");
        $AnalyticsMax = AnalyticsVisitor::max("date");


        $daterangepicker_start = date('Y-m-d', strtotime('-29 day'));
        $daterangepicker_end = date('Y-m-d');
        $daterangepicker_start_text = date("F d , Y", strtotime($daterangepicker_start));
        $daterangepicker_end_text = date("F d , Y", strtotime($daterangepicker_end));
        $min_visitor_date = date('d-m-Y', strtotime('-29 day'));
        if ($AnalyticsMin != "") {
            $min_visitor_date = date('d-m-Y', strtotime($AnalyticsMin));
        }
        $max_visitor_date = date('d-m-Y');
        if ($AnalyticsMax != "") {
            $max_visitor_date = date('d-m-Y', strtotime($AnalyticsMax));
        }


        $AnalyticsValues = array();

        $AnalyticsVisitors = AnalyticsVisitor::select("*")->select($stat)->where('date', '>=', $daterangepicker_start)
            ->where('date', '<=', $daterangepicker_end)
            ->groupBy($stat)
            ->orderBy($stat, 'asc')
            ->get();

        $ix = 0;
        foreach ($AnalyticsVisitors as $AnalyticsV) {

            $TotalV = AnalyticsVisitor::where("$stat", $AnalyticsV->$stat)
                ->where('date', '>=', $daterangepicker_start)
                ->where('date', '<=', $daterangepicker_end)->count();

            $AllVArray = AnalyticsVisitor::select('id')->where("$stat", $AnalyticsV->$stat)
                ->where('date', '>=', $daterangepicker_start)
                ->where('date', '<=', $daterangepicker_end)
                ->get()
                ->toArray();

            $TotalP = AnalyticsPage::whereIn("visitor_id", $AllVArray)->count();

            $newdata = array(
                'name' => $AnalyticsV->$stat,
                'visits' => $TotalV,
                'pages' => $TotalP
            );
            array_push($AnalyticsValues, $newdata);
            $ix++;
        }
        if ($stat == "date") {

        } else {
            usort($AnalyticsValues, function ($a, $b) {
                return $b['visits'] - $a['visits'];
            });
        }

        $TotalVisitors = AnalyticsVisitor::where('date', '>=', $daterangepicker_start)
            ->where('date', '<=', $daterangepicker_end)
            ->count();

        $TotalPages = AnalyticsPage::where('date', '>=', $daterangepicker_start)
            ->where('date', '<=', $daterangepicker_end)
            ->count();

        if ($stat == "org") {
            $statText = "visitorsAnalyticsByOrganization";
        } elseif ($stat == "hostname") {
            $statText = "visitorsAnalyticsByHostName";
        } elseif ($stat == "referrer") {
            $statText = "visitorsAnalyticsByReachWay";
        } elseif ($stat == "resolution") {
            $statText = "visitorsAnalyticsByScreenResolution";
        } elseif ($stat == "browser") {
            $statText = "visitorsAnalyticsByBrowser";
        } elseif ($stat == "os") {
            $statText = "visitorsAnalyticsByOperatingSystem";
        } elseif ($stat == "country") {
            $statText = "visitorsAnalyticsByCountry";
        } elseif ($stat == "city") {
            $statText = "visitorsAnalyticsByCity";
        } else {
            $statText = "visitorsAnalyticsBydate";
        }


        return view("dashboard.analytics.list",
            compact("GeneralWebmasterSections", "daterangepicker_start", "daterangepicker_end",
                "daterangepicker_start_text", "daterangepicker_end_text", "min_visitor_date", "max_visitor_date",
                "stat", "AnalyticsVisitors", "TotalVisitors", "TotalPages", "statText", "AnalyticsValues"));

    }

    public function filter(Request $request, $stat = "date")
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->analytics_status) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List Min & Max
        $AnalyticsMin = AnalyticsVisitor::min("date");
        $AnalyticsMax = AnalyticsVisitor::max("date");


        $daterangepicker_start = date('Y-m-d', strtotime('-29 day'));
        if ($request->this_daterangepicker_start != "") {
            $daterangepicker_start = $request->this_daterangepicker_start;
        }
        $daterangepicker_end = date('Y-m-d');
        if ($request->this_daterangepicker_end != "") {
            $daterangepicker_end = $request->this_daterangepicker_end;
        }
        $daterangepicker_start_text = date("F d , Y", strtotime($daterangepicker_start));
        $daterangepicker_end_text = date("F d , Y", strtotime($daterangepicker_end));
        $min_visitor_date = date('d-m-Y', strtotime('-29 day'));
        if ($AnalyticsMin != "") {
            $min_visitor_date = date('d-m-Y', strtotime($AnalyticsMin));
        }
        $max_visitor_date = date('d-m-Y');
        if ($AnalyticsMax != "") {
            $max_visitor_date = date('d-m-Y', strtotime($AnalyticsMax));
        }


        $AnalyticsValues = array();

        $AnalyticsVisitors = AnalyticsVisitor::select("*")->select($stat)->where('date', '>=', $daterangepicker_start)
            ->where('date', '<=', $daterangepicker_end)
            ->groupBy($stat)
            ->orderBy($stat, 'asc')
            ->get();

        $ix = 0;
        foreach ($AnalyticsVisitors as $AnalyticsV) {

            $TotalV = AnalyticsVisitor::where("$stat", $AnalyticsV->$stat)
                ->where('date', '>=', $daterangepicker_start)
                ->where('date', '<=', $daterangepicker_end)->count();

            $AllVArray = AnalyticsVisitor::select('id')->where("$stat", $AnalyticsV->$stat)
                ->where('date', '>=', $daterangepicker_start)
                ->where('date', '<=', $daterangepicker_end)
                ->get()
                ->toArray();

            $TotalP = AnalyticsPage::whereIn("visitor_id", $AllVArray)->count();

            $newdata = array(
                'name' => $AnalyticsV->$stat,
                'visits' => $TotalV,
                'pages' => $TotalP
            );
            array_push($AnalyticsValues, $newdata);
            $ix++;
        }

        if ($stat == "date") {

        } else {
            usort($AnalyticsValues, function ($a, $b) {
                return $b['visits'] - $a['visits'];
            });
        }

        $TotalVisitors = AnalyticsVisitor::where('date', '>=', $daterangepicker_start)
            ->where('date', '<=', $daterangepicker_end)
            ->count();

        $TotalPages = AnalyticsPage::where('date', '>=', $daterangepicker_start)
            ->where('date', '<=', $daterangepicker_end)
            ->count();

        if ($stat == "org") {
            $statText = "visitorsAnalyticsByOrganization";
        } elseif ($stat == "hostname") {
            $statText = "visitorsAnalyticsByHostName";
        } elseif ($stat == "referrer") {
            $statText = "visitorsAnalyticsByReachWay";
        } elseif ($stat == "resolution") {
            $statText = "visitorsAnalyticsByScreenResolution";
        } elseif ($stat == "browser") {
            $statText = "visitorsAnalyticsByBrowser";
        } elseif ($stat == "os") {
            $statText = "visitorsAnalyticsByOperatingSystem";
        } elseif ($stat == "country") {
            $statText = "visitorsAnalyticsByCountry";
        } elseif ($stat == "city") {
            $statText = "visitorsAnalyticsByCity";
        } else {
            $statText = "visitorsAnalyticsBydate";
        }

        return view("dashboard.analytics.list",
            compact("GeneralWebmasterSections", "daterangepicker_start", "daterangepicker_end",
                "daterangepicker_start_text", "daterangepicker_end_text", "min_visitor_date", "max_visitor_date",
                "stat", "AnalyticsVisitors", "TotalVisitors", "TotalPages", "statText", "AnalyticsValues"));

    }

    public function visitors()
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->analytics_status) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List of Analytics Visitors
        $AnalyticsVisitors = AnalyticsVisitor::orderby('date', 'desc')->orderby('time',
            'desc')->paginate(config('smartend.backend_pagination'));

        return view("dashboard.analytics.visitors", compact("GeneralWebmasterSections", "AnalyticsVisitors"));
    }

    public function ip($ip_code = null)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->analytics_status) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List of Analytics Visitors
        if ($ip_code != "") {
            $AnalyticsVisitors = AnalyticsVisitor::where('ip', $ip_code)
                ->orderby('id', 'desc')->get();
        } else {
            $AnalyticsVisitors = "";
        }

        return view("dashboard.analytics.ip", compact("GeneralWebmasterSections", "AnalyticsVisitors", "ip_code"));
    }

    public function search(Request $request)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->analytics_status) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        if ($request->ip != "") {
            $AnalyticsVisitors = AnalyticsVisitor::where('ip', $request->ip)
                ->orderby('id', 'desc')->get();
        } else {
            $AnalyticsVisitors = "";
        }

        $ip_code = $request->ip;

        return view("dashboard.analytics.ip", compact("GeneralWebmasterSections", "AnalyticsVisitors", "ip_code"));
    }


}
