<div class="tab-pane  {{ $tab_3 }}" id="tab_seo">
    <div class="box-body p-a-2">
        <form method="POST" action="{{ route("WebmasterSectionsSEOUpdate",$WebmasterSections->id) }}" class="dashboard-form">
            @csrf

            @foreach(Helper::languagesList() as $ActiveLanguage)
                <div class="row">
                    <div class="col-sm-6">

                        <div class="form-group">
                            <div>
                                <small>{!!  __('backend.topicSEOTitle') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}
                                <input type="text" autocomplete="off" name="seo_title_{{ @$ActiveLanguage->code }}" id="seo_title_{{ @$ActiveLanguage->code }}" value="{{ $WebmasterSections->{'seo_title_'.@$ActiveLanguage->code} }}" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <small>{!!  __('backend.friendlyURL') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}
                                <input type="text" autocomplete="off" name="seo_url_slug_{{ @$ActiveLanguage->code }}" id="seo_url_slug_{{ @$ActiveLanguage->code }}" value="{{ $WebmasterSections->{'seo_url_slug_'.@$ActiveLanguage->code} }}" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <small>{!!  __('backend.topicSEODesc') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}
                                <textarea name="seo_description_{{ @$ActiveLanguage->code }}" id="seo_description_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="2">{{ $WebmasterSections->{'seo_description_'.@$ActiveLanguage->code} }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <small>{!!  __('backend.topicSEOKeywords') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}
                                <textarea name="seo_keywords_{{ @$ActiveLanguage->code }}" id="seo_keywords_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="2">{{ $WebmasterSections->{'seo_keywords_'.@$ActiveLanguage->code} }}</textarea>
                            </div>
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6">
                            <?php
                            $seo_example_title = $WebmasterSections->{'title_'.@$ActiveLanguage->code};
                            $seo_example_desc = Helper::GeneralSiteSettings("site_desc_".@$ActiveLanguage->code);
                            if ($WebmasterSections->{'seo_title_'.@$ActiveLanguage->code} != "") {
                                $seo_example_title = $WebmasterSections->{'seo_title_'.@$ActiveLanguage->code};
                            }
                            if ($WebmasterSections->{'seo_description_'.@$ActiveLanguage->code} != "") {
                                $seo_example_desc = $WebmasterSections->{'seo_description_'.@$ActiveLanguage->code};
                            }
                            $seo_example_url = Helper::sectionURL($WebmasterSections->id, @$ActiveLanguage->code,
                                $WebmasterSections);
                            ?>
                        <div class="form-group">
                            <div class="search-example-div">
                                {!! @Helper::languageName($ActiveLanguage) !!}
                                <div class="search-example" dir="{{ @$ActiveLanguage->direction }}">
                                    <a id="title_in_engines_{{ @$ActiveLanguage->code }}"
                                       href="{{ $seo_example_url }}"
                                       target="_blank">{{ $seo_example_title }}</a>
                                    <span
                                        id="url_in_engines_{{ @$ActiveLanguage->code }}">{{ $seo_example_url }}</span>
                                    <div
                                        id="desc_in_engines_{{ @$ActiveLanguage->code }}">{{ $seo_example_desc }}
                                        ...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <i class="material-icons">&#xe8fd;</i>
                                <small>{!!  __('backend.seoTabSettings') !!}</small>
                            </div>
                        </div>
                        <br>
                        <br>

                    </div>
                </div>
            @endforeach

            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                    <a href="{{ route('WebmasterSectionsEdit',$WebmasterSections->id) }}"
                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                </div>
            </div>
        </form>
    </div>
</div>

@push("after-scripts")
    <script type="text/javascript">
        // Js Slug
        function slugify(string) {
            return string
                .toString()
                .trim()
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "")
                .replace(/\-\-+/g, "-")
                .replace(/^-+/, "")
                .replace(/-+$/, "");
        }

        @foreach(Helper::languagesList() as $ActiveLanguage)
        $("#seo_title_{{ @$ActiveLanguage->code }}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#title_in_engines_{{ @$ActiveLanguage->code}}").text($(this).val());
            } else {
                $("#title_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo $WebmasterSections->{'title_'.@$ActiveLanguage->code}; ?>");
            }
        });
        $("#seo_description_{{ @$ActiveLanguage->code}}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#desc_in_engines_{{ @$ActiveLanguage->code }}").text($(this).val());
            } else {
                $("#desc_in_engines_{{ @$ActiveLanguage->code}}").text("<?php echo Helper::GeneralSiteSettings("site_desc_".@$ActiveLanguage->code); ?>");
            }
        });
        $("#seo_url_slug_{{ @$ActiveLanguage->code }}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#url_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo url(''); ?>/" + slugify($(this).val()));
            } else {
                $("#url_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo Helper::sectionURL($WebmasterSections->id,
                    @$ActiveLanguage->code, $WebmasterSections); ?>");
            }
        });
        @endforeach
    </script>
@endpush
