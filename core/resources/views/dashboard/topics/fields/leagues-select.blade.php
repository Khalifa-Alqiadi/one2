@if ($WebmasterSection->sportmonks_status == 1 || $WebmasterSection->sportmonks_status == 2 || $WebmasterSection->sportmonks_status == 3)
    <div class="form-group row">
        <label for="league_id" class="col-sm-2 form-control-label">{!! __('backend.leagues') !!}</label>
        <div class="col-sm-10">
            <select name="league_id" id="league_id" class="form-control select2"
                ui-jp="select2" ui-options="{theme: 'bootstrap'}" required>
                <option value="">....</option>
                @foreach ($leagues as $league)
                    @if($type == 'edit')
                        <option value="{{ $league->id }}" {{$Topic->league_id == $league->id ? 'selected' : ''}}>{!! $league->$name_var !!}</option>
                    @else
                        <option value="{{ $league->id }}">{!! $league->$name_var !!}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
@else
    <input type="hidden" name="league_id" id="league_id" value="0">
@endif
