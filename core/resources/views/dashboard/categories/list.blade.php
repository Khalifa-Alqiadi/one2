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
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <h3>{{ __('backend.sectionsOf') }}  {!! $WebmasterSectionTitle !!}</h3>
                        <small>
                            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                            <a>{!! $WebmasterSectionTitle !!}</a> /
                            <a>{{ __('backend.sectionsOf') }}  {!! $WebmasterSectionTitle !!}</a>
                        </small>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="row">
                            <div class="col-sm-7">
                                <form method="GET" action="{{ route("categories",$WebmasterSection->id) }}" class="form-inline" role="search">
                                    <div class="form-group">
                                        <div class="input-group"><input type="text" name="q" value="{{ @$_GET['q'] }}"
                                                                        class="form-control p-x" autocomplete="off"
                                                                        placeholder="{{ __('backend.searchIn')." ".__('backend.categories') }}">
                                            <span
                                                class="input-group-btn"><button type="submit"
                                                                                class="btn white b-a no-shadow"><i
                                                        class="fa fa-search"></i></button></span></div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-5">
                                @if(@Auth::user()->permissionsGroup->add_status)
                                    <a class="btn btn-fw primary w-100" style="overflow: hidden"
                                       href="{{route("categoriesCreate",$WebmasterSection->id)}}">
                                        <i class="material-icons">&#xe02e;</i>
                                        &nbsp; {{ __('backend.categoryNew') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="b-t">
                @if($Sections->total() == 0)
                    <div class="row p-a">
                        <div class="col-sm-12">
                            <div class=" p-a text-center ">
                                <div class="text-muted m-b"><i class="fa fa-laptop fa-4x"></i></div>
                                <h6>{{ __('backend.noData') }}</h6>
                            </div>
                        </div>
                    </div>
                @endif

                @if($Sections->total() > 0)
                    <form method="POST" action="{{ route('categoriesUpdateAll',$WebmasterSection->id) }}" class="dashboard-form">
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
                                    @if($WebmasterSection->no_status)
                                        <th class="text-center w-64">ID</th>
                                    @endif
                                    <th>{{ __('backend.categoryName') }}</th>
                                    <th class="text-center" style="width:100px;">{{ __('backend.contents') }}</th>
                                    <th class="text-center" style="width:100px;">{{ __('backend.visits') }}</th>
                                    <th class="text-center" style="width:50px;">{{ __('backend.status') }}</th>
                                    <th class="text-center" style="width:60px;">{{ __('backend.options') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $title_var = "title_".@Helper::currentLanguage()->code;
                                    $title_var2 = "title_".config('smartend.default_language');
                                    $x = 0;
                                    ?>
                                @foreach($Sections as $Section)
                                        <?php
                                        $x++;
                                        if ($Section->$title_var != "") {
                                            $title = $Section->$title_var;
                                        } else {
                                            $title = $Section->$title_var2;
                                        }
                                        ?>
                                    <tr>
                                        <td class="dker"><label class="ui-check m-a-0">
                                                <input type="checkbox" name="ids[]" value="{{ $Section->id }}"><i
                                                    class="dark-white"></i>
                                                <input type="hidden" name="row_ids[]" value="{{ $Section->id }}" class="form-control row_no">
                                            </label>
                                        </td>
                                        @if($WebmasterSection->no_status)
                                            <td class="text-center">{{ $Section->id }}</td>
                                        @endif
                                        <td class="h6 nowrap">
                                            @if($Section->photo !="")
                                                <div class="pull-right">
                                                    <img src="{{ route("fileView",["path" =>'sections/'.$Section->photo ]) }}"
                                                         style="height: 30px" alt="{{ $title }}">
                                                </div>
                                            @endif
                                            <input type="text" name="row_no_{{ $Section->id }}" id="row_no_{{ $Section->id }}" value="{{ $Section->row_no }}" class="form-control row_no light" autocomplete="off">
                                            @if (@Auth::user()->permissionsGroup->edit_status)
                                                <a href="{{ route("categoriesEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$Section->id]) }}">
                                                    @if($Section->icon !="")
                                                        <i class="{!! $Section->icon !!} "></i>
                                                    @endif
                                                    {{ $title }}
                                                </a>
                                            @else
                                                @if($Section->icon !="")
                                                    <i class="{!! $Section->icon !!} "></i>
                                                @endif
                                                {{ $title }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $Section->selectedCategories->count() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $Section->visits  !!}
                                        </td>

                                        <td class="text-center">
                                            <i class="fa {{ ($Section->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown {{ (($x+1) >= count($Sections))?"dropup":"" }}">
                                                <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                        data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                                    {{ __('backend.options') }}
                                                </button>
                                                <div class="dropdown-menu pull-right">
                                                    <a class="dropdown-item"
                                                       href="{{ Helper::categoryURL($Section->id,"",$Section) }}"
                                                       target="_blank"><i
                                                            class="material-icons">&#xe8f4;</i> {{ __('backend.preview') }}
                                                    </a>
                                                    @if(@Auth::user()->permissionsGroup->edit_status)
                                                        <a class="dropdown-item"
                                                           href="{{ route("categoriesEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$Section->id]) }}"><i
                                                                class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                                                        </a>
                                                    @endif
                                                    @if (@Auth::user()->permissionsGroup->add_status)
                                                        <a class="dropdown-item"
                                                           href="{{ route("categoriesClone",["webmasterId"=>$WebmasterSection->id,"id"=>$Section->id]) }}"><i
                                                                class="material-icons">&#xe14d;</i> {{ __('backend.clone') }}
                                                        </a>
                                                    @endif
                                                    @if(@Auth::user()->permissionsGroup->delete_status)
                                                        <a class="dropdown-item text-danger"
                                                           onclick="DeleteCategory('{{ $Section->id }}')"><i
                                                                class="material-icons">&#xe872;</i> {{ __('backend.delete') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    @foreach($Section->fatherSections as $subSection)
                                            <?php
                                            $x++;
                                            if ($subSection->$title_var != "") {
                                                $title = $subSection->$title_var;
                                            } else {
                                                $title = $subSection->$title_var2;
                                            }
                                            ?>
                                        <tr>
                                            <td class="dker"><label class="ui-check m-a-0">
                                                    <input type="checkbox" name="ids[]" value="{{ $subSection->id }}"><i
                                                        class="dark-white"></i>

                                                    <input type="hidden" name="row_ids[]" value="{{ $subSection->id }}" class="form-control row_no">
                                                </label>
                                            </td>
                                            @if($WebmasterSection->no_status)
                                                <td class="text-center">{{ $subSection->id }}</td>
                                            @endif
                                            <td class="h6 nowrap">
                                                @if($subSection->photo !="")
                                                    <div class="pull-right">
                                                        <img src="{{ route("fileView",["path" =>'sections/'.$subSection->photo ]) }}"
                                                             style="height: 30px" alt="{{ $title }}">
                                                    </div>
                                                @endif
                                                <img
                                                    src="{{ asset('assets/dashboard/images/treepart_'.@Helper::currentLanguage()->direction.'.png') }}"
                                                    class="submenu_tree">
                                                <input type="text" name="row_no_{{ $subSection->id }}" id="row_no_{{ $subSection->id }}" value="{{ $subSection->row_no }}" class="form-control row_no light" autocomplete="off">
                                                @if (@Auth::user()->permissionsGroup->edit_status)
                                                    <a href="{{ route("categoriesEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$subSection->id]) }}">
                                                        @if($subSection->icon !="")
                                                            <i class="{!! $subSection->icon !!} "></i>
                                                        @endif
                                                        {!! $title   !!}
                                                    </a>
                                                @else
                                                    @if($subSection->icon !="")
                                                        <i class="{!! $subSection->icon !!} "></i>
                                                    @endif
                                                    {!! $title   !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {!! $subSection->selectedCategories->count() !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $subSection->visits !!}
                                            </td>
                                            <td class="text-center">
                                                <i class="fa {{ ($subSection->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                            </td>

                                            <td class="text-center">
                                                <div class="dropdown {{ (($x+1) >= count($Sections))?"dropup":"" }}">
                                                    <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        <i class="material-icons">&#xe5d4;</i>
                                                        {{ __('backend.options') }}
                                                    </button>
                                                    <div class="dropdown-menu pull-right">
                                                        <a class="dropdown-item"
                                                           href="{{ Helper::categoryURL($subSection->id,"",$subSection) }}"
                                                           target="_blank"><i
                                                                class="material-icons">&#xe8f4;</i> {{ __('backend.preview') }}
                                                        </a>
                                                        @if(@Auth::user()->permissionsGroup->edit_status)
                                                            <a class="dropdown-item"
                                                               href="{{ route("categoriesEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$subSection->id]) }}"><i
                                                                    class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                                                            </a>
                                                        @endif
                                                        @if (@Auth::user()->permissionsGroup->add_status)
                                                            <a class="dropdown-item"
                                                               href="{{ route("categoriesClone",["webmasterId"=>$WebmasterSection->id,"id"=>$subSection->id]) }}"><i
                                                                    class="material-icons">&#xe14d;</i> {{ __('backend.clone') }}
                                                            </a>
                                                        @endif
                                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                                            <a class="dropdown-item text-danger"
                                                               onclick="DeleteCategory('{{ $subSection->id }}')"><i
                                                                    class="material-icons">&#xe872;</i> {{ __('backend.delete') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach($subSection->fatherSections as $subSection2)
                                                <?php
                                                $x++;
                                                if ($subSection2->$title_var != "") {
                                                    $title = $subSection2->$title_var;
                                                } else {
                                                    $title = $subSection2->$title_var2;
                                                }
                                                ?>
                                            <tr>
                                                <td class="dker"><label class="ui-check m-a-0">
                                                        <input type="checkbox" name="ids[]"
                                                               value="{{ $subSection2->id }}"><i
                                                            class="dark-white"></i>

                                                        <input type="hidden" name="row_ids[]" value="{{ $subSection2->id }}" class="form-control row_no">
                                                    </label>
                                                </td>
                                                @if($WebmasterSection->no_status)
                                                    <td class="text-center">{{ $subSection2->id }}</td>
                                                @endif
                                                <td class="h6 nowrap">
                                                    @if($subSection2->photo !="")
                                                        <div class="pull-right">
                                                            <img src="{{ route("fileView",["path" =>'sections/'.$subSection2->photo ]) }}"
                                                                 style="height: 30px" alt="{{ $title }}">
                                                        </div>
                                                    @endif
                                                    <img
                                                        src="{{ asset('assets/dashboard/images/treepart_sub_'.@Helper::currentLanguage()->direction.'.png') }}"
                                                        class="submenu_tree">
                                                    <input type="text" name="row_no_{{ $subSection2->id }}" id="row_no_{{ $subSection2->id }}" value="{{ $subSection2->row_no }}" class="form-control row_no light" autocomplete="off">
                                                    @if (@Auth::user()->permissionsGroup->edit_status)
                                                        <a href="{{ route("categoriesEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$subSection2->id]) }}">
                                                            @if($subSection2->icon !="")
                                                                <i class="{!! $subSection2->icon !!} "></i>
                                                            @endif
                                                            {{ $title }}
                                                        </a>
                                                    @else
                                                        @if($subSection2->icon !="")
                                                            <i class="{!! $subSection2->icon !!} "></i>
                                                        @endif
                                                        {!! $title   !!}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {!! $subSection2->selectedCategories->count() !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! $subSection2->visits !!}
                                                </td>
                                                <td class="text-center">
                                                    <i class="fa {{ ($subSection2->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                                </td>

                                                <td class="text-center">
                                                    <div class="dropdown {{ (($x+1) >= count($Sections))?"dropup":"" }}">
                                                        <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                                data-toggle="dropdown"><i
                                                                class="material-icons">&#xe5d4;</i>
                                                            {{ __('backend.options') }}
                                                        </button>
                                                        <div class="dropdown-menu pull-right">
                                                            <a class="dropdown-item"
                                                               href="{{ Helper::categoryURL($subSection2->id,"",$subSection2) }}"
                                                               target="_blank"><i
                                                                    class="material-icons">&#xe8f4;</i> {{ __('backend.preview') }}
                                                            </a>
                                                            @if(@Auth::user()->permissionsGroup->edit_status)
                                                                <a class="dropdown-item"
                                                                   href="{{ route("categoriesEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$subSection2->id]) }}"><i
                                                                        class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                                                                </a>
                                                            @endif
                                                            @if (@Auth::user()->permissionsGroup->add_status)
                                                                <a class="dropdown-item"
                                                                   href="{{ route("categoriesClone",["webmasterId"=>$WebmasterSection->id,"id"=>$subSection2->id]) }}"><i
                                                                        class="material-icons">&#xe14d;</i> {{ __('backend.clone') }}
                                                                </a>
                                                            @endif
                                                            @if(@Auth::user()->permissionsGroup->delete_status)
                                                                <a class="dropdown-item text-danger"
                                                                   onclick="DeleteCategory('{{ $subSection2->id }}')"><i
                                                                        class="material-icons">&#xe872;</i> {{ __('backend.delete') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
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
                                            @if(Helper::GeneralWebmasterSettings("instant_index"))
                                                @if($WebmasterSection->index_status)
                                                    <optgroup label="{{ __('backend.instantIndexing') }}">
                                                        <option value="index_add">
                                                            - {{ __('backend.sendToGoogleIndex') }}</option>
                                                        <option value="index_remove">
                                                            - {{ __('backend.removeToGoogleIndex') }}</option>
                                                    </optgroup>
                                                @endif
                                            @endif
                                            <optgroup label="{{ __('backend.active') }}/{{ __('backend.notActive') }}">
                                                <option value="activate">- {{ __('backend.activeSelected') }}</option>
                                                <option value="block">- {{ __('backend.blockSelected') }}</option>
                                            </optgroup>
                                            @if(@Auth::user()->permissionsGroup->delete_status)
                                                <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                            @endif
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
                                    <small
                                        class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Sections->firstItem() }}
                                        -{{ $Sections->lastItem() }} {{ __('backend.of') }}
                                        <strong>{{ $Sections->total()  }}</strong> {{ __('backend.records') }}</small>
                                </div>
                                <div class="col-sm-6 text-right text-center-xs">
                                    {!! $Sections->links() !!}
                                </div>
                            </div>
                        </footer>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <!-- .modal -->
    <div id="delete-category" class="modal fade" data-backdrop="true">
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
                    <a type="button" id="category_delete_btn" href=""
                       class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                </div>
            </div><!-- /.modal-content -->
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

        function DeleteCategory(id) {
            $("#category_delete_btn").attr("href", '{{ route("categoriesDestroy",["webmasterId"=>$WebmasterSection->id]) }}/' + id);
            $("#delete-category").modal("show");
        }
    </script>
@endpush
