@extends('layouts.app')

@section('title', 'Restroom List')

@section('content')
    <div class="container">
        <h1>Restroom List</h1>

        @foreach($restrooms as $r)
            {{ $r }}
            <a href="{{ url('/edit/' . $r->id) }}">
                <button class="btn btn-info">Edit</button>
            </a>
        @endforeach
    </div>
@endsection
