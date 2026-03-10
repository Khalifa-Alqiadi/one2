@extends('dashboard.layouts.master')
@section('title', __('backend.siteSectionsSettings'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker m-b-xs">
                <h3>{{ __('backend.siteSectionsSettings') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    {{ __('backend.webmasterTools') }} /
                    <a href="">{{ __('backend.siteSectionsSettings') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="btn btn-fw primary" href="{{route("WebmasterSectionsCreate")}}">
                            <i class="material-icons">&#xe02e;</i>
                            &nbsp; {{ __('backend.sectionNew') }}</a>
                    </li>
                </ul>
            </div>
            @if($WebmasterSections->total() == 0)
                <div class="row p-a">
                    <div class="col-sm-12">
                        <div class=" p-a text-center light ">
                            {{ __('backend.noData') }}
                        </div>
                    </div>
                </div>
            @endif

            @if($WebmasterSections->total() > 0)
                <form method="POST" action="{{ route("WebmasterSectionsUpdateAll") }}" class="dashboard-form">
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
                                <th class="text-center w-64">ID</th>
                                <th>{{ __('backend.sectionName') }}</th>
                                <th>{{ __('backend.sectionType') }}</th>
                                <th>{{ __('backend.categories') }}</th>
                                <th class="text-center" style="width:50px;">{{ __('backend.status') }}</th>
                                @if (config('smartend.rss_status'))
                                    <th class="text-center" style="width:50px;">RSS</th>
                                @endif
                                <th class="text-center" style="width:170px;">{{ __('backend.options') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                $title_var = "title_".@Helper::currentLanguage()->code;
                                $title_var2 = "title_".config('smartend.default_language');
                                ?>
                            @foreach($WebmasterSections as $WebSection)
                                    <?php
                                    if ($WebSection->$title_var != "") {
                                        $title = $WebSection->$title_var;
                                    } else {
                                        $title = $WebSection->$title_var2;
                                    }
                                    ?>
                                <tr>
                                    <td class="dker"><label class="ui-check m-a-0">
                                            <input type="checkbox" name="ids[]" value="{{ $WebSection->id }}"><i
                                                class="dark-white"></i>
                                            <input type="hidden" name="row_ids[]" value="{{ $WebSection->id }}" class="form-control row_no">
                                        </label>
                                    </td>
                                    <td class="text-center">{{ $WebSection->id }}</td>
                                    <td class="h6">
                                        <input type="text" name="row_no_{{ $WebSection->id }}" value="{{ $WebSection->row_no }}" class="form-control row_no" autocomplete="off">
                                        <a href="{{ route("WebmasterSectionsEdit",["id"=>$WebSection->id]) }}">{!! $title  !!}</a>
                                    </td>
                                    <td>
                                        {!! ($WebSection->type==10) ? "<span class='label text-sm indigo'><i class='material-icons'>&#xe051;</i>  ".__('backend.landingPages')."</span>":"" !!}
                                        {!! ($WebSection->type==9) ? "<span class='label text-sm orange'><i class='material-icons'>&#xe2c8;</i>  ".__('backend.documentationSection')."</span>":"" !!}
                                        {!! ($WebSection->type==8) ? "<span class='label text-sm teal'><i class='material-icons'>&#xe896;</i>  ".__('backend.accordionSection')."</span>":"" !!}
                                        {!! ($WebSection->type==7) ? "<span class='label text-sm deep-purple'><i class='material-icons'>&#xe880;</i>  ".__('backend.private2')."</span>":"" !!}
                                        {!! ($WebSection->type==6) ? "<span class='label text-sm orange'><i class='material-icons'>&#xe31f;</i>  ".__('backend.publicForm')."</span>":"" !!}
                                        {!! ($WebSection->type==5) ? "<span class='label text-sm accent'><i class='fa fa-table'></i>  ".__('backend.tableView')."</span>":"" !!}
                                        {!! ($WebSection->type==4) ? "<span class='label text-sm pink'><i class='material-icons'>&#xe899;</i>  ".__('backend.private')."</span>":"" !!}
                                        {!! ($WebSection->type==3) ? "<span class='label text-sm warn'><i class='material-icons'>&#xe050;</i>  ".__('backend.typeSounds')."</span>":"" !!}
                                        {!! ($WebSection->type==2) ? "<span class='label text-sm red'><i class='material-icons'>&#xe63a;</i>  ".__('backend.typeVideos')."</span>":"" !!}
                                        {!! ($WebSection->type==1) ? "<span class='label text-sm green'><i class='material-icons'>&#xe251;</i>  ".__('backend.typePhotos')."</span>":"" !!}
                                        {!! ($WebSection->type==0) ? "<span class='label text-sm blue-grey'><i class='material-icons'>&#xe873;</i>  ".__('backend.typeTextPages')."</span>":"" !!}
                                    </td>
                                    <td>
                                        {!! ($WebSection->sections_status==2) ? "<span><i class='material-icons'>&#xe23e;</i>  ".__('backend.mainAndSubCategories')."</span>":"" !!}
                                        {!! ($WebSection->sections_status==1) ? "<span><i class='material-icons'>&#xe241;</i>  ".__('backend.mainCategoriesOnly')."</span>":"" !!}
                                        {!! ($WebSection->sections_status==0) ? "<span><i class='material-icons'>&#xe14b;</i>  ".__('backend.withoutCategories')."</span>":"" !!}
                                    </td>
                                    <td class="text-center">
                                        <i class="fa {{ ($WebSection->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                    </td>
                                    @if (config('smartend.rss_status'))
                                        <td class="text-center">
                                            @if(in_array($WebSection->type,[0,5,8]))
                                                <a target="_blank" class="text-warning"
                                                   href="/rss?section={{ $WebSection->id }}?lang={{ @Helper::currentLanguage()->code }}"><i
                                                        class="fa-2x fa fa-rss"></i></a>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <a class="btn btn-sm success"
                                           href="{{ route("WebmasterSectionsEdit",["id"=>$WebSection->id]) }}" data-toggle="tooltip" title="{{ __('backend.edit') }}">
                                            <small><i class="material-icons">&#xe3c9;</i>
                                            </small>
                                        </a>

                                        <button type="button" class="btn btn-sm info" onclick="clone_module('{{ $WebSection->id }}','{!! $title !!}')" data-toggle="tooltip" title="{{ __('backend.clone') }}">
                                            <small><i class="material-icons">&#xe14d;</i>
                                            </small>
                                        </button>

                                        <button type="button" class="btn btn-sm warning" onclick="delete_module('{{ $WebSection->id }}','{!! $title !!}')" data-toggle="tooltip" title="{{ __('backend.delete') }}">
                                            <small><i class="material-icons">&#xe872;</i>
                                            </small>
                                        </button>


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
                                <div id="del-module" class="modal fade" data-backdrop="true">
                                    <div class="modal-dialog" id="animate">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                            </div>
                                            <div class="modal-body text-center p-lg">
                                                <h5 class="m-b-0">
                                                    {{ __('backend.confirmationDeleteMsg') }}
                                                    <br>
                                                    [ <strong id="del-module-title"></strong> ]
                                                </h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark-white p-x-md"
                                                        data-dismiss="modal">{{ __('backend.no') }}</button>
                                                <a id="del-module-btn" href="#" class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div>
                                <!-- / .modal -->
                                <!-- .modal -->
                                <div id="clone-module" class="modal fade" data-backdrop="true">
                                    <div class="modal-dialog" id="animate">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                            </div>
                                            <div class="modal-body text-center p-lg">
                                                <p>
                                                    {{ __('backend.cloneRecordConfirmation') }}
                                                    <br>
                                                    [ <strong id="clone-module-title"></strong> ]
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark-white p-x-md"
                                                        data-dismiss="modal">{{ __('backend.no') }}</button>
                                                <a id="clone-module-btn" href="#" class="btn info p-x-md">{{ __('backend.yes') }}</a>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div>
                                <!-- / .modal -->
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

                                <select name="action" id="action" class="form-control c-select w-sm inline v-middle"
                                        required>
                                    <option value="">{{ __('backend.bulkAction') }}</option>
                                    <option value="order">{{ __('backend.saveOrder') }}</option>
                                    @if(Helper::GeneralWebmasterSettings("instant_index"))
                                        <optgroup label="{{ __('backend.instantIndexing') }}">
                                            <option value="index_add">
                                                - {{ __('backend.sendToGoogleIndex') }}</option>
                                            <option value="index_remove">
                                                - {{ __('backend.removeToGoogleIndex') }}</option>
                                        </optgroup>
                                    @endif
                                    <optgroup label="{{ __('backend.active') }}/{{ __('backend.notActive') }}">
                                        <option value="activate">- {{ __('backend.activeSelected') }}</option>
                                        <option value="block">- {{ __('backend.blockSelected') }}</option>
                                    </optgroup>
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
                                    class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $WebmasterSections->firstItem() }}
                                    -{{ $WebmasterSections->lastItem() }} {{ __('backend.of') }}
                                    <strong>{{ $WebmasterSections->total()  }}</strong> {{ __('backend.records') }}
                                </small>
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $WebmasterSections->links() !!}
                            </div>
                        </div>
                    </footer>
                </form>
            @endif
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

        function delete_module(id, title) {
            $("#del-module-title").html(title);
            $("#del-module-btn").attr('href', '{{ route("WebmasterSectionsDestroy") }}/' + id);
            $("#del-module").modal('show');
        }

        function clone_module(id, title) {
            $("#clone-module-title").html(title);
            $("#clone-module-btn").attr('href', '{{ route("WebmasterSectionsClone") }}/' + id);
            $("#clone-module").modal('show');
        }
    </script>
@endpush
