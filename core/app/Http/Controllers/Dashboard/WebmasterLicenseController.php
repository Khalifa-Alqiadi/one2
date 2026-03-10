<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\WebmasterSetting;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Mail;
use Redirect;
use ZipArchive;

class WebmasterLicenseController extends Controller
{
    private $ItemID = "19184332";
    private $ApiURL = "https://smartend.app/api/license/index.php";
    private $Error = "";
    private $Beta = false;


    public function index(Request $request)
    {
        $this->Error = __('backend.error');
        if ($request->action == "check_license" && $request->purchase_code != "") {
            if ($this->check_license($request->purchase_code)) {
                return response()->json(['status' => 'success', 'msg' => __('backend.licenseSuccess')]);
            }
        } elseif ($request->action == "check_updates") {
            $response = $this->check_updates();
            if (in_array(@$response['status'], ["success", "upto_date", "upgrade", "blocked"])) {
                return response()->json($response);
            }
        } elseif ($request->action == "install_updates") {
            if ($this->system_update()) {
                return [
                    "status" => "updated", "msg" => "<i class='fa fa-check-circle'></i> ".__('backend.updateCompleted'),
                    "version" => "10.0.0"
                ];
            }
        } elseif ($request->action == "re_activate") {
            $WebmasterSetting = WebmasterSetting::find(1);
            if (!empty($WebmasterSetting)) {
                $WebmasterSetting->license = 1;
                $WebmasterSetting->purchase_code = null;
                $WebmasterSetting->save();
            }
        }
        return response()->json(['status' => 'error', 'msg' => $this->Error]);
    }

    private function api_call($action, $purchase_code, $current_version)
    {
        $request = [
            "item_id" => $this->ItemID,
            "beta" => $this->Beta,
            "action" => $action,
            "purchase_code" => $purchase_code,
            "version" => $current_version,
            "website" => @$_SERVER['SERVER_NAME'],
        ];
        return Http::withBody(json_encode($request), "text/plain;charset=UTF-8")->post($this->ApiURL);
    }

    private function check_license($purchase_code)
    {
        try {
            $WebmasterSetting = WebmasterSetting::find(1);
            $response = $this->api_call("check_license", $purchase_code, @$WebmasterSetting->version);
            if (@$response['status'] == "success") {
                $WebmasterSetting->license = 1;
                $WebmasterSetting->purchase_code = encrypt($purchase_code);
                $WebmasterSetting->save();
                Cache::forget('_Loader_WebmasterSettings');
                return 1;
            } elseif (@$response['msg'] != "") {
                $this->Error = @$response['msg'];
            }
        } catch (\Exception $e) {

        }
        return 0;
    }

    private function check_updates()
    {
        try {
            $WebmasterSetting = WebmasterSetting::find(1);
            try {
                $purchase_code = decrypt(@$WebmasterSetting->purchase_code);
            } catch (\Exception $e) {
                $purchase_code = "";
                $WebmasterSetting->purchase_code = null;
                $WebmasterSetting->license = 0;
                $WebmasterSetting->save();
            }
            $response = $this->api_call("check_update", $purchase_code, @$WebmasterSetting->version);
            if (@$response['status'] == "success") {
                if (@$WebmasterSetting->version == @$response['version'] || (int) @$WebmasterSetting->version > (int) @$response['version']) {
                    return [
                        "status" => "upto_date",
                        "msg" => "<span class='text-lg'><strong><i class='fa fa-check-circle'></i> ".__('backend.licensedVersion')."</strong></span><br>".__('backend.websiteUptoDate'),
                        "version" => @$WebmasterSetting->version
                    ];
                } else {
                    if (version_compare(PHP_VERSION, @$response['php']) >= 0) {
                        return [
                            "status" => "success", "php" => @$response['php'], "version" => @$response['version'],
                            "date" => @$response['date'], "change_log" => nl2br(@$response['change_log'])
                        ];
                    } else {
                        return [
                            "status" => "upgrade", "php" => @$response['php'], "version" => @$response['version'],
                            "date" => @$response['date'], "change_log" => nl2br(@$response['change_log'])
                        ];
                    }
                }
            } elseif (@$response['status'] == "blocked") {
                $this->Error = @$response['msg'];
                return ["status" => "blocked", "msg" => @$response['msg']];
            } elseif (@$response['msg'] != "") {
                $this->Error = @$response['msg'];
            }
        } catch (\Exception $e) {

        }
        return ["status" => "failed"];
    }

    private function system_update()
    {
        try {
            $WebmasterSetting = WebmasterSetting::find(1);
            try {
                $purchase_code = decrypt(@$WebmasterSetting->purchase_code);
            } catch (\Exception $e) {
                $purchase_code = "";
                $WebmasterSetting->purchase_code = null;
                $WebmasterSetting->license = 0;
                $WebmasterSetting->save();
            }
            $response = $this->api_call("check_update", $purchase_code, @$WebmasterSetting->version);
            if (@$response['file'] != "" && @$WebmasterSetting->version != @$response['version'] && version_compare(PHP_VERSION,
                    @$response['php']) >= 0) {
                // check file structure
                $root_path = str_replace("/core", "/", base_path());
                if (!file_exists(base_path()."/.env")) {
                    return 0;
                }

                // get DB info
                $DB_HOST = config('database.connections.mysql.host');
                $DB_PORT = config('database.connections.mysql.port');
                $DB_DATABASE = config('database.connections.mysql.database');
                $DB_USERNAME = config('database.connections.mysql.username');
                $DB_PASSWORD = config('database.connections.mysql.password');
                $DB_TABLE_PREFIX = config('database.connections.mysql.prefix');

                // download and install update files
                $local_zip_file = $root_path."update.zip";
                $local_db_files = $root_path."db_updates/";

                // clear cache files
                $cache_routes_file = $root_path."core/bootstrap/cache/routes-v7.php";
                $cache_config_file = $root_path."core/bootstrap/cache/config.php";

                if (file_exists($cache_routes_file)) {
                    try {
                        File::delete($cache_routes_file);
                    } catch (\Exception $e) {

                    }
                }
                if (file_exists($cache_config_file)) {
                    try {
                        File::delete($cache_config_file);
                    } catch (\Exception $e) {

                    }
                }

                // start updating
                if (@copy(@$response['file'], $local_zip_file)) {
                    $zip = new ZipArchive;
                    $res = $zip->open($local_zip_file);
                    if ($res === true) {
                        if (@$response['code'] != "") {
                            $res = $zip->setPassword(@$response['code']);
                        }
                        $zip->extractTo($root_path);
                        $zip->close();
                    }


                    $DB_updated = 0;
                    if (count(@$response['dbs']) > 0) {
                        try {
                            if (file_exists($local_db_files)) {
                                $con = @mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE, $DB_PORT);
                                if (mysqli_connect_errno()) {
                                    $DB_updated = 0;
                                } else {
                                    try {
                                        // update DB
                                        mysqli_set_charset($con, "utf8");
                                        foreach (@$response['dbs'] as $sql_file_name) {
                                            if (file_exists($local_db_files.$sql_file_name.".sql")) {
                                                $templine = '';
                                                $lines = file($local_db_files.$sql_file_name.".sql");
                                                foreach ($lines as $line) {
                                                    if (substr($line, 0, 2) == '--' || $line == '') {
                                                        continue;
                                                    }

                                                    $templine .= $line;
                                                    if (substr(trim($line), -1, 1) == ';') {
                                                        $templine = str_replace("smartend_", $DB_TABLE_PREFIX,
                                                            $templine);
                                                        mysqli_query($con, $templine);
                                                        $templine = '';
                                                    }
                                                }

                                            }
                                        }
                                        $DB_updated = 1;
                                    } catch (\Exception $e) {
                                        $DB_updated = 0;
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            $DB_updated = 0;
                        }
                    }

                    // remove temp files
                    if (file_exists($local_zip_file)) {
                        try {
                            File::delete($local_zip_file);
                        } catch (\Exception $e) {

                        }
                    }
                    if (file_exists($local_db_files)) {
                        try {
                            File::deleteDirectory($local_db_files);
                        } catch (\Exception $e) {

                        }
                    }

                    // clear old cache
                    try {
                        Cache::forget('_Loader_WebmasterSettings');
                        Cache::forget('_Loader_Web_Settings');
                        Cache::forget('_Loader_Languages');
                        Cache::forget('_Loader_Events');
                        Cache::forget('_Loader_Webmails');
                        Cache::forget('_Loader_BannersList');
                        Cache::forget('_Loader_MenuList');
                        Cache::forget('_Loader_WebmasterSections');
                    } catch (\Exception $e) {

                    }
                    return $DB_updated;
                }
            }
        } catch (\Exception $e) {

        }
        return 0;
    }
}
