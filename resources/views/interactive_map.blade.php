@extends('layouts.app')

@section('title', 'Current Location')

@section('styles')
    <link href="{{ url('/css/interactive_map.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Custom Search Bar -->
    <div id="search_cont">
        <button class="btn btn-success" id="btn_use_loc" type="button">
            <span class="glyphicon glyphicon-record"></span>Find Near Me
        </button>
        <strong id="static_or_text">OR</strong>
        <input type="text" id="inp_search" placeholder="Enter keywords" />
        <button class="btn btn-default" id="btn_search" type="button">
        Search
        </button>
    </div>

    <!-- Map and Search Results -->
    <div id="map_results_cont">
        <div id="map"></div>
        <div id="results"></div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/tnav.location.js') }}"></script>
    <script src="{{ url('/js/interactive_map.js') }}"></script>
    <script src="{{ url('/js/interactive_map.ui.js') }}"></script>
@endsection
