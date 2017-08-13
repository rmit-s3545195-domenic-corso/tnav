<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Restroom;

class RestroomController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), Restroom::getValidationRules());
        dump($validator->fails());
        dd($validator->failed());
    }
}
