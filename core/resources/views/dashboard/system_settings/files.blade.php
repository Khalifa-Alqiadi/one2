<div class="tab-pane {{  ( Session::get('active_tab') == 'fileSystemTab') ? 'active' : '' }}"
     id="tab-16">
    <div class="p-a-md"><h5>{!!  __('backend.fileSystem') !!}</h5></div>

    <div class="p-a-md col-md-12">
        <div class="form-group m-b-2">
            <div class="radio">
                <div class="m-b-sm">
                    <label class="md-check">
                        <input type="radio" name="file_system" value="local" class="has-value" {{ (config('filesystems.default') !="s3")?"checked":"" }} id="file_system1">
                        <i class="primary"></i>
                        {{ __('backend.local') }}
                    </label>
                </div>
                <div id="local_path_settings" class="m-t {{ (config('filesystems.default') =="s3")?"displayNone":"" }}">
                    <div class="form-group">
                        <label for="local_path">{{ __('backend.localUploadsPath') }}</label>
                        <input type="text" dir="ltr" class="form-control" name="local_path" id="local_path" value="{{ config('filesystems.uploads_path') }}">
                    </div>
                </div>
                <div class="m-t-2">
                    <label class="md-check">
                        <input type="radio" name="file_system" value="s3" class="has-value" {{ (config('filesystems.default') =="s3")?"checked":"" }} id="file_system2">
                        <i class="orange"></i>
                        {{ __('backend.amazonS3') }}
                    </label>
                </div>
                <div id="s3_settings" class="m-t {{ (config('filesystems.default') =="s3")?"":"displayNone" }}">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="s3_key">{{ __('backend.amazonS3AccessKey') }}</label>
                                <input type="text" dir="ltr" class="form-control" name="s3_key" id="s3_key" value="{{ config('filesystems.disks.s3.key') }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="s3_secret">{{ __('backend.amazonS3SecretKey') }}</label>
                                <input type="text" dir="ltr" class="form-control" name="s3_secret" id="s3_secret" value="{{ config('filesystems.disks.s3.secret') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="s3_region">{{ __('backend.amazonS3Region') }}</label>
                                <input type="text" dir="ltr" class="form-control" name="s3_region" id="s3_region" value="{{ config('filesystems.disks.s3.region') }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="s3_bucket">{{ __('backend.amazonS3Bucket') }}</label>
                                <input type="text" dir="ltr" class="form-control" name="s3_bucket" id="s3_bucket" value="{{ config('filesystems.disks.s3.bucket') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="s3_endpoint">{{ __('backend.amazonS3Endpoint') }}</label>
                        <input type="text" dir="ltr" class="form-control" name="s3_endpoint" id="s3_endpoint" value="{{ config('filesystems.disks.s3.endpoint') }}">
                    </div>
                    <div class="form-group">
                        <label for="s3_url">{{ __('backend.amazonS3URL') }}</label>
                        <input type="text" dir="ltr" class="form-control" name="s3_url" id="s3_url" value="{{ config('filesystems.disks.s3.url') }}">
                    </div>
                    <div class="form-group">
                        <label for="s3_path_style1">{{ __('backend.amazonS3UsePathStyle') }}</label>
                        <div class="radio m-t-sm">
                            <div>
                                <label class="md-check">
                                    <input type="radio" name="s3_path_style" value="false" class="has-value" {{ (config('filesystems.disks.s3.use_path_style_endpoint'))?"":"checked" }} id="s3_path_style1">
                                    <i class="danger"></i>
                                    {{ __('backend.no') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="s3_path_style" value="true" class="has-value" {{ (config('filesystems.disks.s3.use_path_style_endpoint'))?"checked":"" }} id="s3_path_style2">
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
