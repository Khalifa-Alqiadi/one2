@extends('dashboard.layouts.master')
@section('title', __('backend.siteSectionsSettings'))
@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.sectionEdit') }}</h3>
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
        </div>

        <?php
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
        $tab_4 = "";
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "fields") {
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
                $tab_4 = "";
            }
            if (Session::get('activeTab') == "seo") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "active";
                $tab_4 = "";
            }
            if (Session::get('activeTab') == "code") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "active";
            }
        }
        ?>
        <div class="box nav-active-border b-info">
            <ul class="nav nav-md">
                <li class="nav-item inline">
                    <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details">
                        <span class="text-md"><i class="material-icons">
                                &#xe31e;</i> {{ __('backend.topicTabSection') }}</span>
                    </a>
                </li>
                <li class="nav-item inline {{ ($WebmasterSections->type==10)?"displayNone":"" }}" id="custom-fields-tab-link">
                    <a class="nav-link  {{ $tab_2 }}" data-toggle="tab" data-target="#tab_custom">
                    <span class="text-md"><i class="material-icons">
                            &#xe30d;</i> {{ __('backend.customFields') }}
                        @if($WebmasterSections->allCustomFields->count()>0)
                            <span class="label">{{ $WebmasterSections->allCustomFields->count() }}</span>
                        @endif
                    </span>
                    </a>
                </li>
                <li class="nav-item inline">
                    <a class="nav-link  {{ $tab_3 }}" data-toggle="tab" data-target="#tab_seo">
                    <span class="text-md"><i class="material-icons">
                            &#xe8e5;</i> {{ __('backend.seoTabTitle') }}</span>
                    </a>
                </li>
                <li class="nav-item inline">
                    <a class="nav-link  {{ $tab_4 }}" data-toggle="tab" data-target="#tab_code">
                    <span class="text-md"><i class="material-icons">
                            &#xe86f;</i> {{ __('backend.customCode') }}</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content clear b-t">
                <div class="tab-pane  {{ $tab_1 }}" id="tab_details">
                    <div class="box-body p-a-2">
                        <form method="POST" action="{{ route("WebmasterSectionsUpdate",$WebmasterSections->id) }}" class="dashboard-form" enctype="multipart/form-data">
                            @csrf

                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                <div class="form-group row">
                                    <label
                                        class="col-sm-2 form-control-label">{!!  __('backend.sectionName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="{{ $WebmasterSections->{'title_'.@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
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
                                                class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a {{ ($WebmasterSections->type==0)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="0" class="has-value" {{ ($WebmasterSections->type==0)?"checked":"" }} id="site_status1">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded blue-grey pull-left m-r-md">
                                                    <span class="material-icons sec-icon">&#xe873;</span></div>
                                                <strong>{{ __('backend.typeTextPages') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.generalDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==10)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="10" class="has-value" {{ ($WebmasterSections->type==10)?"checked":"" }} id="site_status11">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded indigo pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe051;</span></div>
                                                <strong>{{ __('backend.landingPages') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.landingPagesDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==1)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="1" class="has-value" {{ ($WebmasterSections->type==1)?"checked":"" }} id="site_status2">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded green pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe251;</span></div>
                                                <strong>{{ __('backend.typePhotos') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.photoDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==2)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="2" class="has-value" {{ ($WebmasterSections->type==2)?"checked":"" }} id="site_status3">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded red pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe63a;</span></div>
                                                <strong>{{ __('backend.typeVideos') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.videoDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==3)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="3" class="has-value" {{ ($WebmasterSections->type==3)?"checked":"" }} id="site_status4">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded warn pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe050;</span></div>
                                                <strong>{{ __('backend.typeSounds') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.audioDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==5)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="5" class="has-value" {{ ($WebmasterSections->type==5)?"checked":"" }} id="site_status6">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded accent pull-left m-r">
                                                    <span class="fa fa-table sec-icon"></span></div>
                                                <strong>{{ __('backend.tableView') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.tableDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==8)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="8" class="has-value" {{ ($WebmasterSections->type==8)?"checked":"" }} id="site_status9">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded teal pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe896;</span></div>
                                                <strong>{{ __('backend.accordionSection') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.accordionSectionDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==9)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="9" class="has-value" {{ ($WebmasterSections->type==9)?"checked":"" }} id="site_status10">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded orange pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe2c8;</span></div>
                                                <strong>{{ __('backend.documentationSection') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.documentationSectionDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==4)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="4" class="has-value" {{ ($WebmasterSections->type==4)?"checked":"" }} id="site_status5">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded pink pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe899;</span></div>
                                                <strong>{{ __('backend.private') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.privateDesc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==7)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="7" class="has-value" {{ ($WebmasterSections->type==7)?"checked":"" }} id="site_status8">
                                                <i class="dark-white"></i>
                                                <div class="p-a-sm rounded deep-purple pull-left m-r">
                                                    <span class="material-icons sec-icon">&#xe880;</span></div>
                                                <strong>{{ __('backend.private2') }}</strong>
                                                <div class="m-x-sm text-muted">{{ __('backend.private2Desc') }}</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ui-check ui-check-md p-y-sm w-100 p-x-2 b-a  {{ ($WebmasterSections->type==6)?"sec-active":"" }}">
                                                <input type="radio" name="type" value="6" class="has-value" {{ ($WebmasterSections->type==6)?"checked":"" }} id="site_status7">
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
                                <label for="sections_status1" class="col-sm-2 form-control-label">{!!  __('backend.hasCategories') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio b-a p-a">
                                        <div>
                                            <label class="md-check">
                                                <input type="radio" name="sections_status" value="0" class="has-value" {{ ($WebmasterSections->sections_status==0)?"checked":"" }} id="sections_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.withoutCategories') }}
                                            </label>
                                        </div>
                                        <div>
                                            <label class="md-check">
                                                <input type="radio" name="sections_status" value="1" class="has-value" {{ ($WebmasterSections->sections_status==1)?"checked":"" }} id="sections_status2">
                                                <i class="primary"></i>
                                                {{ __('backend.mainCategoriesOnly') }}
                                            </label>
                                        </div>
                                        <div>
                                            <label class="md-check">
                                                <input type="radio" name="sections_status" value="2" class="has-value" {{ ($WebmasterSections->sections_status==2)?"checked":"" }} id="sections_status3">
                                                <i class="primary"></i>
                                                {{ __('backend.mainAndSubCategories') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="fields_settings" class="{{ ($WebmasterSections->type==10)?"displayNone":"" }}">
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
                                                <input type="radio" name="title_status" value="1" class="has-value" {{ ($WebmasterSections->title_status==1)?"checked":"" }} id="title_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="title_status" value="0" class="has-value" {{ ($WebmasterSections->title_status==0)?"checked":"" }} id="title_status2">
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
                                                <input type="radio" name="date_status" value="1" class="has-value" {{ ($WebmasterSections->date_status==1)?"checked":"" }} id="date_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="date_status" value="0" class="has-value" {{ ($WebmasterSections->date_status==0)?"checked":"" }} id="date_status2">
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
                                                <input type="radio" name="expire_date_status" value="1" class="has-value" {{ ($WebmasterSections->expire_date_status==1)?"checked":"" }} id="expire_date_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="expire_date_status" value="0" class="has-value" {{ ($WebmasterSections->expire_date_status==0)?"checked":"" }} id="expire_date_status2">
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
                                                <input type="radio" name="longtext_status" value="1" class="has-value" {{ ($WebmasterSections->longtext_status==1)?"checked":"" }} id="longtext_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="longtext_status" value="0" class="has-value" {{ ($WebmasterSections->longtext_status==0)?"checked":"" }} id="longtext_status2">
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
                                                <input type="radio" name="editor_status" value="1" class="has-value" {{ ($WebmasterSections->editor_status==1)?"checked":"" }} id="editor_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="editor_status" value="0" class="has-value" {{ ($WebmasterSections->editor_status==0)?"checked":"" }} id="editor_status2">
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
                                                <input type="radio" name="tags_status" value="1" class="has-value" {{ ($WebmasterSections->tags_status==1)?"checked":"" }} id="tags_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="tags_status" value="0" class="has-value" {{ ($WebmasterSections->tags_status==0)?"checked":"" }} id="tags_status0">
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
                                                <input type="radio" name="case_status" value="1" class="has-value" {{ ($WebmasterSections->case_status==1)?"checked":"" }} id="case_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="case_status" value="0" class="has-value" {{ ($WebmasterSections->case_status==0)?"checked":"" }} id="case_status2">
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
                                                <input type="radio" name="featured_status" value="1" class="has-value" {{ ($WebmasterSections->featured_status==1)?"checked":"" }} id="featured_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="featured_status" value="0" class="has-value" {{ ($WebmasterSections->featured_status==0)?"checked":"" }} id="featured_status2">
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
                                                <input type="radio" name="visits_status" value="1" class="has-value" {{ ($WebmasterSections->visits_status==1)?"checked":"" }} id="visits_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="visits_status" value="0" class="has-value" {{ ($WebmasterSections->visits_status==0)?"checked":"" }} id="visits_status2">
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
                                                <input type="radio" name="photo_status" value="1" class="has-value" {{ ($WebmasterSections->photo_status==1)?"checked":"" }} id="photo_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="photo_status" value="0" class="has-value" {{ ($WebmasterSections->photo_status==0)?"checked":"" }} id="photo_status2">
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
                                                <input type="radio" name="attach_file_status" value="1" class="has-value" {{ ($WebmasterSections->attach_file_status==1)?"checked":"" }} id="attach_file_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="attach_file_status" value="0" class="has-value" {{ ($WebmasterSections->attach_file_status==0)?"checked":"" }} id="attach_file_status2">
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
                                                <input type="radio" name="section_icon_status" value="1" class="has-value" {{ ($WebmasterSections->section_icon_status==1)?"checked":"" }} id="section_icon_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="section_icon_status" value="0" class="has-value" {{ ($WebmasterSections->section_icon_status==0)?"checked":"" }} id="section_icon_status2">
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
                                                <input type="radio" name="icon_status" value="1" class="has-value" {{ ($WebmasterSections->icon_status==1)?"checked":"" }} id="icon_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="icon_status" value="0" class="has-value" {{ ($WebmasterSections->icon_status==0)?"checked":"" }} id="icon_status2">
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
                                                <input type="radio" name="no_status" value="1" class="has-value" {{ ($WebmasterSections->no_status==1)?"checked":"" }} id="no_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="no_status" value="0" class="has-value" {{ ($WebmasterSections->no_status==0)?"checked":"" }} id="no_status2">
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
                                                <input type="radio" name="index_status" value="1" class="has-value" {{ ($WebmasterSections->index_status==1)?"checked":"" }} id="index_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="index_status" value="0" class="has-value" {{ ($WebmasterSections->index_status==0)?"checked":"" }} id="index_status2">
                                                <i class="danger"></i>
                                                {{ __('backend.no') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabs_settings" class="{{ ($WebmasterSections->type==10)?"displayNone":"" }}">
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
                                                <input type="radio" name="multi_images_status" value="1" class="has-value" {{ ($WebmasterSections->multi_images_status==1)?"checked":"" }} id="multi_images_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="multi_images_status" value="0" class="has-value" {{ ($WebmasterSections->multi_images_status==0)?"checked":"" }} id="multi_images_status2">
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
                                                <input type="radio" name="extra_attach_file_status" value="1" class="has-value" {{ ($WebmasterSections->extra_attach_file_status==1)?"checked":"" }} id="extra_attach_file_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="extra_attach_file_status" value="0" class="has-value" {{ ($WebmasterSections->extra_attach_file_status==0)?"checked":"" }} id="extra_attach_file_status2">
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
                                                <input type="radio" name="maps_status" value="1" class="has-value" {{ ($WebmasterSections->maps_status==1)?"checked":"" }} id="maps_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="maps_status" value="0" class="has-value" {{ ($WebmasterSections->maps_status==0)?"checked":"" }} id="maps_status2">
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
                                                <input type="radio" name="order_status" value="1" class="has-value" {{ ($WebmasterSections->order_status==1)?"checked":"" }} id="order_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="order_status" value="0" class="has-value" {{ ($WebmasterSections->order_status==0)?"checked":"" }} id="order_status2">
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
                                                <input type="radio" name="comments_status" value="1" class="has-value" {{ ($WebmasterSections->comments_status==1)?"checked":"" }} id="comments_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="comments_status" value="0" class="has-value" {{ ($WebmasterSections->comments_status==0)?"checked":"" }} id="comments_status2">
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
                                                <input type="radio" name="related_status" value="1" class="has-value" {{ ($WebmasterSections->related_status==1)?"checked":"" }} id="related_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="related_status" value="0" class="has-value" {{ ($WebmasterSections->related_status==0)?"checked":"" }} id="related_status2">
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
                                                <input type="radio" name="seo_status" value="1" class="has-value" {{ ($WebmasterSections->seo_status==1)?"checked":"" }} id="seo_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="seo_status" value="0" class="has-value" {{ ($WebmasterSections->seo_status==0)?"checked":"" }} id="seo_status2">
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
                                                <input type="radio" name="code_status" value="1" class="has-value" {{ ($WebmasterSections->code_status==1)?"checked":"" }} id="code_status1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="code_status" value="0" class="has-value" {{ ($WebmasterSections->code_status==0)?"checked":"" }} id="code_status2">
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

                            <div id="cover_settings" class="{{ ($WebmasterSections->type==10)?"displayNone":"" }}">
                                <div class="form-group row">
                                    <label for="photo"
                                           class="col-sm-2 form-control-label">{!!  __('backend.coverPhoto') !!}</label>
                                    <div class="col-sm-10">
                                        @if($WebmasterSections->photo!="")
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div id="section_photo" class="col-sm-4 box p-a-xs">
                                                        <a target="_blank"
                                                           href="{{ route("fileView",["path" =>'topics/'.$WebmasterSections->photo]) }}"><img
                                                                src="{{ route("fileView",["path" =>'topics/'.$WebmasterSections->photo]) }}"
                                                                class="img-responsive">
                                                            {{ $WebmasterSections->photo }}
                                                        </a>
                                                        <br>
                                                        <a onclick="document.getElementById('section_photo').style.display='none';document.getElementById('photo_delete').value='1';document.getElementById('undo').style.display='block';"
                                                           class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                                    </div>
                                                    <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                                        <a onclick="document.getElementById('section_photo').style.display='block';document.getElementById('photo_delete').value='0';document.getElementById('undo').style.display='none';">
                                                            <i class="material-icons">
                                                                &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                                    </div>

                                                    <input type="hidden" name="photo_delete" id="photo_delete" value="0">
                                                </div>
                                            </div>

                                        @endif
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
                                                    value="{{ $PPopup->id }}" {{ ($PPopup->id == $WebmasterSections->popup_id) ? "selected='selected'":""  }}>{!!  $PPopup->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif


                            <div class="form-group">
                                <br/>
                                <label><h5><i class="material-icons">&#xe8ac;</i> {{ __('backend.active_disable') }}
                                    </h5></label>
                                <hr class="m-a-0">
                            </div>

                            <div class="form-group row">
                                <label for="status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check">
                                            <input type="radio" name="status" value="1" class="has-value" {{ ($WebmasterSections->status==1)?"checked":"" }} id="status1">
                                            <i class="primary"></i>
                                            {{ __('backend.active') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="status" value="0" class="has-value" {{ ($WebmasterSections->status==0)?"checked":"" }} id="status2">
                                            <i class="danger"></i>
                                            {{ __('backend.notActive') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-t-md">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                                    <a href="{{route("WebmasterSections")}}"
                                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- Custom Fields--}}
                <div class="tab-pane  {{ $tab_2 }}" id="tab_custom">
                    <div class="box-body">
                        @if (Session::has('fieldST'))
                            @include('dashboard.modules.fields.create')
                            @include('dashboard.modules.fields.edit')
                        @else
                            @include('dashboard.modules.fields.list')
                        @endif
                    </div>
                </div>
                {{-- End of Custom Fields --}}

                @include('dashboard.modules.seo')
                @include('dashboard.modules.code')

            </div>
        </div>
    </div>
@endsection
@push("after-scripts")
    <script type="text/javascript">
        $("#checkAll4").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#action4").change(function () {
            if (this.value === "delete") {
                $("#submit_all4").css("display", "none");
                $("#submit_show_msg4").css("display", "inline-block");
            } else {
                $("#submit_all4").css("display", "inline-block");
                $("#submit_show_msg4").css("display", "none");
            }
        });
        $(".fields-types input[type=radio][name=type]").click(function () {
            let typ = parseInt($(this).val());
            if (typ === 6 || typ === 7 || typ === 13 || typ === 17) {
                $("#options").css("display", "inline");
                $("#fixed_text").hide();
                $(".in_statics_div").show();
            } else if (typ === 99) {
                $("#fixed_text").css("display", "inline");
                $("#options").hide();
                $(".in_statics_div").show();
            } else {
                $("#fixed_text").hide();
                $("#options").hide();
                $(".in_statics_div").hide();
            }
            $("#in_statics2").checked = true;
            if (typ === 8 || typ === 9 || typ === 10) {
                $("#default_val").hide();
            } else {
                $("#default_val").css("display", "block");
            }
        });
        $(".secs input[type=radio][name=type]").click(function () {
            $("label").removeClass("sec-active");
            if ($(this).is(":checked")) {
                $(this).parent().addClass("sec-active");
            }

            let module_type = parseInt($(this).val());
            if (module_type === 10) {
                $("#custom-fields-tab-link").hide();
                $("#fields_settings").hide();
                $("#tabs_settings").hide();
                $("#cover_settings").hide();
            } else {
                $("#custom-fields-tab-link").css("display", "inline-block");
                $("#fields_settings").show();
                $("#tabs_settings").show();
                $("#cover_settings").show();
            }
        });
    </script>
@endpush
