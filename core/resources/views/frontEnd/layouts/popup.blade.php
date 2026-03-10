@if(Helper::GeneralWebmasterSettings("popups_status"))
    @if(!empty($Popup))
        <?php

        $title_var = "title_" . @Helper::currentLanguage()->code;
        $title_var2 = "title_" . config('smartend.default_language');
        $details_var = "details_" . @Helper::currentLanguage()->code;
        $details_var2 = "details_" . config('smartend.default_language');

        if ($Popup->$title_var != "") {
            $popup_title = $Popup->$title_var;
        } else {
            $popup_title = $Popup->$title_var2;
        }
        if ($Popup->$details_var != "") {
            $popup_details = $Popup->$details_var;
        } else {
            $popup_details = $Popup->$details_var2;
        }

        $PopupSettings = [];
        if ($Popup->settings != "") {
            try {
                $PopupSettings = json_decode($Popup->settings);
            } catch (Exception $e) {

            }
        }
        $last_view_date = \Cookie::get('page-popup-' . $Popup->id . '-last-view-date');
        $show_popup = true;
        if ($last_view_date > 0) {
            $show_popup = false;
            if (@$PopupSettings->period > 0) {
                $last_view_date_plus = strtotime("$last_view_date +" . @$PopupSettings->period . " days");
                if ($last_view_date_plus <= time()) {
                    $show_popup = true;
                }
            } elseif (@$PopupSettings->period == -1) {
                $show_popup = true;
            }
        }
        ?>
        @if($show_popup)
            <?php
            \Cookie::queue("page-popup-" . $Popup->id . "-last-view-date", time(), 31104000);
            ?>
            <div class="modal fade" id="page-popup-{{ $Popup->id }}"
                 data-bs-backdrop="static" data-bs-keyboard="false"
                 tabindex="-1">
                <div class="page-popup modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            @if(@$PopupSettings->closable)
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            @endif
                            @if(@$Popup->photo !="" && @$PopupSettings->photo_position)
                                <div class="row">
                                    <div class="col-sm-4 image-side">
                                        <img src="{{ route("fileView",["path" =>'banners/'.$Popup->photo]) }}"
                                             alt="{{ $popup_title }}">
                                    </div>
                                    <div class="col-sm-8 content-side">
                                        <div class="content-body">
                                            @if(strip_tags($popup_details) !="")
                                            {!! $popup_details !!}
                                            @endif
                                            {!! $Popup->code !!}
                                                @if(@$Popup->form_id == -1)
                                                    @include('frontEnd.layouts.popup_subscribe',["PopupID"=>@$Popup->id])
                                                @elseif(@$Popup->form_id >0)
                                                @include('frontEnd.form',["FormSectionID"=>@$Popup->form_id])
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="content-body">
                                    @if(strip_tags($popup_details) !="")
                                        {!! $popup_details !!}
                                    @endif
                                    {!! $Popup->code !!}
                                    @if(@$Popup->form_id == -1)
                                            @include('frontEnd.layouts.popup_subscribe',["PopupID"=>@$Popup->id])
                                    @elseif(@$Popup->form_id >0)
                                        @include('frontEnd.form',["FormSectionID"=>@$Popup->form_id])
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @push('after-styles')
                <style>
                    #page-popup-{{ $Popup->id }} .content-body {
                        padding: 30px;
                    }

                    #page-popup-{{ $Popup->id }} .btn-close {
                        position: absolute;
                        right: 12px;
                        top: 12px;
                        border-radius: 50%;
                        padding: 10px;
                        z-index: 9999999;
                        border: 1px solid #000;
                    }

                    #page-popup-{{ $Popup->id }} .modal-content {
                        border-radius: 25px;
                        overflow: hidden;
                        background-color: {{ @$PopupSettings->background_color }};
                        border: none;
                        box-shadow: 0 2px 25px rgba(0, 0, 0, .3);
                        @if(@$Popup->photo !="" && !@$PopupSettings->photo_position)
                        background-size: cover;
                        background-repeat: no-repeat;
                        background-position: center;
                        background-image: url('{{ route("fileView",["path" =>'banners/'.$Popup->photo]) }}');
                        @endif
                    }
                    @if(@$Popup->photo =="" || @$PopupSettings->photo_position)
                    .dark .page-popup .modal-content{
                        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.8) }} !important;
                    }
                    @endif

                    #page-popup-{{ $Popup->id }} .modal-body {
                        padding: 0;
                    }

                    #page-popup-{{ $Popup->id }} .btn {
                        white-space: nowrap !important;
                    }
                    .modal-backdrop.show {
                        opacity: {{ round(@$PopupSettings->backdrop_opacity/100,2) }} !important;
                    }

                    #page-popup-{{ $Popup->id }} .form-block .bottom-article {
                        background: none !important;
                        border: none !important;
                        padding: 0 !important;
                    }

                    @if(@$Popup->photo !="" && @$PopupSettings->photo_position)
                     #page-popup-{{ $Popup->id }} .bottom-article .row {
                     margin: 0;
                    }
                    #page-popup-{{ $Popup->id }} .bottom-article .col-lg-4,#page-popup-{{ $Popup->id }} .bottom-article .col-sm-12 {
                        width: 100% !important;
                        padding: 5px;
                    }
                    #page-popup-{{ $Popup->id }} .bottom-article .submit-btn {
                        margin: 5px;
                    }
                    @endif
                    #page-popup-{{ $Popup->id }} .form-title {
                        display: none;
                    }
                    @media (min-width: 576px) {
                        #page-popup-{{ $Popup->id }} .modal-dialog {
                            max-width: {{ @$PopupSettings->width."px" }} !important;
                        }

                        #page-popup-{{ $Popup->id }} .modal-body {
                            @if(@$PopupSettings->height)
                        height: {{ @$PopupSettings->height."px" }}  !important;
                        @endif

                        }
                    }

                </style>
            @endpush
            @push('after-scripts')
                <script type="text/javascript">
                    $(document).ready(function () {
                        @if(@$PopupSettings->delay >0)
                        setTimeout(
                            function () {
                                $("#page-popup-{{ $Popup->id }}").modal("show");
                            }, parseInt("{{ (@$PopupSettings->delay*1000) }}")
                        );
                        @else
                        $("#page-popup-{{ $Popup->id }}").modal("show");
                        @endif
                    });
                </script>
            @endpush

        @endif
    @endif
@endif
