@extends('dashboard.layouts.master')
@section('title',  __('backend.generalSettings'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/codemirror/lib/codemirror.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/codemirror/theme/eclipse.css') }}" type="text/css"/>
@endpush
@section('content')
    <div class="padding">
        <div class="row-col">
            <div class="col-sm-3 col-lg-2">
                <div class="p-y">
                    <div class="nav-active-border left b-primary">
                        <ul class="nav nav-sm">

                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'styleTab' || Session::get('active_tab') =="") ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-5"
                                   onclick="document.getElementById('active_tab').value='styleTab'"><i
                                        class="material-icons">&#xe41d;</i>
                                    &nbsp; {!!  __('backend.styleSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{ ( Session::get('active_tab') == 'infoTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-1"
                                   onclick="document.getElementById('active_tab').value='infoTab'"><i
                                        class="material-icons">&#xe30c;</i>
                                    &nbsp; {!!  __('backend.siteInfoSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'contactsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-2"
                                   onclick="document.getElementById('active_tab').value='contactsTab'"><i
                                        class="material-icons">&#xe0ba;</i>
                                    &nbsp; {!!  __('backend.siteContactsSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'socialTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-3"
                                   onclick="document.getElementById('active_tab').value='socialTab'"><i
                                        class="material-icons">&#xe80d;</i>
                                    &nbsp; {!!  __('backend.siteSocialSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'whatsappTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-8"
                                   onclick="document.getElementById('active_tab').value='whatsappTab'"><i
                                        class="fab fa-whatsapp"></i>
                                    &nbsp; {!!  __('backend.whatsappChatWidget') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'codeTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-7" id="code_tab_link"
                                   onclick="document.getElementById('active_tab').value='codeTab'"><i
                                        class="material-icons">&#xe86f;</i>
                                    &nbsp; {!!  __('backend.customCode') !!}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'emailTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-6"
                                   onclick="document.getElementById('active_tab').value='emailTab'"><i
                                        class="material-icons">&#xe0be;</i>
                                    &nbsp; {!!  __('backend.emailNotifications') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'statusTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-4"
                                   onclick="document.getElementById('active_tab').value='statusTab'"><i
                                        class="material-icons">&#xe8c6;</i>
                                    &nbsp; {!!  __('backend.siteStatusSettings') !!}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-7 col-lg-10 light lt">
                <form method="POST" action="{{ route("settingsUpdateSiteInfo") }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="active_tab" name="active_tab" value="{{ Session::get('active_tab') }}"/>
                    <div class="tab-content">
                        <button type="submit" class="btn primary m-a pull-right"><i
                                class="material-icons">&#xe31b;</i> {{ __('backend.update') }}</button>

                        @include('dashboard.settings.style')
                        @include('dashboard.settings.general')
                        @include('dashboard.settings.contacts')
                        @include('dashboard.settings.social')
                        @include('dashboard.settings.whatsapp')
                        @include('dashboard.settings.custom')
                        @include('dashboard.settings.notifications')
                        @include('dashboard.settings.site_status')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push("after-scripts")
    <script src="{{ asset('assets/dashboard/js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/addon/hint/show-hint.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/addon/hint/xml-hint.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/addon/hint/html-hint.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/addon/display/autorefresh.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/mode/xml/xml.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/mode/css/css.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#site_status1").click(function () {
                $("#close_msg_div").css("display", "none");
            });
            $("#site_status2").click(function () {
                $("#close_msg_div").css("display", "block");
            });
            editor = CodeMirror.fromTextArea(document.getElementById("css_code"), {
                mode: "text/css",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                lineWrapping: true,
                theme: "eclipse",
                autoRefresh: true
            });
            editor2 = CodeMirror.fromTextArea(document.getElementById("js_code"), {
                mode: "text/html",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                lineWrapping: true,
                theme: "eclipse",
                autoRefresh: true
            });
            editor2 = CodeMirror.fromTextArea(document.getElementById("body_code"), {
                mode: "text/html",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                lineWrapping: true,
                theme: "eclipse",
                autoRefresh: true
            });

        });

    </script>
    <script>
        $(function () {
            let colors = {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#FF0000',
                'default': '#777777',
                'primary': '#337ab7',
                'success': '#5cb85c',
                'info': '#5bc0de',
                'warning': '#f0ad4e',
                'danger': '#d9534f'
            };

            $('#cp1').colorpicker({
                colorSelectors: colors
            });
            $('#cp2').colorpicker({
                colorSelectors: colors
            });
            $('#cp3').colorpicker({
                colorSelectors: colors
            });
            $('#cp4').colorpicker({
                colorSelectors: colors
            });
        });

        function SetThemeColors(clr1, clr2, clr3, clr4) {
            document.getElementById("style_color1").value = clr1;
            $("#cpbg i").css("background-color", clr1);
            document.getElementById("style_color2").value = clr2;
            $("#cpbg2 i").css("background-color", clr2);
            document.getElementById("style_color3").value = clr3;
            $("#cpbg3 i").css("background-color", clr3);
            document.getElementById("style_color4").value = clr4;
            $("#cpbg4 i").css("background-color", clr4);
        }

        function readURL(input, prv) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + prv).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        @foreach(Helper::languagesList() as $ActiveLanguage)
        $("#style_logo_{{ @$ActiveLanguage->code }}").change(function () {
            readURL(this, "style_logo_{{ @$ActiveLanguage->code }}_prv");
        });
        @endforeach

    </script>

    @include('dashboard.layouts.editor')
@endpush
