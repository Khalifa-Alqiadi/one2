{{--
<section class="pt-3 pb-0 teams-home">
    <div class="container">
        <div class="row">
            @php $teams = Helper::majorCompetitionsTeams(); @endphp
            @if(count($teams) > 0)
                <div class="col-md-6">
                    <div class="major-competitions d-flex align-items-center">
                        <div class="items-title d-flex align-items-center">
                            <img src="{{URL::to('uploads/settings/major_competitions.svg')}}" alt="" />
                            <h4 class="mb-0 mx-2">{{__('frontend.major_competitions')}}</h4>
                        </div>
                        <div class="items">
                            @foreach($teams as $team)
                                <div class="item">
                                    <img src="{{$team->image_path}}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section> --}}
<section class="pt-3 pb-0 teams-home">
    <div class="container">
        <div class="row">
            @php $teams = Helper::majorCompetitionsTeams(); @endphp

            @if(count($teams) > 0)
                <div class="col-md-6">
                    <div class="major-competitions-wrapper">
                        <div class="items-title">
                            <img src="{{ URL::to('uploads/settings/major_competitions.svg') }}" alt="">
                            <h4 class="mb-0">{{ __('frontend.major_competitions') }}</h4>
                        </div>
                        <div class="items-track">
                            @foreach($teams as $team)
                                <div class="item">
                                    <img src="{{ $team->image_path }}" alt="">
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endif
            @php $major_national_teams = Helper::majorNationalTeams(); @endphp

            @if(count($major_national_teams) > 0)
                <div class="col-md-6">
                    <div class="major-competitions-wrapper">
                        <div class="items-title">
                            <img src="{{ URL::to('uploads/settings/major_competitions.svg') }}" alt="">
                            <h4 class="mb-0">{{ __('frontend.major_national_teams') }}</h4>
                        </div>
                        <div class="items-track">
                            @foreach($major_national_teams as $team)
                                <div class="item">
                                    <img src="{{ $team->image_path }}" alt="">
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
