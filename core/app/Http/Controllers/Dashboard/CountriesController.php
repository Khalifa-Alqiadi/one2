<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\WebmasterSection;
use App\Services\ApiClientService;
use Illuminate\Support\Facades\Auth;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountriesController extends Controller
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->middleware('auth');
    }

    public function index()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();


        $countries = Country::paginate(config('smartend.backend_pagination'));

        return view('dashboard.countries.list', compact('GeneralWebmasterSections', 'countries'));
    }

    public function list(Request $request)
    {
        $limit = $request->input('length');
        $start = $request->input('start');
        $dir = $request->input('order.0.dir');

        $find_q = $request->input('find_q');

        if (@Auth::user()->permissionsGroup->view_status) {
            $Countries = Country::where('created_by', '=', Auth::user()->id);
        } else {
            $Countries = Country::where('id', '>', 0);
        }
        if ($find_q != "") {
            $lang = Helper::currentLanguage()->code;
            $Countries = $Countries->where(function ($query) use ($find_q, $lang) {
                $query->where('title_' . $lang, 'like', '%' . $find_q . '%')
                    ->orWhere('code', 'like', '%' . $find_q . '%');
            });
        }

        $columns = [];
        if (@Auth::user()->permissionsGroup->edit_status) {
            $columns[] = "id";
        }
        $columns[] = "title";
        $columns[] = "code";
        $columns[] = "tel";
        $columns[] = "created_at";
        $order = @$columns[$request->input('order.0.column')];

        $totalData = $Countries->count();
        $totalFiltered = $totalData;
        if ($limit > 0) {
            $Countries = $Countries->offset($start)->limit($limit);
        }
        if ($order) {
            $Countries = $Countries->orderBy($order, $dir)->orderBy('id', 'desc')->get();
        } else {
            $Countries = $Countries->orderBy('id', 'desc')->get();
        }

        $data = array();
        if ($totalFiltered > 0) {
            foreach ($Countries as $Country) {
                $nestedData = [];
                $nestedData['check'] = "<div class='row_checker'><label class=\"ui-check m-a-0\">"
                    . "<input type=\"checkbox\" name=\"ids[]\" value=\"" . $Country->id . "\"><i class=\"dark-white\"></i>"
                    . "<input type='hidden' name='row_ids[]' value='" . $Country->id . "' class='form-control row_no'>"
                    . "</label></div>";

                $lang = Helper::currentLanguage()->code;
                $title = $Country->{'title_' . $lang} ?? $Country->title_en ?? '';
                if (@Auth::user()->permissionsGroup->edit_status) {
                    $nestedData['title'] = '<a href="#" onclick="UpdateCountry(\'' . $Country->id . '\');return false;">' . "<div class='h6 m-b-0'>" . $title . "</div>" . '</a>';
                } else {
                    $nestedData['title'] = "<div class='h6 m-b-0'>" . $title . "</div>";
                }

                $nestedData['code'] = "<div class='text-center'>" . htmlspecialchars($Country->code) . "</div>";
                $nestedData['tel'] = "<div class='text-center'>" . htmlspecialchars($Country->tel) . "</div>";
                $nestedData['created_at'] = "<div class='text-center'>" . Helper::formatDate($Country->created_at) . " " . date('h:i A', strtotime($Country->created_at)) . "</div>";

                $options = '<div class="dropdown">
        <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i> ' . __('backend.options') . '</button>
        <div class="dropdown-menu pull-right">';
                if (@Auth::user()->permissionsGroup->edit_status) {
                    $options .= '<a class="dropdown-item" onclick="UpdateCountry(\'' . $Country->id . '\')"><i class="material-icons">&#xe3c9;</i> ' . __('backend.edit') . '</a>';
                }
                if (@Auth::user()->permissionsGroup->delete_status) {
                    $options .= '<a class="dropdown-item text-danger" onclick="DeleteCountry(\'' . $Country->id . '\')"><i class="material-icons">&#xe872;</i> ' . __('backend.delete') . '</a>';
                }
                $options .= '</div></div>';

                $nestedData['options'] = "<div class='text-center'>" . $options . "</div>";

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }

    public function edit($id = 0)
    {
        if ($id > 0) {
            if (@Auth::user()->permissionsGroup->edit_status) {
                if (@Auth::user()->permissionsGroup->view_status) {
                    $Country = Country::where('created_by', '=', Auth::user()->id)->find($id);
                } else {
                    $Country = Country::find($id);
                }
                if (!empty($Country)) {
                    return view('dashboard.countries.edit', compact('Country'));
                }
            }
        }
        return "<div class='p-a-2 text-danger'>" . __("backend.error") . "</div>";
    }

    public function update(Request $request)
    {
        $fields_to_validate = [
            'country_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $fields_to_validate);
        if ($validator->passes()) {
            if (@Auth::user()->permissionsGroup->view_status) {
                $Country = Country::where('created_by', '=', Auth::user()->id)->find($request->country_id);
            } else {
                $Country = Country::find($request->country_id);
            }
            if (!empty($Country)) {
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $code = $ActiveLanguage->code;
                        $Country->{'title_' . $code} = strip_tags($request->{'title_' . $code});
                    }
                }
                $Country->code = strip_tags($request->code);
                $Country->tel = strip_tags($request->tel);
                $Country->updated_by = Auth::user()->id;
                $Country->save();

                return response()->json(array('stat' => 'success', 'msg' => __('backend.saveDone')));
            }
        }
        return response()->json(array('stat' => 'error', 'msg' => __('backend.error')));
    }

    public function destroy($id = 0)
    {
        if ($id > 0) {
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return response()->json(array('stat' => 'error', 'msg' => __('backend.error')));
            }
            if (@Auth::user()->permissionsGroup->view_status) {
                $Country = Country::where('created_by', '=', Auth::user()->id)->find($id);
            } else {
                $Country = Country::find($id);
            }
            if (!empty($Country)) {
                $Country->delete();
                return response()->json(array('stat' => 'success', 'msg' => __('backend.deleteDone')));
            }
        }
        return response()->json(array('stat' => 'error', 'msg' => __('backend.error')));
    }

    public function updateAll(Request $request)
    {
        if ($request->action == 'activate') {
            if ($request->ids != "") {
                Country::wherein('id', $request->ids)->update(['status' => 1]);
                return response()->json(array('stat' => 'success', 'msg' => __('backend.saveDone')));
            }
        } elseif ($request->action == 'block') {
            if ($request->ids != "") {
                Country::wherein('id', $request->ids)->update(['status' => 0]);
                return response()->json(array('stat' => 'success', 'msg' => __('backend.saveDone')));
            }
        } elseif ($request->action == 'delete') {
            if ($request->ids != "") {
                if (!@Auth::user()->permissionsGroup->delete_status) {
                    return response()->json(array('stat' => 'error', 'msg' => __('backend.error')));
                }
                Country::wherein('id', $request->ids)->delete();
                return response()->json(array('stat' => 'success', 'msg' => __('backend.deleteDone')));
            }
        }

        return response()->json(array('stat' => 'error', 'msg' => __('backend.error')));
    }


    public function update_api()
    {
        $locale = Helper::currentLanguage()->code;
        $token  = config('services.SPORTMONKS_TOKEN');

        $page  = 1;
        $saved = 0;

        do {
            $url = "https://api.sportmonks.com/v3/core/countries"
                . "?api_token={$token}"
                . "&locale={$locale}"
                . "&page={$page}";

            $countriesRes = $this->apiClient->curlGet($url);
            $data = data_get($countriesRes, 'json', []);

            $countries = $data['data'] ?? [];

            foreach ($countries as $country) {
                Country::updateOrCreate(
                    [
                        'sport_id' => $country['id'],
                        'title_ar' => $country['name'] ?? null,
                    ], // شرط التطابق (ثابت)
                    [
                        'code'     => $country['iso2'] ?? null,
                    ]
                );

                $saved++;
            }

            $hasMore = (bool) data_get($data, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        return redirect()
            ->action([CountriesController::class, 'index'])
            ->with('doneMessage', __('backend.saveDone') . " | Saved: {$saved}");
    }
}
