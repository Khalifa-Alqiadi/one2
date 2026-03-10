@extends('dashboard.layouts.master')
<?php
$title_var = "title_".@Helper::currentLanguage()->code;
$title_var2 = "title_".config('smartend.default_language');
if ($WebmasterSection->$title_var != "") {
    $WebmasterSectionTitle = $WebmasterSection->$title_var;
} else {
    $WebmasterSectionTitle = $WebmasterSection->$title_var2;
}
?>
@section('title', $Topic->{"title_" . @Helper::currentLanguage()->code})
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/dropzone/dist/min/dropzone.min.css") }}" rel="stylesheet">
@endpush
@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">
                <?php
                $title_var = "title_".@Helper::currentLanguage()->code;
                $title_var2 = "title_".config('smartend.default_language');
                if ($WebmasterSection->$title_var != "") {
                    $WebmasterSectionTitle = $WebmasterSection->$title_var;
                } else {
                    $WebmasterSectionTitle = $WebmasterSection->$title_var2;
                }
                ?>
                <h3><i class="material-icons">
                        &#xe3c9;</i> {{ __('backend.topicEdit') }} {!! $WebmasterSectionTitle !!}
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! $WebmasterSectionTitle !!}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline dropdown">
                        <a class="btn white b-a nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="material-icons md-18">&#xe5d4;</i> {{  __('backend.options') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale pull-right">
                            <a class="dropdown-item" href="{{ route('topics',$WebmasterSection->id) }}"><i
                                    class="material-icons">&#xe31b;</i> {{ __('backend.back') }}</a>
                            <a class="dropdown-item"
                               href="{{ ((@$Topic->webmasterSection->type == 4 || @$Topic->webmasterSection->type == 6) ? route("topicView", ["webmasterId" => @$Topic->webmasterSection->id, "id" => $Topic->id]) : Helper::topicURL($Topic->id,'',$Topic)) }}" {!! ((@$Topic->webmasterSection->type == 4 || @$Topic->webmasterSection->type == 6) ? "" : "target='_blank'") !!}><i
                                    class="material-icons">&#xe8f4;</i> {{ __('backend.preview') }}</a>
                            @if (@Auth::user()->permissionsGroup->add_status)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                   href="{{ route("topicsClone", ["webmasterId" => @$Topic->webmasterSection->id, "id" => $Topic->id]) }}"><i
                                        class="material-icons">&#xe14d;</i> {{ __('backend.clone') }}</a>
                            @endif

                            @if(Helper::GeneralWebmasterSettings("instant_index"))
                                @if($WebmasterSection->index_status)
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                       href="{{ route("topicsInstantIndex", ["webmasterId" => @$Topic->webmasterSection->id, "id" => $Topic->id]) }}?action=add"><i
                                            class="material-icons">&#xe148;</i> {{ __('backend.sendToGoogleIndex') }}
                                    </a>
                                    <a class="dropdown-item"
                                       href="{{ route("topicsInstantIndex", ["webmasterId" => @$Topic->webmasterSection->id, "id" => $Topic->id]) }}?action=remove"><i
                                            class="material-icons">&#xe15d;</i> {{ __('backend.removeToGoogleIndex') }}
                                    </a>
                                @endif
                            @endif

                            @if (@Auth::user()->permissionsGroup->delete_status)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" onclick="DeleteTopic('{{ $Topic->id }}')"><i
                                        class="material-icons">&#xe872;</i> {{ __('backend.delete') }}</a>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <?php
        $tab_0 = "";
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
        $tab_4 = "";
        $tab_5 = "";
        $tab_6 = "";
        $tab_7 = "";
        $tab_8 = "";
        if ($WebmasterSection->type == 10) {
            $tab_0 = "active";
            $tab_1 = "";
        }
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "details") {
                $tab_0 = "";
                $tab_1 = "active";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
                $tab_8 = "";
            }
            if (Session::get('activeTab') == "seo") {
                $tab_0 = "";
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
                $tab_8 = "";
            }
            if (Session::get('activeTab') == "photos") {
                $tab_0 = "";
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "active";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
                $tab_8 = "";
            }
            if (Session::get('activeTab') == "comments") {
                $tab_0 = "";
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "active";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
                $tab_8 = "";
            }
            if (Session::get('activeTab') == "maps") {
                $tab_0 = "";
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "active";
                $tab_6 = "";
                $tab_7 = "";
                $tab_8 = "";
            }
            if (Session::get('activeTab') == "files") {
                $tab_0 = "";
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "active";
                $tab_7 = "";
                $tab_8 = "";
            }
            if (Session::get('activeTab') == "related") {
                $tab_0 = "";
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "active";
                $tab_8 = "";
            }
            if (Session::get('activeTab') == "code") {
                $tab_0 = "";
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
                $tab_8 = "active";
            }
        }
        ?>
        <div class="box nav-active-border b-primary">
            <ul class="nav nav-md light dk">
                @if($WebmasterSection->type == 10)
                    <li class="nav-item inline">
                        <a class="nav-link {{ $tab_0 }}" data-toggle="tab" data-target="#tab_landing" href="#">
                        <span class="text-md"><i class="material-icons">
                                &#xe8ed;</i> {{ __('backend.pageBlocks') }}</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item inline">
                    <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details" href="#">
                        <span class="text-md"><i class="material-icons">
                                &#xe31e;</i> {{ ($WebmasterSection->type == 10)?__('backend.pageInfo'):__('backend.topicTabDetails') }}</span>
                    </a>
                </li>

                @if($WebmasterSection->multi_images_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_3 }}" data-toggle="tab" data-target="#tab_photos" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe251;</i>
                        {{ __('backend.topicAdditionalPhotos') }}
                        @if(count($Topic->photos)>0)
                            <span class="label">{{ count($Topic->photos) }}</span>
                        @endif
                    </span>
                        </a>
                    </li>
                @endif

                @if($WebmasterSection->extra_attach_file_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_6 }}" data-toggle="tab" data-target="#tab_files" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe226;</i> {{ __('backend.additionalFiles') }}
                        @if(count($Topic->attachFiles)>0)
                            <span class="label">{{ count($Topic->attachFiles) }}</span>
                        @endif
                    </span>
                        </a>
                    </li>
                @endif

                @if($WebmasterSection->comments_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_4 }}" data-toggle="tab" data-target="#tab_comments" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe0b9;</i> {{ __('backend.comments') }}
                        @if(count($Topic->comments)>0)
                            <span class="label">{{ count($Topic->comments) }}</span>
                        @endif
                    </span>
                        </a>
                    </li>
                @endif


                @if($WebmasterSection->maps_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_5 }}" id="mapTabLink" data-toggle="tab"
                           data-target="#tab_maps" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe0c8;</i> {{ __('backend.topicGoogleMaps') }}
                        @if(count($Topic->maps)>0)
                            <span class="label">{{ count($Topic->maps) }}</span>
                        @endif
                    </span>
                        </a>
                    </li>
                @endif

                @if($WebmasterSection->related_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_7 }}" data-toggle="tab" data-target="#tab_related" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe867;</i> {{ __('backend.relatedTopics') }}
                        @if(count($Topic->relatedTopics)>0)
                            <span class="label">{{ count($Topic->relatedTopics) }}</span>
                        @endif
                    </span>
                        </a>
                    </li>
                @endif

                @if($WebmasterSection->seo_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_2 }}" data-toggle="tab" data-target="#tab_seo" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe8e5;</i> {{ __('backend.seoTabTitle') }}</span>
                        </a>
                    </li>
                @endif
                @if($WebmasterSection->code_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_8 }}" data-toggle="tab" data-target="#tab_code" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe86f;</i> {{ __('backend.customCode') }}</span>
                        </a>
                    </li>
                @endif

            </ul>
            <div class="tab-content b-t clear b-t">
                <div class="tab-pane  {{ $tab_1 }}" id="tab_details">
                    <div class="box-body p-a-2">
                        <form method="POST" action="{{ route("topicsUpdate",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dashboard-form" enctype="multipart/form-data">
                            @csrf
                            @if($WebmasterSection->date_status)
                                <div class="form-group row">
                                    <label for="date" class="col-sm-2 form-control-label">{!!  __('backend.topicDate') !!}
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }}',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                                <input type="text" name="date" id="date" class="form-control" value="{{ Helper::formatDate($Topic->date) }}" placeholder="" maxlength="191" autocomplete="off" required>
                                                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="date" id="date" class="form-control" value="{{ $Topic->date }}">
                            @endif


                            @if($WebmasterSection->expire_date_status)
                                <div class="form-group row">
                                    <label for="expire_date" class="col-sm-2 form-control-label">{!!  __('backend.expireDate') !!}
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }}',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                                <input type="text" name="expire_date" id="expire_date" class="form-control" value="{{ Helper::formatDate($Topic->expire_date) }}" placeholder="" maxlength="191" autocomplete="off">
                                                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            @if($WebmasterSection->sections_status!=0)
                                <div class="form-group row">
                                    <label for="section_id" class="col-sm-2 form-control-label">{!!  __('backend.categories') !!} </label>
                                    <div class="col-sm-10">
                                        <select name="section_id[]" id="cat_ids" class="form-control select2-multiple"
                                                multiple
                                                ui-jp="select2"
                                                ui-options="{theme: 'bootstrap'}" required onchange="load_custom_fields()">
                                                <?php
                                                $title_var = "title_".@Helper::currentLanguage()->code;
                                                $title_var2 = "title_".config('smartend.default_language');

                                                $t_arrow = "&raquo;";

                                                $categories = array();
                                                foreach ($Topic->categories as $category) {
                                                    $categories[] = $category->section_id;
                                                }
                                                ?>
                                            @foreach ($fatherSections as $fatherSection)
                                                    <?php
                                                    if ($fatherSection->$title_var != "") {
                                                        $ftitle = $fatherSection->$title_var;
                                                    } else {
                                                        $ftitle = $fatherSection->$title_var2;
                                                    }
                                                    ?>
                                                <option
                                                    value="{{ $fatherSection->id  }}" {{ (in_array($fatherSection->id,$categories)) ? "selected='selected'":""  }}>{{ $ftitle }}</option>
                                                @foreach ($fatherSection->fatherSections as $subFatherSection)
                                                        <?php
                                                        if ($subFatherSection->$title_var != "") {
                                                            $title = $subFatherSection->$title_var;
                                                        } else {
                                                            $title = $subFatherSection->$title_var2;
                                                        }
                                                        ?>
                                                    <option
                                                        value="{{ $subFatherSection->id  }}" {{ (in_array($subFatherSection->id,$categories)) ? "selected='selected'":""  }}> {!! $ftitle !!} {!! $t_arrow !!} {!! $title !!}</option>

                                                    @foreach ($subFatherSection->fatherSections as $sub2FatherSection)
                                                            <?php
                                                            if ($sub2FatherSection->$title_var != "") {
                                                                $title2 = $sub2FatherSection->$title_var;
                                                            } else {
                                                                $title2 = $sub2FatherSection->$title_var2;
                                                            }
                                                            ?>
                                                        <option
                                                            value="{{ $sub2FatherSection->id  }}" {{ (in_array($sub2FatherSection->id,$categories)) ? "selected='selected'":""  }}> {!! $ftitle !!} {!! $t_arrow !!} {!! $title !!} {!! $t_arrow !!} {!! $title2 !!}</option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="section_id" id="cat_ids" value="{{ $Topic->section_id }}" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                            @endif

                            @if($WebmasterSection->title_status)
                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                    @if($ActiveLanguage->box_status)
                                        <div class="form-group row">
                                            <label for="title_{{ @$ActiveLanguage->code }}" class="col-sm-2 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="{{ $Topic->{'title_'.@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif


                            @if($WebmasterSection->longtext_status)

                                @if($WebmasterSection->editor_status)

                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                        @if($ActiveLanguage->box_status)
                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <label
                                                        class="form-control-label">{!!  __('backend.bannerDetails') !!}</label>
                                                    {!! @Helper::languageName($ActiveLanguage) !!}

                                                    <a class="btn btn-outline b-a m-y-sm  light dk w-full"
                                                       href="{{ route("keditor",$Topic->id) }}?lang={{ @$ActiveLanguage->code }}"
                                                       target="_blank" style="white-space: normal;">
                                                        <i class="material-icons text-lg text-primary">&#xe434;</i><br>
                                                        <small>{!!  __('backend.clickToUseDragAndDropEditor') !!}</small>
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fa fa-info-circle"></i>
                                                        <small>{!!  __('backend.refreshAfterKEdit') !!}.</small>
                                                    </small>
                                                </div>
                                                <div class="col-sm-10">
                                                    @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                                        <div>
                                                            <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control ckeditor" dir="{{ @$ActiveLanguage->direction }}">{{ $Topic->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                                        </div>
                                                    @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                                        <div>
                                                            <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control tinymce" dir="{{ @$ActiveLanguage->direction }}">{{ $Topic->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                                        </div>
                                                    @else
                                                        <div class="box p-a-xs">
                                                            <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control summernote summernote_{{ @$ActiveLanguage->code }}" dir="{{ @$ActiveLanguage->direction }}">{{ $Topic->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else

                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                        @if($ActiveLanguage->box_status)
                                            <div class="form-group row">
                                                <label
                                                    class="col-sm-2 form-control-label">{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                </label>
                                                <div class="col-sm-10">
                                                    <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="5">{{ $Topic->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif


                            @if($WebmasterSection->type==2)
                                <div class="form-group row">
                                    <label for="video_type1" class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoType') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="radio m-t-sm">
                                            <label class="md-check">
                                                <input type="radio" name="video_type" value="0" class="has-value" {{ ($Topic->video_type==0)?"checked":"" }} id="video_type1" onclick="document.getElementById('youtube_link_div').style.display='none';document.getElementById('embed_link_div').style.display='none';document.getElementById('vimeo_link_div').style.display='none';document.getElementById('files_div').style.display='block';document.getElementById('youtube_link').value=''">
                                                <i class="primary"></i>
                                                {{ __('backend.bannerVideoType1') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="video_type" value="1" class="has-value" {{ ($Topic->video_type==1)?"checked":"" }} id="video_type2" onclick="document.getElementById('youtube_link_div').style.display='block';document.getElementById('embed_link_div').style.display='none';document.getElementById('vimeo_link_div').style.display='none';document.getElementById('files_div').style.display='none';document.getElementById('youtube_link').value=''">
                                                <i class="primary"></i>
                                                {{ __('backend.bannerVideoType2') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="video_type" value="2" class="has-value" {{ ($Topic->video_type==2)?"checked":"" }} id="video_type3" onclick="document.getElementById('youtube_link_div').style.display='none';document.getElementById('embed_link_div').style.display='none';document.getElementById('vimeo_link_div').style.display='block';document.getElementById('files_div').style.display='none';document.getElementById('vimeo_link').value=''">
                                                <i class="primary"></i>
                                                {{ __('backend.bannerVideoType3') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="video_type" value="3" class="has-value" {{ ($Topic->video_type==3)?"checked":"" }} id="video_type4" onclick="document.getElementById('youtube_link_div').style.display='none';document.getElementById('embed_link_div').style.display='block';document.getElementById('vimeo_link_div').style.display='none';document.getElementById('files_div').style.display='none';document.getElementById('embed_link').value=''">
                                                <i class="primary"></i>
                                                Embed
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="files_div" style="display: {{ ($Topic->video_type ==0) ? "block" : "none" }}">
                                    <div class="form-group row">
                                        <label for="video_file" class="col-sm-2 form-control-label">{!!  __('backend.topicVideo') !!}</label>
                                        <div class="col-sm-10">
                                            @if($Topic->video_type==0 && $Topic->video_file!="")
                                                <div class="box p-a-xs">

                                                    <video width="380" height="230" controls>
                                                        <source src="{{ route("fileView",["path" =>'topics/'.$Topic->video_file]) }}"
                                                                type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <br>
                                                    <a target="_blank"
                                                       href="{{ route("fileView",["path" =>'topics/'.$Topic->video_file]) }}">
                                                        {{ $Topic->video_file }} </a>
                                                </div>
                                            @endif
                                            <input type="file" name="video_file" id="video_file" class="form-control" accept="*">
                                        </div>
                                    </div>

                                    <div class="form-group row m-t-md" style="margin-top: 0 !important;">
                                        <div class="offset-sm-2 col-sm-10">
                                            <small>
                                                <i class="material-icons">&#xe8fd;</i>
                                                {!!  __('backend.videoTypes') !!}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" id="youtube_link_div"
                                     style="display: {{ ($Topic->video_type==1) ? "block" : "none" }}">
                                    <label for="youtube_link" class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoUrl') !!}</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="youtube_link" id="youtube_link" class="form-control" value="{{ $Topic->video_file }}" placeholder="'https://www.youtube.com/watch?v=JQs4QyKnYMQ" maxlength="191" autocomplete="off" dir="ltr">
                                    </div>
                                </div>
                                <div class="form-group row" id="vimeo_link_div"
                                     style="display: {{ ($Topic->video_type ==2) ? "block" : "none" }}">
                                    <label for="youtube_link" class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoUrl2') !!}</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="vimeo_link" id="vimeo_link" class="form-control" value="{{ $Topic->video_file }}" placeholder="'https://vimeo.com/131766159" maxlength="191" autocomplete="off" dir="ltr">
                                    </div>
                                </div>

                                <div class="form-group row" id="embed_link_div"
                                     style="display: {{ ($Topic->video_type ==3) ? "block" : "none" }}">
                                    <label for="embed_link" class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoUrl2') !!}</label>
                                    <div class="col-sm-10">
                                        <textarea name="embed_link" id="embed_link" class="form-control" dir="ltr" rows="3">{{ $Topic->video_file }}</textarea>
                                    </div>
                                </div>
                            @endif

                            @if($WebmasterSection->type==3)
                                {{ $Topic->video_type }}
                                <div class="form-group row">
                                    <label for="audio_type" class="col-sm-2 form-control-label">{!!  __('backend.audioType') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="radio m-t-sm">
                                            <label class="md-check">
                                                <input type="radio" name="video_type" value="0" {{ ($Topic->video_type==0)?"checked":"" }} class="has-value" id="audio_type1" onclick="document.getElementById('embed_audio_div').style.display='none';document.getElementById('audio_file_div').style.display='block';document.getElementById('embed_audio').value=''">
                                                <i class="primary"></i>
                                                {{ __('backend.localFile') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="video_type" value="3" {{ ($Topic->video_type==3)?"checked":"" }} class="has-value" id="audio_type3" onclick="document.getElementById('embed_audio_div').style.display='block';document.getElementById('audio_file_div').style.display='none';document.getElementById('embed_audio').value=''">
                                                <i class="primary"></i>
                                                Embed
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="audio_file_div" style="display: {{ ($Topic->video_type ==0) ? "block" : "none" }}">
                                    <div class="form-group row">
                                        <label for="audio_file" class="col-sm-2 form-control-label">{!!  __('backend.topicAudio') !!}</label>
                                        <div class="col-sm-10">
                                            @if($Topic->audio_file!="" && $Topic->video_type==0)
                                                <div class="box p-a-xs">
                                                    <audio controls>
                                                        <source src="{{ route("fileView",["path" =>'topics/'.$Topic->audio_file]) }}"
                                                                type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                    <br>
                                                    <a target="_blank"
                                                       href="{{ route("fileView",["path" =>'topics/'.$Topic->audio_file]) }}"> {{ $Topic->audio_file }} </a>
                                                </div>
                                            @endif
                                            <input type="file" name="audio_file" id="audio_file" class="form-control" accept="audio/*">
                                        </div>
                                    </div>

                                    <div class="form-group row m-t-md" style="margin-top: 0 !important;">
                                        <div class="offset-sm-2 col-sm-10">
                                            <small>
                                                <i class="material-icons">&#xe8fd;</i>
                                                {!!  __('backend.audioTypes') !!}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div id="embed_audio_div" style="display: {{ ($Topic->video_type ==3) ? "block" : "none" }}">
                                    <div class="form-group row">
                                        <label for="embed_audio" class="col-sm-2 form-control-label">Embed Code</label>
                                        <div class="col-sm-10">
                                            <textarea name="embed_audio" id="embed_audio" class="form-control" dir="ltr" rows="3">{{ $Topic->audio_file }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($WebmasterSection->photo_status)
                                <div class="form-group row">
                                    <label for="photo_file" class="col-sm-2 form-control-label">{!!  __('backend.topicPhoto') !!}</label>
                                    <div class="col-sm-10">
                                        @if($Topic->photo_file!="")
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div id="topic_photo" class="col-sm-4 box p-a-xs">
                                                        <a target="_blank"
                                                           href="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file]) }}"><img
                                                                src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file]) }}"
                                                                class="img-responsive">
                                                            {{ $Topic->photo_file }}
                                                        </a>
                                                        <br>
                                                        <a onclick="document.getElementById('topic_photo').style.display='none';document.getElementById('photo_delete').value='1';document.getElementById('undo').style.display='block';"
                                                           class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                                    </div>
                                                    <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                                        <a onclick="document.getElementById('topic_photo').style.display='block';document.getElementById('photo_delete').value='0';document.getElementById('undo').style.display='none';">
                                                            <i class="material-icons">
                                                                &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                                    </div>

                                                    <input type="hidden" name="photo_delete" value="0" id="photo_delete">
                                                </div>
                                            </div>
                                        @endif

                                        <input type="file" name="photo_file" id="photo_file" class="form-control" accept="image/*">

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
                            @endif

                            @if($WebmasterSection->icon_status)
                                <div class="form-group row">
                                    <label for="icon"
                                           class="col-sm-2 form-control-label">{!!  __('backend.sectionIcon') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <i id="iconPreview" class="{{ $Topic->icon }}"></i>
                                        </span>
                                            <input type="text" class="form-control" autocomplete="off" name="icon" id="iconPicker" value="{{ $Topic->icon }}">
                                            <span class="input-group-btn">
            <button class="btn white" type="button" id="iconPickerButton">{!!  __('backend.chooseIcon') !!}</button>
          </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($WebmasterSection->attach_file_status)
                                <div class="form-group row">
                                    <label for="attach_file" class="col-sm-2 form-control-label">{!!  __('backend.topicAttach') !!}</label>
                                    <div class="col-sm-10">
                                        @if($Topic->attach_file!="")
                                            <div id="topic_attach" class="col-sm-4 box p-a-xs">
                                                <a target="_blank"
                                                   href="{{ route("fileView",["path" =>'topics/'.$Topic->attach_file]) }}"> {{ $Topic->attach_file }} </a>
                                                <br>
                                                <a onclick="document.getElementById('topic_attach').style.display='none';document.getElementById('attach_delete').value='1';document.getElementById('undo2').style.display='block';"
                                                   class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                            </div>
                                            <div id="undo2" class="col-sm-4 p-a-xs" style="display: none">
                                                <a onclick="document.getElementById('topic_attach').style.display='block';document.getElementById('attach_delete').value='0';document.getElementById('undo2').style.display='none';">
                                                    <i class="material-icons">
                                                        &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                            </div>
                                            <input type="hidden" name="attach_delete" value="0" id="attach_delete">
                                        @endif
                                        <input type="file" name="attach_file" id="attach_file" class="form-control" accept="{{ ".".@str_replace(",",",.",@$allowed_file_types) }}">
                                    </div>
                                </div>
                            @endif

                            <div id="customFields"></div>

                            @if($WebmasterSection->tags_status)
                                <div class="form-group row">
                                    <label for="icon" class="col-sm-2 form-control-label">{!!  __('backend.tags') !!}</label>
                                    <div class="col-sm-10">
                                            <?php
                                            $exist_tags = [];
                                            foreach ($Topic->tags as $tag_item) {
                                                if (!empty($tag_item->tag)) {
                                                    $exist_tags[] = $tag_item->tag->title;
                                                }
                                            }
                                            ?>
                                        <input type="text" name="tags" id="tags" class="form-control form-tags" value="{{ @implode(",",$exist_tags) }}" autocomplete="off">
                                    </div>
                                </div>
                                @push("after-styles")
                                    <link href="{{ asset("assets/dashboard/js/jquery-ui/jquery-ui.min.css") }}"
                                          rel="stylesheet">
                                    <link href="{{ asset("assets/dashboard/js/tags-input/tagsinput.min.css") }}"
                                          rel="stylesheet">
                                @endpush
                                @push("after-scripts")
                                    <script src="{{ asset("assets/dashboard/js/jquery-ui/jquery-ui.min.js") }}"></script>
                                    <script src="{{ asset("assets/dashboard/js/tags-input/tagsinput.min.js") }}"></script>
                                    <script>
                                        $('.form-tags').tagsInput({
                                            placeholder: '{!!  __('backend.typeTags') !!}',
                                            'autocomplete': {
                                                source: [
                                                    @foreach($TagsList as $Tag)
                                                        '{{ $Tag->title }}',
                                                    @endforeach
                                                ]
                                            }
                                        });
                                    </script>
                                @endpush
                            @endif

                            @if($WebmasterSection->type ==0)
                                <div class="form-group row">
                                    <label for="page_form_id" class="col-sm-2 form-control-label">{!!  __('backend.pageCustomForm') !!}</label>
                                    <div class="col-sm-10">
                                        <select name="page_form_id" class="form-control c-select" id="page_form_id">
                                            <option value="">- - {!!  __('backend.none') !!} - -</option>
                                            @foreach($GeneralWebmasterSections->where("type",6) as $FWebmasterSection)
                                                <option
                                                    value="{{ $FWebmasterSection->id }}" {{ ($FWebmasterSection->id == $Topic->form_id) ? "selected='selected'":""  }}>{!!  $FWebmasterSection->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(Helper::GeneralWebmasterSettings("popups_status"))
                                <div class="form-group row">
                                    <label for="popup_id" class="col-sm-2 form-control-label">{!!  __('backend.customPopup') !!}</label>
                                    <div class="col-sm-10">
                                        <select name="popup_id" class="form-control c-select" id="popup_id">
                                            <option value="">- - {!!  __('backend.none') !!} - -</option>
                                            @foreach(\App\Models\Popup::where("status",1)->get() as $PPopup)
                                                <option
                                                    value="{{ $PPopup->id }}" {{ ($PPopup->id == $Topic->popup_id) ? "selected='selected'":""  }}>{!!  $PPopup->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if($WebmasterSection->featured_status)
                                <div class="form-group row">
                                    <label for="featured1" class="col-sm-2 form-control-label">{!!  __('backend.featured') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="radio m-t-sm">
                                            <label class="md-check">
                                                <input type="radio" name="featured" value="1" class="has-value" id="featured1" {{ ($Topic->featured==1)?"checked":"" }}>
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="featured" value="0" class="has-value" {{ ($Topic->featured==0)?"checked":"" }} id="featured2">
                                                <i class="danger"></i>
                                                {{ __('backend.no') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if($WebmasterSection->type ==10)
                                <div class="form-group row">
                                    <label for="hide_header1" class="col-sm-2 form-control-label">{!!  __('backend.showHeader') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="radio m-t-sm">
                                            <label class="md-check">
                                                <input type="radio" name="hide_header" value="0" class="has-value" {{ ($Topic->hide_header==0)?"checked":"" }} id="hide_header1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="hide_header" value="1" class="has-value" {{ ($Topic->hide_header==1)?"checked":"" }} id="hide_header2">
                                                <i class="danger"></i>
                                                {{ __('backend.no') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="hide_footer1" class="col-sm-2 form-control-label">{!!  __('backend.showFooter') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="radio m-t-sm">
                                            <label class="md-check">
                                                <input type="radio" name="hide_footer" value="0" class="has-value" {{ ($Topic->hide_footer==0)?"checked":"" }} id="hide_footer1">
                                                <i class="primary"></i>
                                                {{ __('backend.yes') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="hide_footer" value="1" class="has-value" {{ ($Topic->hide_footer==1)?"checked":"" }} id="hide_footer2">
                                                <i class="danger"></i>
                                                {{ __('backend.no') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(@Auth::user()->permissionsGroup->active_status)
                                @if($WebmasterSection->case_status)
                                    <div class="form-group row">
                                        <label for="status1" class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                        <div class="col-sm-10">
                                            <div class="radio m-t-sm">
                                                <label class="md-check">
                                                    <input type="radio" name="status" value="1" class="has-value" id="status1" {{ ($Topic->status==1)?"checked":"" }}>
                                                    <i class="primary"></i>
                                                    {{ __('backend.yes') }}
                                                </label>
                                                &nbsp; &nbsp;
                                                <label class="md-check">
                                                    <input type="radio" name="status" value="0" class="has-value" {{ ($Topic->status==0)?"checked":"" }} id="status2">
                                                    <i class="danger"></i>
                                                    {{ __('backend.no') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif


                            <div class="form-group row m-t-md">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" id="page_submit_btn" class="btn btn-lg btn-primary m-t">
                                        <i class="material-icons">
                                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                                    <a href="{{ route('topics',$WebmasterSection->id) }}"
                                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                @include('dashboard.topics.landing.blocks')
                @include('dashboard.topics.tabs.photos')
                @include('dashboard.topics.tabs.comments')
                @include('dashboard.topics.tabs.files')
                @include('dashboard.topics.tabs.related')
                @include('dashboard.topics.tabs.maps')
                @include('dashboard.topics.tabs.seo')
                @include('dashboard.topics.tabs.code')
            </div>
        </div>
    </div>
    <!-- .modal -->
    <div id="delete-topic" class="modal fade" data-backdrop="true">
        <div class="modal-dialog" id="animate">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <h5 class="m-b-0">
                        {{ __('backend.confirmationDeleteMsg') }}
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark-white p-x-md"
                            data-dismiss="modal">{{ __('backend.no') }}</button>
                    <button type="button" id="topic_delete_btn" row-id=""
                            class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>

    <div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-labelledby="iconModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconModalLabel">{!!  __('backend.chooseIcon') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-a-0 m-b">
                    <div class="icon-search-container p-a">
                        <div class="row">
                            <div class="col-sm-7 col-xs-6">
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" id="iconSearch" placeholder="{!!  __('backend.search') !!}...">
                                </div>
                            </div>
                            <div class="col-sm-5 col-xs-6">
                                <div class="form-group mb-0">
                                    <select class="form-control c-select" id="iconStyle">
                                        <option value="all">All Styles</option>
                                        <option value="solid">Solid</option>
                                        <option value="regular">Regular</option>
                                        <option value="brands">Brands</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="iconsContainer" class="p-a" dir="ltr">
                        <!-- Icons will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("after-scripts")
    @if(Helper::GeneralWebmasterSettings("text_editor") ==1)
        @include('dashboard.layouts.tinymce')
    @endif
    <script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function () {
            if (this.value === "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });

        $("#checkAll2").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#checkAll4").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action2").change(function () {
            if (this.value === "delete") {
                $("#submit_all2").css("display", "none");
                $("#submit_show_msg2").css("display", "inline-block");
            } else {
                $("#submit_all2").css("display", "inline-block");
                $("#submit_show_msg2").css("display", "none");
            }
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

        $("#checkAll3").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action3").change(function () {
            if (this.value === "delete") {
                $("#submit_all3").css("display", "none");
                $("#submit_show_msg3").css("display", "inline-block");
            } else {
                $("#submit_all3").css("display", "inline-block");
                $("#submit_show_msg3").css("display", "none");
            }
        });

        $("#mapDivNew").click(function () {
            $("#mapDiv").css("display", "block");
            $("#mapDivBtns").css("display", "none");
        });
        function load_custom_fields() {
            $("#customFields").html("<div class=\"text-center p-a-2 m-b light dk\"><img class=\"m-b-sm\" src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;\"/><div class='text-muted small'>{{ __("backend.loading") }}</div></div>");
            $("#page_submit_btn").prop('disabled', true);
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("topicsCustomFields"); ?>",
                data: {
                    _token: "{{csrf_token()}}",
                    webmaster_id: '{{ encrypt(@$WebmasterSection->id) }}',
                    topic_id: '{{ encrypt(@$Topic->id) }}',
                    cat_ids: $("#cat_ids").val(),
                },
                success: function (data) {
                    $("#page_submit_btn").prop('disabled', false);
                    $("#customFields").html(data);
                    @if(Helper::GeneralWebmasterSettings("text_editor") ==1 && $WebmasterSection->type != 10)
                    initTinyMCE();
                    @endif
                }
            });
        }

        load_custom_fields();

    </script>
    @if($WebmasterSection->type != 10)
        <script src="{{ asset("assets/dashboard/js/dropzone/dist/min/dropzone.min.js") }}"></script>
        <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker-custom.js") }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const dropzoneElement = document.querySelector(".dropzone");

                if (dropzoneElement) {
                    const myDropzone = Dropzone.forElement(dropzoneElement);
                    if (myDropzone) {
                        myDropzone.on("success", function (file, response) {
                            $.get("{{ route('topicsPhotosList',["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}", function (data) {
                                $('#photos_list').html(data);
                            });
                        });
                        myDropzone.on("error", function (file, errorMessage) {
                            console.error("File upload error:", errorMessage);
                        });
                    } else {
                        console.error("No Dropzone instance found for the element!");
                    }
                } else {
                    console.error("Dropzone element not found!");
                }
            });
        </script>
    @endif
    <script>
        // Js Slug
        function slugify(string) {
            return string
                .toString()
                .trim()
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "")
                .replace(/\-\-+/g, "-")
                .replace(/^-+/, "")
                .replace(/-+$/, "");
        }

        @foreach(Helper::languagesList() as $ActiveLanguage)
        @if($ActiveLanguage->box_status)
        $("#seo_title_{{ @$ActiveLanguage->code }}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#title_in_engines_{{ @$ActiveLanguage->code }}").text($(this).val());
            } else {
                $("#title_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo $Topic->{'title_'.@$ActiveLanguage->code}; ?>");
            }
        });
        $("#seo_description_{{ @$ActiveLanguage->code }}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#desc_in_engines_{{ @$ActiveLanguage->code }}").text($(this).val());
            } else {
                $("#desc_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo Helper::GeneralSiteSettings("site_desc_".@$ActiveLanguage->code); ?>");
            }
        });
        $("#seo_url_slug_{{ @$ActiveLanguage->code }}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#url_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo url(''); ?>/" + slugify($(this).val()));
            } else {
                $("#url_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo Helper::topicURL($Topic->id,
                    @$ActiveLanguage->code, $Topic); ?>");
            }
        });
        @endif
        @endforeach

        function DeleteTopic(id) {
            $("#topic_delete_btn").attr("row-id", id);
            $("#delete-topic").modal("show");
        }

        $("#topic_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "GET",
                    url: "<?php echo route("topicsDestroy",
                        ["webmasterId" => $WebmasterSection->id]); ?>/" + row_id,
                    success: function (result) {
                        location.href = "{{ route('topics',$WebmasterSection->id) }}";
                    }
                });
            }
        });
    </script>
@endpush
