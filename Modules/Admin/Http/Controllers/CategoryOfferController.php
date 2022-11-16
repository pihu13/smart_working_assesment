<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Route;
use Auth;
use Hash;
use Validator;
use Mail;
use App\Models\CategoryOffer;
use App\Models\Category;
use App\Helpers\Helper;
use Exception;
use Toastr;
use DB;

class CategoryOfferController extends Controller {

    public function __construct(CategoryOffer $CategoryOffer) {
        $this->CategoryOffer = $CategoryOffer;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        try {
            $categoryOffers = CategoryOffer::orderBy("id", "desc")->get();
            return view('admin::category-offers.index', compact("categoryOffers"));
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
            $categories = Category::whereNotIn('id', function($query) {
                        $query->select("category_id")->from("category_parents");
                    })->orderBy('id', 'DESC')->get();

            return view('admin::category-offers.create', compact("categories"));
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
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'offer_type' => 'required|in:1,2',
            'price_discount' => 'required',
            'banner_type' => 'required|in:1,2',
            'valid_from_date' => 'required',
            'valid_to_date' => 'required',
            'status' => 'required',
        ];

        if ($data['banner_type'] == "2") {
            $valiKey['small_banner_img_db'] = 'required';
        } else {
            $valiKey['full_banner_img_db'] = 'required';
        }

        $valiMsg = [
            'category_id.required' => 'Please select store',
            'title.required' => 'Please enter product title',
            'offer_type.required' => 'Please select offer type',
            'price_discount.required' => 'Please enter price discount',
            'banner_type.required' => 'Please select banner type',
            'valid_from_date.required' => 'Please select from date',
            'valid_to_date.required' => 'Please select to date',
            'status.required' => 'Please select product status',
        ];

        if ($data['banner_type'] == "2") {
            $valiMsg['small_banner_img_db.required'] = 'Please select banner image';
        } else {
            $valiMsg['full_banner_img_db.required'] = 'Please select banner image';
        }

        $validator = Validator::make($data, $valiKey, $valiMsg);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);
                if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 3) {
                    $userId = '1';
                } else {
                    $userId = Auth::user()->id;
                }

                if ($data['banner_type'] == "2") {
                    $bannerImg = $data["small_banner_img_db"];
                } else {
                    $bannerImg = $data["full_banner_img_db"];
                }

                $categoryOffer = [
                    "category_id" => $data["category_id"],
                    "title" => $data["title"],
                    "offer_type" => $data["offer_type"],
                    "price_discount" => $data["price_discount"],
                    "banner_type" => $data["banner_type"],
                    "valid_from_date" => $data["valid_from_date"],
                    "valid_to_date" => $data["valid_to_date"],
                    "status" => $data["status"],
                    "banner_img" => $bannerImg,
                ];

                CategoryOffer::create($categoryOffer);

                DB::commit();
                Toastr::success('Offers added successfully.', 'Success');
                return redirect('admin/category-offers')->withSuccess("Offers added successfully.");
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
            $offer = [];
            if ($slug != null) {
                $offer = CategoryOffer::where('id', $slug)->first();
            }
            if (@$offer && !empty($offer)) {
                return view('admin::category-offers.show', compact('offer'));
            } else {
                Toastr::error('Either something went wrong or invalid access.', 'Success');
                return redirect('admin/category-offers')->with("errors_catch", "Either something went wrong or invalid access.");
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
            $categories = Category::whereNotIn('id', function($query) {
                        $query->select("category_id")->from("category_parents");
                    })->orderBy('id', 'DESC')->get();

            $offer = [];
            if ($slug != null) {
                $offer = CategoryOffer::where('id', $slug)->first();
            }
            if (@$offer && !empty($offer)) {
                return view('admin::category-offers.edit', compact('offer', 'categories'));
            } else {
                Toastr::error('Either something went wrong or invalid access.', 'Success');
                return redirect('admin/category-offers')->with("errors_catch", "Either something went wrong or invalid access.");
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

        $valiKey = [
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'offer_type' => 'required|in:1,2',
            'price_discount' => 'required',
            'banner_type' => 'required|in:1,2',
            'valid_from_date' => 'required',
            'valid_to_date' => 'required',
            'status' => 'required',
        ];


        if ($data['banner_type'] == "2") {
            if (empty($data["small_banner_img_db_old"])) {
                $valiKey['small_banner_img_db'] = 'required';
            }
        } else {
            if (empty($data["full_banner_img_db_old"])) {
                $valiKey['full_banner_img_db'] = 'required';
            }
        }


        $valiMsg = [
            'category_id.required' => 'Please select store',
            'title.required' => 'Please enter product title',
            'offer_type.required' => 'Please select offer type',
            'price_discount.required' => 'Please enter price discount',
            'banner_type.required' => 'Please select banner type',
            'valid_from_date.required' => 'Please select from date',
            'valid_to_date.required' => 'Please select to date',
            'status.required' => 'Please select product status',
        ];


        if ($data['banner_type'] == "2") {
            if (empty($data["small_banner_img_db_old"])) {
                $valiMsg['small_banner_img_db.required'] = 'Please select banner image';
            }
        } else {
            if (empty($data["full_banner_img_db_old"])) {
                $valiMsg['full_banner_img_db.required'] = 'Please select banner image';
            }
        }


        $validator = Validator::make($data, $valiKey, $valiMsg);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);
                if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 3) {
                    $userId = '1';
                } else {
                    $userId = Auth::user()->id;
                }

                if ($data['banner_type'] == "2") {
                    if (!empty($data["small_banner_img_db"])) {
                        $bannerImg = $data["small_banner_img_db"];
                    } else {
                        $bannerImg = $data["small_banner_img_db_old"];
                    }
                } else {
                    if (!empty($data["full_banner_img_db"])) {
                        $bannerImg = $data["full_banner_img_db"];
                    } else {
                        $bannerImg = $data["full_banner_img_db_old"];
                    }
                }

                $categoryOffer = [
                    "category_id" => $data["category_id"],
                    "title" => $data["title"],
                    "offer_type" => $data["offer_type"],
                    "price_discount" => $data["price_discount"],
                    "banner_type" => $data["banner_type"],
                    "valid_from_date" => $data["valid_from_date"],
                    "valid_to_date" => $data["valid_to_date"],
                    "status" => $data["status"],
                    "banner_img" => $bannerImg,
                ];
                
                

                CategoryOffer::where('id', $data["slug"])->update($categoryOffer);

                DB::commit();
                Toastr::success('Offers updated successfully.', 'Success');
                return redirect('admin/category-offers')->withSuccess("Offers updated successfully.");
            } catch (Exception $ex) {
                DB::rollback();
                dd($ex);
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
                $this->CategoryOffer->where('id', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    public function offerStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    $this->CategoryOffer->where('id', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    $this->CategoryOffer->where('id', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    public function uploadOfferImg(Request $request) {
        $filename = '';
        $filenameDB = '';
        try {
            if (isset($request->image) && !empty($request->image)) {
                $image_file = $request->image;
                list($type, $image_file) = explode(';', $image_file);
                list(, $image_file) = explode(',', $image_file);
                $image_file = base64_decode($image_file);

                $image_name = time() . '_' . rand(100, 999) . '.png';
                \Storage::disk('public')->put('/uploads/categories-offers/' . $image_name, $image_file);

                $filename = asset('/storage/uploads/categories-offers/' . $image_name);
                $filenameDB = 'uploads/categories-offers/' . $image_name;
                Session::put('offer_banner_img', $filename);
                Session::put('offer_banner_img_db', $filenameDB);
                return response()->json(['status' => true, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            } else {
                return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            }
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
        }
    }

}
