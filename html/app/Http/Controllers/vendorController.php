<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 5/25/2015
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalDetail;
use App\Models\JournalRequiry;
use App\Models\Vendor;
use App\Models\AccountCustomer;
use Request;
use Auth;
use Image;

class vendorController extends Controller
{
    public function __construct()
    {
//        $this->middleware('xss');
//        $this->middleware('auth');
    }

    final function index()
    {
        $data = Vendor::with(['JournalRequiryWhere', 'JournalRequiryWhere.detail'])->get();
        return $this->view('accounting.vendor.vendor', ['vendor'=>$data]);
    }

    final function get_add_vendor_act($id = null)
    {

        if (!empty($id)) {

            $vedor['vendor'] = Vendor::where('id', $id)->first();
            return $this->view('accounting.vendor.add_and_edit', $vedor);
        }
        return $this->view('accounting.vendor.add_and_edit');
    }

    final function post_add_vendor_act($id)
    {

        if (Request::ajax()) {

            $res = false;
            $vendor = new Vendor();
            if (!empty($id)) {
                $vendor = Vendor::find($id);
            }
            $vendor->company_name = Request::input('company_name');
            $vendor->fname = Request::input('fname');
            $vendor->lname = Request::input('lname');
            $vendor->job_title = Request::input('job_title');
            $vendor->main_phone = Request::input('main_phone');
            $vendor->work_phone = Request::input('work_phone');
            $vendor->mobile = Request::input('mobile');
            $vendor->fax = Request::input('fax');
            $vendor->main_email = Request::input('main_email');
            $vendor->website = Request::input('website');
            $vendor->address = Request::input('address');
            if ($vendor->save()) {
                $res['save'] = true;
            }
            return $res;
        }
    }

    final function delet_vendor_act($id)
    {

        if (Request::ajax()) {

            $res = false;
            $vender = Vendor::find($id);
            $status = 1;
            if((int)$vender->status == 1) {
                $status = 0;
            }
            $vender->status = $status;
            if ($vender->save()) {
                $res = true;
            }
            return ['delete' => $res, 'status'=>$vender->status];
        }
    }

    final function get_vendor_info($id)
    {
        $data = JournalRequiry::with(['detail', 'detail.account', 'vendor'])->where('ref_name_id', $id)->where('ref_name_type', 1)->get();

        if(count($data) == 0){
            $data = Vendor::with(['JournalRequiryWhere'])->where('id',$id)->get();
        }
        foreach($data as $jr){
            $jrId[] = $jr->id;
        }

        $journalDetail = JournalDetail::with(['journal','account'])->whereIn('journal_id',$jrId)->where('debit','!=',0)->get();
        return $this->view('accounting.vendor.vendor_info', ['dataa'=>$data, 'data'=>$journalDetail]);
    }

    /*
     * Acc customer*/

    final function account_customer()
    {
        $data['acc_customer'] = AccountCustomer::with(['JournalRequiryWhere', 'JournalRequiryWhere.detail'])->get();
        return $this->view('accounting.acc_customer.acc_customer', $data);
    }

    final function get_add_customer_act($id = null)
    {
        if (Request::ajax()) {
            $acc_cust = [];
            if (!empty($id)) {

                $acc_cust['acc_customer'] = AccountCustomer::where('id', $id)->first();
            }
            return $this->view('accounting.acc_customer.add_and_edit_cust', $acc_cust);
        }
    }

    final function post_add_edit_customer_act($id)
    {
        if (Request::ajax()) {

            $res = false;
            $vendor = new AccountCustomer();
            if (!empty($id)) {
                $vendor = AccountCustomer::find($id);
            }
            $vendor->company_name = Request::input('company_name');
            $vendor->fname = Request::input('fname');
            $vendor->lname = Request::input('lname');
            $vendor->job_title = Request::input('job_title');
            $vendor->main_phone = Request::input('main_phone');
            $vendor->work_phone = Request::input('work_phone');
            $vendor->mobile = Request::input('mobile');
            $vendor->fax = Request::input('fax');
            $vendor->main_email = Request::input('main_email');
            $vendor->website = Request::input('website');
            $vendor->address = Request::input('address');
            if ($vendor->save()) {
                $res['save'] = true;
            }
            return $res;
        }
    }

    final function delet_acc_customer($id)
    {

        if (Request::ajax()) {

            $res = false;
            $customer = AccountCustomer::find($id);
            $status = 1;
            if((int)$customer->status == 1){
                $status = 0;
            }
            $customer->status = $status;
            if ($customer->save()) {
                $res = true;
            }
            return ['delete' => $res, 'status'=>$customer->status];
        }
    }

    final function get_acc_customer_info($id, $from = null, $to = null)
    {
        if (Request::ajax()) {

            $journal_req = JournalRequiry::with(['detail', 'detail.account'])->with('AccountCustomer')->where('ref_name_id', $id)->where('ref_name_type', 2)
                ->whereHas('AccountCustomer', function($where)use($id){
                $where->where('id', $id);
            })->get();
            $account = AccountCustomer::where('id',$id)->get();
            return $this->view('accounting.acc_customer.customer_info', ['journal_req' => $journal_req,'account'=>$account]);
        }
    }

}


