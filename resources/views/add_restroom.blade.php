@extends('layouts.app')

@section('title', 'Add Restroom')

@section('styles')
    <link href="{{ url('/css/restroom_input.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <h1>Add Restroom</h1>
        @include('partials.errors')
        @if (Session::has("flash_filecount"))
            <div class="alert alert-danger">
                <strong>{{ Session::get('flash_filecount') }}</strong>
            </div>
        @endif
        <!-- Display 'Add Restroom' form -->
        <form action="{{ url('/add-restroom') }}" method="post" enctype="multipart/form-data">
            @include('partials.restroom_input')
            {{ csrf_field() }}
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/tnav.location.js') }}"></script>
    <script src="{{ url('/js/restroom_input.js') }}"></script>
@endsection
