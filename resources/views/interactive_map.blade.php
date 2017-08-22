@extends('layouts.app')

@section('title', 'Current Location')

@section('styles')
    <link href="{{ url('/css/interactive_map.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Custom Search Bar -->
    <div id="search_cont">
        <button class="btn btn-success" id="btn_use_loc" type="button">
            <span class="glyphicon glyphicon-record"></span>
                Find Near Me
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
        <div id="results">
            <div class="result_container">
                <div class="result_header">
                    <div class="results_rr_name">Restroom Name</div>
                    <div class="result_stars_cont">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                    </div>
                </div>
                <div class="results_tag_cont">
                    <img src="{{ url('/img/baby.png') }}" alt="tag" />
                    <img src="{{ url('/img/unisex.png') }}" alt="tag" />
                    <img src="{{ url('/img/syringe.png') }}" alt="tag" />
                    <img src="{{ url('/img/wheelchair.png') }}" alt="tag" />
                </div>
                <div class="results_desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque est enim, volutpat ut interdum vel, pretium eget odio. Nulla molestie lacinia finibus. Curabitur neque ex, porttitor sit amet tincidunt at, maximus ut eros. Fusce efficitur mi justo, ac auctor est porttitor in. Sed consectetur eros arcu. Aliquam cursus volutpat ligula, quis congue elit mattis sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In at dolor at odio imperdiet sollicitudin vel a sapien. Nullam et purus in est accumsan condimentum quis vitae nulla.</div>
                <div class="results_photos_cont">
                    <img src="{{ url('/img/rr_photo_1.jpg') }}" alt="tag" />
                    <img src="{{ url('/img/rr_photo_2.jpg') }}" alt="tag" />
                    <img src="{{ url('/img/rr_photo_3.jpg') }}" alt="tag" />
                    <img src="{{ url('/img/rr_photo_4.jpg') }}" alt="tag" />
                    <img src="{{ url('/img/rr_photo_5.jpg') }}" alt="tag" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/tnav.location.js') }}"></script>
    <script src="{{ url('/js/interactive_map.js') }}"></script>
    <script src="{{ url('/js/interactive_map.ui.js') }}"></script>
@endsection
