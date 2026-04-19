@extends('dashboard.layouts.master')
<?php
$title_var = "title_".@Helper::currentLanguage()->code;
$title_var2 = "title_".config('smartend.default_language');
$name_var = "name_".@Helper::currentLanguage()->code;
if ($WebmasterSection->$title_var != "") {
    $WebmasterSectionTitle = $WebmasterSection->$title_var;
} else {
    $WebmasterSectionTitle = $WebmasterSection->$title_var2;
}
?>
@section('title', $WebmasterSectionTitle)
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <?php
                $cf_title_var = "title_".@Helper::currentLanguage()->code;
                $cf_title_var2 = "title_".config('smartend.default_language');

                $title_var = "title_".@Helper::currentLanguage()->code;
                $title_var2 = "title_".config('smartend.default_language');
                if ($WebmasterSection->$title_var != "") {
                    $WebmasterSectionTitle = $WebmasterSection->$title_var;
                } else {
                    $WebmasterSectionTitle = $WebmasterSection->$title_var2;
                }
                ?>
                <h3>{!! $WebmasterSectionTitle !!}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! $WebmasterSectionTitle !!}</a>
                </small>
            </div>
            <div class="box-tool box-tool-lg">
                <ul class="nav">

                    @if(@Auth::user()->permissionsGroup->add_status)
                        <li class="nav-item inline">
                            <a class="btn primary" href="{{route("topicsCreate",$WebmasterSection->id)}}">
                                <i class="material-icons">&#xe02e;</i>
                                <span
                                    class="phone-hide">{{ __('backend.topicNew') }}  {!! $WebmasterSectionTitle !!}</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item inline">
                        <button type="button" class="btn info" id="filter_btn" title="{{ __('backend.search') }}"
                                data-toggle="tooltip">
                            <i class="fa fa-search"></i>
                        </button>
                    </li>
                    @if($WebmasterSection->type !=10)
                        @if(@Auth::user()->permissionsGroup->add_status)
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
                                    data-toggle="tooltip"
                                    onclick="print_as('excel')">
                                <i class="fa fa-file-excel-o"></i>
                            </button>
                        </li>
                    @endif
                    @if(@Auth::user()->permissionsGroup->webmaster_status)
                        <li class="nav-item inline">
                            <a href="{{ route("WebmasterSectionsEdit",["id"=>$WebmasterSection->id]) }}" target="_blank"
                               class="btn blue-grey text-white" title="{{ __('backend.moduleSettings') }}"
                               data-toggle="tooltip">
                                <i class="material-icons">&#xe8b8;</i>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item inline dropdown" title="{{ __('backend.tableColumns') }}"
                        data-toggle="tooltip">
                        <button type="button" class="btn p-x-sm white"
                                data-toggle="dropdown">
                            <i class="material-icons md-24 opacity-8">&#xe3ec;</i>
                        </button>
                        @include("dashboard.topics.list-columns")
                    </li>
                </ul>
            </div>
            <div>
                @include("dashboard.topics.search")
                <form method="POST" action="{{ route("topicsUpdateAll",$WebmasterSection->id) }}" class="dashboard-form">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width: 100%"
                               id="topics_{{ $WebmasterSection->id }}">
                            <thead class="dker">
                            <?php
                            $Cols = Helper::get_webmaster_columns($WebmasterSection);
                            ?>
                            @if(@Auth::user()->permissionsGroup->edit_status)
                                <th style="width:20px;">
                                    <label class="ui-check m-a-0">
                                        <input id="checkAll" type="checkbox"><i></i>
                                    </label>
                                </th>
                            @endif
                            @foreach($Cols as $KEY=>$COL)
                                <th class="{{ ($KEY == "col_title")?"":"text-center" }}"
                                    style="{{ ($KEY == "col_id"||$KEY == "col_status"||$KEY == "col_featured"||$KEY == "col_visits")?"width:80px;":"" }}">{{ @$COL['title'] }}</th>
                            @endforeach
                            <th class="text-center" style="width:60px;">{{ __('backend.options') }}</th>
                            </thead>
                        </table>
                    </div>
                    <footer class="p-x p-b dker">
                        <div class="row">
                            <div class="col-sm-12 hidden-xs">
                                @if(@Auth::user()->permissionsGroup->delete_status)
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
                                @endif
                                @if(@Auth::user()->permissionsGroup->edit_status)
                                    <select name="action" id="action"
                                            class="input-sm form-control w-md inline v-middle c-select"
                                            required>
                                        <option value="">{{ __('backend.bulkAction') }}</option>
                                        @if($WebmasterSection->featured_status)
                                            <optgroup label="{{ __('backend.featured') }}">
                                                <option value="featured">- {{ __('backend.convertTo') }}
                                                    : {{ __('backend.featured') }}</option>
                                                <option value="not_featured">- {{ __('backend.convertTo') }}
                                                    : {{ __('backend.notFeatured') }}</option>
                                            </optgroup>
                                        @endif
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
                                        @if($WebmasterSection->case_status)
                                            @if(@Auth::user()->permissionsGroup->active_status)
                                                <optgroup label="{{ __('backend.active') }}/{{ __('backend.notActive') }}">
                                                    <option value="activate">
                                                        - {{ __('backend.activeSelected') }}</option>
                                                    <option value="block">- {{ __('backend.blockSelected') }}</option>
                                                </optgroup>
                                            @endif
                                        @endif
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
        @foreach($WebmasterSection->customFields->whereIn("type",[6,7,13,17]) as $customField)
            @if($customField->in_statics && in_array($customField->type,[6,7,13,17]))
                <div class="box">
                        <?php
                        $cf_details_var = "details_".@Helper::currentLanguage()->code;
                        $cf_details_var2 = "details_".config('smartend.default_language');
                        if ($customField->$cf_details_var != "") {
                            $cf_details = $customField->$cf_details_var;
                        } else {
                            $cf_details = $customField->$cf_details_var2;
                        }
                        $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                        $heigth = count($cf_details_lines) * 12;
                        if ($heigth < 200) {
                            $heigth = 200;
                        }
                        ?>
                    <div class="chart-area" id="canvas-holder-{{ $customField->id }}"
                         style="overflow: auto;padding: 20px;border-bottom: 1px solid #ddd;">
                        <h6 class="text-muted">{!! $customField->$title_var !!}</h6>
                        <canvas id="chart-area-{{ $customField->id }}" width="920" height="{{ $heigth }}"
                                style="margin: 0 auto"></canvas>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- .modal -->
    <div id="delete-topic" class="modal fade" data-backdrop="true">
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
                    <button type="button" id="topic_delete_btn" row-id=""
                            class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
@endsection
@push("after-scripts")
    <script src="{{ URL::asset('assets/frontend/js/Chart.min.js') }}"></script>
    <script>
        var dynamicColors = function () {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        var randomScalingFactor = function () {
            return Math.round(Math.random() * 100);
        };

        window.onload = function () {
            @foreach($WebmasterSection->customFields->whereIn("type",[6,7,13,17]) as $customField)
            @if($customField->in_statics && in_array($customField->type,[6,7,13,17]))
                <?php
                $cf_details_var = "details_".@Helper::currentLanguage()->code;
                $cf_details_var2 = "details_".config('smartend.default_language');
                if ($customField->$cf_details_var != "") {
                    $cf_details = $customField->$cf_details_var;
                } else {
                    $cf_details = $customField->$cf_details_var2;
                }
                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                ?>
            new Chart(document.getElementById('chart-area-{{ $customField->id }}').getContext('2d'), {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [
                                <?php
                                $line_num = 1;
                                ?>
                                @foreach ($cf_details_lines as $cf_details_line)
                                {{ (@$statics[$customField->id][$line_num] !="")?@$statics[$customField->id][$line_num]:0 }},
                                <?php
                                $line_num++;
                                ?>
                            @endforeach
                        ],
                        backgroundColor: [
                                <?php
                                $line_num = 1;
                                ?>
                            @foreach ($cf_details_lines as $cf_details_line)

                            dynamicColors(),
                                <?php
                                $line_num++;
                                ?>
                            @endforeach
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: [
                            <?php
                            $line_num = 1;
                            ?>
                            @foreach ($cf_details_lines as $cf_details_line)
                        ('{{ $cf_details_line }}').substring(0, 40) + ((('{{ $cf_details_line }}').length > 40) ? '..' : '') + " ( " + '{{ (@$statics[$customField->id][$line_num] !="")?@$statics[$customField->id][$line_num]:0 }}' + ' )',
                            <?php
                            $line_num++;
                            ?>
                        @endforeach
                    ]
                },
                options: {
                    responsive: false,
                    legend: {
                        display: true,
                        position: 'left',
                        labels: {

                            // font size, default is defaultFontSize
                            fontSize: 11,

                            // font color, default is '#fff'
                            fontColor: '#666',

                            // font style, default is defaultFontStyle
                            fontStyle: 'normal',

                            // font family, default is defaultFontFamily
                            fontFamily: "smart4dsTitles, 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                        }
                    }
                }
            });
            @endif
            @endforeach
        };
    </script>


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
        $(document).ready(function () {
            var table_name = "#topics_{{ $WebmasterSection->id }}";
            var dataTable = $(table_name).DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [[10, 20, 30, 50, 75, 100, 200, -1], [10, 20, 30, 50, 75, 100, 200, "All"]],
                "ajax": {
                    "url": "{{ route('topicsList') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (data) {
                        data._token = "{{csrf_token()}}";
                        data.find_q = $('#find_q').val();
                        data.find_date = $('#find_date').val();
                        data.section_id = $('#find_section_id').val();
                        data.league_id = $('#league_id').val();
                        data.team_id = $('#team_id').val();
                        data.match_id = $('#match_id').val();
                        data.created_by = $('#find_created_by').val();
                        data.webmaster_id = '{{ $WebmasterSection->id }}';
                        @foreach($WebmasterSection->customFields->whereNotIn("type",[99]) as $customField)
                            @if($customField->in_search)
                            data.customField_{{ $customField->id }} = $('#customField_{{ $customField->id }}').val();
                        @endif
                        @endforeach
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
                    $('[data-toggle-second="tooltip"]').tooltip({html: true});
                },
                "language": {!! json_encode(__("backend.dataTablesTranslation")) !!},
                "columns": [

                        @if(@Auth::user()->permissionsGroup->edit_status)
                    {
                        "data": "check", "class": "dker", "orderable": false
                    },
                        @endif
                        @foreach($Cols as $KEY=>$COL)
                    {
                        "data": "{{ $KEY }}", "orderable": {{ (@$COL['sortable'])?"true":"false" }}
                            @if(@$COL['custom'])
                        ,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).addClass(rowData['class_{{ $KEY }}']);
                        },
                        @endif
                    },
                        @endforeach
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

            $("#search-btn").on('click', function () {
                dataTable.draw();
            });
            $('#filter_form').submit(function () {
                if ($("#search_submit_stat").val() === "") {
                    dataTable.draw();
                    return false;
                }
            });

            $("#filter_btn").on('click', function () {
                $("#filter_div").slideToggle();
            });
        });


        function DeleteTopic(id) {
            $("#topic_delete_btn").attr("row-id", id);
            $("#delete-topic").modal("show");
        }

        $("#topic_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "GET",
                    url: "<?php echo route("topicsDestroy", ["webmasterId" => $WebmasterSection->id]); ?>/" + row_id,
                    success: function (result) {
                        var obj_result = jQuery.parseJSON(result);
                        if (obj_result.stat == 'success') {
                            $('#topic_delete_btn').html("{!! __('backend.yes') !!}");
                            swal({
                                title: "<span class='text-success'>{{ __("backend.deleteDone") }}</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                            $('#topics_{{ $WebmasterSection->id }}').DataTable().ajax.reload();
                        }
                        $('#delete-topic').modal('hide');
                        $('.modal-backdrop').hide();
                    }
                });
            }
        });

        function print_as(stat) {
            $("#search_submit_stat").val(stat);
            $("#filter_form").attr('action', '{{ route("topicsPrint",$WebmasterSection->id) }}');
            $("#filter_form").attr('target', '_blank');
            $("#filter_form").submit();
            $("#filter_form").attr('action', '{{ route("topics",$WebmasterSection->id) }}');
            $("#search_submit_stat").val("");
            $("#filter_form").attr('target', '');
        }

        $('.table-columns').click(function (e) {
            e.stopPropagation();
        });

    </script>

    <script src="{{ asset("assets/dashboard/js/jquery-ui/jquery-ui.min.js") }}"></script>
    <script>
        $(function () {
            var _sortable = $("#table-columns-list").sortable({
                update: function (event, ui) {
                    $("#columns_position").val($(this).sortable('toArray'));
                }
            });
            $("#columns_position").val(_sortable.sortable("toArray"));
        });
        $("#reset_default_cols").change(function () {
            if (this.checked) {
                $("#table-columns-container").hide();
            } else {
                $("#table-columns-container").show();
            }
        });
    </script>

    @include("dashboard.topics.import.modal")
@endpush
