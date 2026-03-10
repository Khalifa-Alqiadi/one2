@if($WebmasterSection->multi_images_status)
    <div class="tab-pane  {{ $tab_3 }}" id="tab_photos">

        <div class="box-body p-a-2">

            <div>
                <form method="POST" action="{{ route("topicsPhotosEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dropzone white" enctype="multipart/form-data">
                    @csrf
                    <div class="dz-message">
                        <h4 class="m-t-lg m-b-md">{{ __('backend.topicDropFiles') }}</h4>
                        <span class="text-muted block m-b-lg">( {{ __('backend.topicDropFiles2') }}
                                        )</span>
                    </div>
                </form>
                <br>
            </div>
            <form method="POST" action="{{ route("topicsPhotosUpdateAll",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dashboard-form">
                @csrf
                <div id="photos_list">
                    @if(count($Topic->photos)>0)
                        <div class="row">
                            @include("dashboard.topics.tabs.photos_list")
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <!-- .modal -->
                        <div id="mx-all" class="modal fade" data-backdrop="true">
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
                                        <button type="submit"
                                                class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div>
                        </div>
                        <!-- / .modal -->

                        <label class="ui-check m-a-0">
                            <input id="checkAll"
                                   type="checkbox"><i></i> {{ __('backend.checkAll') }}
                        </label>
                        <div class="pull-right">
                            <select name="action" id="action"
                                    class="form-control c-select w-md inline v-middle" required>
                                <option value="order">{{ __('backend.update') }}</option>
                                @if(@Auth::user()->permissionsGroup->delete_status)
                                    <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                @endif
                            </select>
                            <button type="submit" id="submit_all"
                                    class="btn white">{{ __('backend.apply') }}</button>
                            <button id="submit_show_msg" class="btn white" data-toggle="modal"
                                    style="display: none"
                                    data-target="#mx-all" ui-toggle-class="bounce"
                                    ui-target="#animate">{{ __('backend.apply') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
