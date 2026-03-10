@extends('dashboard.layouts.master')
@section('title', __('backend.siteSectionsSettings'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('backend.sectionNew') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    {{ __('backend.webmasterTools') }} /
                    <a href="">{{ __('backend.siteSectionsSettings') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("WebmasterSections")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body p-a-2">
                <form method="POST" action="{{ route("WebmasterSectionsStore") }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf

                    @foreach(Helper::languagesList() as $ActiveLanguage)
                        <div class="form-group row">
                            <label
                                class="col-sm-2 form-control-label">{!!  __('backend.sectionName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                            </label>
                            <div class="col-sm-10">
                                <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group row">
                        <label for="site_status1"
                               class="col-sm-2 form-control-label">{!!  __('backend.sectionType') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio secs">
                                <div>
                                    <label
                                        class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a sec-active">
                                        <input type="radio" name="type" value="0" class="has-value" checked id="site_status1">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded blue-grey pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe873;</span></div>
                                        <strong>{{ __('backend.typeTextPages') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.generalDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="10" class="has-value" id="site_status11">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded indigo pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe051;</span></div>
                                        <strong>{{ __('backend.landingPages') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.landingPagesDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="1" class="has-value" id="site_status2">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded green pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe251;</span></div>
                                        <strong>{{ __('backend.typePhotos') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.photoDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="2" class="has-value" id="site_status3">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded red pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe63a;</span></div>
                                        <strong>{{ __('backend.typeVideos') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.videoDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="3" class="has-value" id="site_status4">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded warn pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe050;</span></div>
                                        <strong>{{ __('backend.typeSounds') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.audioDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="5" class="has-value" id="site_status6">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded accent pull-left m-r">
                                            <span class="fa fa-table sec-icon"></span></div>
                                        <strong>{{ __('backend.tableView') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.tableDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="8" class="has-value" id="site_status9">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded teal pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe896;</span></div>
                                        <strong>{{ __('backend.accordionSection') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.accordionSectionDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a ">
                                        <input type="radio" name="type" value="9" class="has-value" id="site_status10">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded orange pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe2c8;</span></div>
                                        <strong>{{ __('backend.documentationSection') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.documentationSectionDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="4" class="has-value" id="site_status5">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded pink pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe899;</span></div>
                                        <strong>{{ __('backend.private') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.privateDesc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="7" class="has-value" id="site_status8">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded deep-purple pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe880;</span></div>
                                        <strong>{{ __('backend.private2') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.private2Desc') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a">
                                        <input type="radio" name="type" value="6" class="has-value" id="site_status7">
                                        <i class="dark-white"></i>
                                        <div class="p-a-sm rounded blue pull-left m-r">
                                            <span class="material-icons sec-icon">&#xe31f;</span></div>
                                        <strong>{{ __('backend.publicForm') }}</strong>
                                        <div class="m-x-sm text-muted">{{ __('backend.publicFormDesc') }}</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sections_status1"
                               class="col-sm-2 form-control-label">{!!  __('backend.hasCategories') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio b-a p-a">
                                <div>
                                    <label class="md-check">
                                        <input type="radio" name="sections_status" value="0" class="has-value" checked id="sections_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.withoutCategories') }}
                                    </label>
                                </div>
                                <div>
                                    <label class="md-check">
                                        <input type="radio" name="sections_status" value="1" class="has-value" id="sections_status2">
                                        <i class="primary"></i>
                                        {{ __('backend.mainCategoriesOnly') }}
                                    </label>
                                </div>
                                <div>
                                    <label class="md-check">
                                        <input type="radio" name="sections_status" value="2" class="has-value" id="sections_status3">
                                        <i class="primary"></i>
                                        {{ __('backend.mainAndSubCategories') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="fields_settings">
                        <div class="form-group">
                            <br/>
                            <label><h5><i class="material-icons">&#xe1db;</i> {{ __('backend.optionalFields') }}
                                </h5></label>
                            <hr class="m-a-0">
                        </div>
                        <div class="form-group row">
                            <label for="title_status1" class="col-sm-2 form-control-label">{!!  __('backend.titleField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="title_status" value="1" class="has-value" id="title_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="title_status" value="0" class="has-value" checked id="title_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date_status1" class="col-sm-2 form-control-label">{!!  __('backend.dateField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="date_status" value="1" class="has-value" id="date_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="date_status" value="0" class="has-value" checked id="date_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expire_date_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.expireDateField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="expire_date_status" value="1" class="has-value" id="expire_date_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="expire_date_status" value="0" class="has-value" checked id="expire_date_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="longtext_status1" class="col-sm-2 form-control-label">{!!  __('backend.longTextField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="longtext_status" value="1" class="has-value" id="longtext_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="longtext_status" value="0" class="has-value" checked id="longtext_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editor_status1" class="col-sm-2 form-control-label">{!!  __('backend.allowEditor') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="editor_status" value="1" class="has-value" id="editor_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="editor_status" value="0" class="has-value" checked id="editor_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tags_status1" class="col-sm-2 form-control-label">{!!  __('backend.tagsField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="tags_status" value="1" class="has-value" id="tags_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="tags_status" value="0" class="has-value" checked id="tags_status0">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="case_status1" class="col-sm-2 form-control-label">{!!  __('backend.caseField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="case_status" value="1" class="has-value" id="case_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="case_status" value="0" class="has-value" checked id="case_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="featured_status1" class="col-sm-2 form-control-label">{!!  __('backend.featuredField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="featured_status" value="1" class="has-value" id="featured_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="featured_status" value="0" class="has-value" checked id="featured_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="visits_status1" class="col-sm-2 form-control-label">{!!  __('backend.visitsField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="visits_status" value="1" class="has-value" id="visits_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="visits_status" value="0" class="has-value" checked id="visits_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photo_status1" class="col-sm-2 form-control-label">{!!  __('backend.photoField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="photo_status" value="1" class="has-value" id="photo_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="photo_status" value="0" class="has-value" checked id="photo_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="attach_file_status1" class="col-sm-2 form-control-label">{!!  __('backend.attachFileField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="attach_file_status" value="1" class="has-value" id="attach_file_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="attach_file_status" value="0" class="has-value" checked id="attach_file_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="section_icon_status1" class="col-sm-2 form-control-label">{!!  __('backend.sectionIconPicker') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="section_icon_status" value="1" class="has-value" id="section_icon_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="section_icon_status" value="0" class="has-value" checked id="section_icon_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="icon_status1" class="col-sm-2 form-control-label">{!!  __('backend.topicsIconPicker') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="icon_status" value="1" class="has-value" id="icon_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="icon_status" value="0" class="has-value" checked id="icon_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_status1" class="col-sm-2 form-control-label">{!!  __('backend.showIdColumn') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="no_status" value="1" class="has-value" id="no_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="no_status" value="0" class="has-value" checked id="no_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="index_status1" class="col-sm-2 form-control-label">{!!  __('backend.allowInstantIndexing') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="index_status" value="1" class="has-value" id="index_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="index_status" value="0" class="has-value" checked id="index_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tabs_settings">
                        <div class="form-group">
                            <br/>
                            <label><h5><i class="material-icons">&#xe8d8;</i> {{ __('backend.additionalTabs') }}
                                </h5></label>
                            <hr class="m-a-0">
                        </div>
                        <div class="form-group row">
                            <label for="multi_images_status1" class="col-sm-2 form-control-label">{!!  __('backend.additionalImages') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="multi_images_status" value="1" class="has-value" id="multi_images_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="multi_images_status" value="0" class="has-value" checked id="multi_images_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="extra_attach_file_status1" class="col-sm-2 form-control-label">{!!  __('backend.additionalFiles') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="extra_attach_file_status" value="1" class="has-value" id="extra_attach_file_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="extra_attach_file_status" value="0" class="has-value" checked id="extra_attach_file_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="maps_status1" class="col-sm-2 form-control-label">{!!  __('backend.attachGoogleMaps') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="maps_status" value="1" class="has-value" id="maps_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="maps_status" value="0" class="has-value" checked id="maps_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="order_status1" class="col-sm-2 form-control-label">{!!  __('backend.attachOrderForm') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="order_status" value="1" class="has-value" checked id="order_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="order_status" value="0" class="has-value" checked id="order_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="comments_status1" class="col-sm-2 form-control-label">{!!  __('backend.reviewsAvailable') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="comments_status" value="1" class="has-value" id="comments_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="comments_status" value="0" class="has-value" checked id="comments_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="related_status1" class="col-sm-2 form-control-label">{!!  __('backend.relatedTopics') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="related_status" value="1" class="has-value" id="related_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="related_status" value="0" class="has-value" checked id="related_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="seo_status1" class="col-sm-2 form-control-label">{!!  __('backend.seoTabTitle') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="seo_status" value="1" class="has-value" id="seo_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="seo_status" value="0" class="has-value" checked id="seo_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="code_status1" class="col-sm-2 form-control-label">{!!  __('backend.customCode') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="code_status" value="1" class="has-value" id="code_status1">
                                        <i class="primary"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="code_status" value="0" class="has-value" checked id="code_status2">
                                        <i class="danger"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <br/>
                        <label><h5><i class="material-icons">&#xe8a4;</i> {{ __('backend.extra') }}
                            </h5></label>
                        <hr class="m-a-0">
                    </div>

                    <div id="cover_settings">
                        <div class="form-group row">
                            <label for="photo"
                                   class="col-sm-2 form-control-label">{!!  __('backend.coverPhoto') !!}</label>
                            <div class="col-sm-10">
                                <input type="file" name="photo" class="form-control" id="photo" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group row m-t-md" style="margin-top: 0 !important;">
                            <div class="offset-sm-2 col-sm-10">
                                <small>
                                    <i class="material-icons">&#xe8fd;</i>
                                    {!!  __('backend.imagesTypes') !!}
                                </small>
                            </div>
                        </div>
                    </div>
                    @if(Helper::GeneralWebmasterSettings("popups_status"))
                        <div class="form-group row">
                            <label for="popup_id"
                                   class="col-sm-2 form-control-label">{!!  __('backend.customPopup') !!}</label>
                            <div class="col-sm-10">
                                <select name="popup_id" id="popup_id" class="form-control c-select">
                                    <option value="">- - {!!  __('backend.none') !!} - -</option>
                                    @foreach(\App\Models\Popup::where("status",1)->get() as $PPopup)
                                        <option
                                            value="{{ $PPopup->id }}">{!!  $PPopup->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row m-t-md">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.add') !!}</button>
                            <a href="{{route("WebmasterSections")}}"
                               class="btn btn-lg btn-default m-t"><i class="material-icons">
                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push("after-scripts")
    <script type="text/javascript">
        $(".secs input[type=radio][name=type]").click(function () {
            $("label").removeClass("sec-active");
            if ($(this).is(":checked")) {
                $(this).parent().addClass("sec-active");
            }

            let module_type = parseInt($(this).val());
            if (module_type === 10) {
                $("#fields_settings").hide();
                $("#tabs_settings").hide();
                $("#cover_settings").hide();
            } else {
                $("#fields_settings").show();
                $("#tabs_settings").show();
                $("#cover_settings").show();
            }
        });
    </script>
@endpush
