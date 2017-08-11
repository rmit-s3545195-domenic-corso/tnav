@extends('layouts.app')

@section('title', 'Edit Restroom')

@section('styles')
    <link href="{{ url('css/restroom_input.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <h1>Edit Restroom</h1>
        @include('partials.restroom_input')
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/restroom_input.js') }}"></script>
@endsection
