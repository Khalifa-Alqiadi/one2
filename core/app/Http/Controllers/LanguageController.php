<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    //
    public function index(Request $request)
    {
        $lang = $request->input('locale');
        App::setLocale($lang);
        session(['locale' => $lang]);
        return redirect()->back();

    }

    public function change($lang)
    {
        App::setLocale($lang);
        session(['locale' => $lang]);

        try {
            $prev_url = url()->previous();
            $Languages = Language::all();
            foreach ($Languages as $Language) {
                if ($lang == config('smartend.default_language') && config('smartend.default_language') != "") {
                    $prev_url = str_replace("/" . $Language->code . "/", "/", $prev_url);
                } else {
                    $prev_url = str_replace("/" . $Language->code . "/", "/" . $lang . "/", $prev_url);
                }
            }
            return redirect()->to($prev_url);
        } catch (\Exception $e) {
            return redirect()->route("Home");
        }
    }

    public function locale($lang)
    {
        App::setLocale($lang);
        session(['locale' => $lang]);
        return redirect()->back();

    }
}
