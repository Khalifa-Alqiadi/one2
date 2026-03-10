@extends('frontEnd.layouts.master')
@push('after-styles')
    <link rel="stylesheet"
          href="{{ URL::asset('assets/frontend/css/colors.css') }}?v={{ Helper::system_version() }}"/>
@endpush
@section('content')
    <div>
        <?php
        $cf_title_var = "title_".@Helper::currentLanguage()->code;
        $cf_title_var2 = "title_".config('smartend.default_language');
        $title_var = "title_".@Helper::currentLanguage()->code;
        $title_var2 = "title_".config('smartend.default_language');

        $webmaster_section_title = "";
        $category_title = "";
        $page_title = "";
        $category_image = "";

        $custom_css_code = @$WebmasterSection->css_code;
        $custom_js_code = @$WebmasterSection->js_code;
        $custom_body_code = @$WebmasterSection->body_code;

        if (@$WebmasterSection != "none") {
            if (@$WebmasterSection->$title_var != "") {
                $webmaster_section_title = @$WebmasterSection->$title_var;
            } else {
                $webmaster_section_title = @$WebmasterSection->$title_var2;
            }
            $page_title = $webmaster_section_title;
            if (@$WebmasterSection->photo != "") {
                $category_image = route("fileView", ["path" => 'topics/'.@$WebmasterSection->photo]);
            }
        }
        if (@$CurrentCategory != "none") {
            if (!empty($CurrentCategory)) {
                if (@$CurrentCategory->$title_var != "") {
                    $category_title = @$CurrentCategory->$title_var;
                } else {
                    $category_title = @$CurrentCategory->$title_var2;
                }
                $page_title = $category_title;
                if (@$CurrentCategory->photo != "") {
                    $category_image = route("fileView", ["path" => 'sections/'.@$CurrentCategory->photo]);
                }

                $custom_css_code .= @$CurrentCategory->css_code;
                $custom_js_code .= @$CurrentCategory->js_code;
                $custom_body_code .= @$CurrentCategory->body_code;
            }
        }
        ?>
        @if($category_image !="")
            @include("frontEnd.topic.cover")
        @endif
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>{{ $page_title }}</h2>
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __("backend.home") }}</a></li>
                        @if($webmaster_section_title !="")
                            <li class="active">{!! $webmaster_section_title !!}</li>
                        @elseif(@$search_word!="")
                            <li class="active">{{ @$search_word }}</li>
                        @else
                            <li class="active">{{ $User->name }}</li>
                        @endif
                        @if($category_title !="")
                            <li class="active">{{ $category_title }}
                            </li>
                        @endif
                    </ol>
                </div>

            </div>
        </section>
        <section id="content">
            <div class="container">
                @include("frontEnd.layouts.search")
                <div class="row">
                    <div class="col-lg-{{(count($Categories)>0)? "9":"12"}}">
                        @if($Topics->total() == 0)
                            <div class="p-5 text-center no-data">
                                <i class="fa fa-desktop fa-5x opacity-50"></i>
                                <h5 class="mt-3 text-muted">{{ __('frontend.noData') }}</h5>
                            </div>
                        @else
                            @if($Topics->total() > 0)
                                <div>
                                    <table class="table table-bordered">
                                        <thead>
                                        @if(@$WebmasterSection->no_status)
                                            <th class="text-center light">ID</th>
                                        @endif
                                        @if($WebmasterSection->title_status)
                                            <th class="text-left light">{{ __('backend.topicName') }}</th>
                                        @endif
                                        @foreach($WebmasterSection->customFields as $customField)
                                                <?php
                                                // check permission
                                                $view_permission_groups = [];
                                                if ($customField->view_permission_groups != "") {
                                                    $view_permission_groups = explode(",",
                                                        $customField->view_permission_groups);
                                                }
                                            if (in_array(@Auth::user()->permissions_id,
                                                    $view_permission_groups) || in_array(0,
                                                    $view_permission_groups) || $customField->view_permission_groups == "") {
                                                // have permission & continue
                                                ?>
                                            @if ($customField->in_listing)
                                                @if ($customField->lang_code == "all" || $customField->lang_code == @Helper::currentLanguage()->code)
                                                        <?php
                                                        if ($customField->$cf_title_var != "") {
                                                            $cf_title = $customField->$cf_title_var;
                                                        } else {
                                                            $cf_title = $customField->$cf_title_var2;
                                                        }
                                                        $cls = "text-left";
                                                        if ($customField->type != 0) {
                                                            $cls = "text-center";
                                                        }
                                                        ?>
                                                    <th class="light {{ $cls }}">{{ $cf_title }}</th>
                                                @endif
                                            @endif
                                                <?php
                                            }
                                                ?>
                                        @endforeach
                                        @if($WebmasterSection->date_status)
                                            <th class="light text-center">{{ __('backend.topicDate') }}</th>
                                        @endif
                                        @if($WebmasterSection->expire_date_status)
                                            <th class="light text-center">{{ __('backend.expireDate') }}</th>
                                        @endif
                                        @if($WebmasterSection->longtext_status)
                                            <th class="light text-center">{{ __('backend.viewDetails') }}</th>
                                        @endif
                                        </thead>
                                        <tbody>
                                            <?php
                                            $title_var = "title_".@Helper::currentLanguage()->code;
                                            $title_var2 = "title_".config('smartend.default_language');
                                            $details_var = "details_".@Helper::currentLanguage()->code;
                                            $details_var2 = "details_".config('smartend.default_language');
                                            $slug_var = "seo_url_slug_".@Helper::currentLanguage()->code;
                                            $slug_var2 = "seo_url_slug_".config('smartend.default_language');
                                            $i = 0;
                                            ?>
                                        @foreach($Topics as $Topic)
                                                <?php
                                                if ($Topic->$title_var != "") {
                                                    $title = $Topic->$title_var;
                                                } else {
                                                    $title = $Topic->$title_var2;
                                                }
                                                if ($Topic->$details_var != "") {
                                                    $details = $details_var;
                                                } else {
                                                    $details = $details_var2;
                                                }
                                                $section = "";
                                                try {
                                                    if ($Topic->section->$title_var != "") {
                                                        $section = $Topic->section->$title_var;
                                                    } else {
                                                        $section = $Topic->section->$title_var2;
                                                    }
                                                } catch (Exception $e) {
                                                    $section = "";
                                                }

                                                $topic_link_url = Helper::topicURL($Topic->id, "", $Topic);
                                                ?>

                                                <?php
                                                $i++;
                                                ?>
                                            <tr>
                                                @if(@$WebmasterSection->no_status)
                                                    <td class="text-center">{!! $Topic->id !!}</td>
                                                @endif
                                                @if($WebmasterSection->title_status)
                                                    <td>{!! $title !!}</td>
                                                @endif
                                                    <?php
                                                foreach (@$Topic->webmasterSection->customFields as $customField) {
                                                    $cls = "text-left";
                                                    if ($customField->type != 0) {
                                                        $cls = "text-center";
                                                    }
                                                    // check permission
                                                    $view_permission_groups = [];
                                                    if ($customField->view_permission_groups != "") {
                                                        $view_permission_groups = explode(",",
                                                            $customField->view_permission_groups);
                                                    }
                                                if (in_array(@Auth::user()->permissions_id,
                                                        $view_permission_groups) || in_array(0,
                                                        $view_permission_groups) || $customField->view_permission_groups == "") {
                                                    // have permission & continue

                                                if ($customField->in_listing) {

                                                    $cf_saved_val = "";
                                                    $cf_saved_val_array = array();
                                                    if (count($Topic->fields) > 0) {
                                                        foreach ($Topic->fields as $t_field) {
                                                            if ($t_field->field_id == $customField->id) {
                                                                if (in_array($customField->type, [7, 17])) {
                                                                    // if multi check
                                                                    $cf_saved_val_array = explode(", ",
                                                                        $t_field->field_value);
                                                                } else {
                                                                    $cf_saved_val = $t_field->field_value;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $cf_data = "";
                                                if (($customField->lang_code == "all" || $customField->lang_code == @Helper::currentLanguage()->code)) {
                                                    if ($customField->type == 12) {
                                                        $CF_Vimeo_id = Helper::Get_vimeo_video_id($cf_saved_val);
                                                        $cf_data = "<a target='_blank' href='https://player.vimeo.com/video/$CF_Vimeo_id?title=0&amp;byline=0'><i class='fa fa-play'></i></a>";

                                                    } elseif ($customField->type == 11) {
                                                        $CF_Youtube_id = Helper::Get_youtube_video_id($cf_saved_val);
                                                        $cf_data = "<a target='_blank' href='https://www.youtube.com/embed/$CF_Youtube_id'><i class='fa fa-play'></i></a>";

                                                    } elseif ($customField->type == 10) {
                                                        $cf_data = "<a target='_blank' href='".route("fileView",
                                                                ["path" => 'topics/'.$cf_saved_val])."'><i class='fa fa-play'></i></a>";
                                                    } elseif ($customField->type == 9) {
                                                        $cf_data = "<a target='_blank' href='".route("fileView",
                                                                ["path" => 'topics/'.$cf_saved_val])."'><i class='fa fa-play'></i></a>";
                                                    } elseif ($customField->type == 8) {
                                                        $cf_data = "<a target='_blank' href='".route("fileView",
                                                                ["path" => 'topics/'.$cf_saved_val])."'><i class='fa fa-picture-o'></i></a>";

                                                    } elseif ($customField->type == 14) {
                                                        $cf_data = (($cf_saved_val == 1) ? "&check;" : "&bigotimes;")."  "." (".(($cf_saved_val == 1) ? __('backend.yes') : __('backend.no')).")";

                                                    } elseif (in_array($customField->type, [7, 17])) {
                                                        $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                                        $cf_details_var2 = "details_".config('smartend.default_language');
                                                        if ($customField->$cf_details_var != "") {
                                                            $cf_details = $customField->$cf_details_var;
                                                        } else {
                                                            $cf_details = $customField->$cf_details_var2;
                                                        }
                                                        $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                        $line_num = 1;
                                                        foreach ($cf_details_lines as $cf_details_line) {
                                                            if (in_array($line_num, $cf_saved_val_array)) {
                                                                $cf_data .= "<span class=\"badge badge-inline bg-secondary\">".$cf_details_line."</span> ";
                                                            }
                                                            $line_num++;
                                                        }
                                                    } elseif ($customField->type == 6 || $customField->type == 13) {
                                                        $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                                        $cf_details_var2 = "details_".config('smartend.default_language');
                                                        if ($customField->$cf_details_var != "") {
                                                            $cf_details = $customField->$cf_details_var;
                                                        } else {
                                                            $cf_details = $customField->$cf_details_var2;
                                                        }
                                                        $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                        $line_num = 1;
                                                        foreach ($cf_details_lines as $cf_details_line) {
                                                            if ($line_num == $cf_saved_val) {
                                                                $cf_data .= "<span class=\"badge badge-inline bg-secondary\">".$cf_details_line."</span> ";
                                                            }
                                                            $line_num++;
                                                        }
                                                    } elseif ($customField->type == 5) {
                                                        $cf_data = Helper::formatDate($cf_saved_val)." ".date("h:i A",
                                                                strtotime($cf_saved_val));
                                                    } elseif ($customField->type == 4) {
                                                        $cf_data = Helper::formatDate($cf_saved_val);
                                                    } else {
                                                        $cf_data = $cf_saved_val;
                                                    }
                                                if ($cf_data != ""){
                                                    ?>
                                                <td class="{{ $cls }} {!! $customField->css_class !!}">{!! $cf_data !!}</td>
                                                    <?php
                                                }else{
                                                    ?>
                                                <td class="{{ $cls }} {!! $customField->css_class !!}"></td>
                                                    <?php
                                                }
                                                }
                                                }
                                                }
                                                }
                                                    ?>
                                                @if (@$Topic->webmasterSection->date_status)
                                                    <td class="text-center">{!! Helper::formatDate($Topic->date) !!}</td>
                                                @endif
                                                @if (@$Topic->webmasterSection->expire_date_status)
                                                    <td class="text-center">
                                                        <div {!! (($Topic->expire_date < date("Y-m-d")) ? "style='color:red'" : "") !!}>{!! Helper::formatDate($Topic->expire_date) !!}</div>
                                                    </td>
                                                @endif
                                                @if($WebmasterSection->longtext_status)
                                                    <td class="text-center">
                                                        <a href="{!! $topic_link_url !!}"
                                                           class="btn btn-theme"><i class="fa fa-file-text-o"></i></a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            {!! $Topics->appends($_GET)->links() !!}
                                        </div>
                                        <div class="col-lg-4 text-end">
                                            <h5 style="padding-top: 18px">{{ $Topics->firstItem() }}
                                                - {{ $Topics->lastItem() }} {{ __('backend.of') }}
                                                ( {{ $Topics->total()  }} ) {{ __('backend.records') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if($custom_body_code !="")
                            {!! Blade::render($custom_body_code) !!}
                        @endif
                    </div>
                    @if(count($Categories)>0)
                        @include('frontEnd.layouts.side')
                    @endif
                </div>
                @foreach($WebmasterSection->customFields as $customField)
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
                            $heigth = count($cf_details_lines) * 12;
                            if ($heigth < 200) {
                                $heigth = 200;
                            }
                            ?>
                        <div id="canvas-holder-{{ $customField->id }}" class="bottom-article mt-3 mb-3">
                            <h4>{!! $customField->$title_var !!}</h4>
                            <canvas id="chart-area-{{ $customField->id }}" width="1000" height="{{ $heigth }}"
                                    style="margin: 0 auto"></canvas>
                        </div>
                    @endif
                @endforeach

            </div>
        </section>
    </div>
    @include('frontEnd.layouts.popup',['Popup'=>@$Popup])
@endsection

@push('after-scripts')
    <script
        src="{{ URL::asset('assets/frontend/js/Chart.min.js') }}?v={{ Helper::system_version() }}"></script>
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
            @foreach($WebmasterSection->customFields as $customField)
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
                            fontFamily: "'Helvetica', 'Arial', sans-serif"
                        }
                    }
                }
            });
            @endif
            @endforeach
        };
    </script>
@endpush
@if($custom_css_code !="" || $custom_js_code !="")
    @push('after-styles')
        @if($custom_css_code !="")
            <style>
                {!! $custom_css_code !!}
            </style>
        @endif
        {!! $custom_js_code !!}
    @endpush
@endif
