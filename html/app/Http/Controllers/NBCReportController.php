<?php
/**
 * Created by PhpStorm.
 * User: SOTheary
 * Date: 6/27/2016
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Request;
//use App\Http\Requests\Request;
use Auth;
use Image;

class NBCReportController extends Controller
{
    public function  __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }
    public function getBalanceSheet()
    {
        return $this->view('nbc_reports.bs_mfi01');
    }
    public function getBalanceSheetPl()
    {
        return $this->view('nbc_reports.pl_mfi02');
    }
    public function getNetOpenPosition()
    {
        return $this->view('nbc_reports.net_open_position');
    }

    public function getDenominator()
    {
        return $this->view('nbc_reports.denominator');
    }

    public function getSolvency()
    {
        return $this->view('nbc_reports.solvency');
    }
    public function getSourceFinancing()
    {
        return $this->view('nbc_reports.source_financing');
    }

    public function getCalculation()
    {
        return $this->view('nbc_reports.calculation');
    }

    public function getNgosMicrofinance()
    {
        return $this->view('nbc_reports.ngos_microfinance');
    }

    public function getListInfo()
    {
        return $this->view('nbc_reports.list_info');
    }

    public function getLiquidity()
    {
        return $this->view('nbc_reports.liquidity');
    }

    public function getListLargeExposure()
    {
        return $this->view('nbc_reports.list_large_exposure');
    }

    public function getListLoan()
    {
        return $this->view('nbc_reports.nbc_list_loan');
    }

    public function getLoanClassification()
    {
        return $this->view('nbc_reports.loan_classification');
    }

    public function getDepositBreakdown()
    {
        return $this->view('nbc_reports.deposit_breakdown');
    }

    public function getLoanBreakdown()
    {
        return $this->view('nbc_reports.loan_breakdown');
    }

    public function getLoanBreakdownCategory()
    {
        return $this->view('nbc_reports.loan_breakdown_category');
    }

    public function getBreakDownDeposit()
    {
        return $this->view('nbc_reports.breakdown_deposit');
    }

    public function getOffBalanch()
    {
        return $this->view('nbc_reports.off_balanch');
    }
}