<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Restroom;
use Session;

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

    public function delete_restroom(Request $request)
    {
        return view('delete_restroom');
    }

    public function admin_login(Request $request)
    {   
        if($request->admin_password != NULL)
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
        } else {
            Session::flash("Blank", "Invalid Password!");
            return redirect('/admin-login');
        }

    }

    public function checkPassword(string $paramPassword)
    {
        $ADMIN_DETAILS = fopen(storage_path("/app/adminDetails.txt"), "r");
        if(fgets($ADMIN_DETAILS) == $paramPassword)
        {
            return true;
        } else { return false; }
    }

    public function hashPassword(string $password)
    {
        $i = 0;
        $HASHED_PASSWORD = "";
        $SALT = fgets(fopen(storage_path("/app/SALT.txt"), "r"));
        while ($i < 20000)
        {
            $HASHED_PASSWORD = $HASHED_PASSWORD.hash('sha256', $password).$SALT;
            $i++;
        }
        return $HASHED_PASSWORD;
    }

    private function addPasswordToFile()
    {
        $ADMIN_DETAILS = fopen(storage_path("/app/adminDetails.txt"), "r");

        $password = $request->admin_password;

        $hashed_txt = "";
        $SALT = fgets(fopen(storage_path("/app/SALT.txt"), "r"));
        $i = 0;
        while ($i < 20000)
        {
            $hashed_txt = $hashed_txt.hash('sha256', $password).$SALT;
            $i++;
        }

        fwrite($adminFile, $hashed_txt);
        fclose($adminFile); 
    }
}
