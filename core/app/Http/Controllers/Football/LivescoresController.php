<?php

namespace App\Http\Controllers\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class LivescoresController extends Controller
{
    public function index()
    {
        $this->website_status();
        $liveMatches = Fixture::where('is_finished', false)
            ->where(function ($query) {
                $query->where('starting_at', '<=', now()->addHours(3))
                    ->orWhereNull('starting_at');
            })
            ->get();
        $PageTitle = __('frontend.live_matches');
        $PageDescription = __('frontend.live_matches');
        return view('frontEnd.football.livescores', compact('liveMatches', 'PageTitle', 'PageDescription'));
    }

    public function website_status()
    {
        // Check the website Status
        if (!Auth::check()) {
            $site_status = Helper::GeneralSiteSettings("site_status");
            if ($site_status == 0) {
                echo view("frontEnd.closed", ["close_message" => Helper::GeneralSiteSettings("close_msg")])->render();
                exit();
            }
        }
    }
}
