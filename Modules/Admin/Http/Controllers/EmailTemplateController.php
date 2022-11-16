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
use App\Models\EmailTemplate;
use DB;
use Toastr;

class EmailTemplateController extends Controller {

    public function __construct(EmailTemplate $EmailTemplate) {
        $this->EmailTemplate = $EmailTemplate;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        try {
            $emails = EmailTemplate::orderBy('id', 'DESC')->get();
            return view('admin::emailtemplates.index', compact('emails'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        try {
            return view('admin::emailtemplates.create');
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
                    'title' => 'required|string|max:255|unique:email_templates',
                    'subject' => 'required',
                    'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                $data = $request->all();
                unset($data['_token']);


                EmailTemplate::create($data);

                Toastr::success('Email template add successfully.', 'Success');
                return redirect('admin/emails')->withSuccess("Email template add successfully!");
            } catch (Exception $ex) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($slug) {
        try {
            $email = [];
            if ($slug != null) {
                $email = EmailTemplate::where('slug', $slug)->first();
            }
            return view('admin::emailtemplates.show', compact('email'));
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug) {
        try {
            $email = [];
            if ($email != '') {
                $email = EmailTemplate::where('slug', $slug)->first();
            }
            return view('admin::emailtemplates.edit', compact('email'));
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request) {
        $email = EmailTemplate::where('slug', $request->slug)->first();
        $validator = Validator::make($request->all(), [
                    'title' => 'required|string|max:100|unique:email_templates,title,' . $email->id,
                    'subject' => 'required',
                    'content' => 'required',
                        ], [
                    'title.required' => 'Please enter email template title',
                    'subject.required' => 'Please enter email template subject',
                    'content.required' => 'Please enter content',
        ]);

        $data = $request->all();
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            try {
                unset($data['_token']);

                EmailTemplate::where('slug', $request->slug)->update($data);

                Toastr::success('Email template has been updated successfully.', 'Success');
                return redirect('admin/emails')->withSuccess("Email template has been updated successfully.");
            } catch (Exception $ex) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($slug) {
        try {
            $this->EmailTemplate->where('slug', $slug)->delete();
            Toastr::success('Email template remove successfully.', 'Success');
            return redirect('admin/emailtemplates')->withSuccess("Email template remove successfully.");
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    public function emailStatus($slug) {
        $explode = explode('_', $slug);
        if (trim($explode[1]) == 0 || trim($explode[1]) == 1) {
            try {
                $this->EmailTemplate->where('slug', $explode[0])->update(['status' => $explode[1]]);
                Toastr::success('Email template status has been updated successfully.', 'Success');
                return redirect('admin/emails')->withSuccess("Email template status has been updated successfully.");
            } catch (\Exception $e) {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
            }
        }
    }

}
