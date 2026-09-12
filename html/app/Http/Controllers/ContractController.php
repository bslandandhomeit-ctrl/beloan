<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitType;
use App\Models\Loan;
use App\Models\Cbc\ClientCbcGeneral;
use App\Models\CompanyBranch;
use App\Models\RepaymentSchedule;
use App\Models\CoBorrower;
use App\Models\User;
use App\Models\Client;
class ContractController extends Controller {
	public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

	public function condo($id)
	{	
		if(!empty($id)){
			// $loan = Loan::find($id);
			$loan = Loan::join('projects','projects.id','=','loans.project_id')
			->join('company_branch','company_branch.id','=','projects.company_id')
			->join('representatives','representatives.id','=','projects.sale_representative_id')
			->where('loans.id',$id)
			->select('loans.*','representatives.name','representatives.gender','representatives.dob','representatives.national_id','representatives.national_issued_date','representatives.phone','representatives.national_front','representatives.national_back','company_branch.logo as logo','company_branch.branch_name','company_branch.branch_name_en')->first();
			
			if(!$loan){
				return redirect()->back();
			}
			$this->_data['client'] = Client::where('id', $loan->client_id)
			->with([
					'clientLoan',
					'general.country',
					'general.province',
					'general.District',
					'general.Commune',
					'general.Village',
					'Address.country.description',
					'Address.province',
					'Address.District',
					'Address.Commune',
					'Address.Village'
				]
			)->where('id', $loan->client_id)->first();

			$co_borrower  = CoBorrower::where('loan_id',$id)->get();
			$teller_user = User::select('users.name as name','users.kh_name')		
			->where('users.id', '=', $loan->user_id)
			->first();
			return view('contract.condo',$this->_data,compact('loan','co_borrower','teller_user'));
		}
	}

	public function land($id)
	{
		if(!empty($id)){
			// $loan = Loan::find($id);
			$loan = Loan::join('projects','projects.id','=','loans.project_id')
			->join('company_branch','company_branch.id','=','projects.company_id')
			->join('representatives','representatives.id','=','projects.sale_representative_id')
			->where('loans.id',$id)
			->select('loans.*','representatives.name','representatives.gender','representatives.dob','representatives.national_id','representatives.national_issued_date','representatives.phone','representatives.national_front','representatives.national_back','company_branch.logo as logo','company_branch.branch_name','company_branch.branch_name_en')->first();
			
			if(!$loan){
				return redirect()->back();
			}
			$this->_data['client'] = Client::where('id', $loan->client_id)
			->with([
					'clientLoan',
					'general.country',
					'general.province',
					'general.District',
					'general.Commune',
					'general.Village',
					'Address.country.description',
					'Address.province',
					'Address.District',
					'Address.Commune',
					'Address.Village'
				]
			)->where('id', $loan->client_id)->first();
			$co_borrower  = CoBorrower::where('loan_id',$id)->get();
			$teller_user = User::select('users.name as name','users.kh_name')		
			->where('users.id', '=', $loan->user_id)
			->first();
			return view('contract.land',$this->_data,compact('loan','co_borrower','teller_user'));
		}
	}

	public function house($id){
		if(!empty($id)){
			// $loan = Loan::find($id);
			$loan = Loan::join('projects','projects.id','=','loans.project_id')
			->join('company_branch','company_branch.id','=','projects.company_id')
			->join('representatives','representatives.id','=','projects.sale_representative_id')			
			->where('loans.id',$id)
			->select('loans.*','representatives.name','representatives.gender','representatives.dob','representatives.national_id','representatives.national_issued_date','representatives.phone','representatives.national_front','representatives.national_back','company_branch.logo as logo','company_branch.branch_name','company_branch.branch_name_en')->first();

			if(!$loan){
				return redirect()->back();
			}
			$this->_data['client'] = Client::where('id', $loan->client_id)
			->with([
					'clientLoan',
					'general.country',
					'general.province',
					'general.District',
					'general.Commune',
					'general.Village',
					'Address.country.description',
					'Address.province',
					'Address.District',
					'Address.Commune',
					'Address.Village'
				]
			)->where('id', $loan->client_id)->first();
			$co_borrower  = CoBorrower::where('loan_id',$id)->get();
			$teller_user = User::select('users.name as name','users.kh_name')		
			->where('users.id', '=', $loan->user_id)
			->first();
			return view('contract.house', $this->_data,compact('loan','co_borrower','teller_user'));
		}
	}

	public function shop($id){
		if(!empty($id)){
			// $loan = Loan::find($id);
			$loan = Loan::join('projects','projects.id','=','loans.project_id')
			->join('company_branch','company_branch.id','=','projects.company_id')
			->join('representatives','representatives.id','=','projects.sale_representative_id')
			->where('loans.id',$id)
			->select('loans.*','representatives.name','representatives.gender','representatives.dob','representatives.national_id','representatives.national_issued_date','representatives.phone','representatives.national_front','representatives.national_back','company_branch.logo as logo','company_branch.branch_name','company_branch.branch_name_en')->first();
			
			if(!$loan){
				return redirect()->back();
			}
			$this->_data['client'] = Client::where('id', $loan->client_id)
			->with([
					'clientLoan',
					'general.country',
					'general.province',
					'general.District',
					'general.Commune',
					'general.Village',
					'Address.country.description',
					'Address.province',
					'Address.District',
					'Address.Commune',
					'Address.Village'
				]
			)->where('id', $loan->client_id)->first();
			$co_borrower  = CoBorrower::where('loan_id',$id)->get();
			$teller_user = User::select('users.name as name','users.kh_name')		
			->where('users.id', '=', $loan->user_id)
			->first();
			return view('contract.shop', $this->_data,compact('loan','co_borrower','teller_user'));
		}
	}

	public function receipt(){
		$company = CompanyBranch::get()->last();
		return view('receipt.receipt',compact('company'));
	}

	public function printPayOffLetter($id)
	{	
		if(!empty($id)){
			$loan = Loan::join('projects','projects.id','=','loans.project_id')
			->join('company_branch','company_branch.id','=','projects.company_id')
			->join('representatives','representatives.id','=','projects.sale_representative_id')
			->leftjoin('loan_payoff','loan_payoff.loan_id','=','loans.id')
			->where('loans.id',$id)
			->select('loans.*','representatives.name','representatives.gender','representatives.dob','representatives.national_id','representatives.national_issued_date','representatives.phone','representatives.national_front','representatives.national_back','company_branch.logo as logo','company_branch.branch_name','company_branch.branch_name_en','company_branch.id as branch_id','loan_payoff.payoff_date')->first();
			if(!$loan){
				return redirect()->back();
			}
			$this->_data['client'] = Client::where('id', $loan->client_id)
			->with([
					'clientLoan',
					'general.country',
					'general.province',
					'general.District',
					'general.Commune',
					'general.Village',
					'Address.country.description',
					'Address.province',
					'Address.District',
					'Address.Commune',
					'Address.Village'
				]
			)->where('id', $loan->client_id)->first();

			$co_borrower  = CoBorrower::where('loan_id',$id)->get();
			$teller_user = User::select('users.name as name','users.kh_name')		
			->where('users.id', '=', $loan->user_id)
			->first();
			return view('contract.pay_off_letter',$this->_data,compact('loan','co_borrower','teller_user'));
		}
	}
	
}