@extends('dashboard.layouts.master')
@section('title', __('backend.adsBanners'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3>{{ __('backend.adsBanners') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('backend.adsBanners') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="btn white" href="{{ route('WebmasterBanners') }}">
                            <i class="material-icons">&#xe8b8;</i> {{ __('backend.adsBannersSettings') }}
                        </a>
                    </li>
                </ul>
            </div>
            @if($Banners->total() >0)
                @if(@Auth::user()->permissionsGroup->add_status)
                    <div class="row p-a">
                        <div class="col-sm-12">
                            @foreach($WebmasterBanners as $WebmasterBanner)
                                <a class="btn btn-fw primary marginBottom5"
                                   href="{{route("BannersCreate",$WebmasterBanner->id)}}">
                                    <i class="material-icons">&#xe02e;</i>
                                    &nbsp; {!! __('backend.add')." ".$WebmasterBanner->{'title_'.@Helper::currentLanguage()->code} !!}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
            @if($Banners->total() == 0)
                <div class="row p-a">
                    <div class="col-sm-12">
                        <div class=" p-a text-center ">
                            {{ __('backend.noData') }}
                            <br>
                            <br>
                            @if(@Auth::user()->permissionsGroup->add_status)
                                @foreach($WebmasterBanners as $WebmasterBanner)
                                    <a class="btn btn-fw primary marginBottom5"
                                       href="{{route("BannersCreate",$WebmasterBanner->id)}}">
                                        <i class="material-icons">&#xe02e;</i>
                                        &nbsp; {!! $WebmasterBanner->{'title_'.@Helper::currentLanguage()->code} !!}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($Banners->total() > 0)
                <form method="POST" action="{{ route("BannersUpdateAll") }}" class="dashboard-form">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered m-a-0">
                            <thead class="dker">
                            <tr>
                                <th class="width20 dker">
                                    <label class="ui-check m-a-0">
                                        <input id="checkAll" type="checkbox"><i></i>
                                    </label>
                                </th>
                                <th>{{ __('backend.bannerTitle') }}</th>
                                <th class="text-center width200">{{ __('backend.category') }}</th>
                                <th class="text-center width50">{{ __('backend.status') }}</th>
                                <th class="text-center" style="width:150px;">{{ __('backend.options') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                $title_var = "title_".@Helper::currentLanguage()->code;
                                $title_var2 = "title_".config('smartend.default_language');
                                ?>
                            @foreach($Banners as $Banner)
                                    <?php
                                    if ($Banner->$title_var != "") {
                                        $title = $Banner->$title_var;
                                    } else {
                                        $title = $Banner->$title_var2;
                                    }
                                    ?>
                                <tr>
                                    <td class="dker"><label class="ui-check m-a-0">
                                            <input type="checkbox" name="ids[]" value="{{ $Banner->id }}"><i
                                                class="dark-white"></i>
                                            <input type="hidden" name="row_ids[]" value="{{ $Banner->id }}" class="form-control row_no">
                                        </label>
                                    </td>
                                    <td class="h6">
                                        <input type="text" name="row_no_{{ $Banner->id }}" value="{{ $Banner->row_no }}" id="row_no_{{ $Banner->id }}" class="form-control row_no">
                                        <a href="{{ route("BannersEdit",["id"=>$Banner->id]) }}">
                                            @if($Banner->icon !="")
                                                <i class="{!! $Banner->icon !!} "></i>
                                            @endif
                                            @if($Banner->{"file_".@Helper::currentLanguage()->code} !="")
                                                    <?php
                                                    $img_type = array(".gif", ".jpeg", ".png", ".jpg", ".svg", ".webp");
                                                    $ext = strrchr($Banner->{"file_".@Helper::currentLanguage()->code},
                                                        ".");
                                                    $ext = strtolower($ext);
                                                    ?>
                                                @if(in_array($ext, $img_type))
                                                    <div class="pull-right">
                                                        <img
                                                            src="{{ route("fileView",["path" =>'banners/'.$Banner->{"file_".@Helper::currentLanguage()->code} ]) }}?w=150&h=150"
                                                            style="height: 40px" alt="{{ $title }}">
                                                    </div>
                                                @endif
                                            @endif
                                            {!! $title !!}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                    <span
                                        class="label dker b-a text-sm">{!! @$Banner->webmasterBanner->{'title_'.@Helper::currentLanguage()->code}   !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa {{ ($Banner->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                    </td>
                                    <td class="text-center">
                                        @if(@Auth::user()->permissionsGroup->edit_status)
                                            <a class="btn btn-sm success"
                                               href="{{ route("BannersEdit",["id"=>$Banner->id]) }}" data-toggle="tooltip"
                                               data-original-title="{{ __('backend.edit') }}">
                                                <i class="material-icons">&#xe3c9;</i>
                                            </a>
                                        @endif
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <button type="button" class="btn btn-sm warning"
                                                    onclick="DeleteBanner('{{ $Banner->id }}','{{$title}}')"
                                                    data-toggle="tooltip"
                                                    data-original-title="{{ __('backend.delete') }}">
                                                <i class="material-icons">&#xe872;</i>
                                            </button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                    <footer class="dker p-a">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs">
                                <!-- .modal -->
                                <div id="m-all" class="modal fade" data-backdrop="true">
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
                                                <button type="submit"
                                                        class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div>
                                <!-- / .modal -->
                                @if(@Auth::user()->permissionsGroup->edit_status)
                                    <select name="action" id="action" class="form-control c-select w-sm inline v-middle"
                                            required>
                                        <option value="">{{ __('backend.bulkAction') }}</option>
                                        <option value="order">{{ __('backend.saveOrder') }}</option>
                                        <option value="activate">{{ __('backend.activeSelected') }}</option>
                                        <option value="block">{{ __('backend.blockSelected') }}</option>
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                        @endif
                                    </select>
                                    <button type="submit" id="submit_all"
                                            class="btn white">{{ __('backend.apply') }}</button>
                                    <button id="submit_show_msg" class="btn white displayNone" data-toggle="modal"
                                            data-target="#m-all" ui-toggle-class="bounce"
                                            ui-target="#animate">{{ __('backend.apply') }}
                                    </button>
                                @endif
                            </div>

                            <div class="col-sm-3 text-center">
                                <small
                                    class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Banners->firstItem() }}
                                    -{{ $Banners->lastItem() }} {{ __('backend.of') }}
                                    <strong>{{ $Banners->total()  }}</strong> {{ __('backend.records') }}</small>
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $Banners->links() !!}
                            </div>
                        </div>
                    </footer>
                </form>
            @endif
        </div>
    </div>

    <!-- .modal -->
    <div id="DeleteBanner" class="modal fade" data-backdrop="true">
        <div class="modal-dialog" id="animate">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <h5 class="m-b-0">
                        {{ __('backend.confirmationDeleteMsg') }}
                        <br>
                        [ <strong class="record-title"></strong> ]
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark-white p-x-md"
                            data-dismiss="modal">{{ __('backend.no') }}</button>
                    <a id="DeleteBannerBtn" href=""
                       class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- / .modal -->
@endsection
@push("after-scripts")
    <script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function () {
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });

        function DeleteBanner(id, title = '') {
            $("#DeleteBanner").modal("show");
            $("#DeleteBanner .modal-body .record-title").html(title);
            $("#DeleteBannerBtn").attr("href", "{{ route("BannersDestroy") }}/" + id);
        }
    </script>
@endpush
