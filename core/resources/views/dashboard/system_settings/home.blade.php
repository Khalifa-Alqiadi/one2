@extends('dashboard.layouts.master')
@section('title',  __('backend.generalSettings'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style>
        .modal.in .modal-dialog {
            margin-top: 70px;
        }

    </style>
@endpush
@section('content')
    <?php
    $title_var = "title_".@Helper::currentLanguage()->code;
    $title_var2 = "title_".config('smartend.default_language');
    ?>
    <div class="padding">
        <div class="row-col">
            <div class="col-sm-3 col-lg-2">
                <div class="p-y">
                    <div class="nav-active-border left b-primary">
                        <ul class="nav nav-sm">

                            <li class="nav-item">
                                <a class="nav-link block {{ ( Session::get('active_tab') == 'frontSettingsTab' || Session::get('active_tab') =="") ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-5"
                                   onclick="clicked_tab('frontSettingsTab')">
                                    &nbsp; {!!  __('backend.frontSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'languageSettingsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-2"
                                   onclick="clicked_tab('languageSettingsTab')">
                                    &nbsp; {!!  __('backend.languageSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'fileSystemTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-16"
                                   onclick="clicked_tab('fileSystemTab')">
                                    &nbsp; {!!  __('backend.fileSystem') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'SEOSettingTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-3"
                                   onclick="clicked_tab('SEOSettingTab')">
                                    &nbsp; {!!  __('backend.seoTabTitle') !!}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'registrationSettingsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-4"
                                   onclick="clicked_tab('registrationSettingsTab')">
                                    &nbsp; {!!  __('backend.registrationSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'mailSettingsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-7"
                                   onclick="clicked_tab('mailSettingsTab')">
                                    &nbsp; {!!  __('backend.mailSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'googleRecaptchaTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-8"
                                   onclick="clicked_tab('googleRecaptchaTab')">
                                    &nbsp; {!!  __('backend.googleRecaptcha') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'googleTagsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-9"
                                   onclick="clicked_tab('googleTagsTab')">
                                    &nbsp; {!!  __('backend.googleTags') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'googleMapsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-10"
                                   onclick="clicked_tab('googleMapsTab')">
                                    &nbsp; {!!  __('backend.googleMaps') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'analyticsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-11"
                                   onclick="clicked_tab('analyticsTab')">
                                    &nbsp; {!!  __('backend.analyticsSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'newsletterTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-15"
                                   onclick="clicked_tab('newsletterTab')">
                                    &nbsp; {!!  __('backend.newsletterProvider') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'textEditorTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-12"
                                   onclick="clicked_tab('textEditorTab')">
                                    &nbsp; {!!  __('backend.textEditor') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'appsSettingsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-1"
                                   onclick="clicked_tab('appsSettingsTab')">
                                    &nbsp; {!!  __('backend.appsSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'restfulAPITab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-6"
                                   onclick="clicked_tab('restfulAPITab')">
                                    &nbsp; {!!  __('backend.restfulAPI') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'debugMode') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-14"
                                   onclick="clicked_tab('debugMode')">
                                    &nbsp; {!!  __('backend.debugMode') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'systemUpdate') ? 'active' : '' }}"
                                   href="#"
                                   id="systemUpdateLink"
                                   data-toggle="tab" data-target="#tab-13"
                                   onclick="clicked_tab('systemUpdate')">
                                    &nbsp; {!!  __('backend.systemUpdate') !!}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-7 col-lg-10 light lt">
                <form method="POST" action="{{ route("webmasterSettingsUpdate") }}" class="dashboard-form" id="settingsForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="active_tab" name="active_tab" value="{{ Session::get('active_tab') }}"/>
                    <div class="tab-content pos-rlt">

                        <button type="submit" id="save-settings-btn"
                                class="btn primary m-a pull-right"><i
                                class="material-icons">&#xe31b;</i> {{ __('backend.update') }}</button>


                        @include('dashboard.system_settings.front')
                        @include('dashboard.system_settings.language')
                        @include('dashboard.system_settings.files')
                        @include('dashboard.system_settings.seo')
                        @include('dashboard.system_settings.login')
                        @include('dashboard.system_settings.api')
                        @include('dashboard.system_settings.mail')
                        @include('dashboard.system_settings.captcha')
                        @include('dashboard.system_settings.tags')
                        @include('dashboard.system_settings.maps')
                        @include('dashboard.system_settings.analytics')
                        @include('dashboard.system_settings.newsletter')
                        @include('dashboard.system_settings.other')
                        @include('dashboard.system_settings.apps')
                        @include('dashboard.system_settings.debug')
                        @include('dashboard.system_settings.update')
                    </div>
                </form>
                @include('dashboard.system_settings.languages')
            </div>
        </div>
    </div>
@endsection
@push("after-scripts")
    @include('dashboard.layouts.editor')
    <script type="text/javascript">

        $("input:radio[name=homepage_type]").click(function () {
            if (parseInt($(this).val()) === 1) {
                $("#homepage_type0_settings").css("display", "none");
                $("#homepage_type1_settings").css("display", "block");
            } else {
                $("#homepage_type0_settings").css("display", "block");
                $("#homepage_type1_settings").css("display", "none");
            }
        });

        $("input:radio[name=api_status]").click(function () {
            if (parseInt($(this).val()) === 1) {
                $("#api_key_div").css("display", "block");
            } else {
                $("#api_key_div").css("display", "none");
            }
        });
        $("input:radio[name=geoip_status]").click(function () {
            if (parseInt($(this).val()) === 1) {
                $("#geoip_service_info").css("display", "block");
            } else {
                $("#geoip_service_info").css("display", "none");
            }
        });
        $("input:radio[name=newsletter_provider_status]").click(function () {
            if (parseInt($(this).val()) === 1) {
                $("#newsletter_service_info").css("display", "block");
            } else {
                $("#newsletter_service_info").css("display", "none");
            }
        });
        $('#newsletter_provider').on('change', function () {
            if ($(this).val() === "mailcoach") {
                $("#newsletter_endpoint_div").css("display", "block");
            } else {
                $("#newsletter_endpoint_div").css("display", "none");
            }
        });
        $('#mail_driver').on('change', function () {
            if ($(this).val() == "sendmail" || $(this).val() == "") {
                $("#smtp_check").hide();

                $("#mail_host").val('');
                $("#mail_port").val('');
                $("#mail_username").val('');
                $("#mail_encryption").val('');
                $("#mail_password").val('');

                $("#mail_host_div").hide();
                $("#mail_port_div").hide();
                $("#mail_username_div").hide();
                $("#mail_password_div").hide();
                $("#mail_encryption_div").hide();
                $("#send_test").show();
                $("#mail_from_div").show();
                if ($(this).val() == "") {
                    $("#send_test").hide();
                    $("#mail_from_div").hide();
                }

            } else {
                $("#mail_host_div").show();
                $("#mail_port_div").show();
                $("#mail_username_div").show();
                $("#mail_password_div").show();
                $("#mail_encryption_div").show();
                $("#smtp_check").show();
                $("#send_test").show();
                $("#mail_from_div").show();
            }
            if ($(this).val() != "smtp") {
                $("#smtp_check").hide();
            } else {
                $("#smtp_check").show();
            }
        });

        function generate_key() {
            if (!confirm('{!!  __('backend.APIKeyConfirm') !!}')) {
                return false;
            } else {
                $("#api_key").val(Math.floor(Math.random() * 1000000000000000));
            }
        }


        $(document).ready(function () {
            $('#settingsForm').submit(function (evt) {
                let btn = $('#save-settings-btn');
                btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 22px\"/> {!! __('backend.update') !!}");
                setTimeout(
                    function () {
                        btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 22px\"/> {!! __('backend.cashClearing') !!}...");
                    }, 2000);
                btn.prop('disabled', true);
            });

            $("#nocaptcha_status2").click(function () {
                $("#nocaptcha_div").css("display", "none");
            });
            $("#nocaptcha_status1").click(function () {
                $("#nocaptcha_div").css("display", "block");
            });

            $("#google_tags_status2").click(function () {
                $("#google_tags_div").css("display", "none");
            });
            $("#google_tags_status1").click(function () {
                $("#google_tags_div").css("display", "block");
            });

            $("#google_maps_status2").click(function () {
                $("#google_maps_div").css("display", "none");
                $("#google_maps_key").val('');
            });
            $("#google_maps_status1").click(function () {
                $("#google_maps_div").css("display", "block");
            });

            $("#login_facebook_status2").click(function () {
                $("#facebook_ids_div").css("display", "none");
            });
            $("#login_facebook_status1").click(function () {
                $("#facebook_ids_div").css("display", "block");
            });

            $("#login_twitter_status2").click(function () {
                $("#twitter_ids_div").css("display", "none");
            });
            $("#login_twitter_status1").click(function () {
                $("#twitter_ids_div").css("display", "block");
            });

            $("#login_google_status2").click(function () {
                $("#google_ids_div").css("display", "none");
            });
            $("#login_google_status1").click(function () {
                $("#google_ids_div").css("display", "block");
            });

            $("#login_linkedin_status2").click(function () {
                $("#linkedin_ids_div").css("display", "none");
            });
            $("#login_linkedin_status1").click(function () {
                $("#linkedin_ids_div").css("display", "block");
            });

            $("#login_github_status2").click(function () {
                $("#github_ids_div").css("display", "none");
            });
            $("#login_github_status1").click(function () {
                $("#github_ids_div").css("display", "block");
            });

            $("#login_bitbucket_status2").click(function () {
                $("#bitbucket_ids_div").css("display", "none");
            });
            $("#login_bitbucket_status1").click(function () {
                $("#bitbucket_ids_div").css("display", "block");
            });


            $("#instant_index2").click(function () {
                $("#instant_index_settings").css("display", "none");
            });
            $("#instant_index1").click(function () {
                $("#instant_index_settings").css("display", "block");
            });

            $("#file_system1").click(function () {
                $("#local_path_settings").show();
                $("#s3_settings").hide();
            });
            $("#file_system2").click(function () {
                $("#local_path_settings").hide();
                $("#s3_settings").show();
            });

            $("#text_editor_1").click(function () {
                $("#Summernote").show();
                $("#CKEditor").hide();
                $("#TinyMCE").hide();
            });
            $("#text_editor_2").click(function () {
                $("#Summernote").hide();
                $("#CKEditor").show();
                $("#TinyMCE").hide();
            });
            $("#text_editor_3").click(function () {
                $("#Summernote").hide();
                $("#CKEditor").hide();
                $("#TinyMCE").show();
            });
            document.getElementById('timezone').value = '{!! $WebmasterSetting->timezone !!}';

            @if(@$_GET['tab'] == "license")
            $("#systemUpdateLink").trigger("click");
            clicked_tab("systemUpdate");
            @endif

        });
        $(function () {
            $(".backend_path").keypress(function (event) {
                var ew = event.which;
                if (ew == 32)
                    return true;
                if (48 <= ew && ew <= 57)
                    return true;
                if (65 <= ew && ew <= 90)
                    return true;
                if (97 <= ew && ew <= 122)
                    return true;
                return false;
            });
        });

        $('#smtp_check').click(function () {
            if ($("#mail_host").val() != "" && $("#mail_port").val() != "") {
                $('#smtp_check').html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.smtpCheck') !!}");
                $('#smtp_check').prop('disabled', true);
                $('#mail_save_btn').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: "<?php echo route("mailSMTPCheck"); ?>",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "mail_driver": $("#mail_driver").val(),
                        "mail_host": $("#mail_host").val(),
                        "mail_port": $("#mail_port").val(),
                        "mail_username": $("#mail_username").val(),
                        "mail_password": $("#mail_password").val(),
                        "mail_encryption": $("#mail_encryption").val(),
                    },
                    success: function (result) {
                        var obj_result = jQuery.parseJSON(result);
                        if (obj_result.stat == 'success') {
                            swal({
                                title: "<span class='text-success'>{{ __("backend.smtpCheckSuccess") }}</span>",
                                text: "{{ __("backend.smtpCheckSuccessMsg") }}",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        } else {
                            swal({
                                title: "<span class='text-danger'>{{ __("backend.smtpCheck") }}</span>",
                                text: "<span class='text-danger' dir='ltr'>" + obj_result.error + "</span>",
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                            });
                        }
                        $('#smtp_check').html("<i class=\"fa fa-bolt\"></i> {!! __('backend.smtpCheck') !!}");
                        $('#smtp_check').prop('disabled', false);
                        $('#mail_save_btn').prop('disabled', false);
                    }
                });
            }
        });
        $('#send_test').click(function () {
            swal({
                title: "{{ __("backend.sendTestMail") }}",
                text: "{{ __("backend.sendTestMailTo") }}",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "email@site.com",
                inputValue: $("#to_email").val(),
                confirmButtonText: "{{ __("backend.continue") }}",
                cancelButtonText: "{{ __("backend.cancel") }}",
                showLoaderOnConfirm: true,
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("{{ __("backend.sendTestMailTo") }}");
                    return false
                }
                if (!validateEmail(inputValue)) {
                    swal.showInputError("{{ __("backend.sendTestMailError") }}");
                    return false
                }
                $("#to_email").val(inputValue);
                var xhr = $.ajax({
                    type: "POST",
                    url: "<?php echo route("mailTest"); ?>",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "mail_driver": $("#mail_driver").val(),
                        "mail_host": $("#mail_host").val(),
                        "mail_port": $("#mail_port").val(),
                        "mail_username": $("#mail_username").val(),
                        "mail_password": $("#mail_password").val(),
                        "mail_encryption": $("#mail_encryption").val(),
                        "mail_no_replay": $("#mail_no_replay").val(),
                        "mail_test": $("#to_email").val(),
                    },
                    success: function (result) {
                        var obj_result = jQuery.parseJSON(result);
                        if (obj_result.stat == 'success') {
                            swal({
                                title: "<span class='text-success'>{{ __("backend.mailTestSuccess") }}</span>",
                                text: inputValue,
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        } else {
                            swal({
                                title: "<span class='text-danger'>{{ __("backend.mailTestFailed") }}</span>",
                                text: inputValue,
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                            });
                        }
                    }
                });
            });
        });

        function validateEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        // update progress bar
        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                $('progress').attr({value: e.loaded, max: e.total});
                // reset progress on complete
                if (e.loaded == e.total) {
                    $('progress').attr('value', '0.0');
                }
            }
        }

        function clicked_tab(tab) {
            $("#active_tab").val(tab);
            if (tab === "systemUpdate") {
                $("#save-settings-btn").hide();
                check_for_updates();
            } else {
                $("#save-settings-btn").show();
            }
        }

        $('#purchase_btn').click(function () {
            var purchase_code = $("#purchase_code").val();
            if (purchase_code !== "") {
                $("#purchase_btn").html('<img src="{{ asset('assets/dashboard/images/loading.gif') }}" style="height: 20px;"/> {{ __('backend.activateNow') }}');
                $("#purchase_btn").prop('disabled', true);
                var xhr = $.ajax({
                    type: "POST",
                    url: "<?php echo route("licenseCheck"); ?>",
                    data: {
                        _token: "{{csrf_token()}}",
                        purchase_code: purchase_code,
                        action: "check_license",
                    },
                    success: function (result) {
                        if (result.status === 'success') {
                            swal({
                                title: "<span class='text-success'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            }, function () {
                                location.reload();
                            });
                        } else {
                            $("#purchase_btn").html('<i class="material-icons">&#xe31b;</i> {{ __('backend.activateNow') }}');
                            $("#purchase_btn").prop('disabled', false);
                            swal({
                                title: "<span class='text-danger'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                            });
                        }
                    }
                });
                console.log(xhr);
            } else {
                swal({
                    title: "<span class='text-danger'>{{ __("backend.purchaseCodeIsRequired") }}</span>",
                    text: "",
                    html: true,
                    type: "error",
                    confirmButtonText: "{{ __("backend.close") }}",
                    confirmButtonColor: "#acacac",
                });
            }
        });
        @if(Helper::GeneralWebmasterSettings("license") && Helper::GeneralWebmasterSettings("purchase_code")!="")
        function check_for_updates(action = "check_updates", load_msg = '{{ __("backend.loading") }}') {

            if (action === "install_updates") {
                if (!$('.agreement:checked').val()) {
                    $(".agreement_text").css("color", "red");
                    return false;
                }
            }
            $("#system_updates").html("<div class=\"b-a p-a-2 white dk\"><img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 35px;vertical-align: bottom;\"/> <span class='text-lg text-muted'>" + load_msg + "..</div></div>");
            var xhr = $.ajax({
                type: "POST",
                url: "<?php echo route("licenseCheck"); ?>",
                data: {
                    _token: "{{csrf_token()}}",
                    action: action,
                },
                success: function (result) {
                    if (result.status === 'success') {
                        $("#system_updates").html("<div class='b-a p-a-2 white dk' dir='ltr'><h4 class='m-b-0'>Smartend CMS <strong>" + result.version + "</strong></h4><div class='text-muted'> <i class='fa fa-calendar'></i> " + result.date + "</div><div class='m-t'>" + result.change_log + "</div><div class='m-t'><label class='md-check'><input type='checkbox' class='agreement has-value' value='1'><i class='green'></i><span class='agreement_text'>I agree to update and overwrite system files.<span></label></div><button type='button' class='btn btn-lg success m-t-sm' onclick=\"check_for_updates('install_updates','{{ __("backend.updateInProgress") }}')\">Update Now</button></div>");
                    } else if (result.status === 'upgrade') {
                        $("#system_updates").html("<div class='b-a p-a-2 white dk' dir='ltr'><h4 class='m-b-0'>Smartend CMS <strong>" + result.version + "</strong></h4><div class='text-muted'> <i class='fa fa-calendar'></i> " + result.date + "</div><div class='m-t'>" + result.change_log + "</div><div class=' m-t b-a b-danger p-a'><h5 class='text-danger m-b-sm'>You need to upgrade your PHP version to >= " + result.php + "</h5>Current PHP version is {{ phpversion() }}</div></div>");
                    } else if (result.status === 'upto_date' || result.status === 'updated') {
                        $("#system_updates").html("<div class='b-a p-a-2 white dk'><h4>Smartend CMS <strong>" + result.version + "</strong></h4><h5 class='text-success m-t' style='line-height: 30px'>" + result.msg + "</h5></div>");
                        if (result.status === 'updated') {
                            swal({
                                title: "<span class='text-success'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            }, function () {
                                location.href = '{{ route("webmasterSettings") }}?tab=license';
                            });
                        }
                    } else if (result.status === 'blocked') {
                        $("#system_updates").html("<div class='b-a m-t-sm p-a-2 text-danger'><h4><i class='fa fa-times-circle'></i> " + result.msg + "</h4><button type='button' onclick='re_acivate()' class='btn btn-sm success m-t-sm'>Use another purchase code </button></div>");

                    } else {
                        $("#system_updates").html("<div class='b-a m-t-sm p-a-2 text-danger'><h4 class='m-t'><i class='fa fa-times-circle'></i> " + result.msg + "</h4></div>");
                    }
                }
            });
            console.log(xhr);
        }

        function re_acivate() {
            $.ajax({
                type: "POST",
                url: "<?php echo route("licenseCheck"); ?>",
                data: {
                    _token: "{{csrf_token()}}",
                    action: "re_activate",
                },
                success: function () {
                    location.href = '{{ route("webmasterSettings") }}?tab=license';
                }
            });
        }
        @else
        function check_for_updates() {

        }
        @endif
    </script>
@endpush
