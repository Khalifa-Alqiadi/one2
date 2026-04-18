@if ($WebmasterSection->sportmonks_status == 1 || $WebmasterSection->sportmonks_status == 2)
    <div class="form-group row">
        <label for="league_id" class="col-sm-2 form-control-label">{!! __('backend.leagues') !!}</label>
        <div class="col-sm-10">
            <select name="league_id" id="league_id" class="form-control select2" required>
                <option value="">....</option>
                @foreach ($leagues as $league)
                    <option value="{{ $league->id }}">{!! $league->$name_var !!}</option>
                @endforeach
            </select>
        </div>
    </div>
@else
    <input type="hidden" name="league_id" id="league_id" value="0">
@endif
