<?php

namespace App\Http\Controllers;

/* Validator */
use Illuminate\Support\Facades\Validator;

/* Input */
use Illuminate\Support\Facades\Input;

/* Image */
use Image;

/* File */
use Illuminate\Support\Facades\File;

/* Requests */
use Illuminate\Http\Request;
use App\Http\Requests\UpdateManagerPasswordRequest;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\AddProductRequest;

/* Activity Log */
use Spatie\Activitylog\Contracts\Activity; 

/* User Authentication */
use Auth;

/* Mail */
use Mail;
use App\Mail\ActivityReport;
use App\Mail\Alert;

/* Excel */
use Excel;
use App\Exports\ActivityLogExport;

/* Password Hash */
use Illuminate\Support\Facades\Hash;

/* Models */
use App\ActivityLog;
use App\Category;
use App\Count;
use App\Manager;
use App\Product;
use App\Role;
use App\SKUImage;
use App\StockKeepingUnit;
use App\SiteSetting;

class ManagerPagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:manager');
    }
    
    public function showDashboard()
    {
        return view('portal.dashboard');
    }

    public function showUsers(){
        /*- Authorization -*/
        if (Auth::user()->role > 2) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to view the page you are attempting to.");
        }

        return view('portal.users')
        ->with('users', Manager::where([
            ["role", ">=", Auth::user()->role]
            ])
            ->whereIn('state', ['1', '2'])
            ->with('role', 'state')
            ->get()
            ->toArray());
    }

    public function showUser($user_id){

        /*- Authorization & Checks -*/
        if (Auth::user()->role > 2) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to take the action you are attempting to.");
        }

        if (is_null(Manager::find($user_id))) {
            return back()->with("error", "User account not found.");
        }

        if (is_null(Manager::find($user_id)->role < Auth::user()->role)) {
            return back()->with("error", "You are not authorized to view the account you are attempting to");
        }

        return view('portal.view-user')
                ->with('user', Manager::where('id', $user_id)
                    ->with('role', 'state')
                    ->first()
                    ->toArray()
                )->with('roles',  Role::where([
                    ['id', '>=', Auth::user()->role],
                ])->get()
                ->toArray());
    }

    public function processUser(Request $request, $user_id){
        /*- Authorization & Account Check -*/
        if (Auth::user()->role > 2) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to take the action you are attempting to.");
        }

        if (is_null(Manager::find($user_id))) {
            return back()->with("error", "User account not found.");
        }

        if (is_null(Manager::find($user_id)->role < Auth::user()->role)) {
            return back()->with("error", "You are not authorized to view the account you are attempting to");
        }

        switch ($request->user_action) {
            case 'reset_password':
                /*- Update Password -*/
                $password = rand(10000, 99999);
                $hashed_password = Hash::make($password);

                Manager::where('id', $user_id)
                    ->update([
                        "password" => $hashed_password
                    ]);

                /*- Notify user -*/
                $user = Manager::where('id', $user_id)->get()->first();

                $data = array(
                    'subject' => "Password Reset - NewLucky Electricals",
                    'name' => $user->first_name.' '.$user->last_name,
                    'message' => "Dear ".$user->first_name.",<br><br>Your user password has been reset. <br>Access your account with this password : $password<br>If you believe this is a mistake, contact your application manager immediately. <br><br>Regards,<br>NewLucky Electricals Software Support"
                );
        
                Mail::to($user->email, $user->first_name.' '.$user->last_name)
                    ->queue(new Alert($data));

                /*- Log password reset -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'User Password Reset';
                })
                ->log(Auth::guard('manager')->user()->email.' reset user password for account '.$user->email.' of '.$user->first_name.' '.$user->last_name.'.');
 
                /*- Return success -*/
                return back()->with("success", "Password of ".$user->first_name." reset successfully");

                break;
            case 'activate':
                /*- Update State -*/
                Manager::where('id', $user_id)
                    ->update([
                        "state" => "1"
                    ]);

                /*- Notify user -*/
                $user = Manager::where('id', $user_id)->get()->first();

                $data = array(
                    'subject' => "Account Activated - NewLucky Electricals",
                    'name' => $user->first_name.' '.$user->last_name,
                    'message' => "Dear ".$user->first_name.",<br><br>Your user account has been activated. Access to the NewLucky Electricals Manager Portal has been restored. <br>If you believe this is a mistake, contact your application manager immediately. <br><br>Regards,<br>NewLucky Electricals Software Support"
                );
        
                Mail::to($user->email, $user->first_name.' '.$user->last_name)
                    ->queue(new Alert($data));

                /*- Log user deactivated -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'User Account Activated';
                })
                ->log(Auth::guard('manager')->user()->email.' activated user account '.$user->email.' of '.$user->first_name.' '.$user->last_name.'.');
 
                /*- Return success -*/
                return back()->with("success", "Account activated successfully");

                break;
            
            case 'deactivate':
                /*- Update State -*/
                Manager::where('id', $user_id)
                    ->update([
                        "state" => "2"
                    ]);

                /*- Notify user -*/
                $user = Manager::where('id', $user_id)->get()->first();

                $data = array(
                    'subject' => "Account Deactivated - NewLucky Electricals",
                    'name' => $user->first_name.' '.$user->last_name,
                    'message' => "Dear ".$user->first_name.",<br><br>Your user account has been deactivated. You will no longer be able to access the NewLucky Electricals Manager Portal unless it is re-activated. <br>If you believe this is a mistake, contact your application manager immediately. <br><br>Regards,<br>NewLucky Electricals Software Support"
                );
        
                Mail::to($user->email, $user->first_name.' '.$user->last_name)
                    ->queue(new Alert($data));

                /*- Log user deactivated -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'User Account Deactivated';
                })
                ->log(Auth::guard('manager')->user()->email.' deactivated user account '.$user->email.' of '.$user->first_name.' '.$user->last_name.'.');

                /*- Return success -*/
                return back()->with("success", "Account deactivated successfully");

                break;

            case 'delete':
                /*- Update State -*/
                Manager::where('id', $user_id)
                    ->update([
                        "state" => "3"
                    ]);

                /*- Notify user -*/
                $user = Manager::where('id', $user_id)->get()->first();

                $data = array(
                    'subject' => "Account Deleted - NewLucky Electricals",
                    'name' => $user->first_name.' '.$user->last_name,
                    'message' => "Dear ".$user->first_name.",<br><br>Your user account has been deleted. You will no longer be able to access the NewLucky Electricals Manager Portal. <br>If you believe this is a mistake, contact your application manager immediately. <br><br>Regards,<br>NewLucky Electricals Software Support"
                );
        
                Mail::to($user->email, $user->first_name.' '.$user->last_name)
                    ->queue(new Alert($data));

                /*- Log user deletion -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'User Account Deleted';
                })
                ->log(Auth::guard('manager')->user()->email.' deleted user account '.$user->email.' of '.$user->first_name.' '.$user->last_name.'.');

                /*- Return success -*/
                return redirect()->route('manager.show.users')->with("success", $user->first_name."'s account deleted successfully");

                break;

            case 'update':
                /*- Update Details -*/
                Manager::where('id', $user_id)
                    ->update([
                        "first_name" => ucwords($request->first_name),
                        "last_name" => ucwords($request->last_name),
                        "email" => strtolower($request->email),
                        "role" => $request->role
                    ]);

                /*- Notify user -*/
                $data = array(
                    'subject' => "Account Updated - NewLucky Electricals",
                    'name' => ucwords($request->first_name).' '.ucwords($request->last_name),
                    'message' => "Dear ".ucwords($request->first_name).",<br><br>Your user account details have been updated. If you didn't request this change, contact your application manager immediately. <br><br>Regards,<br>NewLucky Electricals Software Support"
                );
        
                Mail::to(strtolower($request->email), ucwords($request->first_name).' '.ucwords($request->last_name))
                    ->queue(new Alert($data));

                /*- Log user activation -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'User Account Updated';
                })
                ->log(Auth::guard('manager')->user()->email.' updated user account '.strtolower($request->email).' of '.ucwords($request->first_name).' '.ucwords($request->last_name).'.');

                /*- Return success -*/
                return back()->with("success", "Account details updated successfully");

                break;
            default:
                /*- Do Nothing -*/
                break;
        }
    }

    public function showAddUser(){
        if (Auth::user()->role > 2) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to view the page you are attempting to.");
        }

        return view("portal.add-user")
                ->with('roles', Role::where([
                        ['id', '>=', Auth::user()->role],
                    ])->get()
                    ->toArray());

    }

    public function processAddUser(AddUserRequest $request){
        if (Auth::user()->role > 2) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to take the action you are attempting to.");
        }

        /*- Create user -*/
        $user = new Manager;
        $user->first_name = ucwords($request->first_name);
        $user->last_name = ucwords($request->last_name);
        $user->email = strtolower($request->email);

        /*-- Generate User Password --*/
        $password = rand(10000, 99999);

        $user->password = Hash::make($password);
        $user->role = $request->role;
        $user->state = 1;
        $user->save();

        /*- Notify new user -*/
        $data = array(
            'subject' => "Account Created - NewLucky Electricals",
            'name' => ucwords($request->first_name).' '.ucwords($request->last_name),
            'message' => "Dear ".ucwords($request->first_name).",<br><br>Your user account has been created successfully. Access it with the password below which can be changed when you sign in.<br><br>Email: ".strtolower($request->email)."<br>Password: $password<br>Access Link: <a href='".url('portal/manager')."'>NewLucky Electricals Manager Portal</a><br><br>Regards,<br>NewLucky Electricals Software Support"
        );

        Mail::to(strtolower($request->email), ucwords($request->first_name).' '.ucwords($request->last_name))
            ->queue(new Alert($data));


        /*- Log new user creation */
        activity()
        ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
        ->tap(function(Activity $activity) {
            $activity->subject_type = 'System';
            $activity->subject_id = '0';
            $activity->log_name = 'User Account Created';
        })
        ->log(Auth::guard('manager')->user()->email.' created a user account '.strtolower($request->email).', for '.ucwords($request->first_name).' '.ucwords($request->last_name).'.');

        return back()->with('success', ucwords($request->first_name)."'s account created successfully.");

    }

    public function showProducts(){
        return view("portal.products")
            ->with("products", Product::where([
                ["state", "=", 1]
            ])
            ->with('skus.images', 'category')
            ->get()
            ->toArray());
    }

    public function showProduct($product_slug){
        if (is_null(Product::where('slug', $product_slug)->first())) {
            return redirect()->back()->with("error", "Product not found");
        }

        $product =  Product::
        where('slug', $product_slug)
        ->with('skus.images', 'category')
        ->first()
        ->toArray();

        
        /*--- Category Options ---*/
        $product["category_options"] = Category::orderBy('description')->where('level', 2)->get()->toArray();

        return view("portal.view-product")
            ->with("product", $product);
    }

    public function processProduct($product_slug, Request $request){
        switch ($request->product_action) {
            case 'update_details':
                /*- check for product -*/
                $product = Product::where('slug', $product_slug)->get()->first();
                if ($product) {
                   /*-- check for product slug --*/
                   if((Product::where([
                        ['name', '=', $request->name]
                    ])->get()->count()) > 1){
                        $product_slug_count = Product::where([
                            ['name', '=', $request->name],
                            ['id', '<>', $product->id]
                        ])->get()->count();
                        $product_slug_count++;
                        $product_slug = str_slug($request->name)."-".$product_slug_count;
                    }else{
                        $product_slug = str_slug($request->name);
                    }
                }

                $product->name = $request->name;
                $product->slug = $product_slug;
                $product->cid = $request->category;
                $product->description = $request->description;
                $product->features = $request->features;
                $product->tags = $request->tags;
                $product->save();

                /*- log update -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'Product Updated';
                })
                ->log(Auth::guard('manager')->user()->email.' updated a product '.$product->id);

                /*- Redirect with success -*/
                return redirect()->route('manager.show.product', $product_slug)->with('success', 'Product details updated successfully.');
                break;
            case 'update_stock':
                /*- check for product -*/
                $product = Product::where('slug', $product_slug)->get()->first();

                /*- Validate images -*/
                for ($i=0; $i < $request->newSKUCount; $i++) { 
                    if (!is_null(Input::file('variantImages'.$i))) {
                        for ($j=0; $j < sizeof(Input::file('variantImages'.$i)); $j++) { 
                            if(Input::file('variantImages'.$i)[$j]->getClientOriginalExtension() != "jpg"){
                                return back()->with("error", "Images must be of type jpg");
                            }
                
                            list($width, $height) = getimagesize(Input::file('variantImages'.$i)[$j]);
                            if ($width != $height or $height < 600) {
                                return back()->with("error", "Images must be minimum height 600px with aspect ratio of 1");
                            }
                
                            if(filesize(Input::file('variantImages'.$i)[$j]) > 5000000){
                                return back()->with("error", "One or more images exceed the allowed size for upload.");
                            }
                        }
                    }
                }

                
                $count = Count::find(1);

                /*- Update old stock -*/
                for ($i=0; $i < $request->oldSKUCount; $i++) { 
                    /*- Check images if images are set -*/
                    $sku = StockKeepingUnit::where('id', $request->input('sku'.$i))->first();
                    $sku->stock_left = $request->input('stock'.$i);
                    $sku->save();

                    /*-Save new images for old stock -*/
                    if (!is_null(Input::file('variantImages'.$i))) {
                        for ($j=0; $j < sizeof(Input::file('variantImages'.$i)); $j++) { 
                            $sku_image = new SKUImage;
                            $sku_image->sku_id = $request->input('sku'.$i);
                            $sku_image->product_id = $product->id;
                            $sku_image->path = $request->input('sku'.$i).rand(1000, 9999);

                            $img = Image::make(Input::file('variantImages'.$i)[$j]);

                            //save original image
                            $img->save('app/assets/img/products/original/'.$sku_image->path.'.jpg');

                            //save main image
                            $img->resize(600, 600);
                            // $img->insert('portal/images/watermark/stamp.png', 'center');
                            $img->save('app/assets/img/products/main/'.$sku_image->path.'.jpg');

                            //save thumbnail
                            $img->resize(300, 300);
                            $img->save('app/assets/img/products/thumbnail/'.$sku_image->path.'.jpg');

                            //store image details
                            $sku_image->save();
                        }
                    }
                }
                /*- Add new stock -*/
                if ($request->oldSKUCount != $request->newSKUCount) {
                    for ($i=$request->oldSKUCount; $i < $request->newSKUCount; $i++) { 
                        if ((ucfirst(trim($request->input('variantDescription'.$i))) != "None") AND ($request->input('stock'.$i) >= 0)) {
                            /*-- insert sku --*/
                            $count->sku++;
                            $sku = new StockKeepingUnit;
                            $sku->id                        = $sku_id = "S-".($count->sku);
                            $sku->product_id            = $product->id;
                            $sku->description   = $request->input('variantDescription'.$i);
                            $sku->stock_left            = $request->input('stock'.$i);
                            $sku->save();
        
                            /*-- save new images --*/
                            if (!is_null(Input::file('variantImages'.$i))) {
                                for ($j=0; $j < sizeof(Input::file('variantImages'.$i)); $j++) {  
                                    $sku_image = new SKUImage;
                                    $sku_image->sku_id = $sku_id;
                                    $sku_image->product_id = $product->id;
                                    $sku_image->path = $sku_id.rand(1000, 9999);

                                    $img = Image::make(Input::file('variantImages'.$i)[$j]);

                                    //save original image
                                    $img->save('app/assets/img/products/original/'.$sku_image->path.'.jpg');

                                    //save main image
                                    $img->resize(600, 600);
                                    // $img->insert('portal/images/watermark/stamp.png', 'center');
                                    $img->save('app/assets/img/products/main/'.$sku_image->path.'.jpg');

                                    //save thumbnail
                                    $img->resize(300, 300);
                                    $img->save('app/assets/img/products/thumbnail/'.$sku_image->path.'.jpg');

                                    //store image details
                                    $sku_image->save();
                                }
                            }
                        }
                    }
                }
                
                $count->save();

                /*- log update -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'Product Stock Updated';
                })
                ->log(Auth::guard('manager')->user()->email.' updated product stock for product '.$product->id);

                /*- Redirect with success -*/
                return redirect()->route('manager.show.product', $product_slug)->with('success', 'Product stock updated successfully.');
                break;
            case 'delete_stock_image':
                /*- select product */
                $product = Product::where('slug', $product_slug)->get()->first();

                /*- select image record -*/
                $image = SKUImage::where('id', $request->image_id)->first();

                /*- delete image  -*/
                $main_image_path = "app/assets/img/products/main/";
                $thumbnail_image_path = "app/assets/img/products/thumbnail/";
                $original_image_path = "app/assets/img/products/original/";

                File::delete($main_image_path.$image->path.'.jpg');
                File::delete($thumbnail_image_path.$image->path.'.jpg');
                File::delete($original_image_path.$image->path.'.jpg');

                /*- delete image record -*/
                $image->delete();


                /*- log image deletion -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'Product Image(s) Deleted';
                })
                ->log(Auth::guard('manager')->user()->email.' deleted product image(s) for product '.$product->id);

                /*- return back with success -*/
                return back()->with("success", "Image(s) deleted successfully.");

                break;
            default:
                # code...
                break;
        }
    }

    public function showAddProduct(){
        return view('portal.add-product')
            ->with('categories', Category::where('level', 2)->get()->toArray());
    }

    public function processAddProduct(AddProductRequest $request){
        /*- check variant images -*/
        $variantImages = 1; //no variant images | one image fits all
        for ($i=1; $i < $request->newSKUCount; $i++) { 
            if ((ucfirst(trim($request->input('variantDescription'.$i))) != "None") AND ($request->input('stock'.$i) >= 0)) {
                if (!is_null(Input::file('variantImages'.$i))) {
                    $variantImages = 0; //each variant has its image
                    break;
                }
            }
        }

        /*- validate variant images -*/
        if ($variantImages == 1) {
            /*-- validate only first set of images --*/
            for ($j=0; $j < sizeof(Input::file('variantImages0')); $j++) { 
                if(Input::file('variantImages0')[$j]->getClientOriginalExtension() != "jpg"){
                    return back()->with("error", "Images must be of type jpg");
                }
    
                list($width, $height) = getimagesize(Input::file('variantImages0')[$j]);
                if ($width != $height or $height < 600) {
                    return back()->with("error", "Images must be minimum height 600px with aspect ratio of 1");
                }
    
                if(filesize(Input::file('variantImages0')[$j]) > 5000000){
                    return back()->with("error", "One or more images exceed the allowed size for upload.");
                }
            }
        } else {
            /*-- validate all images --*/
            for ($i=0; $i < $request->newSKUCount; $i++) { 
                for ($j=0; $j < sizeof(Input::file('variantImages'.$i)); $j++) { 
                    if(Input::file('variantImages'.$i)[$j]->getClientOriginalExtension() != "jpg"){
                        return back()->with("error", "Images must be of type jpg");
                    }
        
                    list($width, $height) = getimagesize(Input::file('variantImages'.$i)[$j]);
                    if ($width != $height or $height < 600) {
                        return back()->with("error", "Images must be minimum height 600px with aspect ratio of 1");
                    }
        
                    if(filesize(Input::file('variantImages'.$i)[$j]) > 5000000){
                        return back()->with("error", "One or more images exceed the allowed size for upload.");
                    }
                }
            }
        }

        /*- Counts -*/
        $count = Count::find(1);
        
        /*--- Validate and generate Product Slug ---*/
        if((Product::where([
            ['name', '=', $request->name]
        ])->get()->count()) > 0){
            $product_slug_count = Product::where([
                ['name', '=', $request->name]
            ])->get()->count();
            $product_slug_count++;
            $product_slug = str_slug($request->name)."-".$product_slug_count;
        }else{
            $product_slug = str_slug($request->name);
        }

        /*--- Generate product id and set detail variables ---*/
        $count = Count::first();
        $count->product++;

        $product = New Product;
        $product_id = "P-".date("Ymd")."-".$count->product;
        $product->id = $product_id;
        $product->name = ucwords(strtolower($request->name));
        $product->slug = $product_slug;
        $product->features = $request->features;
        $product->cid = $request->category;
        $product->description = $request->description;
        $product->tags = $request->tags;
        $product->views = 0;
        $product->state = 1;

        /*- Add SKUs -*/
        $count->sku++;

        /*- Save Default SKU -*/
        $sku = new StockKeepingUnit;
        $sku->id                        = $sku_id = "S-".($count->sku);
        $sku->product_id            = $product_id;
        $sku->description   = $request->input('variantDescription0');
        $sku->stock_left            = $request->input('stock0');
        $sku->save();

        /*- Default SKU Images -*/
        for ($i=0; $i < sizeof(Input::file('variantImages0')); $i++) { 
            $sku_image = new SKUImage;
            $sku_image->sku_id = $sku_id;
            $sku_image->product_id = $product_id;
            $sku_image->path = $sku->id.rand(1000, 9999);

            $img = Image::make(Input::file('variantImages0')[$i]);

            //save original image
            $img->save('app/assets/img/products/original/'.$sku_image->path.'.jpg');

            //save main image
            $img->resize(600, 600);
            // $img->insert('portal/images/watermark/stamp.png', 'center');
            $img->save('app/assets/img/products/main/'.$sku_image->path.'.jpg');

            //save thumbnail
            $img->resize(300, 300);
            $img->save('app/assets/img/products/thumbnail/'.$sku_image->path.'.jpg');

            //store image details
            $sku_image->save();
        }

        if ($variantImages == 1) {
            /*-- For each sku, save the same images in db --*/
            $default_sku_images = SKUImage::where('sku_id', $sku->id)->get()->toArray();

            for ($i=1; $i < $request->newSKUCount; $i++) { 
                if ((ucfirst(trim($request->input('variantDescription'.$i))) != "None") AND ($request->input('stock'.$i) >= 0)) {
                    /*-- insert sku --*/
                    $count->sku++;
                    $sku = new StockKeepingUnit;
                    $sku->id                        = $sku_id = "S-".($count->sku);
                    $sku->product_id            = $product_id;
                    $sku->description   = $request->input('variantDescription'.$i);
                    $sku->stock_left            = $request->input('stock'.$i);
                    $sku->save();

                    /*-- save same images as default --*/
                    for ($j=0; $j < sizeof($default_sku_images); $j++) { 
                        $sku_image = new SKUImage;
                        $sku_image->sku_id = $sku_id;
                        $sku_image->product_id = $product_id;
                        $sku_image->path = $default_sku_images[$j]["path"];
                        $sku_image->save();
                    }
                }
            }
    
        } else {
            /*-- For each sku, save its unique images in db --*/
            for ($i=1; $i < $request->newSKUCount; $i++) { 
                if ((ucfirst(trim($request->input('variantDescription'.$i))) != "None") AND ($request->input('stock'.$i) >= 0)) {
                    /*-- insert sku --*/
                    $count->sku++;
    
                    $sku = new StockKeepingUnit;
                    $sku->id                        = $sku_id = "S-".($count->sku);
                    $sku->product_id            = $product_id;
                    $sku->description   = $request->input('variantDescription'.$i);
                    $sku->stock_left            = $request->input('stock'.$i);
                    $sku->save();

                    /*-- save unique images --*/
                    for ($j=0; $j < sizeof(Input::file('variantImages'.$i)); $j++) { 
                        $sku_image = new SKUImage;
                        $sku_image->product_id = $product_id;
                        $sku_image->sku_id = $sku_id;
                        $sku_image->path = $sku->id.rand(1000, 9999);
            
                        $img = Image::make(Input::file('variantImages'.$i)[$j]);
            
                        //save original image
                        $img->save('app/assets/img/products/original/'.$sku_image->path.'.jpg');
            
                        //save main image
                        $img->resize(600, 600);
                        // $img->insert('portal/images/watermark/stamp.png', 'center');
                        $img->save('app/assets/img/products/main/'.$sku_image->path.'.jpg');
            
                        //save thumbnail
                        $img->resize(300, 300);
                        $img->save('app/assets/img/products/thumbnail/'.$sku_image->path.'.jpg');
            
                        //store image details
                        $sku_image->save();
                    }
                }
            }    
        }
        
        /*- Save Product -*/
        $product->save();
        
        /*- Save Counts -*/
        $count->save();
        /*- Log Activity Export and Clear -*/
        activity()
        ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
        ->tap(function(Activity $activity) {
            $activity->subject_type = 'System';
            $activity->subject_id = '0';
            $activity->log_name = 'Product Added';
        })
        ->log(Auth::guard('manager')->user()->email.' added a product '.$product_id);

        return back()->with("success", "Product ".$product->name." added successfully");
    }

    public function showCategories(){
        return view("portal.categories")
            ->with("categories", Category::with("parent", "children")
                ->get()
                ->toArray());
    }


    public function showCategory($category_slug){
        $category = Category::where("slug", $category_slug)
                    ->with("parent", "children", "products.skus.images")
                    ->get()
                    ->first()
                    ->toArray();



        if ($category["level"] > 1) {
            $parents = Category::where('id', ($category["level"]-1))
                        ->get()
                        ->toArray();
        }else{
            $parents = NULL;
        }

        return view("portal.view-category")
            ->with("category", $category)
            ->with("parents", $parents);
    }

    public function processCategory(Request $request, $category_slug){
        switch ($request->category_action) {
            case 'update':
                /*- Validation -*/
                $validator = Validator::make($request->all(), [
                    'description' => 'required|max:255',
                    'parent' => 'required'
                ]);
        
                if ($validator->fails()) {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }

                $category = Category::where('slug', $category_slug)->get()->first();
                $category_slug = str_slug($request->description);

                /*- Check for duplicate category -*/
                if (Category::where([
                    ["id", "<>", $category->id],
                    ["slug", "=", $category_slug]
                ])->get()->count() > 0) {
                    return back()->with("error", "Category already exists.");
                }

                /*- Update category -*/
                $category->description = ucwords($request->description);
                $category->slug = str_slug($request->description);
                $category->parent = $request->parent;
                $category->save();

                 /*- Log category update -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'Product Category Updated';
                })
                ->log(Auth::guard('manager')->user()->email.' updated a category, '.ucwords($request->description));

                /*- Return success -*/
                return redirect()->route('manager.show.category', $category_slug)->with('success', 'Category successfully updated');

                break;
            
            case 'delete':
                $category = Category::where('slug', $category_slug)->get()->first();
                $category_description = $category->description;

                /*- Check for products -*/
                if (Product::where([
                        ['cid', "=", $category->id]
                    ])->whereIn('state', [1, 2])->get()->count() > 0) {
                    return back()->with("error", "You cannot delete this category because there are products under it.");
                }

                /*- Check for children -*/
                if (Category::where('parent', $category->id)->get()->count() > 0) {
                    return back()->with("error", "You cannot delete this category because there are sub-categories under it.");
                }

                /*- Delete Category -*/
                $category->delete();

                /*- Log category deletion -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'Product Category Deleted';
                })
                ->log(Auth::guard('manager')->user()->email.' deleted a category, '.$category_description);

                /*- Return success -*/
                return redirect()->route('manager.show.categories')->with('success', 'Category, '.$category_description.' successfully deleted');

                break;

            case 'delete_child':
                $category = Category::find($request->child_id);
                $category_description = $category->description;

                /*- Check for products -*/
                if (Product::where([
                        ['cid', "=", $category->id]
                    ])->whereIn('state', [1, 2])->get()->count() > 0) {
                    return back()->with("error", "You cannot delete this category because there are products under it.");
                }

                /*- Delete Category -*/
                $category->delete();

                /*- Log category deletion -*/
                activity()
                ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
                ->tap(function(Activity $activity) {
                    $activity->subject_type = 'System';
                    $activity->subject_id = '0';
                    $activity->log_name = 'Product Category Deleted';
                })
                ->log(Auth::guard('manager')->user()->email.' deleted a category, '.$category_description);

                /*- Return success -*/
                return back()->with('success', 'Category, '.$category_description.' successfully deleted');

                break;
            default:
                # Do absolutely nothing
                break;
        }
    }

    public function showAddCategory(){
        $parents = Category::where('level', 1)
                        ->get()
                        ->toArray();

        return view("portal.add-category")
            ->with("parents", $parents);
    }

    public function processAddCategory(AddCategoryRequest $request){
        /*- Category slug -*/
        $category_slug = str_slug($request->description);

        /*- Duplication check -*/
        if(Category::where('slug', $category_slug)->get()->count() > 0){
            return back()->with("error", "Category already exists");
        }

        /*- Add category -*/
        $category = new Category;
        $category->description = ucwords($request->description);
        $category->slug = $category_slug;
        $category->parent = $request->parent;
        if ($request->parent == 0) {
            $category->level = 1;
        } else {
            $category->level = 2;
        }
        $category->save();

        if ($request->parent != 0) {
            /*- Add Category to parent cna -*/
            $parent = Category::where('id', $request->parent)->first();
            $parent->cna .= $category->id."|";
            $parent->save();
        }

        /*- Log category addition -*/
        activity()
        ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
        ->tap(function(Activity $activity) {
            $activity->subject_type = 'System';
            $activity->subject_id = '0';
            $activity->log_name = 'Product Category Added';
        })
        ->log(Auth::guard('manager')->user()->email.' added a category, '.ucwords($request->description));

        /*- Return success -*/ 
        return back()->with("success", "Category ".ucwords($request->description)." added successfully");

    }

    public function showSettings(){
        /*- Authorization -*/
        if (Auth::user()->role > 2) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to view the page you are attempting to.");
        }

        return view('portal.site-settings')
            ->with('settings', SiteSetting::get()->toArray());
    }

    public function processSettings(Request $request){
        /*- Authorization -*/
        if (Auth::user()->role > 2) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to take the action you are attempting to.");
        }

        SiteSetting::where('id', 1)
            ->update([
                "value" => ucwords(trim($request->scrolling_text))
            ]);


        /*- Log update -*/
        activity()
        ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
        ->tap(function(Activity $activity) {
            $activity->subject_type = 'System';
            $activity->subject_id = '0';
            $activity->log_name = 'Site Settings Updated';
        })
        ->log(Auth::guard('manager')->user()->email.' updated the site settings.');

        /*- Return success -*/
        return back()->with("success", "Site settings updated successfully");
    }

    public function showAccount(){
        return view('portal.account')
                ->with('user', Manager::where('id', Auth::user()->id)->with('role')->first()->toArray());
    }

    public function processAccount(UpdateManagerPasswordRequest $request){
        if ($request->current_password === $request->new_password) {

            /*- Log Password Change Attempt -*/
            activity()
            ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
            ->tap(function(Activity $activity) {
                $activity->subject_type = 'System';
                $activity->subject_id = '0';
                $activity->log_name = 'Manager Password Change Attempt';
            })
            ->log(Auth::guard('manager')->user()->email.' attempted to change their password.');

            return back()->with("error", "New password must be different from old password");
        }

        if (Hash::check($request->current_password, Auth::user()->password)) {
            /*- Update Password -*/
            $manager = Manager::where('id', Auth::user()->id)->first();
            $manager->password = Hash::make($request->new_password);
            $manager->save();

            /*- Log Password Change -*/
            activity()
            ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
            ->tap(function(Activity $activity) {
                $activity->subject_type = 'System';
                $activity->subject_id = '0';
                $activity->log_name = 'Manager Password Change';
            })
            ->log(Auth::guard('manager')->user()->email.' changed their password.');

            return back()->with("success", "Password updated successfully");
        }

        /*- Log Password Change Attempt -*/
        activity()
        ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
        ->tap(function(Activity $activity) {
            $activity->subject_type = 'System';
            $activity->subject_id = '0';
            $activity->log_name = 'Manager Password Change Attempt';
        })
        ->log(Auth::guard('manager')->user()->email.' attempted to change their password.');

        return back()->with("error", "Invalid current password");
    }

    public function showActivityLog(){
        if (Auth::user()->role > 1) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to view the page you are attempting to.");
        }

        return view('portal.activity-log')
                ->with('activity', ActivityLog::all()->toArray());
    }

    public function processActivityLog(){
        if (Auth::user()->role > 1) {
            return redirect()->route("manager.dashboard")->with("error", "You are not authorized to take the action you are attempting to.");
        }

        /*- Export Activity Log -*/
        Excel::store(new ActivityLogExport, '/reports/activity/activity-report-'.date('d-m-Y-his').'.csv');

        /*- Empty Activity Log -*/
        ActivityLog::truncate();

        /*- Send Email -*/
        $data = array(
            'subject' => "NewLucky Electricals Activity Report - ".date("d-m-Y-his"),
            'name' => "MSCS Dev Team",
            'message' => "Dear Dev,<br><br>Activity report has been generated successfully and attached to this email.<br><br>Regards,<br>NewLucky Electricals Software Support"
        );

        Mail::to("dev@michaelselby.me", "Dev")
            ->queue(new ActivityReport($data));

        /*- Log Activity Export and Clear -*/
        activity()
        ->causedBy(Manager::where('id', Auth::guard('manager')->user()->id)->get()->first())
        ->tap(function(Activity $activity) {
            $activity->subject_type = 'System';
            $activity->subject_id = '0';
            $activity->log_name = 'Activity Log Exported and Cleared';
        })
        ->log(Auth::guard('manager')->user()->email.' exported and cleared the activity log.');

        return back()->with("success", "Export generated and Log cleared successfully.");
    }
}
