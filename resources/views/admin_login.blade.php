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
        <h1>Admin Login</h1>
        <div class="form-group">
            <label for="admin_username">Username</label>
            <input type="text" name="admin_username" class="form-control" id="admin_username">
        </div>
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="text" name="admin_password" class="form-control" id="admin_password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info">Login</button>
        </div>
    </div>
@endsection
