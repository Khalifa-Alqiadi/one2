@php($matches = Helper::getMatchHome(3))
@php($name_var = 'name_' . @Helper::currentLanguage()->code)
@if (count($matches) > 0)
    <section class="matches py-5">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($matches as $match)
                    <div class="col mb-3">
                        <div class="card bg-transparent">
                            <div class="box-match d-flex justify-content-between align-items-center ">
                                <div class="team d-flex flex-column align-items-center">
                                    @if ($match->homeTeam)
                                        @if ($match->homeTeam->image_path)
                                            <img src="{{ $match->homeTeam->image_path }}" style="height:30px"
                                                alt="">
                                        @endif
                                        <span class="mt-2">{{ $match->homeTeam->$name_var }}</span>
                                    @endif
                                </div>
                                <div class="goals">
                                    <h4>{{ $match->home_goals }}</h4>
                                </div>
                                <span class="m-x-sm">vs</span>
                                <div class="goals">
                                    <h4>{{ $match->away_goals }}</h4>
                                </div>
                                <div class="team d-flex flex-column align-items-center">
                                    @if ($match->awayTeam)
                                        @if ($match->awayTeam->image_path)
                                            <img src="{{ $match->awayTeam->image_path }}" style="height:30px"
                                                alt="">
                                        @endif
                                        <span class="mt-2">{{ $match->awayTeam->$name_var }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body border-top mt-3 pb-0">
                                <h4 class="text-center mb-0">
                                    {!! Helper::day_name($match->starting_at) !!}
                                </h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
