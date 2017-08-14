<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Restroom;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin_login');
    }

    public function edit_restroom(Request $request)
    {
        $restroom = Restroom::find($request->id);

        return view('edit_restroom')->with('restroom', $restroom);
    }
}
