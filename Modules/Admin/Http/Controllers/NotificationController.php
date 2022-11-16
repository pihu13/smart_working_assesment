<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Validator;
use Toastr;
use Exception;
use App\Models\Notification;
use App\Models\NotificationMessage;
use App\Models\EmailTemplate;
use Hash;
use App\Models\User;
use Mail;
use App\Helpers\Helper;
use DB;

class NotificationController extends Controller {

    public function __construct(User $User, Notification $Notification) {
        $this->User = $User;
        $this->Notification = $Notification;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request) {
        $requestData = $request->all();
        try {
            $notifications = NotificationMessage::orderBy("id", "desc")->get();
            return view('admin::notifications.index', compact("notifications", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create() {
        try {
            $customers = User::role(['Customer'])->where(["is_deleted" => "0", "status" => "1"])->orderBy('id', 'desc')->get();
            return view('admin::notifications.create', compact("customers"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
                    'title' => 'required|string|max:100',
                    'cutomer_id' => 'required',
                    'cutomer_id.*' => 'required',
                    'message' => 'required'
                        ],
                        [
                            'title.required' => 'Please enter title.',
                            'cutomer_id.*' => 'Please select user.',
                            'message.required' => 'Please enter message.'
                        ]
        );

        if ($validator->fails()) {
            Toastr::error('There is some error, Please check the data.', 'Error');
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);
                $userId = Auth::user()->id;

                $userNames = [];
                foreach ($data["cutomer_id"] as $customer) {
                    $getUser = $this->User->userDetails($customer);
                    $userNames[] = $getUser->name;
                    /*
                     * Save Notification For Respected User
                     */
                    $sendData = array(
                        "sender_id" => $userId,
                        "receiver_id" => $customer,
                        "title" => $data['title'],
                        "notification_type" => "is_admin",
                        "notification_from" => "is_admin",
                        "message" => $data['message'],
                        "payload" => "",
                        "read_status" => "1",
                        "status" => "1",
                        "is_delete" => "0",
                    );
                    Notification::create($sendData);

                    /*
                     * Send Email To Customer
                     */

                    $fullName = @$getUser->name;
                    $messageVal = $data['message'];

                    $slug = "customer-notification-email";
                    $mailMessage = EmailTemplate::where('slug', $slug)->first();
                    if (@$mailMessage && !empty($mailMessage->content) && @$mailMessage->status == "1") {
                        $bodySec = str_replace(
                                array("##USER_NAME##", "##MESG##"),
                                array($fullName, $messageVal),
                                @$mailMessage->content
                        );
                        $subject = @$mailMessage->subject;
                        $to = @$getUser->email;
                        $dataVal = ["body" => $bodySec];
                        $this->sendEmail($dataVal, $subject, $to);
                    }
                }

                $usernameImplode = implode(",", $userNames);
                $notificationMsg = array(
                    "user_id" => $userId,
                    "title" => $data['title'],
                    "customer_names" => $usernameImplode,
                    "message" => $data['message'],
                );
                NotificationMessage::create($notificationMsg);

                /*
                 * Sendt Push Notification
                 */
                if (@$data["cutomer_id"]  && count($data["cutomer_id"]) > 0) {
                    foreach ($data["cutomer_id"] as $cutomer_id) {
                        $message = 'You have receive a new notification from admin.';
                        $payload = "";
                        $from = 'is_admin';
                        $pushNotiData = [
                            'receiver_id' => $cutomer_id,
                            'message' => $message,
                            'from' => $from,
                            'payload' => $payload,
                            'title' => 'Admin Notification'
                        ];
                        $this->Notification->sendNotification($pushNotiData);
                    }
                }

                DB::commit();

                Toastr::success('Notification sent successfully.', 'Success');
                return redirect()->route('admin.notification.list')->withSuccess("Notification sent successfully.");
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
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
            $notifications = [];
            if ($slug != null) {
                $notifications = NotificationMessage::where('id', $slug)->first();
            }
            if (@$notifications && !empty($notifications)) {
                return view('admin::notifications.show', compact("notifications"));
            } else {
                Toastr::error('Either something went wrong or invalid access.', 'Success');
                return redirect()->route("admin.notification.list")->with("errors_catch", "Either something went wrong or invalid access.");
            }
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id) {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                NotificationMessage::where('id', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Email Send 
     * @param : $data, $subject, $to
     * @return response
     */

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

}
