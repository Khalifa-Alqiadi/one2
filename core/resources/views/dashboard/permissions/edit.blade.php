@extends('dashboard.layouts.master')
@section('title', __('backend.editPermissions'))
@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.editPermissions') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('backend.settings') }}</a> /
                    <a href="">{{ __('backend.usersPermissions') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("users")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <?php
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
        if (Session::has('tab')) {
            if (Session::get('tab') == "home") {
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
            }
        }
        ?>
        <div class="box nav-active-border b-info">
            <ul class="nav nav-md">
                <li class="nav-item inline">
                    <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details">
                        <span class="text-md"><i class="material-icons">
                                &#xe31e;</i> {{ __('backend.editPermissions') }}</span>
                    </a>
                </li>
                <li class="nav-item inline">
                    <a class="nav-link  {{ $tab_2 }}" data-toggle="tab" data-target="#tab_custom">
                    <span class="text-md"><i class="material-icons">
                            &#xe31f;</i> {{ __('backend.customHome') }}</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content clear b-t">
                <div class="tab-pane  {{ $tab_1 }}" id="tab_details">
                    <div class="box-body">
                        <form method="POST" action="{{ route('permissionsUpdate',$Permissions->id) }}" class="dashboard-form">
                            @csrf
                            <div class="form-group row">
                                <label for="permission_name"
                                       class="col-sm-2 form-control-label">{!!  __('backend.title') !!}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" autocomplete="off" name="name" id="permission_name" value="{{ $Permissions->name }}" required maxlength="100"
                                           placeholder="" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="view_status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.dataManagements') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="view_status" id="view_status1" value="1" {{ ($Permissions->view_status==1)?"checked":"" }} class="has-value">
                                            <i class="primary"></i>
                                            {{ __('backend.dataManagements1') }}
                                        </label>
                                        <br>
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="view_status" id="view_status2" value="0" {{ ($Permissions->view_status==0)?"checked":"" }} class="has-value">
                                            <i class="blue"></i>
                                            {{ __('backend.dataManagements2') }}
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="data_sections0"
                                       class="col-sm-2 form-control-label">{!!  __('backend.activeSiteSections') !!}
                                </label>
                                <div class="col-sm-10">
                                    <div class="b-a p-x p-t">
                                        <div class="row">
                                            <?php
                                            $i = 0;
                                            $title_var = "title_".@Helper::currentLanguage()->code;
                                            $title_var2 = "title_".config('smartend.default_language');
                                            ?>
                                            @foreach($GeneralWebmasterSections as $WebSection)
                                                    <?php
                                                    if ($WebSection->$title_var != "") {
                                                        $WSectionTitle = $WebSection->$title_var;
                                                    } else {
                                                        $WSectionTitle = $WebSection->$title_var2;
                                                    }

                                                    $data_sections_arr = explode(",", $Permissions->data_sections);
                                                    ?>
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label class="md-check">
                                                            <input type="checkbox" class="has-value" name="data_sections[]" value="{{ $WebSection->id }}" id="data_sections{{$i}}" {{ (in_array($WebSection->id,$data_sections_arr))?"checked":"" }}>
                                                            <i class="primary"></i>
                                                            <label for="data_sections{{$i}}">{!! $WSectionTitle !!}</label>
                                                        </label>
                                                    </div>
                                                </div>
                                                    <?php $i++; ?>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="analytics_status"
                                       class="col-sm-2 form-control-label">{!!  __('backend.activeApps') !!}
                                </label>
                                <div class="col-sm-10">
                                    <div class="b-a p-x p-t">
                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="analytics_status" value="1" id="analytics_status" {{ ($Permissions->analytics_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="analytics_status">{{ __('backend.visitorsAnalytics') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="newsletter_status" value="1" id="newsletter_status" {{ ($Permissions->newsletter_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="newsletter_status">{{ __('backend.newsletter') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="inbox_status" value="1" id="inbox_status" {{ ($Permissions->inbox_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="inbox_status">{{ __('backend.siteInbox') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="calendar_status" value="1" id="calendar_status" {{ ($Permissions->calendar_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="calendar_status">{{ __('backend.calendar') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="banners_status" value="1" id="banners_status" {{ ($Permissions->banners_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="banners_status">{{ __('backend.adsBanners') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="popups_status" value="1" id="popups_status" {{ ($Permissions->popups_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="popups_status">{{ __('backend.popups') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="tags_status" value="1" id="tags_status" {{ ($Permissions->tags_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="tags_status">{{ __('backend.tags') }}</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="menus_status" value="1" id="menus_status" {{ ($Permissions->menus_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="menus_status">{{ __('backend.siteMenus') }}</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="file_manager_status" value="1" id="file_manager_status" {{ ($Permissions->file_manager_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="file_manager_status">{{ __('backend.fileManager') }}</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="roles_status" value="1" id="roles_status" {{ ($Permissions->roles_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="roles_status">{{ __('backend.usersPermissions') }}</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="settings_status" value="1" id="settings_status" {{ ($Permissions->settings_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="settings_status">{{ __('backend.generalSiteSettings') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="webmaster_status" value="1" id="webmaster_status" {{ ($Permissions->webmaster_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="webmaster_status">{{ __('backend.generalSettings') }}</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label class="md-check">
                                                        <input type="checkbox" class="has-value" name="modules_status" value="1" id="modules_status" {{ ($Permissions->modules_status==1)?"checked":"" }}>
                                                        <i class="primary"></i>
                                                        <label for="modules_status">{{ __('backend.siteSectionsSettings') }}</label>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="active_status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.topicsStatus') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="active_status" id="active_status1" value="1" class="has-value" {{ ($Permissions->active_status==1)?"checked":"" }}>
                                            <i class="primary"></i>
                                            {{ __('backend.active') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="active_status" id="active_status2" value="0" class="has-value" {{ ($Permissions->active_status==0)?"checked":"" }}>
                                            <i class="danger"></i>
                                            {{ __('backend.notActive') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="add_status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.addPermission') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="add_status" id="add_status1" value="1" class="has-value" {{ ($Permissions->add_status==1)?"checked":"" }}>
                                            <i class="primary"></i>
                                            {{ __('backend.yes') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="add_status" id="add_status2" value="0" class="has-value" {{ ($Permissions->add_status==0)?"checked":"" }}>
                                            <i class="danger"></i>
                                            {{ __('backend.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.editPermission') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="edit_status" id="edit_status1" value="1" class="has-value" {{ ($Permissions->edit_status==1)?"checked":"" }}>
                                            <i class="primary"></i>
                                            {{ __('backend.yes') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="edit_status" id="edit_status2" value="0" class="has-value" {{ ($Permissions->edit_status==0)?"checked":"" }}>
                                            <i class="danger"></i>
                                            {{ __('backend.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="delete_status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.deletePermission') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="delete_status" id="delete_status1" value="1" class="has-value" {{ ($Permissions->delete_status==1)?"checked":"" }}>
                                            <i class="primary"></i>
                                            {{ __('backend.yes') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="delete_status" id="delete_status2" value="0" class="has-value" {{ ($Permissions->delete_status==0)?"checked":"" }}>
                                            <i class="danger"></i>
                                            {{ __('backend.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="status" id="status1" value="1" class="has-value" {{ ($Permissions->status==1)?"checked":"" }}>
                                            <i class="primary"></i>
                                            {{ __('backend.yes') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check m-b-sm">
                                            <input type="radio" name="status" id="status2" value="0" class="has-value" {{ ($Permissions->status==0)?"checked":"" }}>
                                            <i class="danger"></i>
                                            {{ __('backend.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-t-md">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-lg btn-primary  m-t"><i class="material-icons">
                                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                                    <a href="{{route("users")}}"
                                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane  {{ $tab_2 }}" id="tab_custom">
                    <div class="box-body">
                        @include('dashboard.permissions.home.custom')
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push("after-scripts")
    <script type="text/javascript">
        $(document).ready(function () {
            $("#home_status2").click(function () {
                $("#home_details_div").css("display", "none");
            });
            $("#home_status1").click(function () {
                $("#home_details_div").css("display", "block");
            });

            $('#btn_update_form').submit(function (evt) {
                evt.preventDefault();
                $('#link_update_submit').html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.add') !!}");
                $('#link_update_submit').prop('disabled', true);
                var formData = new FormData(this);
                var xhr = $.ajax({
                    type: "POST",
                    url: "<?php echo route("customLinksUpdate"); ?>",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        $('#btn_edit_errors').find("ul").html('');
                        if (result.stat == 'success') {
                            $('#btn_edit_errors').hide();
                            document.getElementById("btn_update_form").reset();
                            $('#link_edit').modal('hide');
                            list_btns();
                        } else {
                            $.each(result.error, function (key, value) {
                                $('#btn_edit_errors').find("ul").append('<li>' + value + '</li>');
                            });
                        }
                        $('#link_update_submit').html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.save') !!}");
                        $('#link_update_submit').prop('disabled', false);
                    }
                });
                console.log(xhr);
                return false;
            });
            $('#btn_add_form').submit(function (evt) {
                evt.preventDefault();
                $('#btn_add_form_submit').html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.add') !!}");
                $('#btn_add_form_submit').prop('disabled', true);
                var formData = new FormData(this);
                var xhr = $.ajax({
                    type: "POST",
                    url: "<?php echo route("customLinksStore"); ?>",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        $('#btn_add_errors').find("ul").html('');
                        if (result.stat == 'success') {
                            $('#btn_add_errors').hide();
                            document.getElementById("btn_add_form").reset();
                            $('#link_add').modal('hide');
                            list_btns();
                        } else {
                            $('#btn_add_errors').css('display', 'block');
                            $.each(result.error, function (key, value) {
                                $('#btn_add_errors').find("ul").append('<li>' + value + '</li>');
                            });
                        }
                        $('#btn_add_form_submit').html("{!! __('backend.add') !!}");
                        $('#btn_add_form_submit').prop('disabled', false);
                    }
                });
                console.log(xhr);
                return false;
            });
            list_btns();

            $('#btns_delete_btn').click(function () {
                $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
                var row_id = $(this).attr('row-id');
                if (row_id != "") {
                    $.ajax({
                        type: "GET",
                        url: "<?php echo route("customLinksDestroy"); ?>/" + row_id + "/{{ $Permissions->id }}",
                        success: function (result) {
                            if (result.stat == 'success') {
                                $('#btns_delete_btn').html("{!! __('backend.yes') !!}");
                                $('#btns-delete').modal('hide');
                                $('.modal-backdrop').hide();
                                list_btns();
                            }
                        }
                    });
                }
            });
        });

        function list_btns() {
            $('#buttons_list').html("<div class=\"text-center\"><img class=\"m-b-1\" src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;\"/></div>");
            $.get("{{ route("customLinksList") }}/{{ $Permissions->id }}", function (data) {
                $('#buttons_list').html(data);
            });
        }

        function setToDelLink(rid) {
            $("#btns_delete_btn").attr("row-id", rid);
            $('#btns-delete').modal('show');
        }

        function setToEditLink(rid) {
            $('#link_edit').modal('show');
            $('#buttons_edit_details').html("<div class=\"text-center\"><img class=\"m-b-1\" src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;\"/></div>");
            $.get("{{ route("customLinksEdit") }}/" + rid + "/{{ $Permissions->id }}", function (data) {
                $('#buttons_edit_details').html(data);
            });
        }
    </script>
    @include('dashboard.layouts.editor')
@endpush
