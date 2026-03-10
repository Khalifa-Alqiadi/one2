@if($Topic->topicBlocks->count() >0)
    <div class="responsive-table">
        <table class="table table-bordered table-striped m-b-0" id="topic-blocks-table">
            <thead>
            <th class="dker width20">
                <label class="ui-check m-a-0">
                    <input id="checkAll" type="checkbox"><i></i>
                </label>
            </th>
            <th>{{ __("backend.title") }}</th>
            <th style="width: 200px">{{ __("backend.type") }}</th>
            <th class="text-center w-64">{{ __("backend.status") }}</th>
            <th class="text-center w-64">{{ __("backend.options") }}</th>
            </thead>
            <tbody>
            @php($title_var = "title_".@Helper::currentLanguage()->code)
            @php($x = 0)
            @foreach($Topic->topicBlocks as $TopicBlock)
                @php($x++)
                <tr>

                    <td class="dker"><label class="ui-check m-a-0">
                            <input type="checkbox" name="ids[]" value="{{ $TopicBlock->id }}"><i
                                    class="dark-white"></i>
                            <input type="hidden" name="row_ids[]" value="{{ $TopicBlock->id }}" class="form-control row_no">
                        </label>
                    </td>
                    <td>
                        <input type="text" name="row_no_{{ $TopicBlock->id }}" id="row_no_{{ $TopicBlock->id }}" value="{{ $TopicBlock->row_no }}" class="form-control row_no light" autocomplete="off">
                        @if (@Auth::user()->permissionsGroup->edit_status)
                            <a onclick="UpdateTopicBlock('{{ encrypt($TopicBlock->id) }}')">{{ $TopicBlock->block_name }}</a>
                        @else
                            {{ $TopicBlock->block_name }}
                        @endif
                    </td>
                    <td>
                        @if($TopicBlock->type == 4)
                                <?php
                                $block_type = '<i class="material-icons m-r-xs">&#xe85d;</i> '.__("backend.customForm");
                                $TopicBlockContents = [];
                                if ($TopicBlock->content != "") {
                                    try {
                                        $TopicBlockContents = json_decode($TopicBlock->content);
                                    } catch (Exception $e) {

                                    }
                                }
                                $block_type = '<i class="material-icons m-r-xs">&#xe85d;</i> '.@$TopicBlockContents->view_style;
                                ?>
                            {!! $block_type !!}
                        @elseif($TopicBlock->type == 3)
                                <?php
                                $block_type = '<i class="material-icons m-r-xs">&#xe051;</i> '.__("backend.dynamicContent");
                                $TopicBlockContents = [];
                                if ($TopicBlock->content != "") {
                                    try {
                                        $TopicBlockContents = json_decode($TopicBlock->content);
                                    } catch (Exception $e) {

                                    }
                                }
                                $block_type = '<i class="material-icons m-r-xs">&#xe051;</i> '.@$TopicBlockContents->view_style;
                                ?>
                            {!! $block_type !!}
                        @elseif($TopicBlock->type == 2)
                                <?php
                                $block_type = '<i class="material-icons m-r-xs">&#xe8f7;</i> '.__("backend.bannersContent");
                                $TopicBlockContents = [];
                                if ($TopicBlock->content != "") {
                                    try {
                                        $TopicBlockContents = json_decode($TopicBlock->content);
                                    } catch (Exception $e) {

                                    }
                                }
                                if (@$TopicBlockContents->banner_style == "slider") {
                                    $block_type = '<i class="material-icons m-r-xs">&#xe8f7;</i> '.__("backend.blockBannersAsSlider");
                                }
                                if (@$TopicBlockContents->banner_style == "banners") {
                                    $block_type = '<i class="material-icons m-r-xs">&#xe8f7;</i> '.__("backend.blockBannersAsItems");
                                }
                                ?>
                            {!! $block_type !!}
                        @elseif($TopicBlock->type == 1)
                            <i class="material-icons m-r-xs">&#xe86f;</i> {{ __("backend.customCode") }}
                        @elseif($TopicBlock->type == 0)
                            <i class="material-icons m-r-xs">&#xe86d;</i> {{ __("backend.staticContent") }}
                        @endif
                    </td>
                    <td class="text-center">
                        <i class="fa {{ (($TopicBlock->status == 1) ? "fa-check text-success" : "fa-times text-danger") }} inline"></i>
                    </td>
                    <td class="text-center">
                        <div class="dropdown {{ ((($x + 1) >= $Topic->topicBlocks->count()) ? "dropup" : "") }}">
                            <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons">&#xe5d4;</i> {{ __('backend.options') }}
                            </button>
                            <div class="dropdown-menu pull-right">
                                @if (@Auth::user()->permissionsGroup->edit_status)
                                    <a class="dropdown-item" onclick="UpdateTopicBlock('{{ encrypt($TopicBlock->id) }}')"><i class="material-icons">&#xe3c9;</i> {{ __("backend.edit") }}
                                    </a>
                                @endif
                                @if (@Auth::user()->permissionsGroup->add_status)
                                    <a class="dropdown-item" onclick="CloneTopicBlock('{{ encrypt($TopicBlock->id) }}')"><i class="material-icons">&#xe14d;</i> {{ __("backend.clone") }}
                                    </a>
                                @endif
                                @if (@Auth::user()->permissionsGroup->delete_status)
                                    <a class="dropdown-item text-danger" onclick="DeleteTopicBlock('{{ encrypt($TopicBlock->id) }}')"><i class="material-icons">&#xe872;</i> {{ __("backend.delete") }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@else
    <div class="row">
        <div class="col-sm-12">
            <div class=" p-y-3 p-x text-center light b-a h6 m-a-0">
                <div class="text-muted m-b"><i class="fa fa-laptop fa-4x"></i></div>
                {{ __('backend.noData') }}
            </div>
        </div>
    </div>
@endif
