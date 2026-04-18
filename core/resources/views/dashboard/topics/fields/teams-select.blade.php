@if ($WebmasterSection->sportmonks_status == 2)
    <div class="form-group row">
        <label for="team_id" class="col-sm-2 form-control-label">{!! __('backend.team') !!}</label>
        <div class="col-sm-10">
            <select name="team_id" id="team_id" class="form-control select2" required>
                <option value="">....</option>
            </select>
        </div>
    </div>
    @push('after-scripts')
        <script>
            $(document).ready(function() {
                $('#league_id').on('change', function() {
                    let leagueId = $(this).val();
                    let $teamSelect = $('#team_id');

                    $teamSelect.html('<option value="">جاري التحميل...</option>').trigger('change');

                    if (!leagueId) {
                        $teamSelect.html('<option value="">....</option>').trigger('change');
                        return;
                    }

                    var xhr = $.ajax({
                        type: "POST",
                        url: "<?php echo route("topics.leagues.teams"); ?>",
                        data: {
                            _token: "{{csrf_token()}}",
                            league_id: leagueId,
                            oldInputs: @json(old())
                        },
                        success: function (data) {
                            let options = '<option value="">....</option>';

                            if (data.ok && data.teams.length > 0) {
                                $.each(data.teams, function(index, team) {
                                    options +=
                                        `<option value="${team.id}">${team.name}</option>`;
                                });
                            }

                            $teamSelect.html(options).trigger('change');
                        }
                    })

//                     $.ajax({
//                         url: "{{ url('/topics/leagues') }}/" + leagueId + "/teams",
//                         type: "GET",
//                         dataType: "json",
//                         success: function(response) {
//                             let options = '<option value="">....</option>';
//
//                             if (response.ok && response.teams.length > 0) {
//                                 $.each(response.teams, function(index, team) {
//                                     options +=
//                                         `<option value="${team.id}">${team.name}</option>`;
//                                 });
//                             }
//
//                             $teamSelect.html(options).trigger('change');
//                         },
//                         error: function() {
//                             $teamSelect.html('<option value="">فشل تحميل الفرق</option>').trigger(
//                                 'change');
//                         }
//                     });
                });
            });
        </script>
    @endpush
@else
    <input type="hidden" name="team_id" id="team_id" value="0">
@endif
