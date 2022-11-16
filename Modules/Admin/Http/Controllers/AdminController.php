<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Password_reset;
use Auth;
use Validator;
use Exception;
use App\Models\User;
use App\Models\UserEnquiry;
use App\Models\CancerType;
use App\Models\EnquiryImage;
use App\Helpers\Helper;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Password;
use App\Models\SystemSetting;
use Mail;
use Toastr;
use Hash;

class AdminController extends Controller {
    /*
     * Admin/Sub-admin Dashboard
     * @return response
     */

    public function index() {
        try {
            return view('admin::dashboard');
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Admin/Sub-admin Dashboard
     * @return response
     */

    public function dashboard() {
        try {
            //Customer
            $customers = User::role(['Customer'])->count();
            //Subadmin
            $subadmins = UserEnquiry::count();

            return view('admin::dashboard', compact("customers", "subadmins"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Admin/Sub-admin Login Redirection
     * @return response
     */

    public function login(Request $request) {

        if (Auth::user() && Auth::user()->roles[0]->id == 1) {
            //return view('admin::dashboard');
            return redirect('admin/dashboard');
        } else if (Auth::user() && Auth::user()->roles[0]->id == 3) {
            return redirect('admin/dashboard');
        } else {
            Auth::guard('web')->logout();
            $request->session()->flush();
            $request->session()->regenerate();
            //return redirect('admin::auth/login');
            return view('admin::auth/login');
        }
    }

    
    public function enquiry(Request $request) {

           $cancers = CancerType::where("is_deleted", "0")->orderBy('id', 'desc')->get();
        
            return view('admin::auth/enquiry', compact('cancers'));
        
    }

    public function enquiry_post(Request $request) {
        $inputVal = $request->all();
        $data = $request->all();
        
        $valiKey = [
            'name' => 'required',
            'email' => 'required|email|unique:user_enquiries,email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zipcode' => 'required|max:6:zipcode',
            'address' => 'required',
            'enquiry_image.*' => 'required|image|mimes:JPG,PNG,jpg,png,jpeg,gif,svg,mp3,mp4|max:10240'
        ];
        $valiMsg = [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email',
            'password.required' => 'Please enter password',
            'state.required' => 'Please enter state',
            'city.required' => 'Please enter city',
            'zipcode.required' => 'Please enter zipcode',
            'address.required' => 'Please enter address',
            'enquiry_image.required' => 'Please select images and mix size is 10MB',
        ];
        $validator = Validator::make($inputVal, $valiKey, $valiMsg);

      
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
             
                
                $customer = [
                    "name" => @$data['name'],
                    "email" => @$data['email'],
                    "password" => Hash::make(@$data['password']),
                    "state" => @$data['state'],
                    "city" => @$data['city'],
                    "zipcode" => @$data['zipcode'],
                    "address" => @$data['address'],
                    "phone_number" => @$data['phone_number'],
                    "cancer_type" => @$data['cancer_type'],
                    "inquiry_number" => "EN_".random_int(100000, 999999),
                 
                    
                ];

                $UserData = UserEnquiry::create($customer);
                $images=array();
                if ($request->hasFile('enquiry_image')) {
                       $files = $request->file('enquiry_image');
                    foreach($files as $image){
                        $name=$image->getClientOriginalName();
                        $image->move(public_path().'/images/', $name);  
                        $images[]=$name; 

                      
                    }

                  

                $imgages = [
                       "enquiry_id" =>  $UserData->id,
                       "enqiery_img" => implode("|",$images)

                ];

                    EnquiryImage::Create($imgages);
                } 
              
                Toastr::success('Form Submit successfully.', 'Success');
                return redirect('admin/login')->withSuccess("Form Submit successfully");

            } catch (Exception $ex) {
                die($ex);
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /*
     * Admin/Sub-admin Login
     * @param : $username, $password
     * @return response
     */

    public function admin_login(Request $request) {
        $inputVal = $request->all();
        $valiKey = [
            'email' => 'required',
            'password' => 'required',
        ];
        $valiMsg = [
            'email.required' => 'Please enter username/email',
            'password.required' => 'Please enter password',
        ];
        $validator = Validator::make($inputVal, $valiKey, $valiMsg);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                $username = User::where('username', $inputVal['email'])->first();
                if (isset($username) && !empty($username)) {
                    $userName = $username['email'];
                } else {
                    $userName = $inputVal['email'];
                }

                if (auth()->attempt(array('email' => $userName, 'password' => $inputVal['password']))) {
                    if (Auth::user() && Auth::user()->roles[0]->id == 1) {
                        return redirect('admin/dashboard');
                    } else if (Auth::user() && Auth::user()->roles[0]->id == 3) {
                        return redirect('admin/dashboard');
                    } else {
                        return view('admin::auth/login');
                    }
                } else {
                    return redirect()->back()->withErrors("Email or Password are incorrect.");
                }
            } catch (Exception $ex) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /*
     * Admin/Sub-admin Forgot Password
     * @return response
     */

    public function forgotPassword() {
        return view('admin::auth.passwords.email');
    }

    /*
     * Admin/Sub-admin Update Forgot Password
     * @param : $email
     * @return response
     */

    public function forgotUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                        ], [
                    'email.required' => 'Please enter email address',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                $data = $request->all();
                unset($data['_token']);
                $userData = User::role(['Admin', 'sub-admin'])->where("email", $data['email'])->first();
                if (!empty($userData)) {
                    unset($data['_token']);
                    Password::sendResetLink($data);

                    Toastr::success('Reset password link sent on your email id.', 'Success');
                    return redirect('admin/forgot-password')->withSuccess("Reset password link sent on your email id.");
                } else {
                    Toastr::error('Email address not exist!', 'Error');
                    return redirect()->back()->withErrors("Email address not exist!");
                }
            } catch (Exception $ex) {
                Toastr::error('Something went wrong!', 'Error');
                return redirect()->back()->withErrors("Something went wrong");
            }
        }
    }

    /*
     * Admin/Sub-admin Logout
     * @param : $token
     * @return Redirection
     */

    public function admin_logout(Request $request) {
        try {
            Auth::guard('web')->logout();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect()->guest(route('admin.login'));
        } catch (Exception $ex) {
            return redirect('admin/login')->withSuccess("Logout successfully.");
        }
    }

    /*
     * Admin/Sub-admin Edit Profile
     * @param : $slug
     * @return response
     */

    public function editAdmin() {
        try {
            $slug = Auth::user()->slug;
            $role = Auth::user()->roles[0]->name;
            $adminData = User::role([$role])->where('slug', $slug)->first();
            return view('admin::admin.admin-profile', compact('adminData'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Admin/Sub-admin Update Profile
     * @param : $email,$username,$first_name, $last_name, $phone_number
     * @return response
     */

    public function updateAdmin(Request $request) {
        $data = $request->all();
        $adminData = User::where('slug', $request->slug)->first();
        $validator = Validator::make($data, [
                    'username' => 'required|string|max:45',
                    'first_name' => 'required|string|max:45',
                    'last_name' => 'required|string|max:45',
                    'email' => 'required|email|unique:users,email,' . @$adminData->id,
                    'phone_number' => 'required|unique:users,phone_number,' . @$adminData->id,
                    'contact_code' => 'required',
                    'country_code' => 'required',
                        ], [
                    'username.required' => 'Please enter username',
                    'first_name.required' => 'Please enter first name',
                    'last_name.required' => 'Please enter last name',
                    'email.required' => 'Please enter email',
                    'phone_number.required' => 'Please enter phone number',
                    'contact_code.required' => 'Please enter select country',
                    'country_code.required' => 'Please enter select country',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                unset($data['_token']);
                if ($request->hasFile('photo')) {
                    $picturename = $request->file('photo')->store('public/uploads/users/');
                    $picturename = str_replace('public/', '', $picturename);
                } else {
                    $picturename = $data["old_photo"];
                }


                $fillName = $data['first_name'] . ' ' . $data['last_name'];

                $userData = [
                    "username" => $data["username"],
                    "name" => $fillName,
                    "first_name" => $data["first_name"],
                    "last_name" => $data["last_name"],
                    "email" => $data["email"],
                    "profile_photo" => $picturename,
                    'phone_number' => $data['phone_number'],
                    'country_std_code' => $data['contact_code'],
                    'country_name' => $data['country_name'],
                    'country_code' => $data['country_code'],
                ];

                User::role(['sub-admin', 'Admin'])->where('slug', $request->slug)->update($userData);

                Toastr::success('Details has been updated Successfully.', 'Success');
                return redirect('admin/edit-admin')->withSuccess("Details has been updated Successfully.");
            } catch (\Exception $e) {
                dd($e);
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /*
     * Admin/Sub-admin Change Password
     * @param : $token, $old_pass, $new_pass, $conform_pass
     * @return response
     */

    public function adminChangePassword(Request $request) {
        $data = $request->all();
        $adminData = User::where('slug', $request->slug)->first();
        $validator = Validator::make($data, [
                    'old_password' => [
                        'required', function ($attribute, $value, $fail) {
                            if (!Hash::check($value, Auth::user()->password)) {
                                $fail('Old password does not match');
                            }
                        },
                    ],
                    'password' => 'required|same:confirm_password|min:6',
                    'confirm_password' => 'required',
        ]);
        if ($validator->fails()) {
            return \Redirect::back()->withInput()->withErrors($validator->errors());
        } else {
            try {
                unset($data['_token']);
                User::where('slug', $request->slug)->update(['password' => \Hash::make($data['confirm_password'])]);
                Toastr::success('Password updated Successfully.', 'Success');
                return redirect('admin/edit-admin')->withSuccess("Password updated Successfully.");
            } catch (\Exception $e) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

}
