@extends('layouts.app')

@section('title', 'Current Location')

@section('styles')
    <link href="{{ url('/css/interactive_map.css') }}" rel="stylesheet" />
    <link href="{{ url('/css/image_viewer.css') }}"  rel="stylesheet" />
@endsection

@section('content')
    <!-- Custom Search Bar -->
    <div id="search_cont">
        <button class="btn btn-success" id="btn_use_loc" type="button">
            <span class="glyphicon glyphicon-record"></span> Find Near Me
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
        <!-- Container for the enlarged img -->
        <div id="photo_cont">

            <div id="caption"></div>

            <img id="rr_image_display" />

            <div id="photo_counter"><span id="current_photo_number"></span> / <span id="total_photo_number"></span></div>
            <div id='image_view_navigation'>
                <span id="left" class="btn btn-info glyphicon glyphicon-chevron-left" title="Previous Image"></span>
                <span id="close" class="btn btn-danger glyphicon glyphicon-remove" title="Close"></span>
                <span id="right" class="btn btn-info glyphicon glyphicon-chevron-right" title="Next Image"></span>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/tnav.location.js') }}"></script>
    <script src="{{ url('/js/interactive_map.js') }}"></script>
    <script src="{{ url('/js/interactive_map.ui.js') }}"></script>
    <script src="{{ url('/js/InteractiveMap.Navigation.js') }}"></script>
    <script src="{{ url('/js/image_viewer.js') }}"></script>
@endsection
