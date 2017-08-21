<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Restroom;
use Session;

class AdminController extends Controller
{
    /* list of paths to files (i.e. salt, hashed password) */
    const SALT_PATH = "/var/www/html/tnav/storage/app/SALT.txt"; 
    const ADMIN_HASH_PATH = "/var/www/html/tnav/storage/app/admin_password.txt";     

    public function index()
    {
        return view('admin_login');
    }

    public function edit_restroom(Request $request)
    {
        $restroom = Restroom::find($request->id);

        return view('edit_restroom')->with('restroom', $restroom);
    }

    public function delete_restroom(Request $request)
    {
        return view('delete_restroom');
    }

    public function admin_login(Request $request)
    {   
        if(isset($request->admin_password))
        {
            $HASHED_PASSWORD = self::hashPassword($request->admin_password);

            if(self::checkPassword($HASHED_PASSWORD) == true)
            {
                Session::flash("Authorised", "Logged in as admin");
                $request->session()->put("Admin", "Session has an admin");
                return redirect('/admin-login');
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

    private function checkPassword(string $paramPassword) : bool
    {
        $ADMIN_DETAILS = file_get_contents(self::ADMIN_HASH_PATH);
        if($ADMIN_DETAILS == $paramPassword)
        {
            return true;
        } else { 
            return false; 
        }
    }

    private function hashPassword(string $password)
    {
        $hash_runs = 20000;
        $HASHED_PASSWORD = "";
        $SALT = file_get_contents(self::SALT_PATH);
        for($i = 0; $i < $hash_runs; $i++)
        {
            $HASHED_PASSWORD = hash('sha256', $password.$SALT);
        }
        return $HASHED_PASSWORD;
    }

    private function addPasswordToFile(string $passwordInput)
    {
        $hash_runs = 20000;
        $ADMIN_DETAILS = fopen(storage_path("/app/admin_password.txt"), "w");

        $password = $passwordInput;

        $hashed_txt = "";
        $SALT = file_get_contents(self::SALT_PATH);
        for($i = 0; $i < $hash_runs; $i++)
        {
            $hashed_txt = hash('sha256', $password.$SALT);
        }

        fwrite($ADMIN_DETAILS, $hashed_txt);
        fclose($ADMIN_DETAILS); 
    }
}
