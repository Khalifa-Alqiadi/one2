@extends('dashboard.layouts.master')
@section('title', __('backend.siteMenus'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('backend.newLink') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('backend.settings') }}</a> /
                    <a href="">{{ __('backend.siteMenus') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{ route("menus",["ParentMenuId"=>$ParentMenuId]) }}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body">
                <form method="POST" action="{{ route("menusStore",$ParentMenuId) }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ParentMenuId" value="{{ $ParentMenuId }}">

                    <div class="form-group row">
                        <label for="father_id"
                               class="col-sm-3 form-control-label">{!!  __('backend.fatherSection') !!} </label>
                        <div class="col-sm-9">
                            <select name="father_id" id="father_id" class="form-control c-select">
                                <option value="{{$ParentMenuId}}">- - {!!  __('backend.sectionNoFather') !!} - -
                                </option>
                                <?php
                                $title_var = "title_".@Helper::currentLanguage()->code;
                                $title_var2 = "title_".config('smartend.default_language');
                                ?>
                                @foreach ($FatherMenus as $FatherMenu)
                                        <?php
                                        if ($FatherMenu->$title_var != "") {
                                            $title = $FatherMenu->$title_var;
                                        } else {
                                            $title = $FatherMenu->$title_var2;
                                        }
                                        ?>
                                    <option value="{{ $FatherMenu->id  }}">{{ $title }}</option>
                                    @foreach ($FatherMenu->subMenus as $FatherMenu2)
                                            <?php
                                            if ($FatherMenu2->$title_var != "") {
                                                $title = $FatherMenu2->$title_var;
                                            } else {
                                                $title = $FatherMenu2->$title_var2;
                                            }
                                            ?>
                                        <option value="{{ $FatherMenu2->id  }}">
                                            &nbsp; {!! (@Helper::currentLanguage()->direction=="rtl")?"&#8617;":"&#8618;" !!} {{ $title }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                    </div>
                    @foreach(Helper::languagesList() as $ActiveLanguage)
                        @if($ActiveLanguage->box_status)
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label" for="title_{{ @$ActiveLanguage->code }}">{!!  __('backend.sectionTitle') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" class="form-control" dir="{{ @$ActiveLanguage->direction }}"/>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="form-group row">
                        <label for="type1"
                               class="col-sm-3 form-control-label">{!!  __('backend.linkType') !!}</label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label class="md-check">
                                    <input type="radio" name="type" value="0" class="has-value" id="type1" onclick="document.getElementById('link_div').style.display='block';document.getElementById('cat_div').style.display='none'">
                                    <i class="primary"></i>
                                    {{ __('backend.linkType1') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="type" value="1" class="has-value" checked id="type2" onclick="document.getElementById('link_div').style.display='block';document.getElementById('cat_div').style.display='none'">
                                    <i class="danger"></i>
                                    {{ __('backend.linkType2') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="type" value="2" class="has-value"  id="type3" onclick="document.getElementById('link_div').style.display='none';document.getElementById('cat_div').style.display='block'">
                                    <i class="info"></i>
                                    {{ __('backend.linkType3') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="type" value="3" class="has-value" id="type4" onclick="document.getElementById('link_div').style.display='none';document.getElementById('cat_div').style.display='block'">
                                    <i class="warn"></i>
                                    {{ __('backend.linkType4') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="link_div">
                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            @if($ActiveLanguage->box_status)
                                <div class="form-group row">
                                    <label for="link_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.linkURL') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="link_{{ @$ActiveLanguage->code }}" id="link_{{ @$ActiveLanguage->code }}" value="" class="form-control" dir="ltr" autocomplete="off">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div id="cat_div" class="form-group row" style="display: none">
                        <label for="link"
                               class="col-sm-3 form-control-label">{!!  __('backend.linkSection') !!}
                        </label>
                        <div class="col-sm-9">
                            <select name="cat_id" id="cat_id" class="form-control c-select">
                                <option value="{{$ParentMenuId}}">- - {!!  __('backend.linkSection') !!} - -
                                </option>
                                <?php
                                $title_var = "title_".@Helper::currentLanguage()->code;
                                $title_var2 = "title_".config('smartend.default_language');
                                ?>
                                @foreach ($GeneralWebmasterSections as $WSection)
                                    @if($WSection->type !=4)
                                            <?php
                                            if ($WSection->$title_var != "") {
                                                $WSectionTitle = $WSection->$title_var;
                                            } else {
                                                $WSectionTitle = $WSection->$title_var2;
                                            }
                                            ?>
                                        <option
                                            value="{{ $WSection->id  }}">{!!  $WSectionTitle !!}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="target1"
                               class="col-sm-3 form-control-label">{!!  __('backend.target') !!}</label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label class="md-check">
                                    <input type="radio" name="target" value="0" class="has-value" checked id="target1">
                                    <i class="primary"></i>
                                    {{ __('backend.targetIn') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="target" value="1" class="has-value" id="target2">
                                    <i class="danger"></i>
                                    {{ __('backend.targetOut') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="icon"
                               class="col-sm-3 form-control-label">{!!  __('backend.sectionIcon') !!}</label>
                        <div class="col-sm-9">
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

                    <div class="form-group row m-t-md">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.add') !!}</button>
                            <a href="{{ route("menus",["ParentMenuId"=>$ParentMenuId]) }}"
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
