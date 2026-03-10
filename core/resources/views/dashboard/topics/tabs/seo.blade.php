@if($WebmasterSection->seo_status)
    <div class="tab-pane  {{ $tab_2 }}" id="tab_seo">

        <div class="box-body p-a-2">
            <form method="POST" action="{{ route("topicsSEOUpdate",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dashboard-form">
                @csrf
                @foreach(Helper::languagesList() as $ActiveLanguage)
                    @if($ActiveLanguage->box_status)
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div>
                                        <label>{!!  __('backend.topicSEOTitle') !!}</label> {!! @Helper::languageName($ActiveLanguage) !!}
                                        <input type="text" autocomplete="off" name="seo_title_{{ @$ActiveLanguage->code }}" id="seo_title_{{ @$ActiveLanguage->code }}" value="{{ $Topic->{'seo_title_'.@$ActiveLanguage->code} }}" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label>{!!  __('backend.friendlyURL') !!}</label> {!! @Helper::languageName($ActiveLanguage) !!}
                                        <input type="text" autocomplete="off" name="seo_url_slug_{{ @$ActiveLanguage->code }}" id="seo_url_slug_{{ @$ActiveLanguage->code }}" value="{{ $Topic->{'seo_url_slug_'.@$ActiveLanguage->code} }}" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div>
                                        <label>{!!  __('backend.topicSEODesc') !!}</label> {!! @Helper::languageName($ActiveLanguage) !!}
                                        <textarea name="seo_description_{{ @$ActiveLanguage->code }}" id="seo_description_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="2">{{ $Topic->{'seo_description_'.@$ActiveLanguage->code} }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div>
                                        <label>{!!  __('backend.topicSEOKeywords') !!}</label> {!! @Helper::languageName($ActiveLanguage) !!}
                                        <textarea name="seo_keywords_{{ @$ActiveLanguage->code }}" id="seo_keywords_{{ @$ActiveLanguage->code }}" class="form-control" dir="{{ @$ActiveLanguage->direction }}" rows="2">{{ $Topic->{'seo_keywords_'.@$ActiveLanguage->code} }}</textarea>
                                    </div>
                                </div>
                                <br>
                                <br>
                            </div>
                            <div class="col-sm-6">
                                    <?php
                                    $seo_example_title = $Topic->{'title_'.@$ActiveLanguage->code};
                                    $seo_example_desc = Helper::GeneralSiteSettings("site_desc_".@$ActiveLanguage->code);
                                    if ($Topic->{'seo_title_'.@$ActiveLanguage->code} != "") {
                                        $seo_example_title = $Topic->{'seo_title_'.@$ActiveLanguage->code};
                                    }
                                    if ($Topic->{'seo_description_'.@$ActiveLanguage->code} != "") {
                                        $seo_example_desc = $Topic->{'seo_description_'.@$ActiveLanguage->code};
                                    }
                                    $seo_example_url = Helper::topicURL($Topic->id, @$ActiveLanguage->code, $Topic);
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
                    @endif
                @endforeach

                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                &#xe31b;</i> {!! __('backend.update') !!}</button>
                        <a href="{{ route('topics',$WebmasterSection->id) }}"
                           class="btn btn-lg btn-default m-t"><i class="material-icons">
                                &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endif
