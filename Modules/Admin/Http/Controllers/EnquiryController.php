<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Route;
use Auth;
use Hash;
use Validator;
use Mail;
use App\Models\User;
use App\Models\UserEnquiry;
use App\Models\EnquiryImage;
use App\Helpers\Helper;
use Exception;
use Toastr;
use DB;
use App\Exports\UserEnquiryExport;
use Maatwebsite\Excel\Facades\Excel;

class EnquiryController extends Controller {

    public function __construct(UserEnquiry $UserEnquiry) {
        $this->UserEnquiry = $UserEnquiry;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request) {
        $requestData = $request->all();
        try {
            $userEnquiries = UserEnquiry::orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $userEnquiries = $userEnquiries->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $userEnquiries = $userEnquiries->get();
            }

            return view('admin::user-enquiries.index', compact("userEnquiries", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($slug) {
        try {
            $userEnquiry = [];
            if ($slug != Null) {
                $userEnquiry = UserEnquiry::with('EnquiryImages')->where("id", $slug)->first();
               
            }
            if (@$userEnquiry && !empty($userEnquiry)) {
                return view('admin::user-enquiries.show', compact('userEnquiry'));
            } else {
                Toastr::error('Either something went wrong!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong!");
            }
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
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
                $this->UserEnquiry->where('id', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Export Enquiry
     * @param : $start_date, $end_date
     * @return csv
     */
    
    public function exportEnquiry(Request $request) {
        try {
            $requestData = $request->all();
            
            $start_date = $requestData["export_start_date"];
            $end_date = $requestData["export_end_date"];
            
            return Excel::download(new UserEnquiryExport($start_date, $end_date), time() . '-user-inquiries.csv');
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

}
