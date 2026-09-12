<?php
/**
 * Created by PhpStorm.
 * User: N.K
 * Date: 15/05/2021
 * Time: 05:35 PM
 */

namespace App\Models;

class ItemConstant {
    const STATUS_ACTIVE = 1; // status active
    const STATUS_INACTIVE = 0; // status active

    const ITEM_REF_COL_ACTION = 'COL_ACTION'; // Collection Action
    const SUB_ITEM_REF_COL_ACTION_1 = 'COL_ACTION_1'; // Call
    const SUB_ITEM_REF_COL_ACTION_2 = 'COL_ACTION_2'; // SMS 1
    const SUB_ITEM_REF_COL_ACTION_3 = 'COL_ACTION_3'; // SMS 2
    const SUB_ITEM_REF_COL_ACTION_4 = 'COL_ACTION_4'; // SMS 3
    const SUB_ITEM_REF_COL_ACTION_5 = 'COL_ACTION_5'; // SMS >3
    const SUB_ITEM_REF_COL_ACTION_6 = 'COL_ACTION_6'; // Letter 1
    const SUB_ITEM_REF_COL_ACTION_7 = 'COL_ACTION_7'; // Letter 2
    const SUB_ITEM_REF_COL_ACTION_8 = 'COL_ACTION_8'; // Letter 3
    const SUB_ITEM_REF_COL_ACTION_9 = 'COL_ACTION_9'; // Letter >3
    const SUB_ITEM_REF_COL_ACTION_10 = 'COL_ACTION_10'; // Walk-In
    const SUB_ITEM_REF_COL_ACTION_11 = 'COL_ACTION_11'; // Visit

    const ITEM_REF_COL_WHOM = 'COL_WHOM'; // Collection Whom
    const SUB_ITEM_REF_COL_WHOM_1 = 'COL_WHOM_1'; // Borrower
    const SUB_ITEM_REF_COL_WHOM_2 = 'COL_WHOM_2'; // Co-Borrower
    const SUB_ITEM_REF_COL_WHOM_3 = 'COL_WHOM_3'; // Guarantor

    const ITEM_REF_COL_CUS_RESP = 'COL_CUS_RESP'; // Customer Response
    const SUB_ITEM_REF_COL_CUS_RESP_1 = 'COL_CUS_RESP_1'; // Can't contact
    const SUB_ITEM_REF_COL_CUS_RESP_2 = 'COL_CUS_RESP_2'; // Customer Cancel
    const SUB_ITEM_REF_COL_CUS_RESP_3 = 'COL_CUS_RESP_3'; // No answer
    const SUB_ITEM_REF_COL_CUS_RESP_4 = 'COL_CUS_RESP_4'; // No service
    const SUB_ITEM_REF_COL_CUS_RESP_5 = 'COL_CUS_RESP_5'; // Number not in use
    const SUB_ITEM_REF_COL_CUS_RESP_6 = 'COL_CUS_RESP_6'; // Promise to Pay
    const SUB_ITEM_REF_COL_CUS_RESP_7 = 'COL_CUS_RESP_7'; // Request Restructure
    const SUB_ITEM_REF_COL_CUS_RESP_8 = 'COL_CUS_RESP_8'; // Stop to pay
    const SUB_ITEM_REF_COL_CUS_RESP_9 = 'COL_CUS_RESP_9'; // Wrong Number
    const SUB_ITEM_REF_COL_CUS_RESP_10 = 'COL_CUS_RESP_10'; // Other
    const SUB_ITEM_REF_COL_CUS_RESP_11 = 'COL_CUS_RESP_11'; // Request Partial Payment
    const SUB_ITEM_REF_COL_CUS_RESP_12 = 'COL_CUS_RESP_12'; // Request Waive Penalty
    const SUB_ITEM_REF_COL_CUS_RESP_13 = 'COL_CUS_RESP_13'; // Request Partial Payment & Waive Penalty
    const SUB_ITEM_REF_COL_CUS_RESP_14 = 'COL_CUS_RESP_14'; // Request Change Unit
    const SUB_ITEM_REF_COL_CUS_RESP_15 = 'COL_CUS_RESP_15'; // Request Suspend Payment
    const SUB_ITEM_REF_COL_CUS_RESP_16 = 'COL_CUS_RESP_16'; // The Door is Locked
    const SUB_ITEM_REF_COL_CUS_RESP_17 = 'COL_CUS_RESP_17'; // Not meet customer

//    const SUB_ITEM_REF_COL_CUS_RESP_1 = 'COL_CUS_RESP_1'; // Promise to Pay
//    const SUB_ITEM_REF_COL_CUS_RESP_2 = 'COL_CUS_RESP_2'; // Request Restructure
//    const SUB_ITEM_REF_COL_CUS_RESP_3 = 'COL_CUS_RESP_3'; // Stop to pay
//    const SUB_ITEM_REF_COL_CUS_RESP_4 = 'COL_CUS_RESP_4'; // Can't contact
//    const SUB_ITEM_REF_COL_CUS_RESP_5 = 'COL_CUS_RESP_5'; // Wrong Number
//    const SUB_ITEM_REF_COL_CUS_RESP_6 = 'COL_CUS_RESP_6'; // Other
//    const SUB_ITEM_REF_COL_CUS_RESP_7 = 'COL_CUS_RESP_7'; // Customer Cancel
//    const SUB_ITEM_REF_COL_CUS_RESP_8 = 'COL_CUS_RESP_8'; // No answer
//    const SUB_ITEM_REF_COL_CUS_RESP_9 = 'COL_CUS_RESP_9'; // No service
//    const SUB_ITEM_REF_COL_CUS_RESP_10 = 'COL_CUS_RESP_10'; // Number not in use

    const ITEM_REF_COL_CUS_RESP_3 = 'COL_CUS_RESP_3'; // Reason stop to pay
    const SUB_ITEM_REF_COL_CUS_RESP_3_1 = 'COL_CUS_RESP_3_1'; // Quality Construction
    const SUB_ITEM_REF_COL_CUS_RESP_3_2 = 'COL_CUS_RESP_3_2'; // Construction Late
    const SUB_ITEM_REF_COL_CUS_RESP_3_3 = 'COL_CUS_RESP_3_3'; // Construction Deadline

    const ITEM_REF_COL_COMM = 'COL_COMM'; // Comment
    const ITEM_REF_COL_CAP = 'COL_CAP'; // Capacity
    const ITEM_REF_COL_SOLU = 'COL_SOLU'; // Solution
    const ITEM_REF_COL_NEGO_PROG = 'COL_NEGO_PROG'; // Negotiation Progress
    const ITEM_REF_COL_COL_REQ_TYPE = 'COL_REQ_TYPE'; // Request Type

    const ITEM_REF_COL_PAYMENT_STATUS = 'COL_PAY_STA'; // Payment Status
    const SUB_ITEM_REF_COL_PAYMENT_STATUS_1 = 'COL_PAY_STA_1'; // Full Paid
    const SUB_ITEM_REF_COL_PAYMENT_STATUS_2 = 'COL_PAY_STA_2'; // Partial Paid
    const SUB_ITEM_REF_COL_PAYMENT_STATUS_3 = 'COL_PAY_STA_3'; // Not yet Pay
}
