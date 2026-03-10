<div class="tab-pane {{  ( Session::get('active_tab') == 'styleTab' || Session::get('active_tab') =="") ? 'active' : '' }}" id="tab-5">
    <div class="p-a-md"><h5><i class="material-icons">&#xe41d;</i>
            &nbsp; {!!  __('backend.styleSettings') !!}</h5></div>
    <div class="p-a-md col-md-12">

        <div class="form-group row">
            @foreach(Helper::languagesList() as $ActiveLanguage)
                <div class="col-sm-6 m-b-2">
                    <label>{!!  __('backend.siteLogo') !!}</label> {!! @Helper::languageName($ActiveLanguage) !!}
                    @if($Setting->{'style_logo_'.@$ActiveLanguage->code}!="")
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-12 box p-a-xs text-center">
                                    <a target="_blank"
                                       href="{{ route("fileView",["path" =>'settings/'.$Setting->{'style_logo_'.@$ActiveLanguage->code}]) }}"><img
                                            src="{{ route("fileView",["path" =>'settings/'.$Setting->{'style_logo_'.@$ActiveLanguage->code}]) }}"
                                            class="img-responsive" id="style_logo_{{ @$ActiveLanguage->code }}_prv"
                                            style="width: auto;max-width: 260px;max-height: 60px">
                                        <br>
                                        <small>{{ $Setting->{'style_logo_'.@$ActiveLanguage->code} }}</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-12 box p-a-xs text-center">
                                    <img
                                        src="{{ route("fileView",["path" =>'settings/nologo.png']) }}"
                                        class="img-responsive" id="style_logo_{{ @$ActiveLanguage->code }}_prv"
                                        style="width: auto;max-width: 260px;max-height: 60px">
                                    <br>
                                    <small>nologo.png</small>

                                </div>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="style_logo_{{ @$ActiveLanguage->code }}" id="style_logo_{{ @$ActiveLanguage->code }}" class="form-control" accept="accept'=>'image/*">
                    <small>
                        <i class="material-icons">&#xe8fd;</i>( 260x60 px ) -
                        {!!  __('backend.imagesTypes') !!}
                    </small>
                </div>
            @endforeach
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="style_fav">{!!  __('backend.favicon') !!}</label>
                @if($Setting->style_fav!="")
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 box p-a-xs text-center">
                                <a target="_blank"
                                   href="{{ route("fileView",["path" =>'settings/'.$Setting->style_fav]) }}"><img
                                        src="{{ route("fileView",["path" =>'settings/'.$Setting->style_fav]) }}"
                                        class="img-responsive" id="style_fav_prv"
                                        style="max-width: 60px;height: 60px">
                                    <br>
                                    <small>{{ $Setting->style_fav }}</small>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 box p-a-xs text-center">
                                <a target="_blank"
                                   href="{{ route("fileView",["path" =>'settings/nofav.png']) }}"><img
                                        src="{{ route("fileView",["path" =>'settings/nofav.png']) }}"
                                        class="img-responsive" id="style_fav_prv"
                                        style="max-width: 60px;height: 60px">
                                    <br>
                                    <small>nofav.png</small>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                <input type="file" name="style_fav" id="style_fav" class="form-control" accept="accept'=>'image/*">
                <small>
                    <i class="material-icons">&#xe8fd;</i> ( 32x32 px ) -
                    {!!  __('backend.imagesTypes') !!}
                </small>
            </div>
            <div class="col-sm-6">
                <label for="style_apple">{!!  __('backend.appleIcon') !!}</label>
                @if($Setting->style_apple!="")
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 box p-a-xs text-center">
                                <a target="_blank"
                                   href="{{ route("fileView",["path" =>'settings/'.$Setting->style_apple]) }}"><img
                                        src="{{ route("fileView",["path" =>'settings/'.$Setting->style_apple]) }}"
                                        class="img-responsive" id="style_apple_prv"
                                        style="width: 60px;height: 60px">
                                    <br>
                                    <small>{{ $Setting->style_apple }}</small>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 box p-a-xs text-center">
                                <a target="_blank"
                                   href="{{ route("fileView",["path" =>'settings/nofav.png']) }}"><img
                                        src="{{ route("fileView",["path" =>'settings/nofav.png']) }}"
                                        class="img-responsive" id="style_apple_prv"
                                        style="max-width: 60px;height: 60px">
                                    <br>
                                    <small>nofav.png</small>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                <input type="file" name="style_apple" id="style_apple" class="form-control" accept="accept'=>'image/*">
                <small>
                    <i class="material-icons">&#xe8fd;</i> ( 180x180 px ) -
                    {!!  __('backend.imagesTypes') !!}
                </small>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <h5 class="m-t m-b">{!!  __('backend.setThemeColors') !!}</h5>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="style_color1">{!!  __('backend.styleColor1') !!}</label>

                        <div>
                            <div id="cp1" class="input-group colorpicker-component">
                                <input type="text" autocomplete="off" name="style_color1" id="style_color1" value="{{ $Setting->style_color1 }}" dir="ltr" class="form-control"/>
                                <span class="input-group-addon" id="cpbg"><i></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="style_color2">{!!  __('backend.styleColor2') !!}</label>

                        <div>
                            <div id="cp2" class="input-group colorpicker-component">
                                <input type="text" autocomplete="off" name="style_color2" id="style_color2" value="{{ $Setting->style_color2 }}" dir="ltr" class="form-control"/>
                                <span class="input-group-addon" id="cpbg2"><i></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="style_color3">{!!  __('backend.styleColor3') !!}</label>

                        <div>
                            <div id="cp3" class="input-group colorpicker-component">
                                <input type="text" autocomplete="off" name="style_color3" id="style_color3" value="{{ $Setting->style_color3 }}" dir="ltr" class="form-control"/>
                                <span class="input-group-addon" id="cpbg3"><i></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="style_color4">{!!  __('backend.styleColor4') !!}</label>
                        <div>
                            <div id="cp4" class="input-group colorpicker-component">
                                <input type="text" autocomplete="off" name="style_color4" id="style_color4" value="{{ $Setting->style_color4 }}" dir="ltr" class="form-control"/>
                                <span class="input-group-addon" id="cpbg4"><i></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <h5 class="m-t m-b">{!!  __('backend.orSelectFromPredefined') !!}</h5>
                <label>&nbsp;</label>
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        $themes = [
                            ["#0cbaa4", "#2e3e4e", "#edf3f2", "#dfeae8"],
                            ["#84ba3f", "#45631d", "#f2f3ed", "#e8eadf"],
                            ["#00b106", "#14700c", "#edf3ed", "#dfeae0"],
                            ["#1977cc", "#054a8a", "#f1f7fd", "#d5dbe0"],
                            ["#1c46ff", "#101e52", "#f1f4fd", "#d5d7e0"],
                            ["#6a10fd", "#351073", "#efedf3", "#e4dfea"],
                            ["#ff796c", "#5f221d", "#f3eeed", "#eae2df"],
                            ["#f41e54", "#481a2a", "#f3edef", "#eadfe4"],
                            ["#d12326", "#222222", "#f3eded", "#eadfdf"],
                            ["#ba893f", "#664412", "#f3f0ed", "#eae4df"],
                            ["#f57703", "#000000", "#f3f0ed", "#eae5df"],
                            ["#f7c605", "#3b3b3a", "#f3f2ed", "#eae8df"],
                        ]
                        ?>
                        @foreach($themes as $theme_colors)
                            <button type="button" class="btn white dk m-r-sm m-b-sm" style="letter-spacing: -4px"
                                    onclick="SetThemeColors('{{ @$theme_colors[0] }}','{{ @$theme_colors[1] }}','{{ @$theme_colors[2] }}','{{ @$theme_colors[3] }}')">
                                <span class="inline"
                                      style="background: {{ @$theme_colors[1] }};width: 22px">&nbsp;</span>
                                <span class="inline"
                                      style="background:{{ @$theme_colors[0] }};width: 22px">&nbsp;</span>
                                <span class="inline"
                                      style="background: {{ @$theme_colors[2] }};width: 22px">&nbsp;</span>
                                <span class="inline"
                                      style="background: {{ @$theme_colors[3] }};width: 22px">&nbsp;</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t">
            <div class="col-sm-6">
                <label for="style_type">{{ __('backend.appearance') }} : </label>
                <div class="radio">
                    <label class="md-check">
                        <input type="radio" name="style_type" value="0" class="has-value" {{ ($Setting->style_type==0)?"checked":"" }} id="style_type0">
                        <i class="primary"></i>
                        {{ __('backend.themes1') }}
                    </label>
                    &nbsp; &nbsp;
                    <label class="md-check">
                        <input type="radio" name="style_type" value="1" class="has-value" {{ ($Setting->style_type==1)?"checked":"" }} id="style_type1">
                        <i class="danger"></i>
                        {{ __('backend.themes3') }}
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                <label for="style_change0">{{ __('backend.allowAppearanceChange') }} : </label>
                <div class="radio">
                    <label class="md-check">
                        <input type="radio" name="style_change" value="1" class="has-value" {{ ($Setting->style_change==1)?"checked":"" }} id="style_change0">
                        <i class="primary"></i>
                        {{ __('backend.yes') }}
                    </label>
                    &nbsp; &nbsp;
                    <label class="md-check">
                        <input type="radio" name="style_change" value="0" class="has-value" {{ ($Setting->style_change==0)?"checked":"" }} id="style_change1">
                        <i class="danger"></i>
                        {{ __('backend.no') }}
                    </label>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="style_header1">{{ __('backend.fixedHeader') }} : </label>
                    <div class="radio">
                        <label class="md-check"
                               onclick="document.getElementById('transparentHeaderDiv').style.display='block'">
                            <input type="radio" name="style_header" value="1" class="has-value" {{ ($Setting->style_header==1)?"checked":"" }} id="style_header1">
                            <i class="primary"></i>
                            {{ __('backend.yes') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check"
                               onclick="document.getElementById('transparentHeaderDiv').style.display='none'">
                            <input type="radio" name="style_header" value="0" class="has-value" {{ ($Setting->style_header==0)?"checked":"" }} id="style_header2">
                            <i class="danger"></i>
                            {{ __('backend.no') }}
                        </label>
                    </div>
                </div>
                <div class="col-sm-6  {{ ($Setting->style_header)?"":"displayNone" }}" id="transparentHeaderDiv">
                    <label for="style_bg_type1">{{ __('backend.transparentHeader') }} : </label>
                    <div class="radio">
                        <label class="md-check">
                            <input type="radio" name="style_bg_type" value="1" class="has-value" {{ ($Setting->style_bg_type==1)?"checked":"" }} id="style_bg_type1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                        &nbsp; &nbsp;
                        <label class="md-check">
                            <input type="radio" name="style_bg_type" value="0" class="has-value" {{ ($Setting->style_bg_type==0)?"checked":"" }} id="style_bg_type2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="style_preload1">{{ __('backend.preLoad') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="style_preload" value="1" class="has-value" {{ ($Setting->style_preload==1)?"checked":"" }} id="style_preload1">
                    <i class="primary"></i>
                    {{ __('backend.active') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="style_preload" value="0" class="has-value" {{ ($Setting->style_preload==0)?"checked":"" }} id="style_preload2">
                    <i class="danger"></i>
                    {{ __('backend.notActive') }}
                </label>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="style_footer1">{{ __('backend.footerStyle') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="style_footer" value="1" class="has-value" {{ ($Setting->style_footer==1)?"checked":"" }} id="style_footer1">
                    <i class="primary"></i>
                    {{ __('backend.footerStyle') }} #1
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="style_footer" value="2" class="has-value" {{ ($Setting->style_footer==2)?"checked":"" }} id="style_footer2">
                    <i class="danger"></i>
                    {{ __('backend.footerStyle') }} #2
                </label>
            </div>
            <br>
            <label>{{ __('backend.footerStyleBg') }} : </label>
            <div class="row">
                <div class="col-sm-6">
                    @if($Setting->style_footer_bg!="")
                        <div>
                            <div>
                                <div id="footer_bg" class="col-sm-8 box p-a-xs">
                                    <a target="_blank"
                                       href="{{ route("fileView",["path" =>'settings/'.$Setting->style_footer_bg]) }}"><img
                                            src="{{ route("fileView",["path" =>'settings/'.$Setting->style_footer_bg]) }}"
                                            class="img-responsive">
                                        {{ $Setting->style_footer_bg }}
                                    </a>
                                    <br>
                                    <a onclick="document.getElementById('footer_bg').style.display='none';document.getElementById('photo_delete').value='1';document.getElementById('undo').style.display='block';"
                                       class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                </div>
                                <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                    <a onclick="document.getElementById('footer_bg').style.display='block';document.getElementById('photo_delete').value='0';document.getElementById('undo').style.display='none';">
                                        <i class="material-icons">
                                            &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                </div>
                                <input type="hidden" name="photo_delete" id="photo_delete" value="0">
                            </div>
                        </div>

                    @endif
                    <input type="file" name="style_footer_bg" id="style_footer_bg" class="form-control" accept="image/*">
                    <small>
                        <i class="material-icons">&#xe8fd;</i>( 260x60 px ) -
                        {!!  __('backend.imagesTypes') !!}
                    </small>
                </div>
            </div>

        </div>
        <hr>
        <div class="form-group">
            <label for="style_subscribe1">{{ __('backend.newsletterSubscribe') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="style_subscribe" value="1" class="has-value" {{ ($Setting->style_subscribe==1)?"checked":"" }} id="style_subscribe1">
                    <i class="primary"></i>
                    {{ __('backend.active') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="style_subscribe" value="0" class="has-value" {{ ($Setting->style_subscribe==0)?"checked":"" }} id="style_subscribe2">
                    <i class="danger"></i>
                    {{ __('backend.notActive') }}
                </label>
            </div>
        </div>
    </div>
</div>
