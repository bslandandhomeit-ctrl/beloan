<?php namespace App\Http\Controllers;

use App\Models\Accessible;
use App\Models\FeeCharge;
use App\Models\Loan;
use App\Models\LoanCostFee;
use App\Models\LoanPayOff;
use App\Models\LoanWriteOff;
use App\Models\Locale;
use App\Models\LocaleTitle;
use App\Models\UserActivity;
use App\Models\Language;
use App\Models\Audit;
use App\Models\User;
use App\Models\Client;
use App\Models\ClientLoanAccounts;
use App\Models\DrawdownAccounts;
use App\Models\LoanPaymentsDraft;
use App\Models\JournalRequiry;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CommissionWithdrawal;
use App\Models\UnitType;
use App\Models\TransferBalance;

use Image;
use File;
use Response;

class HomeController extends Controller {

    public function __construct()
    {
         parent::__construct();
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        $data = [];
        $data['show'] = false;
        $role_id = Auth::user()->role_id;
        // if( $role_id == 1 || $role_id == 2 ) {
        //     $data['show'] = true;
        //     $year = date('Y');
        //     $income = Loan::select('id','loan_type');
        //     $income->with(['payment'=>function($query) use ($year){
        //         $query->whereRaw("YEAR(repayment_date) =".$year);
        //     }])
        //     ->whereHas('payment',function($query) use($year){
        //         $query->whereRaw("YEAR(repayment_date) =".$year);
        //     });
        //     $income->whereNotIn('status',[1,2]);
        //     $data['incomes'] = $income->get();

        //     $feecharge = FeeCharge::whereRaw("YEAR(charge_date)=".$year)->get(['charge_type','charge_amount','charge_date']);
        //     $data['feecharges'] = $feecharge;

        //     $payoff = LoanPayOff::whereRaw("YEAR(payoff_date)=".$year)->get(['payoff_fee','payoff_date']);
        //     $data['payoff'] = $payoff;

        //     $cost = LoanCostFee::whereRaw("YEAR(cost_date)=".$year)->get(['cost_amount','cost_type','cost_date']);
        //     $data['cost'] = $cost;

        //     $writeoff = LoanWriteOff::whereRaw("YEAR(write_off_date)=".$year)->get(['amount','write_off_date']);
        //     $data['writeoff'] = $writeoff;
        // }

        //chuch select unauthorized
       //find branch code
        $query_arr = array('user_id'=>Auth::user()->id, 'branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id, 'branch_code'=>$branch_code);
        $B0 = new User();
        $B0 = $this->getUserByBranch($B0, 'branch_id', $query_arr)->get();
        $user_arr = array();
        foreach($B0 as $b){
            $user_arr[] = $b->id;
        }

        $data['client_count'] = Client::join('audit', 'tbl_id', '=', 'clients.id')->where('tbl', 'clients')->where('clients.status', 0)->whereIn('audit.user_id', $user_arr)->count();
        $data['client_loan_account_count'] = ClientLoanAccounts::join('audit', 'tbl_id', '=', 'client_loan_accounts.id')->where('tbl', 'client_loan_accounts')->where('client_loan_accounts.status', 0)->whereIn('audit.user_id', $user_arr)->count();
        $data['loan_count'] = Loan::join('audit', 'tbl_id', '=', 'loans.id')->where('loans.status', 1)->where('tbl', 'loans')->whereIn('audit.user_id', $user_arr)->count();
        $data['drawdown_account_count'] = DrawdownAccounts::join('audit', 'tbl_id', '=', 'drawdown_account.id')->where('tbl', 'drawdown_account')->where('drawdown_account.status', 0)->whereIn('audit.user_id', $user_arr)->count();

        $data['repayment_count'] = LoanPaymentsDraft::whereIn('type', [1,2])->where('status', 0)->count();
        $data['payoff_count'] = LoanPaymentsDraft::where('type', 2)->where('status', 0)->count();
        //$data['add_journal_count'] = Audit::where('type', 'add_journal')->count();
        $data['add_journal_count'] = JournalRequiry::where('is_audit', 0)->count();
        $data['charge_count'] = FeeCharge::where('is_audit', 0)->count();
        $data['cost_count'] = LoanCostFee::where('is_audit', 0)->count();
        $data['writeoff_count'] = LoanWriteOff::where('is_audit', 0)->count();

        $data['loan_to_verify_count'] = Loan::where('workflow_status','create')->count();
        $data['loan_send_back_count'] = Loan::where('workflow_status','send_back')->count();
        $data['loan_waiting_approve_count'] = Loan::where('workflow_status','verify')->count();
        $data['schedule_approve_count'] = Loan::where('status','7')->count();
        $data['commission_setting_count'] = UnitType::join('sale_commission_setting', 'sale_commission_setting.unit_type_id', '=', 'unit_types.id')->where('sale_commission_setting.is_active',1)->where('sale_commission_setting.approval','pending')->count();
        $data['commission_setting_send_back_count'] = UnitType::join('sale_commission_setting', 'sale_commission_setting.unit_type_id', '=', 'unit_types.id')->where('sale_commission_setting.is_active',1)->where('sale_commission_setting.approval','send_back')->count();
        $data['commission_withdrawal_count'] = CommissionWithdrawal::where('status','Withdrawal')->count();
        $data['transfer_Balance_count'] = TransferBalance::where('status','Pending')->count();
        
        $data['user']=Auth::user();
 
        return $this->view('home',$data);
    }

    public function no_permission(Request $request)
    {
        if(session('NO_PERMISSION') != null){
            $request->session()->forget('NO_PERMISSION');
            return $this->view('noperm');
        }else{
            return redirect('/');
        }
    }

    public function get_approval_loan()
    {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        $query_arr = array('user_id'=>Auth::user()->id, 'company_branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
        $B0 = new Loan();
        $B0 = $this->getUserByBranch($B0, 'company_branch_id', $query_arr);

        $loans = $B0->with([
                    'approval'=>function($query){
                        $query->select('id','loan_id','approval_date');
                    },
                    'branch'=>function($query){
                        $query->select('id','branch_name');
                    },
                    'client'=>function($query){
                        $query->select('id','client_name');
                    }
                ])->where('status','=',2)
                ->orderBy('id','DESC')->paginate($offset,['id','client_id','company_branch_id','contract_id','start_date']);
        return $this->view('loans.approval_list',['loans'=>$loans,'offset'=>$offset]);
    }

    public function get_unauthorized_loan()
    {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        $query_arr = array('user_id'=>Auth::user()->id, 'company_branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
        $B0 = new Loan();
        // $B0 = $this->getUserByBranch($B0, 'company_branch_id', $query_arr);

        $loans = $B0->with([
            'branch'=>function($query){
                $query->select('id','branch_name');
            },
            'client'=>function($query){
                $query->select('id','client_name');
            }
        ])->whereIn('status',[1,7])
         ->orderBy('id','DESC')->paginate($offset,['id','client_id','company_branch_id','product_id','contract_id','start_date']);
        return $this->view('loans.unauthorized_list',['loans'=>$loans,'offset'=>$offset]);
    }
    public function ip_range()
    {
        return $this->view('welcome');
    }
    public function post_ip_range(Request $request)
    {
        $inputs = $request->except(['_token']);
        if(!empty($inputs)){
            $rule = [
                'ip_addr' => 'required|ip',
                //'end'=> 'required|ip'
            ];
            $v = Validator::make($inputs,$rule);
            if ($v->fails()) {
                return redirect()->back();
            }
            $ipacss = new Accessible;
            $ipacss->ip_start_range = DB::raw('INET_ATON(\''.$inputs['ip_addr'].'\')');
           // $ipacss->ip_end_range = DB::raw('INET_ATON(\''.$inputs['end'].'\')');
            if($ipacss->save()){
                return redirect()->route('ip_list');
            }
        }
        return redirect()->back();
    }
    public function ip_list()
    {
        $ip_list = Accessible::selectRaw('id,INET_NTOA(ip_start_range) as ip_start_range')->get();
        return $this->view('ip_list',compact('ip_list'));
    }
    public function de_list($id)
    {
        $acc = Accessible::find($id);
        if(!empty($acc)){
            $acc->delete();
        }
        return redirect()->back();
    }

    public function user_activity_log(Request $request)
    {
        $start = '';
        $end = '';
        $by = '';
        $activity = UserActivity::select('user_activity.*','users.name')->join('users','users.id','=','user_activity.user_id');
        $user = Auth::user();
        if($user->role_id > 2){
            return redirect()->back();
        }
        if($user->role_id == 2){
            $activity->where('role_id','!=',1);
        }
        if($request->has('by')){
            $by = $request->input('by');
            $activity->where('users.name','LIKE','%'.$by.'%');
        }
        if($request->has('start') && $request->has('end')){
            $start = $request->input('start');
            $end = $request->input('end');
            $activity->whereBetween('activity_date',[date('Y-m-d 00:00:00',strtotime($start)),date('Y-m-d 23:59:59',strtotime($end))]);
        }elseif($request->has('start') && !$request->has('end')){
            $start = $request->input('start');
            $activity->where('activity_date','>=',date('Y-m-d 00:00:00',strtotime($start)));
        }elseif(!$request->has('start') && $request->has('end')){
            $end = $request->input('end');
            $activity->where('activity_date','<=',date('Y-m-d 23:59:59',strtotime($end)));
        }
        $activity = $activity->paginate(30);
        return $this->view('user_activity',compact('activity','start','end','by'));
    }

    public function Locale($locale)
    {
        session(['locale'=>$locale]);
        $user = Auth::user();
        $user->locale = $locale;
        $user->save();
        return redirect()->back();
    }
    public function getLanguage(){
        $lang = Language::select('id','english','khmer')->get();
        return $this->view('language',['language'=>$lang]);
    }

    public function getLocale()
    {
        return $this->view('locales.locale');
    }

    public function postLocale(Request $request)
    {
        if($request->has('locale') && $request->has('short_locale')){
            $locale = $request->input('locale');
            $short_locale = $request->input('short_locale');
            if(strlen(trim($short_locale)) != 2){
                return redirect()->back();
            }
            $l = new Locale();
            $l->locale = $locale;
            $l->short_locale = $short_locale;

            if($request->hasFile('icon')){
                $file = $request->file('icon');
                if($file->isValid()) {
                    list($w,$h) = getimagesize($file);
                    if($w >= 60){
                        $h = ($h * 60)/$w;
                        $w = 60;
                        if($h > $w){
                            $w = ($w * 60)/$h;
                            $h = 60;
                        }
                    }elseif($h >= 60){
                        $w = ($w * 60)/$h;
                        $h = 60;
                        if($w > $h){
                            $h = ($h * 60)/$w;
                            $w = 60;
                        }
                    }
                    $image = Image::make( $file )->resize( $w,$h);
                    $icon = uniqid( date( 'dmY' ) ) . '.jpg';
                    if($image->save( public_path( 'data/locales' ) . '/' . $icon )){
                        $l->icon = $icon;
                    }
                }
            }
            if($l->save()){
                return redirect()->route('all_locale');
            }
        }
        return redirect()->back();
    }

    public function getTran()
    {
        $locale = Locale::where('id', '>', 0)->get(['id', 'locale']);
        return $this->view('locales.tran',['locale' => $locale]);
    }

    public function postTran(Request $request)
    {
        if($request->has('locale') && $request->has('key')){
            $locale = $request->input('locale');
            $key = $request->input('key');
            $text = $request->input('text');
            $group = $request->input('group');
            $lo = new LocaleTitle();
            $lo->locale_id = $locale;
            $lo->key = $key;
            $lo->title = $text;
            $lo->group = $group;
            if($lo->save()){
                return redirect()->route('all_locale');
            }
        }
        return redirect()->back();
    }

    public function getLocaleList()
    {
        $all_locale = LocaleTitle::get(['id','locale_id','title','key']);
        return $this->view('locales.locale_list',['all_locale'=>$all_locale]);
    }

    public function updateLocale(Request $request)
    {
        $locale = $request->input('locale');
        $title_code = '';
        $route_name = '';
        $success = false;
        $save_id = [];
        if(is_array($locale)){
            foreach($locale as $loc){
                if(is_array($loc)){
                    $locTitle = LocaleTitle::find($loc['id']);
                    if(!empty($locTitle)){
                        $locTitle->title = $loc['title'];
                        $title_code = $locTitle->key;
                        $route_name = $locTitle->group;
                        if($locTitle->save()){
                            $success = true;
                        }else{
                            $success = false;
                        }
                        $save_id[] = $loc['id'];
                    }else{
                        if($title_code != ''  && $route_name != ''){
                            $locTitle = new LocaleTitle();
                            $locTitle->locale_id = $loc['locale_id'];
                            $locTitle->title = $loc['title'];
                            $locTitle->key = $title_code;
                            $locTitle->group = $route_name;
                            if($locTitle->save()){
                                $success = true;
                                $save_id[] = $locTitle->id;
                            }else{
                                $success = false;
                                $save_id[] = "";
                            }
                        }else{
                            $success = false;
                        }
                    }
                }
            }
        }
        return ['success'=>$success,'saveId'=>$save_id];
    }

    public function get_backup_db()
    {
        $files = File::files(public_path().'/backups');
        $back_up = [];
        foreach($files as $f){
            $file_name = File::name($f);
            $back_up[] = [
                //substr($file_name,10),
                $file_name.'.sql'
            ];
        }
        arsort($back_up);
        return $this->view('backup.bkp',compact('back_up'));
    }

    public function download(Request $request){
        if($request->has('b')){
            $file_name = $request->input('b');
            $file = public_path().'/backups/'.$file_name;
            if(file_exists($file)){
                $headers = array(
                    'Content-Type: application/octet-stream'
                );
                return Response::download($file, substr($file_name,3), $headers);
            }
        }
        return redirect()->back();
    }
}
