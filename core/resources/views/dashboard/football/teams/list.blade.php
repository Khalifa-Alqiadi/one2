<?php
$name_var = 'name_' . @Helper::currentLanguage()->code;
$name_var2 = 'name_' . config('smartend.default_language');
$title_var = 'title_' . @Helper::currentLanguage()->code;
$title_var2 = 'title_' . config('smartend.default_language');
$x = 0;

?>
@extends('dashboard.layouts.master')
@section('title', __('backend.teams'))
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <h3>{{ __('backend.teams') }} </h3>
                        <small>
                            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                            <a>{{ __('backend.teams') }} </a>
                        </small>
                    </div>
                </div>
            </div>
            <div class="box-tool box-tool-lg">
                <ul class="nav">

                    @if (@Auth::user()->permissionsGroup->add_status)
                        <li class="nav-item inline">
                            <a class="btn btn-fw info w-100" style="overflow: hidden" href="{{ route('teamsUpdateAPI') }}">
                                <i class="material-icons">&#xe02e;</i>
                                &nbsp; {{ __('backend.update_refrech') }}</a>
                        </li>
                    @endif
                    <li class="nav-item inline">
                        <button type="button" class="btn info" id="filter_btn" title="{{ __('backend.search') }}"
                            data-toggle="tooltip">
                            <i class="fa fa-search"></i>
                        </button>
                    </li>
                    @if (@Auth::user()->permissionsGroup->add_status)
                        <li class="nav-item inline">
                            <button type="button" class="btn accent" id="import_btn" title="{{ __('backend.import') }}"
                                data-toggle="tooltip">
                                <i class="fa fa-upload"></i>
                            </button>
                        </li>
                    @endif
                    <li class="nav-item inline">
                        <button type="button" class="btn warn" id="print_btn" title="{{ __('backend.print') }}"
                            data-toggle="tooltip" onclick="print_as('print')">
                            <i class="fa fa-print"></i>
                        </button>
                    </li>
                    <li class="nav-item inline">
                        <button type="button" class="btn success" id="excel_btn" title="{{ __('backend.export') }}"
                            data-toggle="tooltip" onclick="print_as('excel')">
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="b-t">
                <div class="dker b-b displayNone" id="filter_div">
                    <div class="p-a">
                        <form method="GET" action="{{ route('teams') }}" class="dashboard-form" id="filter_form"
                            target="">
                            <input type="hidden" name="stat" id="search_submit_stat" value="">
                            <div class="filter_div">
                                <div class="row">
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <input type="text" name="find_q" id="find_q" class="form-control"
                                            value="{{ @$_GET['find_q'] }}" placeholder="{{ __('backend.searchFor') }}"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                        <div class="form-group m-b-0">
                                            <select name="country_id" id="find_country_id" class="form-control select2"
                                                ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                                <option value="">{{ __('backend.country') }} (
                                                    {{ __('backend.all') }} )</option>
                                                <?php
                                                $title_var = 'title_' . @Helper::currentLanguage()->code;
                                                $title_var2 = 'title_' . config('smartend.default_language');

                                                $t_arrow = '&raquo;';
                                                ?>
                                                @foreach ($countries as $country)
                                                    <?php
                                                    if ($country->$title_var != '') {
                                                        $ftitle = $country->$title_var;
                                                    } else {
                                                        $ftitle = $country->$title_var2;
                                                    }
                                                    ?>
                                                    <option value="{{ $country->id }}"
                                                        {{$country->id == $country_id ? 'selected' : ''}}>{{ $ftitle }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-6">
                                        <button class="btn white w-full" id="search-btn" type="button"><i
                                                class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('teamsUpdateAll') }}" class="dashboard-form">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered" id="teamsTable" style="width:100%">
                    <thead class="dker">
                        <tr>
                            <th style="width:20px;">
                                <label class="ui-check m-a-0">
                                    <input id="checkAll" type="checkbox"><i></i>
                                </label>
                            </th>
                            <th style="width:80px" class="text-center">#</th>
                            {{-- <th style="width:80px" class="text-center">{{__('backend.logo')}}</th> --}}
                            <th>{{ __('backend.name') }}</th>
                            <th style="width:200px" class="text-center">{{ __('backend.country') }}</th>
                            <th style="width:110px" class="text-center">{{ __('backend.status') }}</th>
                            <th style="width:160px" class="text-center">{{ __('backend.updatedAt') }}</th>
                            <th style="width:140px" class="text-center">{{ __('backend.options') }}</th>
                        </tr>
                    </thead>
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
                                        <button type="submit" class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div>
                        </div>
                        <!-- / .modal -->

                        @if (@Auth::user()->permissionsGroup->edit_status)
                            <select name="action" id="action" class="form-control c-select w-sm inline v-middle"
                                required>
                                <option value="">{{ __('backend.bulkAction') }}</option>
                                <option value="order">{{ __('backend.saveOrder') }}</option>

                                <optgroup label="{{ __('backend.active') }}/{{ __('backend.notActive') }}">
                                    <option value="activate">- {{ __('backend.activeSelected') }}</option>
                                    <option value="block">- {{ __('backend.blockSelected') }}</option>
                                </optgroup>
                                @if(@Auth::user()->permissionsGroup->delete_status)
                                    <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                @endif
                            </select>
                            <button type="submit" id="submit_all" class="btn white">{{ __('backend.apply') }}</button>
                            <button id="submit_show_msg" class="btn white" data-toggle="modal" style="display: none"
                                data-target="#m-all" ui-toggle-class="bounce"
                                ui-target="#animate">{{ __('backend.apply') }}
                            </button>
                        @endif
                    </div>
                </div>
            </footer>
        </form>

    </div>
    <div id="delete-team" class="modal fade" data-backdrop="true">
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
                    <button type="button" id="team_delete_btn" row-id=""
                            class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
@endsection
@push('after-scripts')
    <script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function() {
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });
        $(document).ready(function() {
            var table_name = "#teamsTable";
            var dataTable = $('#teamsTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false, // ✅ بدون بحث
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [
                    [10, 20, 30, 50, 75, 100, 200, -1],
                    [10, 20, 30, 50, 75, 100, 200, "All"]
                ],
                ajax: {
                    "url": "{{ route('teams.list') }}",
                    "dataType": "json",
                    "type": "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.find_q = $('#find_q').val();
                        d.country_id = $('#find_country_id').val();
                        // لو بتضيف فلتر لاحقًا:
                        // d.q = $('#q').val();
                        // d.status = $('#status').val();
                    }
                },
                "dom": '<"dataTables_wrapper"<"col-sm-12 col-md-9"i><"col-sm-12 col-md-3"l><"col-sm-12 col-md-12"r><"row"t><"row b-t p-x p-t dker"<"col-sm-12"p>>>',
                "fnDrawCallback": function() {
                    if ($(table_name + '_paginate .paginate_button').length > 3) {
                        $(table_name + '_paginate')[0].style.display = "block";
                    } else {
                        $(table_name + '_paginate')[0].style.display = "none";
                    }


                    $('[data-toggle="tooltip"]').tooltip({
                        html: true
                    });
                    $('[data-toggle-second="tooltip"]').tooltip({
                        html: true
                    });
                },
                "language": {!! json_encode(__('backend.dataTablesTranslation')) !!},
                columns: [{
                        "data": "check", "class": "dker", "orderable": false
                    },
                    {
                        data: "id",
                        orderable: true
                    },
                    {
                        data: "name",
                        orderable: true
                    },
                    {
                        data: "country_id",
                        orderable: true
                    },
                    {
                        data: "status",
                        orderable: true
                    },
                    {
                        data: "updated_at",
                        orderable: true
                    },
                    {
                        data: "options",
                        orderable: false
                    },
                ],
                order: [
                    [0, "asc"]
                ],
            });
            dataTable.on('page.dt', function() {
                $('html, body').animate({
                    scrollTop: $(".dataTables_wrapper").offset().top
                }, 'slow');
            });
            $.fn.dataTable.ext.errMode = 'none';
            $("#search-btn").on('click', function() {
                dataTable.draw();
            });
            $('#filter_form').submit(function() {
                if ($("#search_submit_stat").val() === "") {
                    dataTable.draw();
                    return false;
                }
            });

            $("#filter_btn").on('click', function() {
                $("#filter_div").slideToggle();
            });

            function DeleteTeam(id) {
            $("#team_delete_btn").attr("row-id", id);
                $("#delete-team").modal("show");
            }
        });

        function DeleteTeam(id) {
            $("#team_delete_btn").attr("row-id", id);
            $("#delete-team").modal("show");
        }

        $("#team_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "GET",
                    url: "<?php echo route("teamsDestroy"); ?>/" + row_id,
                    success: function (result) {
                        var obj_result = jQuery.parseJSON(result);
                        if (obj_result.stat == 'success') {
                            $('#team_delete_btn').html("{!! __('backend.yes') !!}");
                            swal({
                                title: "<span class='text-success'>{{ __("backend.deleteDone") }}</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                            $('#teams').DataTable().ajax.reload();
                        }
                        $('#delete-team').modal('hide');
                        $('.modal-backdrop').hide();
                    }
                });
            }
        });
    </script>
@endpush
