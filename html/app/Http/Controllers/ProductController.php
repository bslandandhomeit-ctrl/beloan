<?php

/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 5/25/201000000
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;

use App\Models\Cbc\ClientCbcIdentificationTypes;
use App\Models\ProductCategory;
use App\Models\ProductBrand;
use App\Models\Product;
use App\Models\ProductRecord;
use App\Models\Dealer;
use App\Models\Products\Product_type;
use App\Models\Products\Product_products_type;
use Request;
//use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;

class ProductController extends Controller {

    public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function getAdd() {

        $data['categories'] = ProductCategory::select('id', 'category_name')->get();
        $data['productsProTypes']= Product_type::all();
        $data['brands'] = ProductBrand::all();
        $data['dealers'] = Dealer::select('id', 'dealer')->get();
        return $this->view('products.add', $data);
    }

    public function postAdd() {
        $data = Request::except(['_token']);
        $rules = [
            'prod_Prod_type' => 'required',
            'ipPrice' => 'required|numeric'
        ];
        $attr = [
            'prod_Prod_type' => 'Product Type',
            'ipPrice' => 'Price'
        ];
        $validator = Validator::make($data, $rules);
        $validator->setAttributeNames($attr);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $pro = new Product;
            $pro->product_name = Request::input('ipName');
            $pro->product_type = Request::input('ipType');
            $pro->product_price = Request::input('ipPrice');
            $pro->product_type_id = Request::input('prod_Prod_type');
            $pro->category_id= Request::input('prod_cat');
            $pro->product_year = Request::input('selYear');
            $pro->brand_id = Request::input('selBrand');
            $pro->dealer_id = Request::input('selDealer');
            $pro->serial_number = Request::input('ipSerial');
            $pro->engine_number = Request::input('ipEngine');
            $pro->plate_num = Request::input('plate_num');
            $pro->user_id = Auth::user()->id;
            $pro->is_loan = 0;
            if ($pro->save()) {
                $this->userActivity($pro->user_id, $pro->id, 7, 'Add product');
                return redirect()->route('list_product');
            }
        }
        return redirect()->back();
    }

    public function getList() {

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
    	$query_arr = array('user_id'=>Auth::user()->id, 'branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
    	$B0 = new Product();
    	$B0 = $this->getUserByBranch($B0, 'user_id', $query_arr);

        $products = $B0->with(['brand', 'category','prod_prod_type', 'dealer' => function($query) {
                $query->select('id', 'dealer');
            }])->orderby('id', 'ASC');

        $querystringArray = [];
        if (Request::has('ipName')) {
            $data['ipName'] = Request::input('ipName');
            $products->where('product_name', 'like', '%' . $data['ipName'] . '%');
            $querystringArray['ipName'] = $data['ipName'];
        }
        if (Request::has('ipCode')) {
            $data['ipCode'] = Request::input('ipCode');
            $products->where('id', '=', $data['ipCode']);
            $querystringArray['ipCode'] = $data['ipCode'];
        }
        if (Request::has('ipSerial')) {
            $data['ipSerial'] = Request::input('ipSerial');
            $products->where('serial_number', 'like', '%' . $data['ipSerial'] . '%');
            $querystringArray['ipSerial'] = $data['ipSerial'];
        }
        if (Request::has('ipModel')) {
            $data['ipModel'] = Request::input('ipModel');
            $products->where('product_type', 'like', '%' . $data['ipModel'] . '%');
            $querystringArray['ipModel'] = $data['ipModel'];
        }

        $data['proList'] = $products->paginate($offset);
        $data['proList']->appends($querystringArray);

        return $this->view('products.list', $data,['offset'=>$offset]);
    }

    public function getEdit($id = 0) {
        if ($id > 0) {

            $data['pro'] = Product::where('id', '=', $id)->with(['brand', 'category'])->first();
            if (!empty($data['pro']) && count($data['pro']) > 0) {
                $data['categories'] = ProductCategory::select('id', 'category_name')->get();
                $data['productsProTypes']= Product_products_type::all();
                //$data['categories'] = ProductCategory::all();
                $data['brands'] = ProductBrand::all();
                $data['dealers'] = Dealer::select('id', 'dealer')->get();
                return $this->view('products.edit', $data);
            }
        }
        return redirect()->back();
    }

    public function postEdit($id = 0) {
        if ($id > 0) {
            $data = Request::except(['_token']);
            $rules = [
                'prod_cat' => 'required'
            ];
            $pro = Product::find($id);
            if($pro->is_loan==0){
            	$rules = array();
            }
            $attr = [
                'prod_cat' => 'Product Category'
            ];
            $validator = Validator::make($data, $rules);
            $validator->setAttributeNames($attr);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                 if($pro->is_loan==0){
	                $pro->product_name = Request::input('ipName');
	                $pro->product_type = Request::input('ipType');
	                $pro->product_price = Request::input('ipPrice');
	                $pro->category_id = Request::input('prod_cat');
	                $pro->product_type_id = Request::input('prod_Prod_type');
	                $pro->brand_id = Request::input('selBrand');
	                $pro->dealer_id = Request::input('selDealer');
	                $pro->serial_number = Request::input('ipSerial');
	                $pro->engine_number = Request::input('ipEngine');
	                $pro->product_year = Request::input('selYear');
                    $pro->plate_num = Request::input('plate_num');
	                $pro->user_id = Auth::user()->id;
                }else{
                	$pro->mou_price = Request::input('mou_price');
                }

                if ($pro->save()) {
                    $this->userActivity(Auth::user()->id, $pro->id, 7, 'Update product', Request::fullUrl());
                    return redirect()->route('list_product');
                }
            }
        }
        return redirect()->back();
    }

    public function getUpdate($id = 0) {
        if ($id > 0) {

            $data['pro'] = Product::where('id', '=', $id)->first();
            if (!empty($data['pro']) && count($data['pro']) > 0) {
                $data['paction_type'] = ProductRecord::where('product_id',$id)->lists('action_type');
                return $this->view('products.update', $data);
            }
        }
        return redirect()->back();
    }

    public function postUpdate($id = 0) {
        if ($id > 0) {
            $product_id = Request::input('pro_id',0);//default=0
            $product = Product::find($product_id);
            if(!empty($product)){
                $action_type = config('static_data.action_type');
                if(!empty($action_type)){
                    $type = Request::input('action_type');
                    $status = 0;
                    foreach ($action_type as $key => $val){
                        if($val == $type){
                            $status = $key;
                            break;
                        }
                    }
                    $product->status = $status;
                    if($product->save() ){
                        $this->userActivity(Auth::user()->id, $product->id, 7, 'Update Product', Request::fullUrl());
                        $pro = new ProductRecord();
                        $pro->product_id = $product_id;
                        $pro->action_type = $type;
                        $pro->location = Request::input('location');
                        $pro->date = Request::input('return_date');
                        $pro->price = Request::input('resale_price');
                        $pro->remark = Request::input('remark');
                        if ($pro->save()) {
                            return redirect()->route('product_detail',[$pro->product_id]);
                        }
                    }
                }
            }

        }
        return redirect()->back();
    }
    //product detail
    public function getDetail($id = 0){
        if ($id > 0) {
            $data['pro'] = Product::where('id', '=', $id)->with(['brand', 'product_types','record', 'dealer', 'loan'])->first();
            if (!empty($data['pro'])) {
                return $this->view('products.detail', $data);
            }
        }
        return redirect()->back();
    }

    public function postAddCategory() {
        if (Request::ajax()) {
            $cate = new ProductCategory;
            $cate->category_name = Request::input('ipCate');
            $cate->description = Request::input('ipDesc');
            $cate->save();
            $this->userActivity(Auth::user()->id, $cate->id, 0, 'Add ProductCategory', Request::fullUrl());
            return ['status' => true, 'insertId' => $cate->id, 'insertLabel' => Request::input('ipCate')];
        }
        return ['status' => false];
    }

    public function postAddProd_type() {

        if (Request::ajax()) {
            $cate = new Product_products_type();
            $cate->type_name = Request::input('type');
            $cate->description = Request::input('desc');
            if($cate->save()) {
                $this->userActivity(Auth::user()->id, $cate->id, 0, 'Add ProductCategory', Request::fullUrl());
                return ['status' => true, 'insertId' => $cate->id, 'insertLabel' => Request::input('type')];
            }
            return ['status' => false, 'insertId' => 0, 'insertLabel' => Request::input('type')];
        }
        return ['status' => false];
    }

    public function postAddBrand() {
        if (Request::ajax()) {
            $brand = new ProductBrand;
            $brand->brand_name = Request::input('ipBrand');
            $brand->description = Request::input('ipDesc');
            $brand->save();
            $this->userActivity(Auth::user()->id, $brand->id, 0, 'Add ProductBrand', Request::fullUrl());
            return ['status' => true, 'insertId' => $brand->id, 'insertLabel' => Request::input('ipBrand')];
        }
        return ['status' => false];
    }

}
