@if($WebmasterSection->type == 10)
    <div class="tab-pane  {{ $tab_0 }}" id="tab_landing">
            <?php
            $title_var = "title_".@Helper::currentLanguage()->code;
            ?>
        <div class="box-body p-a-2">
            <div class="row m-b">
                <div class="col-sm-8 col-xs-12">
                    <h4 class="m-b-sm">
                        {{ $Topic->$title_var }}
                    </h4>
                    <div class="m-b-sm">
                        {{ __("backend.createdAt") }}: <strong>{!! Helper::formatDate($Topic->created_at)." ".date("h:i A",
                                strtotime($Topic->created_at)) !!}</strong> &nbsp;|&nbsp; {{ __("backend.visits") }}:
                        <strong>{{ $Topic->visits }}</strong>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12 text-right">
                    @if (@Auth::user()->permissionsGroup->add_status)
                        <div class="dropdown inline m-b  w-full-sm">
                            <button class="btn primary dropdown-toggle w-full-sm" data-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons md-24">&#xe145;</i> {{ __('backend.addNewBlock') }}</button>
                            <div class="dropdown-menu pull-right w-full-sm">
                                <a class="dropdown-item h6" onclick="CreateTopicBlock('dynamic',this.innerHTML)"><i class="material-icons m-r-xs">&#xe051;</i> {{ __("backend.dynamicContent") }}
                                </a>
                                <a class="dropdown-item h6" onclick="CreateTopicBlock('static',this.innerHTML)"><i class="material-icons m-r-xs">&#xe86d;</i> {{ __("backend.staticContent") }}
                                </a>
                                <a class="dropdown-item h6" onclick="CreateTopicBlock('banners',this.innerHTML)"><i class="material-icons m-r-xs">&#xe8f7;</i> {{ __("backend.bannersContent") }}
                                </a>
                                <a class="dropdown-item h6" onclick="CreateTopicBlock('form',this.innerHTML)"><i class="material-icons m-r-xs">&#xe85d;</i> {{ __("backend.customForm") }}
                                </a>
                                <a class="dropdown-item h6" onclick="CreateTopicBlock('code',this.innerHTML)"><i class="material-icons m-r-xs">&#xe86f;</i> {{ __("backend.customCode") }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route("topicBlocksUpdateAll") }}" class="dashboard-form" id="topic-blocks-list-form">
                @csrf
                <input type="hidden" name="topic_id" class="form-control" value="{{ encrypt($Topic->id) }}">
                <div id="topic-blocks-list"></div>
                <div class="row m-t">
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
                        <select name="action" id="action"
                                class="input-sm form-control w-md inline v-middle c-select"
                                required>
                            <option value="">{{ __('backend.bulkAction') }}</option>
                            <option value="order">{{ __('backend.saveOrder') }}</option>
                            <option value="activate">{{ __('backend.activeSelected') }}</option>
                            <option value="block">{{ __('backend.blockSelected') }}</option>
                            @if(@Auth::user()->permissionsGroup->delete_status)
                                <option value="delete">{{ __('backend.deleteSelected') }}</option>
                            @endif
                        </select>
                        <button type="submit" id="topic-blocks-list-form-submit"
                                class="btn white">{{ __('backend.apply') }}</button>
                        <button id="submit_show_msg" class="btn white" data-toggle="modal"
                                style="display: none"
                                data-target="#m-all" ui-toggle-class="bounce"
                                ui-target="#animate">{{ __('backend.apply') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    @if(@Auth::user()->permissionsGroup->add_status)
        <div id="create-topic-block" class="modal fade" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form method="POST" action="{{ route('topicBlocksStore') }}" class="dashboard-form" id="create-topic-block-form" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title"><i
                                    class="material-icons">&#xe02e;</i> <span
                                    class="modal-box-title">{!! __('backend.addNewBlock') !!}</span>
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
                            <button type="submit" id="create-topic-block-form-submit" class="btn info p-x-md"><i
                                    class="material-icons">&#xe31b;</i> {!! __('backend.add') !!}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="clone-topic-block" class="modal fade" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" id="animate">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                    </div>
                    <div class="modal-body text-center p-lg">
                        <h5 class="m-b-0">
                            {{ __('backend.cloneRecordConfirmation') }}
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md"
                                data-dismiss="modal">{{ __('backend.cancel') }}</button>
                        <button type="button" id="topic_block_clone_btn" row-id="" class="btn info p-x-md">{{ __('backend.clone') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
    @endif
    @if(@Auth::user()->permissionsGroup->delete_status)
        <div id="delete-topic-block" class="modal fade" data-backdrop="static" data-keyboard="false">
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
                                data-dismiss="modal">{{ __('backend.cancel') }}</button>
                        <button type="button" id="topic_block_delete_btn" row-id="" class="btn danger p-x-md">{{ __('backend.delete') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
    @endif
@endif

@push("after-scripts")
    <script type="text/javascript">
        let table_name = "#topic-blocks-list";
        let active_content_type = "";
        $("#checkAllBlocks").click(function () {
            $(table_name + ' input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function () {
            if (this.value === "delete") {
                $("#topic-blocks-list-form-submit").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#topic-blocks-list-form-submit").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });
        $(document).ready(function () {
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
                    url: "<?php echo route("topicBlocksUpdateAll"); ?>",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
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
                        $(frm)[0].reset();
                        $('#submit_all').html("{!! __('backend.apply') !!}");
                        $('#submit_show_msg').html("{!! __('backend.apply') !!}");
                        $('#submit_all').prop('disabled', false);
                        $('#submit_show_msg').prop('disabled', false);
                    }
                });
                return false;
            });
            LoadTopicBlocks();
        });

        function LoadTopicBlocks() {
            $(table_name).html("<div class=\"text-center p-a-2\"><img class=\"m-b-sm\" src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;\"/><br>{{ __("backend.loading") }}..</div>");
            $.ajax({
                type: "POST",
                url: "<?php echo route("topicBlocksList"); ?>",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "topic_id": "{{ encrypt($Topic->id) }}",
                },
                success: function (result) {
                    $(table_name).html(result);
                    $('[data-toggle="tooltip"]').tooltip({html: true});
                }
            });
        }

        function CreateTopicBlock(content_type = "code", content_title = "") {
            active_content_type = content_type;
            $("#create-topic-block").modal("show");
            $("#create-topic-block .modal-body").html("<div class=\"text-center p-a\"><img class=\"m-b-xs\" src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;\"/><br>{{ __("backend.loading") }}..</div>");
            $("#create-topic-block .modal-title").html(content_title);
            let btn = $('#create-topic-block-form-submit');
            btn.html("{!! __('backend.add') !!}");
            btn.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "<?php echo route("topicBlocksCreate"); ?>",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "topic_id": "{{ encrypt($Topic->id) }}",
                    "content_type": content_type,
                },
                success: function (result) {
                    btn.prop('disabled', false);
                    $("#create-topic-block .modal-body").html(result);
                    $.fn.modal.Constructor.prototype._enforceFocus = function () {
                    };
                }
            });
        }

        function UpdateTopicBlock(id) {
            $("#create-topic-block").modal("show");
            $("#create-topic-block .modal-body").html("<div class=\"text-center p-a\"><img class=\"m-b-xs\" src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;\"/><br>{{ __("backend.loading") }}..</div>");
            $("#create-topic-block .modal-title").html('<i class=\'material-icons m-r-xs\'>&#xe150;</i>  {{ __("backend.blockUpdate") }}');
            let btn = $('#create-topic-block-form-submit');
            btn.html("{!! __('backend.save') !!}");
            btn.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "<?php echo route("topicBlocksEdit"); ?>",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "topic_id": "{{ encrypt($Topic->id) }}",
                    "block_id": id,
                },
                success: function (result) {
                    btn.prop('disabled', false);
                    $("#create-topic-block .modal-body").html(result);
                    $.fn.modal.Constructor.prototype._enforceFocus = function () {
                    };
                }
            });
            return false;
        }

        function DeleteTopicBlock(id) {
            $("#topic_block_delete_btn").attr("row-id", id);
            $("#delete-topic-block").modal("show");
        }

        function CloneTopicBlock(id) {
            $("#topic_block_clone_btn").attr("row-id", id);
            $("#clone-topic-block").modal("show");
        }

        $("#topic_block_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.delete') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id !== "") {
                $.ajax({
                    type: "POST",
                    url: "<?php echo route("topicBlocksDestroy"); ?>",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "topic_id": "{{ encrypt($Topic->id) }}",
                        "block_id": row_id,
                    },
                    success: function (result) {
                        $('#delete-topic-block').modal('hide');
                        $('.modal-backdrop').hide();
                        if (result.stat === 'success') {
                            $('#topic_block_delete_btn').html("{!! __('backend.delete') !!}");
                            swal({
                                title: "<span class='text-success'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 50000,
                            });
                            LoadTopicBlocks();
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
                    }
                });
            }
        });
        $("#topic_block_clone_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.clone') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id !== "") {
                $.ajax({
                    type: "POST",
                    url: "<?php echo route("topicBlocksClone"); ?>",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "topic_id": "{{ encrypt($Topic->id) }}",
                        "block_id": row_id,
                    },
                    success: function (result) {
                        $('#clone-topic-block').modal('hide');
                        $('.modal-backdrop').hide();
                        if (result.stat === 'success') {
                            $('#topic_block_clone_btn').html("{!! __('backend.clone') !!}");
                            swal({
                                title: "<span class='text-success'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 50000,
                            });
                            LoadTopicBlocks();
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
                    }
                });
            }
        });

        function moreBtnStatusSettingsView(st = 0) {
            if (parseInt(st)) {
                $("#more_btn_status_settings").show();
            } else {
                $("#more_btn_status_settings").hide();
            }
        }

        $('#create-topic-block-form').submit(function (evt) {
            evt.preventDefault();
            let btn = $('#create-topic-block-form-submit');
            btn.html("<img src=\"{{ URL::to('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.add') !!}");
            btn.prop('disabled', true);
            if (active_content_type === "static") {
                @if(Helper::GeneralWebmasterSettings("text_editor") ==2)
                    for (let instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
                @endif
            }

            $('.code-editor').each(function () {
                // Get the CodeMirror instance associated with this textarea
                var editor = $(this).next('.CodeMirror').get(0).CodeMirror;
                if (editor) {
                    editor.save();
                }
            });

            var formData = new FormData(this);
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("topicBlocksStore"); ?>",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    btn.html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.add') !!}");
                    btn.prop('disabled', false);
                    if (result.stat === 'success') {
                        swal({
                            title: "<span class='text-success'>" + result.msg + "</span>",
                            text: "",
                            html: true,
                            type: "success",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                            timer: 5000,
                        });
                        LoadTopicBlocks();
                        $('#create-topic-block').modal('hide');
                        $('.modal-backdrop').hide();
                        $("#create-topic-block-form")[0].reset();
                        if (active_content_type === "static") {
                            @if(Helper::GeneralWebmasterSettings("text_editor") ==2)
                            location.reload()
                            @endif
                        }
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
                }
            });
            //console.log(xhr);
            return false;
        });
        $('#topic-blocks-list-form').submit(function (evt) {
            evt.preventDefault();
            let btn = $('#topic-blocks-list-form-submit');
            btn.html("<img src=\"{{ URL::to('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.apply') !!}");
            btn.prop('disabled', true);
            var formData = new FormData(this);
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("topicBlocksUpdateAll"); ?>",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    btn.html("{!! __('backend.apply') !!}");
                    btn.prop('disabled', false);
                    $('#m-all').modal('hide');
                    $('.modal-backdrop').hide();
                    if (result.stat === 'success') {
                        swal({
                            title: "<span class='text-success'>" + result.msg + "</span>",
                            text: "",
                            html: true,
                            type: "success",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                            timer: 5000,
                        });
                        LoadTopicBlocks();
                        $("#topic-blocks-list-form")[0].reset();
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
                }
            });
            //console.log(xhr);
            return false;
        });
    </script>
@endpush
