<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactsGroup;
use App\Models\Country;
use App\Models\WebmasterSection;
use Auth;
use File;
use Helper;
use Illuminate\Config;
use Illuminate\Http\Request;
use Redirect;

class ContactsController extends Controller
{

    private $uploadPath = "contacts";

    // Define Default Variables

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($group_id = null)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->newsletter_status || !Helper::GeneralWebmasterSettings("newsletter_status")) {
            return redirect()->route("NoPermission");
        }

        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List of groups
        if (@Auth::user()->permissionsGroup->view_status) {
            $ContactsGroups = ContactsGroup::where('created_by', '=', Auth::user()->id)->orderby('id', 'asc')->get();
        } else {
            $ContactsGroups = ContactsGroup::orderby('id', 'asc')->get();
        }

        //List of Countries
        $Countries = Country::orderby('title_'.@Helper::currentLanguage()->code, 'asc')->get();

        if (@Auth::user()->permissionsGroup->view_status) {
            if ((int)$group_id > 0) {
                //List of group contacts
                $Contacts = Contact::where('created_by', '=', Auth::user()->id)->where('group_id', '=',
                    $group_id)->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            } elseif ($group_id == "wait") {
                //List waiting activation Contacts
                $Contacts = Contact::where('created_by', '=', Auth::user()->id)->where('status', '=',
                    '0')->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            } elseif ($group_id == "blocked") {
                //List waiting activation Contacts
                $Contacts = Contact::where('created_by', '=', Auth::user()->id)->where('status', '=',
                    '2')->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            } else {
                //List of all contacts
                $Contacts = Contact::where('created_by', '=', Auth::user()->id)->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            }
        } else {
            if ((int)$group_id > 0) {
                //List of group contacts
                $Contacts = Contact::where('group_id', '=', (int) $group_id)->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            } elseif ($group_id == "wait") {
                //List waiting activation Contacts
                $Contacts = Contact::where('status', '=', '0')->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            } elseif ($group_id == "blocked") {
                //List waiting activation Contacts
                $Contacts = Contact::where('status', '=', '2')->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            } else {
                //List of all contacts
                $Contacts = Contact::orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));
            }
        }

        if (@Auth::user()->permissionsGroup->view_status) {
            //Count of waiting activation Contacts
            $WaitContactsCount = Contact::where('created_by', '=', Auth::user()->id)->where('status', '=',
                '0')->count();

            //Count of Blocked Contacts
            $BlockedContactsCount = Contact::where('created_by', '=', Auth::user()->id)->where('status', '=',
                '2')->count();

            //Count of All Contacts
            $AllContactsCount = Contact::where('created_by', '=', Auth::user()->id)->count();
        } else {
            //Count of waiting activation Contacts
            $WaitContactsCount = Contact::where('status', '=', '0')->count();

            //Count of Blocked Contacts
            $BlockedContactsCount = Contact::where('status', '=', '2')->count();

            //Count of All Contacts
            $AllContactsCount = Contact::count();
        }


        $search_word = "";

        return view("dashboard.contacts.list",
            compact("Contacts", "GeneralWebmasterSections", "ContactsGroups", "Countries", "WaitContactsCount",
                "BlockedContactsCount", "AllContactsCount", "group_id", "search_word"));
    }

    public function search(Request $request)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //List of groups
        if (@Auth::user()->permissionsGroup->view_status) {
            $ContactsGroups = ContactsGroup::where('created_by', '=', Auth::user()->id)->orderby('id', 'asc')->get();
        } else {
            $ContactsGroups = ContactsGroup::orderby('id', 'asc')->get();
        }

        //List of Countries
        $Countries = Country::orderby('title_'.@Helper::currentLanguage()->code, 'asc')->get();

        if (@Auth::user()->permissionsGroup->view_status) {
            if ($request->q != "") {
                //find Contacts
                $Contacts = Contact::where('created_by', '=', Auth::user()->id)->where('first_name', 'like',
                    '%'.$request->q.'%')
                    ->orwhere('last_name', 'like', '%'.$request->q.'%')
                    ->orwhere('company', 'like', '%'.$request->q.'%')
                    ->orwhere('city', 'like', '%'.$request->q.'%')
                    ->orwhere('notes', 'like', '%'.$request->q.'%')
                    ->orwhere('phone', '=', $request->q)
                    ->orwhere('email', '=', $request->q)
                    ->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));
            } else {
                //List of all contacts
                $Contacts = Contact::where('created_by', '=', Auth::user()->id)->orderby('id',
                    'desc')->paginate(config('smartend.backend_pagination'));
            }
        } else {
            if ($request->q != "") {
                //find Contacts
                $Contacts = Contact::where('first_name', 'like', '%'.$request->q.'%')
                    ->orwhere('last_name', 'like', '%'.$request->q.'%')
                    ->orwhere('company', 'like', '%'.$request->q.'%')
                    ->orwhere('city', 'like', '%'.$request->q.'%')
                    ->orwhere('notes', 'like', '%'.$request->q.'%')
                    ->orwhere('phone', '=', $request->q)
                    ->orwhere('email', '=', $request->q)
                    ->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));
            } else {
                //List of all contacts
                $Contacts = Contact::orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));
            }
        }
        if (@Auth::user()->permissionsGroup->view_status) {
            //Count of waiting activation Contacts
            $WaitContactsCount = Contact::where('created_by', '=', Auth::user()->id)->where('status', '=',
                '0')->count();

            //Count of Blocked Contacts
            $BlockedContactsCount = Contact::where('created_by', '=', Auth::user()->id)->where('status', '=',
                '2')->count();

            //Count of All Contacts
            $AllContactsCount = Contact::where('created_by', '=', Auth::user()->id)->count();
        } else {
            //Count of waiting activation Contacts
            $WaitContactsCount = Contact::where('status', '=', '0')->count();

            //Count of Blocked Contacts
            $BlockedContactsCount = Contact::where('status', '=', '2')->count();

            //Count of All Contacts
            $AllContactsCount = Contact::count();
        }
        $group_id = "";
        $search_word = $request->q;

        return view("dashboard.contacts.list",
            compact("Contacts", "GeneralWebmasterSections", "ContactsGroups", "Countries", "WaitContactsCount",
                "BlockedContactsCount", "AllContactsCount", "group_id", "search_word"));
    }

    public function storeGroup(Request $request)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->add_status) {
            return Redirect::to(route('NoPermission'))->send();
        }
        //
        $ContactsGroup = new ContactsGroup;
        $ContactsGroup->name = strip_tags($request->name);
        $ContactsGroup->created_by = Auth::user()->id;
        $ContactsGroup->save();

        return redirect()->action([ContactsController::class, 'index']);
    }

    public function store(Request $request)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->add_status) {
            return Redirect::to(route('NoPermission'))->send();
        }

        //
        $this->validate($request, [
            'email' => 'email|required',
            'group_id' => 'required'
        ]);


        // Start of Upload Files
        $fileFinalName = null;
        $formFileName = "file";
        if ($request->hasFile($formFileName)) {
            // validate image
            $this->validate($request, [
                $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
            ]);
            $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 1);
            $fileFinalName = @$FileInfo['name'];
        }

        $Contact = new Contact;
        $Contact->group_id = ($request->group_id > 0) ? $request->group_id : null;
        $Contact->first_name = strip_tags($request->first_name);
        $Contact->last_name = strip_tags($request->last_name);
        $Contact->company = strip_tags($request->company);
        $Contact->email = strip_tags($request->email);
        $Contact->password = $request->password;
        $Contact->phone = $request->phone;
        $Contact->country_id = $request->country_id;
        $Contact->city = $request->city;
        $Contact->address = strip_tags($request->address);
        $Contact->photo = $fileFinalName;
        $Contact->notes = $request->notes;
        $Contact->status = 1;
        $Contact->created_by = Auth::user()->id;
        $Contact->save();

        return redirect()->action([ContactsController::class, 'index']);
    }

    public function edit($id)
    {
        //
        $ContactToEdit = Contact::find($id);
        if (!empty($ContactToEdit)) {
            return redirect()->action([ContactsController::class, 'index'],
                ['group_id' => $ContactToEdit->group_id])->with('ContactToEdit',
                $ContactToEdit);
        } else {
            return redirect()->action([ContactsController::class, 'index']);
        }
    }

    public function editGroup($id)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->edit_status) {
            return Redirect::to(route('NoPermission'))->send();
        }
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        if (@Auth::user()->permissionsGroup->view_status) {
            $EditContactsGroup = ContactsGroup::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $EditContactsGroup = ContactsGroup::find($id);
        }
        if (!empty($EditContactsGroup)) {
            return redirect()->action([ContactsController::class, 'index'])->with('EditContactsGroup',
                $EditContactsGroup);
        } else {
            return redirect()->action([ContactsController::class, 'index']);
        }
    }

    public function update(Request $request, $id)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->edit_status) {
            return Redirect::to(route('NoPermission'))->send();
        }
        //
        if (@Auth::user()->permissionsGroup->view_status) {
            $Contact = Contact::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $Contact = Contact::find($id);
        }
        if (!empty($Contact)) {


            $this->validate($request, [
                'email' => 'email|required'
            ]);

            // Start of Upload Files
            $fileFinalName = null;
            $formFileName = "file";
            if ($request->hasFile($formFileName)) {
                // validate image
                $this->validate($request, [
                    $formFileName => 'file|mimes:'.config('filesystems.allowed_image_types')
                ]);
                $FileInfo = FileHelper::uploadFile($request->file($formFileName), $this->uploadPath, 1);
                $fileFinalName = @$FileInfo['name'];
                if ($Contact->photo != "" && $Contact->photo != "profile.jpg") {
                    FileHelper::deleteFile($this->uploadPath."/".$Contact->photo);
                }
            }

            $Contact->group_id = $request->group_id;
            $Contact->first_name = strip_tags($request->first_name);
            $Contact->last_name = strip_tags($request->last_name);
            $Contact->company = strip_tags($request->company);
            $Contact->email = strip_tags($request->email);
            $Contact->password = $request->password;
            $Contact->phone = $request->phone;
            $Contact->country_id = $request->country_id;
            $Contact->city = $request->city;
            $Contact->address = strip_tags($request->address);
            $Contact->notes = $request->notes;

            if ($fileFinalName != "") {
                // Delete a Contact file
                if ($Contact->photo != "" && $Contact->photo != "profile.jpg") {
                    FileHelper::deleteFile($this->uploadPath."/".$Contact->photo);
                }

                $Contact->photo = $fileFinalName;
            }

            $Contact->status = $request->status;
            $Contact->updated_by = Auth::user()->id;
            $Contact->save();

            return redirect()->action([ContactsController::class, 'index'])->with('ContactToEdit',
                $Contact)->with('doneMessage2',
                __('backend.saveDone'));
        } else {
            return redirect()->action([ContactsController::class, 'index']);
        }
    }

    public function updateGroup(Request $request, $id)
    {
        //
        $ContactsGroup = ContactsGroup::find($id);
        if (!empty($ContactsGroup)) {
            $ContactsGroup->name = strip_tags($request->name);
            $ContactsGroup->updated_by = Auth::user()->id;
            $ContactsGroup->save();
        }
        return redirect()->action([ContactsController::class, 'index']);
    }

    public function destroy($id)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->delete_status) {
            return Redirect::to(route('NoPermission'))->send();
        }
        //
        if (@Auth::user()->permissionsGroup->view_status) {
            $Contact = Contact::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $Contact = Contact::find($id);
        }
        if (!empty($Contact)) {
            // Delete a Contact file
            if ($Contact->photo != "" && $Contact->photo != "profile.jpg") {
                FileHelper::deleteFile($this->uploadPath."/".$Contact->photo);
            }

            $Contact->delete();
        }
        return redirect()->action([ContactsController::class, 'index']);

    }

    public function destroyGroup($id)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->delete_status) {
            return Redirect::to(route('NoPermission'))->send();
        }
        //
        if (@Auth::user()->permissionsGroup->view_status) {
            $ContactsGroup = ContactsGroup::where('created_by', '=', Auth::user()->id)->find($id);
        } else {
            $ContactsGroup = ContactsGroup::find($id);
        }
        if (!empty($ContactsGroup)) {
            $ContactsGroup->delete();
            return redirect()->action([ContactsController::class, 'index']);
        } else {
            return redirect()->action([ContactsController::class, 'index']);
        }
    }

    public function updateAll(Request $request)
    {
        //
        if ($request->ids != "") {
            if ($request->action == "activate") {
                Contact::wherein('id', $request->ids)
                    ->update(['status' => 1]);

            } elseif ($request->action == "block") {
                Contact::wherein('id', $request->ids)
                    ->update(['status' => 0]);

            } elseif ($request->action == "delete") {
                // Check Permissions
                if (!@Auth::user()->permissionsGroup->delete_status) {
                    return Redirect::to(route('NoPermission'))->send();
                }
                // Delete Contacts file
                $Contacts = Contact::wherein('id', $request->ids)->get();
                foreach ($Contacts as $Contact) {
                    if ($Contact->photo != "" && $Contact->photo != "profile.jpg") {
                        FileHelper::deleteFile($this->uploadPath."/".$Contact->photo);
                    }
                }

                Contact::wherein('id', $request->ids)
                    ->delete();

            }
        }

        return redirect()->action([ContactsController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
    }


}
