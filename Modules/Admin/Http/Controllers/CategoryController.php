<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Route;
use Auth;
use Hash;
use Validator;
use Mail;
use App\Models\Category;
use App\Models\CategoryParent;
use App\HeaderMenu;
use App\Models\User;
use DB;
use Toastr;
use Exception;

class CategoryController extends Controller {

    public function __construct(Category $Category) {
        $this->Category = $Category;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        try {
            Session::forget('cat_full_img');
            Session::forget('cat_full_img_db');
            //$categories = Category::orderBy('id', 'desc')->get();
            $categories = Category::whereNotIn('id', function($query) {
                        $query->select("category_id")->from("category_parents");
                    })->orderBy('name', 'ASC')->get();

            foreach ($categories as $val) {
                $val->parentCat;
            }
            return view('admin::categories.index', compact('categories'));
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
            return view('admin::categories.create');
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
            'name' => 'required|string|max:255',
            'cat_image_full_db' => 'required',
            'status' => 'required',
            'color_code' => 'required',
        ];
        $valiMsg = [
            'name.required' => 'Please enter category name',
            'cat_image_full_db.required' => 'Please select category image',
            'status.required' => 'Please select status',
            'color_code.required' => 'Please select color code',
        ];
        $validator = Validator::make($data, $valiKey, $valiMsg);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                $data = $request->all();
                unset($data['_token']);

                $catVal = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'cat_image' => (isset($data['cat_image_full_db'])) ? $data['cat_image_full_db'] : "",
                    'color_code' => (@$data['color_code']) ? $data['color_code'] : null,
                ];

                Category::create($catVal);

                Toastr::success('Category added successfully.', 'Success');
                return redirect('admin/category-list')->withSuccess("category added successfully!");
            } catch (Exception $ex) {
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
            Session::forget('cat_full_img');
            Session::forget('cat_full_img_db');
            $cat = [];
            if ($slug != null) {
                $cat = Category::where('slug', $slug)->first();
            }
            if (@$cat && !empty($cat)) {
                return view('admin::categories.show', compact('cat'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
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
            $category = [];
            if ($category != '') {
                $category = Category::where('slug', $slug)->first();
            }

            if (@$category && !empty($category)) {
                return view('admin::categories.edit', compact('category'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
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
        $cat = Category::where('slug', $request->slug)->first();
        $data = $request->all();

        $valiKey = [
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ];
        if (@$cat->parentCatData && count($cat->parentCatData) > 0) {
            
        } else {
            $valiKey["color_code"] = "required";
        }
        $valiVal = [
            'name.required' => 'Please enter category name',
            'status.required' => 'Please select category status',
        ];
        if (@$cat->parentCatData && count($cat->parentCatData) > 0) {
            
        } else {
            $valiVal["color_code.required"] = "Please select color code";
        }
        $validator = Validator::make($data, $valiKey, $valiVal);


        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                unset($data['_token']);

                $catData = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'cat_image' => (isset($data['cat_image_full_db']) && !empty($data['cat_image_full_db'])) ? $data['cat_image_full_db'] : $data['old_cat_image_full'],
                    "color_code" => (@$data["color_code"]) ? $data["color_code"] : null
                ];

                Category::where('slug', $request->slug)->update($catData);

                Toastr::success('Category has been updated successfully.', 'Success');
                return redirect('admin/category-list')->withSuccess("Category has been updated successfully.");
            } catch (Exception $ex) {
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
    public function destroy($slug) {
        try {
            $catData = Category::where('slug', $slug)->first();

            $array_of_ids = $this->getChildren($catData);
            array_push($array_of_ids, $catData->id);
            foreach ($array_of_ids as $val) {
                $this->Category->where('id', $val)->delete();
            }

            Toastr::success('Category remove successfully.', 'Success');
            return \Redirect::route('admin.category.list')->withSuccess('Category remove successfully.');
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    public function catStatus($slug) {
        $explode = explode('_', $slug);
        if (trim($explode[1]) == 0 || trim($explode[1]) == 1) {
            try {
                $catData = Category::where('id', $explode[2])->first();
                //$this->Category->where('slug', $explode[0])->update(['status' => $explode[1]]);
                $array_of_ids = $this->getChildren($catData);
                array_push($array_of_ids, $explode[2]);

                foreach ($array_of_ids as $val) {
                    $this->Category->where('id', $val)->update(['status' => $explode[1]]);
                }
                Toastr::success('Category status has been updated successfully.', 'Success');
                return \Redirect::route('admin.category.list')->withSuccess('Category status has been updated successfully.');
            } catch (\Exception $e) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors_catch', "Either something went wrong or invalid acces!");
            }
        }
    }

    private function getChildren($category) {
        $ids = [];
        foreach ($category->subcategoryParent as $cat) {
            $ids[] = $cat->category_id;
            //$ids = array_merge($ids, $this->getChildren($cat));
        }
        return $ids;
    }

    public function catImageCrop(Request $request) {
        $filename = '';
        $filenameDB = '';
        try {
            if (isset($request->image) && !empty($request->image)) {
                $image_file = $request->image;
                list($type, $image_file) = explode(';', $image_file);
                list(, $image_file) = explode(',', $image_file);
                $image_file = base64_decode($image_file);

                $image_name = time() . '_' . rand(100, 999) . '.png';
                \Storage::disk('public')->put('/uploads/categories/' . $image_name, $image_file);

                $filename = asset('/storage/uploads/categories/' . $image_name);
                $filenameDB = 'uploads/categories/' . $image_name;
                Session::put('cat_full_img', $filename);
                Session::put('cat_full_img_db', $filenameDB);
                return response()->json(['status' => true, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            } else {
                return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
            }
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'file_name' => $filename, 'file_name_db' => $filenameDB]);
        }
    }

    public function createSubCat($id) {
        try {
            $pCat = Category::where('id', $id)->first();
            return view('admin::categories.create-sub-cat', compact('id', 'pCat'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
        }
    }

    public function storeSubCat(Request $request) {
        $data = $request->all();

        $valiKey = [
            'name' => 'required|string|max:255',
            'cat_image_full_db' => 'required',
            'status' => 'required',
        ];
        $valiMsg = [
            'name.required' => 'Please enter category name',
            'cat_image_full_db.required' => 'Please select category image',
            'status.required' => 'Please select status',
        ];
        $validator = Validator::make($data, $valiKey, $valiMsg);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                $data = $request->all();
                unset($data['_token']);

                $catVal = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'cat_image' => (isset($data['cat_image_full_db'])) ? $data['cat_image_full_db'] : "",
                ];
                $catData = Category::create($catVal);

                if (isset($catData->id) && !empty($catData->id)) {
                    $proCat = [
                        "parent_id" => $data['parent_id'],
                        "category_id" => $catData->id,
                    ];
                    CategoryParent::create($proCat);
                }

                Toastr::success('Category added successfully.', 'Success');
                return redirect('admin/category-list')->withSuccess("category added successfully!");
            } catch (Exception $ex) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors_catch', "Either something went wrong or invalid access!");
            }
        }
    }

}
