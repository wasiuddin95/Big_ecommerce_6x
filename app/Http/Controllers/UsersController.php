<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Country;
use Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Exports\usersExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class UsersController extends Controller
{

    public function userLoginRegister()
    {
        $meta_title = "User Login/Register - Great E-commerce Website";
        return view('users.login_register')->with(compact('meta_title'));
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                $userStatus = User::where('email',$data['email'])->first();
                if ($userStatus->status == 0) {
                    return redirect()->back()->with('flash_message_error','Your account has not been activate
                    via Email! Please confirm your email to activate.');
                }
                Session::put('frontSession',$data['email']);

                if(!empty(Session::get('session_id'))){
                $session_id = Session::get('session_id');
                DB::table('carts')->where('session_id',$session_id)->update(['user_email' => $data['email']]);
                }

                return redirect('/cart');
            }else {
                return redirect()->back()->with('flash_message_error','Invalid Username Or Password!!');
            }
        }
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // Check User Allready Exists,
            $userCount = User::where('email',$data['email'])->count();
            if ($userCount>0) {
                return redirect()->back()->with('flash_message_error','Email already exists!!');
            }else {
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                date_default_timezone_set('Asia/Dhaka');
                $user->created_at = date("Y-m-d H:i:s");
                $user->updated_at = date("Y-m-d H:i:s");
                $user->save();

                // // Send Register Email
                // $email = $data['email'];
                // $messageData = ['email'=>$data['email'],'name'=>$data['name']];
                // Mail::send('emails.register',$messageData,function($message) use($email){
                //     $message->to($email)->subject('Registration With Great E-commerce Site');
                // });

                //  Send Confirmation Email
                $email = $data['email'];
                $messageData = ['email'=>$data['email'],'name'=>$data['name'],
                    'code'=>base64_encode($data['email'])];
                Mail::send('emails.confirmation',$messageData,function($message) use($email){
                        $message->to($email)->subject('Confirmation your Great E-commerce account!!');
                });

                return redirect()->back()->with('flash_message_success','Please Confirm Your Email
                 To Active Your Account!!');

                if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                    Session::put('frontSession',$data['email']);

                    if(!empty(Session::get('session_id'))){
                    $session_id = Session::get('session_id');
                    DB::table('carts')->where('session_id',$session_id)->update(['user_email' => $data['email']]);
                    }

                    return redirect('/cart');
                }
            }
        }
    }

    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $userCount = User::where('email',$data['email'])->count();
            if ($userCount == 0) {
                return redirect()->back()->with('flash_message_error','This Email does not exists!');
            }
            
            // Get User Details
            $userDetails = User::where('email',$data['email'])->first();
            
            // Generate Random Password
            $random_password = str_random(8);

            // Generate Random Password
            $new_password = bcrypt($random_password);

            // Update Password
            User::where('email',$data['email'])->update(['password'=>$new_password]);

            // Send Forgot Password Email Code
            $email = $data['email'];
            $name = $userDetails->name;
            $messageData = [
                'email'=>$email,
                'name'=>$name,
                'password'=>$random_password
            ];
            Mail::send('emails.forgotpassword', $messageData, function ($message)use($email) {
                $message->to($email)->subject('New Password - Great E-commerce Site');
            });

            return redirect('login-register')->with('flash_message_success','Please check your Email to get the
            new password and active your account!');
            
        }
        return view('users.forgot_password');
    }

    public function confirmAccount($email)
    {
        $email = base64_decode($email);
        $userCount = User::where('email',$email)->count();
        if ($userCount > 0) {
            $userDetails = User::where('email',$email)->first();
            if ($userDetails->status == 1) {
                return redirect('login-register')->with('flash_message_success','Your Email account is already
                activated. You can login now.');
            }else {
                User::where('email',$email)->update(['status'=>'1']);

                // Send Register Email
                $messageData = ['email'=>$email,'name'=>$userDetails->name];
                Mail::send('emails.welcome_page',$messageData,function($message) use($email){
                    $message->to($email)->subject('Welcome To Great E-commerce Site');
                });

                return redirect('login-register')->with('flash_message_success','Your Email account is
                activated. You can login now.');
            }
        }else {
            abort(404);
        }
    }

    public function account(Request $request)
    {
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $countries = Country::get();
        
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            if (empty($data['name'])) {
                return redirect()->back()->with('flash_message_error','Please Enter Your Name To Update Your Account Details!!');
            }

            if (empty($data['address'])) {
                $data['address'] = '';
            }

            if (empty($data['city'])) {
                $data['city'] = '';
            }

            if (empty($data['state'])) {
                $data['state'] = '';
            }

            if (empty($data['country'])) {
                $data['country'] = '';
            }

            if (empty($data['pincode'])) {
                $data['pincode'] = '';
            }

            if (empty($data['mobile'])) {
                $data['mobile'] = '';
            }

            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->pincode = $data['pincode'];
            $user->mobile = $data['mobile'];
            $user->save();
            return redirect()->back()->with('flash_message_success','Your account details has been updated successfully!!');
        }

        return view('users.account')->with(compact('countries','userDetails'));
    }

    public function chkUserPassword(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $current_password = $data['current_pwd'];
        $user_id = Auth::User()->id;
        $check_password = User::where('id',$user_id)->first();
        if (Hash::check($current_password, $check_password->password)) {
            echo "true"; die;
        }else {
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $old_pwd = User::where('id',Auth::User()->id)->first();
            $current_pwd = $data['current_pwd'];
            if (Hash::check($current_pwd, $old_pwd->password)) {
                // Update password
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id',Auth::User()->id)->update(['password'=>$new_pwd]);
                return redirect()->back()->with('flash_message_success','Password Updated Successfully!!');
            }else {
                return redirect()->back()->with('flash_message_error','Current Password is Incorrect!!');
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::forget('frontSession');
        Session::forget('session_id');
        return redirect('/');
    }

    public function checkEmail(Request $request)
    {
        // Check if User Allready Exists,
        $data = $request->all();
        $userCount = User::where('email',$data['email'])->count();
        if ($userCount>0) {
            echo "false";
        }else {
            echo "true"; die;
        }
    }

    public function viewUsers()
    {
        if (Session::get('adminDetails')['users_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $users = User::get();
        return view('admin.users.view_users')->with(compact('users'));
    }

    public function exportUsers()
    {
        return Excel::download(new usersExport,'users.xlsx');
    }

    public function viewUsersCharts()
    {
        $current_month_users = User::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)->count();
        $last_month_users = User::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(1))->count();
        $last_to_last_month_users = User::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(2))->count();
        return view('admin.users.view_users_charts')->with(compact('current_month_users','last_month_users',
            'last_to_last_month_users'));
    }

    public function viewUsersCountriesCharts()
    {
        $getUserCountries = User::select('state',DB::raw('count(state) as count'))
                ->groupBy('state')->get();
        $getUserCountries = json_decode(json_encode($getUserCountries),true);
        // echo $getUserCountries[0]['country']; die;
        // echo "<pre>"; print_r($getUserCountries); die;
        return view('admin.users.view_users_countries_charts')->with(compact('getUserCountries'));
    }












}
