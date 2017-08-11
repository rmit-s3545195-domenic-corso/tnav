<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin_login');
    }
}