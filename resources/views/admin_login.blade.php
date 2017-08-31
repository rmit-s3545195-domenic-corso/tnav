@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <div class="container">
        <!-- Flash messages -->
        @if (Session::has('flash_invalid_pwd'))
            <div class="alert alert-danger">
                <strong>{{ Session::get('flash_invalid_pwd') }}</strong>
            </div>
        @endif
        @if (Session::has('flash_blank_pwd'))
            <div class="alert alert-danger">
                <strong>{{ Session::get('flash_blank_pwd') }}</strong>
            </div>
        @endif

        <h1>Admin Login</h1>
        <form action="{{ url('/admin-login') }}" method="post">
            <div class="form-group">
                <label for="admin_password">Password</label>
                <input type="text" name="admin_password" class="form-control" id="admin_password">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info">Login</button>
            </div>
            {{ csrf_field() }}
        </form>
    </div>
@endsection
