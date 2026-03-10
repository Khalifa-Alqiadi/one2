@foreach($Topic->photos as $photo)
    <div class="col-xs-6 col-sm-4 col-md-3">
        <div class="box p-a-xs">
            <div class="pull-right">
                <input type="text" name="row_no_{{ $photo->id }}" value="{{ $photo->row_no }}" class="pull-left form-control row_no light" autocomplete="off">
            </div>
            <label class="ui-check">
                <input type="checkbox" name="ids[]" value="{{ $photo->id }}"><i
                    class="dark-white"></i>
                <input type="hidden" name="row_ids[]" value="{{ $photo->id }}" class="form-control row_no">
            </label>

            <div class="text-center">
                <a style="display: block;overflow: hidden;"
                   href="{{ route("fileView",["path" =>'topics/'.$photo->file]) }}"
                   target="_blank">
                    <img src="{{ route("fileView",["path" =>'topics/'.$photo->file]) }}?w=300&h=300"
                         alt="{{ $photo->title  }}" title="{{ $photo->title  }}"
                         style="height: 150px;width: auto"
                         class="img-responsive">
                </a>
            </div>
            <div class="p-a-sm">
                <div class="text-ellipsis">
                    @if(@Auth::user()->permissionsGroup->delete_status)
                        <button class="btn btn-sm warning pull-right m-b-sm"
                                data-toggle="modal"
                                data-target="#mx-{{ $photo->id }}"
                                ui-toggle-class="bounce"
                                ui-target="#animate"
                                title="{{ __('backend.delete') }}"
                                style="padding: 0 5px 2px;">
                            <small><i class="material-icons">&#xe872;</i></small>
                        </button>
                    @endif
                    <input type="text" class="form-control" name="title_of_{{ $photo->id }}" value="{{ ($photo->title !="") ? $photo->title:$photo->file  }}">

                </div>
            </div>

            <!-- .modal -->
            <div id="mx-{{ $photo->id }}" class="modal fade" data-backdrop="true">
                <div class="modal-dialog" id="animate">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                        </div>
                        <div class="modal-body text-center p-lg">
                            <h5 class="m-b-0">
                                {{ __('backend.confirmationDeleteMsg') }}
                                <br>
                                <strong>[ {{ ($photo->title !="") ? $photo->title:$photo->file  }}
                                    ]</strong>
                            </h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark-white p-x-md"
                                    data-dismiss="modal">{{ __('backend.no') }}</button>
                            <a href="{{ route("topicsPhotosDestroy",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id,"photo_id"=>$photo->id]) }}"
                               class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            </div>
            <!-- / .modal -->
        </div>
    </div>
@endforeach
