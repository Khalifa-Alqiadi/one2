@extends('dashboard.layouts.master')
@section('title', __('backend.popups'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3>{{ __('backend.popups') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('backend.popups') }}</a>
                </small>
            </div>

            @if(@Auth::user()->permissionsGroup->add_status)
                <div class="box-tool">
                    <ul class="nav">
                        <li class="nav-item inline">
                            <a class="btn btn-fw primary" href="{{ route('popupsCreate') }}">
                                <i class="material-icons">&#xe02e;</i> {{ __('backend.popupCreate') }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
            @if($Popups->total() == 0)
                <div class="row p-a">
                    <div class="col-sm-12">
                        <div class=" p-a text-center">
                            <div class="text-muted m-b"><i class="fa fa-laptop fa-4x"></i></div>
                            <h6>{{ __('backend.noData') }}</h6>
                        </div>
                    </div>
                </div>
            @endif

            @if($Popups->total() > 0)
                <div class="b-t">
                    <form method="POST" action="{{ route('popupsUpdateAll') }}" class="dashboard-form">
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
                                    <th>{{ __('backend.popupTitle') }}</th>
                                    <th class="text-center width200">{{ __('backend.showIn') }}</th>
                                    <th class="text-center width50">{{ __('backend.status') }}</th>
                                    <th class="text-center" style="width:150px;">{{ __('backend.options') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $title_var = "title_".@Helper::currentLanguage()->code;
                                    $title_var2 = "title_".config('smartend.default_language');
                                    ?>
                                @foreach($Popups as $Popup)
                                        <?php
                                        if ($Popup->$title_var != "") {
                                            $title = $Popup->$title_var;
                                        } else {
                                            $title = $Popup->$title_var2;
                                        }
                                        ?>
                                    <tr>
                                        <td class="dker"><label class="ui-check m-a-0">
                                                <input type="checkbox" name="ids[]" value="{{ $Popup->id }}"><i
                                                    class="dark-white"></i>
                                                <input type="hidden" name="row_ids[]" value="{{ $Popup->id }}" class="form-control row_no">
                                            </label>
                                        </td>
                                        <td class="h6">
                                            <input type="text" name="row_no_{{ $Popup->id }}" value="{{ $Popup->row_no }}" class="form-control row_no">
                                            <a href="{{ route("popupsEdit",["id"=>$Popup->id]) }}">
                                                @if($Popup->photo !="")
                                                        <?php
                                                        $img_type = array(
                                                            ".gif", ".jpeg", ".png", ".jpg", ".svg", ".webp"
                                                        );
                                                        $ext = strrchr($Popup->photo, ".");
                                                        $ext = strtolower($ext);
                                                        ?>
                                                    @if(in_array($ext, $img_type))
                                                        <div class="pull-right">
                                                            <img
                                                                src="{{ route("fileView",["path" =>'banners/'.$Popup->photo])}}"
                                                                style="height: 40px" alt="{{ $title }}">
                                                        </div>
                                                    @endif
                                                @endif
                                                {!! $title !!}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if(@$Popup->show_in == 2)
                                                <span class="label dker b-a text-sm">{!!  __('backend.specificPages') !!}</span>
                                            @elseif(@$Popup->show_in == 1)
                                                <span class="label dker b-a text-sm">{!!  __('backend.homePage') !!}</span>
                                            @else
                                                <span class="label dker b-a text-sm">{!!  __('backend.allPages') !!}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <i class="fa {{ ($Popup->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                        </td>
                                        <td class="text-center">
                                            @if(@Auth::user()->permissionsGroup->edit_status)
                                                <a class="btn btn-sm success"
                                                   href="{{ route("popupsEdit",["id"=>$Popup->id]) }}" data-toggle="tooltip"
                                                   data-original-title="{{ __('backend.edit') }}">
                                                    <i class="material-icons">&#xe3c9;</i>
                                                </a>
                                            @endif
                                            @if(@Auth::user()->permissionsGroup->delete_status)
                                                <button type="button" class="btn btn-sm warning"
                                                        onclick="DeletePopup('{{ $Popup->id }}')" data-toggle="tooltip"
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
                                        class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Popups->firstItem() }}
                                        -{{ $Popups->lastItem() }} {{ __('backend.of') }}
                                        <strong>{{ $Popups->total()  }}</strong> {{ __('backend.records') }}</small>
                                </div>
                                <div class="col-sm-6 text-right text-center-xs">
                                    {!! $Popups->links() !!}
                                </div>
                            </div>
                        </footer>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- .modal -->
    <div id="DeletePopup" class="modal fade" data-backdrop="true">
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
                    <a id="DeletePopupBtn" href=""
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

        function DeletePopup(id, title = '') {
            $("#DeletePopup").modal("show");
            $("#DeletePopup .modal-body .record-title").html(title);
            $("#DeletePopupBtn").attr("href", "{{ route("popupsDestroy") }}/" + id);
        }
    </script>
@endpush
