<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Section;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\WebmasterSection;
use Helper;


class SiteMapController extends Controller
{
    // add topic ids to hide from sitemap like [123,456,89,...]
    private $ExceptTopicIds = [];

    // add category ids to hide from sitemap like [12,34,56,...]
    private $ExceptCategoryIds = [];

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $lang
     * @return \Illuminate\Http\Response
     */
    public function siteMap($lang = "")
    {
        $lang = $this->getLanguage($lang);

        \Session::put('locale', $lang);

        $SiteMapDetails = "";
        $slug_var = "seo_url_slug_".$lang;

        // HOME
        $url_link = url("");
        $SiteMapDetails .= "
<url>
    <loc>$url_link</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
</url>
            ";

        // Main Site Sections
        $WebmasterSections = WebmasterSection::where("status", 1)->where("id", "!=", 1)->orderby('row_no',
            'asc')->get();
        $ActiveWebmasterSectionIds = $WebmasterSections->pluck('id')->toarray();
        foreach ($WebmasterSections as $WebmasterSection) {
            if ($WebmasterSection->type != 4) {
                $url_link = Helper::sectionURL($WebmasterSection->id, "", $WebmasterSection);
                $url_time = date("c", strtotime($WebmasterSection->updated_at));

                $SiteMapDetails .= "
<url>
    <loc>$url_link</loc>
    <lastmod>$url_time</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
</url>
            ";
            }
        }


        // Categories
        $Sections = Section::whereIn("webmaster_id", $ActiveWebmasterSectionIds)->where("status", 1)->orderby('row_no',
            'asc')->get();
        foreach ($Sections as $Section) {
            if (!in_array($Section->id, $this->ExceptCategoryIds)) {
                $url_link = Helper::categoryURL($Section->id, "", $Section);
                $url_time = date("c", strtotime($Section->updated_at));

                $SiteMapDetails .= "
<url>
    <loc>$url_link</loc>
    <lastmod>$url_time</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
</url>
            ";
            }
        }

        // Topics
        $ExceptTopicIds = [];
        $Topics = Topic::where("status", 1)->where(function ($query) {
            $query->where([['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere('expire_date',
                null);
        })->orderby('id', 'desc')->get();

        foreach ($Topics as $Topic) {
            if (in_array($Topic->webmaster_id, $ActiveWebmasterSectionIds) && !in_array($Topic->id,
                    $this->ExceptTopicIds)) {
                if (!empty($Topic->webmasterSection)) {
                    if ($Topic->webmasterSection->type != 4) {
                        $url_link = Helper::topicURL($Topic->id, "", $Topic);
                        $url_time = date("c", strtotime($Topic->updated_at));

                        $SiteMapDetails .= "
<url>
    <loc>$url_link</loc>
    <lastmod>$url_time</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
</url>
            ";
                    }
                }
            }
        }

        $Tags = Tag::withCount([
            'topics' => function ($query) {
                $query->where('status', 1);
            }
        ])->orderBy('topics_count', 'desc')->get();
        foreach ($Tags as $Tag) {
            $url_link = route("tag", @$Tag->seo_url);
            $url_time = date("c", strtotime($Tag->updated_at));
            $SiteMapDetails .= "
<url>
    <loc>$url_link</loc>
    <lastmod>$url_time</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
</url>
            ";
        }

        return response()->view("frontEnd.sitemap", compact("SiteMapDetails"))->header('Content-Type', 'text/xml');
    }

    /**
     * Language Check
     */
    public function getLanguage($lang)
    {
        // List of active languages for API
        $Language = Language::where("status", true)->where("code", $lang)->first();
        if ($lang == "" || empty($Language)) {
            $lang = config('smartend.default_language');
        }
        return $lang;
    }
}
