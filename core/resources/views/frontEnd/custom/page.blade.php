@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>{{ $PageTitle }}</h2>
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __("backend.home") }}</a></li>
                        <li class="active">{{ $PageTitle }}</li>
                    </ol>
                </div>
            </div>
        </section>
        <section id="content">
            <div class="container">

                {{-- page content --}}
                <h1>{{ $PageTitle }}</h1>
                <p>You custom code here</p>

            </div>
        </section>
    </div>

@endsection
@push('before-styles')
    {{-- integrate your custom css code/files here --}}
@endpush
@push('after-styles')
    {{-- integrate your custom css code/files here --}}
@endpush
@push('after-scripts')
    {{-- integrate your custom js code/files here--}}
@endpush
@section('footInclude')
    {{-- integrate your custom js code/files here--}}
@endsection
