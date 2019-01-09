@extends('app::layouts.dashboard.dashboard')

@section('css')
    <style>

        .mobile-hide {
            display: none;
        }

        .day {
            width: 100%;
            *width: 100%;
            padding-right: 5px;
        }

        .day-heading {
            display: block;
        }

        .day-calendar > div.alert {
            margin-left: 5px;
        }

        .day-date {
            margin-top: .5em;
            display: inline-block;
        }


        .day-short-name {
            display: none;
            font-weight: bold;
        }

        .day-short-name:first-child {
            display: inline-block;
            border-bottom: 1px solid #ced4da;
            height: 1px;
            overflow: hidden;
        }

        .day-name {
            display: inline-block;
            font-weight: bold;
        }

        .day-calendar {
            min-height: 9em;
            border-bottom: 1px solid #ced4da;
            border-right: none;
        }

        .short-month {
            font-weight: bold;
        }

        .day-data {
            padding-left: 5px;
            margin-bottom: 3px;
        }

        .day-data > .list-group > .list-group-item {
            margin-bottom: 1px;
        }

        /* Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) {

        }

        /* Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) {

        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {

            .mobile-hide {
                display: block;
            }

            .day {
                width: 14.28%;
                *width: 14.28%;
            }

            .day-short-name {
                display: inline-block;
                border-bottom: 1px solid #ced4da;
            }

            .day-short-name:first-child {
                height: auto;
            }

            .day-name {
                display: none;
            }

            .day-calendar {
                border-right: 1px solid #ced4da;
            }
        }

        /* Extra large devices (large desktops, 1200px and up) */
        @media (min-width: 1200px) {

        }

    </style>
@endsection

@section('js')
    <script src="{{ asset('js/angular.min.js') }}"></script>
    <script src="{{ asset('js/angular-sanitize.min.js') }}"></script>
    <script src="{{ asset('js/ngDraggable.js') }}"></script>
    <script src="{{ asset('js/controllers/diaryCtrl.js') }}"></script>
    <script src="{{ asset('js/controllers/diaryDataCtrl.js') }}"></script>
    <script src="{{ asset('js/services/diaryService.js') }}"></script>
    <script>
        var diaryApp = angular.module('diaryApp', [
            'diaryCtrl',
            'diaryDataCtrl',
            'diaryService'
        ]);
    </script>

@endsection

@section('content')

    <div class="container-fluid mt-3">

        <div ng-app="diaryApp">
            <div ng-controller="diaryController" ng-cloak>
                <div id="content"></div>
                <div ng-show="loading">
                    <i class="fas fa-spinner fa-2x fa-spin"></i>
                </div>
            </div>
        </div>

    </div>

@endsection
