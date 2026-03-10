<?php
namespace App\Helpers;

use App;
use App\Models\Menu;
use URL; 

class SiteMenu
{
    static function List($GroupId)
    {
        $MenuLinks = Menu::where('father_id', $GroupId)->where('status', 1)->orderby('row_no', 'asc')->get();
        return SiteMenu::ParseLinks($MenuLinks);
    }

    static function ParseLinks($MenuLinks = [])
    {
        $Links = [];
        if (!empty($MenuLinks)) {
            $_title_var = "title_" . @Helper::currentLanguage()->code;
            $_title_var2 = "title_" . config('smartend.default_language');
            $_link_var = "link_" . @Helper::currentLanguage()->code;

            foreach ($MenuLinks as $MenuLink) {
                if ($MenuLink->$_title_var != "") {
                    $link_title = $MenuLink->$_title_var;
                } else {
                    $link_title = $MenuLink->$_title_var2;
                }

                if ($MenuLink->type == 3) {
                    // Section with drop list
                    $Sub = [];
                    if (count($MenuLink->webmasterSection->sections) > 0) {
                        // category list
                        foreach ($MenuLink->webmasterSection->sections->where('status', 1) as $MnuCategory) {
                            if ($MnuCategory->father_id == 0) {
                                // get sub cats
                                $Sub2 = [];
                                $SubCategories = $MnuCategory->fatherSections->where("status",1);
                                foreach ($SubCategories ->where('status', 1) as $SubCategory) {
                                    $Sub3 = [];
                                    $SubCategories2 = $SubCategory->fatherSections->where("status",1);
                                    foreach ($SubCategories2->where('status', 1) as $SubCategory2) {
                                        if ($SubCategory2->$_title_var != "") {
                                            $category2_title = $SubCategory2->$_title_var;
                                        } else {
                                            $category2_title = $SubCategory2->$_title_var2;
                                        }
                                        $Sub3[] = [
                                            "id" => $SubCategory2->id,
                                            "title" => $category2_title,
                                            "url" => Helper::categoryURL($SubCategory2->id,"",$SubCategory2),
                                            "target" => "",
                                            "icon" => ($SubCategory2->icon != "") ? "fa " . $SubCategory2->icon : "",
                                            "category_id" => $SubCategory2->id,
                                            "webmaster_id" => $MenuLink->cat_id,
                                            "sub" => [],
                                        ];
                                    }
                                    if ($SubCategory->$_title_var != "") {
                                        $category_title = $SubCategory->$_title_var;
                                    } else {
                                        $category_title = $SubCategory->$_title_var2;
                                    }
                                    $Sub2[] = [
                                        "id" => $SubCategory->id,
                                        "title" => $category_title,
                                        "url" => Helper::categoryURL($SubCategory->id,"",$SubCategory),
                                        "target" => "",
                                        "icon" => ($SubCategory->icon != "") ? "fa " . $SubCategory->icon : "",
                                        "category_id" => $SubCategory->id,
                                        "webmaster_id" => $MenuLink->cat_id,
                                        "sub" => $Sub3,
                                    ];
                                }
                                if ($MnuCategory->$_title_var != "") {
                                    $category_title = $MnuCategory->$_title_var;
                                } else {
                                    $category_title = $MnuCategory->$_title_var2;
                                }
                                $Sub[] = [
                                    "id" => $MnuCategory->id,
                                    "title" => $category_title,
                                    "url" => Helper::categoryURL($MnuCategory->id,"",$MnuCategory),
                                    "target" => "",
                                    "icon" => ($MnuCategory->icon != "") ? "fa " . $MnuCategory->icon : "",
                                    "category_id" => $MnuCategory->id,
                                    "webmaster_id" => $MenuLink->cat_id,
                                    "sub" => $Sub2,
                                ];
                            }
                        }
                    } elseif (count($MenuLink->webmasterSection->topics) > 0) {
                        // topics list
                        foreach ($MenuLink->webmasterSection->topics->where('status', 1) as $MnuTopic) {
                            if ($MnuTopic->status && ($MnuTopic->expire_date == '' || ($MnuTopic->expire_date != '' && $MnuTopic->expire_date >= date("Y-m-d")))) {
                                if ($MnuTopic->$_title_var != "") {
                                    $topic_title = $MnuTopic->$_title_var;
                                } else {
                                    $topic_title = $MnuTopic->$_title_var2;
                                }
                                $Sub[] = [
                                    "id" => $MnuTopic->id,
                                    "title" => $topic_title,
                                    "url" => Helper::topicURL($MnuTopic->id,"",$MnuTopic),
                                    "target" => "",
                                    "icon" => ($MnuTopic->icon != "") ? "fa " . $MnuTopic->icon : "",
                                    "category_id" => 0,
                                    "webmaster_id" => $MenuLink->cat_id,
                                    "sub" => [],
                                ];
                            }
                        }
                    }
                    $Links[] = [
                        "id" => $MenuLink->id,
                        "title" => $link_title,
                        "url" => Helper::sectionURL($MenuLink->cat_id),
                        "target" => ($MenuLink->target) ? "_blank" : "",
                        "icon" => ($MenuLink->icon != "") ? "fa " . $MenuLink->icon : "",
                        "category_id" => 0,
                        "webmaster_id" => $MenuLink->cat_id,
                        "sub" => $Sub,
                    ];
                } elseif ($MenuLink->type == 2) {
                    // Section Link
                    $Links[] = [
                        "id" => $MenuLink->id,
                        "title" => $link_title,
                        "url" => Helper::sectionURL($MenuLink->cat_id),
                        "target" => ($MenuLink->target) ? "_blank" : "",
                        "icon" => ($MenuLink->icon != "") ? "fa " . $MenuLink->icon : "",
                        "category_id" => 0,
                        "webmaster_id" => $MenuLink->cat_id,
                        "sub" => [],
                    ];
                } elseif ($MenuLink->type == 1) {
                    // Direct Link
                    $this_link_url = "";
                    $link = $MenuLink->link;
                    if(@$MenuLink->$_link_var !=""){
                        $link = @$MenuLink->$_link_var;
                    }
                    if ($link != "") {
                        if (@Helper::currentLanguage()->code != config('smartend.default_language')) {
                            $f3c = mb_substr($link, 0, 3);
                            if ($f3c == "htt" || $f3c == "www") {
                                $this_link_url = $link;
                            } else {
                                $this_link_url = url(@Helper::currentLanguage()->code . "/" . $link);
                            }
                        } else {
                            $this_link_url = url($link);
                        }
                    }
                    $Links[] = [
                        "id" => $MenuLink->id,
                        "title" => $link_title,
                        "url" => $this_link_url,
                        "target" => ($MenuLink->target) ? "_blank" : "",
                        "icon" => ($MenuLink->icon != "") ? "fa " . $MenuLink->icon : "",
                        "category_id" => 0,
                        "webmaster_id" => 0,
                        "sub" => [],
                    ];
                } else {
                    // Main title ( have drop down menu )
                    $this_link_url = "#";
                    $link = $MenuLink->link;
                    if(@$MenuLink->$_link_var !=""){
                        $link = @$MenuLink->$_link_var;
                    }
                    if ($link != "") {
                        if (@Helper::currentLanguage()->code != config('smartend.default_language')) {
                            $f3c = mb_substr($link, 0, 3);
                            if ($f3c == "htt" || $f3c == "www") {
                                $this_link_url = $link;
                            } else {
                                $this_link_url = url(@Helper::currentLanguage()->code . "/" . $link);
                            }
                        } else {
                            $this_link_url = url($link);
                        }
                    }
                    $Links[] = [
                        "id" => $MenuLink->id,
                        "title" => $link_title,
                        "url" => $this_link_url,
                        "target" => ($MenuLink->target) ? "_blank" : "",
                        "icon" => ($MenuLink->icon != "") ? "fa " . $MenuLink->icon : "",
                        "category_id" => 0,
                        "webmaster_id" => 0,
                        "sub" => SiteMenu::ParseLinks($MenuLink->subMenus->where("status",1)),
                    ];
                }
            }
        }
        return json_decode(json_encode($Links));
    }

    static function ActiveLink($CurrentURL = "", $MenuLink = [], $WebmasterSection = [])
    {
        if (!empty($MenuLink)) {

            if ($CurrentURL == rtrim(config('app.url'), '/') && @$MenuLink->url == "/") {
                return "active";
            }

            if ($CurrentURL == URL::to("/") && @$MenuLink->url == "/") {
                return "active";
            }
            
            if ($CurrentURL == @$MenuLink->url || urldecode($CurrentURL) == urldecode(@$MenuLink->url)) {
                return "active";
            }
            if (!empty($WebmasterSection)) {
                if (@$MenuLink->webmaster_id == @$WebmasterSection->id && config('app.url') != $CurrentURL && $CurrentURL != URL::to("/") && $CurrentURL != route("NotFound")) {
                    return "active";
                }
            }

            if ((rtrim(config('app.url'), '/') == $CurrentURL && (@$MenuLink->url == URL::to("/" . @Helper::currentLanguage()->code) || @$MenuLink->url == URL::to("/") || @$MenuLink->url == URL::to("/")))) {
                return "active";
            }
        }
        return "";
    }
}

?>
