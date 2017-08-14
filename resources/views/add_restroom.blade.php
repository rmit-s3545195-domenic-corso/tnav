@extends('layouts.app')

@section('title', 'Add Restroom')

@section('styles')
    <link href="{{ url('/css/restroom_input.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <h1>Add Restroom</h1>

        <!-- If there are form errors, show these here -->
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <!-- Display 'Add Restroom' form -->
        <form action="{{ url('/add') }}" method="post">
            @include('partials.restroom_input')
            {{ csrf_field() }}
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/js/tnav.location.js') }}"></script>
    <script src="{{ url('/js/restroom_input.js') }}"></script>
@endsection
