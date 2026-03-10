@extends('dashboard.layouts.master')
@section('title', __('backend.popups'))
@push("after-styles")
    <link rel="stylesheet"
          href="{{ asset('assets/dashboard/js/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}"
          type="text/css"/>
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.popupEdit') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('backend.popups') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("popups")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <?php
            $PopupSettings = [];
            if ($Popup->settings != "") {
                try {
                    $PopupSettings = json_decode($Popup->settings);
                } catch (Exception $e) {

                }
            }
            ?>
            <div>
                <form method="POST" action="{{ route('popupsUpdate') }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="popup_id" value="{{ $Popup->id }}">
                    <div class="nav-active-border b-info">
                        <ul class="nav nav-md">
                            <li class="nav-item inline">
                                <a class="nav-link active" data-toggle="tab" data-target="#tab_details">
                                    <span class="text-md"><i class="material-icons">&#xe055;</i> {{ __("backend.popupDetails") }}</span>
                                </a>
                            </li>
                            <li class="nav-item inline">
                                <a class="nav-link" data-toggle="tab" data-target="#tab_settings">
                                    <span class="text-md"><i class="material-icons">&#xe8a4;</i> {{ __("backend.popupSettings") }}</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content clear b-t">
                            <div class="tab-pane active" id="tab_details">
                                <div class="p-a-2">
                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                        @if($ActiveLanguage->box_status)
                                            <div class="form-group row">
                                                <label for="title_{{ @$ActiveLanguage->code }}" class="col-sm-2 form-control-label">{!!  __('backend.popupTitle') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                </label>
                                                <div class="col-sm-10">
                                                    <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="{{ @$Popup->{"title_".@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                        @if($ActiveLanguage->box_status)
                                            <div class="form-group row">
                                                <label
                                                    class="col-sm-2 form-control-label">{!!  __('backend.popupDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                </label>
                                                <div class="col-sm-10">
                                                    <div>
                                                        @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                                            <div>
                                                                <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control ckeditor" dir="{{ @$ActiveLanguage->direction }}">{{ @$Popup->{"details_".@$ActiveLanguage->code} }}</textarea>
                                                            </div>
                                                        @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                                            <div>
                                                                <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control tinymce" dir="{{ @$ActiveLanguage->direction }}">{{ @$Popup->{"details_".@$ActiveLanguage->code} }}</textarea>
                                                            </div>
                                                        @else
                                                            <div class="box p-a-xs">
                                                                <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control summernote summernote_{{ @$ActiveLanguage->code }}" dir="{{ @$ActiveLanguage->direction }}">{{ @$Popup->{"details_".@$ActiveLanguage->code} }}</textarea>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div class="form-group row">
                                        <label for="photo_file"
                                               class="col-sm-2 form-control-label">{!!  __('backend.backgroundPhoto') !!}</label>
                                        <div class="col-sm-10">
                                            @if($Popup->photo!="")
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div id="topic_photo" class="col-sm-4 box p-a-xs">
                                                            <a target="_blank"
                                                               href="{{ route("fileView",["path" =>'banners/'.$Popup->photo])}}"><img
                                                                    src="{{ route("fileView",["path" =>'banners/'.$Popup->photo]) }}"
                                                                    class="img-responsive">
                                                                {{ $Popup->photo }}
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

                                            <input type="file" name="photo" class="form-control" id="photo_file" accept="image/*">

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
                                    <div class="form-group row">
                                        <label for="link_status"
                                               class="col-sm-2 form-control-label">{!!  __('backend.pageCustomForm') !!}</label>
                                        <div class="col-sm-10">
                                            <select name="form_id" class="form-control c-select">
                                                <option value="">- - {!!  __('backend.none') !!} - -</option>
                                                <option value="-1" {{ (@$Popup->form_id == -1)?"selected":"" }}>{!!  __('backend.newsletterSubscribe') !!}</option>
                                                @foreach($GeneralWebmasterSections->where("type",6) as $FWebmasterSection)
                                                    <option
                                                        value="{{ $FWebmasterSection->id }}" {{ (@$Popup->form_id == $FWebmasterSection->id)?"selected":"" }}>{!!  $FWebmasterSection->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status1"
                                               class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label class="md-check">
                                                    <input type="radio" name="status" value="1" class="has-value" {{ ($Popup->status==1)?"checked":"" }} id="status1">
                                                    <i class="primary"></i>
                                                    {{ __('backend.active') }}
                                                </label>
                                                &nbsp; &nbsp;
                                                <label class="md-check">
                                                    <input type="radio" name="status" value="0" class="has-value" {{ ($Popup->status==0)?"checked":"" }} id="status2">
                                                    <i class="danger"></i>
                                                    {{ __('backend.notActive') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_settings">
                                <div class="p-a-2">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.showIn') !!} </label>
                                            <select name="show_in" class="form-control c-select">
                                                <option
                                                    value="0" {{ (@$Popup->show_in == 0)?"selected":"" }}>{!!  __('backend.allPages') !!}</option>
                                                <option
                                                    value="1" {{ (@$Popup->show_in == 1)?"selected":"" }}>{!!  __('backend.homePage') !!}</option>
                                                <option
                                                    value="2" {{ (@$Popup->show_in == 2)?"selected":"" }}>{!!  __('backend.specificPages') !!}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.showEvery') !!} </label>
                                            <select name="period" class="form-control c-select">
                                                @foreach(__("backend.showPeriods") as $k=>$v)
                                                    <option
                                                        value="{{ $k }}" {{ (@$PopupSettings->period == $k)?"selected":"" }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.closable') !!} </label>
                                            <select name="closable" class="form-control c-select">
                                                <option
                                                    value="1" {{ (@$PopupSettings->closable == 1)?"selected":"" }}>{!!  __('backend.yes') !!}</option>
                                                <option
                                                    value="0" {{ (@$PopupSettings->closable == 0)?"selected":"" }}>{!!  __('backend.no') !!}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.photoPosition') !!} </label>
                                            <select name="photo_position" class="form-control c-select">
                                                <option value="0" {{ (@$PopupSettings->photo_position == 0)?"selected":"" }}>{!!  __('backend.photoAsBackground') !!}</option>
                                                <option value="1" {{ (@$PopupSettings->photo_position == 1)?"selected":"" }}>{!!  __('backend.photoAsSideBanner') !!}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.backgroundColor') !!} </label>

                                            <div id="cp1" class="input-group colorpicker-component">
                                                <input type="text" autocomplete="off" name="background_color" id="style_color1" value="{{ @$PopupSettings->background_color }}" dir="ltr" class="form-control"/>
                                                <span class="input-group-addon" id="cpbg"><i></i></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.delay') !!} </label>
                                            <input type="number" min="0" name="delay" class="form-control"
                                                   value="{{ @$PopupSettings->delay }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.width') !!} ( px ) </label>
                                            <input type="number" min="200" name="width" class="form-control"
                                                   value="{{ @$PopupSettings->width }}">
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.height') !!} ( px ) ( 0 =
                                                auto
                                                )</label>
                                            <input type="number" min="0" name="height" class="form-control"
                                                   value="{{ @$PopupSettings->height }}">
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="form-control-label">{!!  __('backend.backdropOpacity') !!} ( 0 -
                                                100
                                                ) %</label>
                                            <input type="number" min="0" max="100" name="backdrop_opacity"
                                                   class="form-control"
                                                   value="{{ @$PopupSettings->backdrop_opacity }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label
                                                class="form-control-label">{!!  __('backend.customCode') !!}</label>
                                            <textarea name="code" class="form-control" rows="7" placeholder="<style>
...
</style>

<script>
...
</script>">{{ $Popup->code }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-x-2 p-b-2">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                        &#xe31b;</i> {!! __('backend.save') !!}</button>
                                <a href="{{route("popups")}}"
                                   class="btn btn-lg btn-default m-t"><i class="material-icons">
                                        &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push("after-scripts")

    <script src="{{ asset('assets/dashboard/js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(function () {
            let colors = {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#FF0000',
                'default': '#777777',
                'primary': '#337ab7',
                'success': '#5cb85c',
                'info': '#5bc0de',
                'warning': '#f0ad4e',
                'danger': '#d9534f'
            };
            $('#cp1').colorpicker({
                colorSelectors: colors
            });
            $('#cp2').colorpicker({
                colorSelectors: colors
            });
        });
    </script>

    @include('dashboard.layouts.editor')
@endpush
