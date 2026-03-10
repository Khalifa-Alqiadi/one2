@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <section id="content" style="margin-top: 200px">
            <div class="container">

                {{-- @if (!empty($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif

                @if ($league)
                    <h3>{{ $league['name'] ?? 'League' }}</h3>
                @endif

                @foreach ($fixtures as $fx)
                    @php
                        $participants = collect($fx['participants'] ?? []);
                        $home = $participants->firstWhere('meta.location', 'home')['name'] ?? 'Home';
                        $away = $participants->firstWhere('meta.location', 'away')['name'] ?? 'Away';
                        $state = data_get($fx, 'state.name', '-');
                        $score = data_get($fx, 'scores.0.score', null);
                    @endphp

                    <div class="card mb-2 p-2">
                        <div><strong>{{ $home }}</strong> vs <strong>{{ $away }}</strong></div>
                        <div>State: {{ $state }} @if ($score)
                                | Score: {{ $score }}
                            @endif
                        </div>
                    </div>
                @endforeach --}}
                {{-- <h2>{{ $league }}</h2>

                @if (!empty($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif

                <p>عدد المباريات: {{ $count ?? 0 }}</p> --}}

                @forelse($leagues as $league)
                    @php
                        $participants = collect($fx['participants'] ?? []);


                        $name =  $league['name'] ?? null;

                    @endphp

                    <div class="card mb-2 p-2">
                        <img src="{{ $league['image_path'] ?? '' }}" width="28" alt="">
                        {{ $league['name'] ?? '' }}
                    </div>
                @empty
                    <p>لا توجد دوري</p>
                @endforelse




            </div>
        </section>
    </div>
@endsection
