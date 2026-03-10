@if(@Auth::user()->permissionsGroup->add_status)
    <div id="record-import" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route("topicsImport") }}" class="dashboard-form" enctype="multipart/form-data" id="record-import-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i
                                class="material-icons">&#xe2c6;</i> <span
                                class="modal-box-title">{!! __('backend.importData') !!}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-a-0">
                        <div class="form-fields"></div>

                        <div id="fields_import_process" class="displayNone"></div>
                        <div id="fields_import_report" class="m-a b-a light p-a displayNone">
                            <div class="p-a-2">
                                <div class="row">
                                    <div class="col-sm-6 text-center text-success">
                                        <h3 id="report_success_count">0</h3>
                                        <h5> {{ __("backend.importedCount") }}</h5>
                                    </div>
                                    <div class="col-sm-6 text-center text-danger">
                                        <h3 id="report_failed_count">0</h3>
                                        <h5> {{ __("backend.notImportedCount") }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="record-import-form-errors" class="alert alert-danger text-left displayNone">
                            <ul></ul>
                        </div>
                        <input type="hidden" name="section_id" value="{{ encrypt(@$WebmasterSection->id) }}"/>
                        <button type="button" class="btn dark-white p-x-md"
                                data-dismiss="modal">{!! __('backend.cancel') !!}
                        </button>
                        <button type="submit" id="record-import-form-submit" class="btn info p-x-md pull-left"><i
                                class="material-icons">&#xe31b;</i> {!! __('backend.continue') !!}
                        </button>
                    </div>
                    <input type="hidden" name="from_row" id="from_row" value="0">
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#import_btn").click(function () {
            $("#record-import").modal("show");
            $("#record-import .form-fields").show();
            $("#record-import .modal-footer").show();
            $("#fields_import_process").hide();
            $("#fields_import_report").hide();
            $("#report_success_count").text("0");
            $("#report_failed_count").text("0");
            $("#from_row").val("0");
            $("#record-import .form-fields").html("<div class=\"text-center p-a-2\"><img class=\"m-b-1\" src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;\"/><div class='text-muted small'>{{ __("backend.loading") }}</div></div>");
            $("#record-import-form-errors").hide();
            $("#record-import-form-submit").prop('disabled', true);
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("topicsImport"); ?>",
                data: {
                    _token: "{{csrf_token()}}",
                    section_id: '{{ encrypt(@$WebmasterSection->id) }}',
                    step: 0,
                },
                success: function (data) {
                    $("#record-import-form-submit").prop('disabled', false);
                    $("#record-import .form-fields").html(data);
                }
            });
            console.log(xhr);
        });

        function send_next_request() {
            var formData = new FormData(document.getElementById('record-import-form'));
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("topicsImport"); ?>",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.stat === 'success') {
                        if (result.data.finished) {
                            update_report(result.data);
                            $("#fields_import_process").hide();
                            $("#record-import .modal-footer").hide();
                            $('#topics_{{ $WebmasterSection->id }}').DataTable().ajax.reload();
                            $(".chart-area").remove();
                        } else {
                            $("#from_row").val(result.data.from_row);
                            update_report(result.data);
                            send_next_request();
                        }
                    }
                    $("#record-import .f_icon2").html("<i class=\"material-icons\">&#xe86c;</i>");

                    $('#record-import-form-submit').html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.continue') !!}");
                    $('#record-import-form-submit').prop('disabled', false);
                }
            });
            console.log(xhr);
            return false;
        }

        function update_report(Data) {
            var suc = parseInt($("#report_success_count").text());
            suc = suc + Data.success_count;
            $("#report_success_count").text(suc);

            var fal = parseInt($("#report_failed_count").text());
            fal = fal + Data.failed_count;
            $("#report_failed_count").text(fal);
        }

        $('#record-import-form').submit(function (evt) {
            evt.preventDefault();
            $('#record-import-form-submit').html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.add') !!}");
            $('#record-import-form-submit').prop('disabled', true);
            var formData = new FormData(this);
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("topicsImport"); ?>",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    $("#record-import-form-errors").find("ul").html('');
                    $('#record-import-form-submit').html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.continue') !!}");
                    $('#record-import-form-submit').prop('disabled', false);
                    if (result.stat === 'success') {
                        if (result.step === 2) {
                            //$('#topics_{{ $WebmasterSection->id }}').DataTable().ajax.reload();
                            //$(".chart-area").remove();
                            $("#record-import .form-fields").html(result.html);
                        } else {
                            $("#record-import .form-fields").hide();
                            $("#fields_import_process").show();
                            $("#fields_import_report").show();

                            $("#record-import .final_step").removeClass("grey-300");
                            $("#record-import .final_step").addClass("primary");

                            $("#fields_import_process").html("<div class='p-a m-b text-center h6'><img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.importLoading') !!}</div>");

                            if (parseInt(result.data.finished)) {
                                update_report(result.data);
                                $("#fields_import_process").hide();
                                $("#record-import .modal-footer").hide();
                                $('#topics_{{ $WebmasterSection->id }}').DataTable().ajax.reload();
                                $(".chart-area").remove();
                            } else {
                                $("#from_row").val(result.data.from_row);
                                update_report(result.data);
                                send_next_request();
                            }
                        }
                    } else {
                        $("#record-import-form-errors").css('display', 'block');
                        $.each(result.error, function (key, value) {
                            $("#record-import-form-errors").find("ul").append('<li>' + value + '</li>');
                        });
                    }
                }
            });
            console.log(xhr);
            return false;
        });
    </script>
@endif
