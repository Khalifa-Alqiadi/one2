@if ($WebmasterSection->sportmonks_status == 2 || $WebmasterSection->sportmonks_status == 3)
    <div class="form-group row">
        <label for="team_id" class="col-sm-2 form-control-label">{!! __('backend.team') !!}</label>
        <div class="col-sm-10">
            @if($type == 'edit')
                <select name="team_id" id="team_id" class="form-control select2"
                    ui-jp="select2" ui-options="{theme: 'bootstrap'}" required>
                    <option value="">....</option>
                    @if(count($teams) > 0)
                        @foreach ($teams as $team)
                            @if($team->id == $Topic->team_id)
                                <option value="{{ $team->id }}" selected>{!! $team->$name_var !!}</option>
                            @else
                                <option value="{{ $team->id }}">{!! $team->$name_var !!}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            @else
                <select name="team_id" id="team_id" class="form-control select2"
                    ui-jp="select2" ui-options="{theme: 'bootstrap'}" required>
                    <option value="">....</option>
                </select>
            @endif
        </div>
    </div>

@else
    <input type="hidden" name="team_id" id="team_id" value="0">
@endif
