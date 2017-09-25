@extends('layouts.app')

@section('title', 'Oops!')

@section('content')
    <div class="container">
        <h2>Page Not Found! - 404</h2>

        <p>
        	The page you requested was not found. If you think you stumbled 
        	upon this through normal use, please make a bug report on our
        	<a href="https://github.com/rmit-s3545195-domenic-corso/tnav">GitHub Repository</a>.
        </p>

        <p>
        	<strong>{{ $exception->getMessage() }}</strong>
        </p>
    </div>
@endsection
