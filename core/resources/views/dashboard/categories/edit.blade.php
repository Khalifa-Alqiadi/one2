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
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.categoryEdit') }}</h3>
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

        </div>

        <?php
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "seo") {
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
            }
            if (Session::get('activeTab') == "code") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "active";
            }
        }
        ?>
        <div class="box nav-active-border b-info">
            <ul class="nav nav-md">
                <li class="nav-item inline">
                    <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details">
                        <span class="text-md"><i class="material-icons">
                                &#xe31e;</i> {{ __('backend.topicTabCategory') }}</span>
                    </a>
                </li>
                @if($WebmasterSection->seo_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_2 }}" data-toggle="tab" data-target="#tab_seo">
                    <span class="text-md"><i class="material-icons">
                            &#xe8e5;</i> {{ __('backend.seoTabTitle') }}</span>
                        </a>
                    </li>
                @endif
                @if($WebmasterSection->code_status)
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_3 }}" data-toggle="tab" data-target="#tab_code">
                    <span class="text-md"><i class="material-icons">
                            &#xe86f;</i> {{ __('backend.customCode') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
            <div class="tab-content clear b-t">
                <div class="tab-pane  {{ $tab_1 }}" id="tab_details">
                    <div class="box-body p-a-2">
                        <form method="POST" action="{{ route("categoriesUpdate",["webmasterId"=>$WebmasterSection->id,"id"=>$Sections->id]) }}" class="dashboard-form" enctype="multipart/form-data">
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
                                                <option
                                                    value="{{ $fatherSection->id  }}" {{ ($fatherSection->id == $Sections->father_id) ? "selected='selected'":""  }}>{{ $title }}</option>
                                                @foreach ($fatherSection->fatherSections as $subSection)
                                                        <?php
                                                        if ($subSection->$title_var != "") {
                                                            $title = $subSection->$title_var;
                                                        } else {
                                                            $title = $subSection->$title_var2;
                                                        }
                                                        ?>
                                                    <option
                                                        value="{{ $subSection->id  }}" {{ ($subSection->id == $Sections->father_id) ? "selected='selected'":""  }}>
                                                        &nbsp; {!! (@Helper::currentLanguage()->direction=="rtl")?"&#8617;":"&#8618;" !!} {{ $title }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            @else
                                <input type="hidden" name="father_id" value="{{ $Sections->father_id }}">
                            @endif

                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="form-group row">
                                        <label for="title_{{ @$ActiveLanguage->code }}"
                                               class="col-sm-2 form-control-label">{!!  __('backend.categoryName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="{{ $Sections->{'title_'.@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="form-group row">
                                        <label for="details_{{ @$ActiveLanguage->code }}" class="col-sm-2 form-control-label">{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        </label>
                                        <div class="col-sm-10">
                                            <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control" placeholder="" dir="{{ @$ActiveLanguage->direction }}">{{ $Sections->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="form-group row">
                                <label for="photo"
                                       class="col-sm-2 form-control-label">{!!  __('backend.coverPhoto') !!}</label>
                                <div class="col-sm-10">
                                    @if($Sections->photo!="")
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="section_photo" class="col-sm-4 box p-a-xs">
                                                    <a target="_blank"
                                                       href="{{ route("fileView",["path" =>'sections/'.$Sections->photo ]) }}"><img
                                                            src="{{ route("fileView",["path" =>'sections/'.$Sections->photo ]) }}"
                                                            class="img-responsive">
                                                        {{ $Sections->photo }}
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
                                                <input type="hidden" name="photo_delete" value="0" id="photo_delete">
                                            </div>
                                        </div>
                                    @endif
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

                            <div class="form-group row">
                                <label for="status1"
                                       class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check">
                                            <input type="radio" name="status" value="1" class="has-value" {{ ($Sections->status==1)?"checked":"" }} id="status1">
                                            <i class="primary"></i>
                                            {{ __('backend.active') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="status" value="0" class="has-value" {{ ($Sections->status==0)?"checked":"" }} id="status2">
                                            <i class="danger"></i>
                                            {{ __('backend.notActive') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @if($WebmasterSection->section_icon_status)
                                <div class="form-group row">
                                    <label for="icon"
                                           class="col-sm-2 form-control-label">{!!  __('backend.sectionIcon') !!}</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <i id="iconPreview" class="{{ $Sections->icon }}"></i>
                                        </span>
                                            <input type="text" class="form-control" autocomplete="off" name="icon" id="iconPicker" value="{{ $Sections->icon }}">
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
                                                    value="{{ $PPopup->id }}" {{ ($PPopup->id == $Sections->popup_id) ? "selected='selected'":""  }}>{!!  $PPopup->{"title_".@Helper::currentLanguage()->code} !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row m-t-md">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                                    <a href="{{ route('categories',$WebmasterSection->id) }}"
                                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                @include('dashboard.categories.seo')
                @include('dashboard.categories.code')
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
                $("#title_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo $Sections->{'title_'.@$ActiveLanguage->code}; ?>");
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
                $("#url_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo Helper::categoryURL($Sections->id,
                    @$ActiveLanguage->code, $Sections); ?>");
            }
        });
        @endif
        @endforeach
    </script>
@endpush
