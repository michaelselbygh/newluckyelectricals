<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerPagesController extends Controller
{
    public function showDashboard()
    {
        return view('portal.dashboard');
    }
}
