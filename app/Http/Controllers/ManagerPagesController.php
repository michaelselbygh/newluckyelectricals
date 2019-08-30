<?php

namespace App\Http\Controllers;

/* Validator */
use Illuminate\Support\Facades\Validator;

/* Requests */
use Illuminate\Http\Request;
use App\Http\Requests\UpdateManagerPasswordRequest;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\AddCategoryRequest;

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

    }

    public function processProducts(){

    }

    public function showProduct(){

    }

    public function processProduct(){

    }

    public function showAddProduct(){

    }

    public function processAddProduct(){

    }

    public function showCategories(){
        return view("portal.categories")
            ->with("categories", Category::with("parent", "children")
                ->get()
                ->toArray());
    }


    public function showCategory($category_id){
        $category = Category::where("id", $category_id)
                    ->with("parent", "children", "products")
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

    public function processCategory(Request $request, $category_id){
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

                $category = Category::find($category_id);
                $category_slug = str_slug($request->description);

                /*- Check for duplicate category -*/
                if (Category::where([
                    ["id", "<>", $category_id],
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
                return back()->with('success', 'Category successfully updated');

                break;
            
            case 'delete':
                $category = Category::find($category_id);
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
