<div class="table-columns dropdown-menu pull-right box-shadow-z4">
    <form method="POST" action="{{ route("tableColumnsUpdate") }}" class="dashboard-form">
        @csrf
        <div class="w-lg">
            <?php
            $AllCols = Helper::get_all_webmaster_section_columns($WebmasterSection);
            $MyCols = Helper::get_user_webmaster_columns($WebmasterSection->id);
            $ActiveCols = [];
            ?>
            <div id="table-columns-container" class="p-a">
                <ul id="table-columns-list" class="list-group m-b-0" style="max-height: 500px;overflow-y: scroll">
                    @foreach($MyCols as $MyCol=>$MyColSt)
                            <?php
                            $ActiveCols[] = $MyCol;
                            $COL = Helper::get_all_webmaster_section_columns($WebmasterSection, $MyCol);
                            ?>
                        @if(!empty($COL))
                            <li class="list-group-item p-a-sm" id="{{ $MyCol }}">
                                <span class="pull-right js-handle m-l"><i class="material-icons">&#xe3a5;</i></span>
                                <label class="md-check">
                                    <input type="checkbox" name="{{ $MyCol }}" value="1"
                                           {{ ($MyColSt)?"checked":"" }} class="has-value">
                                    <i class="primary"></i>
                                    {{ @$COL['title'] }}
                                </label>
                            </li>
                        @endif
                    @endforeach
                    @foreach($AllCols as $KEY=>$COL)
                        @if(!in_array($KEY,$ActiveCols))
                            <li class="list-group-item p-a-sm" id="{{ $KEY }}">
                                <span class="pull-right js-handle m-l"><i class="material-icons">&#xe3a5;</i></span>
                                <label class="md-check">
                                    <input type="checkbox" name="{{ $KEY }}"
                                           {{ (empty($MyCols) && @$COL['default'])?"checked":"" }} value="1"
                                           class="has-value">
                                    <i class="primary"></i>
                                    {{ @$COL['title'] }}
                                </label>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="b-t p-a">
                <div class="m-b">
                    <label class="md-check">
                        <input type="checkbox" name="reset_default" id="reset_default_cols" value="1" class="has-value">
                        <i class="danger"></i>
                        <small>{{ __('backend.resetDefaults') }}</small>
                    </label>
                </div>
                <input type="hidden" name="columns_position" id="columns_position"/>
                <input type="hidden" name="webmaster_id" value="{{ $WebmasterSection->id }}"/>
                <button type="submit" class="btn primary w-100">{{ __('backend.update') }}</button>
            </div>
        </div>
    </form>
</div>
