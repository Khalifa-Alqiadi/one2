@extends('dashboard.layouts.master')
@section('title', __('backend.adsBanners'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.bannerEdit') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('backend.adsBanners') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("Banners")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body">
                <form method="POST" action="{{ route('BannersUpdate',$Banners->id) }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="section_id" value="{{ $Banners->section_id }}">

                    @foreach(Helper::languagesList() as $ActiveLanguage)
                        @if($ActiveLanguage->box_status)
                            <div class="form-group row">
                                <label
                                    class="col-sm-2 form-control-label">{!!  __('backend.bannerTitle') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" value="{{ $Banners->{'title_'.@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if($WebmasterBanner->type==2)
                        <div class="form-group row">
                            <label for="video_type1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoType') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="video_type" class="has-value" value="0" id="video_type1" {{ ($Banners->video_type==0)?"checked":"" }} onclick="document.getElementById('youtube_link_div').style.display='none';document.getElementById('vimeo_link_div').style.display='none';document.getElementById('files_div').style.display='block';document.getElementById('youtube_link').value=''">
                                        <i class="primary"></i>
                                        {{ __('backend.bannerVideoType1') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="video_type" class="has-value" value="1" id="video_type2" {{ ($Banners->video_type==1)?"checked":"" }} onclick="document.getElementById('vimeo_link_div').style.display='none';document.getElementById('youtube_link_div').style.display='block';document.getElementById('files_div').style.display='block';document.getElementById('youtube_link').value=''">
                                        <i class="primary"></i>
                                        {{ __('backend.bannerVideoType2') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="video_type" class="has-value" value="2" id="video_type2" {{ ($Banners->video_type==2)?"checked":"" }} onclick="document.getElementById('vimeo_link_div').style.display='block';document.getElementById('youtube_link_div').style.display='none';document.getElementById('files_div').style.display='block';document.getElementById('vimeo_link').value=''">
                                        <i class="primary"></i>
                                        {{ __('backend.bannerVideoType3') }}
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row" id="youtube_link_div"
                             style="display: {{ ($Banners->video_type==1) ? "block" : "none" }}">
                            <label for="youtube_link"
                                   class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoUrl') !!}</label>
                            <div class="col-sm-10">
                                <input type="text" autocomplete="off" name="youtube_link" id="youtube_link" value="{{ $Banners->youtube_link }}" placeholder="https://www.youtube.com/watch?v=JQs4QyKnYMQ" class="form-control" dir="ltr"/>
                            </div>
                        </div>

                        <div class="form-group row" id="vimeo_link_div"
                             style="display: {{ ($Banners->video_type==2) ? "block" : "none" }}">
                            <label for="vimeo_link"
                                   class="col-sm-2 form-control-label">{!!  __('backend.bannerVideoUrl2') !!}</label>
                            <div class="col-sm-10">
                                <input type="text" autocomplete="off" name="vimeo_link" id="vimeo_link" value="{{ $Banners->youtube_link }}" placeholder="https://vimeo.com/131766159" class="form-control" dir="ltr"/>
                            </div>
                        </div>
                    @endif


                    @if($WebmasterBanner->type!=0)
                        @if($WebmasterBanner->type==1)
                                <?php
                                $ttile = "bannerPhoto";
                                $file_name = "file_";
                                $file_allow = "image/*";
                                ?>
                        @else
                                <?php
                                $ttile = "topicVideo";
                                $file_name = "file2_";
                                $file_allow = "*'";
                                ?>
                        @endif
                        <div id="files_div" style="display: {{ ($Banners->video_type == 0) ? "block" : "none" }}">
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 form-control-label">{!!  __('backend.'.$ttile) !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        </label>
                                        <div class="col-sm-10">
                                            @if($Banners->{"file_".$ActiveLanguage->code}!="")
                                                @if($WebmasterBanner->type==1)
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="col-sm-4 box p-a-xs">
                                                                <a target="_blank"
                                                                   href="{{ route("fileView",["path" =>'banners/'.$Banners->{"file_".$ActiveLanguage->code} ]) }}"><img
                                                                        src="{{ route("fileView",["path" =>'banners/'.$Banners->{"file_".$ActiveLanguage->code} ]) }}"
                                                                        class="img-responsive">
                                                                    {{ $Banners->{"file_".$ActiveLanguage->code} }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="box p-a-xs">

                                                        <video width="380" height="230" controls>
                                                            <source src="{{ route("fileView",["path" =>'banners/'.$Banners->{"file_".$ActiveLanguage->code} ]) }}"
                                                                    type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <br>
                                                        <a target="_blank"
                                                           href="{{ route("fileView",["path" =>'banners/'.$Banners->{"file_".$ActiveLanguage->code} ]) }}">
                                                            {!!  $Banners->{"file_".$ActiveLanguage->code} !!} </a>
                                                    </div>
                                                @endif
                                            @endif
                                            <input type="file" name="{{ $file_name.$ActiveLanguage->code }}" class="form-control" accept="{{ $file_allow }}">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="form-group row m-t-md" style="margin-top: 0 !important;">
                                <div class="offset-sm-2 col-sm-10">
                                    <small>
                                        <i class="material-icons">&#xe8fd;</i>
                                        @if($WebmasterBanner->type==1)
                                            {!!  __('backend.imagesTypes') !!}
                                        @else
                                            {!!  __('backend.videoTypes') !!}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($WebmasterBanner->desc_status)
                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            @if($ActiveLanguage->box_status)
                                <div class="form-group row">
                                    <label for="details_ar"
                                           class="col-sm-2 form-control-label">{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea name="details_{{ @$ActiveLanguage->code }}" id="contact_message" class="form-control" placeholder="" rows="3" dir="{{ @$ActiveLanguage->direction }}">{{ $Banners->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @if($WebmasterBanner->link_status)
                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            @if($ActiveLanguage->box_status)
                                <div class="form-group row">
                                    <label for="link_{{ @$ActiveLanguage->code }}"
                                           class="col-sm-2 form-control-label">{!!  __('backend.bannerLinkUrl') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" autocomplete="off" name="link_{{ @$ActiveLanguage->code }}" id="link_{{ @$ActiveLanguage->code }}" value="{{ $Banners->{'link_'.@$ActiveLanguage->code} }}" placeholder="http://www.site.com" maxlength="191" class="form-control" dir="ltr"/>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @if($WebmasterBanner->type==0)
                        <div class="form-group row">
                            <label for="code"
                                   class="col-sm-2 form-control-label">{!!  __('backend.bannerCode') !!}</label>
                            <div class="col-sm-10">
                                <textarea name="code" id="code" class="form-control" placeholder="" rows="3" dir="ltr">{{ $Banners->code }}</textarea>
                            </div>
                        </div>
                    @endif


                    @if($WebmasterBanner->icon_status)
                        <div class="form-group row">
                            <label for="icon"
                                   class="col-sm-2 form-control-label">{!!  __('backend.sectionIcon') !!}</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i id="iconPreview" class="{{ $Banners->icon }}"></i>
                                        </span>
                                    <input type="text" class="form-control" autocomplete="off" name="icon" id="iconPicker" value="{{ $Banners->icon }}">
                                    <span class="input-group-btn">
            <button class="btn white" type="button" id="iconPickerButton">{!!  __('backend.chooseIcon') !!}</button>
          </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="status1"
                               class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="md-check">
                                    <input type="radio" name="status" value="1" class="has-value" {{ ($Banners->status==1)?"checked":"" }} id="status1">
                                    <i class="primary"></i>
                                    {{ __('backend.active') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="status" value="0" class="has-value" {{ ($Banners->status==0)?"checked":"" }} id="status2">
                                    <i class="danger"></i>
                                    {{ __('backend.notActive') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.update') !!}</button>
                            <a href="{{route("Banners")}}"
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
