<input type="hidden" name="topic_id" class="form-control" value="{{ encrypt($Topic->id) }}">
<input type="hidden" name="block_id" class="form-control" value="{{ @$TopicBlock->id }}">
<input type="hidden" name="content_type" class="form-control" value="2">
<div class="light">
    <div class="row">
        @include("dashboard.topics.landing.blocks.settings")
        <div class="col-sm-9">
            <div class="p-a-2 white b-l">
                <div class="form-group">
                    @include("dashboard.topics.landing.blocks.meta")
                    <div class="form-group row">
                        <label for="view-styles"
                            class="col-sm-12 form-control-label m-b-sm">{!!  __('backend.viewStyle') !!}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-12">
                            <div class="img-radio-group" id="view-styles">

                                <input type="radio" name="banner_style" id="banners" class="radio-input" value="banners" {{ (@$TopicBlockContents->banner_style == "banners" || empty(@$TopicBlockContents))?"checked":"" }} required>
                                <label for="banners" class="radio-label">
                                    <img src="{{ asset('assets/dashboard/images/blocks/banners.png') }}" alt="{{ __("backend.blockBannersAsItems") }}">
                                    <div class="text">{{ __("backend.blockBannersAsItems") }}</div>
                                </label>

                                <input type="radio" name="banner_style" id="slider" class="radio-input" value="slider" {{ (@$TopicBlockContents->banner_style == "slider")?"checked":"" }} required>
                                <label for="slider" class="radio-label">
                                    <img src="{{ asset('assets/dashboard/images/blocks/slider.png') }}" alt="{{ __("backend.blockBannersAsSlider") }}">
                                    <div class="text">{{ __("backend.blockBannersAsSlider") }}</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="banner_area_id"
                            class="col-sm-12 form-control-label">{!!  __('backend.bannerArea') !!}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-12">
                            <?php
                            $WebmasterBanners = \App\Models\WebmasterBanner::where("status",1)->orderBy("row_no","asc")->get();
                            ?>
                            <div class="input-group">

                                <select name="banner_area_id" id="banner_area_id" class="form-control form-select2" required>
                                    <option value=""> - - {{ __("backend.select") }} - -</option>
                                    <?php
                                    $title_var = "title_".@Helper::currentLanguage()->code;
                                    $title_var2 = "title_".config('smartend.default_language');
                                    ?>
                                    @foreach($WebmasterBanners as $WebmasterBanner)
                                            <?php
                                            if ($WebmasterBanner->$title_var != "") {
                                                $title = $WebmasterBanner->$title_var;
                                            } else {
                                                $title = $WebmasterBanner->$title_var2;
                                            }
                                            ?>
                                        <option value="{{$WebmasterBanner->id}}" {{ (@$TopicBlockContents->banner_area_id == $WebmasterBanner->id)?"selected":"" }}>{{ $title }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn"><a href="{{ route("WebmasterBanners") }}" target="_blank" class="btn white" type="button"><i class="material-icons m-r-xs">&#xe145;</i></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ URL::asset('assets/dashboard/js/select2-bootstrap-theme/dist/select2-bootstrap.css') }}?v={{ Helper::system_version() }}"/>
<link rel="stylesheet" href="{{ URL::asset('assets/dashboard/js/select2-bootstrap-theme/dist/select2-bootstrap.4.css') }}?v={{ Helper::system_version() }}"/>
<script src="{{ URL::asset('assets/dashboard/js/select2/dist/js/select2.min.js') }}?v={{ Helper::system_version() }}"></script>
<script>
    $(".form-select2").select2({
        theme: 'bootstrap',
        placeholder: "{{ __("backend.select") }}"
    });
    moreBtnStatusSettingsView(0);
</script>
