<!-- column -->
<div class="col-sm-6 col-md-7">
    <div class="row-col">
        <div class="p-a-sm">
            <div>
                <h6 class="m-b-0 m-t-sm"><i class="material-icons">
                        &#xe3c9;</i> {{ __('backend.editContacts') }}</h6>

            </div>
        </div>
        <div class="row-row">
            <div class="row-body">
                <div class="row-inner">
                    <div class="padding p-y-sm">
                        @if(Session::has('doneMessage2'))
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        {{ Session::get('doneMessage2') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ route("contactsUpdate",Session::get('ContactToEdit')->id) }}" class="dashboard-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row-col h-auto m-b-1">
                                <div class="col-sm-3">
                                    <div class="avatar w-64 inline">
                                        @if(Session::get('ContactToEdit')->photo !="")
                                            <img id="photo_preview"
                                                 src="{{ route("fileView",["path" =>'contacts/'.Session::get('ContactToEdit')->photo]) }}">
                                        @else
                                            <img id="photo_preview"
                                                 src="{{ route("fileView",["path" =>'contacts/profile.jpg']) }}"
                                                 style="opacity: 0.2">
                                        @endif
                                    </div>
                                    <div class="form-file inline">
                                        <input id="photo_file" type="file" name="file" accept="image/*">
                                        <button class="btn white btn-sm">
                                            <small>
                                                <small>{{ __('backend.selectFile') }} ..</small>
                                            </small>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-9 v-m h2 _300">
                                    <div class="p-l-xs">
                                        <input type="text" name="first_name" id="first_name" value="{{ Session::get('ContactToEdit')->first_name }}" class="form-control w-sm inline" required maxlength="191" autocomplete="off" placeholder="{{ __('backend.firstName') }}">
                                        <input type="text" name="last_name" id="last_name" value="{{ Session::get('ContactToEdit')->last_name }}" class="form-control w-sm inline" required maxlength="191" autocomplete="off" placeholder="{{ __('backend.lastName') }}">
                                        @if(count($ContactsGroups) >0)
                                            <select name="group_id"
                                                    class="form-control c-select w-sm inline" required
                                                    style="vertical-align: bottom;">
                                                <option value="">- - {!!  __('backend.group') !!} - -
                                                </option>

                                                @foreach ($ContactsGroups as $Group)
                                                    <option
                                                        value="{{ $Group->id  }}" {{ ($Group->id == Session::get('ContactToEdit')->group_id) ? "selected='selected'":""  }}>{{ $Group->name }}</option>
                                                @endforeach

                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- fields -->
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="phone">{{ __('backend.contactPhone') }}</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="phone" id="phone" value="{{ Session::get('ContactToEdit')->phone }}" class="form-control" maxlength="191" autocomplete="off">
                                    </div>
                                    @if(Session::get('ContactToEdit')->phone !="")
                                        <div class="col-sm-3">
                                            <a href="tel:{{Session::get('ContactToEdit')->phone}}"
                                               class="btn white pull-right" style="width: 100%">
                                                <small>
                                                    <i class="material-icons">
                                                        &#xe0b1;</i> {{ __('backend.callNow') }}
                                                </small>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="email">{{ __('backend.contactEmail') }}</label>
                                    <div class="col-sm-6">
                                        <input type="email" name="email" id="email" value="{{ Session::get('ContactToEdit')->email }}" class="form-control" required maxlength="191" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="{{ route("webmails",["group_id"=>"create","stat"=>"email","wid"=>'new',"contact_email"=>Session::get('ContactToEdit')->email]) }}"
                                           style="width: 100%" class="btn white pull-right">
                                            <small>
                                                <i class="material-icons">
                                                    &#xe151;</i> {{ __('backend.sendEmail') }}
                                            </small>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="company">{{ __('backend.companyName') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="company" id="company" value="{{ Session::get('ContactToEdit')->company }}" class="form-control" maxlength="191" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.country') !!}</label>
                                    <div class="col-sm-6">
                                        <select name="country_id" id="country_id"
                                                class="form-control select2 select2-hidden-accessible" ui-jp="select2"
                                                ui-options="{theme: 'bootstrap'}">
                                            <option value="">- - {!!  __('backend.country') !!} - -
                                            </option>
                                            <?php
                                            $title_var = "title_".@Helper::currentLanguage()->code;
                                            $title_var2 = "title_".config('smartend.default_language');
                                            ?>
                                            @foreach ($Countries as $country)
                                                    <?php
                                                    if ($country->$title_var != "") {
                                                        $title = $country->$title_var;
                                                    } else {
                                                        $title = $country->$title_var2;
                                                    }
                                                    ?>
                                                <option
                                                    value="{{ $country->id  }}" {{ ($country->id == Session::get('ContactToEdit')->country_id) ? "selected='selected'":""  }}>{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name="city" id="city" value="{{ Session::get('ContactToEdit')->city }}" class="form-control" maxlength="191" autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="notes">{{ __('backend.notes') }}</label>
                                    <div class="col-sm-9">
                                        <textarea name="notes" id="notes" cols="30" rows="2" class="form-control">{{ Session::get('ContactToEdit')->notes }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="status1">{{ __('backend.status') }}</label>
                                    <div class="col-sm-9">
                                        <div class="radio">
                                            <label class="md-check">
                                                <input type="radio" name="status" value="1" class="has-value" {{ (Session::get('ContactToEdit')->status==1)?"checked":"" }} id="status1">
                                                <i class="primary"></i>
                                                {{ __('backend.active') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="status" value="0" class="has-value" {{ (Session::get('ContactToEdit')->status==0)?"checked":"" }} id="status2">
                                                <i class="danger"></i>
                                                {{ __('backend.waitActivation') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="status" value="2" class="has-value" {{ (Session::get('ContactToEdit')->status==2)?"checked":"" }} id="status3">
                                                <i class="danger"></i>
                                                {{ __('backend.notActive') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <button class="btn warning pull-right" data-toggle="modal"
                                                    data-target="#mc-{{ Session::get('ContactToEdit')->id }}"
                                                    ui-toggle-class="bounce"
                                                    ui-target="#animate">
                                                <small><i class="material-icons">
                                                        &#xe872;</i> {{ __('backend.deleteContacts') }}
                                                </small>
                                            </button>
                                        @endif
                                        <!-- .modal -->
                                        <div id="mc-{{ Session::get('ContactToEdit')->id }}"
                                             class="modal fade"
                                             data-backdrop="true">
                                            <div class="modal-dialog" id="animate">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                    </div>
                                                    <div class="modal-body text-center p-lg">
                                                        <h5 class="m-b-0">
                                                            {{ __('backend.confirmationDeleteMsg') }}
                                                            <br>
                                                            <strong>[ {{ Session::get('ContactToEdit')->first_name }}  {{ Session::get('ContactToEdit')->last_name }}
                                                                ]</strong>
                                                        </h5>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn dark-white p-x-md"
                                                                data-dismiss="modal">{{ __('backend.no') }}</button>
                                                        <a href="{{ route("contactsDestroy",["id"=>Session::get('ContactToEdit')->id]) }}"
                                                           class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div>
                                        </div>
                                        <!-- / .modal -->

                                        <button type="submit" class="btn btn-primary"><i
                                                class="material-icons">
                                                &#xe31b;</i> {!! __('backend.save') !!}</button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /column -->
