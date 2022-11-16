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
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\ProductOrderAddress;
use App\Models\OrderShippingProduct;
use App\Models\TransectionHistory;
use App\Models\TransectionRefundHistory;
use Exception;
use Toastr;
use DB;
use View;

class OrderTransactionController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function transactionIndex(Request $request) {
        $requestData = $request->all();
        try {

            $transections = TransectionHistory::orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $transections = $transections->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $transections = $transections->get();
            }
            return view('admin::orders.transactions.index', compact('transections', 'requestData'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Return and Cancel transection_refund_histories list
     */

    public function cancelReturnTransactionIndex(Request $request) {
        $requestData = $request->all();
        try {
            $transections = TransectionRefundHistory::orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $transections = $transections->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $transections = $transections->get();
            }
            return view('admin::orders.transactions.cancel-return-transactions.index', compact('transections', 'requestData'));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

}
