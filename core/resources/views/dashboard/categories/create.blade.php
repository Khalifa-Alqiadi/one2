<?php
$title_var = "title_".@Helper::currentLanguage()->code;
$title_var2 = "title_".config('smartend.default_language');
if ($WebmasterSection->$title_var != "") {
    $WebmasterSectionTitle = $WebmasterSection->$title_var;
} else {
    $WebmasterSectionTitle = $WebmasterSection->$title_var2;
}
?>
@extends('dashboard.layouts.master')
@section('title', __('backend.sectionsOf')." ".$WebmasterSectionTitle)
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
                <h3><i class="material-icons">&#xe02e;</i> {{ __('backend.categoryNew') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! $WebmasterSectionTitle !!}</a> /
                    <a>{{ __('backend.sectionsOf') }}  {!! $WebmasterSectionTitle !!}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{ route('categories',$WebmasterSection->id) }}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body p-a-2">
                <form method="POST" action="{{ route('categoriesStore',$WebmasterSection->id) }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf

                    @if($WebmasterSection->sections_status==2)
                        <div class="form-group row">
                            <label for="father_id"
                                   class="col-sm-2 form-control-label">{!!  __('backend.categoryFather') !!} </label>
                            <div class="col-sm-10">
                                <select name="father_id" id="father_id" class="form-control c-select">
                                    <option value="0">- - {!!  __('backend.categoryNoFather') !!} - -</option>
                                        <?php
                                        $title_var = "title_".@Helper::currentLanguage()->code;
                                        $title_var2 = "title_".config('smartend.default_language');
                                        ?>
                                    @foreach ($fatherSections as $fatherSection)
                                            <?php
                                            if ($fatherSection->$title_var != "") {
                                                $title = $fatherSection->$title_var;
                                            } else {
                                                $title = $fatherSection->$title_var2;
                                            }
                                            ?>
                                        <option value="{{ $fatherSection->id  }}">{{ $title }}</option>
                                        @foreach ($fatherSection->fatherSections as $subSection)
                                                <?php
                                                if ($subSection->$title_var != "") {
                                                    $title = $subSection->$title_var;
                                                } else {
                                                    $title = $subSection->$title_var2;
                                                }
                                                ?>
                                            <option
                                                value="{{ $subSection->id  }}">
                                                &nbsp; {!! (@Helper::currentLanguage()->direction=="rtl")?"&#8617;":"&#8618;" !!} {{ $title }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    @else
                        <input type="hidden" name="father_id" value="0">
                    @endif

                    @foreach(Helper::languagesList() as $ActiveLanguage)
                        @if($ActiveLanguage->box_status)
                            <div class="form-group row">
                                <label for="title_{{ @$ActiveLanguage->code }}" class="col-sm-2 form-control-label">{!!  __('backend.categoryName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control">
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @foreach(Helper::languagesList() as $ActiveLanguage)
                        @if($ActiveLanguage->box_status)
                            <div class="form-group row">
                                <label id="details_{{ @$ActiveLanguage->code }}" class="col-sm-2 form-control-label">{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                </label>
                                <div class="col-sm-10">
                                    <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="3"></textarea>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="form-group row">
                        <label for="photo"
                               class="col-sm-2 form-control-label">{!!  __('backend.coverPhoto') !!}</label>
                        <div class="col-sm-10">
                            <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
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

                    @if($WebmasterSection->section_icon_status)
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

                    @if(Helper::GeneralWebmasterSettings("popups_status"))
                        <div class="form-group row">
                            <label for="link_status"
                                   class="col-sm-2 form-control-label">{!!  __('backend.customPopup') !!}</label>
                            <div class="col-sm-10">
                                <select name="popup_id" class="form-control c-select">
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
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.add') !!}</button>
                            <a href="{{ route('categories',$WebmasterSection->id) }}"
                               class="btn btn-lg btn-default m-t"><i class="material-icons">
                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>
                    </div>
                </form>
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
@push("after-scripts")
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker-custom.js") }}"></script>
@endpush
