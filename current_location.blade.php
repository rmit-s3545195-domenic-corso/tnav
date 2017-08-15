@extends('layouts.app')

@section('title', 'Current Location')

@section('styles')
    <link href="{{ url('/css/current_location.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <div id="rr_info" class="col">Rest room info</div>
        <!-- Displays map with current location -->
        <div id="map" class="col"></div>
@endsection

@section('scripts')
    <script src="{{ url('/js/tnav.location.js') }}"></script>
    <script src="{{ url('/js/current_location.js') }}"></script>
@endsection
