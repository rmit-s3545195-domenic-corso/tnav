@extends('layouts.app')

@section('title', 'Search Restrooms')

@section('styles')
    <link href="{{ url('/css/admin_search.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <h1>Search Restrooms</h1>
        <div id="search_container">
            <input type="text" id="search_input" class="form-control" placeholder="Enter keywords" />
            <h4 id="search_results_title">Results</h4>
            <div id="results_cont"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/admin_search.js') }}"> </script>
@endsection
