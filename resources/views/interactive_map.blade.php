@extends('layouts.app')

@section('title', 'Current Location')

@section('styles')
    <link href="{{ url('/css/interactive_map.css') }}" rel="stylesheet" />
    <link href="{{ url('/css/image_viewer.css') }}"  rel="stylesheet" />
    <link href="{{ url('/css/reviews.css') }}"  rel="stylesheet" />
@endsection

@section('content')
    <div id="reviews_cont">
        <div id="reviews_cont_controls">
            <span id="reviews_cont_close_btn" class="glyphicon glyphicon-remove"></span>
        </div>
        <div id="reviews_cont_add_a_review_title">Add a review</div>
        <div id="reviews_cont_stars_cont">
            <span class="glyphicon glyphicon-star"></span>
            <span class="glyphicon glyphicon-star"></span>
            <span class="glyphicon glyphicon-star"></span>
            <span class="glyphicon glyphicon-star-empty"></span>
            <span class="glyphicon glyphicon-star-empty"></span>
        </div>
        <div id="reviews_cont_tell_us_what_you_think_title">What do you think about <span id="reviews_cont_restroom_name">AMF Bowling</span> restroom?</div>
        <input id="reviews_cont_author_inp" type="text" class="form-control" placeholder="Your name - leave blank for anonymous" />
        <textarea id="reviews_cont_body_inp" class="form-control" placeholder="Tell us more" ></textarea>
        <div style="text-align: right">
            <button id="reviews_cont_submit_btn" class="btn btn-success">Submit</button>
        </div>
        <div id="reviews_cont_all_reviews_title">All Reviews</div>
        <div id="reviews_cont_all_reviews_cont">
            <div class="restroom_review">
                <div class="restroom_review_author">Domenic Corso</div>
                -
                <div class="restroom_review_stars">
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star-empty"></span>
                    <span class="glyphicon glyphicon-star-empty"></span>
                </div>
                <div class="restroom_review_body">
                    Yeah mate there is bloody shit on the walls!
                </div>
                <div class="restroom_review_added">2 months ago</div>
                <div class="restroom_review_report">Report</div>
            </div>
            <div class="restroom_review">
                <div class="restroom_review_author">Domenic Corso</div>
                - 
                <div class="restroom_review_stars">
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star-empty"></span>
                    <span class="glyphicon glyphicon-star-empty"></span>
                </div>
                <div class="restroom_review_body">
                    Yeah mate there is bloody shit on the walls!
                </div>
                <div class="restroom_review_added">2 months ago</div>
                <div class="restroom_review_report">Report</div>
            </div>
        </div>
    </div>
    <!-- Custom Search Bar -->
    <div id="search_cont">
        <button class="btn btn-success" id="btn_use_loc" type="button">
            <span class="glyphicon glyphicon-record"></span> Find Near Me
        </button>
        <strong id="static_or_text">OR</strong>
        <input type="text" id="inp_search" placeholder="Enter keywords" />
        <button class="btn btn-default" id="btn_search" type="button" onclick="returnAddress();">
        Search
        </button>
    </div>

    <!-- Map and Search Results -->
    <div id="map_results_cont">
        <div id="map"></div>
        <div id="results">
            <button id="end_nav_btn" class="btn btn-danger">End Navigation</button>
        </div>
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
    <script src="{{ url('/js/tnav.interactiveMap.js') }}"></script>
    <script src="{{ url('/js/tnav.interactiveMap.ui.js') }}"></script>
    <script src="{{ url('/js/tnav.interactiveMap.navigation.js') }}"></script>
    <script src="{{ url('/js/tnav.interactiveMap.reviews.js') }}"></script>
    <script src="{{ url('/js/lib/imageViewer.js') }}"></script>
    <script>
        tnav.interactiveMap.init();
    </script>
@endsection
