<?php
$name_var = 'name_' . @Helper::currentLanguage()->code;
$name_var2 = 'name_' . config('smartend.default_language');
$title_var = 'title_' . @Helper::currentLanguage()->code;
$title_var2 = 'title_' . config('smartend.default_language');
$x = 0;

?>
@extends('dashboard.layouts.master')
@section('title', __('backend.leagues'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <h3>{{ __('backend.leagues') }} </h3>
                        <small>
                            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                            <a>{{ __('backend.leagues') }} </a>
                        </small>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="row">
                            <div class="col-sm-7">
                                <form method="GET" action="{{ route('leagues') }}" class="form-inline" role="search">
                                    <div class="form-group">
                                        <div class="input-group"><input type="text" name="q"
                                                value="{{ @$_GET['q'] }}" class="form-control p-x" autocomplete="off"
                                                placeholder="{{ __('backend.searchIn') . ' ' . __('backend.leagues') }}">
                                            <span class="input-group-btn"><button type="submit"
                                                    class="btn white b-a no-shadow"><i
                                                        class="fa fa-search"></i></button></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-5">
                                @if (@Auth::user()->permissionsGroup->add_status)
                                    <a class="btn btn-fw info w-100" style="overflow: hidden"
                                        href="{{ route('leaguesUpdateAPI') }}">
                                        <i class="material-icons">&#xe02e;</i>
                                        &nbsp; {{ __('backend.update_refrech') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="b-t">
                @if ($Leagues->total() == 0)
                    <div class="row p-a">
                        <div class="col-sm-12">
                            <div class=" p-a text-center ">
                                <div class="text-muted m-b"><i class="fa fa-futbol-o fa-4x"></i></div>
                                <h6>{{ __('backend.noData') }}</h6>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($Leagues->total() > 0)
                    <form method="POST" action="{{ route('leaguesUpdateAll') }}" class="dashboard-form">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered m-a-0">
                                <thead class="dker">
                                    <tr>
                                        <th class="dker width20">
                                            <label class="ui-check m-a-0">
                                                <input id="checkAll" type="checkbox"><i></i>
                                            </label>
                                        </th>
                                        <th class="text-center w-64">ID</th>
                                        <th>{{ __('backend.name') }}</th>
                                        <th class="text-center" style="width:300px;">{{ __('backend.country') }}</th>
                                        <th class="text-center" style="width:100px;">{{ __('backend.bulkAction') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Leagues as $League)
                                        <tr>
                                            <td class="dker"><label class="ui-check m-a-0">
                                                    <input type="checkbox" name="ids[]" value="{{ $League->id }}"><i
                                                        class="dark-white"></i>
                                                    <input type="hidden" name="row_ids[]" value="{{ $League->id }}" class="form-control row_no">
                                                </label>
                                            </td>
                                            <td class="text-center">{{ $League->id }}</td>
                                            <td class="h6 nowrap">
                                                <div class="d-flex content-justify-between">
                                                    {{ $League->$name_var }}
                                                    <div class="pull-right">
                                                        @if ($League->image_path)
                                                            <img src="{{ $League->image_path }}" style="height:30px" alt="">
                                                        @endif
                                                    </div>

                                                </div>

                                            </td>
                                            <td class="text-center">
                                                {{$League->country ? $League->country->$title_var : ''}}
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown {{ (($x+1) >= count($Leagues))?"dropup":"" }}">
                                                    <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                            data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                                        {{ __('backend.options') }}
                                                    </button>
                                                    <div class="dropdown-menu pull-right">
                                                        @if(@Auth::user()->permissionsGroup->edit_status)
                                                            <a class="dropdown-item"
                                                            href="{{ route("leaguesEdit",["id"=>$League->id]) }}"><i
                                                                    class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td class="text-center">{{ $League->sport_id }}</td> --}}
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

                                            <optgroup label="{{ __('backend.active') }}/{{ __('backend.notActive') }}">
                                                <option value="activate">- {{ __('backend.activeSelected') }}</option>
                                                <option value="block">- {{ __('backend.blockSelected') }}</option>
                                            </optgroup>
                                        </select>
                                        <button type="submit" id="submit_all"
                                                class="btn white">{{ __('backend.apply') }}</button>
                                        <button id="submit_show_msg" class="btn white" data-toggle="modal"
                                                style="display: none"
                                                data-target="#m-all" ui-toggle-class="bounce"
                                                ui-target="#animate">{{ __('backend.apply') }}
                                        </button>
                                    @endif
                                </div>
                                <div class="col-sm-3 text-center">
                                    <small class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }}
                                        {{ $Leagues->firstItem() }}
                                        -{{ $Leagues->lastItem() }} {{ __('backend.of') }}
                                        <strong>{{ $Leagues->total() }}</strong> {{ __('backend.records') }}</small>
                                </div>
                                <div class="col-sm-6 text-right text-center-xs">
                                    {!! $Leagues->links() !!}
                                </div>
                            </div>
                        </footer>
                    </form>
                @endif

            </div>
        </div>
    </div>
@endsection
@push("after-scripts")
    <script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function () {

            $("#submit_all").css("display", "inline-block");
            $("#submit_show_msg").css("display", "none");
        });
    </script>
@endpush
