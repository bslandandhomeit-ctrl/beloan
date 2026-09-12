<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 5/25/2015
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CoaCategory;
use App\Models\CompanyBranch;
use App\Models\Currency;
use App\Models\JournalDetail;
use App\Models\JournalRequiry;
use App\Models\Vendor;
use App\Models\AccountCustomer;
use Illuminate\Support\Facades\DB;

use League\Flysystem\Exception;
use Request;
use Auth;
use Image;
use Validator, Input, Redirect;


class TransactionCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    final function index()
    {

        return $this->view('accounting.transaction_code.index');
    }

    final function write_check()
    {
        $coa = CoaCategory::where('type', 6)->where('nbc_code', 'like', '1%')->with('detail',['currency_i'=>function($q){$q->select('id','code');}])->get();
        $coa_exp = CoaCategory::where('type', 6)->with('detail',['currency_i'=>function($q){$q->select('id','code');}])->get();
        $vendor = Vendor::all();

        return $this->view('accounting.transaction_code.write_check',['coa'=>$coa,'coa_exp'=>$coa_exp, 'vendor'=>$vendor]);
    }

    final function post_write_check()
    {

        if (Request::ajax()) {

            try {
                DB::beginTransaction();

                $upload = $this->UploadImageFile('photo', 'images/receipt');
                if(!empty($upload['error'])){

                    $res['error'] = $upload['error'];

                } else {

                        $journald = new JournalDetail();
                        $journalR = new JournalRequiry();
                        $journalR->ref_name_id = Request::input('toVendor');
                        $journalR->ref_name_type = 1;// for vendor only
                        $journalR->trans_type = 12;
                        $journalR->invoice_number = (Request::input('inNum')) ? Request::input('inNum') : str_pad(JournalRequiry::max('id') + 1, 8, '0', STR_PAD_LEFT); // invoice_number
                        $journalR->user_id = Auth::user()->id;
                        $journalR->description = Request::input('description') . ' / ' . Request::input('address');
                        $journalR->entry_date = date("Y-m-d H:i:s", strtotime(Request::input('mdate')));

                        $journalR->receipt = ($upload['uploaded'])?$upload['uploaded']:'';
                        if ($journalR->save()) {

                            $branch =  CompanyBranch::where('id',auth()->user()->branch_id)->first();
                            $inputs = Request::all();
                            for ($i = 0; $i < count(Request::input('coa_id')); $i++) {
                                $data[] = [
                                    'coa_id' => (int)$inputs['coa_id'][$i],
                                    'journal_id' => (int)$journalR->id,//Journal Requiry ID, I have confused with JournalDetial id
                                    'debit' => (int)$inputs['amount'][$i],
                                    'description' => $inputs['memo'][$i],
                                    'branch_code' => $branch->branch_code
                                ];
                            }

                            if (DB::table('journal_detail')->insert($data) === true) {

                                $journald->journal_id = $journalR->id;
                                $journald->coa_id = Request::input('selAct');
                                $journald->credit = Request::input('credit');
                                $journald->description = Request::input('description');
                                $journald->branch_code = $branch->branch_code;
                                $res['jd'] = $journald->save();
                            }
                        }
                }if ($res['jd'] == true) {
                    DB::commit();
                }
                return $res;
            } catch (Exception $error) {
                DB::rollback();
            }
        }
    }


    final function enterBill()
    {
        if (Request::ajax()) {

            if (Request::isMethod('post')) {
                try {

                    DB::beginTransaction();
                    $upload = $this->UploadImageFile('file','images/receipt');
                    //return [!empty($upload['error']), empty($upload['uploaded'])];
                    if(!empty($upload['error'])){

                        $res['error'] = $upload['error'];

                    }else{

                        $branch_code = CompanyBranch::where('id', Auth::user()->branch_id)->first()->branch_code;
                        $journal_id = JournalRequiry::max('id') + 1;
                        $journalR = new JournalRequiry();
                        $journalR->ref_name_id = Request::input('vendor');
                        $journalR->ref_name_type = 1; // Vendor
                        $journalR->trans_type = 13;//Enter Bill
                        $journalR->user_id = Auth::user()->id;
                        $journalR->description = Request::input('description') . ' / ' . Request::input('address');
                        $journalR->entry_date = date("Y-m-d H:i:s", strtotime(Request::input('mdate')));
                        $journalR->due_date = date("Y-m-d H:i:s", strtotime(Request::input('due_date')));
                        (Request::has('refer')) ? $ref_num = Request::input('refer') : $ref_num = str_pad($journal_id, 8, '0', STR_PAD_LEFT);
                        $journalR->invoice_number = $ref_num;

                        if ($journalR->save()) {

                            $inputs = Request::all();
                            for ($i = 0; $i < count($inputs['coa_id']); $i++) {
                                $data[] = [
                                    'coa_id' => (int)$inputs['coa_id'][$i],
                                    'journal_id' => $journalR->id,
                                    'debit' => floatval($inputs['amount'][$i]),
                                    'description' => $inputs['memo'][$i],
                                    'branch_code' => $branch_code,
                                ];
                            }
                            $res['ins'] = DB::table('journal_detail')->insert($data);
                            if ($res['ins'] === true) {

                                $coa = CoaCategory::where('type', 6)->where('id', '=', '4200')->with('detail')->first(); // Accounts Payable-Other USD
                                $journalD = new JournalDetail();
                                $journalD->coa_id = $coa->id;
                                $journalD->journal_id = $journalR->id;
                                $journalD->credit = Request::input('amount_due');
                                $journalD->term = (int)Request::input('term');
                                $journalD->description = Request::input('description');
                                $journalD->branch_code = $branch_code;
                                $res['ins'] = $journalD->save();
                            }
                        }
                    }
                    if ($res['ins'] == true) {
                        DB::commit();
                        return $res;
                    }
                    return $res;
                } catch (Exception $e) {
                    DB::rollback();
                }
            }
            $coa = CoaCategory::where('type', 6)->where('nbc_code', 'like', '6%')->with('detail',['currency_i'=>function($q){$q->select('id','code');}])->get();
            $vendor = Vendor::all();
            return $this->view('accounting.transaction_code.enter_bill', ['vendor' => $vendor, 'coa' => $coa]);
        }
    }


    final function make_deposit()
    {
        if(Request::isMethod('post')) {
            try {

                DB::beginTransaction();
                $inputs = Request::except(['_token']);
                $upload = $this->UploadImageFile('photo', 'images/receipt');

                if(!empty($upload['error'])) {

                    $res['error'] = $upload['error'];

                }else {

                    $branch_code = CompanyBranch::where('id', Auth::user()->branch_id)->first()->branch_code;

                    $journalR = new JournalRequiry();
                    $journalR->ref_name_type = 2; // 1Vendor 2 customer
                    $journalR->ref_name_id = $inputs['customer'];
                    $journalR->trans_type = 14;//Make deposit
                    $journalR->user_id = Auth::user()->id;
                    $journalR->description = $inputs['memo'];
                    $journalR->entry_date = date('Y-m-d H:i:s', strtotime($inputs['mdate']));
                    $journalR->receipt = ($upload['uploaded'])?$upload['uploaded']:'';
                    $journalR->invoice_number = (Request::input('recipsnNum')) ? Request::input('recipsNum') : str_pad(JournalRequiry::max('id') + 1, 8, '0', STR_PAD_LEFT); // invoice_number

                    if($res['jr'] = $journalR->save()) {
                        //debit = credit

                        $jd = new JournalDetail();
                        $jd->coa_id =  (int)$inputs['to'];
                        $jd->journal_id = $journalR->id;
                        $jd->debit = floatval($inputs['amount']);
                        $jd->description = $inputs['memo'];
                        $jd->branch_code = $branch_code;
                        $jd->reference = $inputs['check_num'];

                        if($jd->save()){
                            //debit = credit

                            $data[] = [
                                'coa_id' => (int)$inputs['coa_id'],
                                'journal_id' => $journalR->id,
                                'credit' => floatval($inputs['amount']),
                                'description' => $inputs['Arr_memo'],
                                'reference' => $inputs['check_num'],
                                'branch_code'=>$branch_code
                            ];
                            $res['jd'] = DB::table('journal_detail')->insert($data);
                        }
                    }
                }
                if($res['jd']) {
                    DB::commit();
                }
                return $res;
            }catch(Exception $e) {
                DB::rollback();
            }
        }

        $coa = CoaCategory::where('type', 6)->with('detail',['currency_i'=>function($q){$q->select('id','code');}])->get();
        $depositTo = CoaCategory::where(function($query){

            $query->where('name', 'like', '%Bank%')->orWhere('name','like','%Cash%')->orWhere('name','like','%Account%');
            $query->where('nbc_code', 'like', '1%');

        })->where('type', '6')->with('detail',['currency_i'=>function($q){$q->select('id','code');}])->where('nbc_code', 'like', '1%')->get();

        $customer = AccountCustomer::all();
        return $this->view('accounting.transaction_code.deposit',['coa'=>$coa,'customer'=>$customer,'depositTo'=>$depositTo]);
    }

    final function trans_funds()
    {
        if(Request::isMethod('post') && Request::ajax()) {

            try{

                DB::beginTransaction();

                $upload = $this->UploadImageFile('photo', 'images/receipt');

                if(!empty($upload['error'])) {

                    $res['error'] = $upload['error'];

                }else {

                    $branch_code = CompanyBranch::where('id', Auth::user()->branch_id)->first()->branch_code;
                    $journalR = new JournalRequiry();
                    $journalR->ref_name_id = 0;//Request::input('from'); RefName_id is the id of related table it can be customer,vendor
                    $journalR->ref_name_type = 0; // 1Vendor 2 customer 0 another
                    $journalR->trans_type = 15;//Transfer Funds
                    $journalR->user_id = Auth::user()->id;
                    $journalR->description = Request::input('memo');
                    $journalR->entry_date = date("Y-m-d H:i:s", strtotime(Request::input('mdate')));
                    $journalR->receipt = ($upload['uploaded'])?$upload['uploaded']:'';;
                    $journalR->invoice_number = (Request::input('receiptNum')) ? Request::input('receiptNum') : str_pad(JournalRequiry::max('id') + 1, 8, '0', STR_PAD_LEFT);

                    if($journalR->save()) {

                        $journald = new JournalDetail();
                        $journald->journal_id = $journalR->id;
                        $journald->coa_id = Request::input('to');
                        $journald->debit = Request::input('amount');
                        $journald->description = Request::input('memo');
                        $journald->reference = Request::input('reference');
                        $journald->branch_code = $branch_code;

                        if($res['jdf'] = $journald->save()) {

                            $journald = new JournalDetail();
                            $journald->journal_id = $journalR->id;
                            $journald->coa_id = Request::input('from');
                            $journald->credit = Request::input('amount');
                            $journald->description = Request::input('memo');
                            $journald->reference = Request::input('reference');
                            $journald->branch_code = $branch_code;
                            $res['jd'] = $journald->save();
                        }
                    }
                }
                if($res['jd'] === true) {
                    DB::commit();
                }
                return $res;
                }catch(Exception $e){
                    DB::rollBack();
                }
        }

        $currency = Currency::all();
        $coa = CoaCategory::where('type', 6)->with('detail')->where('nbc_code', 'like', '1%')->with('detail')->get();
        return $this->view('accounting.transaction_code.trans_funds',['coa'=>$coa,'currency'=>$currency]);
    }
}

