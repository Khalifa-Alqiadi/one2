@push('after-scripts')
    @if ($WebmasterSection->sportmonks_status == 2 || $WebmasterSection->sportmonks_status == 3)
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
                    url: "<?php echo route('topics.leagues.teams'); ?>",
                    data: {
                        _token: "{{ csrf_token() }}",
                        league_id: leagueId,
                        oldInputs: @json(old())
                    },
                    success: function(data) {
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

            });
        });
    </script>
    @endif
    @if ($WebmasterSection->sportmonks_status == 3)
    <script>
            $(document).ready(function() {
                $('#team_id').on('change', function() {
                    let leagueId = $('#league_id').val();
                    let teamId = $(this).val();
                    let $matchSelect = $('#match_id');

                    $matchSelect.html('<option value="">جاري التحميل...</option>').trigger('change');

                    if (!teamId || !leagueId) {
                        $matchSelect.html('<option value="">....</option>').trigger('change');
                        return;
                    }

                    $.ajax({
                        type: "POST",
                        url: "{{ route('topics.leagues.team.matches') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            league_id: leagueId,
                            team_id: teamId,
                            oldInputs: @json(old())
                        },
                        success: function(data) {
                            let options = '<option value="">....</option>';

                            if (data.ok && data.matches.length > 0) {
                                $.each(data.matches, function(index, match) {
                                    options +=
                                        `<option value="${match.id}">${match.name}</option>`;
                                });
                            }

                            $matchSelect.html(options).trigger('change');
                        },
                        error: function() {
                            $matchSelect.html('<option value="">فشل تحميل المباريات</option>')
                                .trigger('change');
                        }
                    });
                });
            });
        </script>
    @endif
@endpush
