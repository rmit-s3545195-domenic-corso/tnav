@extends('layouts.app')

@section('title', 'Admin Login')

@section('styles')
<style type="text/css">
    .container {
        width: 400px;
    }
</style>
@endsection

@section('content')
    <div class="container">
    @if (Session::has('Authorised'))
        <div class="alert alert-success">
            <strong>Authorised: </strong> {{ Session::get('Authorised')}}
        </div>
    @elseif (Session::has('Unauthorised'))
        <div class="alert alert-danger">
            <strong>Unauthorised: </strong> {{ Session::get('Unauthorised')}} 
        </div>
    @elseif (Session::has('Blank'))
        <div class="alert alert-danger">
            <strong>Unauthorised: </strong> {{ Session::get('Blank')}} 
        </div>
    @endif
        <h1>Admin Login</h1>
        <form action="{{ url('/admin') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="text" name="admin_password" class="form-control" id="admin_password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info">Login</button>
        </div>
        </form>
    </div>
@endsection
