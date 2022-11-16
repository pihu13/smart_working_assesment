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
use App\Models\Page;
use Exception;
use Toastr;

class PageController extends Controller {

    public function __construct(Page $Page) {
        $this->Page = $Page;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        try {
            $pages = Page::orderBy("title", "ASC")->get();
            return view('admin::pages.index')->with('pages', $pages);
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
            return view('admin::pages.create');
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
        $key = [
            'title' => 'required|string|max:255|unique:pages',
            'description' => 'required',
            'status' => 'required|in:0,1',
        ];

        $val = [
            'title.required' => 'Please enter title',
            'description.required' => 'Please enter description',
            'status.required' => 'Please select status',
        ];

        $validator = Validator::make($data, $key, $val);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        }
        try {
            $pageData = [
                "title" => $data["title"],
                "description" => $data["description"],
                "status" => $data["status"],
            ];
            Page::create($pageData);

            Toastr::success('CMS page add successfully.', 'Success');
            return redirect('admin/pages-list')->withSuccess("CMS page add successfully.");
        } catch (Exception $ex) {
            dd($ex);
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
            $page = [];
            if ($slug != null) {
                $page = Page::where('slug', $slug)->first();
            }

            if (@$page && !empty($page)) {
                return view('admin::pages.show', compact('page', 'slug'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
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
            $page = [];
            if ($slug != null) {
                $page = Page::where('slug', $slug)->first();
            }

            if (@$page && !empty($page)) {
                return view('admin::pages.edit', compact('page'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
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

        $page = Page::where('slug', $request->slug)->first();

        $key = [
            'title' => 'required|string|max:255|unique:pages,title,' . @$page->id,
            'description' => 'required',
            'status' => 'required',
        ];

        $val = [
            'title.required' => 'Please enter title',
            'description.required' => 'Please enter description',
            'status.required' => 'Please select status',
        ];

        $validator = Validator::make($data, $key, $val);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        } else {
            try {
                unset($data['_token']);

                $pageData = [
                    "title" => $data["title"],
                    "description" => $data["description"],
                    "status" => $data["status"],
                ];

                Page::where('slug', $request->slug)->update($pageData);

                Toastr::success('CMS page update successfully.', 'Success');
                return redirect('admin/pages-list')->withSuccess("CMS page update successfully.");
            } catch (\Exception $e) {
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
    public function CmsPageStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    $this->Page->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    $this->Page->where('slug', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    public function destroy(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                $this->Page->where('slug', $data['slug'])->delete();

                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
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
     * CMS Page Display
     * @param : $slug
     * @return response
     */

    public function cmspages($slug) {
        try {
            $pages = Page::where("slug", $slug)->first();
            return view('pages.cmspages', compact("pages"));
        } catch (Exception $ex) {
            
        }
    }

}
