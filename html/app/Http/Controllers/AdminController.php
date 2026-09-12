<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @author theary
 */
namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Teller;
use App\Models\TillTransaction;
use App\Models\SetCurrency;
use App\Models\CurrencyExchangeRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Request;
//use App\Http\Requests\Request;
use Auth;
use Image;
use App\Models\CoaCategory;
use App\Models\CompanyBranch;
use App\Models\JournalRequiry;
use App\Models\JournalDetail;
use App\Models\TransactionsRequiry;
use App\Models\Loan;
use App\Models\AssetDepreRecord;
use App\Models\Asset;

class AdminController extends Controller{
    //put your code here
    public function  __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }
    
    public function getAdmin(){
        $Teller = new Teller;
        $status = $Teller->get();
        
        $loans = Loan::whereIn('status', [3,8])->get();
        $foo = true;
        foreach($loans as $l){
        	$tran = TransactionsRequiry::where('trans_type', 'Auto Accrued Interest')
        			->where('loan_id', $l->id)
        			->where('trans_date', date('Y-m-d'))
        			->first(); 
        	if($l->id!=$tran->loan_id) $foo = false;
        }
        return $this->view('admin.admin', ['status' => $status, 'foo'=>$foo]);
    }
    
    public function enableTill(){
        Teller::where('status','=',0)->update(['status' => 1,'close_time' => date("Y-m-d H:i:s"),'close_balance' => 'balance']);
        return redirect()->back();
    }
    
    public function setCurrencyRate(){
    	require_once public_path("simple_html_dom.php");
    	$html = file_get_html(NBC_EXCHANGE_URL);
        $data_cur = [];
        if($html){
        	foreach($html->find('div.content-text table') as $tab){
        		$i=0;
        		foreach($tab->find('tr') as $tr){
        			$i++;
        			if($i==3){
        				foreach($tr->find('font') as $f){
        					$data_cur['USD'] = str_replace(',', '', trim($f->plaintext));
        				}
        			}
        		}
        		break;
        	}
        	
        	$i=0;
        	foreach($html->find('div.content-text table') as $tab){
        		$i++;
        		if($i==1) continue;
        		if($i > 2) break;
        		
        		foreach($tab->find('tr') as $tr){
        			$j = 0;
        			foreach($tr->find('td') as $td){
        				$j++;
        				if($j==2) $ex = explode('/', $td->plaintext);
        				if($j==6){
        					//JPY 100, VND 1000
        					$val = str_replace(',', '', trim($td->plaintext));
        					if($ex[0]=='JPY') $val = $val / 100;
        					if($ex[0]=='VND') $val = $val / 1000;
        					$data_cur[$ex[0]] = $val;
        				}
        			}
        		}
        	}
        }
    	
    	$data_cur['KHR'] = 1;
    	
    	$data['currency_arr'] = $data_cur;
    	$data['currency'] = Currency::select('id', 'name', 'code')->where('type', 'Sub')->get();
        return $this->view('admin.setCurrency',$data);
    }
    
    public function postSetCurrencyRate(){
    	$data = Request::all();
        $rules = [
            'mid_rate' => 'required'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to register new staff']);
        } else {
            $s = new SetCurrency;
            $s->currency_id = Request::input('cur');
            $s->ask_rate = Request::input('ask_rate');
            $s->bid_rate = Request::input('bid_rate');
            $s->mid_rate = Request::input('mid_rate');
            $s->nbc_rate = Request::input('nbc_rate');
            $s->created_at = date('Y-m-d H:i:s');
            $s->save();
            return redirect()->route('list_currency_rate');
        }
    }
    
    function getLatestRate(){
    	$cur = Request::input('cur');
    	$currency = SetCurrency::select('id', 'ask_rate', 'bid_rate', 'mid_rate', 'nbc_rate')->where('currency_id', $cur)->orderBy('id','desc')->first();
    	$data = array('ask_rate'=>$currency->ask_rate, 'bid_rate'=>$currency->bid_rate, 'mid_rate'=>$currency->mid_rate, 'nbc_rate'=>$currency->nbc_rate);
        echo json_encode($data); exit();
    }
    
    public function listCurrencyRate(){
    	$data['cur_rate'] = SetCurrency::all();
        //dd($data);
        return $this->view('admin.list_rate',$data);
    }
    
    public function getAddCurrency(){
        return $this->view('admin.addCurrency',$data);
    }
    
    public function postAddCurrency()
    {
        $data = Request::except(['_token']);
        $rules = [ 'currency_name' => 'required',
            'code' => 'required'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $cur = new Currency;
            $cur->name = Request::input('currency_name');
            $cur->code = Request::input('code');
            $cur->symbol = Request::input('symbol');
            $cur->type = Request::input('type');
            $cur->user_id = Auth::user()->id;
            $cur->status = 1;
            if ($cur->save()) {
                $this->userActivity(Auth::user()->id, $cur->id, 1, 'Add currency', Request::fullUrl());
                return redirect()->route('list_currencie');
            }
        }
        return redirect()->back();
    }
    
    public function edit_currency($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            $cur = Currency::find($id);
            if(!empty($cur))
                return $this->view('admin.edit_currency',['cur'=>$cur]);
        }
        return redirect()->back();
    }
    
    public function post_edit_currency($id = 0)
    {
        if(is_numeric($id) && $id > 0){
            $cur = Currency::find($id);
            if(!empty($cur)){
                $inputs =  Request::except(['_token']);
                if(!empty($inputs) && is_array($inputs)){
                    $cur->name = Request::input('currency_name');
                    $cur->code = Request::input('code');
                    $cur->symbol = Request::input('symbol');
                    $cur->type = Request::input('type');
                    $cur->user_id = Auth::user()->id;
                    if($cur->save()){
                        $this->userActivity(Auth::user()->id,$id,1,'Update currency', Request::fullUrl());
                        return redirect()->route('list_currencie');
                    }else{
                        Session::flash('message', 'Save a company branch failed');
                        return redirect()->back()->with('error',true);
                    }
                }
            }
        }
        return redirect()->back();
        
    }
    
    public function listCurrency()
    {
        $cur = Currency::all();
        return $this->view('admin.list',['cur'=>$cur]);
    }
    
    public function disableCurrency($id){
        $cur = Currency::find($id);
        $cur->status = 0;
        $cur->save();
        $this->userActivity(Auth::user()->id,$id,1,'Disable currency', Request::fullUrl());
        return redirect()->back();
    }
    
    public function enableCurrency($id){
        $cur = Currency::find($id);
        $cur->status = 1;
        $cur->save();
        $this->userActivity(Auth::user()->id,$id,1,'Enable currency', Request::fullUrl());
        return redirect()->back();
    }
    
    public function getCurrency(){
        $data['currency'] = Currency::select('id', 'name','symbol')->get();
        $data['tellers'] = Teller::select('till_account.account_name as account_name', 'till_account.balance as balance', 'users.id as uid', 'users.name as name',
                           'company_branch.branch_name as branch_name', 'company_branch.id as bid', 'currency_id', 'till_account.status')
                            ->join('users', 'users.id', '=', 'till_account.assign_user_id')
                            ->join('company_branch', 'company_branch.id', '=', 'till_account.branch_id')
                            ->where('assign_user_id', '=', Auth::user()->id)
                            ->get();
        return $this->view('admin.currency',$data);
    }
    
    public function postCurrencyExchange(){
        $data = Request::except(['_token']);
        $rules = [ 'amount' => 'required'];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $entry_date = date('Y-m-d H:i:s');
            $user_id = Auth::user()->id;
            $branch_id = Auth::user()->branch_id;
            $cur = new CurrencyExchangeRecord;
            $cur->from_currency = Request::input('cur_from');
            $cur->to_currency = Request::input('cur_to');
            $cur->amount = Request::input('amount');
            $cur->exchange_amount = str_replace(',', '', Request::input('exchange_amount'));
            $cur->gain_loss = Request::input('gain_lose');
            $cur->user_id = $user_id;
            //Till Record (Chamroeun)
            $from_account = Teller::where('assign_user_id', $user_id)->with('user')->where('currency_id',Request::input('cur_from'))->first();
            $to_account = Teller::where('assign_user_id', $user_id)->with('user')->where('currency_id',Request::input('cur_to'))->first();
            if(is_null($from_account) || is_null($to_account)){
                Session::flash('message', 'You has no till accounts to do foreign exchange.');
                return redirect()->back();
            }
            if($from_account->status == 1 || $to_account->status == 1){
                Session::flash('message', 'Till account is closed.');
                return redirect()->back();
            }
            $from_account->balance += floatval(Request::input('amount'));
            $to_account->balance -= floatval($cur->exchange_amount);
            if($from_account->balance < 0 || $to_account->balance < 0){
                Session::flash('message', "Till account doesn't have enough balance.");
                return redirect()->back();
            }
            if ($cur->save()) {
                $this->userActivity(Auth::user()->id, $cur->id, 1, 'Add CurrencyExchangeRecord', Request::fullUrl());
                // Till transaction update
                if($from_account->save() && $to_account->save()){
                    // from till transaction
                    $from_till_trans = new TillTransaction();
                    $from_till_trans->till_account_id = $from_account->id;
                    $from_till_trans->from_account = $from_account->user->name . ' ( ' . $from_account->account_no . ' / ' . $from_account->account_name . ' )';
                    $from_till_trans->to_account = $to_account->user->name . ' ( ' . $to_account->account_no . ' / ' . $to_account->account_name . ' )';
                    $from_till_trans->till_user_id = $user_id;
                    $from_till_trans->branch_id = $branch_id;
                    $from_till_trans->tran_currency_id = Request::input('cur_from');
                    $from_till_trans->operate_by = $user_id;
                    $from_till_trans->tranx_time = $entry_date;
                    $from_till_trans->type = 'Currency Exchange';
                    $from_till_trans->cash_in = floatval(Request::input('amount'));
                    $from_till_trans->cash_out = 0;
                    $from_till_trans->balance = $from_account->balance;
                    $from_till_trans->description = 'Currency Exchange';
                    $from_till_trans->save();
                    $this->userActivity(Auth::user()->id, $from_till_trans->id, 0, 'TillTransaction', Request::fullUrl());

                    // to till transaction
                    $to_till_trans = new TillTransaction();
                    $to_till_trans->till_account_id = $to_account->id;
                    $to_till_trans->from_account = $to_account->user->name . ' ( ' . $to_account->account_no . ' / ' . $to_account->account_name . ' )';
                    $to_till_trans->to_account = $from_account->user->name . ' ( ' . $from_account->account_no . ' / ' . $from_account->account_name . ' )';
                    $to_till_trans->till_user_id = $user_id;
                    $to_till_trans->branch_id = $branch_id;
                    $to_till_trans->tran_currency_id = Request::input('cur_to');
                    $to_till_trans->operate_by = $user_id;
                    $to_till_trans->tranx_time = $entry_date;
                    $to_till_trans->type = 'Currency Exchange';
                    $to_till_trans->cash_in = 0;
                    $to_till_trans->cash_out = floatval($cur->exchange_amount);
                    $to_till_trans->balance = $to_account->balance;
                    $to_till_trans->description = 'Currency Exchange';
                    $to_till_trans->save();
                    $this->userActivity(Auth::user()->id, $to_till_trans->id, 0, 'TillTransaction', Request::fullUrl());
                }
            	//journal\
            	$branch_code = CompanyBranch::find($branch_id)->branch_code;
            	$invoice_number = '';
            	$contract_id = 0;
            	
            	for($m=0; $m < count(Request::input('debit')); $m++){
            		$description = Request::input('description')[$m];
            		$transaction_id = 0;
            	
            		$journal = new JournalRequiry;
            		$journal->tran_id = $transaction_id;
            		$journal->entry_date = $entry_date;
            		$journal->invoice_number = $invoice_number;
            		$journal->description = $description;
            		$journal->user_id = $user_id;
            	
            		if ($journal->save()){
                        $this->userActivity(Auth::user()->id, $journal->id, 0, 'Add JournalRequiry', Request::fullUrl());

            			for ($k = 0; $k < 2; $k++) { // 0 = debit, 1 = credit
            				$parent_debit = Request::input('parent_debit')[$m];
            				$parent_credit = Request::input('parent_credit')[$m];
            				$debit = Request::input('debit')[$m];
            				$credit = Request::input('credit')[$m];
            				$d_description = Request::input('d_description')[$m];
            				$c_description = Request::input('c_description')[$m];
            	
            				$jd = new JournalDetail;
            				$jd->journal_id = $journal->id;
            				$jd->coa_id = $k==0?$parent_debit:$parent_credit;
            				$jd->reference = $contract_id;
            				$jd->branch_code = $branch_code;
            	
            				$prev_bl = array_fill(0, 2, 0.0);
            				$prev_row = JournalDetail::select('b_debit', 'b_credit')
            				->where('coa_id', $jd->coa_id)
            				->orderBy('id', 'desc')
            				->first();
            				if (!empty($prev_row)) {
            					$prev_bl[0] = $prev_row->b_debit;
            					$prev_bl[1] = $prev_row->b_credit;
            				}
            				$jd->p_debit = $prev_bl[0];
            				$jd->p_credit = $prev_bl[1];
            				$jd->debit = $k==0?$debit:0;
            				$jd->credit = $k==1?$credit:0;
            				$jd->b_debit = floatval($prev_bl[0]) + floatval($jd->debit);
            				$jd->b_credit = floatval($prev_bl[1]) + floatval($jd->credit);
            				$jd->description = $k==0?$d_description:$c_description;
            				$jd->save();
                            $this->userActivity(Auth::user()->id, $jd->id, 0, 'Add JournalDetail', Request::fullUrl());
            			}
            		}
            	}  //END $m
            	
                return redirect()->route('list_currency_exchange');
            }
        }
        return redirect()->back();
    }
    
    
    public function getExchangeRate(){
        $cur_from = Request::input('cur_from');
        $cur_to = Request::input('cur_to');
        $amount = Request::input('amount');
        $currency_from = SetCurrency::select('id', 'ask_rate', 'bid_rate', 'mid_rate')->where('currency_id', $cur_from)->orderBy('id','desc')->first();
        $currency_to = SetCurrency::select('id', 'ask_rate', 'bid_rate', 'mid_rate')->where('currency_id', $cur_to)->orderBy('id','desc')->first();
        $gl_dolla = SetCurrency::select('id', 'ask_rate', 'bid_rate', 'mid_rate')->where('currency_id', $cur_to)->orderBy('id','desc')->first();
        
        if($currency_from->ask_rate){
            if($currency_from->ask_rate >=1){
               $cf_usd = $amount/$currency_from->bid_rate;
            }else{
               $cf_usd = $amount*$currency_from->ask_rate;
            }
        }else{
            $cf_usd = $amount;
        }
        
        if($currency_to->ask_rate){
            $ct_usd = $currency_to->ask_rate >=1?$cf_usd*$currency_to->ask_rate:$cf_usd/$currency_to->ask_rate;
        }else{
            $ct_usd = $cf_usd;
        }
        
        ///mid rate
        if($currency_from->mid_rate){
            if($currency_from->mid_rate >=1){
               $ca_usd = $amount/$currency_from->mid_rate;
            }else{
               $ca_usd = $amount*$currency_from->mid_rate;
            }
        }else{
            $ca_usd = $amount;
        }
        
        if($currency_to->mid_rate){
            $ct = $currency_to->mid_rate >=1?$ca_usd*$currency_to->mid_rate:$ca_usd/$currency_to->mid_rate;
        }else{
            $ct = $ca_usd;
        }
        $gl = $ct - $ct_usd;
        
        if($gl_dolla->ask_rate){
            if($gl_dolla->ask_rate >=1){
               $c_usd = $gl/$gl_dolla->bid_rate;
            }else{
               $c_usd = $gl*$gl_dolla->ask_rate;
            }
        }else{
            $c_usd = $gl;
        }
        
    	$data = array('exchange_amount'=> number_format($ct_usd, 2),'gain_lose'=>$gl, 'dolla'=>number_format($c_usd, 2));
        echo json_encode($data); exit();
    }
    
    public function listCurrencyExchange(){
        $data['cur_re'] = CurrencyExchangeRecord::all();
        //dd($data);
        return $this->view('admin.list_currency',$data);
    }
    
    public function getExchangeRateJD(){
    	$amount = Request::input('amount');
    	$cur_from = Request::input('cur_from');
    	$nbc_rate = SetCurrency::where('currency_id', $cur_from)->orderBy('id', 'DESC')->first()->nbc_rate;
        $nbc_rate = $nbc_rate==0?1:$nbc_rate;
    	
    	//get default select_type 1
    	$coa_1 = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', $cur_from);
    	$coa_1 = $coa_1->where('name','Cash in Vault');
    	$coa_1 = $coa_1->first();
    	 
    	$coa_2 = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', $cur_from);
    	$coa_2 = $coa_2->where('name','Foreign Exchange Position Account');
    	$coa_2 = $coa_2->first();
    	
    	$coa_3 = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', 2);
    	$coa_3 = $coa_3->where('name','Equivalence Foreign Exchange Position Acc');
    	$coa_3 = $coa_3->first();
    	
    	$coa_4 = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', 2);
    	$coa_4 = $coa_4->where('name','Cash in Vault');
    	$coa_4 = $coa_4->first();
    	
    	$data['coa_1'] = $coa_1;
    	$data['coa_2'] = $coa_2;
    	$data['coa_3'] = $coa_3;
    	$data['coa_4'] = $coa_4;
    	
    	$data['amount_1'] = $amount;
    	$data['amount_2'] = $amount;
    	$to_usd = $amount / $nbc_rate;
    	$data['amount_3'] = number_format($to_usd, 2, ".","");
    	$data['amount_4'] = number_format($to_usd, 2, ".","");
    	
    	$branch_code = CompanyBranch::find(Auth::user()->branch_id)->branch_code;
    	$data['branch_code'] = $branch_code;
    	
    	return $this->view('admin._currency',$data);
    }

    function asset_depreciation_verify(){
        $last_depre = AssetDepreRecord::orderBy('id', 'DESC')->first();
        if($last_depre){
            $last_day = date('Y-m-d', strtotime($last_depre->created_at));
        }else{
            $last_day = date('Y-m-d');
        }

        $data['last_day'] = $last_day;
        $data['res'] = Asset::get();

        $data['asset_locations'] = config('static_data.asset_locations');
        $data['asset_categories'] = config('static_data.asset_categories');
        $data['asset_classifications'] = config('static_data.asset_classifications');
        $data['currency'] = config('static_data.currency');
        $branches = CompanyBranch::get();
        foreach($branches as $b){
            $data['branches'][$b->id] = $b->branch_name;
        }
        dd($data);
        return $this->view('admin.assets',$data);
    }
    
    function asset_depreciation_execute(){


            if (Request::has('flag')) {
                $flag = Request::input('flag');
            }

            $data['report_title'] = "Fixed Assets's Detail";
            $data['dpStart'] = $dpStart = $data['as_at'] = Request::input('dpStart')?Request::input('dpStart'):date('Y-m-d');

            $data['asset_locations'] = config('static_data.asset_locations');
            $data['asset_categories'] = config('static_data.asset_categories');
            $data['asset_classifications'] = config('static_data.asset_classifications');
            $data['currency'] = config('static_data.currency');

            $asset['LND'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LND')->where('status',1)->get();
            $asset['BLD'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'BLD')->where('status',1)->get();
            $asset['LHI'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LHI')->where('status',1)->get();

            $asset['FF'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LIKE', '1%')->where('status',1)->get();
            $asset['EQ'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LIKE', '2%')->where('status',1)->get();
            $asset['CE'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LIKE', '3%')->where('status',1)->get();
            $asset['MO'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LIKE', '4%')->where('status',1)->get();
            $asset['CS'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LIKE', '5%')->where('status',1)->get();
            $asset['OT'] = Asset::with('depre_record')->with('asset_record')->with('depre_journals')->with('depre_gl_coa')->where('category', 'LIKE', '9%')->where('status',1)->get();

            $data['assets'] = $asset;
            $data['last_record'] = AssetDepreRecord::where('created_at', '<', $data['dpStart'])->first()->created_at;
            $data['flag'] = $flag;



        if($flag == '2'){
            $inputs = Request::except(['_token']);
            foreach(json_decode($inputs["depre_arr"]) as $key=>$dpr){
                if($dpr->depre_amount <= 0) continue;
                $journal_arr = [];
                $up_asset = Asset::find($key);
                //journal record : debit:exp, credit:depre
                $dscp = "Accumulate depreciation for ". $up_asset->asset_name . ' '.$up_asset->tag_num;
                array_push($journal_arr,[$up_asset->exp_gl_id, $dpr->depre_amount, $dscp,
                                         $up_asset->depre_gl_id, $dpr->depre_amount, $dscp,$dscp]);
                record_journal(null, date('Y-m-d H:i:s'), "Accumulated Fixed Asset Depreciation", null,  $journal_arr, $up_asset->branch, Auth::user()->id, null);
                //Depre record
                $depre_rec = new AssetDepreRecord();
                $depre_rec->fa_id = intval($key);
                $depre_rec->intraday_rate = $dpr->intraday_rate;
                $depre_rec->date_num = $dpr->date_num;
                $depre_rec->depre_amount = $dpr->depre_amount;
                $depre_rec->remark = $dscp;
                $depre_rec->created_by = Auth::user()->id;
                $depre_rec->save();
            }
            $data['flag'] = 0;
            return redirect()->back();
        }
        return $this->view('assets.asset_depreciation_exe', $data);
    }
    
}
