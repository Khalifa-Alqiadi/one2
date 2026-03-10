@extends('dashboard.layouts.master')
@section('title', __('backend.siteMenus'))
@section('content')
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
                    <button type="button" onclick="document.getElementById('menusUpdateAll').submit()"
                            class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>

    @foreach($ParentMenus as $ParentMenu)
        <div id="mg-{{ $ParentMenu->id }}" class="modal fade"
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
                            <strong>[ {{ $ParentMenu->{'title_'.Helper::currentLanguage()->code} }}
                                ]</strong>
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn dark-white p-x-md"
                                data-dismiss="modal">{{ __('backend.no') }}</button>
                        <a href="{{ route("parentMenusDestroy",["id"=>$ParentMenu->id]) }}"
                           class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
    @endforeach

    <?php
    try {
        $edt_id = $EditedMenu->id;
    } catch (Exception $e) {
        $edt_id = 0;
    }
    $edt_title = "";
    ?>
    <div class="row-col">
        <div class="col-sm ww-md w-auto-xs light lt bg-auto  hidden-print">
            <div class="padding pos-rlt">
                <div>
                    <div class="nav-active-white">
                        <ul class="nav nav-pills nav-stacked nav-sm">
                            <li class="nav-item">
                                <ul class="list p-b-1" style="list-style: none;">
                                    <?php
                                    $title_var = "title_".@Helper::currentLanguage()->code;
                                    $title_var2 = "title_".config('smartend.default_language');
                                    ?>
                                    @foreach($ParentMenus as $ParentMenu)
                                            <?php
                                            if ($ParentMenu->$title_var != "") {
                                                $title = $ParentMenu->$title_var;
                                            } else {
                                                $title = $ParentMenu->$title_var2;
                                            }
                                            ?>
                                            <?php
                                            if ($ParentMenu->id == $edt_id) {
                                                $edt_title = $title;
                                            }
                                            ?>
                                        <li style="margin-bottom: 5px">
                                            <a class="btn white {{ ($ParentMenuId==$ParentMenu->id)?"active":"" }} w-100"
                                               style="white-space: normal"
                                               href="{{ route("menus",["ParentMenuId"=>$ParentMenu->id]) }}"
                                                {!!   ($ParentMenu->id == $edt_id) ? " style='font-weight: bold;color:#0cc2aa'":""  !!} >
                                                {!! $title !!}
                                            </a>

                                        </li>

                                    @endforeach

                                </ul>
                            </li>
                        </ul>
                        <div class="p-y">
                            @if(Session::has('EditMenu'))
                                <form method="POST" action="{{ route("parentMenusUpdate",["id"=>$EditedMenu->id,"ParentMenuId"=>"0"]) }}" class="dashboard-form">
                                    @csrf
                                    <div class="input-group input-group-sm">

                                        @foreach(Helper::languagesList() as $ActiveLanguage)
                                            @if($ActiveLanguage->box_status)
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="menu_title_{{ @$ActiveLanguage->code }}" value="{{ $EditedMenu->{'title_'.@$ActiveLanguage->code} }}" required maxlength="191" class="form-control" dir="{{ @$ActiveLanguage->direction }}" placeholder="{{ __("backend.menuTitle",[], @$ActiveLanguage->code) }}"/>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-fw primary" type="submit">{!! __('backend.save') !!}</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="POST" action="{{ route("parentMenusStore",$edt_id) }}" class="dashboard-form">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <h6><i class="material-icons">&#xe145;</i> {!! __('backend.newMenu') !!}</h6>
                                        @foreach(Helper::languagesList() as $ActiveLanguage)
                                            @if($ActiveLanguage->box_status)
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="menu_create_title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" class="form-control" dir="{{ @$ActiveLanguage->direction }}" placeholder="{{ __("backend.menuTitle",[], @$ActiveLanguage->code) }}"/>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm primary" type="submit">{!! __('backend.add') !!}</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div ui-view class="padding">
                <div>
                    <div class="box">
                        <div class="box-header dker">
                            <h3 class="inline">{{ $edt_title }}</h3>
                            <small class="inline">
                                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                                <a href="">{{ __('backend.settings') }}</a> /
                                <a href="">{{ __('backend.siteMenus') }}</a>

                            </small>
                        </div>
                        <div class="box-tool">
                            @if($ParentMenuId > 2)
                                <div class="pull-right">
                                    <a class="btn btn-sm success"
                                       title="{{ __('backend.edit')." ".__('backend.menuTitle') }}"
                                       href="{{ route("parentMenusEdit",["id"=>$ParentMenuId]) }}">
                                        <i class="material-icons">&#xe3c9;</i></a>

                                    <button class="btn btn-sm warning"
                                            data-toggle="modal"
                                            data-target="#mg-{{ $ParentMenuId }}"
                                            ui-toggle-class="bounce"
                                            title="{{ __('backend.delete') }}"
                                            ui-target="#animate"><i class="material-icons">&#xe872;</i></button>
                                </div>
                            @endif
                        </div>
                        @if($Menus->total() >0)
                            <div class="row p-a">
                                <div class="col-sm-12">
                                    <a class="btn btn-fw primary" href="{{route("menusCreate",$edt_id)}}">
                                        <i class="material-icons">&#xe02e;</i>
                                        &nbsp; {{ __('backend.newLink') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if($Menus->total() == 0)
                            <div class="row p-a">
                                <div class="col-sm-12">
                                    <div class=" p-a text-center ">
                                        {{ __('backend.noData') }}
                                        <br>
                                        @if(count($ParentMenus)>0)
                                            <br>
                                            <a class="btn btn-fw primary" href="{{route("menusCreate",$edt_id)}}">
                                                <i class="material-icons">&#xe02e;</i>
                                                &nbsp; {{ __('backend.newLink') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($Menus->total() > 0)
                            <form method="POST" action="{{ route("menusUpdateAll") }}" class="dashboard-form" id="menusUpdateAll">
                                @csrf
                                <input type="hidden" name="ParentMenuId" value="{{ $edt_id }}">
                                <div class="table-responsive">
                                    <table class="table table-bordered m-a-0">
                                        <thead class="dker">
                                        <tr>
                                            <th class="width20 dker">
                                                <label class="ui-check m-a-0">
                                                    <input id="checkAll" type="checkbox"><i></i>
                                                </label>
                                            </th>
                                            <th>{{ __('backend.linkURL') }}</th>
                                            <th class="text-center" style="width:50px;">{{ __('backend.status') }}</th>
                                            <th class="text-center"
                                                style="width:100px;">{{ __('backend.options') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $title_var = "title_".@Helper::currentLanguage()->code;
                                            $title_var2 = "title_".config('smartend.default_language');
                                            ?>
                                        @foreach($Menus as $Menu)
                                                <?php
                                                if ($Menu->$title_var != "") {
                                                    $title = $Menu->$title_var;
                                                } else {
                                                    $title = $Menu->$title_var2;
                                                }
                                                ?>
                                            <tr>
                                                <td class="dker"><label class="ui-check m-a-0">
                                                        <input type="checkbox" name="ids[]" value="{{ $Menu->id }}"><i
                                                            class="dark-white"></i>
                                                        <input type="hidden" name="row_ids[]" value="{{ $Menu->id }}" class="form-control row_no">
                                                    </label>
                                                </td>
                                                <td class="h5">
                                                    <input type="text" name="row_no_{{ $Menu->id }}" value="{{ $Menu->row_no }}" class="form-control row_no light" autocomplete="off">
                                                    @if($Menu->icon)
                                                        <i class="{{ $Menu->icon }}"></i>
                                                    @endif

                                                    {!! $title  !!}
                                                </td>
                                                <td class="text-center">
                                                    <i class="fa {{ ($Menu->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-sm success"
                                                       href="{{ route("menusEdit",["ParentMenuId"=>$edt_id,"id"=>$Menu->id]) }}">
                                                        <small><i class="material-icons">
                                                                &#xe3c9;</i> {{ __('backend.edit') }}
                                                        </small>
                                                    </a>

                                                </td>
                                            </tr>
                                            @foreach($Menu->subMenus as $subMenu)
                                                    <?php
                                                    if ($subMenu->$title_var != "") {
                                                        $title = $subMenu->$title_var;
                                                    } else {
                                                        $title = $subMenu->$title_var2;
                                                    }
                                                    ?>
                                                <tr>
                                                    <td class="dker"><label class="ui-check m-a-0">
                                                            <input type="checkbox" name="ids[]"
                                                                   value="{{ $subMenu->id }}"><i
                                                                class="dark-white"></i>
                                                            <input type="hidden" name="row_ids[]" value="{{ $subMenu->id }}" class="form-control row_no">
                                                        </label>
                                                    </td>
                                                    <td class="h5">
                                                        <img
                                                            src="{{ asset('assets/dashboard/images/treepart_'.@Helper::currentLanguage()->direction.'.png') }}"
                                                            class="submenu_tree">
                                                        <input type="text" name="row_no_{{ $subMenu->id }}" value="{{ $subMenu->row_no }}" class="form-control row_no light" autocomplete="off">

                                                        @if($subMenu->icon)
                                                            <i class="{{ $subMenu->icon }}"></i>
                                                        @endif
                                                        {!! $title  !!}</td>
                                                    <td class="text-center">
                                                        <i class="fa {{ ($subMenu->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-sm success"
                                                           href="{{ route("menusEdit",["ParentMenuId"=>$edt_id,"id"=>$subMenu->id]) }}">
                                                            <small><i class="material-icons">
                                                                    &#xe3c9;</i> {{ __('backend.edit') }}
                                                            </small>
                                                        </a>

                                                    </td>
                                                </tr>

                                                @foreach($subMenu->subMenus as $subMenu2)
                                                        <?php
                                                        if ($subMenu2->$title_var != "") {
                                                            $title = $subMenu2->$title_var;
                                                        } else {
                                                            $title = $subMenu2->$title_var2;
                                                        }
                                                        ?>
                                                    <tr>
                                                        <td class="dker"><label class="ui-check m-a-0">
                                                                <input type="checkbox" name="ids[]"
                                                                       value="{{ $subMenu2->id }}"><i
                                                                    class="dark-white"></i>
                                                                <input type="hidden" name="row_ids[]" value="{{ $subMenu2->id }}" class="form-control row_no">
                                                            </label>
                                                        </td>
                                                        <td class="h5">
                                                            <img
                                                                src="{{ asset('assets/dashboard/images/treepart_sub_'.@Helper::currentLanguage()->direction.'.png') }}"
                                                                class="submenu_tree">

                                                            <input type="text" name="row_no_{{ $subMenu2->id }}" value="{{ $subMenu2->row_no }}" class="form-control row_no light" autocomplete="off">

                                                            @if($subMenu2->icon)
                                                                <i class="{{ $subMenu2->icon }}"></i>
                                                            @endif
                                                            {!! $title  !!}</td>
                                                        <td class="text-center">
                                                            <i class="fa {{ ($subMenu2->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                                        </td>
                                                        <td class="text-center">
                                                            <a class="btn btn-sm success"
                                                               href="{{ route("menusEdit",["ParentMenuId"=>$edt_id,"id"=>$subMenu2->id]) }}">
                                                                <small><i class="material-icons">
                                                                        &#xe3c9;</i> {{ __('backend.edit') }}
                                                                </small>
                                                            </a>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach

                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>
                                <footer class="dker p-a">
                                    <div class="row">
                                        <div class="col-sm-3 hidden-xs">

                                            <select name="action" id="action"
                                                    class="form-control c-select w-sm inline v-middle"
                                                    required>
                                                <option value="">{{ __('backend.bulkAction') }}</option>
                                                <option value="order">{{ __('backend.saveOrder') }}</option>
                                                <option value="activate">{{ __('backend.activeSelected') }}</option>
                                                <option value="block">{{ __('backend.blockSelected') }}</option>
                                                <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                            </select>
                                            <button type="submit" id="submit_all"
                                                    class="btn white">{{ __('backend.apply') }}</button>
                                            <button id="submit_show_msg" class="btn white" data-toggle="modal"
                                                    style="display: none"
                                                    data-target="#m-all" ui-toggle-class="bounce"
                                                    ui-target="#animate">{{ __('backend.apply') }}
                                            </button>
                                        </div>

                                        <div class="col-sm-3 text-center">
                                            <small
                                                class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Menus->firstItem() }}
                                                -{{ $Menus->lastItem() }} {{ __('backend.of') }}
                                                <strong>{{ $Menus->total()  }}</strong> {{ __('backend.records') }}
                                            </small>
                                        </div>
                                        <div class="col-sm-6 text-right text-center-xs">
                                            {!! $Menus->links() !!}
                                        </div>
                                    </div>
                                </footer>
                            </form>
                        @endif
                    </div>
                </div>
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
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });
    </script>
@endpush

@push("after-styles")
    <style>
        .app-body {
            padding-bottom: 0 !important;
        }

        .app-body > .row-col {
            height: calc(100vh - 65px);
        }
    </style>
@endpush
