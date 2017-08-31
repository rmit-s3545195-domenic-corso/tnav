<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Restroom;
use Session;

class AdminController extends Controller
{
    /* List of paths to files relative to /storage */
    const SALT_PATH = "/app/admin_salt";
    const HASHED_PWD_PATH = "/app/admin_password";
    const HASH_RUNS = 20000;

    public function login(Request $request)
    {
        /* Get password entered by user */
        $paramPwd = $request->admin_password;

        /* If they have provided a password */
        if (isset($paramPwd)) {
            /* If the password is correct, assign session variable and redirect
            back to the Home */
            if (self::passwordMatches($paramPwd)) {
                Session::flash("flash_success", "Logged in as an Administrator.");
                $request->session()->put("admin", true);
                return redirect('/');
            }
            /* If the password is not correct, redirect back to the login
            page and show errors */
            else {
                Session::flash("flash_invalid_pwd", "Incorrect Password!");
                return redirect('/admin-login');
            }
        }

        /* In this case, user has not provided a password so redirect back to
        the login page with appropriate error */
        Session::flash("flash_blank_pwd", "Password cannot be blank!");
        return redirect('/admin-login');
    }

    /* Returns true/false if password provided is correct */
    private function passwordMatches(string $plainTextPwd) : bool
    {
        $hashedAdminPwd = trim(file_get_contents(storage_path(self::HASHED_PWD_PATH)));
        return ($hashedAdminPwd == trim(self::hashPassword($plainTextPwd)));
    }

    public static function hashPassword(string $plainTextPwd) : string
    {
        $hashedPwd = $plainTextPwd;
        $salt = file_get_contents(storage_path(self::SALT_PATH));

        for ($i = 0; $i < self::HASH_RUNS; $i++)
        {
            $hashedPwd = hash('sha256', $hashedPwd.$salt);
        } return $hashedPwd;
    }
}
