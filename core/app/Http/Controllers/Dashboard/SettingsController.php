<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WebmasterSection;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Redirect;
use Helper;

class SettingsController extends Controller
{
    private $uploadPath = "settings";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {

        // Check Permissions
        if (!@Auth::user()->permissionsGroup->settings_status || !Helper::GeneralWebmasterSettings("settings_status")) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        $id = 1;
        $Setting = Setting::find($id);
        if (!empty($Setting)) {
            return view("dashboard.settings.settings", compact("Setting", "GeneralWebmasterSections"));

        } else {
            return redirect()->route('adminHome');
        }
    }

    public function updateSiteInfo(Request $request)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->settings_status || !Helper::GeneralWebmasterSettings("settings_status")) {
            return redirect()->route("NoPermission");
        }

        $id = 1;
        $Setting = Setting::find($id);
        if (!empty($Setting)) {

            foreach (Helper::languagesList() as $ActiveLanguage) {

                // Start of Upload Files
                $fileFinalName = null;
                $formFileName = "style_logo_".$ActiveLanguage->code;
                if ($request->hasFile($formFileName)) {
                    // validate image
                    $this->validate($request, [
                        $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                    ]);
                    $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 0);
                    $fileFinalName = @$FileInfo['name'];
                }

                //save file name
                if ($fileFinalName != "") {
                    // Delete a banner file
                    if ($Setting->{"style_logo_".$ActiveLanguage->code} != "" && $Setting->{"style_logo_".$ActiveLanguage->code} != "nologo.png") {
                        FileHelper::deleteFile($this->uploadPath."/".$Setting->{"style_logo_".$ActiveLanguage->code});
                    }

                    $Setting->{"style_logo_".$ActiveLanguage->code} = $fileFinalName;
                }

                $Setting->{"site_title_".$ActiveLanguage->code} = strip_tags($request->{"site_title_".$ActiveLanguage->code});
                $Setting->{"site_desc_".$ActiveLanguage->code} = strip_tags($request->{"site_desc_".$ActiveLanguage->code});
                $Setting->{"site_keywords_".$ActiveLanguage->code} = strip_tags($request->{"site_keywords_".$ActiveLanguage->code});
                $Setting->{"contact_t1_".$ActiveLanguage->code} = strip_tags($request->{"contact_t1_".$ActiveLanguage->code});
                $Setting->{"contact_t7_".$ActiveLanguage->code} = strip_tags($request->{"contact_t7_".$ActiveLanguage->code});
            }
            $Setting->site_webmails = $request->site_webmails;
            $Setting->notify_messages_status = $request->notify_messages_status;
            $Setting->notify_comments_status = $request->notify_comments_status;
            $Setting->notify_orders_status = $request->notify_orders_status;
            $Setting->notify_table_status = $request->notify_table_status;
            $Setting->notify_private_status = $request->notify_private_status;
            $Setting->site_url = $request->site_url;


            // Start of Upload Files
            $fileFinalName2 = null;
            $formFileName = "style_fav";
            if ($request->hasFile($formFileName)) {
                // validate image
                $this->validate($request, [
                    $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                ]);
                $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 0);
                $fileFinalName2 = @$FileInfo['name'];

                // Delete a style_fav photo
                if ($Setting->style_fav != "" && $Setting->style_fav != "nofav.png") {
                    FileHelper::deleteFile($this->uploadPath."/".$Setting->style_fav);
                }
            }
            // Start of Upload Files
            $fileFinalName3 = null;
            $formFileName = "style_apple";
            if ($request->hasFile($formFileName)) {
                // validate image
                $this->validate($request, [
                    $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                ]);
                $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 0);
                $fileFinalName3 = @$FileInfo['name'];

                // Delete a style_apple photo
                if ($Setting->style_apple != "" && $Setting->style_apple != "nofav.png") {
                    FileHelper::deleteFile($this->uploadPath."/".$Setting->style_apple);
                }
            }
            // Start of Upload Files
            $fileFinalName4 = null;
            $formFileName = "style_bg_image";
            if ($request->hasFile($formFileName)) {
                // validate image
                $this->validate($request, [
                    $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                ]);
                $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 0);
                $fileFinalName4 = @$FileInfo['name'];

                // Delete a style_bg_image photo
                if ($Setting->style_bg_image != "") {
                    FileHelper::deleteFile($this->uploadPath."/".$Setting->style_bg_image);
                }
            }
            // Start of Upload Files
            $fileFinalName5 = null;
            $formFileName = "style_footer_bg";
            if ($request->hasFile($formFileName)) {
                // validate image
                $this->validate($request, [
                    $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                ]);
                $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 0);
                $fileFinalName5 = @$FileInfo['name'];

                // Delete a style_bg_image photo
                if ($Setting->style_footer_bg != "" && $Setting->style_footer_bg != "footer-bg.webp") {
                    FileHelper::deleteFile($this->uploadPath."/".$Setting->style_footer_bg);
                }
            }

            // End of Upload Files
            if ($fileFinalName2 != "") {
                $Setting->style_fav = $fileFinalName2;
            }
            if ($fileFinalName3 != "") {
                $Setting->style_apple = $fileFinalName3;
            }

            $Setting->style_color1 = $request->style_color1;
            $Setting->style_color2 = $request->style_color2;
            $Setting->style_color3 = $request->style_color3;
            $Setting->style_color4 = $request->style_color4;
            $Setting->style_type = ($request->style_type) ? 1 : 0;
            $Setting->style_change = ($request->style_change) ? 1 : 0;
            $Setting->style_bg_type = $request->style_bg_type;
            $Setting->style_bg_pattern = $request->style_bg_pattern;
            $Setting->style_bg_color = $request->style_bg_color;
            if ($fileFinalName4 != "") {
                $Setting->style_bg_image = $fileFinalName4;
            }
            $Setting->style_subscribe = $request->style_subscribe;
            $Setting->style_footer = $request->style_footer;
            $Setting->style_header = $request->style_header;
            if ($request->photo_delete == 1) {
                // Delete style_footer_bg
                if ($Setting->style_footer_bg != "" && $Setting->style_footer_bg != "footer-bg.webp") {
                    FileHelper::deleteFile($this->uploadPath."/".$Setting->style_footer_bg);
                }
                $Setting->style_footer_bg = "";
            }

            if ($fileFinalName5 != "") {
                $Setting->style_footer_bg = $fileFinalName5;
            }
            $Setting->style_preload = $request->style_preload;
            $Setting->css = $request->css_code;
            $Setting->js = $request->js_code;
            $Setting->body = $request->body_code;
            $Setting->whatsapp_no = $request->whatsapp_no;

            $Setting->contact_t3 = $request->contact_t3;
            $Setting->contact_t4 = $request->contact_t4;
            $Setting->contact_t5 = $request->contact_t5;
            $Setting->contact_t6 = $request->contact_t6;

            $Setting->site_status = $request->site_status;
            $Setting->close_msg = $request->close_msg;


            $Setting->updated_by = Auth::user()->id;

            $Setting->save();

            Cache::forget('_Loader_Web_Settings');

            return redirect()->action([SettingsController::class, 'edit'])
                ->with('doneMessage', __('backend.saveDone'))
                ->with('active_tab', $request->active_tab);

        } else {
            return redirect()->route('adminHome');
        }
    }

    public function getSocialLinks()
    {
        $settings = Setting::first();
        $socialLinks = [];

        if ($settings && $settings->social_links) {
            $socialLinks = json_decode($settings->social_links, true);
        }

        return response()->json([
            'social_links' => $socialLinks
        ]);
    }

    public function saveSocialLink(Request $request)
    {
        $request->validate([
            'social_link.title' => 'required|string|max:255',
            'social_link.url' => 'required',
            'social_link.icon' => 'required|string|max:255',
            'social_link.color' => 'required|string|max:255',
        ]);

        $settings = Setting::firstOrNew([]);
        $socialLinks = [];

        if ($settings->social_links) {
            $socialLinks = json_decode($settings->social_links, true);
        }

        $newLink = $request->social_link;

        // If row_id is not provided (new link), determine the next available
        if (empty($newLink['row_id'])) {
            $nextRowID = 1;
            if (!empty($socialLinks)) {
                $rowNos = array_column($socialLinks, 'row_id');
                $nextRowID = max($rowNos) + 1;
            }
            $newLink['row_id'] = $nextRowID;

            $nextRowNo = 1;
            if (!empty($socialLinks)) {
                $rowNos = array_column($socialLinks, 'row_no');
                $nextRowNo = max($rowNos) + 1;
            }
            $newLink['row_no'] = $nextRowNo;
        } else {
            // For existing links, find and update
            $existingIndex = array_search($newLink['row_id'], array_column($socialLinks, 'row_id'));
        }

        if (isset($existingIndex) && $existingIndex !== false) {
            // Update existing link
            $newLink['row_no'] = @$socialLinks[$existingIndex]['row_no'];
            $socialLinks[$existingIndex] = $newLink;
        } else {
            // Add new link
            $socialLinks[] = $newLink;
        }

        $settings->social_links = json_encode(array_values($socialLinks));
        $settings->save();

        Cache::forget('_Loader_Web_Settings');

        return response()->json([
            'message' => __('backend.saveDone')
        ]);
    }

    public function deleteSocialLink(Request $request)
    {
        $request->validate([
            'row_id' => 'required'
        ]);

        $settings = Setting::first();

        if ($settings && $settings->social_links) {
            $socialLinks = json_decode($settings->social_links, true);
            $filteredLinks = array_filter($socialLinks, function ($link) use ($request) {
                return $link['row_id'] != $request->row_id;
            });

            $settings->social_links = json_encode(array_values($filteredLinks));
            $settings->save();
        }

        Cache::forget('_Loader_Web_Settings');

        return response()->json([
            'message' => __('backend.deleteDone')
        ]);
    }

    public function saveSocialLinkOrder(Request $request)
    {
        $request->validate([
            'order_data' => 'required|array',
            'order_data.*.row_id' => 'required|integer',
            'order_data.*.new_position' => 'required|integer',
        ]);

        $settings = Setting::firstOrNew([]);
        $socialLinks = $settings->social_links ? json_decode($settings->social_links, true) : [];

        if (empty($socialLinks)) {
            return response()->json([
                'success' => false,
                'message' => __('No social links found to reorder')
            ], 404);
        }

        // Create a mapping of row_no to social link
        $linksByRowID = [];
        foreach ($socialLinks as $link) {
            $linksByRowID[$link['row_id']] = $link;
        }

        // Rebuild the array in the new order
        $reorderedLinks = [];
        foreach ($request->order_data as $orderItem) {
            $rowID = $orderItem['row_id'];
            if (isset($linksByRowID[$rowID])) {
                // Update the row_no to the new position
                $link = $linksByRowID[$rowID];
                $link['row_no'] = $orderItem['new_position'];
                $reorderedLinks[] = $link;
            }
        }

        // Sort by the new row_no to maintain order
        usort($reorderedLinks, function($a, $b) {
            return $a['row_no'] <=> $b['row_no'];
        });

        // Save the reordered links
        $settings->social_links = $reorderedLinks;
        $settings->save();

        Cache::forget('_Loader_Web_Settings');

        return response()->json([
            'message' => __('backend.saveDone')
        ]);
    }
}
