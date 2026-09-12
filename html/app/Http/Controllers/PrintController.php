<?php
/**
 * Created by PhpStorm.
 * User: hengsoheak
 * Date: 8/12/2016
 * Time: 11:10 AM
 */

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\JournalDetail;
use App\Models\slips;
use App\Models\User;
use App\Models\DrawdownAccounts;
use App\Models\JournalRequiry;
use App\Models\CompanyBranch;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\Project;
use App\Models\Loan;

use Request;
use Auth;

class PrintController extends Controller
{
    public function __construct()
    {
        $this->middleware('xss');
//        $this->middleware('auth');
    }

    // public function deposit($slips_id)
    // {
    //     $slips = slips::where('id', $slips_id)->first();

    //     $drawdown = DrawdownAccounts::where('id', $slips->draw_acc_id)->first();
    //     $journalR = JournalRequiry::where('id', $slips->jr_id)->first();
    //     $currecyType = Currency::where('id', $drawdown->currency)->first();
    //     $user = User::where('id', $slips->user_id)->first();

    //     return $this->view('slips.deposit', ['drawdown' => $drawdown, 'journalr' => $journalR, 'currency' => $currecyType, 'user' => $user, 'slips' => $slips]);
    // }

    public function deposit($slips_id){
        $slips = slips::where('slips.id', $slips_id)->first();
        if(empty($slips)){
            return redirect()->back();
        }
        // $branch_code = slips::join('journal_detail','slips.jd_id','=','journal_detail.id')
        //                 ->select('slips.*','journal_detail.branch_code')->where('slips.id', $slips_id)->first()->branch_code;
        // $company_branch = CompanyBranch::where('branch_code',$branch_code)->get()->first();
        // if(empty($company_branch)){
        //     return redirect()->back();
        // }
        $drawdown = DrawdownAccounts::where('id', $slips->draw_acc_id)->first();
        $unit = Unit::find($drawdown->unit_id);
        $unit_type = UnitType::find($unit->unit_type_id);
        $projects_row = Project::find($unit_type->project_id);
        $company_branch = CompanyBranch::find($projects_row->company_id);
        $journalR = JournalRequiry::where('id', $slips->jr_id)->first();
        $currecyType = Currency::where('id', $drawdown->currency)->first();
        $user = User::where('id', $slips->user_id)->first();
        if(empty($company_branch)){
            return redirect()->back();
        }
        $loan=Loan::where('drawdown_acc',$drawdown->account_no)->first();
        return $this->view('slips.receipt', [
                                            'drawdown' => $drawdown,
                                            'journalr' => $journalR,
                                            'currency' => $currecyType,
                                            'user' => $user,
                                            'slips' => $slips,
                                            'company_branch'=>$company_branch,
                                            'projects_row'=>$projects_row,
                                            'unit_type'=>$unit_type,
                                            'unit'=>$unit,
                                            'loan'=>$loan
                                        ]);
    }

    public function withdraw($slips_id)
    {

        $slips = slips::where('id', $slips_id)->first();
        if(empty($slips)){
            return redirect()->back();
        }
        $branch_code = slips::join('journal_detail','slips.jd_id','=','journal_detail.id')
                        ->select('slips.*','journal_detail.branch_code')->where('slips.id', $slips_id)->first()->branch_code;
        $company_branch = CompanyBranch::where('branch_code',$branch_code)->get()->first();
        if(empty($company_branch)){
            return redirect()->back();
        }
        $drawdown = DrawdownAccounts::where('id', $slips->draw_acc_id)->first();
        $unit = Unit::find($drawdown->unit_id);
        $journalR = JournalRequiry::where('id', $slips->jr_id)->first();
        $currecyType = Currency::where('id', $drawdown->currency)->first();
        $user = User::where('id', $slips->user_id)->first();

        return $this->view('slips.withdraw', ['drawdown' => $drawdown, 'journalr' => $journalR, 'currency' => $currecyType, 'user' => $user, 'slips' => $slips,'company_branch'=>$company_branch,'unit'=>$unit]);
    }

    public function setBeforeFilters($beforeFilters)
    {
        $this->beforeFilters = $beforeFilters;
    } function debit($id)
    {

        $journalRequiry = JournalRequiry::with(['detail', 'detail.account'])->where('id', $id)->get();
        return $this->view('slips.debit', ['jouralRequiry'=>$journalRequiry]);
    }

    public  function credit($id)
    {

        $JournalDetail = JournalRequiry::with(['detail', 'detail.account'])->where('id', $id)->get();
        return $this->view('slips.credit',['credit'=>$JournalDetail]);
    }
}
