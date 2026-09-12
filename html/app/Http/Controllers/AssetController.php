<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 5/25/201000000
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;
use App\Models\CompanyBranch;
use App\Models\Asset;
use App\Models\AssetRecord;
use App\Models\AssetDepreRecord;
use App\Models\User;
use App\Models\CoaCategory;
use Request;
use Auth;
class AssetController extends Controller {

    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function index(){
        $data['res'] = Asset::paginate(25);
        $data['asset_locations'] = config('static_data.asset_locations');
        $data['asset_categories'] = config('static_data.asset_categories');
        $data['asset_classifications'] = config('static_data.asset_classifications');
        $data['currency'] = config('static_data.currency');
        $branches = CompanyBranch::get();
        foreach($branches as $b){
            $data['branches'][$b->id] = $b->branch_name;
        }

        return $this->view('assets.index', $data);
    }

    function create(){
        $data['asset_locations'] = config('static_data.asset_locations');
        $data['asset_categories'] = config('static_data.asset_categories');
        $data['asset_classifications'] = config('static_data.asset_classifications');
        $data['branches'] = CompanyBranch::get();
        $data['currency'] = config('static_data.currency');
        $data['coas'] = CoaCategory::select('id', 'type', 'account_code', 'name', 'currency')
                        ->where(function($q){
                            $q->where('name', 'like', 'Cash%')->orwhere('name', 'like', 'Cheq%');
                        })->where('type', 6)->where('currency',2)->get();
        return $this->view('assets.create', $data);
    }

    function store(){
        $addNew = new Asset();
        $addNew->category= Request::input('category');
        $addNew->currency= Request::input('currency');
        $coa = $this->asset_coa($addNew->currency, $addNew->category);
        $addNew->main_gl_id= $coa[0];
        $addNew->depre_gl_id= $coa[1];
        $addNew->exp_gl_id= $coa[2];
        $addNew->branch= intval(Request::input('branch'));
        $addNew->depre_meth= 1; //'Straight-Line'

        $addNew->asset_name = Request::input('asset_name');
        $addNew->purchased_date= Request::input('purchased_date');
        $addNew->location= Request::input('location');
        $addNew->tag_num= Request::input('tag_num');
        $addNew->remark= Request::input('remark');
        $addNew->supplier= Request::input('supplier');
        $addNew->invoice_num= Request::input('invoice_num');
        $original_cost = intval(Request::input('original_cost'));
        $addNew->original_cost= $original_cost;
        $addNew->depre_year= Request::input('depre_year');
        $addNew->rate= Request::input('rate');
        $addNew->classification= Request::input('classification');
        $addNew->status=1;
        $addNew->save();

        $faNew = new AssetRecord();
        $faNew->fa_id = $addNew->id;
        $faNew->purpose = 'Register';
        $faNew->created_by = Auth::user()->id;
        $faNew->action = 1;
        $faNew->save();

        // Journal
        $journal_arr = [];
        array_push($journal_arr, [$coa[0], $original_cost, Request::input('remark'),
                                intval(Request::input('credit_coa')), $original_cost, Request::input('remark'),
                                Request::input('remark')]);
        record_journal(null, Request::input('purchased_date'), "Register Fixed Asset", null, $journal_arr, $addNew->branch, $faNew->created_by, Request::input('invoice_num'));

        return redirect()->route('asset_index');
    }

    function edit($id){
        $data['users'] = User::where('role_id', 7)->get();
        $data['asset_locations'] = config('static_data.asset_locations');
        $data['asset_categories'] = config('static_data.asset_categories');
        $data['asset_classifications'] = config('static_data.asset_classifications');
        $data['branches'] = CompanyBranch::get();
        $data['currency'] = config('static_data.currency');

        $data['res'] = Asset::find($id);
        return $this->view('assets.edit', $data);
    }

    function update($id){
        $addNew = Asset::find($id);
        $addNew->category= Request::input('category');
        $addNew->currency= Request::input('currency');
        $coa = $this->asset_coa($addNew->currency, $addNew->category);
        $addNew->main_gl_id= $coa[0];
        $addNew->depre_gl_id= $coa[1];
        $addNew->exp_gl_id= $coa[2];
        $addNew->depre_meth= 1; //'Straight-Line'

        $addNew->asset_name = Request::input('asset_name');
        $addNew->purchased_date= Request::input('purchased_date');
        $addNew->location= Request::input('location');
        $addNew->tag_num= Request::input('tag_num');
        $addNew->remark= Request::input('remark');
        $addNew->supplier= Request::input('supplier');
        $addNew->invoice_num= Request::input('invoice_num');
        $addNew->original_cost= Request::input('original_cost');
        $addNew->depre_year= Request::input('depre_year');
        $addNew->rate= Request::input('rate');
        $addNew->classification= Request::input('classification');
        $addNew->save();

        $faNew = new AssetRecord();
        $faNew->fa_id = $id;
        $faNew->purpose = 'Update';
        $faNew->created_by = Auth::user()->id;
        $faNew->action = 1;
        $faNew->save();

        return redirect()->route('asset_index');
    }

    function ajax_tag_num(){
        $ex = explode('.', Request::input('tag_num'));
        $old_ex = end($ex);
        $ex = explode('-', $ex[0]);
        $old_cx = end($ex);

        $last = Asset::where('category', Request::input('category'))
                ->OrderBy('id', 'DESC')->first();
        $ex = explode('.', $last->tag_num); 

        $ex = end($ex) + 1; 
        $ex = $ex >=10?$ex:'0'.$ex;

        if(Request::input('tag_num') && Request::input('category')==$old_cx) $ex = $old_ex;

        $branch = CompanyBranch::find(Request::input('branch'));

        echo 'TG-'.$branch->short_name.'-'.Request::input('classification').'-'.Request::input('category').'.'.$ex;
    }

    function destroy($id){
        $db = Asset::find($id);
        $db->delete();

        $db1 = AssetRecord::where('fa_id', $id);
        $db1->delete();
    }

    function adjust($id){ 
        $res = Asset::find($id);
        $addNew = Asset::find($id);
        $category = $addNew->category= Request::input('category');
        $currency = $addNew->currency= Request::input('currency');
        $coa = $this->asset_coa($addNew->currency, $addNew->category);
        $main_gl_id = $addNew->main_gl_id= $coa[0];
        $addNew->depre_gl_id= $coa[1];
        $addNew->exp_gl_id= $coa[2];
        $addNew->depre_meth= 1; //'Straight-Line'

        $asset_name = $addNew->asset_name = Request::input('asset_name');
        $purchased_date= $addNew->purchased_date = Request::input('purchased_date');
        $location= $addNew->location = Request::input('location');
        $tag_num= $addNew->tag_num = Request::input('tag_num');
        $remark= $addNew->remark = Request::input('remark');
        $supplier= $addNew->supplier = Request::input('supplier');
        $invoice_num= $addNew->invoice_num = Request::input('invoice_num');
        $original_cost= $addNew->original_cost = Request::input('original_cost');
        $depre_year= $addNew->depre_year = Request::input('depre_year');
        $rate= $addNew->rate = Request::input('rate');
        $classification= $addNew->classification = Request::input('classification');
        $addNew->save();

        $approved_by= Request::input('approved_by');

        if($res->asset_name != $asset_name){ 
            $this->do_adjust($id, 'Asset Name ', $res->asset_name, $asset_name, $remark, $approved_by);
        }

        if($res->purchased_date != $purchased_date){
            $this->do_adjust($id, 'Purchased Date ', $res->purchased_date, $purchased_date, $remark, $approved_by);
        }

        if($res->main_gl_id != $main_gl_id){
            $this->do_adjust($id, 'GL ', $res->main_gl_id, $main_gl_id, $remark, $approved_by);
            // Journal
            $journal_arr = [];
            array_push($journal_arr, [$main_gl_id, $original_cost, Request::input('remark'),
                intval($res->main_gl_id), $original_cost, Request::input('remark'),
                $remark]);
            record_journal(null, $purchased_date, "Adjust Fixed Asset", null, $journal_arr, $res->branch, Auth::user()->id, $invoice_num);
        }

        if($res->category != $category){
            $this->do_adjust($id, 'Category', $res->category, $category, $remark, $approved_by);
        }

        if($res->location != $location){
            $this->do_adjust($id, 'Location', $res->location, $location, $remark, $approved_by);
        }

        if($res->currency != $currency){
            $this->do_adjust($id, 'Currency', $res->currency, $currency, $remark, $approved_by);
        }

        if($res->tag_num != $tag_num){
            $this->do_adjust($id, 'Tag Number', $res->tag_num, $tag_num, $remark, $approved_by);
        }

        if($res->supplier != $supplier){
            $this->do_adjust($id, 'Supplier', $res->supplier, $supplier, $remark, $approved_by);
        }

        if($res->invoice_num != $invoice_num){
            $this->do_adjust($id, 'Invoice Number', $res->invoice_num, $invoice_num, $remark, $approved_by);
        }

        if($res->original_cost != $original_cost){
            $this->do_adjust($id, 'Original Cost', $res->original_cost, $original_cost, $remark, $approved_by);
        }

        if($res->depre_year != $depre_year){
            $this->do_adjust($id, 'Depreciation Year', $res->depre_year, $depre_year, $remark, $approved_by);
        }

        if($res->rate != $rate){
            $this->do_adjust($id, 'Depreciation Rate', $res->rate, $rate, $remark, $approved_by);
        }

//        if($res->depre_meth != $depre_meth){
//            $this->do_adjust($id, 'depre_meth', $res->depre_meth, $depre_meth, $remark, $approved_by);
//        }

        if($res->classification != $classification){
            $this->do_adjust($id, 'classification', $res->classification, $classification, $remark, $approved_by);
        }

        if($res->supplier != $supplier){
            $this->do_adjust($id, 'Supplier ', $res->supplier, $supplier, $remark, $approved_by);
        }
        return redirect()->route('asset_index');
    }

    function do_adjust($id, $type, $a_from, $a_to, $remark, $approved_by){
        $db = new AssetRecord();
        $db->fa_id = $id;
        $db->purpose = 'Adjust '.$type;
        $db->a_from = $a_from;
        $db->a_to = $a_to;
        $db->remark = $remark;
        $db->created_by = Auth::user()->id;
        $db->approved_by = $approved_by; // need replace
        $db->action = 2;
        $db->save();
    }

    function dispose($id){
        $data['users'] = User::where('role_id', 7)->get();
        $data['res'] = Asset::find($id);
        return $this->view('assets.dispose', $data);
    }

    function dispose_update($id){
        $addNew = Asset::find($id);
        $addNew->status = 2; // disposed
        $addNew->save();

        $faNew = new AssetRecord();
        $faNew->fa_id = $id;
        $faNew->purpose = 'Dispose';
        $faNew->created_by = Auth::user()->id;
        $faNew->action = 3;
        $faNew->approved_by = Request::input('approved_by');
        $faNew->amount = Request::input('amount');
        $faNew->a_to = Request::input('a_to');
        $faNew->remark = Request::input('remark');
        $faNew->save();

        return redirect()->route('asset_index');
    }

    function write_off($id){
        $data['users'] = User::where('role_id', 7)->get();
        $data['res'] = Asset::find($id);
        return $this->view('assets.write_off', $data);
    }

    function write_off_update($id){
        $addNew = Asset::find($id);
        $addNew->status = 3;
        $addNew->save();

        $faNew = new AssetRecord();
        $faNew->fa_id = $id;
        $faNew->purpose = 'Write off';
        $faNew->created_by = Auth::user()->id;
        $faNew->action = 4;
        $faNew->approved_by = Request::input('approved_by');
        $faNew->remark = Request::input('remark');
        $faNew->net_book = Request::input('net_book');
        $faNew->save();

        return redirect()->route('asset_index');
    }

    function fa_detail(){
        $data['report_title'] = "Fixed Assets's Detail";
        $data['dpStart'] = $dpStart = $data['as_at'] = Request::input('dpStart')?Request::input('dpStart'):date('Y-m-d');

        $data['asset_locations'] = config('static_data.asset_locations');
        $data['asset_categories'] = config('static_data.asset_categories');
        $data['asset_classifications'] = config('static_data.asset_classifications');
        $data['currency'] = config('static_data.currency');

        $data['LND'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LND')->where('created_at', '<=', $dpStart)->get();
        $data['BLD'] = Asset::with('depre_record')->with('asset_record')->where('category', 'BLD')->where('created_at', '<=', $dpStart)->get();
        $data['LHI'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LHI')->where('created_at', '<=', $dpStart)->get();

        $data['FF'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LIKE', '1%')->where('created_at', '<=', $dpStart)->get();
        $data['EQ'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LIKE', '2%')->where('created_at', '<=', $dpStart)->get();
        $data['CE'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LIKE', '3%')->where('created_at', '<=', $dpStart)->get();
        $data['MO'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LIKE', '4%')->where('created_at', '<=', $dpStart)->get();
        $data['CS'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LIKE', '5%')->where('created_at', '<=', $dpStart)->get();
        $data['OT'] = Asset::with('depre_record')->with('asset_record')->where('category', 'LIKE', '9%')->where('created_at', '<=', $dpStart)->get();

        $data['last_record'] = AssetDepreRecord::where('created_at', '<', $data['dpStart'])->first()->created_at;

        $data['data'] = date('Y-m-d');
        return $this->view('assets.fa_detail', $data);
    }

     function depreciation_summary(){


         $fix_assets = Asset::select('purchased_date', 'asset_name', 'main_gl_id', 'category', 'location', 'tag_num', 'rate', 'original_cost', 'depre_year', 'depre_meth', 'status')
                        ->with(['depre_journals'=>function($q){
                            $q->with('journal');
                        }])
                        ->where('status', 1)
                        ->get();
         //dd($fix_assets);
         return $this->view('assets.depreciation_summary', $data);
     }

    function asset_coa($currency, $code){
        $fcode = $code[0]; 
        $data = array(
                1 => array(
                        'main_gl'=>'Furniture and Fixtures', 
                        'depre_gl'=>'Accumulated Depreciation - Furniture and Fixtures',
                        'exp_gl'=>'Exp-Depre-Furniture and Fixtures',
                    ),
                2 => array(
                        'main_gl'=>'Equipment',
                        'depre_gl'=>'Accumulated Depreciation - Equipment',
                        'exp_gl'=>'Exp-Depre-Equipment',
                    ),
                3 => array(
                        'main_gl'=>'Computer Equipment',
                        'depre_gl'=>'Accumulated Depreciation - Computer Equipment',
                        'exp_gl'=>'Exp-Depre-Computer Equipment',
                    ),
                4 => array(
                        'main_gl'=>'Motor Vehicles', 
                        'depre_gl'=>'Accumulated Depreciation - Motor Vehicles',
                        'exp_gl'=>'Exp-Depre-Motor Vehicles',
                    ),
                5 => array(
                        
                    ),
                9 => array(
                         
                    ),
            );
        $data1[] = CoaCategory::where('currency', $currency)->where('name', $data[$fcode]['main_gl'])->where('type', 6)->first()->id;
        $data1[] = CoaCategory::where('currency', $currency)->where('name', $data[$fcode]['depre_gl'])->where('type', 6)->first()->id;
        $data1[] = CoaCategory::where('currency', $currency)->where('name', $data[$fcode]['exp_gl'])->where('type', 6)->first()->id;
        return $data1;
    }


}