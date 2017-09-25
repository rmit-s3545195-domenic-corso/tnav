@extends('layouts.app')

@section('title', 'Oops!')

@section('content')
    <div class="container">
        <h2>Oops! Something bad happened... - 500</h2>

        <p>
        	To be honest, we aren't too sure what just happened. If you think you stumbled 
        	upon this through normal use, please make a bug report on our
        	<a href="https://github.com/rmit-s3545195-domenic-corso/tnav">GitHub Repository</a>.
        </p>

        <p>
        	<strong>{{ $exception->getMessage() }}</strong>
        </p>
    </div>
@endsection
