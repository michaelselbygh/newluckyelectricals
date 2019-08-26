<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class ManagerLoginController extends Controller
{
    public function index()
    {
        if(Auth::user()){
            /*- User already logged in. Redirect. -*/
            return redirect()->route('manager.dashboard');
        }
        /*- Show login screen -*/
        return view('portal.login.index');
    }

    public function processManagerLogin(Request $request)
    {

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {

            /*- Login attempt unsuccessful -*/
            
            /*-- Redirect to manager dashboard --*/
            return redirect()->route('manager.dashboard');
        }

        /*- Login attempt unsuccessful -*/
        return back()->with("error", "Invalid credentials.");
    }

   
}
