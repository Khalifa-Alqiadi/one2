<?php

namespace App\Helpers;


use App\Models\TopicField;
use App\Models\WebmasterSectionField;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Models\AnalyticsPage;
use App\Models\AnalyticsVisitor;
use App\Models\Banner;
use App\Models\Country;
use App\Models\Event;
use App\Models\Fixture;
use App\Models\Menu;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\TopicCategory;
use App\Models\Webmail;
use App\Models\Team;
use App\Models\Language;
use App\Models\WebmasterSection;
use App\Models\WebmasterSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Auth;
use GeoIP;
use Spatie\Image\Image;
use Newsletter;
use Illuminate\Support\Facades\Http;

class Helper
{
    static function system_version()
    {
        return Helper::GeneralWebmasterSettings("version");
    }

    static function GeneralWebmasterSettings($var)
    {
        $_Loader_WebmasterSettings = Cache::remember('_Loader_WebmasterSettings', 60 * 24, function () {
            return WebmasterSetting::find(1);
        });
        return @$_Loader_WebmasterSettings->$var;
    }

    static function GeneralSiteSettings($var)
    {
        $_Loader_Web_Settings = Cache::remember('_Loader_Web_Settings', 60 * 24, function () {
            return Setting::find(1);
        });
        return @$_Loader_Web_Settings->$var;
    }

    static function eventsAlerts()
    {
        $_Loader_Events = Cache::remember('_Loader_Events', 60 * 24, function () {
            return Event::where('start_date', '>=', date('Y-m-d H:i:s'))->orderby(
                'start_date',
                'asc'
            )->limit(10)->get();
        });
        if (@Auth::user()->permissionsGroup->view_status) {
            return $_Loader_Events->where('created_by', '=', Auth::user()->id);
        } else {
            return $_Loader_Events;
        }
    }

    static function webmailsAlerts()
    {
        $Webmails = Cache::remember('_Loader_Webmails', 60 * 24, function () {
            return Webmail::orderby('id', 'desc')->where('status', '=', 0)->where('cat_id', '=', 0)->get();
        });
        if (@Auth::user()->permissionsGroup->view_status) {
            return $Webmails->where('created_by', '=', Auth::user()->id)->take(5);
        } else {
            return $Webmails->take(5);
        }
    }

    static function webmailsNewCount()
    {
        $Webmails = Cache::remember('_Loader_Webmails', 60 * 24, function () {
            return Webmail::orderby('id', 'desc')->where('status', '=', 0)->where('cat_id', '=', 0)->get();
        });
        if (@Auth::user()->permissionsGroup->view_status) {
            return $Webmails->where('created_by', '=', Auth::user()->id)->count();
        } else {
            return $Webmails->count();
        }
    }

    static function BannersList($BannersSettingsId)
    {
        $Banners = Cache::remember('_Loader_BannersList', 60 * 24, function () {
            return Banner::where('status', 1)->orderby('row_no', 'asc')->get();
        });
        return $Banners->where('section_id', $BannersSettingsId);
    }

    static function MenuList($GroupId)
    {
        $Menus = Cache::remember('_Loader_MenuList', 60 * 24, function () {
            return Menu::where('status', 1)->orderby('row_no', 'asc')->get();
        });
        return $Menus->where('father_id', $GroupId);
    }

    static function getBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if (preg_match('/Trident\/(\d+\.\d+)/', $userAgent, $matches)) {
            $version = intval($matches[1]) + 4;
            return 'Internet Explorer ' . $version;
        }

        if (preg_match('/MSIE (\d+\.\d+)/', $userAgent, $matches)) {
            return 'Internet Explorer ' . intval($matches[1]);
        }

        if (preg_match('/Edg\/(\d+\.\d+)/', $userAgent, $matches)) {
            return 'Microsoft Edge ' . $matches[1];
        }

        if (preg_match('/OPR\/(\d+\.\d+)/', $userAgent, $matches)) {
            return 'Opera ' . $matches[1];
        }

        if (preg_match('/Chrome\/(\d+\.\d+)/', $userAgent, $matches)) {
            return 'Chrome ' . $matches[1];
        }

        if (preg_match('/Firefox\/(\d+\.\d+)/', $userAgent, $matches)) {
            return 'Firefox ' . $matches[1];
        }

        if (preg_match('/Safari\/(\d+\.\d+)/', $userAgent, $matches) && !preg_match('/Chrome/', $userAgent)) {
            return 'Safari ' . $matches[1];
        }

        return 'Unknown';
    }

    static function getOS()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $osArray = [
            '/windows nt 10.0/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/iphone/i' => 'iOS (iPhone)',
            '/ipad/i' => 'iOS (iPad)',
            '/ipod/i' => 'iOS (iPod)',
            '/android/i' => 'Android',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile (webOS)',
        ];

        foreach ($osArray as $pattern => $os) {
            if (preg_match($pattern, $userAgent)) {
                return $os;
            }
        }

        return 'Unknown';
    }

    static function getUserIP()
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $key) {
            if (!empty($_SERVER[$key])) {
                $ipList = explode(',', $_SERVER[$key]);
                return trim($ipList[0]);
            }
        }

        return '0.0.0.0';
    }

    static function SaveVisitorInfo($PageTitle)
    {
        if (config('smartend.geoip_status')) {
            $visitor_ip = Helper::getUserIP();
            $current_page_full_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $page_load_time = round((microtime(true) - LARAVEL_START), 8);

            // Check is it already saved today to visitors?
            $SavedVisitor = AnalyticsVisitor::where('ip', '=', $visitor_ip)->where('date', '=', date('Y-m-d'))->first();
            if (empty($SavedVisitor) || @$SavedVisitor->country == "unknown") {

                // New to analyticsVisitors
                try {
                    $visitor_local_ip_details = AnalyticsVisitor::where('ip', $visitor_ip)->first();
                    if (!empty($visitor_local_ip_details)) {
                        $visitor_city = $visitor_local_ip_details->city;
                        $visitor_region = $visitor_local_ip_details->region;
                        $visitor_country_code = $visitor_local_ip_details->country_code;
                        $visitor_country = $visitor_local_ip_details->country;
                        $visitor_loc_0 = $visitor_local_ip_details->location_cor1;
                        $visitor_loc_1 = $visitor_local_ip_details->location_cor2;
                        $visitor_org = $visitor_local_ip_details->org;
                        $visitor_hostname = $visitor_local_ip_details->hostname;
                    } else {

                        $visitor_ip_details = [];
                        try {
                            $visitor_ip_details = GeoIP($visitor_ip);
                        } catch (\Exception $e) {
                        }

                        $visitor_city = @$visitor_ip_details->city;
                        if ($visitor_city == "") {
                            $visitor_city = "unknown";
                        }
                        $visitor_region = @$visitor_ip_details->state_name;
                        if ($visitor_region == "") {
                            $visitor_region = "unknown";
                        }
                        $visitor_country_code = @$visitor_ip_details->iso_code;
                        if ($visitor_country_code == "") {
                            $visitor_country_code = "unknown";
                        }
                        $visitor_country = @$visitor_ip_details->country;
                        if ($visitor_country == "") {
                            $visitor_country = "unknown";
                        }

                        $visitor_loc_0 = @$visitor_ip_details->lat;
                        if ($visitor_loc_0 == "") {
                            $visitor_loc_0 = "unknown";
                        }
                        $visitor_loc_1 = @$visitor_ip_details->lon;
                        if ($visitor_loc_1 == "") {
                            $visitor_loc_1 = "unknown";
                        }

                        $visitor_org = @$visitor_ip_details->timezone;
                        if ($visitor_org == "") {
                            $visitor_org = "unknown";
                        }
                        $visitor_hostname = @$visitor_ip_details->continent;
                        if ($visitor_hostname == "") {
                            $visitor_hostname = "unknown";
                        }
                    }
                } catch (\Exception $e) {
                    $visitor_city = "unknown";
                    $visitor_region = "unknown";
                    $visitor_country_code = "unknown";
                    $visitor_country = "unknown";
                    $visitor_loc_0 = "unknown";
                    $visitor_loc_1 = "unknown";
                    $visitor_org = "unknown";
                    $visitor_hostname = "unknown";
                }

                $visitor_referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "unknown";
                $visitor_browser = Helper::getBrowser();
                $visitor_os = Helper::getOS();
                $visitor_screen_res = "unknown";

                // Start saving to database
                $Visitor = new AnalyticsVisitor;
                $Visitor->ip = $visitor_ip;
                $Visitor->city = $visitor_city;
                $Visitor->country_code = $visitor_country_code;
                $Visitor->country = $visitor_country;
                $Visitor->region = $visitor_region;
                $Visitor->location_cor1 = $visitor_loc_0;
                $Visitor->location_cor2 = $visitor_loc_1;
                $Visitor->os = $visitor_os;
                $Visitor->browser = $visitor_browser;
                $Visitor->resolution = $visitor_screen_res;
                $Visitor->referrer = $visitor_referrer;
                $Visitor->hostname = $visitor_hostname;
                $Visitor->org = $visitor_org;
                $Visitor->date = date('Y-m-d');
                $Visitor->time = date('H:i:s');
                $Visitor->save();

                // Start saving page info to database
                $VisitedPage = new AnalyticsPage;
                $VisitedPage->visitor_id = $Visitor->id;
                $VisitedPage->ip = $visitor_ip;
                $VisitedPage->title = $PageTitle;
                $VisitedPage->name = "unknown";
                $VisitedPage->query = $current_page_full_link;
                $VisitedPage->load_time = $page_load_time;
                $VisitedPage->date = date('Y-m-d');
                $VisitedPage->time = date('H:i:s');
                $VisitedPage->save();
            } else {
                // Already Saved to analyticsVisitors
                // Check if page saved
                $Savedpage = AnalyticsPage::where('visitor_id', '=', $SavedVisitor->id)->where(
                    'ip',
                    '=',
                    $visitor_ip
                )->where('date', '=', date('Y-m-d'))->where(
                    'query',
                    '=',
                    $current_page_full_link
                )->first();
                if (empty($Savedpage)) {
                    $VisitedPage = new AnalyticsPage;
                    $VisitedPage->visitor_id = $SavedVisitor->id;
                    $VisitedPage->ip = $visitor_ip;
                    $VisitedPage->title = $PageTitle;
                    $VisitedPage->name = "unknown";
                    $VisitedPage->query = $current_page_full_link;
                    $VisitedPage->load_time = $page_load_time;
                    $VisitedPage->date = date('Y-m-d');
                    $VisitedPage->time = date('H:i:s');
                    $VisitedPage->save();
                }
            }
        }
    }

    static function Get_youtube_video_id($url)
    {
        if (preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url)) {
            $pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
            preg_match($pattern, $url, $matches);
            if (count($matches) && strlen($matches[7]) == 11) {
                return $matches[7];
            }
        }

        return '';
    }

    static function Get_vimeo_video_id($url)
    {
        if (preg_match('/vimeo\.com/i', $url)) {
            $pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
            preg_match($pattern, $url, $matches);
            if (count($matches)) {
                return $matches[2];
            }
        }

        return '';
    }

    static function SocialShare($social, $title)
    {
        $shareLink = "";
        $URL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        switch ($social) {
            case "facebook":
                $shareLink = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($URL);
                break;
            case "twitter":
                $shareLink = "https://twitter.com/share?text=$title&url=" . urlencode($URL);
                break;
            case "linkedin":
                $shareLink = "http://www.linkedin.com/shareArticle?mini=true&url=" . urlencode($URL) . "&title=$title";
                break;
            case "tumblr":
                $shareLink = "http://www.tumblr.com/share/link?url=" . urlencode($URL);
                break;
            case "whatsapp":
                $shareLink = "whatsapp://send?text=" . urlencode($URL);
                break;
        }

        return $shareLink;
    }

    static function GetIcon($path, $size = "24px", $bootstrap = 4)
    {
        $ext = strtolower("." . pathinfo($path, PATHINFO_EXTENSION));
        $ico = "fa";
        if ($bootstrap == 5) {
            $ico = "fa-solid";
        }
        $icon = "<i class=\"fa fa-file-o\"></i>";
        if ($ext == ".pdf") {
            $icon = "<i class=\"" . $ico . " fa-file-pdf-o\" style='color: red;font-size: " . $size . "'></i>";
        }
        if ($ext == '.png' or $ext == '.jpg' or $ext == '.jpeg' or $ext == '.gif') {
            $icon = "<img src='$path' style='width: auto;height: " . $size . "' alt=''>";
        }
        if ($ext == ".xls" or $ext == '.xlsx') {
            $icon = "<i class=\"" . $ico . " fa-file-excel-o\" style='color: green;font-size: " . $size . "'></i>";
        }
        if ($ext == ".ppt" or $ext == '.pptx' or $ext == '.pptm') {
            $icon = "<i class=\"" . $ico . " fa-file-powerpoint-o\" style='color: #1066E7;font-size:" . $size . "'></i>";
        }
        if ($ext == ".doc" or $ext == '.docx') {
            $icon = "<i class=\"" . $ico . " fa-file-word-o\" style='color: #0EA8DD;font-size: " . $size . "'></i>";
        }
        if ($ext == ".zip" or $ext == '.rar') {
            $icon = "<i class=\"" . $ico . " fa-file-zip-o\" style='color: #C8841D;font-size: " . $size . "'></i>";
        }
        if ($ext == ".txt" or $ext == '.rtf') {
            $icon = "<i class=\"" . $ico . " fa-file-text-o\" style='color: #7573AA;font-size: " . $size . "'></i>";
        }
        if ($ext == ".mp3" or $ext == '.wav') {
            $icon = "<i class=\"" . $ico . " fa-file-audio-o\" style='color: #8EA657;font-size: " . $size . "'></i>";
        }
        if ($ext == ".mp4" or $ext == '.avi') {
            $icon = "<i class=\"" . $ico . " fa-file-video-o\" style='color: #D30789;font-size: " . $size . "'></i>";
        }
        return $icon;
    }

    static function StringToSlug($string = "")
    {
        if (empty($string)) {
            return "";
        }
        try {
            $separator = "-";
            $string = mb_strtolower($string, 'UTF-8');
            $string = str_replace(["/", "&", "?"], $separator, $string);
            $string = preg_replace('/[^\p{L}\p{N}\s' . preg_quote($separator, '/') . ']+/u', '', $string);
            $string = preg_replace('/[' . preg_quote($separator, '/') . '\s]+/u', $separator, $string);
            return trim($string, $separator);
        } catch (\Exception $e) {
            return "";
        }
    }

    static function SlugToString($slug = "")
    {
        if ($slug != "") {
            return @str_replace("-", " ", $slug);
        }
        return "";
    }


    static function URLSlug($url, $type = "", $id = 0, $num = 0)
    {
        $Check_SEO_st = true;
        if (Helper::GeneralWebmasterSettings("slug_translation")) {
            $seo_url_slug = Helper::StringToSlug($url);
        } else {
            $seo_url_slug = Str::slug($url, '_');
        }

        $ReservedURLs = array(
            "search",
            "comment",
            "order",
            "sitemap"
        );
        $LanguagesList = Helper::languagesList();
        if (count($LanguagesList) > 0) {
            if ($type == "section") {
                if ($id > 0) {
                    $check_WebmasterSection = WebmasterSection::where('id', '!=', $id);
                } else {
                    $check_WebmasterSection = WebmasterSection::where('id', '!=', 0);
                }
                $check_WebmasterSection = $check_WebmasterSection->where(function ($query) use (
                    $LanguagesList,
                    $seo_url_slug
                ) {
                    $i = 0;
                    foreach ($LanguagesList as $ActiveLanguage) {
                        if ($i == 0) {
                            $query->where('seo_url_slug_' . $ActiveLanguage->code, $seo_url_slug);
                        } else {
                            $query->orWhere('seo_url_slug_' . $ActiveLanguage->code, $seo_url_slug);
                        }
                        $i++;
                    }
                });
                $check_WebmasterSection_count = $check_WebmasterSection->count();
                if ($check_WebmasterSection_count > 0) {
                    $Check_SEO_st = false;
                }
            }

            if ($type == "category") {
                if ($id > 0) {
                    $check_Section = Section::where('id', '!=', $id);
                } else {
                    $check_Section = Section::where('id', '!=', 0);
                }
                $check_Section = $check_Section->where(function ($query) use ($LanguagesList, $seo_url_slug) {
                    $i = 0;
                    foreach ($LanguagesList as $ActiveLanguage) {
                        if ($i == 0) {
                            $query->where('seo_url_slug_' . $ActiveLanguage->code, $seo_url_slug);
                        } else {
                            $query->orWhere('seo_url_slug_' . $ActiveLanguage->code, $seo_url_slug);
                        }
                        $i++;
                    }
                });
                $check_Section_count = $check_Section->count();
                if ($check_Section_count > 0) {
                    $Check_SEO_st = false;
                }
            }

            if ($type == "topic") {
                if ($id > 0) {
                    $check_Topic = Topic::where('id', '!=', $id);
                } else {
                    $check_Topic = Topic::where('id', '!=', 0);
                }
                $check_Topic = $check_Topic->where(function ($query) use ($LanguagesList, $seo_url_slug) {
                    $i = 0;
                    foreach ($LanguagesList as $ActiveLanguage) {
                        if ($i == 0) {
                            $query->where('seo_url_slug_' . $ActiveLanguage->code, $seo_url_slug);
                        } else {
                            $query->orWhere('seo_url_slug_' . $ActiveLanguage->code, $seo_url_slug);
                        }
                        $i++;
                    }
                });
                $check_Topic_count = $check_Topic->count();
                if ($check_Topic_count > 0) {
                    $Check_SEO_st = false;
                }
            }
        }

        if (in_array($seo_url_slug, $ReservedURLs)) {
            $Check_SEO_st = false;
        }

        if ($seo_url_slug == "") {
            $Check_SEO_st = true;
        }

        if ($Check_SEO_st) {
            return $seo_url_slug;
        } else {
            $url = preg_replace('/-' . $num . '$/', '', $url);
            $num++;
            $url = $url . "-" . $num;
            return Helper::URLSlug($url, $type, $id, $num);
        }
    }

    static function currentLanguage()
    {
        $locale = App::getLocale();
        if (session('locale', '') != "") {
            $locale = Session('locale');
        }
        $_Loader_Languages = Cache::remember('_Loader_Languages', 60 * 24, function () {
            return Language::all();
        });
        $Language = $_Loader_Languages->first(function ($item) use ($locale) {
            return $item->code == $locale;
        });
        if (empty($Language)) {
            $Language = Language::where("code", config('smartend.default_language'))->first();
        }
        return $Language;
    }

    static function LangFromCode($code)
    {
        return Language::where("code", $code)->first();
    }

    static function languagesList()
    {
        $_Loader_Languages = Cache::remember('_Loader_Languages', 60 * 24, function () {
            return Language::all();
        });
        return $_Loader_Languages->where("status", true);
    }

    static function languageName($Language)
    {
        if (@count(Helper::languagesList()) > 1) {
            $language_title = "<span class='label light text-dark lang-label'>";
            if (!empty($Language)) {
                if ($Language->icon != "") {
                    $language_title .= "<img src=\"" . asset('assets/dashboard/images/flags/' . $Language->icon . '.svg') . "\" alt=\"\">";
                }
                $language_title .= " <small>" . $Language->title . "</small></span>";
            }
            return $language_title;
        }
    }

    static function languageURL($lang_code, $page_type = "", $page_id = 0)
    {
        $lang_url = URL::to('lang/' . $lang_code);
        if (@$page_type == "section" && @$page_id > 0) {
            $lang_url = Helper::sectionURL(@$page_id, $lang_code);
        } elseif (@$page_type == "category" && @$page_id > 0) {
            $lang_url = Helper::categoryURL(@$page_id, $lang_code);
        } elseif (@$page_type == "topic" && @$page_id > 0) {
            $lang_url = Helper::topicURL(@$page_id, $lang_code);
        } elseif (@$page_type == "tag" && @$page_id > 0) {
            $lang_url = Helper::tagURL(@$page_id, $lang_code);
        } elseif (@$page_type == "404") {
            $lang_url = route("NotFound", ['lang' => $lang_code]);
        } elseif (@$page_type == "home") {
            if ($lang_code != config('smartend.default_language')) {
                $lang_url = URL::to($lang_code);
            } else {
                $lang_url = URL::to("/");
            }
        }
        return $lang_url;
    }

    static function canonicalURL($page_type = "", $page_id = 0)
    {
        $lang_code = @Helper::currentLanguage()->code;
        $canonical_url = url()->current();
        if (@$page_type == "section" && @$page_id > 0) {
            $canonical_url = Helper::sectionURL(@$page_id, $lang_code);
        } elseif (@$page_type == "category" && @$page_id > 0) {
            $canonical_url = Helper::categoryURL(@$page_id, $lang_code);
        } elseif (@$page_type == "topic" && @$page_id > 0) {
            $canonical_url = Helper::topicURL(@$page_id, $lang_code);
        } elseif (@$page_type == "404") {
            $canonical_url = route("NotFound");
        } elseif (@$page_type == "home") {
            if ($lang_code != config('smartend.default_language')) {
                $canonical_url = URL::to($lang_code);
            } else {
                $canonical_url = URL::to("/");
            }
        }
        return $canonical_url;
    }

    static function homeURL()
    {
        $lang = @Helper::currentLanguage()->code;
        if ($lang == config('smartend.default_language')) {
            return route("frontendRoute");
        }
        return route("frontendRoute", ["part1" => $lang]);
    }

    static function sectionURL($id, $lang = "", $WebmasterSection = [])
    {
        $section_url = "";
        try {
            if ($lang == "") {
                $lang = @Helper::currentLanguage()->code;
            }
            $title_var = "title_" . $lang;
            if (empty($WebmasterSection)) {
                $_Loader_WebmasterSections = Cache::remember('_Loader_WebmasterSections', 60 * 24, function () {
                    return WebmasterSection::all();
                });
                $WebmasterSection = $_Loader_WebmasterSections->first(function ($item) use ($id) {
                    return $item->id == $id;
                });
            }
            if (!empty($WebmasterSection)) {
                if ($WebmasterSection->{'seo_url_slug_' . $lang} != "") {
                    $slug = $WebmasterSection->{'seo_url_slug_' . $lang};
                } else {
                    $slug = $WebmasterSection->{'seo_url_slug_' . config('smartend.default_language')};
                }
                if ($slug == "") {
                    $slug = Str::slug($WebmasterSection->$title_var, '-');
                }
                if ($lang != config('smartend.default_language')) {
                    $section_url = url($lang . "/" . $slug);
                } else {
                    $section_url = url($slug);
                }
            }
        } catch (\Exception $e) {
        }
        return $section_url;
    }

    static function categoryURL($id, $lang = "", $Category = [])
    {
        $category_url = "";
        try {
            if ($lang == "") {
                $lang = @Helper::currentLanguage()->code;
            }
            $title_var = "title_" . $lang;
            if (empty($Category)) {
                $Category = Section::find($id);
            }
            if (!empty($Category)) {
                if ($Category->{'seo_url_slug_' . $lang} != "") {
                    $cat_slug = $Category->{'seo_url_slug_' . $lang};
                } else {
                    $cat_slug = $Category->{'seo_url_slug_' . config('smartend.default_language')};
                }
                if ($cat_slug == "") {
                    $cat_slug = Str::slug($Category->$title_var, '-');
                }

                $WebmasterSection_slug = "NULL";
                $WebmasterSection = $Category->WebmasterSection;
                if (!empty($WebmasterSection)) {
                    if ($WebmasterSection->{'seo_url_slug_' . $lang} != "") {
                        $WebmasterSection_slug = $WebmasterSection->{'seo_url_slug_' . $lang};
                    } else {
                        $WebmasterSection_slug = $WebmasterSection->{'seo_url_slug_' . config('smartend.default_language')};
                    }
                    if ($WebmasterSection_slug == "") {
                        $WebmasterSection_slug = Str::slug($WebmasterSection->$title_var, '-');
                    }
                }

                $fatherSection2 = @$Category->fatherSection;
                if (!empty($fatherSection2)) {
                    if ($fatherSection2->{'seo_url_slug_' . $lang} != "") {
                        $cat2_slug = $fatherSection2->{'seo_url_slug_' . $lang};
                    } else {
                        $cat2_slug = $fatherSection2->{'seo_url_slug_' . config('smartend.default_language')};
                    }
                    if ($cat2_slug == "") {
                        $cat2_slug = Str::slug($fatherSection2->$title_var, '-');
                    }

                    $fatherSection1 = @$fatherSection2->fatherSection;
                    if (!empty($fatherSection1)) {
                        // is level 3
                        if ($fatherSection1->{'seo_url_slug_' . $lang} != "") {
                            $cat1_slug = $fatherSection1->{'seo_url_slug_' . $lang};
                        } else {
                            $cat1_slug = $fatherSection1->{'seo_url_slug_' . config('smartend.default_language')};
                        }
                        if ($cat1_slug == "") {
                            $cat1_slug = Str::slug($fatherSection1->$title_var, '-');
                        }
                        if ($lang != config('smartend.default_language')) {
                            $category_url = url($lang . "/" . $WebmasterSection_slug . "/" . $cat1_slug . "/" . $cat2_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $cat_slug);
                        } else {
                            $category_url = url($WebmasterSection_slug . "/" . $cat1_slug . "/" . $cat2_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $cat_slug);
                        }
                    } else {
                        // is level 2
                        if ($lang != config('smartend.default_language')) {
                            $category_url = url($lang . "/" . $WebmasterSection_slug . "/" . $cat2_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $cat_slug);
                        } else {
                            $category_url = url($WebmasterSection_slug . "/" . $cat2_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $cat_slug);
                        }
                    }
                } else {
                    // is level 1
                    if ($lang != config('smartend.default_language')) {
                        $category_url = url($lang . "/" . $WebmasterSection_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $cat_slug);
                    } else {
                        $category_url = url($WebmasterSection_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $cat_slug);
                    }
                }
            }
        } catch (\Exception $e) {
        }
        return $category_url;
    }

    static function topicURL($id, $lang = "", $Topic = [])
    {
        $topic_url = "";
        try {
            if ($lang == "") {
                $lang = @Helper::currentLanguage()->code;
            }
            $title_var = "title_" . $lang;
            if (empty($Topic)) {
                $Topic = Topic::find($id);
            }
            if (!empty($Topic)) {

                // for home if it is a landing page
                if (Helper::GeneralWebmasterSettings("homepage_type") == 1 && @$Topic->id == Helper::GeneralWebmasterSettings("landing_page_id")) {
                    if ($lang != config('smartend.default_language')) {
                        return url($lang);
                    } else {
                        return url("");
                    }
                }

                if ($Topic->{'seo_url_slug_' . $lang} != "") {
                    $topic_slug = $Topic->{'seo_url_slug_' . $lang};
                } else {
                    $topic_slug = $Topic->{'seo_url_slug_' . config('smartend.default_language')};
                }
                if ($topic_slug == "") {
                    $topic_slug = Str::slug($Topic->$title_var, '-');
                }
                $WebmasterSection_slug = "NULL";
                $WebmasterSection = $Topic->WebmasterSection;

                if ($Topic->webmaster_id == 1 || @$WebmasterSection->type == 10) {
                    if ($lang != config('smartend.default_language')) {
                        $topic_url = url($lang . "/" . $topic_slug);
                    } else {
                        $topic_url = url($topic_slug);
                    }
                    return $topic_url;
                }

                if (!empty($WebmasterSection)) {
                    if ($WebmasterSection->{'seo_url_slug_' . $lang} != "") {
                        $WebmasterSection_slug = $WebmasterSection->{'seo_url_slug_' . $lang};
                    } else {
                        $WebmasterSection_slug = $WebmasterSection->{'seo_url_slug_' . config('smartend.default_language')};
                    }
                    if ($WebmasterSection_slug == "") {
                        $WebmasterSection_slug = Str::slug($WebmasterSection->$title_var, '-');
                    }
                }

                $Category = [];
                $TopicCategory = TopicCategory::where('topic_id', $Topic->id)->first();
                if (!empty($TopicCategory)) {
                    $Category = Section::find($TopicCategory->section_id);
                }

                if (!empty($Category)) {
                    if ($Category->{'seo_url_slug_' . $lang} != "") {
                        $cat_slug = $Category->{'seo_url_slug_' . $lang};
                    } else {
                        $cat_slug = $Category->{'seo_url_slug_' . config('smartend.default_language')};
                    }
                    if ($cat_slug == "") {
                        $cat_slug = Str::slug($Category->$title_var, '-');
                    }

                    $fatherSection2 = @$Category->fatherSection;
                    if (!empty($fatherSection2)) {
                        if ($fatherSection2->{'seo_url_slug_' . $lang} != "") {
                            $cat2_slug = $fatherSection2->{'seo_url_slug_' . $lang};
                        } else {
                            $cat2_slug = $fatherSection2->{'seo_url_slug_' . config('smartend.default_language')};
                        }
                        if ($cat2_slug == "") {
                            $cat2_slug = Str::slug($fatherSection2->$title_var, '-');
                        }

                        $fatherSection1 = @$fatherSection2->fatherSection;
                        if (!empty($fatherSection1)) {
                            // is level 3
                            if ($fatherSection1->{'seo_url_slug_' . $lang} != "") {
                                $cat1_slug = $fatherSection1->{'seo_url_slug_' . $lang};
                            } else {
                                $cat1_slug = $fatherSection1->{'seo_url_slug_' . config('smartend.default_language')};
                            }
                            if ($cat1_slug == "") {
                                $cat1_slug = Str::slug($fatherSection1->$title_var, '-');
                            }
                            if ($lang != config('smartend.default_language')) {
                                if (@$WebmasterSection->type == 9) {
                                    $topic_url = url($lang . "/" . $WebmasterSection_slug . "#" . $topic_slug);
                                } else {
                                    $topic_url = url($lang . "/" . $WebmasterSection_slug . "/" . $cat1_slug . "/" . $cat2_slug . "/" . $cat_slug . "/" . $topic_slug);
                                }
                            } else {
                                if (@$WebmasterSection->type == 9) {
                                    $topic_url = url($WebmasterSection_slug . "#" . $topic_slug);
                                } else {
                                    $topic_url = url($WebmasterSection_slug . "/" . $cat1_slug . "/" . $cat2_slug . "/" . $cat_slug . "/" . $topic_slug);
                                }
                            }
                        } else {
                            // is level 2
                            if ($lang != config('smartend.default_language')) {
                                if (@$WebmasterSection->type == 9) {
                                    $topic_url = url($lang . "/" . $WebmasterSection_slug . "#" . $topic_slug);
                                } else {
                                    $topic_url = url($lang . "/" . $WebmasterSection_slug . "/" . $cat2_slug . "/" . $cat_slug . "/" . $topic_slug);
                                }
                            } else {
                                if (@$WebmasterSection->type == 9) {
                                    $topic_url = url($WebmasterSection_slug . "#" . $topic_slug);
                                } else {
                                    $topic_url = url($WebmasterSection_slug . "/" . $cat2_slug . "/" . $cat_slug . "/" . $topic_slug);
                                }
                            }
                        }
                    } else {
                        // is level 1
                        if ($lang != config('smartend.default_language')) {
                            if (@$WebmasterSection->type == 9) {
                                $topic_url = url($lang . "/" . $WebmasterSection_slug . "#" . $topic_slug);
                            } else {
                                $topic_url = url($lang . "/" . $WebmasterSection_slug . "/" . $cat_slug . "/" . $topic_slug);
                            }
                        } else {
                            if (@$WebmasterSection->type == 9) {
                                $topic_url = url($WebmasterSection_slug . "#" . $topic_slug);
                            } else {
                                $topic_url = url($WebmasterSection_slug . "/" . $cat_slug . "/" . $topic_slug);
                            }
                        }
                    }
                } else {
                    if ($lang != config('smartend.default_language')) {
                        $topic_url = url($lang . "/" . $WebmasterSection_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $topic_slug);
                    } else {
                        $topic_url = url($WebmasterSection_slug . ((@$WebmasterSection->type == 9) ? "#" : "/") . $topic_slug);
                    }
                }
            }
        } catch (\Exception $e) {
        }
        return $topic_url;
    }

    static function tagURL($id, $lang = "", $DBTag = [])
    {
        $tag_url = "";
        try {
            if ($lang == "") {
                $lang = @Helper::currentLanguage()->code;
            }
            if (empty($DBTag)) {
                $DBTag = Tag::find($id);
            }

            if (!empty($DBTag)) {
                $slug = $DBTag->seo_url;
                if ($slug == "") {
                    $slug = Str::slug($DBTag->title, '-');
                }
                $tag_url = route("tag", $slug) . "?lang=" . $lang;
            }
        } catch (\Exception $e) {
        }
        return $tag_url;
    }

    static function formatDate($date = "")
    {
        if ($date != "") {
            $format = config('smartend.date_format');
            return date($format, strtotime($date));
        }
        return "";
    }

    static function dateForDB($date = "", $withTime = 0)
    {
        if ($date != "") {
            try {
                $format = config('smartend.date_format');
                if ($withTime) {
                    return Carbon::createFromFormat($format . " h:i A", $date)->format('Y-m-d H:i:s');
                } else {
                    return Carbon::createFromFormat($format, $date)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                return $date;
            }
        }
        return "";
    }

    static function jsDateFormat()
    {
        $format = config('smartend.date_format');
        $format = str_replace("Y", "YYYY", $format);
        $format = str_replace("m", "MM", $format);
        $format = str_replace("d", "DD", $format);
        return $format;
    }

    static function WebmasterSection($Id)
    {
        return WebmasterSection::find($Id);
    }

    static function SectionCategories($Id, $limit = 0, $CatIds = "")
    {
        $Categories = Section::where('webmaster_id', '=', $Id);
        if ($CatIds == "" || $CatIds == "0") {
            $CatIds = [];
        } else {
            $CatIds = explode(",", $CatIds);
        }
        if (count($CatIds) > 0) {
            $Categories = $Categories->whereIn("id", $CatIds);
        } else {
            $Categories = $Categories->where('father_id', '=', '0');
        }
        if ($limit > 0) {
            $Categories = $Categories->limit($limit);
        }
        return $Categories->orderby('row_no', 'asc')->get();
    }

    static function ParseLinks($str)
    {
        $target = ' target="_blank"';
        $str = preg_replace(
            '@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.~]*(\?\S+)?)?)*)@',
            '<a href="$1" ' . $target . '>$1</a>',
            $str
        );
        $str = preg_replace(
            '/<a\s[^>]*href\s*=\s*"((?!https?:\/\/)[^"]*)"[^>]*>/i',
            '<a href="http://$1" ' . $target . '>',
            $str
        );
        return $str;
    }

    static function Topic($id)
    {
        return Topic::where("status", 1)->where(function ($query) {
            $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere(
                'expire_date',
                null
            );
        })->find($id);
    }

    static function Topics($SectionId, $CatIds = "", $limit = 12, $random = 0, $custom_order = 0)
    {
        try {
            $Topics = Topic::where("status", 1)->where("webmaster_id", $SectionId)->where(function ($query) {
                $query->where([
                    ['expire_date', '>=', date("Y-m-d")],
                    ['expire_date', '<>', null]
                ])->orWhere('expire_date', null);
            });
            if ($CatIds == "" || $CatIds == "0") {
                $CatIds = [];
            } else {
                $CatIds = explode(",", $CatIds);
            }
            if (count($CatIds) > 0) {
                $Topics = $Topics->whereIn(
                    "id",
                    TopicCategory::whereIn('section_id', $CatIds)->pluck("topic_id")->toarray()
                );
            }
            if ($random) {
                $Topics = $Topics->inRandomOrder();
            } elseif ($custom_order > 0) {
                switch ($custom_order) {
                    case 4:
                        $Topics = $Topics->inRandomOrder();
                        break;
                    case 3:
                        $Topics = $Topics->orderby('date', "asc")->orderby('id', "asc");
                        break;
                    case 2:
                        $Topics = $Topics->orderby('date', "desc")->orderby('id', "desc");
                        break;
                    case 1:
                        $Topics = $Topics->orderby('featured', 'desc')->orderby('date', "desc")->orderby('id', "desc");
                        break;
                }
            } else {
                $Topics = $Topics->orderby('featured', 'desc')->orderby(
                    'date',
                    config('smartend.frontend_topics_order')
                )->orderby('id', config('smartend.frontend_topics_order'));
            }
            if ($limit > 0) {
                $Topics = $Topics->limit($limit);
            }
            $Topics = $Topics->get();
        } catch (\Exception $e) {
            $Topics = [];
        }
        return $Topics;
    }

    static function colorHexToRGB($color, $opacity = 1): string
    {
        $color = str_replace("#", "", $color);
        $parts = str_split($color, 2);
        $p1 = @hexdec(@$parts[0]);
        $p2 = @hexdec(@$parts[1]);
        $p3 = @hexdec(@$parts[2]);
        return "rgba(" . $p1 . "," . $p2 . "," . $p3 . "," . $opacity . ")";
    }

    static function colorHexToDarken($color, $darker = 2)
    {

        $hash = (strpos($color, '#') !== false) ? '#' : '';
        $color = (strlen($color) == 7) ? str_replace('#', '', $color) : ((strlen($color) == 6) ? $color : false);
        if (strlen($color) != 6) {
            return $hash . '000000';
        }
        $darker = ($darker > 1) ? $darker : 1;

        list($R16, $G16, $B16) = str_split($color, 2);

        $R = sprintf("%02X", floor(hexdec($R16) / $darker));
        $G = sprintf("%02X", floor(hexdec($G16) / $darker));
        $B = sprintf("%02X", floor(hexdec($B16) / $darker));

        return $hash . $R . $G . $B;
    }

    static function imageResize($file_path, $width = 0, $height = 0)
    {
        try {
            if (Helper::GeneralWebmasterSettings("image_resize")) {
                $supported_ext = ["jpg", "jpeg", "png", "gif", "webp"];
                $file_ext = strtolower(pathinfo(basename($file_path), PATHINFO_EXTENSION));
                if ($width > 0) {
                    $max_width = $width;
                } else {
                    $max_width = Helper::GeneralWebmasterSettings("image_resize_width");
                }
                if ($height > 0) {
                    $max_height = $height;
                } else {
                    $max_height = Helper::GeneralWebmasterSettings("image_resize_height");
                }
                if (in_array($file_ext, $supported_ext)) {
                    list($image_width, $image_height) = getimagesize($file_path);
                    if ($max_width < $image_width && $max_height < $image_height) {
                        Image::load($file_path)->width($max_width)->height($max_height)->save();
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }

    static function imageOptimize($file_path)
    {
        try {
            if (Helper::GeneralWebmasterSettings("image_optimize")) {
                $supported_ext = ["jpg", "jpeg", "png", "gif", "webp"];
                $file_ext = strtolower(pathinfo(basename($file_path), PATHINFO_EXTENSION));
                if (in_array($file_ext, $supported_ext)) {
                    Image::load($file_path)
                        ->optimize()
                        ->save($file_path);
                }
            }
        } catch (\Exception $e) {
        }
    }

    static function news_letter_subscribe($email, $first_name = "", $last_name = ""): int
    {
        $newsletter_status = config('smartend.newsletter_status');
        $newsletter_provider = config('smartend.newsletter_provider');
        $newsletter_api_key = config('smartend.newsletter_api_key');
        $newsletter_endpoint = config('smartend.newsletter_endpoint');
        $newsletter_list_id = config('smartend.newsletter_list_id');

        if ($newsletter_status && $newsletter_provider != "" && $newsletter_api_key != "" && $newsletter_list_id != "") {
            try {
                if ($newsletter_provider === "mailcoach") {
                    Newsletter::subscribeOrUpdate($email, ['first_name' => $first_name, 'last_name' => $last_name]);
                } else {
                    Newsletter::subscribeOrUpdate($email, ['FNAME' => $first_name, 'LNAME' => $last_name]);
                }
                return 1;
            } catch (\Exception $e) {
            }
        }
        return 0;
    }

    static function get_all_webmaster_section_columns($WebmasterSection, $col = ""): array
    {
        $COLS = [];
        if (!empty($WebmasterSection)) {
            if ($WebmasterSection->no_status) {
                $COLS["col_id"] = [
                    "title" => "ID",
                    "sortable" => 1,
                    "default" => 1,
                    "custom" => 0,
                ];
            }
            if ($WebmasterSection->title_status) {
                $COLS["col_title"] = [
                    "title" => __('backend.topicName'),
                    "sortable" => 1,
                    "default" => 1,
                    "custom" => 0,
                ];
            }
            if ($WebmasterSection->date_status) {
                $COLS["col_date"] = [
                    "title" => __('backend.topicDate'),
                    "sortable" => 1,
                    "default" => 1,
                    "custom" => 0,
                ];
            }
            if ($WebmasterSection->expire_date_status) {
                $COLS["col_expire_date"] = [
                    "title" => __('backend.expireDate'),
                    "sortable" => 1,
                    "default" => 1,
                    "custom" => 0,
                ];
            }
            if ($WebmasterSection->visits_status) {
                $COLS["col_visits"] = [
                    "title" => __('backend.visits'),
                    "sortable" => 1,
                    "default" => 1,
                    "custom" => 0,
                ];
            }
            if ($WebmasterSection->featured_status) {
                $COLS["col_featured"] = [
                    "title" => __('backend.featured'),
                    "sortable" => 1,
                    "default" => 1,
                    "custom" => 0,
                ];
            }
            if ($WebmasterSection->case_status) {
                $COLS["col_status"] = [
                    "title" => __('backend.status'),
                    "sortable" => 1,
                    "default" => 1,
                    "custom" => 0,
                ];
            }
            $cf_title_var = "title_" . @Helper::currentLanguage()->code;
            $cf_title_var2 = "title_" . config('smartend.default_language');
            foreach ($WebmasterSection->customFields->whereNotIn("type", [99]) as $customField) {
                $view_permission_groups = [];
                if ($customField->view_permission_groups != "") {
                    $view_permission_groups = explode(",", $customField->view_permission_groups);
                }
                if (in_array(Auth::user()->permissions_id, $view_permission_groups) || in_array(
                    0,
                    $view_permission_groups
                ) || $customField->view_permission_groups == "") {
                    if ($customField->$cf_title_var != "") {
                        $cf_title = $customField->$cf_title_var;
                    } else {
                        $cf_title = $customField->$cf_title_var2;
                    }

                    $COLS["col_custom_" . $customField->id] = [
                        "title" => $cf_title,
                        "sortable" => 0,
                        "default" => ($customField->in_table) ? 1 : 0,
                        "custom" => 1,
                    ];
                }
            }

            $COLS["col_created_by"] = [
                "title" => __('backend.createdBy'),
                "sortable" => 1,
                "default" => 0,
                "custom" => 0,
            ];
            $COLS["col_created_at"] = [
                "title" => __('backend.createdAt'),
                "sortable" => 1,
                "default" => 0,
                "custom" => 0,
            ];
            $COLS["col_updated_by"] = [
                "title" => __('backend.updatedBy'),
                "sortable" => 1,
                "default" => 0,
                "custom" => 0,
            ];
            $COLS["col_updated_at"] = [
                "title" => __('backend.updatedAt'),
                "sortable" => 1,
                "default" => 0,
                "custom" => 0,
            ];
            if ($col != "") {
                if (!empty(@$COLS[$col])) {
                    return @$COLS[$col];
                }
            } else {
                return @$COLS;
            }
        }
        return [];
    }

    static function get_user_webmaster_columns($webmaster_id): array
    {
        $User = Auth::user();
        if (!empty($User)) {
            $table_columns = [];
            try {
                $table_columns = json_decode($User->table_columns, true);
            } catch (\Exception $e) {
            }
            if (!empty($table_columns)) {
                if (!empty(@$table_columns["sec_" . $webmaster_id])) {
                    return @$table_columns["sec_" . $webmaster_id];
                }
            }
        }
        return [];
    }

    static function get_webmaster_columns($WebmasterSection): array
    {
        $ColsList = [];
        $AllCols = Helper::get_all_webmaster_section_columns($WebmasterSection);
        $MyCols = Helper::get_user_webmaster_columns($WebmasterSection->id);
        if (count($MyCols) > 0) {
            foreach ($MyCols as $MyCol => $MyColSt) {
                if ($MyColSt) {
                    $COL = Helper::get_all_webmaster_section_columns($WebmasterSection, $MyCol);
                    if (!empty($COL)) {
                        $ColsList[$MyCol] = $COL;
                    }
                }
            }
        } else {
            foreach ($AllCols as $KEY => $COL) {
                if (@$COL['default']) {
                    $ColsList[$KEY] = $COL;
                }
            }
        }
        return $ColsList;
    }

    static function LandingModuleIds(): array
    {
        return Cache::remember('_Loader_Landing_Modules', 60 * 24, function () {
            return WebmasterSection::select("id")->where("type", 10)->where("status", 1)->pluck("id")->toArray();
        });
    }

    static function GetStaticsDataByModuleCustomField($module_id, $custom_field_id): array
    {
        $Data = [];
        $CustomField = WebmasterSectionField::find($custom_field_id);
        if (!empty($CustomField)) {
            $cf_title_var = "title_" . @Helper::currentLanguage()->code;
            $cf_details_var = "details_" . @Helper::currentLanguage()->code;
            $cf_details_var2 = "details_" . config('smartend.default_language');
            if ($CustomField->$cf_details_var != "") {
                $cf_details = $CustomField->$cf_details_var;
            } else {
                $cf_details = $CustomField->$cf_details_var2;
            }
            $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
            $line_num = 1;
            foreach ($cf_details_lines as $cf_details_line) {
                if ($CustomField->type == 6) {
                    $tf_ids = TopicField::select("topic_id")->where("field_id", $CustomField->id)->where(
                        "field_value",
                        $line_num
                    );
                } else {
                    $tf_ids = TopicField::select("topic_id")->where("field_id", $CustomField->id)->where(
                        "field_value",
                        'like',
                        '%' . $line_num . '%'
                    );
                }
                $Topics_count = Topic::where('webmaster_id', '=', $module_id)->wherein('id', $tf_ids)->count();
                $Data[] = [
                    "id" => $line_num,
                    "title" => $cf_details_line,
                    "records_count" => $Topics_count,
                ];
                $line_num++;
            }
        }

        // sort data by records_count in descending order
        if (!empty($Data)) {
            usort($Data, function ($a, $b) {
                return $b['records_count'] - $a['records_count'];
            });
        }
        return $Data;
    }
    /**
     * Get the best thumbnail URL for any YouTube video (Shorts or Normal).
     */
    static function getThumbnail($url)
    {
        // 1. Unified Regex for Shorts, long-form, and mobile links
        $pattern = '/(?:\/shorts\/|v=|v\/|embed\/|youtu\.be\/|shorts\/)([\w-]{11})/';
        preg_match($pattern, $url, $matches);
        $videoId = $matches[1] ?? null;

        if (!$videoId) {
            return null;
        }

        // 2. Define resolutions from highest to lowest
        $resolutions = [
            'maxresdefault', // 1280x720 (Best)
            'sddefault',     // 640x480
            'hqdefault',     // 480x360
        ];

        // 3. Fallback logic: check if the high-res version actually exists
        foreach ($resolutions as $res) {
            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/{$res}.jpg";

            // Check status (200 OK), otherwise YouTube returns a placeholder or 404
            $response = Http::head($thumbnailUrl);
            if ($response->successful()) {
                return [
                    'id' => $videoId,
                    'url' => $thumbnailUrl,
                    'webp' => "https://i.ytimg.com/vi/{$videoId}/{$res}.webp"
                ];
            }
        }

        return null;
    }

    public static function oembed(string $url): ?array
    {
        $res = Http::timeout(5)->get('https://www.youtube.com/oembed', [
            'url' => $url,
            'format' => 'json',
        ]);

        if (! $res->successful()) {
            return null;
        }

        $data = $res->json();
        return [
            'title'        => $data['title'] ?? null,
            'channel'      => $data['author_name'] ?? null,
            'channel_url'  => $data['author_url'] ?? null,
            'thumbnail'    => $data['thumbnail_url'] ?? null,
            'embed_html'   => $data['html'] ?? null, // iframe جاهز
        ];
    }

    static function mustView($webmaster_id, $not_id, $limit)
    {
        $query = Topic::where('webmaster_id', $webmaster_id)
            ->whereNot('id', $not_id);
        if ($limit > 0) {
            $query = $query->limit($limit);
        }

        return $query->get();
    }


    static function enrichFixture(array $fx): array
    {
        $stateName = (string) data_get($fx, 'state.name', '');
        $stateCode = strtolower((string) data_get($fx, 'state.code', ''));
        $lowerName = strtolower($stateName);

        $isHalfTime =
            str_contains($stateCode, 'halftime') ||
            str_contains($stateCode, 'ht') ||
            str_contains($lowerName, 'half') ||
            str_contains($lowerName, 'استراحة') ||
            str_contains($lowerName, 'منتصف') ||
            str_contains($lowerName, 'النصف') ||
            str_contains($lowerName, 'نصف');

        $isFinished =
            str_contains($stateCode, 'finished') ||
            str_contains($stateCode, 'ft') ||
            str_contains($lowerName, 'finished') ||
            str_contains($stateName, 'انتهت') ||
            str_contains($stateName, 'النه');

        $isPostponed =
            str_contains($stateCode, 'postpon') ||
            str_contains($lowerName, 'postpon') ||
            str_contains($stateName, 'مؤجل');

        $isCanceled =
            str_contains($stateCode, 'cancel') ||
            str_contains($lowerName, 'cancel') ||
            str_contains($stateName, 'ملغ');

        // 1) minute detection: direct -> periods -> events
        $minute = null;
        $rawMinute = data_get($fx, 'minute') ?? data_get($fx, 'time.minute');
        if (is_numeric($rawMinute)) $minute = (int) $rawMinute;

        if ($minute === null) {
            $periods = collect(data_get($fx, 'periods.data', data_get($fx, 'periods', [])));
            $current = $periods->firstWhere('is_current', true) ?? $periods->firstWhere('current', true);
            $source = $current ?: $periods->last();
            if ($source) {
                $pmin = data_get($source, 'minutes') ?? data_get($source, 'minute') ?? data_get($source, 'elapsed');
                if (is_numeric($pmin)) $minute = (int) $pmin;
            }
        }

        if ($minute === null) {
            $events = collect(data_get($fx, 'events.data', data_get($fx, 'events', [])));
            $maxEv = null;
            foreach ($events as $ev) {
                $em = data_get($ev, 'time.elapsed') ?? data_get($ev, 'minute') ?? data_get($ev, 'elapsed');
                if (is_numeric($em)) {
                    $ival = (int) $em;
                    if ($maxEv === null || $ival > $maxEv) $maxEv = $ival;
                }
            }
            if ($maxEv !== null) $minute = $maxEv;
        }

        // 2) time window logic (STRICT: today only)
        $tz = config('app.timezone') ?: date_default_timezone_get();
        $now = Carbon::now($tz);

        $startAtRaw = data_get($fx, 'starting_at');
        $minsFromStart = null;
        $isSameDay = false;
        $withinWindowToday = false;

        if ($startAtRaw) {
            try {
                // If API returns bare datetime => assume UTC
                if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $startAtRaw)) {
                    $kickoff = Carbon::createFromFormat('Y-m-d H:i:s', $startAtRaw, 'UTC')->setTimezone($tz);
                } else {
                    $kickoff = Carbon::parse($startAtRaw)->setTimezone($tz);
                }

                $minsFromStart = $kickoff->diffInMinutes($now, false);
                $isSameDay = $kickoff->isToday();
                $withinWindowToday = $isSameDay && $minsFromStart >= -5 && $minsFromStart <= 130;

                // minute fallback ONLY if today
                if ($minute === null && $withinWindowToday && $minsFromStart >= 0) {
                    $minute = (int) $minsFromStart;
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // 3) is_live final
        $isLiveByState =
            $isHalfTime ||
            str_contains($stateCode, 'live') ||
            str_contains($stateCode, 'inplay') ||
            str_contains($lowerName, 'live') ||
            str_contains($stateName, 'مباشر');

        $isLive = $isLiveByState;

        // Auto-live ONLY if today-window + not finished/canceled/postponed
        if (!$isLive && !$isFinished && !$isCanceled && !$isPostponed && $withinWindowToday) {
            $isLive = true;
            if ($minute === null && $minsFromStart !== null && $minsFromStart >= 0) {
                $minute = (int) $minsFromStart;
            }
        }

        // halftime minute fallback
        if ($isHalfTime && $minute === null) {
            $isSecondHalf = str_contains($lowerName, 'second') || str_contains($stateName, 'الثاني');
            $minute = $isSecondHalf ? 46 : 45;
        }

        $fx['computed_minute'] = $minute;
        $fx['computed_is_live'] = (bool) $isLive;

        return $fx;
    }

    static function countriesList()
    {
        return Cache::remember('_Loader_Countries_List', 60 * 24, function () {
            return Country::orderby('title_en', 'asc')->get();
        });
    }

    static function getMatchHome($limit = 4)
    {
        $matches = Fixture::with('homeTeam', 'awayTeam', 'league')->where('is_home', 1)->limit($limit)->get();
        return $matches;
    }


    static function day_name($date, $year = false)
    {
        $locale = Helper::currentLanguage()->code;

        $date = Carbon::parse($date)->locale($locale);
        $today = Carbon::now()->locale($locale);
        if ($year) {
            return $date->translatedFormat('d F Y');
        }
        if ($date->isToday()) {
            return __('frontend.today') . ' ' . $date->translatedFormat('d F');
        }

        if ($date->isTomorrow()) {
            return __('frontend.tomorrow') . ' ' . $date->translatedFormat('d F');
        }

        if ($date->isYesterday()) {
            return __('frontend.yesterday') . ' ' . $date->translatedFormat('d F');
        }

        // 👇 هذا يرجع: السبت 28 فبراير
        return $date->translatedFormat('l d F');
    }

    static function isRTL()
    {
        $rtl_languages = ['ar', 'he', 'fa', 'ur'];
        return in_array(Helper::currentLanguage()->code, $rtl_languages);
    }

    static function getUserTimezone(): string
    {
        // 1. من الكوكي
        $tz = request()->cookie('user_timezone');

        // 2. fallback إلى session
        if (!$tz) {
            $tz = session('user_timezone');
        }

        // 3. fallback إلى النظام
        if (!$tz) {
            $tz = config('app.timezone');
        }

        return $tz;
    }

    static function getTeame($id)
    {
        return Team::find($id);
    }
}
