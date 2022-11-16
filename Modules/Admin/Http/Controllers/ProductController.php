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
use App\Models\Product;
use App\Helpers\Helper;
use App\Models\Shop;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\ProductOption;
use App\Models\ProductCombinationOption;
use Exception;
use Toastr;
use DB;
use View;

class ProductController extends Controller {

    public function __construct(Product $Product) {
        $this->Product = $Product;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request) {
        $requestData = $request->all();
        try {
            Session::forget('pro_thumbnail_img');
            Session::forget('pro_thumbnail_img_db');

            Session::forget('pro_slider_img_arr');
            Session::forget('pro_slider_img_db_arr');
            Session::forget('pro_slider_small_img_db_arr');

            Helper::deleteTempListProduct();

            Session::forget('session_product_id');
            Session::forget('session_product_title');

            /*
             * Get All Categories
             */
            $categories = Category::whereNotIn('id', function($query) {
                        $query->select("category_id")->from("category_parents");
                    })->orderBy('name', 'ASC')->get();
            /*
             * Get All Stores
             */
            $shops = Shop::orderBy("store_name", "ASC")->get();


            $products = Product::where("is_deleted", "0")->orderBy('id', 'desc');
            //Search By date
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $products = $products->where(function ($query) use ($request) {
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                    }
                });
            }
            //Search By Categories
            if (@$requestData["category"] && !empty($requestData["category"]) && count($requestData["category"]) > 0) {
                $category_id = $requestData["category"];
                $products = $products->whereHas('proCategory', function($q) use ($category_id) {
                    $q->whereIn('category_id', $category_id);
                });
            }
            //Search By Shop
            if (@$requestData["store"] && !empty($requestData["store"]) && count($requestData["store"]) > 0) {
                $store = $requestData["store"];
                $products = $products->whereIn("shop_id", $store);
            }

            $products = $products->get();
            return view('admin::products.index', compact('products', 'categories', 'requestData', 'shops'));
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
            $stores = Shop::where("status", "1")->get();
            //$categories = Category::where("status", "1")->get();

            $categories = Category::whereNotIn('id', function($query) {
                        $query->select("category_id")->from("category_parents");
                    })->where("status", "1")->orderBy('name', 'ASC')->get();

            foreach ($categories as $val) {
                $val->parentCat;
            }
            $catArr = [];

            return view('admin::products.create', compact("stores", "categories", "catArr"));
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
            'shop_id' => 'required',
            //'title' => 'required|string|max:255|unique:products,title',
            'title' => 'required|string|max:255',
            'product_type' => 'required|in:1,2',
            'pro_thumbnail_img_db' => 'required',
            'description' => 'required',
            'status' => 'required',
            'pro_slider_img_db' => 'required',
            'category_id' => 'required',
            'category_id.*' => 'required',
            'sku' => 'required|string|max:255|unique:products,sku',
        ];

        if ($data['product_type'] == "1") {
            $valiKey['price'] = 'required|numeric|min:0|not_in:0';
            $valiKey['stock'] = 'required|numeric';
            $valiKey['product_default_value'] = 'required';
            if (!empty($data['sale_price'])) {
                $valiKey['sale_price'] = 'numeric|min:0|not_in:0|lt:price';
            }
//            if (@$data["product_tax"] && !empty($data["product_tax"])) {
//                $valiKey['product_tax'] = 'required|numeric|min:0';
//            }
        }

        $valiMsg = [
            'shop_id.required' => 'Please select store',
            'title.required' => 'Please enter product title',
            'product_type.required' => 'Please select product type',
            'pro_thumbnail_img_db.required' => 'Please select product thumbnail',
            'description.required' => 'Please enter description',
            'status.required' => 'Please select product status',
            'pro_slider_img_db.required' => 'Please select product slider',
            'category_id.*' => 'Please select product category',
            'sku.required' => 'Please enter sku',
        ];

        if ($data['product_type'] == "1") {
            $valiMsg['price.required'] = 'Please enter product price';
            $valiMsg['stock.required'] = 'Please enter product stock';
            $valiMsg['product_default_value.required'] = 'Please enter product default value';
            if (!empty($data['sale_price'])) {
                $valiMsg['sale_price.required'] = 'Sale price should be not greater than to product price';
            }
//            if (@$data["product_tax"] && !empty($data["product_tax"])) {
//                $valiMsg['product_tax.required'] = 'Please enter numeric value';
//            }
        }


        $validator = Validator::make($data, $valiKey, $valiMsg);


        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        }
        try {
            $data = $request->all();
            DB::beginTransaction();
            unset($data['_token']);
            if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 3) {
                $userId = '1';
            } else {
                $userId = Auth::user()->id;
            }

            if ($data['product_type'] == "2") {
                $proData = [
                    'shop_id' => (@$data["shop_id"]) ? $data["shop_id"] : null,
                    'title' => $data['title'],
                    'product_type' => $data['product_type'],
                    'product_image' => $data['pro_thumbnail_img_db'],
                    'price' => "0",
                    'sale_price' => "0",
                    'price_sale_price' => "0",
                    'product_tax' => "0",
                    'product_default_value' => "0",
                    'stock' => "0",
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'product_partially' => "0",
                    'sku' => $data['sku'],
                ];
            } else {
                if (!empty($data['sale_price']) && $data['sale_price'] > 0) {
                    $price_sale_price = $data['sale_price'];
                } else {
                    $price_sale_price = $data['price'];
                }

                $proData = [
                    'shop_id' => (@$data["shop_id"]) ? $data["shop_id"] : null,
                    'title' => $data['title'],
                    'product_type' => $data['product_type'],
                    'product_image' => $data['pro_thumbnail_img_db'],
                    'price' => (@$data["price"]) ? $data["price"] : "0",
                    'sale_price' => (@$data["sale_price"]) ? $data["sale_price"] : "0",
                    'price_sale_price' => (@$price_sale_price) ? $price_sale_price : "0",
                    'product_tax' => "0",
                    'product_default_value' => (@$data["product_default_value"]) ? $data["product_default_value"] : "0",
                    'stock' => @$data['stock'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'product_partially' => "0",
                    'sku' => $data['sku'],
                ];
            }

            if (@$data["product_id"] && !empty($data["product_id"])) {
                $proData["slug"] = $this->createCustomSlug($data["title"]);
                Product::where("id", $data["product_id"])->update($proData);
                $product_id = $data["product_id"];
                $prodata = Product::where("id", $data["product_id"])->first();
                $product_slug = $prodata->slug;
            } else {
                $prodata = Product::create($proData);
                $product_id = $prodata->id;
                $product_slug = $prodata->slug;
            }

            if (@$product_id && !empty(@$product_id)) {
                $imgArr = [];
                if (!empty($data['pro_slider_img_db']) && $data['pro_slider_small_img_db']) {
                    $sliderArrFull = json_decode($data['pro_slider_img_db']);
                    $sliderArrFullSmall = json_decode($data['pro_slider_small_img_db']);
                    $count = count($sliderArrFull);
                    for ($j = 0; $j < $count; $j++) {
                        $imgArr[$j]['full_image'] = $sliderArrFull[$j];
                        $imgArr[$j]['small_img'] = $sliderArrFullSmall[$j];
                    }
                }

                if (@($imgArr) && count($imgArr) > 0) {
                    foreach ($imgArr as $key => $image) {
                        $pro_slider_img_db = [
                            "product_id" => @$product_id,
                            "full_image" => $image['full_image'],
                            "small_image" => $image['small_img'],
                            "status" => "1",
                        ];
                        ProductImage::create($pro_slider_img_db);
                    }
                }

                if (isset($data['category_id']) && !empty($data['category_id'])) {
                    foreach ($data['category_id'] as $id) {
                        if (@$id && !empty($id)) {
                            $productCategory = [
                                "product_id" => @$product_id,
                                "category_id" => $id,
                                "status" => "1",
                            ];
                            ProductCategory::create($productCategory);
                        }
                    }
                }
            }

            Session::forget('pro_thumbnail_img');
            Session::forget('pro_thumbnail_img_db');

            Session::forget('pro_slider_img_arr');
            Session::forget('pro_slider_img_db_arr');
            Session::forget('pro_slider_small_img_db_arr');

            DB::commit();

            Toastr::success('Product added successfully.', 'Success');
            return redirect('admin/products-list')->withSuccess("Product added successfully.");
        } catch (Exception $ex) {
            DB::rollback();
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
        $stores = Shop::where("status", "1")->get();
        $product = [];
        if ($slug != null) {
            $product = Product::where('slug', $slug)->first();
        }
        if ($product && !empty($product)) {
            $catArr = [];
            foreach ($product->proCategory as $val) {
                $catArr[] = "<b><a href='" . route("admin.view.category", [@$val->categoryDetails->slug]) . "' target='_blank'>" . @$val->categoryDetails->name . "</a></b>";
            }
            return view('admin::products.show', compact("product", "stores", "catArr"));
        } else {
            Toastr::error('Either something went wrong or invalid access.', 'Success');
            return redirect('admin/products-list')->with("errors_catch", "Either something went wrong or invalid access.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($slug) {
        try {
            $stores = Shop::where("status", "1")->get();

            $categories = Category::whereNotIn('id', function($query) {
                        $query->select("category_id")->from("category_parents");
                    })->where("status", "1")->orderBy('name', 'ASC')->get();

            foreach ($categories as $val) {
                $val->parentCat;
            }

            $product = [];
            if ($slug != null) {
                $product = Product::where('slug', $slug)->first();
            }
            if ($product && !empty($product)) {
                $catArr = [];
                foreach ($product->proCategory as $val) {
                    $catArr[] = $val->category_id;
                }

                if (@$product->product_type == "2") {
                    $id = @$product->id;
                    $variables = [];
                    if ($id != null) {
                        $variables = ProductVariant::where('product_id', $id)->get();
                    }
                } else {
                    $id = "";
                    $variables = [];
                    $combinations = [];
                }

                return view('admin::products.edit', compact("product", "stores", "categories", "catArr", "id", "variables"));
            } else {
                Toastr::error('Product added successfully.', 'Success');
                return redirect('admin/products-list')->with("errors_catch", "Either something went wrong or invalid access.");
            }
        } catch (\Exception $e) {
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
        $product = Product::where('slug', $request->slug)->first();

        $valiKey = [
            'shop_id' => 'required',
            //'title' => 'required|string|max:255|unique:products,title,' . $product->id,
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required',
            'category_id' => 'required',
            'category_id.*' => 'required',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
        ];

        if ($data['product_type'] == "1") {
            $valiKey['price'] = 'required|numeric|min:0|not_in:0';
            $valiKey['stock'] = 'required|numeric';
            $valiKey['product_default_value'] = 'required';
            if (!empty($data['sale_price'])) {
                $valiKey['sale_price'] = 'numeric|min:0|not_in:0|lt:price';
            }
//            if (@$data["product_tax"] && !empty($data["product_tax"])) {
//                $valiKey['product_tax'] = 'required|numeric|min:0';
//            }
        }

        $valiMsg = [
            'shop_id.required' => 'Please select store',
            'title.required' => 'Please enter product title',
            'description.required' => 'Please enter description',
            'status.required' => 'Please select product status',
            'category_id.*' => 'Please select product category',
            'sku.required' => 'Please enter SKU',
        ];

        if ($data['product_type'] == "1") {
            $valiMsg['price.required'] = 'Please enter product price';
            $valiMsg['stock.required'] = 'Please enter product stock';
            $valiMsg['product_default_value.required'] = 'Please enter product default value';
            if (!empty($data['sale_price'])) {
                $valiMsg['sale_price.required'] = 'Sale price should be not greater than to product price';
            }
//            if (@$data["product_tax"] && !empty($data["product_tax"])) {
//                $valiMsg['product_tax.required'] = 'Please enter numeric value';
//            }
        }


        $validator = Validator::make($data, $valiKey, $valiMsg);


        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        }
        try {
            $data = $request->all();
            DB::beginTransaction();
            unset($data['_token']);
            //Delete Product Category
            ProductCategory::where('product_id', $request->product_id)->delete();

            if (!empty($data['sale_price']) && $data['sale_price'] > 0) {
                $price_sale_price = $data['sale_price'];
            } else {
                $price_sale_price = (@$data['price']) ? $data['price'] : "0";
            }

            //dd($price_sale_price);

            $proData = [
                'shop_id' => (@$data["shop_id"]) ? $data["shop_id"] : null,
                'title' => $data['title'],
                'product_image' => (@$data['pro_thumbnail_img_db']) ? $data['pro_thumbnail_img_db'] : $data['old_product_image'],
                'price' => (@$data["price"]) ? $data["price"] : "0",
                'sale_price' => (@$data["sale_price"]) ? $data["sale_price"] : "0",
                'price_sale_price' => (@$price_sale_price) ? $price_sale_price : "0",
                'product_tax' => "0",
                'product_default_value' => (@$data["product_default_value"]) ? $data["product_default_value"] : "0",
                'stock' => @$data['stock'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'status' => $data['status'],
                'sku' => $data['sku'],
            ];

            // dd($proData);

            Product::where("slug", $request->slug)->update($proData);


            if (@$data["product_id"] && !empty(@$data["product_id"])) {
                $imgArr = [];
                if (!empty($data['pro_slider_img_db']) && $data['pro_slider_small_img_db']) {
                    $sliderArrFull = json_decode($data['pro_slider_img_db']);
                    $sliderArrFullSmall = json_decode($data['pro_slider_small_img_db']);
                    $count = count($sliderArrFull);
                    for ($j = 0; $j < $count; $j++) {
                        $imgArr[$j]['full_image'] = $sliderArrFull[$j];
                        $imgArr[$j]['small_img'] = $sliderArrFullSmall[$j];
                    }
                }

                if (@($imgArr) && count($imgArr) > 0) {
                    foreach ($imgArr as $key => $image) {
                        $pro_slider_img_db = [
                            "product_id" => @$data["product_id"],
                            "full_image" => $image['full_image'],
                            "small_image" => $image['small_img'],
                            "status" => "1",
                        ];
                        ProductImage::create($pro_slider_img_db);
                    }
                }

                if (isset($data['category_id']) && !empty($data['category_id'])) {
                    foreach ($data['category_id'] as $id) {
                        if (@$id && !empty($id)) {
                            $productCategory = [
                                "product_id" => @$data["product_id"],
                                "category_id" => $id,
                                "status" => "1",
                            ];
                            ProductCategory::create($productCategory);
                        }
                    }
                }
            }

            Session::forget('pro_thumbnail_img');
            Session::forget('pro_thumbnail_img_db');

            Session::forget('pro_slider_img_arr');
            Session::forget('pro_slider_img_db_arr');
            Session::forget('pro_slider_small_img_db_arr');

            DB::commit();

            Toastr::success('Product added successfully.', 'Success');
            return redirect('admin/products-list')->withSuccess("Product added successfully.");
        } catch (Exception $ex) {
            DB::rollback();
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Crop Product Image Using Image
     * @param : $image
     * @return Response With Store Session Value
     */

    public function productImageCrop(Request $request) {
        $filename = '';
        $filenameDB = '';
        try {
            if (isset($request->image) && !empty($request->image)) {
                $image_file = $request->image;
                list($type, $image_file) = explode(';', $image_file);
                list(, $image_file) = explode(',', $image_file);
                $image_file = base64_decode($image_file);

                $image_name = time() . '_' . rand(100, 999) . '.png';
                \Storage::disk('public')->put('/uploads/products/' . $image_name, $image_file);

                $filename = asset('/storage/uploads/products/' . $image_name);
                $filenameDB = 'uploads/products/' . $image_name;
                Session::put('pro_thumbnail_img', $filename);
                Session::put('pro_thumbnail_img_db', $filenameDB);
                return response()->json(['status' => true, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            } else {
                return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            }
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
        }
    }

    /*
     * Create Custom Slug Using Title
     * @param : $title
     * @return response
     */

    public function createCustomSlug($title) {
        try {
            $slug = preg_replace('/[^a-z0-9]+/i', '-', trim(strtolower($title)));
            $query = Product::where('slug', 'like', '%' . $slug . '%')->get();
            if (@$query && count($query) > 0) {
                $total_row = count($query);
                if ($total_row > 0) {
                    foreach ($query as $row) {
                        $data[] = $row->slug;
                    }
                    if (in_array($slug, $data)) {
                        $count = 0;
                        while (in_array(($slug . '-' . ++$count), $data));
                        $slug = $slug . '-' . $count;
                    }
                }
            }
            return $slug;
        } catch (Exception $ex) {
            $slug = "";
            return $slug;
        }
    }

    /*
     * Changed Product Status
     * @param : $product_slug
     * @return response
     */

    public function productStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    $this->Product->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    $product = Product::where('slug', $data['slug'])->first();
                    $this->Product->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    /*
     * Delete Product Using Slug
     * @param : $slug
     * @return response
     */

    public function destroy(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                //$this->Product->where('slug', $data['slug'])->delete();
                $this->Product->where('slug', $data['slug'])->update(["is_deleted" => "1"]);
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Add Product Slider Image
     * @param : $image
     * @return response
     */

    public function cropSliderImage(Request $request) {
        $filename = '';
        $filenameDB = '';
        $image_name_s_db = '';
        try {
            if (isset($request->image) && !empty($request->image)) {
                $image_file = $request->image;
                list($type, $image_file) = explode(';', $image_file);
                list(, $image_file) = explode(',', $image_file);
                $image_file = base64_decode($image_file);

                $image_name = time() . '_' . rand(100, 999) . '.png';
                \Storage::disk('public')->put('/uploads/products/' . $image_name, $image_file);
                $filename = asset('/storage/uploads/products/' . $image_name);
                $filenameDB = 'uploads/products/' . $image_name;


                //Small Image
                $img = \Image::make($image_file);
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream();
                $image_name_s = time() . '_small_' . rand(100, 999) . '.png';
                \Storage::disk('public')->put('/uploads/products/' . $image_name_s, $img);
                $image_name_s_db = 'uploads/products/' . $image_name_s;

                $proSliderImgOld = Session::get('pro_slider_img_arr');
                $mergedSliderImg = array_merge([$filename], (@$proSliderImgOld) ? $proSliderImgOld : array());
                Session::put('pro_slider_img_arr', $mergedSliderImg);

                //With DB Mail Image
                $proSliderImgDBOld = Session::get('pro_slider_img_db_arr');
                $mergedSliderImgDB = array_merge([$filenameDB], (@$proSliderImgDBOld) ? $proSliderImgDBOld : array());
                Session::put('pro_slider_img_db_arr', $mergedSliderImgDB);

                //With DB Mail Image
                $proSliderImgDBSmallOld = Session::get('pro_slider_small_img_db_arr');
                $mergedSliderImgSmallDB = array_merge([$image_name_s_db], (@$proSliderImgDBSmallOld) ? $proSliderImgDBSmallOld : array());
                Session::put('pro_slider_small_img_db_arr', $mergedSliderImgSmallDB);

                if (@$request->product_id && !empty($request->product_id)) {
                    $product = Product::where("id", @$request->product_id)->first();
                } else {
                    $product = [];
                }

                $accounts = (string) View::make('admin::products.product-slider-image-ajax')->with("proSliderImgOld", $proSliderImgOld)->with("product", $product);
                return json_encode(['status' => 200, 'msg' => '', 'data' => $accounts]);
            } else {
                return json_encode(['status' => 501, 'msg' => 'Please select slider image', 'data' => '']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!', 'data' => '']);
        }
    }

    /*
     * Remove Slider Image
     * @param : $image
     * @return : response
     */

    public function removeSliderImage(Request $request) {
        $data = $request->all();
        try {
            unset($data["_token"]);
            if (@$data["imgId"] && !empty($data["imgId"]) || $data["imgId"] == 0) {
                $pro_slider_img_db = Session::get('pro_slider_img_db_arr');
                $pro_slider_small_img_db = Session::get('pro_slider_small_img_db_arr');
                $sessionProSliderImg = Session::get('pro_slider_img_arr');


                if (@$pro_slider_img_db && !empty($pro_slider_img_db)) {
                    unset($pro_slider_img_db[$data["imgId"]]);
                }

                if (@$pro_slider_small_img_db && !empty($pro_slider_small_img_db)) {
                    unset($pro_slider_small_img_db[$data["imgId"]]);
                }

                if (@$sessionProSliderImg && !empty($sessionProSliderImg)) {
                    unset($sessionProSliderImg[$data["imgId"]]);
                }

                Session::put('pro_slider_img_db_arr', $pro_slider_img_db);
                Session::put('pro_slider_small_img_db_arr', $pro_slider_small_img_db);
                Session::put('pro_slider_img_arr', $sessionProSliderImg);

                if (@$data["product_id"] && !empty($data["product_id"])) {
                    $product = Product::where("id", @$data["product_id"])->first();
                } else {
                    $product = [];
                }

                $accounts = (string) View::make('admin::products.product-slider-image-ajax')->with("product", $product);
                return json_encode(['status' => 200, 'msg' => 'Image removed successfully.', 'data' => $accounts]);
            } else {
                return json_encode(['status' => 501, 'msg' => 'Invalid image id', 'data' => '']);
            }
        } catch (\Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!', 'data' => '']);
        }
    }

    /*
     * Remove Slider Image In DB
     * @param : $image
     * @return : response
     */

    public function removeSliderImageDB(Request $request) {
        $data = $request->all();
        try {
            unset($data["_token"]);
            if (@$data["imgId"] && !empty($data["imgId"]) || $data["imgId"] == 0) {

                ProductImage::where('id', @$data["imgId"])->delete();

                $product = Product::where("id", @$data["product_id"])->first();

                $accounts = (string) View::make('admin::products.product-slider-image-ajax')->with("product", $product);
                return json_encode(['status' => 200, 'msg' => 'Image removed successfully.', 'data' => $accounts]);
            } else {
                return json_encode(['status' => 501, 'msg' => 'Invalid image id', 'data' => '']);
            }
        } catch (\Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!', 'data' => '']);
        }
    }

    /*
     * Store Product In Admin Panel 
     * @param : $product_data
     * @return response
     */

    public function storeProductPartially(Request $request) {
        $data = $request->all();
        $data["status"] = (@$request->status) ? $request->status : "0";

        $valiKey = [
            'title' => 'required|string|max:255',
        ];

        $valiMsg = [
            'title.required' => 'Please enter product title',
        ];

        $validator = Validator::make($data, $valiKey, $valiMsg);

        $data["title"] = (@$data["title"] && !empty($data["title"])) ? $data["title"] : "Variation Product";

        try {
            unset($data['_token']);
            DB::beginTransaction();

            if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 3) {
                $userId = '1';
            } else {
                $userId = Auth::user()->id;
            }

            $productData = Product::where("id", $data["product_id"])->first();

            if (@$data["product_id"] && !empty($data["product_id"]) && @$productData && !empty($productData)) {
                $slug = $this->createCustomSlug($data["title"]);
                Product::where("id", $data["product_id"])->update(["slug" => $slug, "title" => $data["title"]]);

                if (@$request->product_id && !empty($request->product_id)) {
                    $id = $request->product_id;
                    $variables = [];
                    if ($id != null) {
                        $variables = ProductVariant::where('product_id', $id)->get();
                    }
                    $product = Product::where('id', $id)->first();
                    $combinations = ProductCombinationOption::where('product_id', $id)->orderBy('id', 'desc')->get();
                    $accounts = (string) View::make('admin::products.product-variation-option-combination')->with(["variables" => $variables, "id" => $id, "product" => $product, "combinations" => $combinations]);
                    return json_encode(['status' => 201, 'msg' => '', 'data' => $accounts]);
                } else {
                    return json_encode(['status' => 500, 'msg' => 'Invalid product ID.']);
                }
            } else {
                if ($data['product_type'] == "2") {
                    $proData = [
                        'shop_id' => (@$data["shop_id"]) ? $data["shop_id"] : null,
                        'title' => $data['title'],
                        'product_type' => $data['product_type'],
                        'product_image' => $data['pro_thumbnail_img_db'],
                        'price' => "0",
                        'sale_price' => "0",
                        'product_tax' => "0",
                        'stock' => "0",
                        'short_description' => $data['short_description'],
                        'description' => $data['description'],
                        'status' => $data['status'],
                        'product_partially' => "1",
                        'sku' => (@$data["sku"]) ? $data["sku"] : null,
                    ];
                } else {
                    $proData = [
                        'shop_id' => (@$data["shop_id"]) ? $data["shop_id"] : null,
                        'title' => $data['title'],
                        'product_type' => $data['product_type'],
                        'product_image' => $data['pro_thumbnail_img_db'],
                        'price' => (@$data["price"]) ? $data["price"] : "0",
                        'sale_price' => (@$data["sale_price"]) ? $data["sale_price"] : "0",
                        'product_tax' => "0",
                        'stock' => @$data['stock'],
                        'short_description' => $data['short_description'],
                        'description' => $data['description'],
                        'status' => $data['status'],
                        'product_partially' => "1",
                        'sku' => (@$data["sku"]) ? $data["sku"] : null,
                    ];
                }

                $proData = Product::create($proData);

                if (@$proData->id && !empty(@$proData->id)) {
                    $imgArr = [];
                    if (!empty($data['pro_slider_img_db']) && $data['pro_slider_small_img_db']) {
                        $sliderArrFull = json_decode($data['pro_slider_img_db']);
                        $sliderArrFullSmall = json_decode($data['pro_slider_small_img_db']);
                        $count = count($sliderArrFull);
                        for ($j = 0; $j < $count; $j++) {
                            $imgArr[$j]['full_image'] = $sliderArrFull[$j];
                            $imgArr[$j]['small_img'] = $sliderArrFullSmall[$j];
                        }
                    }

                    if (@($imgArr) && count($imgArr) > 0) {
                        foreach ($imgArr as $key => $image) {
                            $pro_slider_img_db = [
                                "product_id" => @$proData->id,
                                "full_image" => $image['full_image'],
                                "small_image" => $image['small_img'],
                                "status" => "1",
                            ];
                            ProductImage::create($pro_slider_img_db);
                        }
                    }

                    if (isset($data['category_id']) && !empty($data['category_id'])) {
                        foreach ($data['category_id'] as $id) {
                            if (@$id && !empty($id)) {
                                $productCategory = [
                                    "product_id" => @$proData->id,
                                    "category_id" => $id,
                                    "status" => "1",
                                ];
                                ProductCategory::create($productCategory);
                            }
                        }
                    }
                }

                Session::forget('pro_thumbnail_img');
                Session::forget('pro_thumbnail_img_db');

                Session::forget('pro_slider_img');
                Session::forget('pro_slider_img_db');
                Session::forget('pro_slider_small_img_db');

                Session::forget('pro_slider_img_arr');
                Session::forget('pro_slider_img_db_arr');
                Session::forget('pro_slider_small_img_db_arr');

                Session::put('session_product_id', @$proData->id);
                Session::put('session_product_title', @$proData->title);


                $id = @$proData->id;
                $variables = [];
                if ($id != null) {
                    $variables = ProductVariant::where('product_id', $id)->get();
                }
                $product = Product::where('id', $id)->first();
                $combinations = ProductCombinationOption::where('product_id', $id)->orderBy('id', 'desc')->get();
                $accounts = (string) View::make('admin::products.product-variation-option-combination')->with(["variables" => $variables, "id" => $id, "product" => $product, "combinations" => $combinations]);

                DB::commit();

                return json_encode(['status' => 200, 'msg' => '', 'data' => $accounts, "session_product_id" => @$proData->id, "session_product_title" => @$proData->title]);
            }
        } catch (Exception $ex) {
            DB::rollback();
            //dd($ex->getMessage());
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!', 'data' => '']);
        }
    }

    /*
     * Soft Delete Product List
     * @param : $is_deleted
     * @return response
     */

    public function indexSoftDeleteProduct() {
        try {
            Session::forget('pro_thumbnail_img');
            Session::forget('pro_thumbnail_img_db');

            Session::forget('pro_slider_img_arr');
            Session::forget('pro_slider_img_db_arr');
            Session::forget('pro_slider_small_img_db_arr');

            Helper::deleteTempListProduct();

            Session::forget('session_product_id');
            Session::forget('session_product_title');

            $products = Product::where("is_deleted", "1")->orderBy('id', 'desc')->get();
            return view('admin::products.softdelete-products.index', compact('products'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    /*
     * View Soft Delete Product
     * @param : $slug
     * @return responce
     */

    public function showSoftDeleteProduct($slug) {
        $stores = Shop::where("status", "1")->get();
        $product = [];
        if ($slug != null) {
            $product = Product::where('slug', $slug)->where("is_deleted", "1")->first();
        }
        if ($product && !empty($product)) {
            $catArr = [];
            foreach ($product->proCategory as $val) {
                $catArr[] = "<b><a href='" . route("admin.view.category", [@$val->categoryDetails->slug]) . "' target='_blank'>" . @$val->categoryDetails->name . "</a></b>";
            }
            return view('admin::products.softdelete-products.show', compact("product", "stores", "catArr"));
        } else {
            Toastr::error('Product added successfully.', 'Success');
            return redirect('admin/products-list')->with("errors_catch", "Either something went wrong or invalid access.");
        }
    }

    /*
     * Restore Soft Delete Product
     * @param : $slug, $is_deleted
     * @return response
     */

    public function restoreProduct(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                $this->Product->where('slug', $data['slug'])->update(["is_deleted" => "0"]);
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
                $this->Product->where('slug', $data['slug'])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

}
