<?php
$sidebar = [
        [
                'css' => 'fa-user',
                'title' => 'sb_client',
                'route' => 'client',
                'sub' => [
                        ['route' => 'add_client', 'title' => 'sb_add_client'],//, 'param' => [0]
                        ['route' => 'list_client', 'title' => 'sb_client_summary', 'param' => []],
                        ['route' => 'drawdown_account', 'title' => 'sb_drawdown_account', 'param' => []],
                ]
        ],
        //[
                //'css' => 'fa-smile-o',
                //'title' => 'sb_dealer',
                //'route' => array('dealer', 'bank'),
                //'sub' => [
                        //['route' => 'add_dealer', 'title' => 'sb_add_dealer', 'param' => []],
                        //['route' => 'list_dealer', 'title' => 'sb_dealer_summary', 'param' => []],
                        //['route' => 'add_bank', 'title' => 'sb_add_bank', 'param' => []],
                        //['route' => 'list_bank', 'title' => 'sb_bank_list', 'param' => []]
                //]
        //],
        [
                'css' => 'fa-smile-o',
                'title' => 'sb_project',
                'route' => array('project', 'representative'),
                'sub' => [
                        ['route' => 'add_project', 'title' => 'sb_add_project', 'param' => []],
                        ['route' => 'list_project', 'title' => 'sb_project_summary', 'param' => []],
                        ['route' => 'add_representative', 'title' => 'sb_add_representative', 'param' => []],
                        ['route' => 'list_representative', 'title' => 'sb_representative_summary', 'param' => []],
                ]
        ],
        [
                'css' => 'fa-shopping-cart',
                'title' => 'sb_product',
                'route' => array('product','unit_type','unit','payment-option','promotion'),
                'sub' => [
                       
                        ['route' => 'add_unit_type', 'title' => 'sb_add_unit_type', 'param' => []],
                        ['route' => 'list_unit_type', 'title' => 'sb_unit_type_summary', 'param' => []],
                        ['route' => 'add_unit', 'title' => 'sb_add_unit', 'param' => []],
                        ['route' => 'list_unit', 'title' => 'sb_unit_summary', 'param' => []],
                        ['route' => 'add_payment_option','title' => 'sb_add_payment_option','param' => []],
                        ['route' => 'list_payment_option','title' => 'sb_payment_option_summary','param' => []],
                        ['route' => 'add_promotion','title' => 'sb_add_promotion','param' => []],
                        ['route' => 'list_promotion','title' => 'sb_promotion_summary','param' => []],
                        
                ]
        ],
        [
                'css' => 'fa-shopping-cart',
                'title' => 'sb_sale',
                'route' => array('sale'),
                'sub' => [                       
                        ['route' => 'list_sale', 'title' => 'sb_list_sale', 'param' => []],  
                        ['route' => 'add_sale', 'title' => 'sb_new_sale', 'param' => []],  
                        ['route' => 'change_unit', 'title' => 'sb_change_unit', 'param' => []],  
                        ['route' => 'saleperson_list', 'title' => 'sb_saleperson', 'param' => []],
                        ['route' => 'transfer_inter_project', 'title' => 'sb_transfer_inter_project', 'param' => []],  
                        
                                          
                ]
        ],
        // [
        //         'css' => 'fa-shopping-cart',
        //         'title' => 'sb_invoice',
        //         'route' => array('invoice'),
        //         'sub' => [                       
        //                 ['route' => 'list_invoice', 'title' => 'sb_list_invoice', 'param' => []],                    
        //         ]
        // ],
        [
                'css' => 'fa-bar-chart-o',
                'title' => 'sb_sale_report',
                'route' => array('sale'),
                'sub' => [                        
                        ['route' => 'sale_report_deposit', 'title' => 'sb_list_sale_deposit', 'param' => []],
                        ['route' => 'sale_report_detail', 'title' => 'sb_list_sale_order_report', 'param' => []]
                        
                ]
        ],
        [
                'css' => 'fa-users',
                'title' => 'sb_user',
                'route' => 'user',
                'sub' => [
                        ['route' => 'add_user', 'title' => 'sb_add_user', 'param' => []],
                        ['route' => 'all_user', 'title' => 'sb_user_summary', 'param' => []],
                        ['route' => 'add_role', 'title' => 'sb_add_role', 'param' => []],
                        ['route' => 'add_saleperson', 'title' => 'sb_add_saleperson', 'param' => []],
                        ['route' => 'list_saleperson', 'title' => 'sb_saleperson_summary', 'param' => []]
                ]
        ],
        [
                'css' => 'fa-usd',
                'title' => 'sb_loan_management',
                'route' => 'loan',
                'route' => 'loans',
                'sub' => [
                        ['route' => 'apply_loan', 'title' => 'sb_loans_apply', 'param' => []],
                        ['route' => 'add_loan_repayment', 'title' => 'sb_add_loan_repayment', 'param' => [0]],
                        ['route' => 'customer_statement_summary', 'title' => 'sb_customer_statement_summary', 'param' => []],
                        ['route' => 'loan_list', 'title' => 'sb_loan_payment_summary', 'param' => []],
                        ['route' => 'loan_verify', 'title' => 'sb_loan_verify_summary', 'param' => []],
                        ['route' => 'loan_to_reject', 'title' => 'sb_loan_send_back_summary', 'param' => []],
                        ['route' => 'waiting_to_approve', 'title' => 'sb_loan_approve_summary', 'param' => []],
                        ['route' => 'reschedule_to_approve', 'title' => 'reschedule_to_approve', 'param' => []],
                        ['route' => 'loan_status', 'title' => 'sb_loan_status_summary', 'param' => []],
                        ['route' => 'repayment_summary', 'title' => 'sb_schedule_monitor', 'param' => []],
                        ['route' => 'loan_pay_today', 'title' => 'sb_todo_payment_for_today', 'param' => []],
                        ['route' => 'unauthorized_list', 'title' => 'sb_unauthorized_list', 'param' => []],
                        ['route' => 'approval_list', 'title' => 'sb_approved_list', 'param' => []],
                        ['route' => 'loan_trans', 'title' => 'sb_transaction_summary', 'param' => []],
                		//['route' => 'draft_loan', 'title' => 'sb_draft_loan', 'param' => []],
                        ['route' => 'client_loan_account', 'title' => 'sb_client_loan_account', 'param' => []],
                        ['route' => 'drawdown_account_detail', 'title' => 'sb_drawdown_account_detail', 'param' => []],
                        ['route' => 'getRepaymentDraft','title'=>'sb_loan_repayment_draft','param'=>[]],
                        ['route' => 'co_performance','title'=>'sb_co_performance','param'=>[]],
                        ['route' => 'getLoanCo','title'=>'sb_loan_co_detail','param'=>[]],
                        ['route' => 'schedule_eir','title'=>'sb_schedule_eir','param'=>[]],

                ]
        ],
        [
                'css' => 'fa-usd',
                'title' => 'sb_loan_recovery',
                'route' => 'loan_recovery',
                'sub' => [
                        ['route' => 'loan_recovery_summary', 'title' => 'sb_summary', 'param' => []],
                        ['route' => 'loan_recovery_action_plan', 'title' => 'sb_action_plan', 'param' => []]
                ]
        ],
        [
                'css' => 'fa-usd',
                'title' => 'sb_sale_commission',                
                'route' => array('commission'),
                'sub' => [
                        ['route' => 'list_sale_commission','title'=>'sb_sale_commission','param'=>[]],
                        ['route' => 'list_prev_commission','title'=>'sb_prev_commission','param'=>[]],
                        ['route' => 'sale_commission_withdraw_list','title'=>'sb_sale_commission_withdraw_list','param'=>[]],
                        ['route' => 'sale_commission_history_list','title'=>'sb_sale_commission_history_list','param'=>[]],
                        ['route' => 'commission_rate_list','title'=>'sb_commission_rate','param'=>[]],
                ]
        ],
        [
                'css' => 'fa-usd',
                'title' => 'sb_property',                
                'route' => array('property'),
                'sub' => [
                        ['route' => 'property_payment_term','title'=>'sb_property_payment_term','param'=>[]],
                        ['route' => 'property_service','title'=>'sb_property_service','param'=>[]],
                        ['route' => 'customer_init','title'=>'sb_list_customer_init','param'=>[]],
                        ['route' => 'customer_actual_payment','title'=>'sb_list_customer_actual','param'=>[]],
                        ['route' => 'property_check_list','title'=>'sb_property_check_list','param'=>[]],
                        ['route' => 'property_sendback_list','title'=>'sb_property_sendback_list','param'=>[]],
                        ['route' => 'property_invoice_list','title'=>'sb_property_invoice_list','param'=>[]],
                        ['route' => 'wallet_list','title'=>'sb_wallet_list','param'=>[]],
                        
                        
                ]
        ],
        [
                'css' => 'fa-usd',
                'title' => 'sb_collateral',
                'route' => 'home',
                'sub' => [
                        ['route' => 'home','title'=>'sb_collateral','param'=>[]],
                ]
        ],
        [
                'css' => 'fa-signal',
                'title' => 'sb_credit_classification',
                'route' => 'credits',
                'sub' => [
                        ['route' => 'creditSummary', 'title' => 'sb_credit_summary', 'param' => []],
                ]
        ],
        [
                'css' => 'fa fa-film',
                'title' => 'sb_teller_management',
                'route' => 'teller',
                'sub' => [
                        ['route' => 'chiefofteller', 'title' => 'sb_till_acc_sum', 'param' => []],
                        ['route' => 'telloperation', 'title' => 'sb_till_operation', 'param' => []],
                        ['route' => 'transaction', 'title' => 'sb_tran_sum', 'param' => []],
                        ['route' => 'currency_exchange', 'title' => 'sb_currency_exchange', 'param' => []],
                        ['route' => 'list_currency_exchange', 'title' => 'sb_currency_exchange_list', 'param' => []],
                        ['route' => 'teller_receipt_detail', 'title' => 'sb_teller_rec_detail', 'param' => []],
                        ['route' => 'teller_receipt_summary', 'title' => 'sb_teller_rec_sum', 'param' => []],
                ]
        ],
        [
                'css' => 'fa fa-money',
                'title' => 'sb_bcash',
                'route' => 'teller',
                'sub' => [
                        ['route' => 'chiefofteller', 'title' => 'sb_till_acc_sum', 'param' => []],
                        ['route' => 'telloperation', 'title' => 'sb_till_operation', 'param' => []],
                        ['route' => 'transaction', 'title' => 'sb_tran_sum', 'param' => []],
                        ['route' => 'currency_exchange', 'title' => 'sb_currency_exchange', 'param' => []],
                        ['route' => 'list_currency_exchange', 'title' => 'sb_currency_exchange_list', 'param' => []],
                        ['route' => 'teller_receipt_detail', 'title' => 'sb_teller_rec_detail', 'param' => []],
                        ['route' => 'teller_receipt_summary', 'title' => 'sb_teller_rec_sum', 'param' => []],
                ]
        ],[
                'css' => 'fa-bar-chart-o',
                'title' => 'sb_report',
                'route' => 'reports',
                'sub' => [
                        ['route' => 'rpt_repayment_plan', 'title' => 'sb_repayment_plan', 'param' => []],
                        ['route' => 'rpt_profitloss', 'title' => 'sb_profit_loss', 'param' => []],
                        ['route' => 'rpt_income_statement', 'title' => 'sb_income_statement', 'param' => []],
                        ['route' => 'rpt_balance_sheet', 'title' => 'sb_balance_sheet', 'param' => []],
                        ['route' => 'rpt_collection', 'title' => 'sb_cash_collection', 'param' => []],
                        ['route' => 'rpt_cash_flow', 'title' => 'sb_cash_flow', 'param' => []],
                        ['route' => 'rpt_cash_deposit', 'title' => 'sb_cash_deposit', 'param' => []],
                        ['route' => 'loan_repayment_report', 'title' => 'sb_loan_repayment', 'param' => []],
                        ['route' => 'irregular_repayment_report', 'title' => 'sb_irregular_repayment', 'param' => null],
                        ['route' => 'rpt_disbursement', 'title' => 'sb_disbursement_report', 'param' => []],
                        ['route' => 'rpt_payoff', 'title' => 'sb_payoff_loans', 'param' => []],
                        ['route' => 'rpt_writeoff', 'title' => 'sb_write-off_loans', 'param' => []],
                        ['route' => 'rpt_closed', 'title' => 'sb_closed_loans', 'param' => []],
                        ['route' => 'rpt_completed', 'title' => 'sb_completed_loans', 'param' => []],
                        ['route' => 'rpt_rejected', 'title' => 'sb_rejected_loans', 'param' => []],
                        ['route' => 'reschedule_repayment_report', 'title' => 'sb_reschedule_loans', 'param' => []],
                        ['route' => 'rpt_loan_collection', 'title' => 'sb_loan_collection_summary', 'param' => []],
                        ['route' => 'rpt_loan_collection_detail', 'title' => 'sb_loan_collection_detail', 'param' => []],
                        ['route' => 'rpt_loan_master_list', 'title' => 'sb_loan_master_list', 'param' => []],   
                        ['route' => 'rpt_loan_detail_list', 'title' => 'sb_loan_detail_list', 'param' => []],                       
                        ['route' => 'rpt_loan_transfer_clients', 'title' => 'sb_loan_transfer_clients', 'param' => []],
                        ['route' => 'rpt_loan_restructure_list', 'title' => 'sb_loan_restructure', 'param' => []],
                        ['route' => 'rpt_loan_change_unit_list', 'title' => 'sb_loan_change_unit', 'param' => []],   
                        ['route' => 'rpt_arrears', 'title' => 'sb_arrears_report', 'param' => []],
                        ['route' => 'parc_report', 'title' => 'sb_parc_report', 'param' => []],
                        ['route' => 'company_drawdown_account', 'title' => 'sb_company_drawdown_account', 'param' => []],
                        ['route' => 'posting_transaction', 'title' => 'sb_posting_transaction', 'param' => []],

                        
                        
                ]
        ],
        //[
        //    'css' => 'fa-clipboard',
        //    'title' => 'sb_loan_recovery',
        //    'route' => 'loan_recovery',
        //    'sub' => [
    //                ['route' => 'acc_add_account', 'title' => 'sb_summary', 'param' => []],
    //                ['route' => 'acc_add_account', 'title' => 'sb_action_plan', 'param' => []]
       //         ['route' => 'loan_recovery_summary', 'title' => 'sb_summary', 'param' => []],
        //        ['route' => 'loan_recovery_action_plan', 'title' => 'sb_action_plan', 'param' => []]
         //   ]
        //],
        [
                'css' => 'fa-sitemap',
                'title' => 'sb_accounting',
                'route' => 'accounting',
                'sub' => [
                        ['route' => 'acc_add_account', 'title' => 'sb_add_account', 'param' => []],
                        ['route' => 'add_client_loan_account', 'title' => 'sb_add_customer_account', 'param' => [0]],
                        ['route' => 'client_loan_account', 'title' => 'sb_client_loan_account', 'param' => []],
                        ['route' => 'chart_of_accounts', 'title' => 'sb_chart_of_account', 'param' => []],
                        ['route' => 'add_journal', 'title' => 'sb_add_journal', 'param' => [0]],
                        ['route' => 'transaction_code', 'title' => 'sb_JournalSpecific', 'param' => []],

                        ['route' => 'view_journal', 'title' => 'sb_journal_history', 'param' => []],
                        ['route' => 'list_loan', 'title' => 'sb_accrued_loan_list', 'param' => []],
                        ['route' => 'trail_balance', 'title' => 'sb_trial_balance', 'param' => []],
                        ['route' => 'balance_sheet_account', 'title' => 'sb_balance_sheet_account', 'param' => []],
                        ['route' => 'rpt_is', 'title' => 'sb_income_statement', 'param' => []],
                        //['route' => 'add_currency', 'title' => 'sb_add_currency', 'param' => []],
                        ['route' => 'account_his', 'title' => 'sb_account_his', 'param' => []],

                        ['route' => 'vendor', 'title' => 'sb_vendor', 'param' => []],
                        ['route' => 'acc_customer', 'title' => 'sb_acc_customer', 'param' => []],

                        ['route' => 'getGlReport', 'title' => 'sb_gl_report', 'param' => []],
                        ['route' => 'filter_journal', 'title' => 'sb_journal_filter', 'param' => []]

                ]
        ],
        //[
        //        'css' => 'fa-sitemap',
        //        'title' => 'sb_master_report',
        //        'sub'=>[
        //                ['route' => 'master_report', 'title' => 'sb_get_report', 'param' => []], 
        //        ]
                

        //],

        [
                'css' => 'fa-sitemap',
                'title' => 'sb_nbc_report',
                'route' => 'nbc_report',
                'sub' => [
                        ['route' => 'bs_mfi01', 'title' => 'sb_BS_MFI01', 'param' => []],
                        ['route' => 'pl_mfi02', 'title' => 'sb_PL_MFI02', 'param' => []],
                        ['route' => 'net_open_position', 'title' => 'sb_net_open_position', 'param' => []],
                        ['route' => 'denominator', 'title' => 'sb_denominator', 'param' => []],
                        ['route' => 'solvency', 'title' => 'sb_solvency', 'param' => []],
                        ['route' => 'source_financing', 'title' => 'sb_source_financing', 'param' => []],
                        ['route' => 'calculation', 'title' => 'sb_calculation', 'param' => []],
                        ['route' => 'ngos_microfinance', 'title' => 'sb_ngos_microfinance', 'param' => []],
                        ['route' => 'list_info', 'title' => 'sb_list_info', 'param' => []],
                        ['route' => 'liquidity', 'title' => 'sb_liquidity', 'param' => []],
                        ['route' => 'list_large_exposure', 'title' => 'sb_list_large_exposure', 'param' => []],
                        ['route' => 'nbc_list_loan', 'title' => 'sb_nbc_list_loan', 'param' => []],
                        ['route' => 'loan_classification', 'title' => 'sb_loan_classification', 'param' => []],
                        ['route' => 'deposit_breakdown', 'title' => 'sb_deposit_breakdown', 'param' => []],
                        ['route' => 'loan_breakdown', 'title' => 'sb_loan_breakdown', 'param' => []],
                        ['route' => 'loan_breakdown_category', 'title' => 'sb_loan_breakdown_category', 'param' => []],
                        ['route' => 'breakdown_deposit', 'title' => 'sb_breakdown_deposit', 'param' => []],
                        ['route' => 'off_balanch', 'title' => 'sb_off_balanch', 'param' => []],
                ]
        ],
        [
                'css' => 'fa-suitcase',
                'title' => 'sb_company',
                'route' => 'company',
                'sub' => [
                        ['route' => 'add_branch', 'title' => 'sb_add_branch', 'param' => []],
                        ['route' => 'company_branch', 'title' => 'sb_branch_summary', 'param' => []]
                ]
        ],
        [
                'css' => 'fa fa-h-square',
                'title' => 'sb_hr_management',
                'route' => 'hr_management',
                'sub' => [
                        ['route' => 'add_staff', 'title' => 'sb_add_new_staff', 'param' => []],
                        ['route' => 'staff_summary', 'title' => 'sb_summary_staff', 'param' => []]
                ]
        ],      
        [
                'css' => 'fa-cogs',
                'title' => 'sb_setting',
                'route' => 'setting',
                'sub' => [
                        ['route' => 'date_holiday', 'title' => 'sb_holiday_management', 'param' => []],
                		['route' => 'setting', 'title' => 'sb_setting', 'param' => []],
                        ['route' => 'list_unittype_config', 'title' => 'sb_unittype_config', 'param' => []],
                ]
        ],
        [
                'css' => 'fa fa-adn',
                'title' => 'sb_administration',
                'route' => 'administration',
                'sub' => [
                        ['route' => 'list_administration', 'title' => 'sb_end_of_date', 'param' => []],
                        ['route' => 'export_db', 'title' => 'sb_export_db', 'param' => []],
                        //['route' => 'export_data', 'title' => 'sb_export_data', 'param' => []],
                        ['route' => 'set_currency_rate', 'title' => 'sb_set_currency_exchange', 'param' => []],
                        ['route' => 'list_currency_rate', 'title' => 'sb_list_currency_rate', 'param' => []],
                        ['route' => 'add_currencie', 'title' => 'sb_add_currencies', 'param' => []],
                        ['route' => 'list_currencie', 'title' => 'sb_list_currencies', 'param' => []],
                        ['route' => 'CBC_index', 'title' => 'CBC Report', 'param' => []],
                ]
        ],
        
        //[
        //        'css' => 'fa-user',
        //        'title' => 'sb_asset',
        //        'route' => 'asset',
         //       'sub' => [
        //                ['route' => 'asset_index', 'title' => 'sb_asset_summary', 'param' => []],
        //                ['route' => 'fa_detail', 'title' => 'sb_fa_detail', 'param' => []],
        //                ['route' => 'depreciation_summary', 'title' => 'sb_depreciation_summary', 'param' => []]
        //        ]
        //]
        //,[
        //        'css' => 'fa-map-marker',
        //        'title' => 'sb_gps',
        //        'route' => 'traccar',
        //        'sub' => [
        //                ['route' => 'traccar.users', 'title' => 'sb_user_summary', 'param' => []],
        //                ['route' => 'traccar.devices', 'title' => 'sb_devices', 'param' => []]
        //        ]
        //]
        
];

$permissions = session('ROLE_PERMISSION');
$subArray = [];
$all = false;
if ($permissions != null) {
    foreach ($permissions as $p) {
        if ($p->code == 'ALL_FUNCTIONS') {
            $all = true;
            break;
        }
        for ($i = 0; $i < count($sidebar); $i++) {
            $sba = $sidebar[$i];
            $action_name = explode(',', $p->action_name);
            $sub = $sba['sub'];
            if (!isset($subArray[$i])) $subArray[$i] = [];
            foreach ($sub as $s) {
                if (in_array(strtoupper($s['route']), $action_name)) {
                    $subArray[$i][] = $s;
                }
            }
        }
    }
}
if ($all == false && count($subArray) == count($sidebar)) {
    for ($i = 0; $i < count($sidebar); $i++) {
        $sidebar[$i]['sub'] = $subArray[$i];
    }
}
$collapse = Session::get('collapse');
$hide_left = '';
if($collapse == 1){
    $hide_left = 'hide-left-bar';
}
?>
<aside>
    <div id="sidebar" class="nav-collapse {{ $hide_left }}">
        <!-- sidebar menu goes here-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>{{ trans('sidebar.sb_dashboard') }}</span>
                    </a>
                </li>

                @foreach($sidebar as $sb)

                    <?PHP
                     if(count($sb['sub'])>0): ?>
                        <?php if($sb['title']==='sb_bcash'): ?>
                        <li>
                                <a href="{{ url('bcash/index') }}">
                                        <i class="fa fa-money"></i>
                                        <span>BE-Cash</span>
                                </a>
                        </li>    
                        <?php else:?>
                            <li class="sub-menu">
                                <?PHP $url = Request::segment(1);

                                $ck = is_array($sb['route']) ? in_array($url, $sb['route']) : $url == $sb['route'];
                                ?>
                                <?PHP if( $ck == true ): ?>
                                <a href="javascript:;" class="active dcjq-parent"><i class="fa {{ $sb['css'] }}"></i>
                                    <span>{{ trans('sidebar.'.$sb['title']) }}  </span></a>
                                <?PHP else: ?>
                                <a href="javascript:;" class=""> <i class="fa {{ $sb['css'] }}"></i>
                                    <span>{{ trans('sidebar.'.$sb['title']) }}  </span></a>
                                <?PHP endif;?>

                                @if(count($sb['sub']) > 0)
                                    <ul class="sub">
                                        @foreach($sb['sub'] as $sub)
                                            <?PHP   if(Route::currentRouteName() == $sub['route']):?>
                                            <li class="active">
                                            <?PHP else: ?>
                                            <li>
                                            <?PHP endif; ?>
                                                <a href="{{  $sub['title']!='sb_export_db'?route($sub['route'],$sub['param']):'javascript:void(0);' }}" id="{{ $sub['title'] == 'sb_export_db'?'sb_export_db':'' }}"> {{ trans('sidebar.'.$sub['title']) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        <?PHP endif; ?>
                    <?PHP endif; ?>
                @endforeach
            </ul>
        </div>
    </div>
</aside>
<div id="url" style="display: none;">{{Request::segment(1)}}</div>
