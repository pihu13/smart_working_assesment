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
use App\Models\Shop;
use App\Models\TimingShop;
use App\Helpers\Helper;
use Exception;
use Toastr;
use DB;
use App\Exports\ShopExport;
use Maatwebsite\Excel\Facades\Excel;

class ShopController extends Controller {

    public function __construct(Shop $Shop) {
        $this->Shop = $Shop;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request) {
        $requestData = $request->all();
        try {
            $shops = Shop::orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $shops = $shops->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $shops = $shops->get();
            }

            return view('admin::shops.index', compact("shops", "requestData"));
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
            return view('admin::shops.create');
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
        $data["status"] = ($request->status) ? $request->status : 0;
        $data["same_time"] = ($request->same_time) ? $request->same_time : 0;

        $data["store_contact_no"] = str_replace(" ", "", $data["store_contact_no"]);

        $valiKey = [
            'store_name' => 'required|string|max:255|unique:shops',
            'store_logo_db' => 'required',
            'store_owner_name' => 'required',
            'store_email' => 'required|email|unique:shops,store_email',
            'store_contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6:store_contact_no|unique:shops,store_contact_no',
            'store_address' => 'required',
            'status' => 'required|in:0,1',
            'contact_code' => 'required',
            'country_code' => 'required',
        ];

        if ($data["same_time"] == "1") {
            $valiKey["same_start_time"] = 'required';
            $valiKey["same_end_time"] = 'required';
        } else {
            if (@$data["day_name"] && count($data["day_name"]) > 0) {
                foreach ($request->input('day_name') as $key => $value) {
                    $valiKey["day_name.{$key}"] = 'required';
                }
            }

            if (@$data["start_time"] && count($data["start_time"]) > 0) {
                foreach ($request->input('start_time') as $key => $value) {
                    $valiKey["start_time.{$key}"] = 'required';
                }
            }

            if (@$data["end_time"] && count($data["end_time"]) > 0) {
                foreach ($request->input('end_time') as $key => $value) {
                    $valiKey["end_time.{$key}"] = 'required';
                }
            }
        }

        $valiMsg = [
            'store_name.required' => 'Please enter shop name',
            'store_logo_db.required' => 'Please select store logo',
            'store_owner_name.required' => 'Please enter store owner name',
            'store_email.required' => 'Please enter store email',
            'store_contact_no.required' => 'Please enter contact number',
            'store_address.required' => 'Please enter contact number',
            'status.required' => 'Please select store status',
            'contact_code.required' => 'Please enter phone number',
            'country_code.required' => 'Please enter phone number',
        ];

        if ($data["same_time"] == "1") {
            $valiKey["same_start_time.required"] = 'Please select opening time';
            $valiKey["same_end_time.required"] = 'Please select closing time';
        } else {
            if (@$data["day_name"] && count($data["day_name"]) > 0) {
                foreach ($request->input('day_name') as $key => $value) {
                    $valiMsg["day_name.{$key}.required"] = 'Please select day';
                }
            }

            if (@$data["start_time"] && count($data["start_time"]) > 0) {
                foreach ($request->input('start_time') as $key => $value) {
                    $valiMsg["start_time.{$key}.required"] = 'Please select start time';
                }
            }

            if (@$data["end_time"] && count($data["end_time"]) > 0) {
                foreach ($request->input('end_time') as $key => $value) {
                    $valiMsg["end_time.{$key}.required"] = 'Please select end time';
                }
            }
        }

        $validator = Validator::make($data, $valiKey, $valiMsg);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);
                $userId = (@Auth::user()->id) ? Auth::user()->id : "";

                $addressLatLong = Helper::get_let_long(@$data["store_address"]);

                $phone_number = str_replace(" ", "", @$data['store_contact_no']);
                $phone_number = str_replace("-", "", @$phone_number);

                $shopArr = [
                    "store_name" => $data["store_name"],
                    "store_logo" => $data["store_logo_db"],
                    "store_owner_name" => $data["store_owner_name"],
                    "store_email" => $data["store_email"],
                    "store_contact_no" => $phone_number,
                    "country_std_code" => @$data['contact_code'],
                    "country_name" => @$data['country_name'],
                    "country_code" => @$data['country_code'],
                    "store_address" => $data["store_address"],
                    "store_lat" => (@$addressLatLong["lat"]) ? $addressLatLong["lat"] : null,
                    "store_long" => (@$addressLatLong["long"]) ? $addressLatLong["long"] : null,
                    "status" => $data["status"],
                    "discription" => (@$data["description"]) ? @$data["description"] : null,
                ];
                $shop = Shop::create($shopArr);

                /*
                 * Add Shop Timing
                 */
                if ($data["same_time"] == "1") {
                    $dayArr = [1, 2, 3, 4, 5, 6, 7];
                    foreach ($dayArr as $dayArrEach) {
                        $shopTiming = [
                            "shop_id" => $shop->id,
                            "day_name" => $dayArrEach,
                            "start_time" => $data['same_start_time'],
                            "end_time" => $data['same_end_time'],
                            "status" => "1",
                        ];
                        TimingShop::create($shopTiming);
                    }
                } else {
                    if (@$shop && !empty($shop->id)) {
                        if (!empty($data['day_name'])) {
                            $of = 0;
                            foreach ($data['day_name'] as $day_name) {
                                if (!empty($day_name) && !empty($data['start_time'][$of]) && !empty($data['end_time'][$of])) {
                                    $shopTiming = [
                                        "shop_id" => $shop->id,
                                        "day_name" => $day_name,
                                        "start_time" => $data['start_time'][$of],
                                        "end_time" => $data['end_time'][$of],
                                        "status" => "1",
                                    ];
                                    TimingShop::create($shopTiming);
                                }
                                $of++;
                            }
                        }
                    }
                }
                DB::commit();
                Toastr::success("Store created successfully.", 'Success');
                return redirect('admin/shops-list')->withSuccess("Store created successfully.");
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
            $shop = [];
            if ($slug != Null) {
                $shop = Shop::where("slug", $slug)->first();
            }
            if (@$shop && !empty($shop)) {
                return view('admin::shops.show', compact('shop'));
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
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($slug) {
        try {
            $shop = [];
            if ($slug != Null) {
                $shop = Shop::where("slug", $slug)->first();
            }
            if (@$shop && !empty($shop)) {
                return view('admin::shops.edit', compact('shop'));
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
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request) {
        $data = $request->all();
        $shop = Shop::where('slug', $request->slug)->first();

        $data["store_contact_no"] = str_replace(" ", "", $data["store_contact_no"]);

        $valiKey = [
            'store_name' => 'required|string|max:255|unique:shops,store_name,' . @$shop->id,
            'store_owner_name' => 'required',
            'store_email' => 'required|email|unique:shops,store_email,' . @$shop->id,
            'store_contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:4:store_contact_no|unique:shops,store_contact_no,' . @$shop->id,
            'store_address' => 'required',
            'status' => 'required|in:0,1',
            'contact_code' => 'required',
            'country_code' => 'required',
        ];

        if (@$data["day_name"] && count($data["day_name"]) > 0) {
            foreach ($request->input('day_name') as $key => $value) {
                $valiKey["day_name.{$key}"] = 'required';
            }
        }

        if (@$data["start_time"] && count($data["start_time"]) > 0) {
            foreach ($request->input('start_time') as $key => $value) {
                $valiKey["start_time.{$key}"] = 'required';
            }
        }

        if (@$data["end_time"] && count($data["end_time"]) > 0) {
            foreach ($request->input('end_time') as $key => $value) {
                $valiKey["end_time.{$key}"] = 'required';
            }
        }

        $valiMsg = [
            'store_name.required' => 'Please enter shop name',
            'store_owner_name.required' => 'Please enter store owner name',
            'store_email.required' => 'Please enter store email',
            'store_contact_no.required' => 'Please enter contact number',
            'store_address.required' => 'Please enter contact number',
            'status.required' => 'Please select store status',
            'contact_code.required' => 'Please enter contact number',
            'country_code.required' => 'Please enter contact number',
        ];

        if (@$data["day_name"] && count($data["day_name"]) > 0) {
            foreach ($request->input('day_name') as $key => $value) {
                $valiMsg["day_name.{$key}.required"] = 'Please select day';
            }
        }

        if (@$data["start_time"] && count($data["start_time"]) > 0) {
            foreach ($request->input('start_time') as $key => $value) {
                $valiMsg["start_time.{$key}.required"] = 'Please select start time';
            }
        }

        if (@$data["end_time"] && count($data["end_time"]) > 0) {
            foreach ($request->input('end_time') as $key => $value) {
                $valiMsg["end_time.{$key}.required"] = 'Please select end time';
            }
        }

        $validator = Validator::make($data, $valiKey, $valiMsg);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);
                $userId = (@Auth::user()->id) ? Auth::user()->id : "";

                TimingShop::where("shop_id", $shop->id)->delete();

                $addressLatLong = Helper::get_let_long(@$data["store_address"]);

                $phone_number = str_replace(" ", "", @$data['store_contact_no']);
                $phone_number = str_replace("-", "", @$phone_number);

                $shopArr = [
                    "store_name" => $data["store_name"],
                    "store_logo" => (@$data["store_logo_db"]) ? $data["store_logo_db"] : @$data["store_logo_db_old"],
                    "store_owner_name" => $data["store_owner_name"],
                    "store_email" => $data["store_email"],
                    "store_contact_no" => $phone_number,
                    "country_std_code" => @$data['contact_code'],
                    "country_name" => @$data['country_name'],
                    "country_code" => @$data['country_code'],
                    "store_address" => $data["store_address"],
                    "store_lat" => (@$addressLatLong["lat"]) ? $addressLatLong["lat"] : null,
                    "store_long" => (@$addressLatLong["long"]) ? $addressLatLong["long"] : null,
                    "status" => $data["status"],
                    "discription" => (@$data["description"]) ? @$data["description"] : null,
                ];
                Shop::where('slug', $request->slug)->update($shopArr);

                /*
                 * Add Shop Timing
                 */
                if (@$shop && !empty($shop->id)) {
                    if (!empty($data['day_name'])) {
                        $of = 0;
                        foreach ($data['day_name'] as $day_name) {
                            if (!empty($day_name) && !empty($data['start_time'][$of]) && !empty($data['end_time'][$of])) {
                                $shopTiming = [
                                    "shop_id" => $shop->id,
                                    "day_name" => $day_name,
                                    "start_time" => $data['start_time'][$of],
                                    "end_time" => $data['end_time'][$of],
                                    "status" => "1",
                                ];
                                TimingShop::create($shopTiming);
                            }
                            $of++;
                        }
                    }
                }
                DB::commit();
                Toastr::success("Store details updated successfully.", 'Success');
                return redirect('admin/shops-list')->withSuccess("Store details updated successfully.");
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
                $this->Shop->where('slug', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    public function shopStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    $this->Shop->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    $this->Shop->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    public function storeLogo(Request $request) {
        $filename = '';
        $filenameDB = '';
        try {
            if (isset($request->image) && !empty($request->image)) {
                $image_file = $request->image;
                list($type, $image_file) = explode(';', $image_file);
                list(, $image_file) = explode(',', $image_file);
                $image_file = base64_decode($image_file);

                $image_name = time() . '_' . rand(100, 999) . '.png';
                \Storage::disk('public')->put('/uploads/store/' . $image_name, $image_file);

                $filename = asset('/storage/uploads/store/' . $image_name);
                $filenameDB = 'uploads/store/' . $image_name;
                return response()->json(['status' => true, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            } else {
                return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            }
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
        }
    }

    public function imgVideoUploadCk(Request $request) {
        try {
            if ($request->hasFile('upload')) {
                $originName = $request->file('upload')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('upload')->getClientOriginalExtension();
                $fileName = $fileName . '_' . time() . '.' . $extension;

                $request->file('upload')->move(public_path('images'), $fileName);

                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $url = asset('images/' . $fileName);
                $msg = 'Image uploaded successfully';
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

                @header('Content-type: text/html; charset=utf-8');
                echo $response;
            }
        } catch (Exception $ex) {
            $response = "";
            echo $response;
        }
    }

    /*
     * Export Stores
     * @param : $start_date, $end_date
     * @return csv
     */

    public function exportStores(Request $request) {
        try {
            $requestData = $request->all();

            $start_date = $requestData["export_start_date"];
            $end_date = $requestData["export_end_date"];

            return Excel::download(new ShopExport($start_date, $end_date), time() . '-stores.csv');
        } catch (Exception $ex) {
            dd($ex);
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

}
