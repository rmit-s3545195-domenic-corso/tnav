<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Restroom;
use Session;

class AdminController extends Controller
{
    /* list of paths to files (i.e. salt, hashed password) */
    const SALT_PATH = "/app/admin_salt"; 
    const HASHED_PWD_PATH = "/app/admin_password";
    const HASH_RUNS = 20000;

    public function index()
    {
        return view('admin_login');
    }

    public function edit_restroom(Request $request)
    {
        $restroom = Restroom::find($request->id);

        return view('edit_restroom')->with('restroom', $restroom);
    }

    public function search_restrooms(Request $request)
    {
        return view('search_restrooms');
    }

    public function admin_login(Request $request)
    {
        $paramPwd = $request->admin_password;

        if (isset($paramPwd))
        {
            if (self::passwordMatches($paramPwd))
            {
                Session::flash("Authorised", "Logged in as admin");
                $request->session()->put("admin_logged_in", true);
                return redirect('/');
            }

            Session::flash("Unauthorised", "Invalid Password!");

            return redirect('/admin-login');
        }
        
        Session::flash("Blank", "Password cannot be blank!");

        return redirect('/admin-login');

    }

    public function admin_logout(Request $request)
    {
        Session::flush();
        return redirect('/');
    }

    private function passwordMatches(string $plainTextPwd) : bool
    {
        $hashedAdminPwd = file_get_contents(storage_path(self::HASHED_PWD_PATH));
        return ($hashedAdminPwd == self::hashPassword($plainTextPwd));
    }

    private function hashPassword(string $plainTextPwd)
    {
        $hashedPwd = $plainTextPwd;
        $salt = file_get_contents(storage_path(self::SALT_PATH));

        for ($i = 0; $i < self::HASH_RUNS; $i++)
        {
            $hashedPwd = hash('sha256', $hashedPwd.$salt);
        } return $hashedPwd;
    }

    private function addPasswordToFile(string $plainTextPwd)
    {
        file_put_contents(storage_path(self::HASHED_PWD_PATH), self::hashPassword($plainTextPwd));
    }
}
