@extends('frontEnd.layouts.master')
@push("after-styles")
    <style>
        h1 {
            font-size: 130px;
            margin: 0;
        }

        .p-y-md {
            padding: 30px 0;
        }

        .app-body {
            width: 100%;
            height: calc(100vh - 520px);
            min-height: 400px;
            position: relative;
        }

        .error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
@endpush
@section('content')
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>404</h2>
                    <ol>
                        <li><a href="{{ Helper::homeURL() }}">{{ __("backend.home") }}</a></li>
                        <li class="active">404</li>
                    </ol>
                </div>

            </div>
        </section>
        <div class="app-body bg-auto w-full">
            <div class="text-center pos-rlt p-y-md error">
                <h1 class="text-shadow  text-4x">
                    <span class="text-2x font-bold block m-t-lg">404</span>
                </h1>
                <h3>{{ __('backend.notFound') }}.</h3>
            </div>
        </div>
    </main>
@endsection
