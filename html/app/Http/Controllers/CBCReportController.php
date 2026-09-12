<?php  namespace App\Http\Controllers;
use \RecursiveIteratorIterator;
use \RecursiveArrayIterator;
use Request;
use Auth;
use Image;
use App\Models\Loan;
use App\Models\Currency;
use DB;
use Illuminate\Support\Facades\Session;
use Artisan;
use Response;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\CharsetConverter;

class CBCReportController extends Controller
{

    public function  __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }
    public function CBC_index() {

        return $this->view('reports.cbc.index');
    }

    public function CBC_Report ()
    {
		/*
       // if(!\App::environment('local')) {
		//     require '../vendor/league/csv/src/autoload.php';
        // }else{
        //     require '../vendor/league/csv/autoload.php';
        // }

        $encoder = (new CharsetConverter())
                        ->inputEncoding('utf-8')
                        ->outputEncoding('iso-8859-15');

    $CBC = new \App\Models\Cbc\CBC();
        $data =  $CBC->CBCData_new();
	//$s = new \SplTempFileObject();
        $csv =\League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv->addFormatter($encoder);
		$i=1;*/
		
		if(!\App::environment('local')) {
			require '../vendor/league/csv/src/autoload.php';
        }else{
			require '../vendor/league/csv/autoload.php';
	}

    $CBC = new \App\Models\Cbc\CBC();
        $data =  $CBC->CBCData_new();
	//$s = new \SplTempFileObject();
        $csv =\League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $i=1;
		
        foreach ($data as $k=>$v) {
			//if($v["id"]==61)dd($v);
          $last_arr = null;
          //$past_last_arr = null;
		  if(!empty($v)) {
               $dd[] = $v;
              if($v['status'] == 0) continue;
			
	      if(date_dif(Request::input('as_of_date'), $v['loan']['disburse_date'], 1, false) > 0) continue;	      
              $last_trans = $v['loan']['transaction'][count($v['loan']['transaction'])-1];
		$d_dif[] = date_dif(Request::input('as_of_date'),"2018-02-21", 2, false);
              //if($v['status'] >= 6 && date_dif(Request::input('as_of_date'),$last_trans['trans_date'], 2, false) != 0) continue;
        //if($v['loan']['id'] == 61) {var_dump(doubleval(substr(str_replace("-","",Request::input('as_of_date')),0,6))); var_dump(doubleval(substr(str_replace("-","",$last_trans['trans_date']),0,7)));var_dump(str_replace("-","",Request::input('as_of_date'))); var_dump( date("Y-m", strtotime($last_trans['trans_date'])));var_dump(floatval(date("Y-m", strtotime(Request::input('as_of_date'))))); dd(floatval(date("Y-m", strtotime($last_trans['trans_date']))));}
//if($v['loan']['contract_id'] == "TGL2016/008") dd((doubleval(substr(str_replace("-","",Request::input('as_of_date')),0,6)) - doubleval(substr(str_replace("-","",$last_trans['trans_date']),0,6))));
		if($v['status'] > 6 && (doubleval(substr(str_replace("-","",Request::input('as_of_date')),0,6)) - doubleval(substr(str_replace("-","",$last_trans['trans_date']),0,6)))>0) continue;
		$result[] = $this->getClientData($v['client'], $v, $i++);
		$last_arr = $result[count($result)-1]["last"];
		//if($past_last_arr!=$last_arr)
			$sub_client_id = json_decode(preg_replace('/\s+/', ' ', $v['sub_client_id']));
			$guarantor = json_decode(preg_replace('/\s+/', ' ', $v['guarantor']));
			//$result[] = $this->getClientData($v['client'], $v, $i++,$result);
			$count[]= $k;
			$insertLastIndexToQurantorAndSubClient = ['last'=>$result[count($count)-1]['last']]; // find the last index of current index of array and fetch on last value from that current array

				if(!empty($sub_client_id)) {
				  foreach($sub_client_id as $sub_id){
					$clientCLone = $CBC->CBCData($sub_id);
					foreach($clientCLone as $ClientCloneData) {
					  $result[] = $this->getClientData($ClientCloneData, $v, $i++, $last_arr);
	//                            $result[] = array_merge($this->getClientData($ClientCloneData, $v, $i++),$insertLastIndexToQurantorAndSubClient,$last_arr);
					}
				  }
				}
				if(!empty($guarantor) && is_array($guarantor)) { //if chis client loan account has many guarantors
				  foreach($guarantor as $gua){
					$CloneGuarantor = $CBC->CBCData($gua);
					foreach($CloneGuarantor as $key=>$CloneGuarantors) {
					  $result[] = $this->getClientData($CloneGuarantors, $v, $i++, $last_arr, 'G');
					  //  $result[] = array_merge($this->getClientData($CloneGuarantors, $v, $i++), $insertLastIndexToQurantorAndSubClient,$last_arr);
						// merge the last index to quarantor
					}
				  }
				}
            }
        }
        if(Request::has('header') && Request::input('header')) {
            $i= 1;
            $vals = [];
            foreach($result as $keys=>$vals){
//                $val = $value['client'];
                if(count($vals) > 0 && count($vals['employer'])>0) {
                    foreach($vals as $subKeys=>$val){
                        foreach($val as $s=>$v){
                            $threeChar = substr($subKeys, 0, 3);
                            if($i++ <= 181){
                                $header[]  = strtoupper($threeChar).'_'.$s;
                            }
                        }
                    }
                }
            }
            $UniqueHeader = array_unique($header);
            $csv->insertOne($UniqueHeader);
        }

        foreach($result as $keys=>$item) {
           if(empty($item["last"])) continue;
            $collectKeyNval = collect($item)->collapse()->all(); // We can validate all the madatory fields which required from CBC then send those fields to users.(Next version)
            $export= array_values($collectKeyNval);
            $csv->insertOne($export);
        }
        $name = 'cbc_report-'.date("Ymd");
        return $csv->output($name.'.csv');
    }

    private function getClientData($v, $clientLoanData, $k, $last_arr = null, $type = 'P') {
        $os_balance = 0.0;
        $a = 0;
        $result = [];
        if($k>$a){
            $a = $k-1;
        }
        //if(!empty($v['client_loan'][$a]['loan_ref'])) {
            foreach($v as $key=>$data) {
                //if($key == 'identification') {

                    $result['rows_id']= ['id'=> $k];// 1
                    $result['identification']  = $this->getIdentificationValues($v['identification']); //10
                //}
                //if($key == 'general') {
                    foreach($v['general'] as $item) {
                        $result['general']  =  [
                            'date_of_birth'                     =>$this->dmYFormat($item['date_of_birth']),               //11 Man, 8,
                            'gen_family_name'                   =>$item['family_name'],                 //12 note, 70 ,Mandatory if in same language if First Name entered. Mandatory if Unformatted Name not entered. Both
                            'gen_first_name'                    =>$item['first_name'],                  //13 note, 70,  -------------||------
                            'gen_second_name'                   =>$item['second_name'],                 //14 opt, 30
                            'gen_third_name'                    =>$item['third_name'],                  //15 opt, 30
                            'gen_unformatted_name'              =>$item['unformatted_name'],            //16 opt, 70, (Mandatory if Family and First Name not entered.)
                            'gen_mother_name_unfomatted'        =>$item['mother_name_unfomatted'],      //17 opt,  180,---------||------
                            'Family Name khmer '                =>$item['family_name_kh'],              // 18
                            'gen_first_name_kh'                 =>$item['first_name_kh'],               //19 Man, 79, same english name
                            'gen_second_name_kh'                =>$item['second_name_kh'],              //20 opt, 30
                            'gen_third_name_kh'                 =>$item['third_name_kh'],               //21 opt, 30
                            'gen_unformatted_name_kh'           =>$item['unformatted_name_kh'],         //22 opt, 180
                            'gen_mother_name_unfomatted_kh'     =>$item['mother_name_unfomatted_kh'],   //23 opt, 180
                            'gen_gender'                        =>$item['gender'],                      //24 Man, 1
                            'gen_marital_status'                =>$item['marital_status'],              //25 Man, 1
                            'gen_national_code'                 =>$item['national_code'],               //26 Man, 3
                            //'gen_taxpayer_reg_no'               =>$item['taxpayer_reg_no'],             //26 opt, 9
                            'gen_taxpayer_reg_no'               =>"",             //26 opt, 9
                            'gen_applicant_type'                =>($type!='P')? $type : $item['applicant_type'],              //28 Man, 1 merl this array to $_address array.
                        ];
                    }
                //}
                //if($key == 'address') {
                    $result['address']  = $this->getAddressValues($v['address']);
                //}
//                if($key == 'contact') {
                    $result['contact']  = $this->getContactValues($v['contact']);
//                }
//                if($key == 'employer') {
                  //if($v['id'] == 3) dd($data);
                    $result['employer']  = $this->getEmployerValues($v['employer']);
                    //dd($result['employer'] );
//                }
            }
	    // find correct os
            $total_sp = $total_ap = 0;
            foreach($clientLoanData['loan']['schedule'] as $sch){
              $total_sp += $sch['principal'];
        }
	    foreach($clientLoanData['loan']['transaction'] as $tr){
            if(date_dif($tr['trans_date'], Request::input('as_of_date'), 1, false) >= 0){
                    $total_ap += $tr['principal'];
                }
            }
            //$os_balance = $clientLoanData['balance'];
	    $os_balance = round($total_sp - $total_ap,2);
        foreach($clientLoanData as $key=>$data){
              if( $key == 'loan') {
                  foreach($data as $d=>$loanData) {
                    if($os_balance == 0 && date('Y-m', strtotime($data['transaction'][count($data['transaction']) - 1]['trans_date'])) - date('Y-m', strtotime(Request::input('as_of_date'))) != 0){
                      break;
                    }
                      if($last_arr == null){
                        $result['last'] = $this->getLoanData($v, $clientLoanData['loan'], $clientLoanData, $os_balance);
                      }else{
                        $result['last'] = $last_arr;
                      }
                  }
              }
            }
//if($clientLoanData['loan']['id']==6) dd($result);
//        }
        return $result;
    }

    private function getLoanData($v, $loanData, $clientData, $os_balance) {
        $arreas_arr = [];
	$product_status_code = 'N';
        $payment_status_code = 'N';
        // Chamroeun : getTotalPenalty for pass_due and get $payment_status_code
        $loan = Loan::where('id',$loanData['id'])->first();
	$ex_loan = Loan::where('loan_account_id', $loan->loan_account_id)->where('id', '!=', $loan->id)->where('status', 6)->first();
        if(is_null($ex_loan)){
	   $loan_amount = $loan->loan_amount;
	   $disburse_date = $loan->disburse_date;
	}else{
	   $loan_amount = $ex_loan->loan_amount;
           $disburse_date = $ex_loan->disburse_date;
	}
	$as_date = Request::input('as_of_date');
    $arreas_arr =  \LoanCalculate::getTotalPenalty($loan,$as_date);
	//if($clientData["account_no"] == "100-132222-34-0082") dd($arreas_arr);
	$overdue = $arreas_arr[2];
        if(!empty($loan->disburse_date)){
            if($overdue < 0){
                $payment_status_code = 'Q';
            //}elseif($overdue <= $loan->penalty_period1){
            }elseif(round($arreas_arr[6],2) == 0){
                  $payment_status_code = '0';
            }elseif($clientData['status'] == 5){ // Loss Loan
                $payment_status_code = 'L';
            }elseif($clientData['status'] == 6){ // Write Off
                $payment_status_code = 'W';
            }elseif($clientData['status'] == 8) { // Close
                $payment_status_code = 'C';
            }elseif($overdue >= 360){
                $payment_status_code = 'L';
            }elseif($overdue >= 330){
                $payment_status_code = 'Y';
            }elseif($overdue >= 300){
                $payment_status_code = 'E';
            }elseif($overdue >= 270){
                $payment_status_code = 'T';
            }else{
                $payment_status_code = (string)((int)($overdue/30) + 1);
            }
        }
/*New Prakas
Classification	Number of days past due		Allowance rate
Standard	Zero to 14 days (short-term)	1%
		Zero to 29 days (long-term)	
Special mention	15 days to 30 days (short-term)	3%
		30 days to 89 days (long-term)	
Substandard	31 days to 60 days (short-term)	20%
		90 days to 179 days (long-term)	
Doubtful	61 days to 90 days (short-term)	50%
		180 days to 359 days (long-term)	
Loss		More than 91 days (short-term)	100%
		360 days or more (long-term)	

*/
        // short-term loan
	if($loan->loan_duration <= 12){
	  if($overdue <= 14)		$product_status_code = 'N';
	  elseif($overdue <= 30) 	$product_status_code = 'S';
	  elseif($overdue <= 60) 	$product_status_code = 'U';
	  elseif($overdue <= 90) 	$product_status_code = 'D';
	  else 				$product_status_code = 'L';
	}
	// long-term loan
	if($loan->loan_duration > 12){
	  if($overdue <= 29)		$product_status_code = 'N';
	  elseif($overdue <= 89) 	$product_status_code = 'S';
	  elseif($overdue <= 179) 	$product_status_code = 'U';
	  elseif($overdue <= 359) 	$product_status_code = 'D';
	  else	 			$product_status_code = 'L';
	}
	if((date('Ym',strtotime($as_date))) == date('Ym',strtotime($loan->disburse_date))){
        $payment_status_code = 'Q';
    }
    foreach($loanData['product'] as $key=>$prodItem){
        $products[$key] = $prodItem;
    }
    foreach($products['product_types'] as $tkey=>$prodType){
        $productsType[$tkey] = $prodType;
    }
    //dd($loanData['transaction']);
    $no_tr_flg = 0;
    $last_trans = ['trans_date'=>'', 'principal'=>0, 'interest'=>0, 'fee'=>0, 'penalty'=>0];
    foreach($loanData['transaction'] as $trkey=>$transaction){
        if(intval(date('Ymd',strtotime($as_date))) >= intval(date('Ymd',strtotime($transaction['trans_date'])))){
            $transaction[$trkey] = $transaction;
            $last_trans = $transaction;	
            $no_tr_flg = 1;
        }	
    }

    $lastAmountPaid = floatval($last_trans['principal'])+floatval($last_trans['interest'])+floatval($last_trans['fee']+floatval($last_trans['penalty']));
    $current_month_install = 0;
    $next_sch = null;
    foreach($loanData['schedule'] as $skey=>$schedule){
        $schedules[] = $schedule;
        if((date('Ym',strtotime($as_date))) == date('Ym',strtotime($schedule['schedule_date']))){
            $current_month_install = $schedule['principal'] + $schedule['interest']+ $schedule['fee'];
            if(!empty($loanData['schedule'] [$skey+1]) || !is_null($loanData['schedule'] [$skey+1])){
                $next_sch = $loanData['schedule'] [$skey+1]['schedule_date'];
            }else{
                $next_sch = $this->dmYFormat(date('dmY', strtotime($as_date .'+1 day')));
            }
        }
    }
    if($no_tr_flg == 0 && intval(date('Ym',strtotime($as_date))) < intval(date('Ym',strtotime($loanData['schedule'][1]["schedule_date"])))){
        $payment_status_code = 'Q';
        if(is_null($next_sch)) $next_sch = $loanData['schedule'] [1]['schedule_date'];
    }
    if($current_month_install == 0) $current_month_install = $lastAmountPaid;
        // $current_install = 0;
        // if($current_install == 0 || ($current_month_install -  $lastAmountPaid < 0)){
        //   $current_install += $current_month_install;
        // }
	$collateral_type = '';
        foreach($loanData['collateral'] as $ckey=>$collateral){
	   if(strpos($collateral_type, substr($collateral['collateral_type'],0,1)) == FALSE)
	   $collateral_type .= substr($collateral['collateral_type'],0,1);
        }
	if(strlen($collateral_type) > 1){
	   $collateral_type = 'MP';
	}else if(strlen($collateral_type) == 1){
	   $collateral_type = $collateral['collateral_type'];
	}else{
	   $collateral_type = 'NO';
	}
        foreach($loanData['writeoff'] as $wkey=>$writeo){
            $writeoff[$wkey] = $writeo;
        }
        // if(date('mY', strtotime($as_date)) != date('mY', strtotime($loanData['disburse_date']))){
        //     $next_sch = (date_dif($arreas_arr[5], $as_date, 1, false) > 0)?date('dmY', strtotime($as_date .'+1 day')):$this->dmYFormat($arreas_arr[5]);
        // }
        //if(date_dif($arreas_arr[5], $as_date, 1, false) > 0)?date('dmY', strtotime($as_date .'+1 day')):$this->dmYFormat($arreas_arr[5]),
        $lastPaymentdate = '';
        if(in_array($payment_status_code, ['N,R,Q'])) {
	    $current_month_install = 0;
            $lastPaymentdate =  '';//$this->dmYFormat($transaction['trans_date']);

        }
        if($lastAmountPaid == 0) {

            $lastPaymentdate = '';

        }if($lastAmountPaid>0){

            $lastPaymentdate =  $this->dmYFormat($last_trans['trans_date']);
        }
	$next_payment_date = $this->dmYFormat($next_sch);
	//$product_sts = config('static_data.loan_product_status')[$clientData['status']];
	if($payment_status_code != 'W' && $os_balance == 0){
        $payment_status_code = 'C';
        $product_status_code = 'C';
	    $next_payment_date = '';
    }
    if($payment_status_code == 'W'){
        $product_status_code = 'W';
    }
    /*
	if($payment_status_code == '0'){
        if(round($arreas_arr[4] +$arreas_arr[6],2) > 0){
            $payment_status_code = '1';
        }
    }
	*/
	if(date_dif(end($loanData['schedule'])['schedule_date'], $as_date, 1, false) <= 0){ // exp date > as of date
	    $expiry_date = end($loanData['schedule'])['schedule_date'];
	}else{ // exp date < as of date
	    $expiry_date = ($payment_status_code == 'C')? $as_date:end($loanData['schedule'])['schedule_date'];
    }
	if(in_array($payment_status_code, array('R', 'L', 'W', 'C'))){
        $next_payment_date = '';
    }

	if($clientData['account_no'] == "100-132232-34-0022") $disburse_date = "2017-10-24";
	if($clientData['account_no'] == "100-132222-34-0040") $disburse_date = "2017-10-28";
	// loan ORO before 01-01-2019
	if((substr($clientData["loan"]["contract_id"], 0, 2) == "LC") && (date_dif($disburse_date, "2019-01-01", 1, false) > 0)) $disburse_date = "2019-01-01";
	$write_off_amount = $writeoff['write_off_outst_balance'] + $writeoff['wo_interest'];
	// product_status_code
	$ps_code = config('static_data.loan_product_status')[$clientData['status']];
    if($clientData['status'] > 8) $ps_code = "C";
	if($ps_code == "C" && $payment_status_code == "0"){
		$ps_code = $product_status_code;
	}
        return [
            'creditor_id'                   =>262,
            'account_type'                  =>$v['account_cbc_type'],
            'Group Account Reference'       =>'',
            'Account Number'                =>(substr($clientData["loan"]["contract_id"], 0, 2) == "TG")?$clientData['account_no'] : $clientData["loan"]["contract_id"],
            'Date Issued'                   =>$this->dmYFormat($disburse_date),
            'Product Type'                  =>$productsType['products_code'],
            'Currency'                      =>Currency::where('id', $clientData['currency'])->first()->code,
            'Original Amount'               =>$loan_amount,
            'Product Expiry Date'           =>$this->dmYFormat($expiry_date), //$loanData['schedule'][$loanData['loan_duration']-1]['schedule_date']
            //'Product Status'                =>$product_status_code, 
	        'Product Status'                => $ps_code, //$products['status_code'],
            'Restructured Loan'             =>$loanData['restructured_loan'],
            'Instalment Amount'             =>round($current_month_install,2),
            'Payment Frequency'             =>$loanData['frequency'],
            'Tenure'                        =>$loanData['loan_duration'],
            'Last Payment Date'             =>$lastPaymentdate,
            'Last Amount Paid'              =>$lastAmountPaid, //transaction principle+ interested ;Must = 0 for Payment Status Code N,R,Q, No payments are expected for these statuses.
            'Security Type'                 =>$collateral_type,
            'Outstanding Balance'           =>abs(round($os_balance,2)),
            'Past Due Amount'               =>($os_balance == 0)? 0 : round($arreas_arr[6],2) ,
            'Next Payment Date'             =>$next_payment_date,
            'Payment Status Code'           =>$payment_status_code,
            'As of Date'                    =>$this->dmYFormat($as_date),   ///
            'Write Off Status'              =>($payment_status_code == "W")?(($os_balance == 0)? "FS":"PP"):"",
            'Write Off Status Date'         =>$this->dmYFormat($writeoff['write_off_date']),
            'Write Off Original Amount as at Load Date'=>($write_off_amount > 0)? $write_off_amount:"",         //
            'Write Off Outstanding Balance' => $writeoff['write_off_outst_balance'],
        ];
        //dd($datas);
    }

    private function dmYFormat($date) {

        if(empty($date) || $date == '0000-00-00'){
            return '';
        }else{

            return strval(str_pad(date("dmY", strtotime(str_replace(['-'], '', $date))),8,"0",STR_PAD_LEFT));
        }

    }

    private function getIdentificationValues($data) {
//	$idData=[];
	for($i = 1; $i < 4; $i++){
	  $idData[]= [
                        'id_type_id_'.$i  =>'',
                        'id_number_'.$i  =>'',
                        'id_expiry_date_'.$i  =>'',
                     ];
	}
	$index = 0;

	foreach($data as $a=>$ids){
	  $index = $a + 1;
	  $idCode = $ids['types']['code'];
	  $idNum = $ids['id_number'];
	  if($idCode=="N"){
	    $idNum = str_replace("(01)","",$idNum);
	    $idNum = str_replace(" ","",$idNum);
	    $idNum = str_pad($idNum, 9, "0", STR_PAD_LEFT);
	  }
	  $idData[$a] = [
                    'id_type_id_'.$index        => $idCode,
                    'id_number_'.$index         => $idNum,
                    'id_expiry_date_'.$index    => $this->dmYFormat($ids['id_expiry_date']),
                      ];

	}

        $result[] = collect($idData)->collapse()->all();
        return collect($result)->collapse()->all();
    }

    private function getAddressValues($data) {

        if(count($data) == 1) {
            foreach($data as $item) {

                $data1[] =  [
                    'address_type'                  =>$item['address_type'],                    // 29 Man, 5
                    'province'                      =>trim($item['province']['prov_gis']),      // 30 Man, 2, this attr is hidden web call for select is value from relationship tables.
                    'district'                      =>trim($item['district']['distr_gis']),     // 31 $item['district_id'],              //23 Man, 4,------------||-------------
                    'commune'                       =>trim($item['commune']['comm_gis']),       // 32 Man, 6,------------||-------------
                    'village'                       =>trim($item['village']['vill_gis']),       // 33 $item['village_id'],           //25 man, 8,------------||-------------
                    'address_en1'                    =>$item['address_en1'],                      // 34 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    //'address_en2'                    =>$item['address_en2'],                      // 34 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    'address_en2'                    =>"",                      // 34 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ 
					'address_kh1'                    =>$item['address_kh1'],                      // 35 note, 150  Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and //country = KHM – must be blank
                    'address_kh2'                    =>"",                      // 35 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and //country = KHM – must be blank
                    'City (English)'                =>$item['city_code'],                       // 36 Khmer, English but the city code is the same
                    //'City (Khmer)'                  =>$item['city_code'],                       // 37 Khmer, English but the city code is the same
                    'City (Khmer)'                  =>'',                       // 37 Khmer, English but the city code is the same
                    'country'                       =>trim($item['country']['iso_code_3']),     // 38 $item['country_id'],               //29 man, 2, Hidden country code is according to ISO. Foreign key
                    'postal_code'                   =>($item['postal_code']!=0)?$item['postal_code']:'',                     //39
                ];
            }
            for($i=1; $i <=2; $i++){
                $data1[] =  [
                    'address_type_'.$i                  =>'',             //20 Man, 5
                    'province_'.$i                      =>'',              //22 Man, 2, this attr is hidden web call for select is value from relationship tables.
                    'district_'.$i                      =>'',              //23 Man, 4,------------||-------------
                    'commune_'.$i                       =>'',           //24 Man, 6,------------||-------------
                    'village_'.$i                       =>'',           //25 man, 8,------------||-------------
                    'address_en1_'.$i                    =>'',               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    'address_en2_'.$i                    =>'',               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    'address_kh1_'.$i                    =>'',               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and country = KHM – //must be blank
                    'address_kh2_'.$i                    =>'',               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and country = KHM – //must be blank
                    'City (English)'.$i                    =>'',                //28 Khmer, English but the city code is the same
                    'City (Khmer)'.$i                      =>'',
                    'country_'.$i                       =>'',               //29 man, 2, Hidden country code is according to ISO. Foreign key
                    'postal_code_'.$i                   =>'',              //30
                ];
            }
            $result[] = collect($data1)->collapse()->all();

        }if(count($data) == 2) {

            foreach($data as $keys2=>$item) {

                $data2[] =  [
                    'address_type_'.$keys2                  =>$item['address_type'],             //20 Man, 5
                    'province_'.$keys2                      =>trim($item['province']['prov_gis']),              //22 Man, 2, this attr is hidden web call for select is value from relationship tables.
                    'district_'.$keys2                      =>trim($item['district']['distr_gis']),              //23 Man, 4,------------||-------------
                    'commune_'.$keys2                       =>trim($item['commune']['comm_gis']),           //24 Man, 6,------------||-------------
                    'village_'.$keys2                       =>trim($item['village']['vill_gis']),           //25 man, 8,------------||-------------
                    'address_en1_'.$keys2                    =>$item['address_en1'],               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    'address_en2_'.$keys2                    =>$item['address_en2'],               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    'address_kh1_'.$keys2                    =>$item['address_kh1'],               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST ///and country = KHM – must be blank
                    'address_kh2_'.$keys2                    =>$item['address_kh2'],               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST ///and country = KHM – must be blank
                    'City (English)'.$keys2                  =>$item['city_code'],                //28 Khmer, English but the city code is the same
                    'City (Khmer)'.$keys2                    =>$item['city_code'],                //28 Khmer, English but the city code is the same
                    'country_'.$keys2                       =>trim($item['country']['iso_code_3']),               //29 man, 2, Hidden country code is according to ISO. Foreign key
                    'postal_code_'.$keys2                   =>($item['postal_code']!=0)?$item['postal_code']:'',              //30
                ];
            }
            $data2[] =  [
                'address_type_3'                  =>'',             //20 Man, 5
                'province_3'                      =>'',              //22 Man, 2, this attr is hidden web call for select is value from relationship tables.
                'district_3'                      =>'',              //23 Man, 4,------------||-------------
                'commune_3'                       =>'',           //24 Man, 6,------------||-------------
                'village_3'                       =>'',           //25 man, 8,------------||-------------
                'address_en1_3'                    =>'',               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                'address_en2_3'                    =>'',               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                'address_kh1_3'                    =>'',               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and country = KHM – mustbe ///blank
                'address_kh2_3'                    =>'',               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and country = KHM – must be ///blank
                'City (English)'                  =>'',                //28 Khmer, English but the city code is the same
                'City (Khmer)'                    =>'',                //28 Khmer, English but the city code is the same
                'country_3'                       =>'',               //29 man, 2, Hidden country code is according to ISO. Foreign key
                'postal_code_3'                   =>'',              //30
            ];
            $result[] = collect($data2)->collapse()->all();
        }if(count($data) == 3) {

            foreach($data as $key3=>$item) {
                $ddd3[] =  [
                    'address_type_'.$key3                  =>$item['address_type'],             //20 Man, 5
                    'province_'.$key3                      =>trim($item['province']['prov_gis']),              //22 Man, 2, this attr is hidden web call for select is value from relationship tables.
                    'district_'.$key3                      =>trim($item['district']['distr_gis']),              //23 Man, 4,------------||-------------
                    'commune_'.$key3                       =>trim($item['commune']['comm_gis']),           //24 Man, 6,------------||-------------
                    'village_'.$key3                       =>trim($item['village']['vill_gis']),           //25 man, 8,------------||-------------
                    'address_en1_'.$key3                    =>$item['address_en1'],               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    'address_en2_'.$key3                    =>$item['address_en2'],               //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
                    'address_kh1_'.$key3                    =>$item['address_kh1'],               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and ///country = KHM – must be blank
                    'address_kh2_'.$key3                    =>$item['address_kh2'],               //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and ///country = KHM – must be blank
                    'City (English)_'.$key3                =>$item['city_code'],                //28 Khmer, English but the city code is the same
                    'City (Khmer)_'.$key3                  =>$item['city_code'],
                    'country_'.$key3                       =>trim($item['country']['iso_code_3']),               //29 man, 2, Hidden country code is according to ISO. Foreign key
                    'postal_code_'.$key3                   =>($item['postal_code']!=0)?$item['postal_code']:'',              //30
                ];
            }
            $result[] = collect($ddd3)->collapse()->all();
        }

        return collect($result)->collapse()->all();
    }

    private function getContactValues($data) {

        if(count($data) == 1) {

            foreach($data as $keys1=>$item) {

                $ddd1[]= ['email_address'  =>$item['email_address']];
                $ddd1[] =  [
                    'contact_number_type'           =>$item['contact_number_type'],              //32 note 1,
                    'contact_number_country_code'   =>$item['contact_number_country_code'],      //33 note 4
                    'contact_number_area'           =>$item['contact_number_area'],              //34 note 3
                    'contact_number_number'         =>$item['contact_number_number'],            //35 notes 25
                    'contact_number_extension'      =>$item['contact_number_extension'],         //35 notes 10
                ];
            }

            for($i=1;$i<=2;$i++){
                $ddd1[] =  [
                    'contact_number_type_'.$i           =>'',              //32 note 1,
                    'contact_number_country_code_'.$i   =>'',      //33 note 4
                    'contact_number_area_'.$i           =>'',              //34 note 3
                    'contact_number_number__'.$i         =>'',            //35 notes 25
                    'contact_number_extension__'.$i      =>'',         //35 notes 10
                ];
            }


            $result[] = collect($ddd1)->collapse()->all();

        }if(count($data) == 2) {

            $i=0;
            foreach($data as $keys2=>$item) {

                if($i == 0){
                    $ddd2[]= ['email_address'                 =>$item['email_address']];                    //31 opt, 50];
                }
                $i++;
                $ddd2[] =  [
                    'contact_number_type_'.$keys2           =>$item['contact_number_type'],              //32 note 1,
                    'contact_number_country_code_'.$keys2   =>$item['contact_number_country_code'],      //33 note 4
                    'contact_number_area_'.$keys2           =>$item['contact_number_area'],              //34 note 3
                    'contact_number_number_'.$keys2         =>$item['contact_number_number'],            //35 notes 25
                    'contact_number_extension_'.$keys2      =>$item['contact_number_extension'],         //35 notes 10
                ];

            }

            $ddd2[] =  [
                'contact_number_type_2'           =>'',              //32 note 1,
                'contact_number_country_code_2'   =>'',      //33 note 4
                'contact_number_area_2'           =>'',              //34 note 3
                'contact_number_number_2'         =>'',            //35 notes 25
                'contact_number_extension_2'      =>'',         //35 notes 10
            ];

            $result[] = collect($ddd2)->collapse()->all();
        }if(count($data) >= 3) {
            $i = 0;
            foreach($data as $keys3=>$item) {
                if($i == 0){
                    $ddd3[]= ['email_address'                 =>$item['email_address']];                    //31 opt, 50];
                }
                $i++;
                $ddd3[] =  [
                    'contact_number_type_'.$keys3           =>$item['contact_number_type'],              //32 note 1,
                    'contact_number_country_code_'.$keys3   =>$item['contact_number_country_code'],      //33 note 4
                    'contact_number_area_'.$keys3           =>$item['contact_number_area'],              //34 note 3
                    'contact_number_number_'.$keys3         =>$item['contact_number_number'],            //35 notes 25
                    'contact_number_extension_'.$keys3      =>$item['contact_number_extension'],         //35 notes 10
                ];
                if($i>=3) break;
            }
            $result[] = collect($ddd3)->collapse()->all();
        }
        return collect($result)->collapse()->all();
    }

    private function getEmployerValues($data) {
      for($i=1; $i <=3; $i++) {
          $ddd[] =  [
              'employer_type_'.$i                 =>'',           //36 opt 1
              'self_employed_'.$i                 =>'',           //37 opt 1
              'employer_name_'.$i                 =>'',           //38 opt 50
              'employer_name_kh_'.$i              =>'',        //39 opt 50
              'economic_sector_'.$i               =>'',         //40 opt 2
              'business_type_'.$i                 =>'',           //41 opt 5
              'employer_address_'.$i              =>'',        //42 note 150 Default Address Type to ‘WORK’ Set Address Field 2 English to blank.
              'employer_address_kh_'.$i           =>'',     //43 note 150 ---------||----------
              'employer_province_'.$i             =>'',             //44
              'employer_district_'.$i             =>'',            //45
              'employer_commune_'.$i              =>'',             //46
              'employer_village_'.$i              =>'',             //47
              'employer_address_city_'.$i         =>'',   //48
              'employer_address_city_kh_'.$i      =>'',//49
              'employer_country_'.$i              =>'',               //50
              'em_postal_code_'.$i                =>'',          //51
              'occupation_'.$i                    =>'',              //52
              'occupation_kh_'.$i                 =>'',           //53
              'date_of_employment_'.$i            =>'',      //54
              'length_of_service_'.$i             =>'',       //55
              'contract_exp_date_'.$i             =>'',       //56
              'currency_code_'.$i                 =>'',           //56
              'monthly_basic_salary_'.$i          =>'',    //58
              'total_monthly_salary_'.$i          =>'',    //59
          ];
      }
      $index = 0;
      foreach($data as $key=>$item){
        $index = $key + 1;
        if($index > 3) continue;
	$ddd[$key] =  [
            'employer_type_'.$index                 =>$item['employer_type'],           //36 opt 1
            'self_employed_'.$index                 =>$item['self_employed'],           //37 opt 1
            'employer_name_'.$index                 =>$item['employer_name'],           //38 opt 50
            'employer_name_kh_'.$index              =>$item['employer_name_kh'],        //39 opt 50
            'economic_sector_'.$index               =>$item['economic_sector']['economic_code'],         //40 opt 2
            'business_type_'.$index                 =>'', //$item['business_type'],           //41 opt 5
            'employer_address_'.$index              =>($item['employer_name']!='')? 'WORK':'',  //$item['employer_address'],        //42 note 150 Default Address Type to ‘WORK’ Set Address Field 2 English to blank.
            'employer_address_kh_'.$index           =>'',  //$item['employer_address_kh'],     //43 note 150 ---------||----------
            'employer_province_'.$index             =>trim($item['employer_province_id']),             //44
            'employer_district_'.$index             =>trim($item['employer_district_id']),            //45
            'employer_commune_'.$index              =>trim($item['employer_commune_id']),             //46
            'employer_village_'.$index              =>trim($item['employer_village_id']),             //47
            'employer_address_city_'.$index         =>$item['employer_address_city'],   //48
            'employer_address_city_kh_'.$index      =>$item['employer_address_city_kh'],//49
            'employer_country_'.$index              =>trim($item['country']['em_code_3']),               //50
            'em_postal_code_'.$index                =>$item['em_postal_code'],          //51
            'occupation_'.$index                    =>$item['occupation'],              //52
            'occupation_kh_'.$index                 =>$item['occupation_kh'],           //53
            'date_of_employment_'.$index            =>$this->dmYFormat($item['date_of_employment']),      //54
            'length_of_service_'.$index             =>$item['length_of_service'],       //55
            'contract_exp_date_'.$index             =>$this->dmYFormat($item['contract_exp_date']),       //56
            'currency_code_'.$index                 =>trim($item['currency']['currency_code']),           //56
            'monthly_basic_salary_'.$index          =>$item['monthly_basic_salary'],    //58
            'total_monthly_salary_'.$index          =>$item['total_monthly_salary'],    //59
        ];
      }
      return collect($ddd)->collapse()->all();
    }

}
