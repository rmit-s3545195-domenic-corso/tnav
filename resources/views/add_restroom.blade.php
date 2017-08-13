@extends('layouts.app')

@section('title', 'Add Restroom')

@section('styles')
    <link href="{{ url('/css/restroom_input.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <h1>Add Restroom</h1>
        <form action="{{ url('/add') }}" method="post">
            @include('partials.restroom_input')
            {{ csrf_field() }}
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/restroom_input.js') }}"></script>
@endsection
