<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsPage;
use App\Models\AnalyticsVisitor;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Section;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\TopicTag;
use App\Models\Webmail;
use App\Models\WebmasterSection;
use App\Models\WebmasterSectionField;
use Auth;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        if (@Auth::user()->permissionsGroup->view_status) {
            //List of all Webmails
            $Webmails = Webmail::where('created_by', '=', Auth::user()->id)->orderby('id', 'desc')
                ->where('cat_id', '=', 0)->limit(4)->get();

            //List of Events
            $Events = Event::where('created_by', '=', Auth::user()->id)->where('start_date', '>=',
                date('Y-m-d 00:00:00'))->orderby('start_date', 'asc')->limit(5)->get();


            //List of all contacts
            $Contacts = Contact::where('created_by', '=', Auth::user()->id)->orderby('id', 'desc')->limit(5)->get();
        } else {
            //List of all Webmails
            $Webmails = Webmail::orderby('id', 'desc')
                ->where('cat_id', '=', 0)->limit(4)->get();

            //List of Events
            $Events = Event::where('start_date', '>=',
                date('Y-m-d 00:00:00'))->orderby('start_date', 'asc')->limit(5)->get();


            //List of all contacts
            $Contacts = Contact::orderby('id', 'desc')->limit(5)->get();
        }
        // Analytics
        $TodayVisitors = AnalyticsVisitor::where('date', date('Y-m-d'))->count();
        $TodayPages = AnalyticsPage::where('date', date('Y-m-d'))->count();

        // Last 7 Days
        $daterangepicker_start = date('Y-m-d', strtotime('-6 day'));
        $daterangepicker_end = date('Y-m-d');
        $stat = "date";

        $Last7DaysVisitors = array();

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
            array_push($Last7DaysVisitors, $newdata);
            $ix++;
        }

        // Today By Country
        $date_today = date('Y-m-d');
        $stat = "country";

        $TodayByCountry = array();

        $AnalyticsVisitors = AnalyticsVisitor::select("*")->select($stat)->where('date', $date_today)
            ->groupBy($stat)
            ->orderBy($stat, 'asc')
            ->get();
        $ix = 0;
        foreach ($AnalyticsVisitors as $AnalyticsV) {

            $FST = AnalyticsVisitor::where("$stat", $AnalyticsV->$stat)
                ->where('date', $date_today)->orderby("id", "desc")->first();

            $TotalV = AnalyticsVisitor::where("$stat", $AnalyticsV->$stat)
                ->where('date', $date_today)->count();

            $AllVArray = AnalyticsVisitor::select('id')->where("$stat", $AnalyticsV->$stat)
                ->where('date', $date_today)
                ->get()
                ->toArray();

            $TotalP = AnalyticsPage::whereIn("visitor_id", $AllVArray)->count();

            $newdata = array(
                'name' => $AnalyticsV->$stat,
                'code' => substr($FST->country_code, 0, 2),
                'visits' => $TotalV,
                'pages' => $TotalP
            );
            array_push($TodayByCountry, $newdata);
            $ix++;
        }
        usort($TodayByCountry, function ($a, $b) {
            return $b['visits'] - $a['visits'];
        });


        // Today By Browser
        $date_today = date('Y-m-d');
        $stat = "browser";

        $TodayByBrowsers = array();

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

            $newdata = array(
                'name' => $AnalyticsV->$stat,
                'visits' => $TotalV
            );
            array_push($TodayByBrowsers, $newdata);
            $ix++;
        }
        usort($TodayByBrowsers, function ($a, $b) {
            return $b['visits'] - $a['visits'];
        });
        $TodayByBrowser1 = "";
        $TodayByBrowser1_val = 0;
        $TodayByBrowser2 = "Other Browsers";
        $TodayByBrowser2_val = 0;
        $ix = 0;
        $emptyB = 0;
        foreach ($TodayByBrowsers as $TodayByBrowser) {
            $emptyBi = 0;
            if ($emptyB == 0) {
                $emptyBi = $ix;
            }
            if ($ix == $emptyBi) {
                $ix2 = 0;
                foreach ($TodayByBrowser as $key => $val) {
                    if ($ix2 == 0) {
                        $TodayByBrowser1 = $val;
                        if ($TodayByBrowser1 != "") {
                            $emptyB = 1;
                        }
                    }
                    if ($ix2 == 1) {
                        $TodayByBrowser1_val = $val;
                    }
                    $ix2++;
                }
            } else {
                $ixx2 = 0;
                foreach ($TodayByBrowser as $key => $val) {
                    if ($ixx2 == 1) {
                        $TodayByBrowser2_val += $val;
                    }
                    $ixx2++;
                }
            }
            $ix++;
        }

        // Visitor Rate today
        $day_date = date('Y-m-d');
        $TodayVisitorsRate = "";
        $fsla = "";
        for ($ii = 0; $ii < 24; $ii = $ii + 2) {
            if ($ii != 0) {
                $fsla = ", ";
            }
            $stepis = $ii + 2;
            $timeis1 = sprintf("%02d", $ii).":00:00";
            $timeis2 = sprintf("%02d", $stepis).":00:00";
            $TotalV = AnalyticsVisitor::where('date', $day_date)
                ->where('time', '>=', $timeis1)
                ->where('time', '<', $timeis2)
                ->count();
            if ($TotalV == 0) {
                $TotalV = 1;
            }
            $TodayVisitorsRate = $TodayVisitorsRate.$fsla."[$ii,$TotalV]";
        }

        return view('dashboard.home',
            compact("GeneralWebmasterSections", "Webmails", "Events", "Contacts", "TodayVisitors", "TodayPages",
                "Last7DaysVisitors", "TodayByCountry", "TodayByBrowser1", "TodayByBrowser1_val", "TodayByBrowser2",
                "TodayByBrowser2_val", "TodayVisitorsRate"));
    }

    public function search()
    {
        $search_word = \request()->input("q");
        $is_hash = mb_substr(request()->input("q"), 0, 1);
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        $active_tab = 0;
        $Contacts = [];
        $Webmails = [];
        $Events = [];
        $Topics = [];
        $Sections = [];
        if ($search_word != "") {
            if ($is_hash == "#") {
                // search in togs
                $tag_word = mb_substr($search_word, 1);
                $DBTag = Tag::where("title", $tag_word)->first();
                if (!empty($DBTag)) {
                    $TopicIds = TopicTag::where("tag_id", $DBTag->id)->pluck("topic_id")->toArray();
                    //find Topics
                    $Topics = Topic::where("id", ">", 0);
                    if (Auth::user()->permissionsGroup->view_status) {
                        $Topics = $Topics->where('created_by', '=', Auth::user()->id);
                    }
                    $Topics = $Topics->whereIn("id", $TopicIds);
                    $Topics = $Topics->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));
                }
            } else {
                //find Contacts
                $Contacts = Contact::where("id", ">", 0);
                if (Auth::user()->permissionsGroup->view_status) {
                    $Contacts = $Contacts->where('created_by', '=', Auth::user()->id);
                }
                $Contacts = $Contacts->where(function ($query) use ($search_word) {
                    $query->where('first_name', 'like', '%'.$search_word.'%')
                        ->orwhere('last_name', 'like', '%'.$search_word.'%')
                        ->orwhere('company', 'like', '%'.$search_word.'%')
                        ->orwhere('city', 'like', '%'.$search_word.'%')
                        ->orwhere('notes', 'like', '%'.$search_word.'%')
                        ->orwhere('phone', '=', $search_word)
                        ->orwhere('email', '=', $search_word);
                });
                $Contacts = $Contacts->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));

                //find Webmails
                $Webmails = Webmail::where("id", ">", 0);
                if (Auth::user()->permissionsGroup->view_status) {
                    $Webmails = $Webmails->where('created_by', '=', Auth::user()->id);
                }
                $Webmails = $Webmails->where(function ($query) use ($search_word) {
                    $query->where('title', 'like', '%'.$search_word.'%')
                        ->orwhere('from_name', 'like', '%'.$search_word.'%')
                        ->orwhere('from_email', 'like', '%'.$search_word.'%')
                        ->orwhere('from_phone', 'like', '%'.$search_word.'%')
                        ->orwhere('to_email', 'like', '%'.$search_word.'%')
                        ->orwhere('to_name', 'like', '%'.$search_word.'%');
                });
                $Webmails = $Webmails->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));


                //find Events
                $Events = Event::where("id", ">", 0);
                if (Auth::user()->permissionsGroup->view_status) {
                    $Events = $Events->where('created_by', '=', Auth::user()->id);
                }
                $Events = $Events->where(function ($query) use ($search_word) {
                    $query->where('title', 'like', '%'.$search_word.'%')
                        ->orwhere('details', 'like', '%'.$search_word.'%');
                });
                $Events = $Events->orderby('start_date', 'desc')->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));


                //find Topics
                $Topics = Topic::where("id", ">", 0);
                if (Auth::user()->permissionsGroup->view_status) {
                    $Topics = $Topics->where('created_by', '=', Auth::user()->id);
                }
                $Topics = $Topics->where(function ($query) use ($search_word) {
                    $query->where('title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%')
                        ->orwhere('seo_title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%');
                });
                $Topics = $Topics->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));

                //find Sections
                $Sections = Section::where("id", ">", 0);
                if (Auth::user()->permissionsGroup->view_status) {
                    $Sections = $Sections->where('created_by', '=', Auth::user()->id);
                }
                $Sections = $Sections->where(function ($query) use ($search_word) {
                    $query->where('title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%')
                        ->orwhere('seo_title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%');
                });
                $Sections = $Sections->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));
            }

            if (count($Webmails) > 0) {
                $active_tab = 5;
            }
            if (count($Events) > 0) {
                $active_tab = 4;
            }
            if (count($Contacts) > 0) {
                $active_tab = 3;
            }
            if (count($Sections) > 0) {
                $active_tab = 2;
            }
            if (count($Topics) > 0) {
                $active_tab = 1;
            }
        }

        return view("dashboard.search",
            compact("GeneralWebmasterSections", "search_word", "Webmails", "Contacts", "Events", "Topics", "Sections",
                "active_tab"));
    }

    public function cache_cleared()
    {
        return redirect()->action([DashboardController::class, 'index'])->with('doneMessage',
            __('backend.cashClearDone'));
    }

    public function cache_clear()
    {
        // clear cache & views cache
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        // delete cache files manually
        if (\File::exists(base_path("bootstrap/cache/config.php"))) {
            \File::delete(base_path("bootstrap/cache/config.php"));
        }
        if (\File::exists(base_path("bootstrap/cache/routes-v7.php"))) {
            \File::delete(base_path("bootstrap/cache/routes-v7.php"));
        }

        // re cache routes
        Artisan::call('route:cache');
        // re cache config
        Artisan::call('config:cache');

        // remove cache of generated custom image sizes
        try {
            $disk = config('filesystems.default');
            $cacheDir = 'cache';
            if (Storage::disk($disk)->exists($cacheDir)) {
                Storage::disk($disk)->deleteDirectory($cacheDir);
            }
        } catch (\Exception $exception) {

        }


        return redirect()->action([DashboardController::class, 'cache_cleared']);
    }

    public function page_403()
    {
        return view('errors.403');
    }

    public function page_oops()
    {
        return view('dashboard.layouts.oops');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
