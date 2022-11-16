<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Exports\SubAdminExport;
use Maatwebsite\Excel\Facades\Excel;
use Route;
use Auth;
use Hash;
use Validator;
use Mail;
use App\Models\User;
use App\Models\EmailTemplate;
use DB;
use Toastr;

class SubAdminController extends Controller {

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
            //return $ex;
        }
    }

    public function subAdminList(Request $request) {
        try {
            $requestData = $request->all();
            $subadmin = User::role(['Sub-admin'])->orderBy('id', 'desc')->where(function ($query) use ($request) {
                        if ($request->has('start_date') && $request->has('end_date')) {
                            $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                        }
                    })->get();
            return view('admin::sub-admins.index', compact('subadmin', 'requestData'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    public function subAdminAdd() {
        try {
            $permissions = Permission::orderBy("id", "DESC")->get();
            $perArr = [];
            if (!$permissions->isEmpty()) {
                foreach ($permissions as $per) {
                    $perArr[$per->controller][] = ["name" => $per->name, "id" => $per->id];
                }
            }

            return view('admin::sub-admins.create', compact('permissions', 'perArr'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    public function subAdminAddStore(Request $request) {
        $data = $request->all();
        $key = [
            'username' => 'required|unique:users,username',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:4:phone_number|unique:users,phone_number',
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
            'status' => 'required',
            'photo' => 'required|image',
            'contact_code' => 'required',
            'country_code' => 'required',
            'permission_name.*' => 'required',
        ];

        $val = [
            'username.required' => 'Please enter username',
            'first_name.required' => 'Please enter first name',
            'last_name.required' => 'Please enter last name',
            'email.required' => 'Please enter email address',
            'phone_number.required' => 'Please enter phone number',
            'password.required' => 'Please enter password',
            'confirm_password.required' => 'Please enter confirm password',
            'status.required' => 'Please select status',
            'photo.required' => 'Please select profile photo',
            'contact_code.required' => 'Please enter phone number',
            'country_code.required' => 'Please enter phone number',
            'permission_name.*' => 'Please select permission',
        ];

        $validator = Validator::make($data, $key, $val);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);

                $data = $request->all();
                if ($request->hasFile('photo')) {
                    $picturename = $request->file('photo')->store('public/uploads/users/');
                    $picturename = str_replace('public/', '', $picturename);
                    $picturename = $picturename;
                } else {
                    $picturename = "uploads/users/avatar.png";
                }

                $data["permission_name"][] = "default";
                $permissions = Permission::whereIn("controller", $data["permission_name"])->get();

                $subAdmin = [
                    "username" => @$data['username'],
                    "profile_photo" => $picturename,
                    "name" => @$data['first_name'] . ' ' . @$data['last_name'],
                    "first_name" => @$data['first_name'],
                    "last_name" => @$data['last_name'],
                    "email" => @$data['email'],
                    "phone_number" => @$data['phone_number'],
                    "country_std_code" => @$data['contact_code'],
                    "country_name" => @$data['country_name'],
                    "country_code" => @$data['country_code'],
                    "password" => Hash::make($data['password']),
                    "status" => @$data['status'],
                ];

                $UserData = User::create($subAdmin);
                $UserData->assignRole('sub-admin');
                $UserData->givePermissionTo($permissions);

                if (@$UserData->status == "1") {
                    /*
                     * Send Email To Subadmin Start
                     */
                    $fullName = @$data['first_name'] . ' ' . @$data['last_name'];
                    $username = @$data['username'];
                    $password = @$data['password'];
                    $url = url("/admin/login");

                    $slug = "subadmin-register-email-template";
                    $mailMessage = EmailTemplate::where('slug', $slug)->first();
                    $bodySec = str_replace(
                            array("##NAME##", "##USERNAME##", "##PASSWORD##", "##URL##", "##EMAIL##"),
                            array($fullName, $username, $password, $url, @$request->email),
                            @$mailMessage->content
                    );
                    $subject = @$mailMessage->subject;
                    $to = $request->email;
                    $data = ["body" => $bodySec];

                    $this->sendEmail($data, $subject, $to);
                    /*
                     * Send Email To Subadmin End
                     */
                }
                
                DB::commit();

                Toastr::success('Sub-admin added successfully.', 'Success');
                return redirect('admin/sub-admins')->withSuccess("Sub-admin added successfully!");
            } catch (Exception $ex) {
                DB::rollback();
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }


    public function subAdminView($slug) {
        try {
            $permissions = Permission::orderBy("id", "DESC")->get();
            $perArr = [];
            if (!$permissions->isEmpty()) {
                foreach ($permissions as $per) {
                    $perArr[$per->controller][] = ["name" => $per->name, "id" => $per->id];
                }
            }

            $user = [];
            if ($slug != null) {
                $user = User::role(['sub-admin'])->where('slug', $slug)->first();
            }

            $userPr = [];
            if (isset($user->permissions)) {
                foreach ($user->permissions as $val) {
                    $userPr[$val->controller][] = ["name" => $val->name, "id" => $val->id];
                }
            }


            return view('admin::sub-admins.show', compact('user', 'perArr', 'userPr'));
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    public function subAdminStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    User::role(['sub-admin'])->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    User::role(['sub-admin'])->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    public function subAdminDestroy(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                User::role(['sub-admin'])->where('slug', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    public function subAdminEdit($slug) {
        try {
            $permissions = Permission::orderBy("id", "DESC")->get();
            $perArr = [];
            if (!$permissions->isEmpty()) {
                foreach ($permissions as $per) {
                    $perArr[$per->controller][] = ["name" => $per->name, "id" => $per->id];
                }
            }

            $user = [];
            if ($slug != null) {
                $user = User::role(['sub-admin'])->where('slug', $slug)->first();
            }
            $userPr = [];
            if (isset($user->permissions)) {
                foreach ($user->permissions as $val) {
                    $userPr[$val->controller][] = ["name" => $val->name, "id" => $val->id];
                }
            }


            return view('admin::sub-admins.edit', compact('user', 'permissions', 'userPr', 'perArr'));
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    public function subAdminUpdate(Request $request) {
        $data = $request->all();
        $user = User::where('slug', $request->slug)->first();

        $validator = Validator::make($data, [
                    'username' => 'required|unique:users,username,' . $user->id,
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:4:phone_number|unique:users,phone_number,' . @$user->id,
                    'status' => 'required',
                    'contact_code' => 'required',
                    'country_code' => 'required',
                    'permission_name' => 'required',
                    'permission_name.*' => 'required',
                        ], [
                    'username.required' => 'Please enter username',
                    'first_name.required' => 'Please enter first name',
                    'last_name.required' => 'Please enter last name',
                    'status.required' => 'Please select status',
                    'email.required' => 'Please enter email address',
                    'phone_number.required' => 'Please enter phone number',
                    'contact_code.required' => 'Please select country',
                    'country_code.required' => 'Please select country',
                    'permission_name.*' => 'Please select permission',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                DB::beginTransaction();

                DB::table('model_has_permissions')->where('model_id', $data['user_id'])->delete();

                unset($data['_token']);

                $userData = [];

                if ($request->hasFile('photo')) {
                    $picturename = $request->file('photo')->store('public/uploads/users/');
                    $picturename = str_replace('public/', '', $picturename);
                    $picturename = $picturename;
                } else {
                    $picturename = @$data["photo_old"];
                }

                $subAdmin = [
                    "username" => @$data['username'],
                    "profile_photo" => $picturename,
                    "name" => @$data['first_name'] . ' ' . @$data['last_name'],
                    "first_name" => @$data['first_name'],
                    "last_name" => @$data['last_name'],
                    "email" => @$data['email'],
                    "phone_number" => @$data['phone_number'],
                    "country_std_code" => @$data['contact_code'],
                    "country_name" => @$data['country_name'],
                    "country_code" => @$data['country_code'],
                    "status" => @$data['status'],
                ];

                $UserData = User::role(['sub-admin'])->where('slug', $request->slug)->update($subAdmin);

                $user = User::role(['sub-admin'])->where('slug', $request->slug)->first();
                $data["permission_name"][] = "default";

                $permissions = Permission::whereIn("controller", $data["permission_name"])->get();

                $user->givePermissionTo($permissions);

                DB::commit();

                Toastr::success('Subadmin details has been updated successfully.', 'Success');
                return redirect('admin/sub-admins')->withSuccess("Subadmin details has been updated successfully.");
            } catch (Exception $ex) {
                DB::rollback();
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    public function subAdminChangePass(Request $request) {
        $data = $request->all();
        $key = [
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required'
        ];
        $val = [
            "password.required" => "Please enter password",
            "confirm_password.required" => "Please enter confirm password",
        ];
        $validator = Validator::make($data, $key, $val);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);

                User::role(['sub-admin'])->where('slug', $request->slug)->update(['password' => \Hash::make($data['confirm_password'])]);
                DB::commit();

                Toastr::success('Sub-admin password updated Successfully.', 'Success');
                return redirect('admin/sub-admins')->withSuccess("Sub-admin password updated Successfully.");
            } catch (Exception $ex) {
                DB::rollback();
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

//    public function exportsubAdmin(Request $request) {
//        try {
//            ob_end_clean();
//            $userId = explode(",", $request->user_id);
//            return Excel::download(new UsersExport($userId), time() . $request->user_role_id . '-' . date('Y-m-d-H-i-s') . '.xlsx');
//        } catch (\Throwable $th) {
//            Toastr::error('Either something went wrong or invalid access!', 'Error');
//            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
//        }
//    }
}
