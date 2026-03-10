@extends('dashboard.layouts.master')
<?php
$title_var = "title_".@Helper::currentLanguage()->code;
$title_var2 = "title_".config('smartend.default_language');
if ($WebmasterSection->$title_var != "") {
    $WebmasterSectionTitle = $WebmasterSection->$title_var;
} else {
    $WebmasterSectionTitle = $WebmasterSection->$title_var2;
}
$PhoneFieldsIds = [];
?>
@section('title', $WebmasterSectionTitle)
@section('content')
    <div class="padding">
        <div class="box">
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
                        &#xe02e;</i> {{ __('backend.topicNew') }} {!! $WebmasterSectionTitle !!}
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! $WebmasterSectionTitle !!}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{ route('topics',$WebmasterSection->id) }}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box nav-active-border b-primary lig">
                <ul class="nav nav-md light dk">
                    <li class="nav-item inline">
                        <a class="nav-link active" data-toggle="tab" data-target="#tab_details" href="#">
                        <span class="text-md"><i class="material-icons">
                                &#xe31e;</i> {{ ($WebmasterSection->type == 10)?__('backend.pageInfo'):__('backend.topicTabDetails') }}</span>
                        </a>
                    </li>

                    @if($WebmasterSection->multi_images_status)
                        <li class="nav-item inline">
                            <a class="p-x">
                    <span class="text-md text-muted"><i class="material-icons">
                            &#xe251;</i>
                        {{ __('backend.topicAdditionalPhotos') }}
                    </span>
                            </a>
                        </li>
                    @endif


                    @if($WebmasterSection->extra_attach_file_status)
                        <li class="nav-item inline">
                            <a class="p-x">
                    <span class="text-md text-muted"><i class="material-icons">
                            &#xe226;</i> {{ __('backend.additionalFiles') }}
                    </span>
                            </a>
                        </li>
                    @endif

                    @if($WebmasterSection->comments_status)
                        <li class="nav-item inline">
                            <a class="p-x">
                    <span class="text-md text-muted"><i class="material-icons">
                            &#xe0b9;</i> {{ __('backend.comments') }}
                    </span>
                            </a>
                        </li>
                    @endif


                    @if($WebmasterSection->maps_status)
                        <li class="nav-item inline">
                            <a class="p-x">
                    <span class="text-md text-muted"><i class="material-icons">
                            &#xe0c8;</i> {{ __('backend.topicGoogleMaps') }}
                    </span>
                            </a>
                        </li>
                    @endif

                    @if($WebmasterSection->related_status)
                        <li class="nav-item inline">
                            <a class="p-x">
                    <span class="text-md text-muted"><i class="material-icons">
                            &#xe867;</i> {{ __('backend.relatedTopics') }}
                    </span>
                            </a>
                        </li>
                    @endif
                    @if($WebmasterSection->seo_status)
                        <li class="nav-item inline">
                            <a class="p-x">
                    <span class="text-md text-muted"><i class="material-icons">
                            &#xe8e5;</i> {{ __('backend.seoTabTitle') }}</span>
                            </a>
                        </li>
                    @endif
                    @if($WebmasterSection->code_status)
                        <li class="nav-item inline">
                            <a class="p-x">
                    <span class="text-md text-muted"><i class="material-icons">
                            &#xe86f;</i> {{ __('backend.customCode') }}</span>
                            </a>
                        </li>
                    @endif

                </ul>
                <div class="box-body b-t p-a-2">
                    <form method="POST" action="{{ route("topicsStore",$WebmasterSection->id) }}" class="dashboard-form" enctype="multipart/form-data">
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
                                            <input type="text" name="date" id="date" class="form-control" value="{{ Helper::formatDate(date("Y-m-d")) }}" placeholder="" maxlength="191" autocomplete="off" required>
                                            <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @else
                            <input type="hidden" name="date" id="date" class="form-control" value="{{ Helper::formatDate(date("Y-m-d")) }}">
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
                                            <input type="text" name="expire_date" id="expire_date" class="form-control" value="" placeholder="" maxlength="191" autocomplete="off">
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
                                    <select name="section_id[]" id="cat_ids" class="form-control select2-multiple" multiple
                                            ui-jp="select2"
                                            ui-options="{theme: 'bootstrap'}" required onchange="load_custom_fields()">
                                            <?php
                                            $title_var = "title_".@Helper::currentLanguage()->code;
                                            $title_var2 = "title_".config('smartend.default_language');
                                            $t_arrow = "&raquo;";
                                            ?>
                                        @foreach ($fatherSections as $fatherSection)
                                                <?php
                                                if ($fatherSection->$title_var != "") {
                                                    $ftitle = $fatherSection->$title_var;
                                                } else {
                                                    $ftitle = $fatherSection->$title_var2;
                                                }
                                                ?>
                                            <option value="{{ $fatherSection->id  }}">{!! $ftitle !!}</option>
                                            @foreach ($fatherSection->fatherSections as $subFatherSection)
                                                    <?php
                                                    if ($subFatherSection->$title_var != "") {
                                                        $title = $subFatherSection->$title_var;
                                                    } else {
                                                        $title = $subFatherSection->$title_var2;
                                                    }
                                                    ?>
                                                <option
                                                    value="{{ $subFatherSection->id  }}">{!! $ftitle !!} {!! $t_arrow !!} {!! $title !!}</option>
                                                @foreach ($subFatherSection->fatherSections as $sub2FatherSection)
                                                        <?php
                                                        if ($sub2FatherSection->$title_var != "") {
                                                            $title2 = $sub2FatherSection->$title_var;
                                                        } else {
                                                            $title2 = $sub2FatherSection->$title_var2;
                                                        }
                                                        ?>
                                                    <option
                                                        value="{{ $sub2FatherSection->id  }}"> {!! $ftitle !!} {!! $t_arrow !!} {!! $title !!} {!! $t_arrow !!} {!! $title2 !!}</option>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="section_id" id="cat_ids" value="0" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                        @endif

                        @if($WebmasterSection->title_status)
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="form-group row">
                                        <label for="title_{{ @$ActiveLanguage->code }}" class="col-sm-2 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        @if($WebmasterSection->longtext_status)

                            @if($WebmasterSection->editor_status)
                                <div class="form-group row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <div class="alert alert-warning m-b-0">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <i class="fa fa-info-circle"></i> {!!  __('backend.savePageToUseDragAndDropEditor') !!}
                                            &nbsp;
                                            <button type="submit" class="btn btn-xs btn-warning"><i
                                                    class="fa fa-save"></i> {!! __('backend.save') !!}</button>
                                        </div>
                                    </div>
                                </div>
                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                    @if($ActiveLanguage->box_status)
                                        <div class="form-group row">
                                            <label for="details_{{ @$ActiveLanguage->code }}" class="col-sm-2 form-control-label">{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                            </label>
                                            <div class="col-sm-10">
                                                @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                                    <div>
                                                        <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control ckeditor" dir="{{ @$ActiveLanguage->direction }}"><div dir="{{ @$ActiveLanguage->direction }}"><br></div></textarea>
                                                    </div>
                                                @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                                    <div>
                                                        <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control tinymce" dir="{{ @$ActiveLanguage->direction }}"><div dir="{{ @$ActiveLanguage->direction }}"><br></div></textarea>
                                                    </div>
                                                @else
                                                    <div class="box p-a-xs">
                                                        <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control summernote summernote_{{ @$ActiveLanguage->code }}" dir="{{ @$ActiveLanguage->direction }}"><div dir="{{ @$ActiveLanguage->direction }}"><br></div></textarea>
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
                                                <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="5"></textarea>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endif

                        @if($WebmasterSection->type==2)
                            <div class="form-group row">
                                <label for="video_type"
                                       class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoType') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio m-t-sm">
                                        <label class="md-check">
                                            <input type="radio" name="video_type" value="0" class="has-value" checked id="video_type1" onclick="document.getElementById('youtube_link_div').style.display='none';document.getElementById('embed_link_div').style.display='none';document.getElementById('vimeo_link_div').style.display='none';document.getElementById('files_div').style.display='block';document.getElementById('youtube_link').value=''">
                                            <i class="primary"></i>
                                            {{ __('backend.bannerVideoType1') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="video_type" value="1" class="has-value" id="video_type2" onclick="document.getElementById('youtube_link_div').style.display='block';document.getElementById('embed_link_div').style.display='none';document.getElementById('vimeo_link_div').style.display='none';document.getElementById('files_div').style.display='none';document.getElementById('youtube_link').value=''">
                                            <i class="primary"></i>
                                            {{ __('backend.bannerVideoType2') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="video_type" value="2" class="has-value" id="video_type3" onclick="document.getElementById('youtube_link_div').style.display='none';document.getElementById('embed_link_div').style.display='none';document.getElementById('vimeo_link_div').style.display='block';document.getElementById('files_div').style.display='none';document.getElementById('vimeo_link').value=''">
                                            <i class="primary"></i>
                                            {{ __('backend.bannerVideoType3') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="video_type" value="3" class="has-value" id="video_type4" onclick="document.getElementById('youtube_link_div').style.display='none';document.getElementById('embed_link_div').style.display='block';document.getElementById('vimeo_link_div').style.display='none';document.getElementById('files_div').style.display='none';document.getElementById('embed_link').value=''">
                                            <i class="primary"></i>
                                            Embed
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="files_div">
                                <div class="form-group row">
                                    <label for="video_file" class="col-sm-2 form-control-label">{!!  __('backend.topicVideo') !!}</label>
                                    <div class="col-sm-10">
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
                            <div class="form-group row" id="youtube_link_div" style="display: none">
                                <label for="youtube_link"
                                       class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoUrl') !!}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="youtube_link" id="youtube_link" class="form-control" value="" placeholder="'https://www.youtube.com/watch?v=JQs4QyKnYMQ" maxlength="191" autocomplete="off" dir="ltr">
                                </div>
                            </div>
                            <div class="form-group row" id="vimeo_link_div" style="display: none">
                                <label for="vimeo_link" class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoUrl2') !!}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="vimeo_link" id="vimeo_link" class="form-control" value="" placeholder="'https://vimeo.com/131766159" maxlength="191" autocomplete="off" dir="ltr">
                                </div>
                            </div>
                            <div class="form-group row" id="embed_link_div" style="display: none">
                                <label for="embed_link" class="col-sm-2 form-control-label">Embed Code</label>
                                <div class="col-sm-10">
                                    <textarea name="embed_link" id="embed_link" class="form-control" dir="ltr" rows="3"></textarea>
                                </div>
                            </div>
                        @endif

                        @if($WebmasterSection->type==3)
                            <div class="form-group row">
                                <label for="audio_type1" class="col-sm-2 form-control-label">{!!  __('backend.audioType') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio m-t-sm">
                                        <label class="md-check">
                                            <input type="radio" name="video_type" value="0" class="has-value" checked id="audio_type1" onclick="document.getElementById('embed_audio_div').style.display='none';document.getElementById('audio_file_div').style.display='block';document.getElementById('embed_audio').value=''">
                                            <i class="primary"></i>
                                            {{ __('backend.localFile') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="video_type" value="3" class="has-value" checked id="audio_type3" onclick="document.getElementById('embed_audio_div').style.display='block';document.getElementById('audio_file_div').style.display='none';document.getElementById('embed_audio').value=''">
                                            <i class="primary"></i>
                                            Embed
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="audio_file_div">
                                <div class="form-group row">
                                    <label for="audio_file" class="col-sm-2 form-control-label">{!!  __('backend.topicAudio') !!}</label>
                                    <div class="col-sm-10">
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
                            <div id="embed_audio_div" style="display: none">
                                <div class="form-group row">
                                    <label for="embed_audio"
                                           class="col-sm-2 form-control-label">Embed Code</label>
                                    <div class="col-sm-10">
                                        <textarea name="embed_audio" id="embed_audio" class="form-control" dir="ltr" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($WebmasterSection->photo_status)
                            <div class="form-group row">
                                <label for="photo_file" class="col-sm-2 form-control-label">{!!  __('backend.topicPhoto') !!}</label>
                                <div class="col-sm-10">
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
                                            <i id="iconPreview" class=""></i>
                                        </span>
                                        <input type="text" class="form-control" autocomplete="off" name="icon" id="iconPicker" value="">
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
                                    <input type="file" name="attach_file" id="attach_file" class="form-control" accept="{{ ".".@str_replace(",",",.",@$allowed_file_types) }}">
                                </div>
                            </div>
                        @endif

                        <div id="customFields"></div>

                        @if($WebmasterSection->tags_status)
                            <div class="form-group row">
                                <label for="tags" class="col-sm-2 form-control-label">{!!  __('backend.tags') !!}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="tags" id="tags" class="form-control form-tags" value="" autocomplete="off">
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
                                <label for="page_form_id"
                                       class="col-sm-2 form-control-label">{!!  __('backend.pageCustomForm') !!}</label>
                                <div class="col-sm-10">
                                    <select name="page_form_id" class="form-control c-select" id="page_form_id">
                                        <option value="">- - {!!  __('backend.none') !!} - -</option>
                                        @foreach($GeneralWebmasterSections->where("type",6) as $FWebmasterSection)
                                            <option
                                                value="{{ $FWebmasterSection->id }}">{!!  $FWebmasterSection->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(Helper::GeneralWebmasterSettings("popups_status"))
                                <div class="form-group row">
                                    <label for="popup_id"
                                           class="col-sm-2 form-control-label">{!!  __('backend.customPopup') !!}</label>
                                    <div class="col-sm-10">
                                        <select name="popup_id" class="form-control c-select" id="popup_id">
                                            <option value="">- - {!!  __('backend.none') !!} - -</option>
                                            @foreach(\App\Models\Popup::where("status",1)->get() as $PPopup)
                                                <option
                                                    value="{{ $PPopup->id }}">{!!  $PPopup->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if($WebmasterSection->featured_status)
                            <div class="form-group row">
                                <label for="featured1" class="col-sm-2 form-control-label">{!!  __('backend.featured') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio m-t-sm">
                                        <label class="md-check">
                                            <input type="radio" name="featured" value="1" class="has-value" id="featured1">
                                            <i class="primary"></i>
                                            {{ __('backend.yes') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="featured" value="0" class="has-value" checked id="featured2">
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
                                            <input type="radio" name="hide_header" value="0" class="has-value" checked id="hide_header1">
                                            <i class="primary"></i>
                                            {{ __('backend.yes') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="hide_header" value="1" class="has-value" id="hide_header2">
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
                                            <input type="radio" name="hide_footer" value="0" class="has-value" checked id="hide_footer1">
                                            <i class="primary"></i>
                                            {{ __('backend.yes') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="hide_footer" value="1" class="has-value" id="hide_footer2">
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
                                    <label for="status1"
                                           class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="radio m-t-sm">

                                            <label class="md-check">
                                                <input type="radio" name="status" value="1" class="has-value" checked id="status1">
                                                <i class="primary"></i>
                                                {{ __('backend.active') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="status" value="0" class="has-value" id="status2">
                                                <i class="danger"></i>
                                                {{ __('backend.notActive') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="form-group row m-t-md">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                        &#xe31b;</i> {!! __('backend.continue') !!}</button>
                                <a href="{{ route('topics',$WebmasterSection->id) }}"
                                   class="btn btn-lg btn-default m-t"><i class="material-icons">
                                        &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
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
@push('before-styles')
    @if(count($PhoneFieldsIds) >0)
        <link rel="stylesheet"
              href="{{ URL::asset('assets/frontend/vendor/intl-tel-input/css/intlTelInput.min.css') }}?v={{ Helper::system_version() }}"/>
    @endif
@endpush
@push("after-scripts")
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker-custom.js") }}"></script>
    @if(count($PhoneFieldsIds) >0)
        <script
            src="{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/intlTelInput.min.js') }}?v={{ Helper::system_version() }}"></script>
    @endif
    @if(Helper::GeneralWebmasterSettings("text_editor") ==1)
        @include('dashboard.layouts.tinymce')
    @endif
    <script>
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
                    oldInputs: @json(old())
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

        @foreach($PhoneFieldsIds as $PhoneFieldId)
        var iti = window.intlTelInput(document.querySelector("#customField_{{ $PhoneFieldId }}"), {
            showSelectedDialCode: true,
            countrySearch: true,
            initialCountry: "auto",
            separateDialCode: true,
            hiddenInput: function () {
                return {
                    phone: "customField_{{ $PhoneFieldId }}_phone_full",
                    country: "customField_{{ $PhoneFieldId }}_country_code"
                };
            },
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "us";
                    callback(countryCode.toLowerCase());
                    iti.setCountry(countryCode.toLowerCase());
                });
            },
            utilsScript: "{{ URL::asset('assets/frontend/vendor/intl-tel-input/js/utils.js') }}?v={{ Helper::system_version() }}",
        });
        @endforeach
    </script>
@endpush
