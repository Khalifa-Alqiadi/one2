@extends('dashboard.layouts.master')
@section('title', __('backend.tags'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3>{!! __('backend.tags') !!}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! __('backend.tags') !!}</a>
                </small>
            </div>
            <div class="box-tool box-tool-lg">
                <ul class="nav" style="display: flex;justify-content: space-around;">
                    <li class="nav-item inline">
                        <form method="GET" action="{{ route('tags') }}" class="dashboard-form w-md" id="filter_form">
                            @csrf
                            <div class="form-group l-h m-a-0">
                                <div class="input-group">
                                    <input type="text" name="find_q" id="tags_find_q" class="form-control p-x"
                                           autocomplete="off" placeholder="{{ __('backend.searchTags') }}...">
                                    <span
                                        class="input-group-btn"><button type="submit" class="btn white b-a no-shadow"><i
                                                class="fa fa-search"></i></button></span></div>
                            </div>
                        </form>
                    </li>
                    @if(@Auth::user()->permissionsGroup->add_status)
                        <li class="nav-item inline">
                            <button type="button" class="btn btn-fw primary m-l" onclick="CreateTag()">
                                <i class="material-icons">&#xe02e;</i>
                                &nbsp; {{ __('backend.addNewTag') }}
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
            <div>
                <form method="POST" action="{{ route('tagsUpdateAll') }}" class="dashboard-form" id="table_form">
                    @csrf
                    <div class="table-responsive" style="overflow: inherit">
                        <table class="table table-bordered" style="width: 100%"
                               id="tags_table">
                            <thead class="dker">

                            @if(@Auth::user()->permissionsGroup->edit_status)
                                <th style="width:20px;">
                                    <label class="ui-check m-a-0">
                                        <input id="checkAll" type="checkbox"><i></i>
                                    </label>
                                </th>
                            @endif
                            <th>{{ __('backend.tag') }}</th>
                            <th style="width:80px;">{{ __('backend.contents') }}</th>
                            <th style="width:80px;">{{ __('backend.visits') }}</th>
                            <th style="width:80px;">{{ __('backend.status') }}</th>
                            <th style="width:160px;">{{ __('backend.createdAt') }}</th>
                            <th class="text-center" style="width:60px;">{{ __('backend.options') }}</th>
                            </thead>
                        </table>
                    </div>
                    <footer class="p-x p-b dker">
                        <div class="row">
                            <div class="col-sm-12 hidden-xs">
                                @if(@Auth::user()->permissionsGroup->delete_status)
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
                                @endif
                                @if(@Auth::user()->permissionsGroup->edit_status)
                                    <select name="action" id="action"
                                            class="input-sm form-control w-md inline v-middle c-select"
                                            required>
                                        <option value="">{{ __('backend.bulkAction') }}</option>
                                        <option value="activate">{{ __('backend.activeSelected') }}</option>
                                        <option value="block">{{ __('backend.blockSelected') }}</option>
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
                        </div>
                    </footer>
                </form>
            </div>
        </div>
    </div>

    <!-- .modal -->
    @if(@Auth::user()->permissionsGroup->delete_status)
        <div id="delete-tag" class="modal fade" data-backdrop="true">
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
                        <button type="button" id="tag_delete_btn" row-id=""
                                class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
    @endif

    <div id="create-tag" class="modal fade" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('tagsStore') }}" class="dashboard-form" id="create-tag-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i
                                class="material-icons">&#xe02e;</i> <span
                                class="modal-box-title">{!! __('backend.addNewTag') !!}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-a-0">
                        @include("dashboard.tags.create")
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md"
                                data-dismiss="modal">{!! __('backend.cancel') !!}
                        </button>
                        <button type="submit" id="create-tag-form-submit" class="btn info p-x-md"><i
                                class="material-icons">&#xe31b;</i> {!! __('backend.add') !!}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="update-tag" class="modal fade" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('tagsUpdate') }}" class="dashboard-form" id="update-tag-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i
                                class="material-icons">&#xe3c9;</i> <span
                                class="modal-box-title">{!! __('backend.editTag') !!}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-a-0"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md"
                                data-dismiss="modal">{!! __('backend.cancel') !!}
                        </button>
                        <button type="submit" id="update-tag-form-submit" class="btn info p-x-md"><i
                                class="material-icons">&#xe31b;</i> {!! __('backend.save') !!}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push("after-scripts")
    <script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
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
        let table_name = "#tags_table";
        $(document).ready(function () {
            var dataTable = $(table_name).DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "responsive": true,
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [[10, 20, 30, 50, 75, 100, 200, -1], [10, 20, 30, 50, 75, 100, 200, "All"]],
                "ajax": {
                    "url": "{{ route('tagsList') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (data) {
                        data._token = "{{csrf_token()}}";
                        data.find_q = $('#tags_find_q').val();
                    }

                },
                "dom": '<"dataTables_wrapper"<"col-sm-12 col-md-9"i><"col-sm-12 col-md-3"l><"col-sm-12 col-md-12"r><"row"t><"row b-t p-x p-t dker"<"col-sm-12"p>>>',
                "fnDrawCallback": function () {
                    if ($(table_name + '_paginate .paginate_button').length > 3) {
                        $(table_name + '_paginate')[0].style.display = "block";
                    } else {
                        $(table_name + '_paginate')[0].style.display = "none";
                    }

                    $('[data-toggle="tooltip"]').tooltip({html: true});
                },
                "language": {!! json_encode(__("backend.dataTablesTranslation")) !!}
                ,
                "columns": [

                        @if(@Auth::user()->permissionsGroup->edit_status)
                    {
                        "data": "check", "class": "dker", "orderable": false
                    },
                        @endif
                    {
                        "data": "title"
                    },
                    {
                        "data": "contents", "orderable": false
                    },
                    {
                        "data": "visits", "orderable": true
                    },
                    {
                        "data": "status", "orderable": true
                    },
                    {
                        "data": "created_at", "orderable": true
                    },
                    {
                        "data": "options", "orderable": false
                    }
                ]
                @if(@Auth::user()->permissionsGroup->edit_status)
                , "order": [[1, "desc"]]
                @else
                , "order": [[0, "desc"]]
                @endif

            });
            dataTable.on('page.dt', function () {
                $('html, body').animate({
                    scrollTop: $(".dataTables_wrapper").offset().top
                }, 'slow');
            });
            $.fn.dataTable.ext.errMode = 'none';

            $('#filter_form').submit(function () {
                $(table_name).DataTable().ajax.reload();
                return false;
            });


            $('#table_form').submit(function (evt) {
                evt.preventDefault();
                $("#m-all").modal("hide");
                var frm = this;
                $('#submit_all').html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.apply') !!}");
                $('#submit_show_msg').html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.apply') !!}");
                $('#submit_all').prop('disabled', true);
                $('#submit_show_msg').prop('disabled', true);
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "<?php echo route("tagsUpdateAll"); ?>",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result.stat == 'success') {
                            $(table_name).DataTable().ajax.reload();
                            swal({
                                title: "<span class='text-success'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        } else {
                            swal({
                                title: "<span class='text-danger'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        }
                        $(frm)[0].reset();
                        $('#submit_all').html("{!! __('backend.apply') !!}");
                        $('#submit_show_msg').html("{!! __('backend.apply') !!}");
                        $('#submit_all').prop('disabled', false);
                        $('#submit_show_msg').prop('disabled', false);
                    }
                });
                return false;
            });
        });


        function CreateTag() {
            $("#create-tag").modal("show");
        }

        function UpdateTag(id) {
            $("#update-tag").modal("show");
            let btn = $('#update-tag-form-submit');
            btn.html("<img src=\"{{ URL::to('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.save') !!}");
            btn.prop('disabled', true);
            $.get("{{ route("tagsEdit") }}/" + id, function (data) {
                btn.prop('disabled', false);
                btn.html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.save') !!}");
                $('#update-tag .modal-body').html(data);
            });
            return false;
        }

        function DeleteTag(id) {
            $("#tag_delete_btn").attr("row-id", id);
            $("#delete-tag").modal("show");
        }

        $("#tag_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "GET",
                    url: "<?php echo route("tagsDestroy"); ?>/" + row_id,
                    success: function (result) {
                        if (result.stat == 'success') {
                            $('#tag_delete_btn').html("{!! __('backend.yes') !!}");
                            swal({
                                title: "<span class='text-success'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 50000,
                            });
                            $(table_name).DataTable().ajax.reload();
                        } else {
                            swal({
                                title: "<span class='text-danger'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        }
                        $('#delete-tag').modal('hide');
                        $('.modal-backdrop').hide();
                    }
                });
            }
        });


        $('#create-tag-form').submit(function (evt) {
            evt.preventDefault();
            let btn = $('#create-tag-form-submit');
            btn.html("<img src=\"{{ URL::to('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.add') !!}");
            btn.prop('disabled', true);
            var formData = new FormData(this);
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("tagsStore"); ?>",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    btn.html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.add') !!}");
                    btn.prop('disabled', false);
                    if (result.stat === 'success') {
                        $(table_name).DataTable().ajax.reload();
                        swal({
                            title: "<span class='text-success'>" + result.msg + "</span>",
                            text: "",
                            html: true,
                            type: "success",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                            timer: 5000,
                        });
                        $("#create-tag-form")[0].reset();
                    } else {
                        swal({
                            title: "<span class='text-danger'>" + result.msg + "</span>",
                            text: "",
                            html: true,
                            type: "error",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                            timer: 5000,
                        });
                    }

                    $('#create-tag').modal('hide');
                    $('.modal-backdrop').hide();
                }
            });
            //console.log(xhr);
            return false;
        });

        $('#update-tag-form').submit(function (evt) {
            evt.preventDefault();
            let btn = $('#update-tag-form-submit');
            btn.html("<img src=\"{{ URL::to('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.save') !!}");
            btn.prop('disabled', true);
            var formData = new FormData(this);
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("tagsUpdate"); ?>",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    btn.html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.save') !!}");
                    btn.prop('disabled', false);
                    if (result.stat === 'success') {
                        $(table_name).DataTable().ajax.reload();
                        swal({
                            title: "<span class='text-success'>" + result.msg + "</span>",
                            text: "",
                            html: true,
                            type: "success",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                            timer: 5000,
                        });
                    } else {
                        swal({
                            title: "<span class='text-danger'>" + result.msg + "</span>",
                            text: "",
                            html: true,
                            type: "error",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                            timer: 5000,
                        });
                    }

                    $('#update-tag').modal('hide');
                    $('.modal-backdrop').hide();
                }
            });
            //console.log(xhr);
            return false;
        });
    </script>
@endpush
