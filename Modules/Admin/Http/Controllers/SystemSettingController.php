<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Route;
use Auth;
use Validator;
use Mail;
use App\Models\User;
use App\Models\SystemSetting;
use DB;
use Toastr;
use App\Helpers\Helper;

class SystemSettingController extends Controller {

    public function __construct(SystemSetting $SystemSetting) {
        $this->SystemSetting = $SystemSetting;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        try {
            $systemSettingData = SystemSetting::all();
            return view('admin::systemsettings.index', compact('systemSettingData'));
        } catch (\Throwable $th) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('admin::systemsettings.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
                    'option_name' => 'required',
                    'option_value' => 'required',
                    'status' => 'required',
                    'setting_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        }
        try {
            SystemSetting::create($data);
            Toastr::success('Option add successfully.', 'Success');
            return redirect('admin/system-settings')->withSuccess("Option add successfully.");
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request) {
        try {
            $data = $request->all();
            $arr = array();
            $cont = count($request->slug);
            for ($j = 0; $j < $cont; $j++) {
                if (isset($request->value[$j])) {
                    $arr[$j]['value'] = $request->value[$j];
                    $arr[$j]['slug'] = $request->slug[$j];
                    $arr[$j]['setting_type'] = $request->setting_type[$j];
                }
            }
            //dd($arr);

            foreach ($arr as $arrEach) {
                if ($arrEach['setting_type'] == 'smtp') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'stripe') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'socialmediasection') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'currency') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'top-bar') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'email_footer_content') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'customersupport') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'blogs') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'googleservicekeys') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'homecontentsection') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'returnpolicydayssection') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'pointssystems') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                } elseif ($arrEach['setting_type'] == 'donationcheckoutsection') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                }elseif ($arrEach['setting_type'] == 'productdiscounts') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                }elseif ($arrEach['setting_type'] == 'deliverychargesection') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                }elseif ($arrEach['setting_type'] == 'producttaxsec') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                }elseif ($arrEach['setting_type'] == 'productdeliverydaysec') {
                    SystemSetting::where("option_slug", $arrEach['slug'])->update(['option_value' => $arrEach['value']]);
                }
            }

            if ($request->hasFile('value')) {
                $i = 0;
                foreach ($data['value'] as $getdata) {
                    if ($data['setting_type'][$i] == 'sitelogo') {
                        if (!empty($getdata)) {
                            $picturename = $getdata->store('public/uploads/sitelogo/');
                            $picturename = str_replace('public/', '', $picturename);
                            try {
                                SystemSetting::where("option_slug", $data['slug'][$i])->update(['option_value' => $picturename]);
                            } catch (\Exception $e) {
                                Toastr::error('Error in logo images save.', 'Error');
                                return redirect()->back();
                            }
                        }
                    }
                    $i++;
                }
            }

            Toastr::success('Option updated successfully.', 'Success');
            return redirect('admin/system-settings')->withSuccess("Option updated successfully.");
        } catch (\Throwable $th) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    public function OptionStatus($slug) {
        $explode = explode('_', $slug);
        if (trim($explode[1]) == 0 || trim($explode[1]) == 1) {
            try {
                SystemSetting::where('setting_type', $explode[0])->update(['status' => $explode[1]]);
                Toastr::success('Option status has been updated successfully.', 'Success');
                return redirect('admin/system-settings')->withSuccess("Option status has been updated successfully.");
            } catch (\Exception $e) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    public function indexSiteLogo() {
        try {
            $systemSettingData = SystemSetting::where("setting_type", "sitelogo")->get();
            return view('admin::systemsettings.index-site-logo', compact('systemSettingData'));
        } catch (\Throwable $th) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    public function updateSiteLogo(Request $request) {
        try {
            $data = $request->all();
            $cont = count($request->slug);
            if ($request->hasFile('value')) {
                foreach ($data['value'] as $key => $getdata) {
                    if ($data['setting_type'][$key] == 'sitelogo') {
                        if (!empty($getdata)) {
                            $picturename = $getdata->store('public/uploads/sitelogo/');
                            $picturename = str_replace('public/', '', $picturename);

                            try {
                                if (!empty($data['value'][$key])) {
                                    SystemSetting::where("option_slug", $data['slug'][$key])->update(['option_value' => $picturename]);
                                }
                            } catch (\Exception $e) {
                                Toastr::error('Error in logo images save.', 'Error');
                                return redirect()->back();
                            }
                        }
                    }
                }
            }

            Toastr::success('Site logo updated successfully.', 'Success');
            return redirect('admin/site-logo')->withSuccess("Site logo updated successfully.");
        } catch (\Throwable $th) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

}
