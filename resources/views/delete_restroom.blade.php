@extends('layouts.app')


@section('title', 'Delete Restroom')


@section('styles')
    <link href="{{ url('/css/delete_restroom.css') }}" rel="stylesheet" />
@endsection

@section('content')

  <div class="container">
    <h1>Delete Restroom</h1>
    <input type="text" id="rr_search_input" class="form-control" placeholder="Enter a restroom to delete" />
    <h4>Restroom List</h4>
    <div id="search_results">
        <div class="search_result">
            <div class="ss_rr_info_cont">
                <h6>Restroom #1</h6>
                <p>Name: Doms Toilet</p>
            </div>
            <div class="ss_rr_delete_cont">
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
        <div class="search_result">
            <div class="ss_rr_info_cont">
                <h6>Restroom #2</h6>
                <p>Name: Varsha Toilet</p>
            </div>
            <div class="ss_rr_delete_cont">
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
  </div>



@endsection


@section('scripts')
<script src="{{ url('/js/delete_restroom.js') }}"> </script>

@endsection
