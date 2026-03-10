<div id="link_add" class="modal black-overlay fade" data-backdrop="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="material-icons">&#xe02e;</i> {{ __('backend.addNewLink') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
            $Adviser = Auth::user();
            $title_var = "title_".__('backend.boxCode');
            $title_var2 = "title_".__('backend.boxCodeOther');
            ?>
            <form method="POST" action="{{ route('customLinksStore') }}" class="dashboard-form" id="btn_add_form">
                @csrf
                <div class="modal-body p-lg p-b-0">
                    <div class=" p-a">

                        <div class="alert alert-danger displayNone" id="btn_add_errors">
                            <ul></ul>
                        </div>
                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            @if($ActiveLanguage->box_status)
                                <div class="form-group row">
                                    <label for="btn_title_{{ @$ActiveLanguage->code }}"
                                        class="col-sm-3 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" autocomplete="off" name="btn_title_{{ @$ActiveLanguage->code }}" id="btn_title_{{ @$ActiveLanguage->code }}" value="" required maxlength="100" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <div class="form-group row">
                            <label for="btn_link" class="col-sm-3 form-control-label">{!!  __('backend.link') !!}
                            </label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" name="link" id="btn_link" value="" required dir="ltr" class="form-control"/>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="target0"
                                   class="col-sm-3 form-control-label">{!!  __('backend.linkTarget') !!}</label>
                            <div class="col-sm-9">
                                <div class="radio m-t-sm">
                                    <label class="md-check m-b-sm">
                                        <input type="radio" name="target" id="target0" value="0" class="has-value" checked>
                                        <i class="primary"></i>
                                        {{ __('backend.linkTargetParent') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check m-b-sm">
                                        <input type="radio" name="target" id="target1" value="1" class="has-value">
                                        <i class="danger"></i>
                                        {{ __('backend.linkTargetBlank') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="btn_class" class="col-sm-3 form-control-label"> CSS Class
                            </label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" name="btn_class" id="btn_class" value="btn btn-lg primary"
                                       dir="ltr" class="form-control" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="permission_id" value="{{ $Permissions->id }}">
                    <button type="button"
                            class="btn dark-white p-x-md"
                            data-dismiss="modal">{{ __('backend.cancel') }}</button>
                    <button type="submit" id="btn_add_form_submit"
                            class="btn btn-primary p-x-md">{!! __('backend.add') !!}</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div>
</div>
