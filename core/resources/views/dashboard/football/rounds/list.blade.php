@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
    $name_var2 = 'name_' . config('smartend.default_language');
    $x = 0;
@endphp
@extends('dashboard.layouts.master')
@section('title', __('backend.matches'))
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush
@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">

                <h3><i class="material-icons">
                        &#xe3c9;</i> {{ __('backend.rounds') }}
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! $League->$name_var !!}</a> /
                    <a
                        href="{{ route('seasons', ['league_id' => $League->id, 'tab' => 'seasons']) }}">{{ __('backend.seasons') }}</a>
                    /
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline dropdown">
                        <a class="btn white b-a nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="material-icons md-18">&#xe5d4;</i> {{ __('backend.options') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale pull-right">
                            <a class="dropdown-item"
                                href="{{ route('seasons', ['league_id' => $League->id, 'tab' => 'seasons']) }}"><i
                                    class="material-icons">&#xe31b;</i> {{ __('backend.back') }}</a>
                            {{-- <a class="dropdown-item" onclick="updateMatchesAPI()"><i class="material-icons">&#xe863;</i>
                                {{ __('backend.update_matches') }}</a> --}}
                        </div>
                    </li>
                </ul>
            </div>
            <div class="box-tool box-tool-lg">
            <ul class="nav">
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
                                        <select name="season_id" id="find_season_id" class="form-control select2"
                                            ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                            <option value="">{{ __('backend.season') }} (
                                                {{ __('backend.all') }} )</option>
                                            <?php
                                                $t_arrow = '&raquo;';
                                            ?>
                                            @foreach ($seasons as $season)
                                                <option value="{{ $season->id }}">{{ $season->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6 m-b-5p">
                                    <div class="form-group m-b-0">
                                        <select name="status" id="find_status" class="form-control select2"
                                            ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                            <option value="">{{ __('backend.status') }} (
                                                {{ __('backend.all') }} )</option>
                                            <?php
                                                $t_arrow = '&raquo;';
                                            ?>
                                            @foreach (App\Enum\StatusMatchesEenum::cases() as $status)
                                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6 m-b-5p">
                                    <div class="form-group m-b-0">
                                        <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                                                format: '{{ Helper::jsDateFormat() }}',
                                                icons: {
                                                time: 'fa fa-clock-o',
                                                date: 'fa fa-calendar',
                                                up: 'fa fa-chevron-up',
                                                down: 'fa fa-chevron-down',
                                                previous: 'fa fa-chevron-left',
                                                next: 'fa fa-chevron-right',
                                                today: 'fa fa-screenshot',
                                                clear: 'fa fa-trash',
                                                close: 'fa fa-remove'
                                                },
                                            allowInputToggle: true,
                                            locale:'{{ @Helper::currentLanguage()->code }}'
                                            }">
                                                                    <input type="text" name="date" id="find_date" class="form-control" value="{{ ((@$_GET['date']!="")?Helper::formatDate(@$_GET['date']):"") }}" placeholder="{{ __('backend.topicDate') }}" autocomplete="off">
                                                                    <span class="input-group-addon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
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
            <div class="box nav-active-border b-primary">
                @include('dashboard.football.leagues.tabs')
            </div>
        </div>

        <div class="b-t">

            <form method="POST" action="{{ route('roundsUpdateAll') }}" class="dashboard-form">
                @csrf

                <div class="table-responsive">
                    <table class="table table-bordered m-a-0" id="matchsTable">
                        <thead class="dker">
                            <tr>
                                <th class="dker width20">
                                    <label class="ui-check m-a-0">
                                        <input id="checkAll" type="checkbox"><i></i>
                                    </label>
                                </th>
                                <th class="text-center w-64">ID</th>
                                <th>{{ __('backend.matche') }}</th>
                                <th class="text-center" style="width:200px;">{{ __('backend.starting_at') }}</th>
                                <th class="text-center" style="width:200px;">{{ __('backend.status') }}</th>
                                <th class="text-center" style="width:100px;">{{ __('backend.bulkAction') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <footer class="dker p-a">

                </footer>
            </form>
        </div>

    </div>
@endsection
@push('after-scripts')
    <script>
        function updateRoundAPI() {
            $('#updateRoundModal').modal('show');
        }

        function updateMatchesAPI() {
            $('#updateMatchesModal').modal('show');
        }
    </script>
@endpush
@push('after-scripts')
    <script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(document).ready(function() {
            var table_name = "#matchsTable";
            var dataTable = $('#matchsTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false, // ✅ بدون بحث
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [
                    [10, 20, 30, 50, 75, 100, 200, -1],
                    [10, 20, 30, 50, 75, 100, 200, "All"]
                ],
                ajax: {
                    "url": "{{ route('leagues.matches.list', $League->id) }}",
                    "dataType": "json",
                    "type": "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.find_q = $('#find_q').val();
                        d.date = $('#find_date').val();
                        d.season_id = $('#find_season_id').val();
                        d.status = $('#find_status').val();
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
                        "data": "check",
                        "class": "dker",
                        "orderable": false
                    },
                    {
                        data: "id",
                        orderable: true
                    },
                    {
                        data: "title",
                        orderable: true
                    },
                    {
                        data: "starting_at",
                        orderable: true
                    },
                    {
                        data: "is_finished",
                        orderable: true
                    },
                    {
                        data: "options",
                        orderable: false
                    },
                ],
                order: [
                    [0, "desc"]
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

            // function DeleteTeam(id) {
            //     $("#team_delete_btn").attr("row-id", id);
            //     $("#delete-team").modal("show");
            // }
        });
    </script>
@endpush
