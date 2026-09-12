<?php
/**
 * Created by PhpStorm.
 * User: hengsoheak
 * Date: 12/9/2016
 * Time: 9:54 AM
 */

namespace App\Models\Cbc;


use App\Models\MyModels;
use DB;
use App\Models\Client;
use App\Models\ClientLoanAccounts;

class CBC extends MyModels
{

    /**
     * the index number is the index of CBC so it can not be change.
     * @type array
     */

    private $_identification = [
        'client_id',            //hidden
        'id_type_id',           //0, Man, 1
        'id_number',            //1, Man, 20
        'id_expiry_date',       //2, Opt, 8
    ];

    private $_client = [
        'clients.id',
        'account_cbc_type',
    ];

    private $_general = [
        'client_id',                //hidden
        'date_of_birth',
        'family_name',              //3 opt, 70 ,Mandatory if in same language if First Name entered. Mandatory if Unformatted Name not entered. Both
        'first_name',               //4 70,  -------------||------
        'second_name',              //5 opt
        'third_name',               //6 opt
        'unformatted_name',         //7 opt, 70, (Mandatory if Family and First Name not entered.)
        'mother_name_unfomatted',   //8 opt,  180,---------||------
        'family_name_kh',            //10 Man, 79, same english name
        'first_name_kh',            //10 Man, 79, same english name
        'second_name_kh',           //11 opt, 30
        'third_name_kh',            //12 opt, 30
        'unformatted_name_kh',      //13 opt, 180
        'mother_name_unfomatted_kh',//14 opt, 180
        'gender',                   //15 Man, 1
        'marital_status',           //16 Man, 1
        'national_code',            //17 Man, 3
        'taxpayer_reg_no',          //18 opt, 9
        'applicant_type',           //19 Man, 1 merl this array to $_address array.

    ];
    private $_address  = [
        'address_type',             //20 Man, 5
        'province_Id',              //22 Man, 2, this attr is hidden web call for select is value from relationship tables.
        'district_id',              //23 Man, 4,------------||-------------
        'commune_id',               //24 Man, 6,------------||-------------
        'village_id',               //25 man, 8,------------||-------------
        'address_en1',              //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
        'address_en2',              //26 Man, 150, Allow also the following punctuation characters: – dash or hyphen, comma/ slashSpace. dot
        'address_kh1',              //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and country = KHM – must be blank
        'address_kh2',              //27 note, 150          Mandatory when country is not Cambodia for all address types. Optional for address types RESID and WORK. Value must be entered either in English or Khmer If address type is POST and country = KHM – must be blank
        'city_code',                //28 Khmer, English but the city code is the same
        'country_id',               //29 man, 2, Hidden country code is according to ISO. Foreign key
        'postal_code',              //30
        'client_id',                //hidden
        'id'                        //hidden
    ];

    private $_contact = [
        'client_id',                        //hide
        'email_address',                    //31 opt, 50
        'contact_number_type',              //32 note 1,
        'contact_number_country_code',      //33 note 4
        'contact_number_area',              //34 note 3
        'contact_number_number',            //35 notes 25
        'contact_number_extension',         //35 notes 10
    ];

    private $_employers = [
        'client_id',                        //hide
        'employer_type',                    //36 opt 1
        'self_employed',                    //37 opt 1
        'employer_name',                    //38 opt 50
        'employer_name_kh',                 //39 opt 50
        'economic_id',                      //40 opt 2
        'business_type',                    //41 opt 5
        'employer_address',                 //42 note 150 Default Address Type to ‘WORK’ Set Address Field 2 English to blank.
        'employer_address_kh',              //43 note 150 ---------||----------
        'employer_province_id',             //44
        'employer_district_id',             //45
        'employer_commune_id',              //46
        'employer_village_id',              //47
        'employer_address_city',            //48
        'employer_address_city_kh',         //49
        'employer_country_id',              //50
        'postal_code as em_postal_code',                      //51
        'occupation',                       //52
        'occupation_kh',                    //53
        'date_of_employment',               //54
        'length_of_service',                //55
        'contract_exp_date',                //56
        'currency_id',                      //57
        'monthly_basic_salary',             //58
        'total_monthly_salary',             //59
    ];

    public function CBCData($id=null) {

//        $select = array_merge($this->_client,['client_loan_accounts.id as acc_id']);
        $select = array_merge($this->_client);
        $data =  Client::select($select)
            //->join('client_loan_accounts', 'client_loan_accounts.client_id', '=', 'clients.id')
            ->with([
            'Identification'=>function($s) {
                $s->with(['types'=>function($type){
                    $type->select('id','code');
                }]);
                $s->select($this->_identification);
            },
            'general'=>function($s){
                $s->select($this->_general);
            },
            'Address'=>function($s) {
                $s->with(
                    [
                        'province'=>function($a) {$a->select('id','prov_gis');},
                        'District'=>function($a) {$a->select('id','distr_gis');},
                        'Commune'=>function($a) {$a->select('id','comm_gis');},
                        'Village'=>function($a) {$a->select('id','vill_gis');},
                        'country'=>function($a) {$a->select('id','iso_code_3');},
                    ]);
                $s->select($this->_address);
            },
            'Contact'=>function($s){
                $s->select($this->_contact);
            },
            'Employer'=>function($s){
                $s->select($this->_employers)->orderBy('id','desc');
                $s->with([
                    'currency'=>function($sa){$sa->select('id','code as currency_code');
                    }]);
                $s->with([
                    'EconomicSector'=>function($sa){
                        $sa->select('id','code as economic_code');
                    }]);

                $s->with([
                        'province'=>function($a) {$a->select('id','prov_gis as em_prov_gis');},
                        'District'=>function($a) {$a->select('id','distr_gis as em_distr_gis');},
                        'Commune'=>function($a) {$a->select('id','comm_gis as em_comm_gis');},
                        'Village'=>function($a) {$a->select('id','vill_gis as em_vill_gis');},
                        'country'=>function($a) {$a->select('id','iso_code_3 as em_code_3');},// the country was the last index of address
                ]);
            },
            'clientLoan'=>function($s) {
                 $s->with([
                    'currencies'=>function($sa){$sa->select('id','code as currency_code');
                    }]);
                $s->select('loan_ref','id', 'account_no', 'client_id','currency','status', 'balance','sub_client_id','guarantor','acc_type'); //sub_client_id the id of joining account
            },
            'loan'=>function($s) {

                $s->with(['schedule'=>function($q){
                    $q->select('id' , 'loan_id', 'schedule_date','principal', 'interest');
                }]);
                $s->with(['transaction'=>function($trans){
                    $trans->select('id', 'loan_id', 'trans_date', 'principal', 'interest', 'fee', 'penalty')->whereRaw("`trans_type` = 'Pay-Off' or `trans_type` = 'Loan Repayment'");// WHERE trans_type = Repayment// priciple+interest
                }]);
                $s->with(['product'=>function($prod){

                    $prod->with(['product_types'=>function($types){
                        $types->select('id','code as products_code');
                    }]);
                    $prod->select('id','status_code','product_type_id');
                }]);

                $s->with(['collateral'=>function($collact){
                    $collact->select('id', 'loan_id', 'collateral_type');
                }]);
                $s->with(['writeoff'=>function($writeoff){
                    $writeoff->select('id', 'loan_id', 'write_off_date', 'write_off_status','amount as write_off_amount','write_off_outst_balance');
                }]);
                $s->select('id', 'client_id', 'disburse_date', 'status', 'product_id', 'loan_amount', 'restructured_loan', 'loan_duration','payment_status_code')->whereIn('status', [3,8]); // product_id query from products table
            }
        ]);
        if(!empty($id)){

            $data->where('clients.id', $id);
            // if(!empty($acc_type)){
            //     $data->where('account_cbc_type', $acc_type);
            // }
        }
//        dd($data->paginate(5)->toArray());
//        return $data->orderBy('acc_id','ASC')->get()->toArray();
        return $data->get()->toArray();
    }

  public function CBCData_new($id=null, $last_arr=null) {

  //        $select = array_merge($this->_client,['client_loan_accounts.id as acc_id']);
      $select = array_merge($this->_client);
      $data =  ClientLoanAccounts::select('id', 'client_id', 'account_no', 'account_name', 'currency', 'balance', 'status', 'sub_client_id', 'guarantor','acc_type')
            ->with(['loan'=>function($s) {
                $s->with(['schedule'=>function($q){
                    $q->select('id' , 'loan_id', 'schedule_date','principal', 'interest','fee');
                }]);
                $s->with(['transaction'=>function($trans){
                    $trans->select('id', 'loan_id', 'trans_date', 'principal', 'interest', 'fee', 'penalty')->whereRaw("`trans_type` = 'Pay-Off' or `trans_type` = 'Close Loan' or `trans_type` = 'Auto Loan Repayment' or `trans_type` = 'Loan Repayment'");// WHERE trans_type = Repayment// priciple+interest
                }]);
                $s->with(['product'=>function($prod){

                    $prod->with(['product_types'=>function($types){
                        $types->select('id','code as products_code');
                    }]);
                    $prod->select('id','status_code','product_type_id');
                }]);

                $s->with(['collateral'=>function($collact){
                    $collact->select('id', 'loan_id', 'collateral_type');
                }]);
                $s->with(['writeoff'=>function($writeoff){
                    $writeoff->select('id', 'loan_id', 'write_off_date', 'write_off_status','amount as write_off_amount','write_off_outst_balance', 'wo_interest');
                }]);
/*
                $s->select('id', 'client_id', 'disburse_date', 'status', 'product_id', 'loan_amount', 'restructured_loan', 'loan_duration','payment_status_code')->whereIn('status', [3,8]); // product_id query from products table
                */
            }
          ])
          ->with(['client'=>function($q){
            $q->with([
            'Identification'=>function($s) {
                $s->with(['types'=>function($type){
                    $type->select('id','code');
                }]);
                $s->select($this->_identification);
            },
            'general'=>function($s){
                $s->select($this->_general);
            },
            'Address'=>function($s) {
                $s->with(
                    [
                        'province'=>function($a) {$a->select('id','prov_gis');},
                        'District'=>function($a) {$a->select('id','distr_gis');},
                        'Commune'=>function($a) {$a->select('id','comm_gis');},
                        'Village'=>function($a) {$a->select('id','vill_gis');},
                        'country'=>function($a) {$a->select('id','iso_code_3');},
                    ]);
                $s->select($this->_address);
            },
            'Contact'=>function($s){
                $s->select($this->_contact);
            },
            'Employer'=>function($s){
                $s->select($this->_employers)->orderBy('id','desc');
                $s->with([
                    'currency'=>function($sa){$sa->select('id','code as currency_code');
                    }]);
                $s->with([
                    'EconomicSector'=>function($sa){
                        $sa->select('id','code as economic_code');
                    }]);

                $s->with([
                        'province'=>function($a) {$a->select('id','prov_gis as em_prov_gis');},
                        'District'=>function($a) {$a->select('id','distr_gis as em_distr_gis');},
                        'Commune'=>function($a) {$a->select('id','comm_gis as em_comm_gis');},
                        'Village'=>function($a) {$a->select('id','vill_gis as em_vill_gis');},
                        'country'=>function($a) {$a->select('id','iso_code_3 as em_code_3');},// the country was the last index of address
                ]);
            }]);
          }]);
/*
          ->with(['loan'=>function($s) {

                $s->with(['schedule'=>function($q){
                    $q->select('id' , 'loan_id', 'schedule_date','principal', 'interest');
                }]);
                $s->with(['transaction'=>function($trans){
                    $trans->select('id', 'loan_id', 'trans_date', 'principal', 'interest')->whereRaw("`trans_type` = 'Pay-Off' or `trans_type` = 'Loan Repayment'");// WHERE trans_type = Repayment// priciple+interest
                }]);
                $s->with(['product'=>function($prod){

                    $prod->with(['product_types'=>function($types){
                        $types->select('id','code as products_code');
                    }]);
                    $prod->select('id','status_code','product_type_id');
                }]);

                $s->with(['collateral'=>function($collact){
                    $collact->select('id', 'loan_id', 'collateral_type');
                }]);
                $s->with(['writeoff'=>function($writeoff){
                    $writeoff->select('id', 'loan_id', 'write_off_date', 'write_off_status','amount as write_off_amount','write_off_outst_balance');
                }]);
                $s->select('id', 'client_id', 'disburse_date', 'status', 'product_id', 'loan_amount', 'restructured_loan', 'loan_duration','payment_status_code')->whereIn('status', [3,8]); // product_id query from products table
            }
          ]);
          */
      if(!empty($id)){

          $data->whereIn('client_id', $id);
          // if(!empty($acc_type)){
          //     $data->where('account_cbc_type', $acc_type);
          // }
      }
  //        return $data->orderBy('acc_id','ASC')->get()->toArray();
      return $data->get()->toArray();
  }

}
