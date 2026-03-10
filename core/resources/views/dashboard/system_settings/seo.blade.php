<div class="tab-pane {{  ( Session::get('active_tab') == 'SEOSettingTab') ? 'active' : '' }}"
     id="tab-3">
    <div class="p-a-md"><h5>{!!  __('backend.seoTabTitle') !!}</h5></div>

    <div class="p-a-md col-md-12">
        <div class="form-group m-b-2">
            <label for="slug_translation1" class="h6">{{ __('backend.slugTranslation') }}</label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="slug_translation" value="1" class="has-value" {{ ($WebmasterSetting->slug_translation ==1)?"checked":"" }} id="slug_translation1">
                        <i class="primary"></i>
                        🌐 {{ __('backend.slugTranslationOp1') }}
                    </label>
                </div>
                <div class="m-b-sm">
                    <label class="md-check">
                        <input type="radio" name="slug_translation" value="0" class="has-value" {{ ($WebmasterSetting->slug_translation ==0)?"checked":"" }} id="slug_translation2">
                        <i class="danger"></i>
                        🇺🇸 {{ __('backend.slugTranslationOp2') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group m-b-2">
            <label for="image_optimize1" class="h6">{{ __('backend.optimizeImages') }}</label>
            <div class="radio">
                <div class="m-b-sm">
                    <label class="md-check">
                        <input type="radio" name="image_optimize" value="0" class="has-value" {{ ($WebmasterSetting->image_optimize ==0)?"checked":"" }} id="image_optimize2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div>
                    <label class="md-check">
                        <input type="radio" name="image_optimize" value="1" class="has-value" {{ ($WebmasterSetting->image_optimize ==1)?"checked":"" }} id="image_optimize1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group m-b-2">
            <label for="image_optimize1" class="h6">{{ __('backend.resizeImages') }}</label>
            <div class="row">
                <div class="col-sm-6">
                    <div class="radio m-t-sm">
                        <div class="m-b-sm">
                            <label class="md-check" onclick="document.getElementById('image_resize_options').style.display='none'">
                                <input type="radio" name="image_resize" value="0" class="has-value" {{ ($WebmasterSetting->image_resize ==0)?"checked":"" }} id="image_resize2">
                                <i class="danger"></i>
                                {{ __('backend.notActive') }}
                            </label>
                        </div>
                        <div>
                            <label class="md-check" onclick="document.getElementById('image_resize_options').style.display='block'">
                                <input type="radio" name="image_resize" value="1" class="has-value" {{ ($WebmasterSetting->image_resize ==1)?"checked":"" }} id="image_resize1">
                                <i class="primary"></i>
                                {{ __('backend.active') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div id="image_resize_options" class="{{ ($WebmasterSetting->image_resize)?"":"displayNone" }}">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6">
                                <label for="image_resize_width">{{ __('backend.maxImageWidth') }}</label>
                                <input type="number" class="form-control" min="100" max="10000"
                                       name="image_resize_width" id="image_resize_width"
                                       value="{{ $WebmasterSetting->image_resize_width }}">
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <label for="image_resize_height">{{ __('backend.maxImageHeight') }}</label>
                                <input type="number" class="form-control" min="100" max="10000"
                                       name="image_resize_height" id="image_resize_height"
                                       value="{{ $WebmasterSetting->image_resize_height }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="b-a white dk m-b-2">
            <a class="p-a-2 block" data-toggle="collapse" href="#SEORepair" role="button" aria-expanded="false" aria-controls="SEORepair">
                <i class="pull-right fa fa-chevron-down"></i> <h5>{{ __('backend.seoFixUrls') }}</h5>
                <div class="text-muted h6 m-b-0">{!! __('backend.seoFixUrlsService') !!}</div>
            </a>
            <div class="collapse p-x-2 p-b-2" id="SEORepair">
                <div class="text-danger h6">{!! __('backend.backupWebsiteFirst') !!}</div>
                <a href="{{ route("webmasterSEORepair") }}"
                   onclick="return confirm('{{ __("backend.seoFixUrlsConfirm") }}')"
                   class="btn danger m-t-xs">{{ __('backend.seoFixUrlsStart') }}</a>
            </div>
        </div>
        <div class="m-b-2">
            <h5>{{ __('backend.sitemapLinks') }}</h5>
            @foreach(Helper::languagesList() as $ActiveLanguage)
                    <?php
                    $link = route('siteMapByLang', $ActiveLanguage->code);
                    if ($ActiveLanguage->code == config('smartend.default_language')) {
                        $link = route('siteMap');
                    }
                    ?>
                <div class="p-a-sm m-b-sm white dk b-a">
                    <span class="label text-sm pull-right">{{ $ActiveLanguage->title }}</span>
                    <a href="{{ $link }}" target="_blank">{{ $link }}</a>
                </div>
            @endforeach
        </div>
        <div class="form-group">
            <label for="instant_index1" class="h5">{{ __('backend.instantIndexing') }}</label>
            <div class="radio">
                <div class="m-b-sm">
                    <label class="md-check">
                        <input type="radio" name="instant_index" value="0" class="has-value" {{ ($WebmasterSetting->instant_index ==0)?"checked":"" }} id="instant_index2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div>
                    <label class="md-check">
                        <input type="radio" name="instant_index" value="1" class="has-value" {{ ($WebmasterSetting->instant_index ==1)?"checked":"" }} id="instant_index1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
            <div id="instant_index_settings" class="{{ ($WebmasterSetting->instant_index)?"":"displayNone" }}">
                <div class="p-a m-t-sm b-a">
                    @if($WebmasterSetting->instant_index && $WebmasterSetting->instant_index_file =="")
                        <div class="alert alert-danger">
                            <i class="fa fa-warning"></i> {{ __('backend.instantIndexingJsonFileMissed') }}
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="instant_index1" class="h6 m-b-sm">{{ __('backend.instantIndexingJsonFile') }}</label>
                        @if ($WebmasterSetting->instant_index_file !="")
                            <div class="box p-a-xs m-b-0">
                                <a target="_blank"
                                   href="{{ route("fileView",["path" =>'settings/'.$WebmasterSetting->instant_index_file]) }}">
                                    {{ $WebmasterSetting->instant_index_file }}
                                </a>
                            </div>
                        @endif
                        <input type="file" name="instant_index_file" id="instant_index_file" class="form-control" accept=".json">
                    </div>
                    <div class="checkbox m-b-sm">
                        <label class="md-switch">
                            <input type="checkbox" class="has-value" name="instant_index_on_create" id="instant_index_on_create" value="1" {{ ($WebmasterSetting->instant_index_on_create)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.autoInstantIndexingOnCreate') }}
                        </label>
                    </div>

                    <div class="checkbox m-b-sm">
                        <label class="md-switch">
                            <input type="checkbox" class="has-value" name="instant_index_on_update" id="instant_index_on_update" value="1" {{ ($WebmasterSetting->instant_index_on_update)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.autoInstantIndexingOnUpdate') }}
                        </label>
                    </div>

                    <div class="checkbox m-b-sm">
                        <label class="md-switch">
                            <input type="checkbox" class="has-value" name="instant_index_on_delete" id="instant_index_on_delete" value="1" {{ ($WebmasterSetting->instant_index_on_delete)?"checked":"" }}>
                            <i class="primary"></i>
                            {{ __('backend.autoInstantIndexingOnDelete') }}
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
