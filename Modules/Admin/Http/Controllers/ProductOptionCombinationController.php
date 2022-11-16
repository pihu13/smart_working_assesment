<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Route;
use Auth;
use Hash;
use Validator;
use Mail;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\ProductOption;
use App\Models\ProductCombinationOption;
use App\Models\SystemSetting;
use App\Models\EmailTemplate;
use App\Helpers\Helper;
use Toastr;
use View;
use DB;

class ProductOptionCombinationController extends Controller {
    /*
     * Add Variation Run Time
     * @param : $variation_data
     * @return response
     */

    public function createVariable(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["id"]);
        try {
            $product = [];
            if (@$id && !empty($id) && $id != null) {
                $product = [];
                $product = Product::where('id', $id)->first();
                $accounts = (string) View::make('admin::products.combinations.create-variable-ajax')->with("product", $product);
                return json_encode(['status' => 200, 'msg' => $accounts]);
            } else {
                return json_encode(['status' => 500, 'msg' => 'Invalid product ID.']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
        }
    }

    /*
     * Store Product Variation
     * @param : $variant_name,$product_id
     * @return response
     */

    public function storeVariable(Request $request) {
        $data = $request->all();

        $product_id = base64_decode($data['product_id']);

        $variant_name = (@$request->variant_name) ? $request->variant_name : ["old"];

        $variat = ProductVariant::whereIn('variant_name', $variant_name)->
                        where('product_id', $product_id)->first();

        if (!empty($variat)) {
            $arr["variant_name"] = ["Variable already exist."];
            return json_encode(['status' => 501, 'errors' => $arr]);
            //return json_encode(['status' => 201, 'msg' => "Variable already exist."]);
        } else {
            $validator = Validator::make($data, [
                        'variant_name' => 'required'
                            ], [
                        'variant_name.required' => 'Please enter variable name'
                            ]
            );
        }

        if ($validator->fails()) {
            return json_encode(['status' => 501, 'errors' => $validator->messages()]);
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);
                $userId = Auth::user()->id;

                if (@$data['variant_name'] && count($data['variant_name']) > 0) {
                    foreach ($data['variant_name'] as $vari) {
                        $proVariable = [
                            'product_id' => $product_id,
                            'variant_name' => $vari,
                            'status' => $data['status'],
                        ];
                        ProductVariant::create($proVariable);
                    }
                }
                DB::commit();
                return json_encode(['status' => 200, 'msg' => 'Product variable added successfully.']);
            } catch (Exception $ex) {
                DB::rollback();
                return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
            }
        }
    }

    /*
     * Add Variable Option
     * @param : $variable_id, $option_name
     * @return response
     */

    public function createVariableOption(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["id"]);
        try {
            $product = [];
            $variables = [];
            if (@$id && !empty($id) && $id != null) {
                if ($id != null) {
                    $variables = ProductVariant::where('product_id', $id)
                            ->where("status", "1")
                            ->get();
                }
                $product = Product::where('id', $id)->first();

                $accounts = (string) View::make('admin::products.combinations.create-product-option-ajax')
                                ->with("variables", $variables)
                                ->with("product", $product)
                                ->with("id", $id);

                return json_encode(['status' => 200, 'msg' => $accounts]);
            } else {
                return json_encode(['status' => 500, 'msg' => 'Invalid product ID.']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
        }
    }

    /*
     * Store Option
     * @param : $variable_id, $option_name
     * @return response
     */

    public function storeProOption(Request $request) {
        $data = $request->all();


        $option_name = (@$request->option_name) ? $request->option_name : ["old"];
        $ProductOptions = ProductOption::where(["product_variant_id" => $data['product_variant_id']])->whereIn("option_name", $option_name)->get();

        if (@$ProductOptions && count($ProductOptions) > 0) {
            $arr["option_name"] = ["Variable option already exist."];
            return json_encode(['status' => 501, 'errors' => $arr]);
        } else {
            $validator = Validator::make($data, [
                        'option_name' => 'required',
                        'product_variant_id' => 'required',
                            ], [
                        'option_name.required' => 'Please enter option name',
                        'product_variant_id.required' => 'Please enter select variant',
                            ]
            );
        }

        if ($validator->fails()) {
            return json_encode(['status' => 501, 'errors' => $validator->messages()]);
        } else {
            try {
                DB::beginTransaction();
                $data = $request->all();
                unset($data['_token']);

                if (@$data['option_name'] && count($data['option_name']) > 0) {
                    foreach ($data['option_name'] as $option) {
                        $proOption = [
                            'product_variant_id' => $data['product_variant_id'],
                            'option_name' => $option,
                            'status' => "1",
                        ];
                        ProductOption::create($proOption);
                    }
                }
                DB::commit();
                return json_encode(['status' => 200, 'msg' => 'Product option add successfully.']);
            } catch (Exception $ex) {
                DB::rollback();
                return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
            }
        }
    }

    /*
     * Add Combination 
     * @param : $option_id
     * @return response
     */

    public function createCombination(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["id"]);
        try {
            $product = [];
            if (@$id && !empty($id) && $id != null) {

                $product = Product::where('id', $id)->first();

                $accounts = (string) View::make('admin::products.combinations.create-combination-ajax')
                                ->with("product", $product);

                return json_encode(['status' => 200, 'msg' => $accounts]);
            } else {
                return json_encode(['status' => 500, 'msg' => 'Invalid product ID.']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
        }
    }

    /*
     * Store Combination
     * @param : $combination_data
     * @return response
     */

    public function storeCombination(Request $request) {
        $data = $request->all();
        $combi = "";
        if (@$data['combination'] && !empty($data['combination'])) {
            foreach ($data['combination'] as $combination) {
                if (!empty($combination)) {
                    $combi .= $combination . ' ';
                }
            }
        }
        if (!empty($combi)) {
            $combinationSlug = $this->slugify($combi);
        } else {
            $combinationSlug = "";
        }

        $data['product_combination'] = $combinationSlug;

        $countCom = ProductCombinationOption::where("product_id", $data['product_id'])->where("product_combination", $combinationSlug)->count();
        if ($countCom > 0) {
            return json_encode(['status' => 201, 'msg' => 'Combination already exist. Please use another combination.']);
        } else {
            $valiKey = [
                'price' => 'required|numeric|min:0|not_in:0',
                'quantity' => 'required|numeric',
                'product_combination' => 'required|string|max:255',
            ];

            if (!empty($data['sale_price'])) {
                $valiKey['sale_price'] = 'numeric|min:0|not_in:0|lt:price';
            }

//            if (!empty($data['product_tax'])) {
//                $valiKey['product_tax'] = 'required|numeric|min:0';
//            }

            $valiMsg = [
                'price.required' => 'Please enter price',
                'quantity.required' => 'Please enter product stock',
                'product_combination.required' => 'Please select combination',
            ];

            if (!empty($data['sale_price'])) {
                $valiMsg['sale_price'] = 'Sale price should be not greater than to product price';
            }

//            if (!empty($data['product_tax'])) {
//                $valiMsg['product_tax'] = 'Please enter numeric value';
//            }
        }

        $validator = Validator::make($data, $valiKey, $valiMsg);

        if ($validator->fails()) {
            return json_encode(['status' => 501, 'errors' => $validator->messages()]);
        } else {
            try {
                unset($data['_token']);
                $userId = Auth::user()->id;

                $product_combination_names = (@$data['combination'] && count($data['combination']) > 0) ? json_encode($data['combination']) : "";

                if (!empty($data['sale_price']) && $data['sale_price'] > 0) {
                    $price_sale_price = $data['sale_price'];
                } else {
                    $price_sale_price = $data['price'];
                }

                $combination = [
                    'product_id' => $data['product_id'],
                    'product_combination' => $data['product_combination'],
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'quantity' => $data['quantity'],
                    'status' => '1',
                    'product_tax' => "0",
                    'product_combination_names' => $product_combination_names,
                    "price_sale_price" => $price_sale_price,
                ];
                ProductCombinationOption::create($combination);

                return json_encode(['status' => 200, 'msg' => 'Product combination added successfully.']);
            } catch (Exception $ex) {
                return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function slugify($text) {
        try {
            $text = preg_replace('~[^\pL\d]+~u', '-', $text);
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            $text = preg_replace('~[^-\w]+~', '', $text);
            $text = trim($text, '-');
            $text = preg_replace('~-+~', '-', $text);
            $text = strtolower($text);
            if (empty($text)) {
                return '';
            }
            return $text;
        } catch (Exception $ex) {
            $text = "";
            return $text;
        }
    }

//    ============================================================================

    /*
     * Add Variation Run Time
     * @param : $variation_data
     * @return response
     */

    public function createVariableEdit(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["id"]);
        try {
            $product = [];
            if (@$id && !empty($id) && $id != null) {
                $product = [];
                $product = Product::where('id', $id)->first();
                $accounts = (string) View::make('admin::products.combinations-edit.create-variable-ajax')->with("product", $product);
                return json_encode(['status' => 200, 'msg' => $accounts]);
            } else {
                return json_encode(['status' => 500, 'msg' => 'Invalid product ID.']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
        }
    }

    /*
     * Store Product Variation
     * @param : $variant_name,$product_id
     * @return response
     */

    public function storeVariableEdit(Request $request) {
        $data = $request->all();

        $product_id = base64_decode($data['product_id']);

        $variat = ProductVariant::whereIn('variant_name', $request->variant_name)->
                        where('product_id', $product_id)->first();

        if (!empty($variat)) {
            $arr["variant_name"] = ["Variable already exist."];
            return json_encode(['status' => 501, 'errors' => $arr]);
            //return json_encode(['status' => 201, 'msg' => "Variable already exist."]);
        } else {
            $validator = Validator::make($data, [
                        'variant_name' => 'required'
                            ], [
                        'variant_name.required' => 'Please enter variable name'
                            ]
            );
        }

        if ($validator->fails()) {
            return json_encode(['status' => 501, 'errors' => $validator->messages()]);
        } else {
            try {
                DB::beginTransaction();
                unset($data['_token']);
                $userId = Auth::user()->id;

                if (@$data['variant_name'] && count($data['variant_name']) > 0) {
                    foreach ($data['variant_name'] as $vari) {
                        $proVariable = [
                            'product_id' => $product_id,
                            'variant_name' => $vari,
                            'status' => $data['status'],
                        ];
                        ProductVariant::create($proVariable);
                    }
                }

                $id = @$product_id;
                $variables = [];
                if ($id != null) {
                    $variables = ProductVariant::where('product_id', $id)->get();
                }
                $product = Product::where('id', $id)->first();
                $accounts = (string) View::make('admin::products.product-variation-option-combination-edit')->with(["variables" => $variables, "id" => $id, "product" => $product]);

                DB::commit();
                return json_encode(['status' => 200, 'msg' => 'Product variable added successfully.', 'data' => $accounts]);
            } catch (Exception $ex) {
                DB::rollback();
                return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
            }
        }
    }

    /*
     * Add Variable Option
     * @param : $variable_id, $option_name
     * @return response
     */

    public function createVariableOptionEdit(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["id"]);
        try {
            $product = [];
            $variables = [];
            if (@$id && !empty($id) && $id != null) {
                if ($id != null) {
                    $variables = ProductVariant::where('product_id', $id)
                            ->where("status", "1")
                            ->get();
                }
                $product = Product::where('id', $id)->first();

                $accounts = (string) View::make('admin::products.combinations-edit.create-product-option-ajax')
                                ->with("variables", $variables)
                                ->with("product", $product)
                                ->with("id", $id);

                return json_encode(['status' => 200, 'msg' => $accounts]);
            } else {
                return json_encode(['status' => 500, 'msg' => 'Invalid product ID.']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
        }
    }

    /*
     * Store Option
     * @param : $variable_id, $option_name
     * @return response
     */

    public function storeProOptionEdit(Request $request) {
        $data = $request->all();

        $option_name = (@$request->option_name) ? $request->option_name : ["old"];
        $ProductOptions = ProductOption::where(["product_variant_id" => $data['product_variant_id']])->whereIn("option_name", $option_name)->get();

        if (@$ProductOptions && count($ProductOptions) > 0) {
            $arr["option_name"] = ["Variable option already exist."];
            return json_encode(['status' => 501, 'errors' => $arr]);
        } else {
            $validator = Validator::make($data, [
                        'option_name' => 'required',
                        'product_variant_id' => 'required',
                            ], [
                        'option_name.required' => 'Please enter option name',
                        'product_variant_id.required' => 'Please enter select variant',
                            ]
            );
        }

        if ($validator->fails()) {
            return json_encode(['status' => 501, 'errors' => $validator->messages()]);
        } else {
            try {
                DB::beginTransaction();
                $data = $request->all();
                unset($data['_token']);

                if (@$data['option_name'] && count($data['option_name']) > 0) {
                    foreach ($data['option_name'] as $option) {
                        $proOption = [
                            'product_variant_id' => $data['product_variant_id'],
                            'option_name' => $option,
                            'status' => "1",
                        ];
                        ProductOption::create($proOption);
                    }
                }

                $id = @$data["product_id"];
                $variables = [];
                if ($id != null) {
                    $variables = ProductVariant::where('product_id', $id)->get();
                }
                $product = Product::where('id', $id)->first();
                $accounts = (string) View::make('admin::products.product-variation-option-combination-edit')->with(["variables" => $variables, "id" => $id, "product" => $product]);

                DB::commit();
                return json_encode(['status' => 200, 'msg' => 'Product option added successfully.', 'data' => $accounts]);
            } catch (Exception $ex) {
                DB::rollback();
                return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
            }
        }
    }

    /*
     * Add Combination 
     * @param : $option_id
     * @return response
     */

    public function createCombinationEdit(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["id"]);
        try {
            $product = [];
            if (@$id && !empty($id) && $id != null) {

                $product = Product::where('id', $id)->first();

                $accounts = (string) View::make('admin::products.combinations-edit.create-combination-ajax')
                                ->with("product", $product);

                return json_encode(['status' => 200, 'msg' => $accounts]);
            } else {
                return json_encode(['status' => 500, 'msg' => 'Invalid product ID.']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
        }
    }

    /*
     * Store Combination
     * @param : $combination_data
     * @return response
     */

    public function storeCombinationEdit(Request $request) {
        $data = $request->all();
        $combi = "";
        if (@$data['combination'] && !empty($data['combination'])) {
            foreach ($data['combination'] as $combination) {
                if (!empty($combination)) {
                    $combi .= $combination . ' ';
                }
            }
        }
        if (!empty($combi)) {
            $combinationSlug = $this->slugify($combi);
        } else {
            $combinationSlug = "";
        }

        $data['product_combination'] = $combinationSlug;

        $countCom = ProductCombinationOption::where("product_id", $data['product_id'])->where("product_combination", $combinationSlug)->count();
        if ($countCom > 0) {
            return json_encode(['status' => 201, 'msg' => 'Combination already exist. Please use another combination.']);
        } else {
            $valiKey = [
                'price' => 'required|numeric|min:0|not_in:0',
                'quantity' => 'required|numeric',
                'product_combination' => 'required|string|max:255',
            ];

            if (!empty($data['sale_price'])) {
                $valiKey['sale_price'] = 'numeric|min:0|not_in:0|lt:price';
            }

//            if (!empty($data['product_tax'])) {
//                $valiKey['product_tax'] = 'required|numeric|min:0';
//            }

            $valiMsg = [
                'price.required' => 'Please enter price',
                'quantity.required' => 'Please enter product stock',
                'product_combination.required' => 'Please select combination',
            ];

            if (!empty($data['sale_price'])) {
                $valiMsg['sale_price'] = 'Sale price should be not greater than to product price';
            }

//            if (!empty($data['product_tax'])) {
//                $valiMsg['product_tax'] = 'Please enter numeric value';
//            }
        }

        $validator = Validator::make($data, $valiKey, $valiMsg);

        if ($validator->fails()) {
            return json_encode(['status' => 501, 'errors' => $validator->messages()]);
        } else {
            try {
                unset($data['_token']);
                $userId = Auth::user()->id;

                $product_combination_names = (@$data['combination'] && count($data['combination']) > 0) ? json_encode($data['combination']) : "";

                if (!empty($data['sale_price']) && $data['sale_price'] > 0) {
                    $price_sale_price = $data['sale_price'];
                } else {
                    $price_sale_price = $data['price'];
                }

                $combination = [
                    'product_id' => $data['product_id'],
                    'product_combination' => $data['product_combination'],
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'quantity' => $data['quantity'],
                    'status' => '1',
                    'product_tax' => "0",
                    'product_combination_names' => $product_combination_names,
                    "price_sale_price" => $price_sale_price,
                ];
                ProductCombinationOption::create($combination);

                $id = @$data["product_id"];
                $variables = [];
                if ($id != null) {
                    $variables = ProductVariant::where('product_id', $id)->get();
                }
                $product = Product::where('id', $id)->first();
                $accounts = (string) View::make('admin::products.product-variation-option-combination-edit')->with(["variables" => $variables, "id" => $id, "product" => $product]);

                return json_encode(['status' => 200, 'msg' => 'Product combination added successfully.', 'data' => $accounts]);
            } catch (Exception $ex) {
                return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
            }
        }
    }

    /*
     * Product Variable Status Update
     * @param : $variable_id, $status
     * @return response
     */

    public function productVariationStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 0 || $data['status'] == 1) {
            try {
                if ($data['status'] == 1) {
                    ProductVariant::where('id', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 200]);
                } else {
                    ProductVariant::where('id', $data['slug'])->update(['status' => $data['status']]);
                    return json_encode(['status' => 201]);
                }
            } catch (\Exception $e) {
                return json_encode(['status' => 500]);
            }
        }
    }

    /*
     * Delete product Variation
     * @param : $variable_id
     * @return response
     */

    public function variationDestroy($id, $pid) {
        try {
            $variant = ProductVariant::where('id', $id)->first();
            if (@$variant->id && !empty($variant->id)) {
                $productOption = ProductOption::where("product_variant_id", $variant->id)->get();
                if (@$productOption && count($productOption) > 0) {
                    foreach ($productOption as $val) {
                        ProductCombinationOption::where('product_combination', 'like', '%' . $val->option_name . '%')->delete();
                    }
                }
                ProductVariant::where('id', $id)->delete();
            }
            Toastr::success('Product variation remove successfully.', 'Success');
            return redirect()->back()->with('success', 'Product variation remove successfully.');
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Delete Product Option
     * @param : $option_id
     * @return response
     */

    public function optionDestroy($oid, $vid, $pid) {
        try {
            $option = ProductOption::where('id', $oid)->where("product_variant_id", $vid)->where("status", "1")->first();
            if (@$option->option_name && !empty($option->option_name)) {
                ProductCombinationOption::where('product_combination', 'like', '%' . $option->option_name . '%')->delete();
                ProductOption::where('id', $oid)->where("product_variant_id", $vid)->where("status", "1")->delete();
            }

            Toastr::success('Product variant option remove successfully.', 'Success');
            return redirect()->back()->with('success', 'Product variant option remove successfully.');
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Edit Combination
     * @param : $combination_id
     * @return response
     */

    public function editCombination(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["id"]);
        try {
            $combinations = [];
            if (@$id && !empty($id)) {
                $combinations = ProductCombinationOption::where('id', $id)->where('status', '1')->first();
                $accounts = (string) View::make('admin::products.combinations-edit.update-combination-ajax')->with("combinations", $combinations);
                return json_encode(['status' => 200, 'msg' => $accounts]);
            } else {
                return json_encode(['status' => 500, 'msg' => 'Invalid combination ID.']);
            }
        } catch (Exception $ex) {
            return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
        }
    }

    /*
     * Update Combination
     * @param : $combination_id
     * @return response
     */

    public function updateCombination(Request $request) {
        $data = $request->all();
        $id = base64_decode($data["com_id"]);

        $countCom = ProductCombinationOption::where("id", $id)->where("status", "1")->first();

        if (@$countCom && !empty($countCom)) {
            $valiKey = [
                'price' => 'required|numeric|min:0|not_in:0',
                'quantity' => 'required|numeric',
            ];

            if (!empty($data['sale_price'])) {
                $valiKey['sale_price'] = 'numeric|min:0|not_in:0|lt:price';
            }

//            if (!empty($data['product_tax'])) {
//                $valiKey['product_tax'] = 'required|numeric|min:0|not_in:0';
//            }

            $valiMsg = [
                'price.required' => 'Please enter price',
                'quantity.required' => 'Please enter product stock',
            ];

            if (!empty($data['sale_price'])) {
                $valiMsg['sale_price'] = 'Sale price should be not greater than to product price';
            }

//            if (!empty($data['product_tax'])) {
//                $valiMsg['product_tax'] = 'Please enter numeric value';
//            }

            $validator = Validator::make($data, $valiKey, $valiMsg);

            if ($validator->fails()) {
                return json_encode(['status' => 501, 'errors' => $validator->messages()]);
            } else {
                try {
                    /*
                     * Send Mail When Product Available
                     * @param : $product_id, $user_id
                     */

                    if (@$countCom->quantity == 0 && $data["quantity"] > 0) {
                        $proId = @$countCom->product_id;
                        $cartProducts = Cart::where("product_id", $proId)->where("product_combination", @$countCom->product_combination)->get();
                        if (@$cartProducts && count($cartProducts) > 0) {
                            foreach ($cartProducts as $cartPro) {
                                $slug = "product-stock-update-email-template";
                                $mailMessage = EmailTemplate::where('slug', $slug)->first();
                                $url = route("product-details", [$cartPro->productDetails->slug]);
                                $bodyText = str_replace(
                                        array("##USERNAME##", "##TITLE##", "##Link##"),
                                        array($cartPro->userDetails->name, $cartPro->productDetails->title, $url),
                                        $mailMessage->content
                                );
                                $subject = $mailMessage->subject;
                                $to = @$cartPro->userDetails->email;
                                $dataEmail = ["body" => $bodyText];
                                $this->sendEmail($dataEmail, $subject, $to);
                            }
                        }
                    }

                    if (!empty($data['sale_price']) && $data['sale_price'] > 0) {
                        $price_sale_price = $data['sale_price'];
                    } else {
                        $price_sale_price = $data['price'];
                    }


                    $combination = [
                        'price' => $data['price'],
                        'sale_price' => $data['sale_price'],
                        'quantity' => $data['quantity'],
                        'product_tax' => "0",
                        'price_sale_price' => $price_sale_price,
                    ];
                    ProductCombinationOption::where('id', $id)->update($combination);


                    $id = @$data["product_id"];
                    $variables = [];
                    if ($id != null) {
                        $variables = ProductVariant::where('product_id', $id)->get();
                    }
                    $product = Product::where('id', $id)->first();
                    $accounts = (string) View::make('admin::products.product-variation-option-combination-edit')->with(["variables" => $variables, "id" => $id, "product" => $product]);

                    return json_encode(['status' => 200, 'msg' => 'Product combination update successfully.', 'data' => $accounts]);
                } catch (Exception $ex) {
                    return json_encode(['status' => 500, 'msg' => 'Either something went wrong or invalid access!']);
                }
            }
        } else {
            return json_encode(['status' => 500, 'msg' => 'Invalid combination ID.']);
        }
    }

    /*
     * Delete Product Combination
     * @param : $combination_id, $product_id
     * @return response
     */

    public function comDestroy($id, $pid) {
        try {
            ProductCombinationOption::where('id', $id)->delete();
            Toastr::success('Product combination remove successfully.', 'Success');
            return redirect()->back()->with('success', 'Product combination remove successfully.');
        } catch (\Exception $e) {
            Toastr::error('Either something went wrong or invalid access!', 'Error');
            return redirect()->back()->with('errors', "Either something went wrong or invalid access!");
        }
    }

    /*
     * Email Send Function
     */

    public function sendEmail($data, $subject, $to) {
        Mail::send('emails.email_template', $data, function($message) use($subject, $to) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
            $message->to($to);
            $message->subject($subject . ' :Welcome to ' . env('APP_NAME'));
        });
    }

}
