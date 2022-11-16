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
use App\Models\Banner;
use App\Helpers\Helper;
use Exception;
use Toastr;
use DB;

class BannerController extends Controller {

    public function __construct(Banner $Banner) {
        $this->Banner = $Banner;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        try {
            $banners = Banner::orderBy("id", "desc")->get();
            return view('admin::banners.index', compact("banners"));
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
            return view('admin::banners.create');
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

        $valiKey = [
            'title' => 'required|string|max:255|unique:banners',
            'content' => 'required',
            'banner_image_db' => 'required',
            'status' => 'required|in:0,1',
        ];

        $valiMsg = [
            'title.required' => 'Please enter title',
            'content.required' => 'Please enter content',
            'banner_image_db.required' => 'Please select banner image',
            'status.required' => 'Please select status',
        ];
        $validator = Validator::make($data, $valiKey, $valiMsg);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);

                $bannerArr = [
                    "title" => $data["title"],
                    "content" => $data["content"],
                    "banner_image" => (@$data["banner_image_db"]) ? $data["banner_image_db"] : null,
                    "status" => $data["status"],
                ];
                Banner::create($bannerArr);

                DB::commit();
                Toastr::success("Banner added successfully.", 'Success');
                return redirect('admin/banners')->withSuccess("Banner added successfully.");
            } catch (Exception $ex) {
                DB::rollback();
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
            $banner = [];
            if ($slug != Null) {
                $banner = Banner::where("id", $slug)->first();
            }
            if (@$banner && !empty($banner)) {
                return view('admin::banners.show', compact('banner'));
            } else {
                Toastr::error('Either something went wrong!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong!");
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
    public function edit($slug) {
        try {
            $banner = [];
            if ($slug != Null) {
                $banner = Banner::where("id", $slug)->first();
            }
            if (@$banner && !empty($banner)) {
                return view('admin::banners.edit', compact('banner'));
            } else {
                Toastr::error('Either something went wrong!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong!");
            }
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
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
        $banner = Banner::where('id', $request->slug)->first();
        $valiKey = [
            'title' => 'required|string|max:255|unique:banners,title,' . @$banner->id,
            'content' => 'required',
            'status' => 'required|in:0,1',
        ];

        $valiMsg = [
            'title.required' => 'Please enter title',
            'content.required' => 'Please enter content',
            'status.required' => 'Please select status',
        ];
        $validator = Validator::make($data, $valiKey, $valiMsg);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);

                $bannerArr = [
                    "title" => $data["title"],
                    "content" => $data["content"],
                    "banner_image" => (@$data["banner_image_db"]) ? $data["banner_image_db"] : $data["banner_image_db_old"],
                    "status" => $data["status"],
                ];
                Banner::where("id", $request->slug)->update($bannerArr);

                DB::commit();
                Toastr::success("Banner details updated successfully.", 'Success');
                return redirect('admin/banners')->withSuccess("Banner details updated successfully.");
            } catch (Exception $ex) {
                DB::rollback();
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
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
                $this->Banner->where('id', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Update Banner Status
     * @param : $banner_id, $status
     * @return response
     */

    public function bannerStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    $this->Banner->where('id', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    $this->Banner->where('id', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    /*
     * Update Banner Image
     * @param : $banner_img
     * @return response
     */

    public function uploadBannerImg(Request $request) {
        $filename = '';
        $filenameDB = '';
        try {
            if (isset($request->image) && !empty($request->image)) {
                $image_file = $request->image;
                list($type, $image_file) = explode(';', $image_file);
                list(, $image_file) = explode(',', $image_file);
                $image_file = base64_decode($image_file);

                $image_name = time() . '_' . rand(100, 999) . '.png';
                \Storage::disk('public')->put('/uploads/banners/' . $image_name, $image_file);

                $filename = asset('/storage/uploads/banners/' . $image_name);
                $filenameDB = 'uploads/banners/' . $image_name;
                return response()->json(['status' => true, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            } else {
                return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            }
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
        }
    }

}
