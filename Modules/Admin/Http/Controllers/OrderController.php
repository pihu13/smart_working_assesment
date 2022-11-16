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
use App\Models\TransectionRefundHistory;
use Exception;
use Toastr;
use DB;
use View;

class OrderController extends Controller {

    public function __construct(ProductOrder $ProductOrder) {
        $this->ProductOrder = $ProductOrder;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request) {

        $requestData = $request->all();
        try {

            $orders = ProductOrder::where("is_deleted", "0")->orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $orders = $orders->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $orders = $orders->get();
            }
            return view('admin::orders.index', compact("orders", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Get resource data using orderid
     * @param : $orderId
     * @return Renderable
     */

    public function show($orderid) {
        try {
            $order = [];
            if ($orderid != null) {
                $order = ProductOrder::where('order_number', $orderid)->first();
                return view('admin::orders.show', compact('order', 'orderid'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect("/admin/product-orders/")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Change Shipping Product Status
     * @param : $orderId,$rowid, $orderrowId
     * @return response
     */

    public function shippingStatus($orderid, $rowid, $orderrowid) {
        try {
            if (@$orderid && @$rowid && @$orderrowid) {
                $ship = [
                    "shipping_status" => "1",
                ];
                OrderShippingProduct::where(["id" => @$rowid, "product_order_id" => @$orderrowid])->update($ship);

                $orderData = OrderShippingProduct::where(["id" => @$rowid, "product_order_id" => @$orderrowid])->first();

                if (@$orderData && @$orderData->title == "Delivered") {
                    ProductOrder::where("id", @$orderrowid)->update(["order_status" => "4"]);
                }

                Toastr::success('Shipping status changed successfully!', 'Error');
                return redirect()->route("admin.view.product.order", [$orderid])->with("success", "Shipping status changed successfully!");
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect()->route("product-orders")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Delivery Order List
     * @param : delivery_type
     * @return response
     */

    public function deliveryOrders(Request $request) {
        $requestData = $request->all();
        try {
            $orders = ProductOrder::where("delivery_type", "delivery")->where("is_deleted", "0")->orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $orders = $orders->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $orders = $orders->get();
            }
            return view('admin::orders.delivery-orders.index', compact("orders", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Get resource data using orderid
     * @param : $orderId
     * @return Renderable
     */

    public function deliveryOrderShow($orderid) {
        try {
            $order = [];
            if ($orderid != null) {
                $order = ProductOrder::where('order_number', $orderid)->where("delivery_type", "delivery")->first();
                return view('admin::orders.delivery-orders.show', compact('order', 'orderid'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect("/admin/product-delivery-orders/")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Pick-up Order List
     * @param : delivery_type
     * @return response
     */

    public function pickUpOrders(Request $request) {
        $requestData = $request->all();
        try {
            $orders = ProductOrder::where("delivery_type", "pick-up")->where("is_deleted", "0")->orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $orders = $orders->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $orders = $orders->get();
            }
            return view('admin::orders.pick-up-orders.index', compact("orders", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Get resource data using orderid
     * @param : $orderId
     * @return Renderable
     */

    public function pickUpOrderShow($orderid) {
        try {
            $order = [];
            if ($orderid != null) {
                $order = ProductOrder::where('order_number', $orderid)->where("delivery_type", "pick-up")->first();
                return view('admin::orders.pick-up-orders.show', compact('order', 'orderid'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect("/admin/product-delivery-orders/")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function cancelOrderIndex(Request $request) {
        $requestData = $request->all();
        try {
            $orders = ProductOrder::where("order_status", "5")->where("is_deleted", "0")->orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $orders = $orders->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('updated_at', '>=', $request->start_date)->whereDate('updated_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $orders = $orders->get();
            }

            return view('admin::orders.cancel-orders.index', compact("orders", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Get resource data using orderid
     * @param : $orderId
     * @return Renderable
     */

    public function cancelOrdershow($orderid) {
        try {
            $order = [];
            if ($orderid != null) {
                $order = ProductOrder::where(['order_number' => $orderid, "order_status" => "5"])->first();
                return view('admin::orders.cancel-orders.show', compact('order', 'orderid'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect("/admin/product-cancel-orders/")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function completedOrderIndex(Request $request) {
        $requestData = $request->all();
        try {
            $orders = ProductOrder::where("order_status", "4")->where("is_deleted", "0")->orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $orders = $orders->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('updated_at', '>=', $request->start_date)->whereDate('updated_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $orders = $orders->get();
            }
            return view('admin::orders.completed-orders.index', compact("orders", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Get resource data using orderid
     * @param : $orderId
     * @return Renderable
     */

    public function completedOrdershow($orderid) {
        try {
            $order = [];
            if ($orderid != null) {
                $order = ProductOrder::where(['order_number' => $orderid, "order_status" => "4"])->first();
                return view('admin::orders.completed-orders.show', compact('order', 'orderid'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect("/admin/product-completed-orders/")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function returnOrderIndex(Request $request) {
        $requestData = $request->all();
        try {
            $orders = ProductOrder::where("order_status", "6")->where("is_deleted", "0")->orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $orders = $orders->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('updated_at', '>=', $request->start_date)->whereDate('updated_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $orders = $orders->get();
            }
            return view('admin::orders.return-orders.index', compact("orders", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Get resource data using orderid
     * @param : $orderId
     * @return Renderable
     */

    public function returnOrdershow($orderid) {
        try {
            $order = [];
            if ($orderid != null) {
                $order = ProductOrder::where(['order_number' => $orderid, "order_status" => "6"])->first();
                return view('admin::orders.return-orders.show', compact('order', 'orderid'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect("/admin/product-return-orders/")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Delete Product Order
     * @param : $product_id, $order_id
     * @return response
     */

    public function destroy(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                //$this->ProductOrder->where(['id' => $data['slug'], "order_number" => $data["order_number"]])->delete();
                $this->ProductOrder->where(['id' => $data['slug'], "order_number" => $data["order_number"]])->update(["is_deleted" => "1"]);
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Restore Delete Product Order
     * @param : $product_id, $order_id
     * @return response
     */

    public function destroyRestore(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                $this->ProductOrder->where(['id' => $data['slug'], "order_number" => $data["order_number"]])->update(["is_deleted" => "0"]);
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Delete Product Order Permanently
     * @param : $product_id, $order_id
     * @return response
     */

    public function destroyPermanently(Request $request) {
        $data = $request->all();
        if (isset($data['slug'])) {
            try {
                $this->ProductOrder->where(['id' => $data['slug'], "order_number" => $data["order_number"]])->delete();
                return json_encode(['status' => 200]);
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        } else {
            return json_encode(['status' => 500]);
        }
    }

    /*
     * Soft Delete Product Order View
     * @param : $is_deleted
     * @return response
     */

    public function indexSoftDeleteOrder(Request $request) {

        $requestData = $request->all();
        try {

            $orders = ProductOrder::where("is_deleted", "1")->orderBy("id", "DESC");
            if (@$request->start_date && !empty($request->start_date) && @$request->end_date && !empty($request->end_date)) {
                $orders = $orders->where(function ($query) use ($request) {
                            if ($request->has('start_date') && $request->has('end_date')) {
                                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                            }
                        })->get();
            } else {
                $orders = $orders->get();
            }
            return view('admin::orders.soft-deleteds.index', compact("orders", "requestData"));
        } catch (Exception $ex) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Soft Delete Product Order View
     * @param : $is_deleted, $order_id
     * @return response
     */

    public function showSoftDeleteOrder($orderid) {
        try {
            $order = [];
            if ($orderid != null) {
                $order = ProductOrder::where(['order_number' => $orderid, "is_deleted" => "1"])->first();
                return view('admin::orders.soft-deleteds.show', compact('order', 'orderid'));
            } else {
                Toastr::error('Either something went wrong or invalid access!', 'Error');
                return redirect("/admin/product-softdelete-orders/")->with('errors', "Either something went wrong or invalid access!");
            }
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Refund Order Amount
     * @param 
     * @return response
     */

    public function returnOrderItemPayment(Request $request) {
        $data = $request->all();

        $valiKey = [
            'sub_total' => 'required',
        ];
        $valiMsg = [
            'sub_total.required' => 'Please select sub total',
        ];
        $validator = Validator::make($data, $valiKey, $valiMsg);


        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->messages());
        }
        try {
            \DB::beginTransaction();
            $userId = @$data["user_id"];
            $order_id = @$data["order_id"];
            $user_email = @$data["user_email_address"];

            $amount = @$data["sub_total"] + @$data["tip_amount"] + @$data["product_tax"] + @$data["delivery_charges_amount"];

            if (!empty($userId) && !empty($order_id)) {
                $productOrder = ProductOrder::where(["status" => "1", "id" => $data["order_id"], "user_id" => $data["user_id"]])->first();
                if (@$productOrder && !empty($productOrder)) {
                    if (@$productOrder["order_status"] && $productOrder["order_status"] == "6") {
                        $refundStatus = Helper::returnRefundsStripe($productOrder, $amount);
                        if (@$refundStatus["status"] && $refundStatus["status"] == "succeeded") {
                            $refundDataIn = [
                                "user_id" => $userId,
                                "order_id" => $order_id,
                                "order_item_id" => null,
                                "order_payment_return_type" => "2", //Refund Payment
                                "refund_id" => @$refundStatus["id"],
                                "refund_balance_transaction_id" => @$refundStatus["balance_transaction"],
                                "charge_id" => @$refundStatus["charge"],
                                "refund_status" => @$refundStatus["status"],
                                "refund_response" => json_encode(@$refundStatus),
                                "sub_total" => (@$data["sub_total"]) ? $data["sub_total"] : "0",
                                "product_tax" => (@$data["product_tax"]) ? $data["product_tax"] : "0",
                                "delivery_charges_amount" => (@$data["delivery_charges_amount"]) ? $data["delivery_charges_amount"] : "0",
                                "tip_amount" => (@$data["tip_amount"]) ? $data["tip_amount"] : "0",
                                "grand_total" => (@$amount) ? $amount : "0",
                            ];
                            TransectionRefundHistory::create($refundDataIn);
                            $refundStatus = true;
                        } else {
                            $refundStatus = false;
                        }
                        //$refundStatus = true;
                        if ($refundStatus == true) {
                            /*
                             * Create Shipping Row
                             */
                            OrderShippingProduct::create(["user_id" => $userId, "product_order_id" => $order_id, "title" => "Payment refund", "shipping_status" => "1", "status" => "1"]);


                            \DB::commit();
                            Toastr::success('Refund amount transfer successfully.', 'Success');
                            return redirect()->route("admin.view.product.return.order", [@$productOrder->order_number])->withSuccess("Refund amount transfer successfully.");
                        } else {
                            \DB::rollback();
                            Toastr::error('Either something went wrong or invalid access!', 'Error');
                            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
                        }
                    }
                } else {
                    \DB::rollback();
                    Toastr::error('Either something went wrong or invalid access!', 'Error');
                    return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
                }
            }
        } catch (Exception $ex) {
            \DB::rollback();
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

}
