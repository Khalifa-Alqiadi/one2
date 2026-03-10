@php
$name_var = 'name_' . @Helper::currentLanguage()->code;
$title_var = 'title_' . @Helper::currentLanguage()->code;
@endphp
@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <section id="content" style="margin-top: 200px">
            <div class="container">
                <div class="row">
                    @forelse($leagues as $league)

                        <div class="col-md-6">
                            <a href="{{route('league.show', ['id' => $league->id])}}" class="card mb-2 p-2">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white rounded-circle p-2">
                                        <img src="{{ $league->image_path ?? '' }}" width="28" alt="">
                                    </div>
                                    <h4 class="mb-2 mx-2">{{ $league->$name_var ?? '' }}</h4>
                                </div>
                                <div class="card-body">
                                    <span>{{$league->country->$title_var ?? ''}}</span>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p>لا توجد دوري</p>
                    @endforelse
                </div>

            </div>
        </section>
    </div>
@endsection
