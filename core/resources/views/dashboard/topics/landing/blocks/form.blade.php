<input type="hidden" name="topic_id" class="form-control" value="{{ encrypt($Topic->id) }}">
<input type="hidden" name="block_id" class="form-control" value="{{ @$TopicBlock->id }}">
<input type="hidden" name="content_type" class="form-control" value="4">
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
                                @php
                                    use Illuminate\Support\Facades\File;

                                    $imagePath = asset('assets/dashboard/images/blocks/');
                                    if (!File::exists($imagePath)) {
                                    $imagePath = str_replace('/core/public','',public_path('assets/dashboard/images/blocks/'));
                                    }
                                    $images = [];

                                    if (File::exists($imagePath)) {
                                        $images = File::files($imagePath);
                                        $images = array_filter($images, function($file) {
                                            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        });
                                    }
                                @endphp
                                @if(count($images) > 2)
                                    @php($i=0)
                                    @foreach($images as $image)
                                        @if(in_array(pathinfo($image, PATHINFO_FILENAME),['Form']))
                                            <input type="radio" name="view_style" id="{{ pathinfo($image, PATHINFO_FILENAME) }}" class="radio-input" value="{{ pathinfo($image, PATHINFO_FILENAME) }}" {{ (@$TopicBlockContents->view_style == pathinfo($image, PATHINFO_FILENAME) || (empty(@$TopicBlock) && $i==1))?"checked":"" }} required>
                                            <label for="{{ pathinfo($image, PATHINFO_FILENAME) }}" class="radio-label">
                                                <img src="{{ asset('assets/dashboard/images/blocks/' . $image->getFilename()) }}" alt="{{pathinfo($image, PATHINFO_FILENAME) }}">
                                                <div class="text">{{ pathinfo($image, PATHINFO_FILENAME) }}</div>
                                            </label>
                                            @php($i++)
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="module_id"
                               class="col-sm-12 form-control-label">{!!  __('backend.customForm') !!}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-12">
                            <?php
                            $WebmasterSections = \App\Models\WebmasterSection::whereIn("type",
                                [4, 7, 6])->where("status", 1)->orderBy("row_no", "asc")->get();
                            ?>
                            <div>
                                <select name="module_id" id="module_id" class="form-control form-select2" required>
                                    <option value=""> - - {{ __("backend.select") }} - -</option>
                                    <option value="0" {{ (@$TopicBlockContents->module_id == 0 && @$TopicBlockContents->module_id!="")?"selected":"" }}> {{ __("backend.contactForm") }} </option>
                                    <?php
                                    $title_var = "title_".@Helper::currentLanguage()->code;
                                    $title_var2 = "title_".config('smartend.default_language');
                                    ?>
                                    @foreach($WebmasterSections as $WebmasterSection)
                                            <?php
                                            if ($WebmasterSection->$title_var != "") {
                                                $title = $WebmasterSection->$title_var;
                                            } else {
                                                $title = $WebmasterSection->$title_var2;
                                            }
                                            ?>
                                        <option value="{{$WebmasterSection->id}}" {{ (@$TopicBlockContents->module_id == $WebmasterSection->id)?"selected":"" }}>{{ $title }}</option>
                                    @endforeach
                                </select>
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
    $(".form-select2-multiple").select2({
        tags: true,
        theme: 'bootstrap',
        placeholder: "{{ __("backend.select") }}"
    });
    var block_name = document.getElementById("block_name");
    var block_name_val = block_name.value;

    block_name.addEventListener('input', function () {
        block_name_val = block_name.value;
    });
    $('input[name="view_style"]').change(function () {
        if ($(this).is(':checked')) {
            var labelText = $('label[for="' + this.id + '"]').text();
            if (block_name_val === "") {
                document.getElementById("block_name").value = labelText.replace(/^\s+/, '').replace(/\s+$/, '').replace(/\n\s*\n/g, '\n').replace(/[ \t]+/g, ' ');
            }
        }
    });
    moreBtnStatusSettingsView(0);
</script>
