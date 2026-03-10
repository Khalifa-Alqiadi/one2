<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use App\Helpers\Helper;

class Topic extends Model implements Feedable
{
    use HasFactory;

    public function toFeedItem(): FeedItem
    {
        $lang = @Helper::currentLanguage()->code;
        if (request()->input("lang") != "" && request()->input("lang") != null) {
            $lang = request()->input("lang");
        }
        return FeedItem::create()
            ->id(@$this->id)
            ->title((@$this->{"title_".$lang} != "") ? @$this->{"title_".$lang} : "None")
            ->summary((@$this->{"details_".$lang} != "") ? @$this->{"details_".$lang} : "None")
            ->updated(@$this->updated_at)
            ->link(Helper::topicURL(@$this->id, $lang))
            ->authorName(Helper::GeneralSiteSettings("site_url"));
    }

    public static function getFeedTopics()
    {
        $section_id = request()->input("section");
        $WebSection = WebmasterSection::find($section_id);
        if (!empty($WebSection)) {
            if ($WebSection->type == 0) {
                return Topic::where("status", 1)->where("webmaster_id", $section_id)->where(function ($query) {
                    $query->where([
                        ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]
                    ])->orWhere('expire_date', null);
                })->orderby('row_no', config('smartend.frontend_topics_order'))->get();
            }
        }
        return Topic::where("id", 0)->get();
    }

    public function webmasterSection()
    {
        return $this->belongsTo('App\Models\WebmasterSection', 'webmaster_id');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Section', 'section_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\TopicCategory');
    }
    public function category($TopicID)
    {
        $Category = null;
        $TopicCategory = TopicCategory::where('topic_id', $TopicID)->first();
        if (!empty($TopicCategory)) {
            $Category = Section::find($TopicCategory->section_id);
        }
        return $Category;
    }
    public function from_topic()
    {
        return $this->belongsTo('App\Models\Topic', 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updated_user()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function form()
    {
        return $this->belongsTo('App\Models\WebmasterSection', 'form_id');
    }

    public function photos()
    {
        return $this->hasMany('App\Models\Photo', 'topic_id')->orderby('row_no', 'asc');
    }

    public function attachFiles()
    {
        return $this->hasMany('App\Models\AttachFile', 'topic_id')->orderby('row_no', 'asc');
    }

    public function relatedTopics()
    {
        return $this->hasMany('App\Models\RelatedTopic', 'topic_id')->orderby('row_no', 'asc');
    }

    public function maps()
    {
        return $this->hasMany('App\Models\Map', 'topic_id')->orderby('row_no', 'asc');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'topic_id')->orderby('row_no', 'asc');
    }

    public function newComments()
    {
        return $this->hasMany('App\Models\Comment', 'topic_id')->where('status', '=', 0)->orderby('row_no', 'asc');
    }

    public function approvedComments()
    {
        return $this->hasMany('App\Models\Comment', 'topic_id')->where('status', '=', 1)->orderby('row_no', 'asc');
    }

    public function fields()
    {
        return $this->hasMany('App\Models\TopicField', 'topic_id')->orderby('id', 'asc');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\TopicTag');
    }

    public function popup()
    {
        return $this->belongsTo('App\Models\Popup', 'popup_id');
    }

    public function topicBlocks()
    {
        return $this->hasMany('App\Models\TopicBlock', 'topic_id')->orderby('row_no', 'asc');
    }

}
