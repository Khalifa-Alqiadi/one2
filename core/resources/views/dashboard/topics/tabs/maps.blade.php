@if($WebmasterSection->maps_status)
    <div class="tab-pane  {{ $tab_5 }}" id="tab_maps">

        <div class="box-body p-a-2">

            <div class="row">
                @if (Session::has('mapST'))

                    @if (Session::get('mapST') == "edit")
                        <div class="offset-sm-2 col-sm-8">
                            <br>
                            <form method="POST" action="{{ route("topicsMapsUpdate",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id,"map_id"=>Session::get('Map')->id]) }}" class="dashboard-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group row">
                                    <label for="longitude" class="col-sm-3 form-control-label">{!!  __('backend.topicMapLocation') !!}
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" name="longitude" id="longitude" class="form-control" value="{{ Session::get('Map')->longitude }}" placeholder="" maxlength="191" autocomplete="off" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="latitude" id="latitude" class="form-control" value="{{ Session::get('Map')->latitude }}" placeholder="" maxlength="191" autocomplete="off" required>
                                    </div>
                                </div>


                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                    @if($ActiveLanguage->box_status)
                                        <div class="form-group row">
                                            <label id="map_title_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.topicMapTitle') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="map_title_{{ @$ActiveLanguage->code }}" value="{{ Session::get('Map')->{'title_'.@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                    @if($ActiveLanguage->box_status)
                                        <div class="form-group row">
                                            <label id="map_details_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.topicMapDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea name="details_{{ @$ActiveLanguage->code }}" id="map_details_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="3">{{ Session::get('Map')->{'details_'.@$ActiveLanguage->code} }}</textarea>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="form-group row">
                                    <label for="icon1" class="col-sm-3 form-control-label">{!!  __('backend.topicMapIcon') !!}</label>
                                    <div class="col-sm-9">
                                        <div class="radio">
                                            <label class="md-check">
                                                <input type="radio" name="icon" value="0" class="has-value" {{ (Session::get('Map')->icon==0)?"checked":"" }} id="icon1">
                                                <i class="primary"></i>
                                                <img src="{{ asset('assets/dashboard/images/marker_0.png')}}" style="width: 25px;">
                                            </label>
                                            <label class="md-check">
                                                <input type="radio" name="icon" value="1" class="has-value" {{ (Session::get('Map')->icon==1)?"checked":"" }} id="icon2">
                                                <i class="primary"></i>
                                                <img src="{{ asset('assets/dashboard/images/marker_1.png')}}" style="width: 25px;">
                                            </label>
                                            <label class="md-check">
                                                <input type="radio" name="icon" value="2" class="has-value" {{ (Session::get('Map')->icon==2)?"checked":"" }} id="icon3">
                                                <i class="primary"></i>
                                                <img src="{{ asset('assets/dashboard/images/marker_2.png')}}" style="width: 25px;">
                                            </label>
                                            <label class="md-check">
                                                <input type="radio" name="icon" value="3" class="has-value" {{ (Session::get('Map')->icon==3)?"checked":"" }} id="icon4">
                                                <i class="primary"></i>
                                                <img src="{{ asset('assets/dashboard/images/marker_3.png')}}" style="width: 25px;">
                                            </label>
                                            <label class="md-check">
                                                <input type="radio" name="icon" value="4" class="has-value" {{ (Session::get('Map')->icon==4)?"checked":"" }} id="icon5">
                                                <i class="primary"></i>
                                                <img src="{{ asset('assets/dashboard/images/marker_4.png')}}" style="width: 25px;">
                                            </label>
                                            <label class="md-check">
                                                <input type="radio" name="icon" value="5" class="has-value" {{ (Session::get('Map')->icon==5)?"checked":"" }} id="icon6">
                                                <i class="primary"></i>
                                                <img src="{{ asset('assets/dashboard/images/marker_5.png')}}" style="width: 25px;">
                                            </label>
                                            <label class="md-check">
                                                <input type="radio" name="icon" value="6" class="has-value" {{ (Session::get('Map')->icon==6)?"checked":"" }} id="icon7">
                                                <i class="primary"></i>
                                                <img src="{{ asset('assets/dashboard/images/marker_6.png')}}" style="width: 25px;">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="status1"
                                           class="col-sm-3 form-control-label">{!!  __('backend.status') !!}</label>
                                    <div class="col-sm-9">
                                        <div class="radio">
                                            <label class="md-check">
                                                <input type="radio" name="status" value="1" class="has-value" {{ (Session::get('Map')->status->status==1)?"checked":"" }} id="status1">
                                                <i class="primary"></i>
                                                {{ __('backend.active') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="status" value="0" class="has-value" {{ (Session::get('Map')->status->status==0)?"checked":"" }} id="status2">
                                                <i class="danger"></i>
                                                {{ __('backend.notActive') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-t-md">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-lg btn-primary m-t"><i
                                                class="material-icons">
                                                &#xe31b;</i> {!! __('backend.update') !!}</button>
                                        <a href="{{ route('topicsMaps',[$WebmasterSection->id,$Topic->id]) }}"
                                           class="btn btn-lg btn-default m-t"><i class="material-icons">
                                                &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    @endif

                @else
                    <div>
                        <div id="mmn-{{ $Topic->id }}" class="modal fade"
                             data-backdrop="true">
                            <div class="modal-dialog" id="animate">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('backend.topicNewMap') }}</h5>
                                    </div>
                                    <form method="POST" action="{{ route("topicsMapsStore",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dashboard-form" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body p-lg">
                                            <div>

                                                <div class="form-group row">
                                                    <label for="longitude" class="col-sm-3 form-control-label">{!!  __('backend.topicMapLocation') !!}
                                                    </label>
                                                    <div class="col-sm-5">
                                                        <input type="text" name="longitude" id="longitude" class="form-control" value="" placeholder="" maxlength="191" autocomplete="off" required>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" name="latitude" id="latitude" class="form-control" value="" placeholder="" maxlength="191" autocomplete="off" required>
                                                    </div>
                                                </div>

                                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                                    @if($ActiveLanguage->box_status)
                                                        <div class="form-group row">
                                                            <label id="map_title_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.topicMapTitle') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                            </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="map_title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                                    @if($ActiveLanguage->box_status)
                                                        <div class="form-group row">
                                                            <label id="map_details_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.topicMapDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                            </label>
                                                            <div class="col-sm-9">
                                                                <textarea name="details_{{ @$ActiveLanguage->code }}" id="map_details_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                                <div class="form-group row">
                                                    <label for="icon1"
                                                           class="col-sm-3 form-control-label">{!!  __('backend.topicMapIcon') !!}</label>
                                                    <div class="col-sm-9">

                                                        <div class="radio">
                                                            <label class="md-check">
                                                                <input type="radio" name="icon" value="0" class="has-value" checked id="icon1">
                                                                <i class="primary"></i>
                                                                <img src="{{ asset('assets/dashboard/images/marker_0.png')}}" style="width: 25px;">
                                                            </label>
                                                            <label class="md-check">
                                                                <input type="radio" name="icon" value="1" class="has-value" id="icon2">
                                                                <i class="primary"></i>
                                                                <img src="{{ asset('assets/dashboard/images/marker_1.png')}}" style="width: 25px;">
                                                            </label>
                                                            <label class="md-check">
                                                                <input type="radio" name="icon" value="2" class="has-value" id="icon3">
                                                                <i class="primary"></i>
                                                                <img src="{{ asset('assets/dashboard/images/marker_2.png')}}" style="width: 25px;">
                                                            </label>
                                                            <label class="md-check">
                                                                <input type="radio" name="icon" value="3" class="has-value"  id="icon4">
                                                                <i class="primary"></i>
                                                                <img src="{{ asset('assets/dashboard/images/marker_3.png')}}" style="width: 25px;">
                                                            </label>
                                                            <label class="md-check">
                                                                <input type="radio" name="icon" value="4" class="has-value"  id="icon5">
                                                                <i class="primary"></i>
                                                                <img src="{{ asset('assets/dashboard/images/marker_4.png')}}" style="width: 25px;">
                                                            </label>
                                                            <label class="md-check">
                                                                <input type="radio" name="icon" value="5" class="has-value"  id="icon6">
                                                                <i class="primary"></i>
                                                                <img src="{{ asset('assets/dashboard/images/marker_5.png')}}" style="width: 25px;">
                                                            </label>
                                                            <label class="md-check">
                                                                <input type="radio" name="icon" value="6" class="has-value"  id="icon7">
                                                                <i class="primary"></i>
                                                                <img src="{{ asset('assets/dashboard/images/marker_6.png')}}" style="width: 25px;">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button"
                                                    class="btn dark-white p-x-md"
                                                    data-dismiss="modal">{{ __('backend.cancel') }}</button>
                                            <button type="submit"
                                                    class="btn btn-primary p-x-md">{!! __('backend.add') !!}</button>
                                        </div>
                                    </form>
                                </div><!-- /.modal-content -->
                            </div>
                        </div>
                        <div class="row p-a" style="display: none">
                            <div class="col-sm-12">
                                <button class="btn btn-fw primary" data-toggle="modal"
                                        data-target="#mmn-{{ $Topic->id }}"
                                        ui-toggle-class="bounce" id="mapNew"
                                        ui-target="#animate">
                                    <i class="material-icons">&#xe02e;</i>
                                    &nbsp; {{ __('backend.topicNewMap') }}
                                </button>
                            </div>
                        </div>
                        @if(count($Topic->maps) == 0)
                            <div id="mapDivBtns">
                                <div class="col-sm-12">
                                    <div class=" p-y-2 p-x text-center light b-a h6 m-a-0">
                                        <div class="text-muted m-b"><i class="fa fa-laptop fa-4x"></i></div>
                                        {{ __('backend.noData') }}
                                        <br>
                                        <br>
                                        <button type="button" class="btn btn-fw primary" id="mapDivNew">
                                            <i class="material-icons">&#xe02e;</i>
                                            &nbsp; {{ __('backend.topicNewMap') }}
                                        </button>

                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-5">
                            @if(count($Topic->maps)>0)
                                <form method="POST" action="{{ route("topicsMapsUpdateAll",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dashboard-form">
                                    @csrf
                                    <div>
                                        <table class="table table-bordered">
                                            <thead class="dker">
                                            <tr>
                                                <th class="width20 dker">
                                                    <label class="ui-check m-a-0">
                                                        <input id="checkAll3" type="checkbox"><i></i>
                                                    </label>
                                                </th>
                                                <th>{{ __('backend.topicCommentName') }}</th>
                                                <th class="text-center"
                                                    style="width:30px;">{{ __('backend.status') }}</th>
                                                <th class="text-center"
                                                    style="width:110px;">{{ __('backend.options') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $title_var = "title_".@Helper::currentLanguage()->code;
                                                $title_var2 = "title_".config('smartend.default_language');
                                                ?>
                                            @foreach($Topic->maps as $map)
                                                    <?php
                                                    if ($map->$title_var != "") {
                                                        $title = $map->$title_var;
                                                    } else {
                                                        $title = $map->$title_var2;
                                                    }
                                                    ?>
                                                <tr>
                                                    <td class="dker"><label class="ui-check m-a-0">
                                                            <input type="checkbox" name="ids[]"
                                                                   value="{{ $map->id }}"><i
                                                                class="dark-white"></i>
                                                            <input type="hidden" name="row_ids[]" value="{{ $map->id }}" class="form-control row_no">
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="row_no_{{ $map->id }}" id="row_no_{{ $map->id }}" value="{{ $subSection->row_no }}" class="pull-left form-control row_no light" autocomplete="off">
                                                        <img
                                                            src="{{ asset('assets/dashboard/images/marker_').$map->icon.".png" }}"
                                                            style="width: 20px;">
                                                        @if($title !="")
                                                            <small>{!! $title !!}</small>
                                                        @else
                                                            <small>
                                                                {!! $map->longitude !!}
                                                            </small>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <i class="fa {{ ($map->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-sm success"
                                                           href="{{ route("topicsMapsEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id,"map_id"=>$map->id]) }}">
                                                            <small><i class="material-icons">
                                                                    &#xe3c9;</i></small>
                                                        </a>
                                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                                            <button class="btn btn-sm warning"
                                                                    data-toggle="modal"
                                                                    data-target="#mm-{{ $map->id }}"
                                                                    ui-toggle-class="bounce"
                                                                    ui-target="#animate">
                                                                <small><i class="material-icons">
                                                                        &#xe872;</i></small>
                                                            </button>
                                                        @endif

                                                    </td>
                                                </tr>
                                                <!-- .modal -->
                                                <div id="mm-{{ $map->id }}" class="modal fade"
                                                     data-backdrop="true">
                                                    <div class="modal-dialog" id="animate">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                            </div>
                                                            <div class="modal-body text-center p-lg">
                                                                <h5 class="m-b-0">
                                                                    {{ __('backend.confirmationDeleteMsg') }}
                                                                    <br>
                                                                    <strong>{!! $title !!}</strong>
                                                                    <br>
                                                                    <small>[
                                                                        {!! $map->longitude !!}
                                                                        , {!! $map->latitude !!}
                                                                        ]
                                                                    </small>

                                                                </h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                        class="btn dark-white p-x-md"
                                                                        data-dismiss="modal">{{ __('backend.no') }}</button>
                                                                <a href="{{ route("topicsMapsDestroy",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id,"map_id"=>$map->id]) }}"
                                                                   class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- / .modal -->
                                            @endforeach

                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 hidden-xs">
                                            <!-- .modal -->
                                            <div id="mm-all" class="modal fade" data-backdrop="true">
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
                                                            <button type="button"
                                                                    class="btn dark-white p-x-md"
                                                                    data-dismiss="modal">{{ __('backend.no') }}</button>
                                                            <button type="submit"
                                                                    class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div>
                                            </div>
                                            <!-- / .modal -->

                                            <select name="action" id="action3"
                                                    class="form-control c-select w-sm inline v-middle"
                                                    required>
                                                <option value="">{{ __('backend.bulkAction') }}</option>
                                                <option value="order">{{ __('backend.saveOrder') }}</option>
                                                <option
                                                    value="activate">{{ __('backend.activeSelected') }}</option>
                                                <option
                                                    value="block">{{ __('backend.blockSelected') }}</option>
                                                @if(@Auth::user()->permissionsGroup->delete_status)
                                                    <option
                                                        value="delete">{{ __('backend.deleteSelected') }}</option>
                                                @endif
                                            </select>
                                            <button type="submit" id="submit_all3"
                                                    class="btn white">{{ __('backend.apply') }}</button>
                                            <button id="submit_show_msg3" class="btn white"
                                                    data-toggle="modal"
                                                    style="display: none"
                                                    data-target="#mm-all" ui-toggle-class="bounce"
                                                    ui-target="#animate">{{ __('backend.apply') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                        <?php
                        $map_dsp = "style='display:none'";
                        $map_wds = "12";
                        if (count($Topic->maps) > 0) {
                            $map_dsp = "";
                            $map_wds = "7";
                        }
                        ?>
                    <div id="mapDiv" class="col-sm-{{$map_wds}}" {!! $map_dsp !!}>
                        <div style="margin-bottom: 3px;">
                            <small>
                                {!! __('backend.topicMapClick') !!} ,
                                <a data-toggle="modal"
                                   data-target="#mmn-{{ $Topic->id }}"
                                   ui-toggle-class="bounce"
                                   ui-target="#animate">
                                    <u>
                                        {!! __('backend.topicMapORClick') !!}
                                    </u>
                                </a>
                            </small>
                        </div>
                        <div id="map" style="height: 400px"></div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    @push("after-scripts")
        <script type="text/javascript"
                src="//maps.google.com/maps/api/js?key={{ config('smartend.google_maps_key') }}&language={{@Helper::currentLanguage()->code}}&callback=Function.prototype"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // var iconURLPrefix = 'http://maps.google.com/mapfiles/ms/icons/';
                var iconURLPrefix = "{{ asset('assets/dashboard/images/')."/" }}";
                var icons = [
                    iconURLPrefix + 'marker_0.png',
                    iconURLPrefix + 'marker_1.png',
                    iconURLPrefix + 'marker_2.png',
                    iconURLPrefix + 'marker_3.png',
                    iconURLPrefix + 'marker_4.png',
                    iconURLPrefix + 'marker_5.png',
                    iconURLPrefix + 'marker_6.png'
                ]

                var map = new google.maps.Map($('#map')[0], {
                    zoom: 7,
                        <?php
                    if (count($Topic->maps) > 0){
                        ?>
                    center: new google.maps.LatLng(<?php echo $Topic->maps[0]->longitude; ?>, <?php echo $Topic->maps[0]->latitude; ?>),
                        <?php
                    }else{
                        ?>
                    center: new google.maps.LatLng(31.012773903012743, 30.208982467651367),
                        <?php
                    }
                        ?>
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                    <?php
                    $title_var = "title_".@Helper::currentLanguage()->code;
                    $title_var2 = "title_".config('smartend.default_language');
                if (count($Topic->maps) > 0){
                foreach ($Topic->maps as $map){
                    if ($map->$title_var != "") {
                        $title = $map->$title_var;
                    } else {
                        $title = $map->$title_var2;
                    }
                    ?>
                var latlng1 = new google.maps.LatLng(<?php echo $map->longitude; ?>, <?php echo $map->latitude; ?>);
                var marker = new google.maps.Marker({
                    position: latlng1,
                    icon: icons[<?php echo $map->icon; ?>],
                    title: "<?php echo $title; ?>"
                });
                marker.setMap(map);

                    <?php
                }
                }
                    ?>
                var geocoder = new google.maps.Geocoder();
                google.maps.event.addListener(map, 'click', function (e) {
                    var marker = new google.maps.Marker({
                        position: e["latLng"],
                        icon: icons[Math.floor(Math.random() * (6 - 0 + 1) + 0)],
                        title: "New Map"
                    });

                    geocoder.geocode({
                        'latLng': e.latLng
                    }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                $("#details_{{ @$ActiveLanguage->code }}").val(results[0].formatted_address);
                                @endif
                                @endforeach

                            }
                        }
                    });

                    marker.setMap(map);
                    $("#longitude").val(e.latLng.lat());
                    $("#latitude").val(e.latLng.lng());
                    $("#mapNew").click()
                });


                $("#mapTabLink").click(function () {
                    setTimeout(function () {
                        google.maps.event.trigger(map, 'resize');
                    }, 1000);
                });
                $("#mapDivNew").click(function () {
                    google.maps.event.trigger(map, 'resize');
                });


            });
        </script>
    @endpush
@endif
