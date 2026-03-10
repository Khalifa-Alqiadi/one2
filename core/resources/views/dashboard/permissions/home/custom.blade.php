<form method="POST" action="{{ route('permissionsHomePageUpdate',$Permissions->id) }}" class="dashboard-form">
    @csrf
    <div class="form-group row m-t">
        <div class="col-sm-12">
            <label for="home_status2">{{ __('backend.customHomeSettings') }} : </label>
            <br>
            <label class="md-check m-b-sm">
                <input type="radio" name="home_status" id="home_status2" value="0" class="has-value" {{ $Permissions->home_status ? "":"checked" }}>
                <i class="primary"></i>
                {{ __('backend.defaultPage') }}
            </label>
            &nbsp; &nbsp;
            <label class="md-check m-b-sm">
                <input type="radio" name="home_status" id="home_status1" value="1" class="has-value" {{ $Permissions->home_status ? "checked":"" }}>
                <i class="danger"></i>
                {{ __('backend.customPage') }}
            </label>
        </div>
    </div>

    <div id="home_details_div" {!!  ( !$Permissions->home_status) ? "style='display:none'":"" !!}>
        @foreach(Helper::languagesList() as $ActiveLanguage)
            @if($ActiveLanguage->box_status)
                <div class="form-group row">
                    <label
                        class="col-sm-2 form-control-label">{!!  __('backend.welcomeDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                    </label>
                    <div class="col-sm-10">
                        <div>
                            @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                <div>
                                    <textarea name="home_details_{{ @$ActiveLanguage->code }}" id="home_details_{{ @$ActiveLanguage->code }}" class="form-control ckeditor" dir="{{ @$ActiveLanguage->direction }}">{{ $Permissions->{'home_details_'.@$ActiveLanguage->code} }}</textarea>
                                </div>
                            @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                <div>
                                    <textarea name="home_details_{{ @$ActiveLanguage->code }}" id="home_details_{{ @$ActiveLanguage->code }}" class="form-control tinymce" dir="{{ @$ActiveLanguage->direction }}">{{ $Permissions->{'home_details_'.@$ActiveLanguage->code} }}</textarea>
                                </div>
                            @else
                                <div class="box p-a-xs">
                                    <textarea name="home_details_{{ @$ActiveLanguage->code }}" id="home_details_{{ @$ActiveLanguage->code }}" class="form-control summernote summernote_{{ @$ActiveLanguage->code }}" dir="{{ @$ActiveLanguage->direction }}">{{ $Permissions->{'home_details_'.@$ActiveLanguage->code} }}</textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        <br>

        <div class="form-group row">
            <div class="col-sm-2">
                <label
                    class="form-control-label">{!!  __('backend.welcomeLinks') !!}
                </label>
                <br>
                <button type="button" class="btn btn-sm primary w-100" data-toggle="modal" data-target="#link_add"><i
                        class="material-icons">&#xe02e;</i>
                    &nbsp; {{ __('backend.addNewLink') }}</button>
            </div>
            <div class="col-sm-10">
                <div class="p-a b-a m-t" id="buttons_list">
                    <div class="text-center">
                        <img class="m-b-1"
                             src="{{ asset('assets/dashboard/images/loading.gif') }}"
                             style="height: 35px;"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row m-t-md">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary  m-t"><i class="material-icons">
                    &#xe31b;</i> {!! __('backend.update') !!}</button>
            <a href="{{route("users")}}"
               class="btn btn-default m-t"><i class="material-icons">
                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
        </div>
    </div>
</form>
@include('dashboard.permissions.home.links.create')
<div id="btns-delete" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="animate">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
            </div>
            <div class="modal-body text-center p-lg">
                <h5 class="m-b-0">
                    {{ __('backend.confirmationDeleteMsg') }}
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark-white p-x-md"
                        data-dismiss="modal">{{ __('backend.no') }}</button>
                <button type="button" id="btns_delete_btn" row-id=""
                        class="btn danger p-x-md"
                        data-dismiss="modal">{{ __('backend.yes') }}</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<div id="link_edit" class="modal black-overlay fade" data-backdrop="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('customLinksUpdate') }}" class="dashboard-form" id="btn_update_form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title"><i
                            class="fa fa-edit"></i> {{ __('backend.editLink') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-lg p-a">

                    <div class="alert alert-danger displayNone" id="btn_edit_errors">
                        <ul></ul>
                    </div>
                    <div id="buttons_edit_details">
                        <div class="text-center">
                            <img class="m-b-1"
                                 src="{{ asset('assets/dashboard/images/loading.gif') }}"
                                 style="height: 35px;"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn dark-white p-x-md"
                            data-dismiss="modal">{!! __('backend.cancel') !!}
                    </button>
                    <button type="submit" id="link_update_submit" class="btn info p-x-md"><i
                            class="material-icons">&#xe31b;</i> {!! __('backend.save') !!}
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div>
</div>
