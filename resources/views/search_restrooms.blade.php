@extends('layouts.app')


@section('title', 'Search Restrooms')


@section('styles')
    <link href="{{ url('/css/search_restrooms.css') }}" rel="stylesheet" />
@endsection

@section('content')

  <div class="container">
    <h1>Search Restrooms</h1>
    <input type="text" id="rr_search_input" class="form-control" placeholder="Enter a restroom to search" />
    <h4>Restroom List</h4>
    <div id="search_results">
       
    </div>
  </div>



@endsection


@section('scripts')
<script src="{{ url('/js/search_restrooms.js') }}"> </script>

@endsection
