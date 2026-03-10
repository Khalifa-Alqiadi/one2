@if($WebmasterSection->comments_status)
    <div class="tab-pane  {{ $tab_4 }}" id="tab_comments">

        <div class="box-body p-a-2">
            @if (Session::has('commentST'))
                @if (Session::get('commentST') == "create")

                    <div>
                        <form method="POST" action="{{ route("topicsCommentsStore",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dashboard-form" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 form-control-label">{!!  __('backend.topicCommentName') !!}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" class="form-control" value="" placeholder="" maxlength="191" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 form-control-label">{!!  __('backend.topicCommentEmail') !!}
                                </label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" id="email" class="form-control" value="" placeholder="" maxlength="191" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="comment" class="col-sm-2 form-control-label">{!!  __('backend.topicComment') !!}
                                </label>
                                <div class="col-sm-10">
                                    <textarea name="comment" id="comment" class="form-control" rows="5" required></textarea>
                                </div>
                            </div>


                            <div class="form-group row m-t-md">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-lg btn-primary m-t"><i
                                            class="material-icons">
                                            &#xe31b;</i> {!! __('backend.add') !!}</button>
                                    <a href="{{ route('topicsComments',[$WebmasterSection->id,$Topic->id]) }}"
                                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                </div>
                            </div>

                        </form>
                    </div>

                @endif

                @if (Session::get('commentST') == "edit")
                    <div>
                        <form method="POST" action="{{ route("topicsCommentsUpdate",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id,"comment_id"=>Session::get('Comment')->id]) }}" class="dashboard-form" enctype="multipart/form-data">
                            @csrf


                            <div class="form-group row">
                                <label for="name" class="col-sm-2 form-control-label">{!!  __('backend.topicCommentName') !!}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" class="form-control" value="{{ Session::get('Comment')->name }}" placeholder="" maxlength="191" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 form-control-label">{!!  __('backend.topicCommentEmail') !!}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" id="name" class="form-control" value="{{ Session::get('Comment')->email }}" placeholder="" maxlength="191" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="comment" class="col-sm-2 form-control-label">{!!  __('backend.topicComment') !!}
                                </label>
                                <div class="col-sm-10">
                                    <textarea name="comment" id="comment" class="form-control" rows="5" required>{{ Session::get('Comment')->comment }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="comment_status1" class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check">
                                            <input type="radio" name="status" value="1" class="has-value" {{ (Session::get('Comment')->status==1)?"checked":"" }} id="comment_status1">
                                            <i class="primary"></i>
                                            {{ __('backend.active') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="status" value="0" class="has-value" {{ (Session::get('Comment')->status==2)?"checked":"" }} id="comment_status2">
                                            <i class="danger"></i>
                                            {{ __('backend.notActive') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-t-md">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-lg btn-primary m-t"><i
                                            class="material-icons">
                                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                                    <a href="{{ route('topicsComments',[$WebmasterSection->id,$Topic->id]) }}"
                                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                </div>
                            </div>

                        </form>
                    </div>
                @endif
            @else

                @if(count($Topic->comments)>0)
                    <div class="row m-b">
                        <div class="col-sm-12">
                            <a class="btn btn-fw primary"
                               href="{{route("topicsCommentsCreate",[$WebmasterSection->id,$Topic->id])}}">
                                <i class="material-icons">&#xe02e;</i>
                                &nbsp; {{ __('backend.topicNewComment') }}
                            </a>
                        </div>
                    </div>
                @endif
                @if(count($Topic->comments) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class=" p-y-2 p-x text-center light b-a h6 m-a-0">
                                <div class="text-muted m-b"><i class="fa fa-laptop fa-4x"></i></div>
                                {{ __('backend.noData') }}
                                <br>
                                <br>
                                <a class="btn btn-fw primary"
                                   href="{{route("topicsCommentsCreate",[$WebmasterSection->id,$Topic->id])}}">
                                    <i class="material-icons">&#xe02e;</i>
                                    &nbsp; {{ __('backend.topicNewComment') }}
                                </a>

                            </div>
                        </div>
                    </div>
                @endif
                @if(count($Topic->comments)>0)
                    <form method="POST" action="{{ route("topicsCommentsUpdateAll",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id]) }}" class="dashboard-form">
                        @csrf
                        <table class="table table-bordered">
                            <thead class="dker">
                            <tr>
                                <th class="width20 dker">
                                    <label class="ui-check m-a-0">
                                        <input id="checkAll2" type="checkbox"><i></i>
                                    </label>
                                </th>
                                <th>{{ __('backend.topicCommentName') }}</th>
                                <th>{{ __('backend.topicComment') }}</th>
                                <th class="text-center"
                                    style="width:50px;">{{ __('backend.status') }}</th>
                                <th class="text-center"
                                    style="width:200px;">{{ __('backend.options') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Topic->comments as $comment)
                                <tr>
                                    <td class="dker"><label class="ui-check m-a-0">
                                            <input type="checkbox" name="ids[]"
                                                   value="{{ $comment->id }}"><i
                                                class="dark-white"></i>
                                            <input type="hidden" name="row_ids[]" value="{{ $comment->id }}" class="form-control row_no">
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" name="row_no_{{ $comment->id }}" id="row_no_{{ $comment->id }}" value="{{ $comment->row_no }}" class="pull-left form-control row_no light" autocomplete="off">
                                        {{$comment->name}}
                                        <div>
                                            <small>
                                                {{$comment->email}}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $comment->date }}</small>
                                        <div>
                                            <small>{{ $comment->comment }}</small>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <i class="fa {{ ($comment->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-sm success"
                                           href="{{ route("topicsCommentsEdit",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id,"comment_id"=>$comment->id]) }}">
                                            <small><i class="material-icons">
                                                    &#xe3c9;</i> {{ __('backend.edit') }}</small>
                                        </a>
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <button class="btn btn-sm warning" data-toggle="modal"
                                                    data-target="#mc-{{ $comment->id }}"
                                                    ui-toggle-class="bounce"
                                                    ui-target="#animate">
                                                <small><i class="material-icons">
                                                        &#xe872;</i> {{ __('backend.delete') }}
                                                </small>
                                            </button>
                                        @endif

                                    </td>
                                </tr>
                                <!-- .modal -->
                                <div id="mc-{{ $comment->id }}" class="modal fade" data-backdrop="true">
                                    <div class="modal-dialog" id="animate">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                            </div>
                                            <div class="modal-body text-center p-lg">
                                                <h5 class="m-b-0">
                                                    {{ __('backend.confirmationDeleteMsg') }}
                                                    <br>
                                                    <strong>[ {!! $comment->name !!} ]</strong>
                                                </h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark-white p-x-md"
                                                        data-dismiss="modal">{{ __('backend.no') }}</button>
                                                <a href="{{ route("topicsCommentsDestroy",["webmasterId"=>$WebmasterSection->id,"id"=>$Topic->id,"comment_id"=>$comment->id]) }}"
                                                   class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div>
                                <!-- / .modal -->
                            @endforeach

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-3 hidden-xs">
                                <!-- .modal -->
                                <div id="mc-all" class="modal fade" data-backdrop="true">
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

                                <select name="action" id="action2"
                                        class="form-control c-select w-sm inline v-middle" required>
                                    <option value="">{{ __('backend.bulkAction') }}</option>
                                    <option value="order">{{ __('backend.saveOrder') }}</option>
                                    <option value="activate">{{ __('backend.activeSelected') }}</option>
                                    <option value="block">{{ __('backend.blockSelected') }}</option>
                                    @if(@Auth::user()->permissionsGroup->delete_status)
                                        <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                    @endif
                                </select>
                                <button type="submit" id="submit_all2"
                                        class="btn white">{{ __('backend.apply') }}</button>
                                <button id="submit_show_msg2" class="btn white" data-toggle="modal"
                                        style="display: none"
                                        data-target="#mc-all" ui-toggle-class="bounce"
                                        ui-target="#animate">{{ __('backend.apply') }}
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
@endif
