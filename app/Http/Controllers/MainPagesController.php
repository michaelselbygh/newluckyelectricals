<?php

namespace App\Http\Controllers;
use Spatie\Activitylog\Contracts\Activity;
use Session; 


/* Models */
use App\Category;
use App\SiteSetting;
use App\StockKeepingUnit;
use App\Product;

/* Requests */
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\FilterShopRequest;

/* Mail */
use Mail;
use App\Mail\Alert;

class MainPagesController extends Controller
{
    public function home(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();

        return view('main.home')
            ->with('categories', $categories)
            ->with('settings', $settings);
    }

    public function showProduct($product_slug, $sku_slug=null){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();
        $product = Product::where('slug', $product_slug)->with('category', 'skus')->first()->toArray();
        $product['features'] = explode('|', $product['features']);

        if ($sku_slug) {
            $product['sku'] = StockKeepingUnit::where('product_id', $product["id"])->where('slug', $sku_slug)->with('images')->first()->toArray();
        } else {
            $product['sku'] = StockKeepingUnit::where('product_id', $product["id"])->with('images')->first()->toArray();
        }

        $product['related'] = Product::where([
            ['cid', '=', $product['cid']],
            ['id', '<>', $product['id']]
            ])->with('category', 'skus')->get();

        return view('main.product')
            ->with('categories', $categories)
            ->with('settings', $settings)
            ->with('product', $product);
    }

    public function showCategory($category_slug){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();
        $category_shop = Category::where('slug', $category_slug)->first();
        

        if (null != (Session::get('seed'))) {
            if((time() - Session::get('seed')) > 3600){
                Session::put('seed', time());
            }
        }else{
            Session::put('seed', time());
        }
        if ($category_shop->level == 1) {
            $category = $category_shop->id;
            $products = Product::inRandomOrder(Session::get('seed'))->where([
                ["state", "=", 1]
            ])
            ->whereIn('cid', function($query) use ($category){
                $query->select('id')
                ->from('categories')
                ->where('parent', '=', $category);
            })
            ->with('skus.images', 'category', 'images')->paginate(16)->onEachSide(2);
        } else {
            $products = Product::inRandomOrder(Session::get('seed'))->where([
                ["state", "=", 1]
            ])
            ->where('cid', $category_shop->id)
            ->with('skus.images', 'category', 'images')->paginate(16)->onEachSide(2);
        }
        
        $settings["shop-category"] = $category_shop->description;

        return view('main.shop')
            ->with('categories', $categories)
            ->with('settings', $settings)
            ->with('products', $products);
    }

    public function showShop(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();

        if (null != (Session::get('seed'))) {
            if((time() - Session::get('seed')) > 3600){
                Session::put('seed', time());
            }
        }else{
            Session::put('seed', time());
        }

        $products = Product::inRandomOrder(Session::get('seed'))->where([
            ["state", "=", 1]
        ])->with('skus.images', 'category', 'images')->paginate(16)->onEachSide(2);

        return view('main.shop')
            ->with('categories', $categories)
            ->with('settings', $settings)
            ->with('products', $products);
    }

    public function filterShop(FilterShopRequest $request){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();
        $search = $request->search;
        if (isset($request->category)) {
            $category = $request->category;
            $products = Product::where([
                ["state", "=", 1]
            ])
            ->whereIn('cid', function($query) use ($category){
                $query->select('id')
                ->from('categories')
                ->where('parent', '=', $category);
            })
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', "%{$search}%");
                $query->orWhere('description', 'LIKE', "%{$search}%");
                $query->orWhere('features', 'LIKE', "%{$search}%");
                $query->orWhere('tags', 'LIKE', "%{$search}%");
            })
            ->with('skus.images', 'category', 'images')->paginate(1000);
        } else {
            $products = Product::where([
                ["state", "=", 1]
            ])->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', "%{$search}%");
                $query->orWhere('description', 'LIKE', "%{$search}%");
                $query->orWhere('features', 'LIKE', "%{$search}%");
                $query->orWhere('tags', 'LIKE', "%{$search}%");
            })
            ->with('skus.images', 'category', 'images')->paginate(1000);
        }

        $settings["search"] = $request->search;
        if (isset($request->category)) {
            $settings["search-category"] = $request->category;
        }

        return view('main.shop')
            ->with('categories', $categories)
            ->with('settings', $settings)
            ->with('products', $products);
    }

    public function showAboutUs(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();
        
        return view('main.about-us')
            ->with('categories', $categories)
            ->with('settings', $settings);
    }

    public function showContactUs(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();
        
        return view('main.contact-us')
            ->with('categories', $categories)
            ->with('settings', $settings);
    }

    public function processContactForm(ContactRequest $request){
        /*- Notify user -*/
        $data = array(
            'subject' => "New Lucky Electricals - Message Received",
            'message' => "Dear ".ucwords($request->name).",<br><br>We have received your contact request sent on ".date("F j, Y, g:i a")." successfully. We will be in touch shortly.<br><br>Regards,<br>New Lucky Electricals Support"
        );

        Mail::to(strtolower($request->email), ucwords($request->first_name).' '.ucwords($request->last_name))
            ->queue(new Alert($data));

        /*- Notify management -*/
        $data = array(
            'subject' => "Contact Request - ".ucwords($request->name),
            'message' => "Dear Support, <br><br>".ucfirst($request->enquiry)."<br><br>Regards,<br>".ucwords($request->name)
        );

        Mail::to("info@newluckyelectricals.com.gh", "New Lucky Electricals")
            ->queue(new Alert($data));

        /*- Log contact request */
        activity()
        ->tap(function(Activity $activity) {
            $activity->causer_type = 'App\User';
            $activity->causer_id = '-';
            $activity->subject_type = 'System';
            $activity->subject_id = '0';
            $activity->log_name = 'Contact Request';
        })
        ->log(strtolower($request->email).' sent a contact request.');

        return back()->with("success", "Your message has been sent successfully, ".ucwords($request->name));
    }

    public function showLocateAStore(){
        $settings["scrolling_text"] = SiteSetting::find(1)->value;
        $categories = Category::where('level', 1)->with('children')->get()->toArray();

        return view('main.locate-a-store')
            ->with('categories', $categories)
            ->with('settings', $settings);
    }
}
