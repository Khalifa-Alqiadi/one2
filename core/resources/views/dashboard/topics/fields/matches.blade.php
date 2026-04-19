@if ($WebmasterSection->sportmonks_status == 3)
    <div class="form-group row">
        <label for="match_id" class="col-sm-2 form-control-label">{!! __('backend.matches') !!}</label>
        <div class="col-sm-10">
            @if($type == 'edit')
                <select name="match_id" id="match_id" class="form-control select2"
                    ui-jp="select2" ui-options="{theme: 'bootstrap'}" required>
                    <option value="">....</option>
                    @if(count($matches) > 0)
                        @foreach ($matches as $match)
                            <option value="{{ $match->id }}"
                                {{$Topic->fixture_id == $match->id ? 'selected' : ''}}>{!! $match?->homeTeam->$name_var !!} vs {!! $match?->awayTeam->$name_var !!}</option>
                        @endforeach
                    @endif
                </select>
            @else
                <select name="match_id" id="match_id" class="form-control select2"
                    ui-jp="select2" ui-options="{theme: 'bootstrap'}" required>
                    <option value="">....</option>
                </select>
            @endif
        </div>
    </div>
@else
    <input type="hidden" name="match_id" id="match_id" value="0">
@endif
