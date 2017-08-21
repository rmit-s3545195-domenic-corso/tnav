@extends('layouts.app')

@section('title', 'Current Location')

@section('styles')
    <link href="{{ url('/css/interactive_map.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div id="fs_container">
        <div id="search_cont">
            <button class="btn btn-default" id="btn_use_loc" type="button">
                <span class="glyphicon glyphicon-record"></span>
                Find Near Me
            </button>
            <strong>OR</strong>
            <input type="text" id="search_inp" placeholder="Enter keywords" />
            <button class="btn btn-default" id="btn_search" type="button">
                Search
            </button>
        </div>
        <div id="restroom_list">
            <button class="btn btn-default" type="button" onclick="InteractiveMap.showInfoOverlay(true)">[Test] Show Info Overlay</button>
            <button class="btn btn-default" type="button" onclick="InteractiveMap.showInfoOverlay(false)">[Test] Hide Info Overlay</button>
        </div>
        <div id="map_info_cont">
            <div id="map"></div>
            <div id="info_overlay">
                <h1>Restroom Info</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce varius quis est eu luctus. Aenean vitae volutpat libero, eget hendrerit ante. Aenean condimentum leo justo, non ullamcorper mi mattis at. In hac habitasse platea dictumst. Nam varius purus in tellus euismod consectetur. Fusce maximus quis massa id tristique. Integer quis semper magna, eget tristique quam. Phasellus fringilla eleifend metus, sit amet aliquet sapien rutrum id. Nunc tempor nisl sed libero congue fringilla. Nullam sagittis consectetur nibh, sed dictum orci facilisis eget. Quisque varius ligula consequat justo semper pellentesque. Nunc nec semper justo, et porttitor elit.</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/tnav.location.js') }}"></script>
    <script src="{{ url('/js/interactive_map.js') }}"></script>
@endsection
