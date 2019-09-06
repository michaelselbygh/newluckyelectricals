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

    public function showProduct(){

    }

    public function showCategory(){
        
    }

    public function showShop(){
        
    }

    public function filterShop(){
        
    }

    public function showAboutUs(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();
        
        return view('main.about-us')
            ->with('categories', $categories)
            ->with('settings', $settings);
    }

    public function showContactUs(){
        
    }

    public function showLocateAStore(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();

        return view('main.locate-a-store')
            ->with('categories', $categories)
            ->with('settings', $settings);
    }
}
