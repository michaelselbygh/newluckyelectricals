<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Contracts\Activity; 

use Auth;
use App\Manager;

class ManagerLoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest:manager')->except('logout');
    }

    public function index()
    {
        /*- Show login screen -*/
        return view('portal.login.index');
    }

    public function login(Request $request)
    {
        /*- Validate form data  -*/
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //attempt to log user in
        if(Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){
            
            if(Manager::where('id', Auth::guard('manager')->user()->id)->first()->state != 1){

                /*--- Log user out, account is inactive ---*/
                Auth::guard('manager')->logout();

                /*--- log activity ---*/
                activity()
                    ->tap(function(Activity $activity) {
                    $activity->causer_type = 'App\Manager';
                    $activity->causer_id = '-';
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'Manager Login Attempt';
                })
                ->log($request->email.' attempted to log in as a manager');

                /*- redirect -*/
                return back()->withInput($request->only('email', 'remember'))->with("error", "Account is inactive.");
            }

            /*-- log activity --*/
            activity()
            ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
            ->tap(function(Activity $activity) {
                $activity->subject_type = 'System';
                $activity->subject_id = '0';
                $activity->log_name = 'Manager Login';
            })
            ->log(Auth::guard('manager')->user()->email.' logged in as a manager');
            
            /*-- redirect --*/
            return redirect()->intended(route('manager.dashboard'));
        }

        /*- log activity -*/
        activity()
        ->tap(function(Activity $activity) {
           $activity->causer_type = 'App\Manager';
           $activity->causer_id = '-';
           $activity->subject_type = 'System';
           $activity->subject_id = '0';
           $activity->log_name = 'Manager Login Attempt';
        })
        ->log($request->email.' attempted to log in as a manager');
        
        /*- redirect -*/
        return redirect()->back()->withInput($request->only('email', 'remember'))->with("error", "Invalid login credentials");
    }

    public function logout(){
        if (Auth::check()) {
            /*--- log activity ---*/
            activity()
            ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
            ->tap(function(Activity $activity) {
                $activity->subject_type = 'System';
                $activity->subject_id = '0';
                $activity->log_name = 'Manager Logout';
            })
            ->log(Auth::guard('manager')->user()->email.' logged out as a manager');
        }
        

        Auth::guard('manager')->logout();
        
        return redirect(route('manager.login'));
    }

   
}
