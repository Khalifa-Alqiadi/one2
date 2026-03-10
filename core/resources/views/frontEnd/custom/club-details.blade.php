@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">

        <div class="container">
            <div class="league-header mb-3 p-3 d-flex align-items-center gap-2">
                <h4 class="mb-0 fw-bold d-flex align-items-center">
                    <div class="logo p-2 rounded-circle bg-white">
                        <img src="{{ $team['image_path'] }}" class="team-logo">
                    </div>

                    <span class="mx-2">{{ $team['name'] }}</span>

                </h4>
                <div class="text-muted">
                    {{ data_get($team, 'country.name') }} |
                    {{ $team['founded'] ?? '-' }}
                </div>
            </div>
            

            {{-- Tabs --}}
            <ul class="nav nav-pills league-pills mb-4 px-0" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab"
                        data-bs-target="#info" type="button">
                        {{ $locale == 'ar' ? 'معلومات' : 'Infos' }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#squad" type="button">
                        {{ $locale == 'ar' ? 'اللاعبون' : 'Players' }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab"
                        data-bs-target="#t-fixtures" type="button">
                        {{ $locale == 'ar' ? 'المباريات' : 'Fixtures' }}
                    </button>
                </li>
            </ul>

            <div class="tab-content cardx border-0 p-3 px-0">

                {{-- Info --}}
                <div class="tab-pane fade show active" id="info">
                    <p>🏟 الملعب: {{ data_get($team, 'venue.name', '-') }}</p>
                    <p>📍 المدينة: {{ data_get($team, 'venue.city', '-') }}</p>
                    <p>👥 السعة: {{ data_get($team, 'venue.capacity', '-') }}</p>
                </div>

                {{-- Squad --}}
                <div class="tab-pane fade" id="squad">
                    <div class="row">
                        @forelse($squad as $p)
                            <div class="col-md-4 mb-2">
                                <div class="player-card">
                                    <strong>{{ data_get($p, 'player.name', '-') }}</strong>
                                    <div class="text-muted small">
                                        {{ data_get($p, 'position.name', '') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">لا توجد بيانات لاعبين</div>
                        @endforelse
                    </div>
                </div>

                {{-- Matches --}}
                <div class="tab-pane fade show" id="t-fixtures">
                    @include('frontEnd.custom.tabs.fixtures', [
                        'fixtures' => $fixtures,
                        'fixturesErr' => null
                    ])
                </div>

            </div>
        </div>
    </section>
@endsection
@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@push('after-styles')
    <style>
        #content {
            margin-top: 140px !important;
        }

        @media (max-width: 992px) {
            #content {
                margin-top: 120px !important;
            }
        }
        
    </style>
@endpush
