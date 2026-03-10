@if($Topic->webmasterSection->date_status)
    <div class="text-muted topic-date mb-2">
        <i class="fa-regular fa-calendar"></i> {!! Helper::formatDate($Topic->date)  !!}
    </div>
@endif
@if(@count(@$Fields) >0)
    <div class="row topic-custom-fields">
            <?php
            $cf_title_var = "title_".@Helper::currentLanguage()->code;
            $cf_title_var2 = "title_".config('smartend.default_language');
            $cat_ids = $Topic->categories->pluck("section_id")->toarray();
            ?>
        @foreach($Fields as $customField)
                <?php
                $intersection = [];
                if (!empty(@$cat_ids) && $customField->categories != "") {
                    $intersection = array_intersect($cat_ids, @explode(",", $customField->categories));
                }
                ?>
            @if($customField->categories=="" || @$cat_ids=="" || !empty($intersection))
                    <?php

                    // check permission
                    $view_permission_groups = [];
                    if ($customField->view_permission_groups != "") {
                        $view_permission_groups = explode(",", $customField->view_permission_groups);
                    }
                    ?>
                @if (in_array(0, $view_permission_groups) || $customField->view_permission_groups == "")
                        <?php
                        if ($customField->$cf_title_var != "") {
                            $cf_title = $customField->$cf_title_var;
                        } else {
                            $cf_title = $customField->$cf_title_var2;
                        }

                        $cf_saved_val = "";
                        $cf_saved_val_array = array();
                        if (count($Topic->fields) > 0) {
                            foreach ($Topic->fields as $t_field) {
                                if ($t_field->field_id == $customField->id) {
                                    if (in_array($customField->type,[7,17])) {
                                        // if multi check
                                        $cf_saved_val_array = explode(", ", $t_field->field_value);
                                    } else {
                                        $cf_saved_val = $t_field->field_value;
                                    }
                                }
                            }
                        }

                        ?>

                    @if(($cf_saved_val!="" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == @Helper::currentLanguage()->code))
                        @if($customField->type ==12)
                            {{--Vimeo Video Link--}}
                                <?php
                                $CF_Vimeo_id = Helper::Get_vimeo_video_id($cf_saved_val);
                                ?>
                            @if($CF_Vimeo_id !="")
                                <div class="col-sm-12">
                                    <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                    <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                        {{-- Vimeo Video --}}
                                        <iframe allowfullscreen
                                                style="height:450px;width: 100%"
                                                src="https://player.vimeo.com/video/{{ $CF_Vimeo_id }}?title=0&amp;byline=0">
                                        </iframe>
                                    </div>
                                </div>
                            @endif
                        @elseif($customField->type ==11)
                            {{--Youtube Video Link--}}

                                <?php
                                $CF_Youtube_id = Helper::Get_youtube_video_id($cf_saved_val);
                                ?>
                            @if($CF_Youtube_id !="")
                                <div class="col-sm-12">
                                    <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                    <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                        {{-- Youtube Video --}}
                                        <iframe allowfullscreen
                                                style="height: 600px;width: 100%"
                                                src="https://www.youtube.com/embed/{{ $CF_Youtube_id }}">
                                        </iframe>
                                    </div>
                                </div>
                            @endif
                        @elseif($customField->type ==10)
                            {{--Video File--}}
                            <div class="col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    <video width="100%" height="450" controls>
                                        <source
                                            src="{{ route("fileView",["path" =>'topics/'.$cf_saved_val ]) }}"
                                            type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>
                        @elseif($customField->type ==9)
                            {{--Attach File--}}
                            <div class="col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    @php($ext = pathinfo($cf_saved_val, PATHINFO_EXTENSION))
                                    @if($ext=="mp3" || $ext=="wav")
                                        <audio controls>
                                            <source
                                                src="{{ route("fileView",["path" =>'topics/'.$cf_saved_val ]) }}"
                                                type="audio/mpeg">
                                            Your browser does not support the audio
                                            element.
                                        </audio>
                                    @else
                                        <a href="{{ route("fileView",["path" =>'topics/'.$cf_saved_val ]) }}"
                                           target="_blank">
<span class="badge">
    {!! Helper::GetIcon(route("fileView",["path" =>'topics/'.$cf_saved_val ]),"64px",5) !!}
    {!! $cf_saved_val !!}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($customField->type ==8)
                            {{--Photo File--}}
                            <div class="col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    <a href="{{ route("fileView",["path" =>'topics/'.$cf_saved_val ]) }}"
                                       class="galelry-lightbox">
                                        <img style="max-height: 256px;width: auto"
                                             src="{{ route("fileView",["path" =>'topics/'.$cf_saved_val ]) }}"
                                             alt="{{ $cf_title }} - {{ $title }}" title="{{ $cf_title }} - {{ $title }}"
                                             class="rounded-2">
                                    </a>
                                </div>
                            </div>
                        @elseif(in_array($customField->type,[7,17]))
                            {{--Multi Check--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                        <?php
                                        $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                        $cf_details_var2 = "details_".config('smartend.default_language');
                                        if ($customField->$cf_details_var != "") {
                                            $cf_details = $customField->$cf_details_var;
                                        } else {
                                            $cf_details = $customField->$cf_details_var2;
                                        }
                                        $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                        $line_num = 1;
                                        ?>
                                    @foreach ($cf_details_lines as $cf_details_line)
                                        @if (in_array($line_num,$cf_saved_val_array))
                                            <span class="badge badge-inline bg-secondary">{!! $cf_details_line !!}</span>
                                        @endif
                                            <?php
                                            $line_num++;
                                            ?>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($customField->type ==14)
                            {{--Checkbox--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    {!! (($cf_saved_val == 1) ? "&check;" : "&bigotimes;"); !!} {!! "(".(($cf_saved_val == 1) ? __('backend.yes') : __('backend.no')).")" !!}
                                </div>
                            </div>
                        @elseif($customField->type ==6 || $customField->type ==13)
                            {{--Select--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                        <?php
                                        $cf_details_var = "details_".@Helper::currentLanguage()->code;
                                        $cf_details_var2 = "details_".config('smartend.default_language');
                                        if ($customField->$cf_details_var != "") {
                                            $cf_details = $customField->$cf_details_var;
                                        } else {
                                            $cf_details = $customField->$cf_details_var2;
                                        }
                                        $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                        $line_num = 1;
                                        ?>
                                    @foreach ($cf_details_lines as $cf_details_line)
                                        @if ($line_num == $cf_saved_val)
                                            {!! $cf_details_line !!}
                                        @endif
                                            <?php
                                            $line_num++;
                                            ?>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($customField->type ==5)
                            {{--Date & Time--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    {!! Helper::formatDate($cf_saved_val)." ".date("h:i A", strtotime($cf_saved_val)) !!}
                                </div>
                            </div>
                        @elseif($customField->type ==4)
                            {{--Date--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    {!! Helper::formatDate($cf_saved_val) !!}
                                </div>
                            </div>
                        @elseif($customField->type ==3)
                            {{--Email Address--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    {!! strip_tags($cf_saved_val) !!}
                                </div>
                            </div>
                        @elseif($customField->type ==2)
                            {{--Number--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    {!! $cf_saved_val !!}
                                </div>
                            </div>
                        @elseif($customField->type ==1)
                            {{--Text Area--}}
                            <div class="col-sm-12">
                                <h5>{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    {!! Helper::ParseLinks(nl2br($cf_saved_val)) !!}
                                </div>
                            </div>
                        @else
                            {{--Text Box--}}
                            <div class="col-lg-{{ @$cols }} col-sm-12">
                                <h5 class="custom-field-title">{!!  $cf_title !!}</h5>
                                <div class="custom-field-value card mb-3 py-2 px-3 rounded-2">
                                    @if (strpos($cf_saved_val, 'http') === 0)
                                        <a href="{{ $cf_saved_val }}" target="_blank" class="btn btn-secondary btn-sm"><i
                                                class="fa-solid fa-arrow-up-right-from-square"></i> {{ __("backend.preview") }}
                                        </a>
                                    @else
                                        {!! Helper::ParseLinks($cf_saved_val) !!}
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            @endif
        @endforeach
    </div>
@endif
