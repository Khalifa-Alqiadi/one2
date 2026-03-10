@extends('dashboard.layouts.master')
@section('title', __('backend.newPermissions'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe03b;</i> {{ __('backend.newPermissions') }}</h3>
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
            <div class="box-body">
                <form method="POST" action="{{ route("permissionsStore") }}" class="dashboard-form">
                    @csrf

                    <div class="form-group row">
                        <label for="permission_name"
                               class="col-sm-2 form-control-label">{!!  __('backend.title') !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="name" id="permission_name" value="" required maxlength="100"
                                   placeholder="" class="form-control"/>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="view_status1"
                               class="col-sm-2 form-control-label">{!!  __('backend.dataManagements') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="md-check m-b-sm">
                                    <input type="radio" name="view_status" id="view_status1" value="1" checked class="has-value">
                                    <i class="primary"></i>
                                    {{ __('backend.dataManagements1') }}
                                </label>
                                <br>
                                <label class="md-check m-b-sm">
                                    <input type="radio" name="view_status" id="view_status2" value="0" class="has-value">
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
                                            ?>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label class="md-check">
                                                    <input type="checkbox" class="has-value" name="data_sections[]" value="{{ $WebSection->id }}" id="data_sections{{$i}}">
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
                                                <input type="checkbox" class="has-value" name="analytics_status" value="1" id="analytics_status">
                                                <i class="primary"></i>
                                                <label for="analytics_status">{{ __('backend.visitorsAnalytics') }}</label>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="newsletter_status" value="1" id="newsletter_status">
                                                <i class="primary"></i>
                                                <label for="newsletter_status">{{ __('backend.newsletter') }}</label>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="inbox_status" value="1" id="inbox_status">
                                                <i class="primary"></i>
                                                <label for="inbox_status">{{ __('backend.siteInbox') }}</label>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="calendar_status" value="1" id="calendar_status">
                                                <i class="primary"></i>
                                                <label for="calendar_status">{{ __('backend.calendar') }}</label>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="banners_status" value="1" id="banners_status">
                                                <i class="primary"></i>
                                                <label for="banners_status">{{ __('backend.adsBanners') }}</label>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="popups_status" value="1" id="popups_status">
                                                <i class="primary"></i>
                                                <label for="popups_status">{{ __('backend.popups') }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="tags_status" value="1" id="tags_status">
                                                <i class="primary"></i>
                                                <label for="tags_status">{{ __('backend.tags') }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="menus_status" value="1" id="menus_status">
                                                <i class="primary"></i>
                                                <label for="menus_status">{{ __('backend.siteMenus') }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="file_manager_status" value="1" id="file_manager_status">
                                                <i class="primary"></i>
                                                <label for="file_manager_status">{{ __('backend.fileManager') }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="roles_status" value="1" id="roles_status">
                                                <i class="primary"></i>
                                                <label for="roles_status">{{ __('backend.usersPermissions') }}</label>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="settings_status" value="1" id="settings_status">
                                                <i class="primary"></i>
                                                <label for="settings_status">{{ __('backend.generalSiteSettings') }}</label>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="webmaster_status" value="1" id="webmaster_status">
                                                <i class="primary"></i>
                                                <label for="webmaster_status">{{ __('backend.generalSettings') }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label class="md-check">
                                                <input type="checkbox" class="has-value" name="modules_status" value="1" id="modules_status">
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
                                    <input type="radio" name="active_status" id="active_status1" value="1" class="has-value" checked>
                                    <i class="primary"></i>
                                    {{ __('backend.active') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check m-b-sm">
                                    <input type="radio" name="active_status" id="active_status2" value="0" class="has-value">
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
                                    <input type="radio" name="add_status" id="add_status1" value="1" class="has-value" checked>
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check m-b-sm">
                                    <input type="radio" name="add_status" id="add_status2" value="0" class="has-value">
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
                                    <input type="radio" name="edit_status" id="edit_status1" value="1" class="has-value" checked>
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check m-b-sm">
                                    <input type="radio" name="edit_status" id="edit_status2" value="0" class="has-value">
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
                                    <input type="radio" name="delete_status" id="delete_status1" value="1" class="has-value" checked>
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check m-b-sm">
                                    <input type="radio" name="delete_status" id="delete_status2" value="0" class="has-value">
                                    <i class="danger"></i>
                                    {{ __('backend.no') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.add') !!}</button>
                            <a href="{{route("users")}}"
                               class="btn btn-lg btn-default m-t"><i class="material-icons">
                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
