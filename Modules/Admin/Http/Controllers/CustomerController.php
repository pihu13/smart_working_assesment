<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Route;
use Auth;
use Hash;
use Validator;
use Mail;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\UserProfile;
Use App\Helpers\Helper;
use DB;
use Toastr;

class CustomerController extends Controller {

    public function __construct(User $User) {
        $this->User = $User;
    }

    public function sendEmail($data, $subject, $to) {
        try {
            Mail::send('emails.email_template', $data, function ($message) use ($subject, $to) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
                $message->to($to);
                $message->subject($subject . ' :Welcome to ' . env('APP_NAME'));
            });
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request) {
        try {
            $requestData = $request->all();

            $customers = User::role(['Customer'])->where("is_deleted", "0")->orderBy('id', 'desc');
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $customers = $customers->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $customers = $customers->get();
            }

            return view('admin::customers.index', compact('customers', 'requestData'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create() {
        try {
            return view('admin::customers.create');
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request) {
        $data = $request->all();

      

        $data["gender"] = ($request->gender) ? $request->gender : null;
        $data["status"] = ($request->status) ? $request->status : 0;

        $key = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
           // 'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6:phone_number|unique:users,phone_number',
           // 'password' => 'required|same:confirm_password',
            //'confirm_password' => 'required',
            'status' => 'required|in:0,1',
           // 'gender' => 'required|in:1,2,3',
           // 'contact_code' => 'required',
            //'country_code' => 'required',
        ];

        $val = [
            'name.required' => 'Please enter full name',
            'phone_number.required' => 'Please enter phone number',
            'password.required' => 'Please enter password',
            'confirm_password.required' => 'Please enter confirm password',
            'status.required' => 'Please select status',
            'gender.required' => 'Please select gender',
            'contact_code.required' => 'Please enter phone number',
            'country_code.required' => 'Please enter phone number',
        ];

        $validator = Validator::make($data, $key, $val);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);

              

                
                if ($request->hasFile('photo')) {

                    

                    $image = $request->file('photo');
                   $name = time().'.'.$image->getClientOriginalExtension();
                   $destinationPath = public_path('/images');
                   $image->move($destinationPath, $name);
                   $picturename = $name;
               } else {
                $picturename = "images/avatar-1.jpg";
               }


                $usernameArr = explode("@", @$data['email']);
                $username = (@$usernameArr[0]) ? $usernameArr[0] : "";

                $phone_number = str_replace(" ", "", @$data['phone_number']);
                $phone_number = str_replace("-", "", @$phone_number);

                $customer = [
                    "username" => $username,
                    "profile_photo" => $picturename,
                    "name" => @$data['name'],
                    "email" => @$data['email'],
                  
                    "password" => Hash::make(random_int(100000, 999999)),
                    "status" => @$data['status'],
                    
                ];

                

                $UserData = User::create($customer);
                $UserData->assignRole('Customer');

               

               /* if (@$UserData->status == "1") {
                  
                    $fullName = @$data['name'];
                    $username = @$data['username'];
                    $password = @$data['password'];

                    $slug = "customer-register-email-template";
                    $mailMessage = EmailTemplate::where('slug', $slug)->first();
                    $bodySec = str_replace(
                            array("##NAME##", "##USERNAME##", "##PASSWORD##", "##EMAIL##"),
                            array($fullName, $username, $password, @$request->email),
                            @$mailMessage->content
                    );
                    $subject = @$mailMessage->subject;
                    $to = $request->email;
                    $dataVal = ["body" => $bodySec];

                    $this->sendEmail($dataVal, $subject, $to);
                    
                }*/
                DB::commit();

                Toastr::success('Doctor added successfully.', 'Success');
                return redirect('admin/customers-list')->withSuccess("Doctor added successfully!");
            } catch (Exception $ex) {
                DB::rollback();
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($slug) {
        try {
            $user = [];
            if ($slug != null) {
                $user = User::role(['Customer'])->where('slug', $slug)->first();
            }
            if (@$user && !empty($user)) {
                return view('admin::customers.show', compact('user'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($slug) {
        try {
            $customer = [];
            if ($slug != null) {
                $customer = User::role(['customer'])->where('slug', $slug)->first();
            }
            if (@$customer && !empty($customer)) {
                return view('admin::customers.edit', compact('customer'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request) {
        $data = $request->all();
        $user = User::role(['Customer'])->where('slug', $request->slug)->first();

        $validator = Validator::make($data, [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    //'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:4:phone_number|unique:users,phone_number,' . @$user->id,
                    'status' => 'required',
                    
                    'name.required' => 'Please enter full name',
                    'status.required' => 'Please select status',
                    'email.required' => 'Please enter email address',
                    'phone_number.required' => 'Please enter phone number',
                    'contact_code.required' => 'Please enter phone number',
                    'country_code.required' => 'Please enter phone number',
                    'gender.required' => 'Please select gender',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);

                $userData = [];


                

                if ($request->hasFile('photo')) {

                    

                     $image = $request->file('photo');
                    $name = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $name);
                    $picturename = $name;
                } else {
                    $picturename = @$data["photo_old"];
                }

                $phone_number = str_replace(" ", "", @$data['phone_number']);
                $phone_number = str_replace("-", "", @$phone_number);

                $subAdmin = [
                    "profile_photo" => $picturename,
                    "name" => @$data['name'],
                    "email" => @$data['email'],
                 
                    "status" => @$data['status'],
                   
                ];

                $UserData = User::role(['Customer'])->where('slug', $request->slug)->update($subAdmin);

                $userId = @$user->id;
             

                DB::commit();

                Toastr::success('Doctor details has been updated successfully.', 'Success');
                return redirect('admin/customers-list')->withSuccess("Doctor details has been updated successfully.");
            } catch (Exception $ex) {
                DB::rollback();
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                //User::role(['Customer'])->where('slug', $data['slug'])->delete();
                User::role(['Customer'])->where('slug', $data['slug'])->update(["is_deleted" => "1"]);
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Change Customer Status
     * @param : $status
     * @return response
     */

    public function customerStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    User::role(['Customer'])->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    User::role(['Customer'])->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    /*
     * Changes Customer Password
     * @param : $password, $confirm_password
     * @return response
     */

    public function customerChangePass(Request $request) {
        $data = $request->all();

        $key = [
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required'
        ];

        $val = [
            'password.required' => 'Please enter password',
            'confirm_password.required' => 'Please enter confirm password'
        ];

        $validator = Validator::make($data, $key, $val);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                $userData = User::where('slug', $request->slug)->first();

                User::where('slug', $request->slug)->update(['password' => \Hash::make($data['confirm_password'])]);

                Toastr::success('Customer password updated Successfully.', 'Success');
                return redirect('admin/customers-list')->withSuccess("Customer password updated Successfully.");
            } catch (Exception $ex) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /*
     * Soft Delete Customer List
     * @param : $is_deleted
     * @return response
     */

    public function SoftCustomerList(Request $request) {
        try {
            $requestData = $request->all();

            $customers = User::role(['Customer'])->where("is_deleted", "1")->orderBy('id', 'desc');
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $customers = $customers->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('updated_at', '>=', $request->start_date)->whereDate('updated_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $customers = $customers->get();
            }

            return view('admin::customers.softdelete-customers.index', compact('customers', 'requestData'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * View Customer Profile
     * @param : $slug
     * @return response
     */

    public function softDeleteCustomerView($slug) {
        try {
            $user = [];
            if ($slug != null) {
                $user = User::role(['Customer'])->where('slug', $slug)->where("is_deleted", "1")->first();
            }
            if (@$user && !empty($user)) {
                return view('admin::customers.softdelete-customers.show', compact('user'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Restore Soft Delete Customer Profile
     * @param : $slug, $is_deleted
     * @return response
     */

    public function restoreCustomer(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                User::role(['Customer'])->where('slug', $data['slug'])->update(["is_deleted" => "0"]);
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Delete Customer Permanently
     * @param : $product_id, $order_id
     * @return response
     */

    public function destroyPermanently(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                User::role(['Customer'])->where('id', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

}
