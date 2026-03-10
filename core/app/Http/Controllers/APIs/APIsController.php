<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Mail\NotificationEmail;
use App\Models\AttachFile;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Language;
use App\Models\Map;
use App\Models\Menu;
use App\Models\Photo;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\TopicField;
use App\Models\Webmail;
use App\Models\WebmasterSection;
use App\Models\WebmasterSetting;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Mail;

class APIsController extends Controller
{
    public function __construct()
    {
        // Check API Status
        if (!Helper::GeneralWebmasterSettings("api_status")) {
            // API disabled
            exit();
        }
        Helper::SaveVisitorInfo(url()->current());
    }

    private function unauthenticated()
    {
        $response = [
            'code' => '-1',
            'status' => "error",
            'msg' => 'Authentication failed'
        ];
        return response()->json($response, 500);
    }

    public function api()
    {

        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        return "<!DOCTYPE html>
<html lang=\"en\">
<head>
<meta charset=\"utf-8\">
<title>API v1 | Restful Web Services</title>
<body>
<br>
<div>
Restful Web Services: <br>
---------------------------------------- <br>
{ GET }     /api/v1/website/status <br>
{ GET }     /api/v1/website/info <br>
{ GET }     /api/v1/website/contacts <br>
{ GET }     /api/v1/website/style <br>
{ GET }     /api/v1/website/social <br>
{ GET }     /api/v1/website/settings <br>
{ GET }     /api/v1/menu/ <br>
{ GET }     /api/v1/banners/ <br>
{ GET }     /api/v1/section/ <br>
{ GET }     /api/v1/categories/ <br>
{ GET }     /api/v1/topics/ <br>
{ GET }     /api/v1/category/ <br>
{ GET }     /api/v1/topic/ <br>
{ GET }     /api/v1/topic/fields/ <br>
{ GET }     /api/v1/topic/photos/ <br>
{ GET }     /api/v1/topic/photo/ <br>
{ GET }     /api/v1/topic/maps/ <br>
{ GET }     /api/v1/topic/map/ <br>
{ GET }     /api/v1/topic/files/ <br>
{ GET }     /api/v1/topic/file/ <br>
{ GET }     /api/v1/topic/comments/ <br>
{ GET }     /api/v1/topic/comment/ <br>
{ GET }     /api/v1/topic/related/ <br>
{ GET }     /api/v1/user/ <br>
{ POST }   /api/v1/subscribe <br>
{ POST }   /api/v1/comment <br>
{ POST }   /api/v1/order <br>
{ POST }   /api/v1/contact <br>
---------------------------------------- <br>
For more details check <a href='http://smartfordesign.net/smartend/documentation/api.html' target='_blank'><strong>API documentation</strong></a>
</div>
</body>
</html>
        ";
    }

    public function website_status()
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        // Get Site Settings
        $Setting = Setting::find(1);
        // Response Details
        $msg = "";
        if ($Setting->site_status == 0) {
            $msg = nl2br($Setting->close_msg);
        }
        $response_details = [
            'status' => $Setting->site_status,
            'close_msg' => $msg
        ];
        // Response MSG
        $response = [
            'status' => "success",
            'msg' => 'Website Status details',
            'details' => $response_details
        ];
        return response()->json($response, 200);
    }

    public function website_info()
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        // Get Site Settings
        $Setting = Setting::find(1);

        // By Language
        $lang = $this->getLanguage();
        $site_title_var = "site_title_$lang";
        $site_desc_var = "site_desc_$lang";
        $site_keywords_var = "site_keywords_$lang";

        // Response Details
        $response_details = [
            'site_url' => $Setting->site_url,
            'site_title' => $Setting->$site_title_var,
            'site_desc' => $Setting->$site_desc_var,
            'site_keywords' => $Setting->$site_keywords_var,
            'site_webmails' => $Setting->site_webmails
        ];
        // Response MSG
        $response = [
            'status' => "success",
            'msg' => 'Main information about the Website',
            'details' => $response_details
        ];
        return response()->json($response, 200);
    }

    public function getLanguage()
    {
        $lang = request()->header("lang");

        // List of active languages for API
        $Language = Language::where("status", true)->where("code", $lang)->first();

        if ($lang == "" || empty($Language)) {
            $lang = config('smartend.default_language');
        }
        return $lang;
    }

    public function website_contacts()
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        // Get Site Settings
        $Setting = Setting::find(1);

        // By Language
        $lang = $this->getLanguage();
        $address_var = "contact_t1_$lang";
        $working_time_var = "contact_t7_$lang";

        // Response Details
        $response_details = [
            'address' => $Setting->$address_var,
            'phone' => $Setting->contact_t3,
            'fax' => $Setting->contact_t4,
            'mobile' => $Setting->contact_t5,
            'email' => $Setting->contact_t6,
            'working_time' => $Setting->$working_time_var
        ];
        // Response MSG
        $response = [
            'status' => "success",
            'msg' => 'List of Contacts Details',
            'details' => $response_details
        ];
        return response()->json($response, 200);
    }

    public function website_style()
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        // Get Site Settings
        $Setting = Setting::find(1);

        // By Language
        $lang = $this->getLanguage();
        $style_logo_var = "style_logo_$lang";

        // Response Details
        $response_details = [
            'logo' => ($Setting->$style_logo_var != "") ? url("")."/uploads/settings/".$Setting->$style_logo_var : null,
            'fav_icon' => ($Setting->style_fav != "") ? url("")."/uploads/settings/".$Setting->style_fav : null,
            'apple_icon' => ($Setting->style_apple != "") ? url("")."/uploads/settings/".$Setting->style_apple : null,
            'style_color_1' => $Setting->style_color1,
            'style_color_2' => $Setting->style_color2,
            'layout_mode' => $Setting->style_type,
            'bg_type' => $Setting->style_bg_type,
            'bg_pattern' => ($Setting->style_bg_pattern != "") ? url("")."/uploads/pattern/".$Setting->style_bg_pattern : null,
            'bg_color' => $Setting->style_bg_color,
            'bg_image' => ($Setting->style_bg_image != "") ? url("")."/uploads/settings/".$Setting->style_bg_image : null,
            'footer_style' => $Setting->style_footer,
            'footer_bg' => ($Setting->style_footer_bg != "") ? url("")."/uploads/settings/".$Setting->style_footer_bg : null,
            'newsletter_subscribe_status' => $Setting->style_subscribe,
            'preload_status' => $Setting->style_preload
        ];
        // Response MSG
        $response = [
            'status' => "success",
            'msg' => 'List of Style Settings',
            'details' => $response_details
        ];
        return response()->json($response, 200);
    }

    public function website_social()
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        // Get Site Settings
        $Setting = Setting::find(1);

        // Response Details
        $response_details = null;
        if ($Setting && $Setting->social_links) {
            $response_details = json_decode($Setting->social_links, true);
        }

        // Response MSG
        $response = [
            'status' => "success",
            'msg' => 'List of Social Networks Links',
            'details' => $response_details
        ];
        return response()->json($response, 200);
    }

    public function website_settings()
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        // Get Site Settings
        $WebmasterSetting = WebmasterSetting::find(1);

        // Response Details
        $response_details = [
            'new_comments_status' => $WebmasterSetting->new_comments_status,
            'allow_register_status' => $WebmasterSetting->register_status,
            'register_permission_group' => $WebmasterSetting->permission_group,
            'contact_text_page_id' => $WebmasterSetting->contact_page_id,
            'header_menu_id' => $WebmasterSetting->header_menu_id,
            'footer_menu_id' => $WebmasterSetting->footer_menu_id,
            'latest_news_section_id' => $WebmasterSetting->latest_news_section_id,
            'newsletter_contacts_group' => $WebmasterSetting->newsletter_contacts_group,
            'home_content1_section_id' => $WebmasterSetting->home_content1_section_id,
            'home_content2_section_id' => $WebmasterSetting->home_content2_section_id,
            'home_content3_section_id' => $WebmasterSetting->home_content3_section_id,
            'home_banners_section_id' => $WebmasterSetting->home_banners_section_id,
            'home_text_banners_section_id' => $WebmasterSetting->home_text_banners_section_id,
            'side_banners_section_id' => $WebmasterSetting->side_banners_section_id,
            'languages' => Helper::languagesList()
        ];
        // Response MSG
        $response = [
            'status' => "success",
            'msg' => 'General Website Settings',
            'details' => $response_details
        ];
        return response()->json($response, 200);
    }

    public function menu($menu_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($menu_id > 0) {
            // Get menu details
            $Menu = Menu::where('father_id', $menu_id)->where('status', 1)->orderby('row_no', 'asc')->get();
            if (count($Menu) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";

                // Response Details
                $response_details = [];
                foreach ($Menu as $MenuLink) {
                    $SubMenu = Menu::where('father_id', $MenuLink->id)->where('status', 1)->orderby('row_no',
                        'asc')->get();
                    $sub_response_details = [];
                    if (count($SubMenu) > 0) {
                        foreach ($SubMenu as $SubMenuLink) {
                            $m_link = "";
                            if ($SubMenuLink->type == 3 || $SubMenuLink->type == 2) {
                                $m_link = $SubMenuLink->webmasterSection->name;
                            } elseif ($SubMenuLink->type == 1) {
                                $m_link = $MenuLink->link;
                            }
                            $sub_response_details[] = [
                                'id' => $SubMenuLink->id,
                                'title' => $SubMenuLink->$title_var,
                                'section_id' => $SubMenuLink->cat_id,
                                'href' => $m_link
                            ];
                        }
                    }

                    $m_link = "";
                    $sub_count = count($SubMenu);
                    if ($MenuLink->type == 3) {
                        // Section with drop list
                        $m_link = $MenuLink->webmasterSection->name;
                        $sub_count = count($MenuLink->webmasterSection->sections);
                        foreach ($MenuLink->webmasterSection->sections as $SubSection) {
                            $sub_response_details[] = [
                                'id' => $SubSection->id,
                                'title' => $SubSection->$title_var,
                                'section_id' => $MenuLink->cat_id,
                                'href' => Helper::sectionURL($SubSection->id, "", $SubSection)
                            ];
                        }
                    } elseif ($MenuLink->type == 2) {
                        // Section Link
                        $m_link = $MenuLink->webmasterSection->name;
                    } elseif ($MenuLink->type == 1) {
                        $m_link = $MenuLink->link;
                    }
                    $response_details[] = [
                        'id' => $MenuLink->id,
                        'title' => $MenuLink->$title_var,
                        'section_id' => $MenuLink->cat_id,
                        'href' => $m_link,
                        'sub_links_count' => $sub_count,
                        'sub_links' => $sub_response_details
                    ];
                    // sub links

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'List of Menu Links',
                    'links_count' => count($Menu),
                    'links' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function banners($group_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($group_id > 0) {
            // Get banners
            $Banners = Banner::where('section_id', $group_id)->where('status', 1)->orderby('row_no', 'asc')->get();
            if (count($Banners) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $details_var = "details_$lang";
                $file_var = "file_$lang";

                // Response Details
                $response_details = [];
                $type = "";
                foreach ($Banners as $Banner) {
                    $type = $Banner->webmasterBanner->type;
                    $response_details[] = [
                        'id' => $Banner->id,
                        'title' => $Banner->$title_var,
                        'details' => nl2br($Banner->$details_var),
                        'file' => ($Banner->$file_var != "") ? url("")."/uploads/banners/".$Banner->$file_var : null,
                        'video_type' => $Banner->video_type,
                        'youtube_link' => $Banner->youtube_link,
                        'link_url' => $Banner->link_url,
                        'icon' => $Banner->icon
                    ];
                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'List of Banners',
                    'type' => $type,
                    'banners_count' => count($Banners),
                    'banners' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function section($section_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($section_id > 0) {
            // Get categories
            $WebmasterSections = WebmasterSection::where('id', $section_id)->where('status', 1)->get();
            if (count($WebmasterSections) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $section_title = "";
                $type = "";
                $sections_status = "";
                $title_var2 = "title_".config('smartend.default_language');

                // Response Details
                $response_details = [];
                foreach ($WebmasterSections as $WebmasterSection) {
                    if ($WebmasterSection->$title_var != "") {
                        $section_title = $WebmasterSection->$title_var;
                    } else {
                        $section_title = $WebmasterSection->$title_var2;
                    }
                    $type = $WebmasterSection->type;
                    $sections_status = $WebmasterSection->sections_status;
                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Website Section Details',
                    'section_id' => $section_id,
                    'title' => $section_title,
                    'href' => Helper::sectionURL($WebmasterSection->id, "", $WebmasterSection),
                    'type' => $type,
                    'categories_status' => $sections_status
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function categories($section_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($section_id > 0) {
            $WebmasterSection = WebmasterSection::find($section_id);
            if (!empty($WebmasterSection)) {
                // if private redirect back to home
                if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                    // Empty MSG
                    $response = [
                        'status' => "error",
                        'msg' => 'There is no data'
                    ];
                    return response()->json($response, 404);
                }
            }

            // Get categories
            $Sections = Section::where('webmaster_id', $section_id)->where('father_id', '0')->where('status',
                1)->orderby('row_no', 'asc')->get();
            if (count($Sections) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $type = "";
                $section_title = "";

                // Response Details
                $response_details = [];
                foreach ($Sections as $Section) {
                    $type = $Section->webmasterSection->type;
                    if ($Section->webmasterSection->$title_var != "") {
                        $section_title = $Section->webmasterSection->$title_var;
                    } else {
                        $section_title = $Section->webmasterSection->$title_var2;
                    }

                    $SubSections = Section::where('webmaster_id', $section_id)->where('father_id',
                        $Section->id)->where('status', 1)->orderby('row_no', 'asc')->get();
                    $sub_response_details = [];
                    foreach ($SubSections as $SubSection) {
                        if ($SubSection->$title_var != "") {
                            $SubCat_title = $SubSection->$title_var;
                        } else {
                            $SubCat_title = $SubSection->$title_var2;
                        }
                        $sub_response_details[] = [
                            'id' => $SubSection->id,
                            'title' => $SubCat_title,
                            'icon' => $SubSection->icon,
                            'photo' => ($SubSection->photo != "") ? url("")."/uploads/sections/".$SubSection->photo : null,
                            'href' => Helper::categoryURL($SubSection->id, "", $SubSection),
                        ];
                    }
                    if ($Section->$title_var != "") {
                        $cat_title = $Section->$title_var;
                    } else {
                        $cat_title = $Section->$title_var2;
                    }
                    $response_details[] = [
                        'id' => $Section->id,
                        'title' => $cat_title,
                        'icon' => $Section->icon,
                        'photo' => ($Section->photo != "") ? url("")."/uploads/sections/".$Section->photo : null,
                        'href' => Helper::categoryURL($Section->id, "", $Section),
                        'sub_categories_count' => count($SubSections),
                        'sub_categories' => $sub_response_details
                    ];

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'List of Categories',
                    'section_id' => $section_id,
                    'section_title' => $section_title,
                    'type' => $type,
                    'categories_count' => count($Sections),
                    'categories' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function topics($section_id, $page_number = 1, $topics_count = 0)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($section_id > 0) {
            $WebmasterSection = WebmasterSection::find($section_id);
            if (!empty($WebmasterSection)) {
                // if private redirect back to home
                if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                    // Empty MSG
                    $response = [
                        'status' => "error",
                        'msg' => 'There is no data'
                    ];
                    return response()->json($response, 404);
                }
            }

            if ($page_number < 1) {
                $page_number = 1;
            }
            Paginator::currentPageResolver(function () use ($page_number) {
                return $page_number;
            });

            // Get topics
            $Topics = Topic::where("status", 1)->where("webmaster_id", $section_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            });

            $search_word = request()->input('q');
            if ($search_word != "") {
                $topics_ids = TopicField::select("topic_id")->where("field_value", 'like', '%'.$search_word.'%');

                $Topics = $Topics->where(function ($query) use ($search_word, $topics_ids) {
                    $query->where('title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%')
                        ->orwhere('seo_title_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%')
                        ->orwhere('details_'.Helper::currentLanguage()->code, 'like', '%'.$search_word.'%')
                        ->orwherein("id", $topics_ids);
                });
            }

            if ($topics_count > 0) {
                $Topics = $Topics->paginate($topics_count);
            } else {
                $Topics = $Topics->get();
            }

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $details_var = "details_$lang";
                $details_var2 = "details_".config('smartend.default_language');
                $type = "";
                $section_title = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {
                    $type = $Topic->webmasterSection->type;
                    if ($Topic->webmasterSection->$title_var != "") {
                        $section_title = $Topic->webmasterSection->$title_var;
                    } else {
                        $section_title = $Topic->webmasterSection->$title_var2;
                    }


                    $Joined_categories = [];
                    foreach ($Topic->categories as $category) {
                        if ($category->section->$title_var != "") {
                            $Cat_title = $category->section->$title_var;
                        } else {
                            $Cat_title = $category->section->$title_var2;
                        }
                        $Joined_categories[] = [
                            'id' => $category->id,
                            'title' => $Cat_title,
                            'icon' => $category->section->icon,
                            'photo' => ($category->section->photo != "") ? url("")."/uploads/sections/".$category->section->photo : null,
                            'href' => Helper::categoryURL($category->section->id, "", $category->section)
                        ];
                    }

                    // additional fields
                    $Additional_fields = [];
                    foreach ($Topic->webmasterSection->customFields->where("in_listing", true) as $customField) {
                        if ($customField->in_page) {

                            $cf_saved_val = "";
                            $cf_saved_val_array = array();
                            if (count($Topic->fields) > 0) {
                                foreach ($Topic->fields as $t_field) {
                                    if ($t_field->field_id == $customField->id) {
                                        if (in_array($customField->type,[7,17])) {
                                            // if multi check
                                            $cf_saved_val_array = explode(", ", $t_field->field_value);
                                            $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                            $cf_details_var2 = "details_".config('smartend.default_language');
                                            if ($customField->$cf_details_var != "") {
                                                $cf_details = $customField->$cf_details_var;
                                            } else {
                                                $cf_details = $customField->$cf_details_var2;
                                            }
                                            $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                            $line_num = 1;
                                            foreach ($cf_details_lines as $cf_details_line) {
                                                if (in_array($line_num, $cf_saved_val_array)) {
                                                    $cf_saved_val .= $cf_details_line.", ";
                                                }
                                                $line_num++;
                                            }
                                            $cf_saved_val = substr($cf_saved_val, 0, -2);
                                        } else {
                                            $cf_saved_val = $t_field->field_value;
                                        }
                                    }
                                }
                            }

                            if (($cf_saved_val != "" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == "$lang")) {
                                $Additional_fields[] = [
                                    'type' => $customField->type,
                                    'title' => $customField->$title_var,
                                    'value' => $cf_saved_val,
                                ];
                            }
                        }
                    }

                    $video_file = $Topic->video_file;
                    if ($Topic->video_type == 0) {
                        $video_file = ($Topic->video_file != "") ? url("")."/uploads/topics/".$Topic->video_file : "";
                    }
                    if ($Topic->$title_var != "") {
                        $Topic_title = $Topic->$title_var;
                    } else {
                        $Topic_title = $Topic->$title_var2;
                    }
                    if ($Topic->$details_var != "") {
                        $Topic_details = $Topic->$details_var;
                    } else {
                        $Topic_details = $Topic->$details_var2;
                    }
                    $response_details[] = [
                        'id' => $Topic->id,
                        'title' => $Topic_title,
                        'details' => $Topic_details,
                        'date' => $Topic->date,
                        'video_type' => $Topic->video_type,
                        'video_file' => $video_file,
                        'photo_file' => ($Topic->photo_file != "") ? url("")."/uploads/topics/".$Topic->photo_file : null,
                        'audio_file' => ($Topic->audio_file != "") ? url("")."/uploads/topics/".$Topic->audio_file : null,
                        'icon' => $Topic->icon,
                        'visits' => $Topic->visits,
                        'href' => Helper::topicURL($Topic->id, "", $Topic),
                        'fields_count' => count($Additional_fields),
                        'fields' => $Additional_fields,
                        'Joined_categories_count' => count($Topic->categories),
                        'Joined_categories' => $Joined_categories,
                        'user' => [
                            'id' => $Topic->user->id,
                            'name' => $Topic->user->name
                        ]

                    ];

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'List of Topics',
                    'section_id' => $section_id,
                    'section_title' => $section_title,
                    'type' => $type,
                    'topics_count' => count($Topics),
                    'topics' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function category($cat_id, $page_number = 1, $topics_count = 0)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($cat_id > 0) {
            if ($page_number < 1) {
                $page_number = 1;
            }
            Paginator::currentPageResolver(function () use ($page_number) {
                return $page_number;
            });

            $category_topics = array();
            $TopicCategories = TopicCategory::where('section_id', $cat_id)->get();
            foreach ($TopicCategories as $category) {
                $category_topics[] = $category->topic_id;
            }

            $Topics = Topic::where("status", 1)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->whereIn('id', $category_topics)->orderby('row_no', config('smartend.frontend_topics_order'))->orderby('id', config('smartend.frontend_topics_order'));

            if ($topics_count > 0) {
                $Topics = $Topics->paginate($topics_count);
            } else {
                $Topics = $Topics->get();
            }

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $details_var = "details_$lang";
                $details_var2 = "details_".config('smartend.default_language');
                $cat_title = "";

                $CurrentCategory = Section::find($cat_id);
                if (!empty($CurrentCategory)) {
                    $cat_title = $CurrentCategory->$title_var;
                }

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {
                    $Joined_categories = [];
                    foreach ($Topic->categories as $category) {
                        if ($category->section->$title_var != "") {
                            $Cat_title = $category->section->$title_var;
                        } else {
                            $Cat_title = $category->section->$title_var2;
                        }
                        $Joined_categories[] = [
                            'id' => $category->id,
                            'title' => $Cat_title,
                            'icon' => $category->section->icon,
                            'photo' => ($category->section->photo != "") ? url("")."/uploads/sections/".$category->section->photo : null,
                            'href' => Helper::categoryURL($category->section->id, "", $category->section)
                        ];
                    }

                    // additional fields
                    $Additional_fields = [];
                    foreach ($Topic->webmasterSection->customFields->where("in_listing", true) as $customField) {
                        if ($customField->in_page) {

                            $cf_saved_val = "";
                            $cf_saved_val_array = array();
                            if (count($Topic->fields) > 0) {
                                foreach ($Topic->fields as $t_field) {
                                    if ($t_field->field_id == $customField->id) {
                                        if (in_array($customField->type,[7,17])) {
                                            // if multi check
                                            $cf_saved_val_array = explode(", ", $t_field->field_value);
                                            $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                            $cf_details_var2 = "details_".config('smartend.default_language');
                                            if ($customField->$cf_details_var != "") {
                                                $cf_details = $customField->$cf_details_var;
                                            } else {
                                                $cf_details = $customField->$cf_details_var2;
                                            }
                                            $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                            $line_num = 1;
                                            foreach ($cf_details_lines as $cf_details_line) {
                                                if (in_array($line_num, $cf_saved_val_array)) {
                                                    $cf_saved_val .= $cf_details_line.", ";
                                                }
                                                $line_num++;
                                            }
                                            $cf_saved_val = substr($cf_saved_val, 0, -2);
                                        } else {
                                            $cf_saved_val = $t_field->field_value;
                                        }
                                    }
                                }
                            }

                            if (($cf_saved_val != "" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == "$lang")) {
                                $Additional_fields[] = [
                                    'type' => $customField->type,
                                    'title' => $customField->$title_var,
                                    'value' => $cf_saved_val,
                                ];
                            }
                        }
                    }

                    $video_file = $Topic->video_file;
                    if ($Topic->video_type == 0) {
                        $video_file = ($Topic->video_file != "") ? url("")."/uploads/topics/".$Topic->video_file : "";
                    }
                    if ($Topic->$title_var != "") {
                        $Topic_title = $Topic->$title_var;
                    } else {
                        $Topic_title = $Topic->$title_var2;
                    }
                    if ($Topic->$details_var != "") {
                        $Topic_details = $Topic->$details_var;
                    } else {
                        $Topic_details = $Topic->$details_var2;
                    }
                    $response_details[] = [
                        'id' => $Topic->id,
                        'title' => $Topic_title,
                        'details' => $Topic_details,
                        'date' => $Topic->date,
                        'video_type' => $Topic->video_type,
                        'video_file' => $video_file,
                        'photo_file' => ($Topic->photo_file != "") ? url("")."/uploads/topics/".$Topic->photo_file : null,
                        'audio_file' => ($Topic->audio_file != "") ? url("")."/uploads/topics/".$Topic->audio_file : null,
                        'icon' => $Topic->icon,
                        'visits' => $Topic->visits,
                        'href' => Helper::topicURL($Topic->id, "", $Topic),
                        'fields_count' => count($Additional_fields),
                        'fields' => $Additional_fields,
                        'Joined_categories_count' => count($Topic->categories),
                        'Joined_categories' => $Joined_categories,
                        'user' => [
                            'id' => $Topic->user->id,
                            'name' => $Topic->user->name,
                        ]

                    ];

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'List of Topics',
                    'cat_id' => $cat_id,
                    'cat_title' => $cat_title,
                    'topics_count' => count($Topics),
                    'topics' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function topic($topic_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($topic_id > 0) {

            // Get topic details
            $Topics = Topic::where("status", 1)->where("id", $topic_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->get();

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $details_var = "details_$lang";
                $details_var2 = "details_".config('smartend.default_language');

                $type = "";
                $section_id = "";
                $section_title = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            // Empty MSG
                            $response = [
                                'status' => "error",
                                'msg' => 'There is no data'
                            ];
                            return response()->json($response, 404);
                        }
                    }

                    $type = $Topic->webmasterSection->type;
                    $section_id = $Topic->webmasterSection->id;
                    if ($Topic->webmasterSection->$title_var != "") {
                        $section_title = $Topic->webmasterSection->$title_var;
                    } else {
                        $section_title = $Topic->webmasterSection->$title_var2;
                    }

                    // additional fields
                    $Additional_fields = [];
                    foreach ($Topic->webmasterSection->customFields->where("in_page", true) as $customField) {
                        if ($customField->in_page) {
                            $cf_saved_val = "";
                            $cf_saved_val_array = array();
                            if (count($Topic->fields) > 0) {
                                foreach ($Topic->fields as $t_field) {
                                    if ($t_field->field_id == $customField->id) {
                                        if (in_array($customField->type,[7,17])) {
                                            // if multi check
                                            $cf_saved_val_array = explode(", ", $t_field->field_value);
                                            $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                            $cf_details_var2 = "details_".config('smartend.default_language');
                                            if ($customField->$cf_details_var != "") {
                                                $cf_details = $customField->$cf_details_var;
                                            } else {
                                                $cf_details = $customField->$cf_details_var2;
                                            }
                                            $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                            $line_num = 1;
                                            foreach ($cf_details_lines as $cf_details_line) {
                                                if (in_array($line_num, $cf_saved_val_array)) {
                                                    $cf_saved_val .= $cf_details_line.", ";
                                                }
                                                $line_num++;
                                            }
                                            $cf_saved_val = substr($cf_saved_val, 0, -2);

                                        } else {
                                            $cf_saved_val = $t_field->field_value;
                                        }
                                    }
                                }
                            }

                            if (($cf_saved_val != "" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == "$lang")) {
                                $Additional_fields[] = [
                                    'type' => $customField->type,
                                    'title' => $customField->$title_var,
                                    'value' => $cf_saved_val,
                                ];
                            }
                        }
                    }

                    // categories
                    $Joined_categories = [];
                    foreach ($Topic->categories as $category) {
                        if ($category->section->$title_var != "") {
                            $Cat_title = $category->section->$title_var;
                        } else {
                            $Cat_title = $category->section->$title_var2;
                        }
                        $Joined_categories[] = [
                            'id' => $category->id,
                            'title' => $Cat_title,
                            'icon' => $category->section->icon,
                            'photo' => ($category->section->photo != "") ? url("")."/uploads/sections/".$category->section->photo : null,
                            'href' => Helper::categoryURL($category->section->id, "", $category->section)
                        ];
                    }
                    // photos
                    $Photos = [];
                    foreach ($Topic->photos as $photo) {
                        $Photos[] = [
                            'id' => $photo->id,
                            'title' => $photo->title,
                            'url' => ($photo->file != "") ? url("")."/uploads/topics/".$photo->file : null,
                        ];
                    }
                    // maps
                    $Maps = [];
                    foreach ($Topic->maps as $map) {

                        if ($map->$title_var != "") {
                            $map_title = $map->$title_var;
                        } else {
                            $map_title = $map->$title_var2;
                        }
                        if ($map->$details_var != "") {
                            $map_details = $map->$details_var;
                        } else {
                            $map_details = $map->$details_var2;
                        }

                        $Maps[] = [
                            'id' => $map->id,
                            'longitude' => $map->longitude,
                            'latitude' => $map->latitude,
                            'title' => $map_title,
                            'details' => $map_details
                        ];
                    }
                    // attach files
                    $Attach_files = [];
                    foreach ($Topic->attachFiles as $attachFile) {
                        if ($attachFile->$title_var != "") {
                            $attachFile_title = $attachFile->$title_var;
                        } else {
                            $attachFile_title = $attachFile->$title_var2;
                        }
                        $Attach_files[] = [
                            'id' => $attachFile->id,
                            'title' => $attachFile_title,
                            'url' => ($attachFile->file != "") ? url("")."/uploads/topics/".$attachFile->file : null
                        ];
                    }
                    // comments
                    $Comments = [];
                    foreach ($Topic->approvedComments as $comment) {
                        $Comments[] = [
                            'id' => $comment->id,
                            'name' => $comment->name,
                            'email' => $comment->email,
                            'date' => $comment->date,
                            'comment' => nl2br($comment->comment)
                        ];
                    }
                    // related topics
                    $Related_topics = [];
                    foreach ($Topic->relatedTopics as $relatedTopic) {
                        if ($relatedTopic->topic->$title_var != "") {
                            $relatedTopic_title = $relatedTopic->topic->$title_var;
                        } else {
                            $relatedTopic_title = $relatedTopic->topic->$title_var2;
                        }
                        $Related_topics[] = [
                            'id' => $relatedTopic->topic->id,
                            'title' => $relatedTopic_title,
                            'date' => $relatedTopic->topic->date,
                            'href' => Helper::topicURL($relatedTopic->topic->id, "", $relatedTopic->topic),
                            'photo_file' => ($relatedTopic->topic->photo_file != "") ? url("")."/uploads/topics/".$relatedTopic->topic->photo_file : null
                        ];
                    }

                    $video_file = $Topic->video_file;
                    if ($Topic->video_type == 0) {
                        $video_file = ($Topic->video_file != "") ? url("")."/uploads/topics/".$Topic->video_file : "";
                    }

                    if ($Topic->$title_var != "") {
                        $Topic_title = $Topic->$title_var;
                    } else {
                        $Topic_title = $Topic->$title_var2;
                    }
                    if ($Topic->$details_var != "") {
                        $Topic_details = $Topic->$details_var;
                    } else {
                        $Topic_details = $Topic->$details_var2;
                    }

                    $response_details[] = [
                        'id' => $Topic->id,
                        'title' => $Topic_title,
                        'details' => $Topic_details,
                        'date' => $Topic->date,
                        'video_type' => $Topic->video_type,
                        'video_file' => $video_file,
                        'photo_file' => ($Topic->photo_file != "") ? url("")."/uploads/topics/".$Topic->photo_file : null,
                        'audio_file' => ($Topic->audio_file != "") ? url("")."/uploads/topics/".$Topic->audio_file : null,
                        'icon' => $Topic->icon,
                        'visits' => $Topic->visits,
                        'href' => Helper::topicURL($Topic->id, "", $Topic),
                        'fields_count' => count($Additional_fields),
                        'fields' => $Additional_fields,
                        'Joined_categories_count' => count($Joined_categories),
                        'Joined_categories' => $Joined_categories,
                        'photos_count' => count($Photos),
                        'photos' => $Photos,
                        'attach_files_count' => count($Attach_files),
                        'attach_files' => $Attach_files,
                        'maps_count' => count($Maps),
                        'maps' => $Maps,
                        'comments_count' => count($Comments),
                        'comments' => $Comments,
                        'related_topics_count' => count($Related_topics),
                        'related_topics' => $Related_topics,
                        'user' => [
                            'id' => $Topic->user->id,
                            'name' => $Topic->user->name,
                        ]

                    ];

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Details of topic',
                    'section_id' => $section_id,
                    'section_title' => $section_title,
                    'type' => $type,
                    'topic' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function topic_photos($topic_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($topic_id > 0) {

            // Get topic details
            $Topics = Topic::where("status", 1)->where("id", $topic_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->get();

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $topic_title = "";
                $photo_file = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            // Empty MSG
                            $response = [
                                'status' => "success",
                                'msg' => 'There is no data'
                            ];
                            return response()->json($response, 404);
                        }
                    }

                    if ($Topic->$title_var != "") {
                        $topic_title = $Topic->$title_var;
                    } else {
                        $topic_title = $Topic->$title_var2;
                    }
                    $photo_file = $Topic->photo_file;

                    // photos
                    $response_details = [];
                    foreach ($Topic->photos as $photo) {
                        $response_details[] = [
                            'id' => $photo->id,
                            'title' => $photo->title,
                            'url' => ($photo->file != "") ? url("")."/uploads/topics/".$photo->file : null
                        ];
                    }

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Photos of topic',
                    'topic_id' => $topic_id,
                    'topic_title' => $topic_title,
                    'topic_link' => Helper::topicURL($topic_id),
                    'topic_photo' => ($photo_file != "") ? url("")."/uploads/topics/".$photo_file : null,
                    'photos_count' => count($response_details),
                    'photos' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function topic_photo($photo_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($photo_id > 0) {

            // Get Photo details
            $Photo = Photo::find($photo_id);

            if (!empty($Photo)) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $topic_title = "";
                $photo_file = "";

                $response_details[] = [
                    'id' => $Photo->id,
                    'title' => $Photo->title,
                    'url' => ($Photo->file != "") ? url("")."/uploads/topics/".$Photo->file : null
                ];

                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Photo details',
                    'topic_id' => $Photo->topic_id,
                    'photo' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function topic_maps($topic_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($topic_id > 0) {

            // Get topic details
            $Topics = Topic::where("status", 1)->where("id", $topic_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->get();

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $details_var = "details_$lang";
                $details_var2 = "details_".config('smartend.default_language');
                $topic_title = "";
                $photo_file = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            // Empty MSG
                            $response = [
                                'status' => "error",
                                'msg' => 'There is no data'
                            ];
                            return response()->json($response, 404);
                        }
                    }

                    if ($Topic->$title_var != "") {
                        $topic_title = $Topic->$title_var;
                    } else {
                        $topic_title = $Topic->$title_var2;
                    }
                    $photo_file = $Topic->photo_file;

                    // maps
                    $response_details = [];
                    foreach ($Topic->maps as $map) {

                        if ($map->$title_var != "") {
                            $map_title = $map->$title_var;
                        } else {
                            $map_title = $map->$title_var2;
                        }
                        if ($map->$details_var != "") {
                            $map_details = $map->$details_var;
                        } else {
                            $map_details = $map->$details_var2;
                        }

                        $response_details[] = [
                            'id' => $map->id,
                            'longitude' => $map->longitude,
                            'latitude' => $map->latitude,
                            'title' => $map_title,
                            'details' => $map_details
                        ];
                    }

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Maps of topic',
                    'topic_id' => $topic_id,
                    'topic_title' => $topic_title,
                    'topic_link' => Helper::topicURL($topic_id),
                    'topic_photo' => ($photo_file != "") ? url("")."/uploads/topics/".$photo_file : null,
                    'maps_count' => count($response_details),
                    'maps' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function topic_map($map_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($map_id > 0) {

            // Get map details
            $Map = Map::find($map_id);

            if (!empty($Map)) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $details_var = "details_$lang";
                $details_var2 = "details_".config('smartend.default_language');

                if ($Map->$title_var != "") {
                    $map_title = $Map->$title_var;
                } else {
                    $map_title = $Map->$title_var2;
                }
                if ($Map->$details_var != "") {
                    $map_details = $Map->$details_var;
                } else {
                    $map_details = $Map->$details_var2;
                }

                $response_details[] = [
                    'id' => $Map->id,
                    'longitude' => $Map->longitude,
                    'latitude' => $Map->latitude,
                    'title' => $map_title,
                    'details' => $map_details
                ];

                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Map details',
                    'topic_id' => $Map->topic_id,
                    'map' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];
                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function topic_files($topic_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($topic_id > 0) {

            // Get topic details
            $Topics = Topic::where("status", 1)->where("id", $topic_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->get();

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $topic_title = "";
                $photo_file = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            // Empty MSG
                            $response = [
                                'status' => "error",
                                'msg' => 'There is no data'
                            ];
                            return response()->json($response, 404);
                        }
                    }

                    if ($Topic->$title_var != "") {
                        $topic_title = $Topic->$title_var;
                    } else {
                        $topic_title = $Topic->$title_var2;
                    }
                    $photo_file = $Topic->photo_file;

                    // attach files
                    $response_details = [];
                    foreach ($Topic->attachFiles as $attachFile) {
                        if ($attachFile->$title_var != "") {
                            $attachFile_title = $attachFile->$title_var;
                        } else {
                            $attachFile_title = $attachFile->$title_var2;
                        }
                        $response_details[] = [
                            'id' => $attachFile->id,
                            'title' => $attachFile_title,
                            'url' => ($attachFile->file != "") ? url("")."/uploads/topics/".$attachFile->file : null
                        ];
                    }

                }

                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Attach files of topic',
                    'topic_id' => $topic_id,
                    'topic_title' => $topic_title,
                    'topic_link' => Helper::topicURL($topic_id),
                    'topic_photo' => ($photo_file != "") ? url("")."/uploads/topics/".$photo_file : null,
                    'files_count' => count($response_details),
                    'files' => $response_details
                ];

                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];

                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];

            return response()->json($response, 404);
        }
    }

    public function topic_file($file_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($file_id > 0) {

            // Get topic details
            $AttachFile = AttachFile::find($file_id);

            if (!empty($AttachFile)) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                if ($AttachFile->$title_var != "") {
                    $attachFile_title = $AttachFile->$title_var;
                } else {
                    $attachFile_title = $AttachFile->$title_var2;
                }
                $response_details[] = [
                    'id' => $AttachFile->id,
                    'title' => $attachFile_title,
                    'url' => ($AttachFile->file != "") ? url("")."/uploads/topics/".$AttachFile->file : null
                ];

                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Attach file details',
                    'topic_id' => $AttachFile->topic_id,
                    'file' => $response_details
                ];

                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];

                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];

            return response()->json($response, 404);
        }
    }

    public function topic_comments($topic_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($topic_id > 0) {

            // Get topic details
            $Topics = Topic::where("status", 1)->where("id", $topic_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->get();

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $topic_title = "";
                $photo_file = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            // Empty MSG
                            $response = [
                                'status' => "error",
                                'msg' => 'There is no data'
                            ];
                            return response()->json($response, 404);
                        }
                    }

                    if ($Topic->$title_var != "") {
                        $topic_title = $Topic->$title_var;
                    } else {
                        $topic_title = $Topic->$title_var2;
                    }
                    $photo_file = $Topic->photo_file;

                    // comments
                    $response_details = [];
                    foreach ($Topic->approvedComments as $comment) {
                        $response_details[] = [
                            'id' => $comment->id,
                            'name' => $comment->name,
                            'email' => $comment->email,
                            'date' => $comment->date,
                            'comment' => nl2br($comment->comment)
                        ];
                    }

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Comments of topic',
                    'topic_id' => $topic_id,
                    'topic_title' => $topic_title,
                    'topic_link' => Helper::topicURL($topic_id),
                    'topic_photo' => ($photo_file != "") ? url("")."/uploads/topics/".$photo_file : null,
                    'comments_count' => count($response_details),
                    'comments' => $response_details
                ];
                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];

                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];

            return response()->json($response, 404);
        }
    }

    public function topic_comment($comment_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($comment_id > 0) {

            // Get topic details
            $Comment = Comment::find($comment_id);

            if (!empty($Comment)) {
                $response_details[] = [
                    'id' => $Comment->id,
                    'name' => $Comment->name,
                    'email' => $Comment->email,
                    'date' => $Comment->date,
                    'comment' => nl2br($Comment->comment)
                ];
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Comment details',
                    'topic_id' => $Comment->topic_id,
                    'comment' => $response_details
                ];

                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];

                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];

            return response()->json($response, 404);
        }
    }

    public function topic_related($topic_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($topic_id > 0) {

            // Get topic details
            $Topics = Topic::where("status", 1)->where("id", $topic_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->get();

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $topic_title = "";
                $photo_file = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            // Empty MSG
                            $response = [
                                'status' => "error",
                                'msg' => 'There is no data'
                            ];
                            return response()->json($response, 404);
                        }
                    }

                    if ($Topic->$title_var != "") {
                        $topic_title = $Topic->$title_var;
                    } else {
                        $topic_title = $Topic->$title_var2;
                    }
                    $photo_file = $Topic->photo_file;

                    // related topics
                    $response_details = [];
                    foreach ($Topic->relatedTopics as $relatedTopic) {
                        if ($relatedTopic->topic->$title_var != "") {
                            $relatedTopic_title = $relatedTopic->topic->$title_var;
                        } else {
                            $relatedTopic_title = $relatedTopic->topic->$title_var2;
                        }
                        $response_details[] = [
                            'id' => $relatedTopic->topic->id,
                            'title' => $relatedTopic_title,
                            'date' => $relatedTopic->topic->date,
                            'photo_file' => ($relatedTopic->topic->photo_file != "") ? url("")."/uploads/topics/".$relatedTopic->topic->photo_file : null,
                        ];
                    }

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Related topics of topic',
                    'topic_id' => $topic_id,
                    'topic_title' => $topic_title,
                    'topic_link' => Helper::topicURL($topic_id),
                    'topic_photo' => ($photo_file != "") ? url("")."/uploads/topics/".$photo_file : null,
                    'related_topics_count' => count($response_details),
                    'related_topics' => $response_details
                ];

                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];

                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];

            return response()->json($response, 404);
        }
    }

    public function topic_fields($topic_id)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($topic_id > 0) {

            // Get topic details
            $Topics = Topic::where("status", 1)->where("id", $topic_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            })->get();

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();
                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $topic_title = "";
                $photo_file = "";

                // Response Details
                $response_details = [];
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            // Empty MSG
                            $response = [
                                'status' => "error",
                                'msg' => 'There is no data'
                            ];
                            return response()->json($response, 404);
                        }
                    }

                    if ($Topic->$title_var != "") {
                        $topic_title = $Topic->$title_var;
                    } else {
                        $topic_title = $Topic->$title_var2;
                    }
                    $photo_file = $Topic->photo_file;

                    // additional fields
                    $response_details = [];
                    foreach ($Topic->webmasterSection->customFields->where("in_page", true) as $customField) {
                        if ($customField->in_page) {
                            $cf_saved_val = "";
                            $cf_saved_val_array = array();
                            if (count($Topic->fields) > 0) {
                                foreach ($Topic->fields as $t_field) {
                                    if ($t_field->field_id == $customField->id) {
                                        if (in_array($customField->type,[7,17])) {
                                            // if multi check
                                            $cf_saved_val_array = explode(", ", $t_field->field_value);
                                            $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                            $cf_details_var2 = "details_".config('smartend.default_language');
                                            if ($customField->$cf_details_var != "") {
                                                $cf_details = $customField->$cf_details_var;
                                            } else {
                                                $cf_details = $customField->$cf_details_var2;
                                            }
                                            $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                            $line_num = 1;
                                            foreach ($cf_details_lines as $cf_details_line) {
                                                if (in_array($line_num, $cf_saved_val_array)) {
                                                    $cf_saved_val .= $cf_details_line.", ";
                                                }
                                                $line_num++;
                                            }
                                            $cf_saved_val = substr($cf_saved_val, 0, -2);
                                        } else {
                                            $cf_saved_val = $t_field->field_value;
                                        }
                                    }
                                }
                            }

                            if (($cf_saved_val != "" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == "$lang")) {
                                $response_details[] = [
                                    'type' => $customField->type,
                                    'title' => $customField->$title_var,
                                    'value' => $cf_saved_val,
                                ];
                            }
                        }
                    }

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'Additional Fields of topic',
                    'topic_id' => $topic_id,
                    'topic_title' => $topic_title,
                    'topic_link' => Helper::topicURL($topic_id),
                    'topic_photo' => ($photo_file != "") ? url("")."/uploads/topics/".$photo_file : null,
                    'fields_count' => count($response_details),
                    'fields' => $response_details
                ];

                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];

                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];

            return response()->json($response, 404);
        }
    }

    public function user_topics($user_id, $page_number = 1, $topics_count = 0)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        if ($user_id > 0) {
            if ($page_number < 1) {
                $page_number = 1;
            }
            Paginator::currentPageResolver(function () use ($page_number) {
                return $page_number;
            });

            // Get topics
            $Topics = Topic::where("status", 1)->where("created_by", $user_id)->where(function ($query){
                $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date', null);
            });

            if ($topics_count > 0) {
                $Topics = $Topics->paginate($topics_count);
            } else {
                $Topics = $Topics->get();
            }

            if (count($Topics) > 0) {
                // By Language
                $lang = $this->getLanguage();

                $title_var = "title_$lang";
                $title_var2 = "title_".config('smartend.default_language');
                $details_var = "details_$lang";
                $details_var2 = "details_".config('smartend.default_language');
                $user_name = "";

                // Response Details
                $response_details = [];
                $ic = 0;
                foreach ($Topics as $Topic) {

                    $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);
                    if (!empty($WebmasterSection)) {
                        // if private redirect back to home
                        if ($WebmasterSection->type == 4 || $WebmasterSection->type == 7) {
                            continue;
                        }
                    }
                    $ic++;
                    $type = $Topic->webmasterSection->type;
                    $section_name = $Topic->webmasterSection->name;
                    $section_id = $Topic->webmasterSection->id;
                    $user_name = $Topic->user->name;


                    $Joined_categories = [];
                    foreach ($Topic->categories as $category) {

                        if ($category->section->$title_var != "") {
                            $category_title = $category->section->$title_var;
                        } else {
                            $category_title = $category->section->$title_var2;
                        }

                        $Joined_categories[] = [
                            'id' => $category->id,
                            'title' => $category_title,
                            'icon' => $category->section->icon,
                            'photo' => ($category->section->photo != "") ? url("")."/uploads/sections/".$category->section->photo : null,
                            'href' => Helper::categoryURL($category->section->id, "", $category->section)
                        ];
                    }

                    // additional fields
                    $Additional_fields = [];
                    foreach ($Topic->webmasterSection->customFields->where("in_listing", true) as $customField) {
                        if ($customField->in_page) {

                            $cf_saved_val = "";
                            $cf_saved_val_array = array();
                            if (count($Topic->fields) > 0) {
                                foreach ($Topic->fields as $t_field) {
                                    if ($t_field->field_id == $customField->id) {
                                        if (in_array($customField->type,[7,17])) {
                                            // if multi check
                                            $cf_saved_val_array = explode(", ", $t_field->field_value);
                                            $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                            $cf_details_var2 = "details_".config('smartend.default_language');
                                            if ($customField->$cf_details_var != "") {
                                                $cf_details = $customField->$cf_details_var;
                                            } else {
                                                $cf_details = $customField->$cf_details_var2;
                                            }
                                            $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                            $line_num = 1;
                                            foreach ($cf_details_lines as $cf_details_line) {
                                                if (in_array($line_num, $cf_saved_val_array)) {
                                                    $cf_saved_val .= $cf_details_line.", ";
                                                }
                                                $line_num++;
                                            }
                                            $cf_saved_val = substr($cf_saved_val, 0, -2);
                                        } else {
                                            $cf_saved_val = $t_field->field_value;
                                        }
                                    }
                                }
                            }

                            if (($cf_saved_val != "" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == "$lang")) {

                                if ($customField->$title_var != "") {
                                    $customField_title = $customField->$title_var;
                                } else {
                                    $customField_title = $customField->$title_var2;
                                }

                                $Additional_fields[] = [
                                    'type' => $customField->type,
                                    'title' => $customField_title,
                                    'value' => $cf_saved_val,
                                ];
                            }
                        }
                    }

                    $video_file = $Topic->video_file;
                    if ($Topic->video_type == 0) {
                        $video_file = ($Topic->video_file != "") ? url("")."/uploads/topics/".$Topic->video_file : "";
                    }

                    if ($Topic->$title_var != "") {
                        $Topic_title = $Topic->$title_var;
                    } else {
                        $Topic_title = $Topic->$title_var2;
                    }
                    if ($Topic->$details_var != "") {
                        $Topic_details = $Topic->$details_var;
                    } else {
                        $Topic_details = $Topic->$details_var2;
                    }

                    $response_details[] = [
                        'id' => $Topic->id,
                        'title' => $Topic_title,
                        'details' => $Topic_details,
                        'date' => $Topic->date,
                        'video_type' => $Topic->video_type,
                        'video_file' => $video_file,
                        'photo_file' => ($Topic->photo_file != "") ? url("")."/uploads/topics/".$Topic->photo_file : null,
                        'audio_file' => ($Topic->audio_file != "") ? url("")."/uploads/topics/".$Topic->audio_file : null,
                        'icon' => $Topic->icon,
                        'visits' => $Topic->visits,
                        'href' => Helper::topicURL($Topic->id, "", $Topic),
                        'fields_count' => count($Additional_fields),
                        'fields' => $Additional_fields,
                        'Joined_categories_count' => count($Topic->categories),
                        'Joined_categories' => $Joined_categories,
                        'section_id' => $section_id,
                        'section_name' => $section_name,
                        'section_type' => $type,

                    ];

                }
                // Response MSG
                $response = [
                    'status' => "success",
                    'msg' => 'List of Topics for user',
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'topics_count' => $ic,
                    'topics' => $response_details
                ];

                return response()->json($response, 200);
            } else {
                // Empty MSG
                $response = [
                    'status' => "error",
                    'msg' => 'There is no data'
                ];

                return response()->json($response, 200);
            }
        } else {
            // Empty MSG
            $response = [
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function ContactPageSubmit(Request $request)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        $this->validate($request, [
            'contact_name' => 'required',
            'contact_email' => 'required|email',
            'contact_subject' => 'required',
            'contact_message' => 'required'
        ]);

        // SITE SETTINGS
        $WebsiteSettings = Setting::find(1);
        $site_title_var = "site_title_".@Helper::currentLanguage()->code;
        $site_email = $WebsiteSettings->site_webmails;
        $site_url = $WebsiteSettings->site_url;
        $site_title = $WebsiteSettings->$site_title_var;

        $Webmail = new Webmail;
        $Webmail->cat_id = 0;
        $Webmail->group_id = null;
        $Webmail->title = $request->contact_subject;
        $Webmail->details = $request->contact_message;
        $Webmail->date = date("Y-m-d H:i:s");
        $Webmail->from_email = $request->contact_email;
        $Webmail->from_name = $request->contact_name;
        $Webmail->from_phone = $request->contact_phone;
        $Webmail->to_email = $WebsiteSettings->site_webmails;
        $Webmail->to_name = $site_title;
        $Webmail->status = 0;
        $Webmail->flag = 0;
        $Webmail->save();

        // SEND Notification Email
        if (@Helper::GeneralSiteSettings('notify_messages_status')) {
            $recipient = explode(",", str_replace(" ", "", $site_email));
            $message_details = __('frontend.name').": ".$request->contact_name."<hr>".__('frontend.phone').": ".$request->contact_phone."<hr>".__('frontend.email').": ".$request->contact_email."<hr>".__('frontend.message').":<br>".nl2br($request->contact_message);

            if (
                config('mail.mailers.smtp.host') &&
                config('mail.mailers.smtp.port') &&
                config('mail.mailers.smtp.username') &&
                config('mail.mailers.smtp.password')
            ) {
                Mail::to($recipient)->send(new NotificationEmail(
                    [
                        "title" => $request->contact_subject,
                        "details" => $message_details,
                        "from_email" => $request->contact_email,
                        "from_name" => $request->contact_name
                    ]
                ));
            }
        }

        // response MSG
        $response = [
            'code' => '1',
            'status' => "success",
            'msg' => 'Message Sent successfully'
        ];
        return response()->json($response, 201);

    }

    public function subscribeSubmit(Request $request)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }

        $this->validate($request, [
            'subscribe_name' => 'required',
            'subscribe_email' => 'required|email'
        ]);

        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        $Contacts = Contact::where('email', $request->subscribe_email)->get();
        if (count($Contacts) > 0) {
            // response MSG
            $response = [
                'code' => '2',
                'status' => "error",
                'msg' => 'You are already subscribed'
            ];
            return response()->json($response, 200);
        } else {
            $subscribe_names = explode(' ', $request->subscribe_name, 2);

            $Contact = new Contact;
            $Contact->group_id = $WebmasterSettings->newsletter_contacts_group;
            $Contact->first_name = @$subscribe_names[0];
            $Contact->last_name = @$subscribe_names[1];
            $Contact->email = $request->subscribe_email;
            $Contact->status = 1;
            $Contact->save();


            // response MSG
            $response = [
                'code' => '1',
                'status' => "success",
                'msg' => 'You have subscribed successfully'
            ];
            return response()->json($response, 201);
        }

    }

    public function commentSubmit(Request $request)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }


        $this->validate($request, [
            'topic_id' => 'required',
            'comment_name' => 'required',
            'comment_email' => 'required|email',
            'comment_message' => 'required'
        ]);

        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        $next_nor_no = Comment::where('topic_id', '=', $request->topic_id)->max('row_no');
        if ($next_nor_no < 1) {
            $next_nor_no = 1;
        } else {
            $next_nor_no++;
        }

        $Comment = new Comment;
        $Comment->row_no = $next_nor_no;
        $Comment->name = $request->comment_name;
        $Comment->email = $request->comment_email;
        $Comment->comment = $request->comment_message;
        $Comment->topic_id = $request->topic_id;;
        $Comment->date = date("Y-m-d H:i:s");
        $Comment->status = $WebmasterSettings->new_comments_status;
        $Comment->save();

        // Site Details
        $WebsiteSettings = Setting::find(1);
        $site_title_var = "site_title_".@Helper::currentLanguage()->code;
        $site_email = $WebsiteSettings->site_webmails;
        $site_url = $WebsiteSettings->site_url;
        $site_title = $WebsiteSettings->$site_title_var;

        // Topic details
        $Topic = Topic::where('status', 1)->find($request->topic_id);
        if (!empty($Topic)) {
            $tpc_title_var = "title_".@Helper::currentLanguage()->code;
            $tpc_title = $WebsiteSettings->$tpc_title_var;

            // SEND Notification Email
            if (@Helper::GeneralSiteSettings('notify_comments_status')) {
                $recipient = explode(",", str_replace(" ", "", $site_email));
                $message_details = __('frontend.name').": ".$request->comment_name."<hr>".__('frontend.email').": ".$request->comment_email."<hr>".__('frontend.comment').":<br>".nl2br($request->comment_message);
                if (
                    config('mail.mailers.smtp.host') &&
                    config('mail.mailers.smtp.port') &&
                    config('mail.mailers.smtp.username') &&
                    config('mail.mailers.smtp.password')
                ) {
                    Mail::to($recipient)->send(new NotificationEmail(
                        [
                            "title" => "Comment: ".$tpc_title,
                            "details" => $message_details,
                            "from_email" => $request->comment_email,
                            "from_name" => $request->comment_name
                        ]
                    ));
                }
            }

            // response MSG
            $response = [
                'code' => '1',
                'status' => "success",
                'msg' => 'Your Comment Sent successfully'
            ];
            return response()->json($response, 201);
        } else {
            // response MSG
            $response = [
                'code' => '0',
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }
    }

    public function orderSubmit(Request $request)
    {
        // api key check
        if (request()->header("token") != Helper::GeneralWebmasterSettings("api_key")) {
            return $this->unauthenticated();
        }


        $this->validate($request, [
            'topic_id' => 'required',
            'order_name' => 'required',
            'order_phone' => 'required',
            'order_email' => 'required|email'
        ]);

        $WebsiteSettings = Setting::find(1);
        $site_title_var = "site_title_".@Helper::currentLanguage()->code;
        $site_email = $WebsiteSettings->site_webmails;
        $site_url = $WebsiteSettings->site_url;
        $site_title = $WebsiteSettings->$site_title_var;

        $Topic = Topic::where('status', 1)->find($request->topic_id);
        if (!empty($Topic)) {
            $tpc_title_var = "title_".@Helper::currentLanguage()->code;
            $tpc_title = $WebsiteSettings->$tpc_title_var;

            $Webmail = new Webmail;
            $Webmail->cat_id = 0;
            $Webmail->group_id = null;
            $Webmail->contact_id = null;
            $Webmail->father_id = null;
            $Webmail->title = "ORDER ".", ".$Topic->$tpc_title_var;
            $Webmail->details = $request->order_message;
            $Webmail->date = date("Y-m-d H:i:s");
            $Webmail->from_email = $request->order_email;
            $Webmail->from_name = $request->order_name;
            $Webmail->from_phone = $request->order_phone;
            $Webmail->to_email = $WebsiteSettings->site_webmails;
            $Webmail->to_name = $WebsiteSettings->$site_title_var;
            $Webmail->status = 0;
            $Webmail->flag = 0;
            $Webmail->save();


            // SEND Notification Email
            if (@Helper::GeneralSiteSettings('notify_orders_status')) {
                $recipient = explode(",", str_replace(" ", "", $site_email));
                $message_details = __('frontend.name').": ".$request->order_name."<hr>".__('frontend.phone').": ".$request->order_phone."<hr>".__('frontend.email').": ".$request->order_email."<hr>".__('frontend.notes').":<br>".nl2br($request->order_message);

                if (
                    config('mail.mailers.smtp.host') &&
                    config('mail.mailers.smtp.port') &&
                    config('mail.mailers.smtp.username') &&
                    config('mail.mailers.smtp.password')
                ) {
                    Mail::to($recipient)->send(new NotificationEmail(
                        [
                            "title" => "Order: ".$tpc_title,
                            "details" => $message_details,
                            "from_email" => $request->order_email,
                            "from_name" => $request->order_name
                        ]
                    ));
                }
            }

            // response MSG
            $response = [
                'code' => '1',
                'status' => "success",
                'msg' => 'Your Order Sent successfully'
            ];
            return response()->json($response, 201);
        } else {
            // response MSG
            $response = [
                'code' => '0',
                'status' => "error",
                'msg' => 'There is no data'
            ];
            return response()->json($response, 404);
        }

    }

}
