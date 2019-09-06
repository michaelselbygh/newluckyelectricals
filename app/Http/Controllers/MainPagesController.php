<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/* Models */
use App\Category;
use App\SiteSetting;

class MainPagesController extends Controller
{
    public function home(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();

        return view('main.home')
            ->with('categories', $categories)
            ->with('settings', $settings);
    }
}
