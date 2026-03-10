<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'File Manager') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('assets/file-manager/css/file-manager.css') }}">

    <style>
        .fm-navbar div div div:last-child {
            display: none;
        }
        .fm .fm-body{
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        .fm-info-block{
            border-bottom: none;
        }
        .fm-tree {
            border-right: 1px solid #ddd;
        }
        .fm-breadcrumb .breadcrumb.active-manager {
            background-color: #c5efea;
        }
    </style>
</head>
<body>
<div id="fm-main-block">
    <div id="fm"></div>
</div>

<!-- File manager -->
<script src="{{ asset('assets/file-manager/js/file-manager.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');
    });
</script>

@if (!@Auth::user()->permissionsGroup->delete_status)
    <style>
        #fm-main-block > div > div.fm-navbar.mb-3 > div > div:nth-child(1) > div:nth-child(2) > button:nth-child(4) {
            display: none !important;
        }

        #fm-main-block > div > div.fm-body > div.fm-context-menu > ul:nth-child(3) > li {
            display: none !important;
        }
    </style>
@endif
</body>
</html>
