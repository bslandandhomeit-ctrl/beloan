<?php
/**
 * Created by PhpStorm.
 * User: N.K
 * Date: 13/05/2021
 * Time: 10:09 AM
 */

namespace App\Models;


use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CollectionAction extends Model {

    protected $table = 'trn_collection_action';

//$action, $phone_number, $whom, $action_date,
//$customer_response, $reason_note, $remark, $promise_pay_date,
//$promise_pay_date, $stop_pay_reason
    public static function createModel($data) {
        $object = new self();
        $object->action = $data['action'];
        if (isset($data['phone_number'])) {
            $object->phone_number = $data['phone_number'];
        }
        if (isset($data['new_phone_number'])) {
            $object->new_phone_number = $data['new_phone_number'];
        }
        $object->whom = $data['whom'];
        $object->action_date = DateTime::createFromFormat('Y-m-d h:i:s A', $data['action_date'])->format('Y-m-d H:i:s');
//        $object->remark = $data['remark'];
        $object->master_loan_id = $data['master_loan_id'];
        $object->loan_id = $data['loan_id'];

        if (isset($data['customer_response']) && !empty($data['customer_response'])) {
            // Todo: need to check negotiation display field
//            $object->negotiation = $data['negotiation'];

            $customer_response = $data['customer_response'];
            $object->customer_response = $customer_response;
            if ($object->action == ItemConstant::SUB_ITEM_REF_COL_ACTION_1) { // Action Call
                if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8) {
                    // Todo: - Stop to Pay => Show Stop Pay Reason, Solution, Reason note.
                    $object->stop_pay_reason = $data['stop_pay_reason'];
                    $object->solution = $data['solution'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6) {
                    // Todo: - Promise to pay => Promise date, Promise Amount, Solution, Reason note.
                    $object->promise_pay_date = DateTime::createFromFormat('Y-m-d', $data['promise_pay_date'])->format('Y-m-d H:i:s');
                    $object->promise_amount = $data['promise_amount'];
                    $object->solution = $data['solution'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_7) {
                    // Todo: - Request Restructure​ => Request Type, # of Month Request, Amount Request, Reason note.
                    $object->request_type = $data['request_type'];
                    $object->month_request = $data['month_request'];
                    $object->amount_request = $data['amount_request'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10) {
                    // Todo: - Other => Comment, Capacity, Progress, Solution, Reason Note
                    $object->comment = $data['comment'];
                    $object->capacity = $data['capacity'];
                    $object->construction_progress_note = $data['construction_progress_note'];
                    $object->solution = $data['solution'];
                } else {
                    // Todo: - Can't contact, No answer, customer cancel, No service, Number not in use, Wrong Number
                    //              =>  Solution, Reason note.
                    $object->solution = $data['solution'];
                }
                $object->reason_note = $data['reason_note'];
            } else if ($object->action == ItemConstant::SUB_ITEM_REF_COL_ACTION_10) { // Action Walk-In
                if (
                    $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_11 ||
                    $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_12 ||
                    $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_13 ||
                    $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_14 ||
                    $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_7
                ) {
                    // Todo: (Request Partial Payment, Request Waive Penalty, Request Partial Payment & Waive Penalty
                    //        Request Restructure, Request Change Unit): Show Promise Date, Promise Amount, Comment, Capacity to pay,
                    //        Construction Progress, Solution, Negotiation Progress, Reason note
                    $object->promise_pay_date = DateTime::createFromFormat('Y-m-d', $data['promise_pay_date'])->format('Y-m-d H:i:s');
                    $object->promise_amount = $data['promise_amount'];
                    $object->comment = $data['comment'];
                    $object->capacity = $data['capacity'];
                    $object->construction_progress_note = $data['construction_progress_note'];
                    $object->solution = $data['solution'];
                    $object->negotiation = $data['negotiation'];
                    $object->reason_note = $data['reason_note'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8) {
                    // Todo: Stop to Pay => Show Request Type, # of Month Request, Amount Request
                    $object->request_type = $data['request_type'];
                    $object->month_request = $data['month_request'];
                    $object->amount_request = $data['amount_request'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_15) {
                    // Todo: Request Suspend Payment => Show Construction Progress, # of Month Request, Reason note.
                    $object->construction_progress_note = $data['construction_progress_note'];
                    $object->month_request = $data['month_request'];
                    $object->reason_note = $data['reason_note'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_2 || $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10) {
                    // Todo: Customer Cancel, Other => Show only Reason note
                    $object->reason_note = $data['reason_note'];
                }
            } else if ($object->action == ItemConstant::SUB_ITEM_REF_COL_ACTION_11) { // Action Visit
                if (
                    $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_16 ||
                    $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_17
                ) {
                    // Todo: The Door is Locked, Not meet customer: Show Solution, Reason note.
                    $object->solution = $data['solution'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6) {
                    // Todo: Promise to Pay: Promise Date, Promise Amount, Capacity to Pay, Reason note.
                    $object->promise_pay_date = DateTime::createFromFormat('Y-m-d', $data['promise_pay_date'])->format('Y-m-d H:i:s');
                    $object->promise_amount = $data['promise_amount'];
                    $object->capacity = $data['capacity'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8) {
                    // Todo: Stop to Pay : Capacity to Pay, Comment, Solution, Reason note
                    $object->capacity = $data['capacity'];
                    $object->comment = $data['comment'];
                    $object->solution = $data['solution'];
                } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_2 || $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10) {
                    // Todo: Customer Cancel, Other : Solution, Reason note.
                    $object->solution = $data['solution'];
                }
                $object->reason_note = $data['reason_note'];
            }

//            if ($data['customer_response'] == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6) {
//                $object->promise_pay_date = DateTime::createFromFormat('Y-m-d', $data['promise_pay_date'])->format('Y-m-d H:i:s');
//            } else if ($data['customer_response'] == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8) {
//                $object->stop_pay_reason = $data['stop_pay_reason'];
//            }
        }
        return $object;
    }

    public function saveUpdate() {
        self::saveOrUpdate($this);
    }
    public static function saveOrUpdate($object) {
        if (!$object->id) {
            $object->created_by = Auth::user()->username;
            $object->created_at = new DateTime();
        } else {
            $object->updated_by = Auth::user()->username;
            $object->updated_at = new DateTime();
        }
        $object->save();
    }

    public static function getActiveQuery($status = ItemConstant::STATUS_ACTIVE) {
        return self::where('status', $status);
    }

    public function actionRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'action');
    }
    public function whomRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'whom');
    }
    public function customerResponseRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'customer_response');
    }
    public function stopPayReasonRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'stop_pay_reason');
    }
    public function solutionRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'solution');
    }
    public function requestTypeRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'request_type');
    }
    public function negotiationRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'negotiation');
    }
    public function commentRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'comment');
    }
    public function capacityRef() {
        return $this->hasOne(ItemRefSub::class, 'code', 'capacity');
    }

    public static function getListActionByLoanId($loan_id = 0, $limit = 1000) {
        $query = self::getActiveQuery()->with('actionRef', 'whomRef', 'customerResponseRef', 'stopPayReasonRef',
                'solutionRef', 'requestTypeRef', 'negotiationRef', 'commentRef', 'capacityRef')
            ->orderBy('action_date', 'DESC');
        $query->where('loan_id', $loan_id)->limit($limit);
        $result = $query->get();
        return $result;
    }
    public static function getListActionByMasterLoanId($master_loan_id = 0, $limit = 1000) {
        $query = self::getActiveQuery()->with('actionRef', 'whomRef', 'customerResponseRef', 'stopPayReasonRef')
            ->orderBy('action_date', 'DESC');
        $query->where('master_loan_id', $master_loan_id)->limit($limit);
        $result = $query->get();
        return $result;
    }
    public static function getLastActionNegotiationProgressByLoanId($loan_id = 0) {
        $query = self::getActiveQuery()
            ->orderBy('action_date', 'DESC');
        $query->where('loan_id', $loan_id)
            ->whereRaw('negotiation <> "" AND negotiation IS NOT NULL')
            ->limit(1);
        return $query->first();
    }

    public function toArray()
    {
        $result = parent::toArray();
        $result['action_date_str'] = date('Y-m-d h:i:s A', strtotime($result['action_date']));
        if ($result['promise_pay_date']) {
            $result['promise_pay_date_str'] = date('Y-m-d', strtotime($result['promise_pay_date']));
        }
//        $result['action'] = $this->action()->toArray();
        return $result; // TODO: Change the autogenerated stub
    }

} 
