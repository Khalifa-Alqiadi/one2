<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use App\Models\Banner;
use App\Mail\NotificationEmail;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\TopicField;
use App\Models\User;
use App\Models\Webmail;
use App\Models\WebmasterSection;
use App\Models\WebmasterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Mail;
use Redirect;
use Helper;
use Auth;

class CustomController extends Controller
{

    public function custom_page()
    {
        $PageTitle = "Custom page";

        return view('frontEnd.custom.page', [
            "PageTitle" => $PageTitle,
        ]);
    }

}
