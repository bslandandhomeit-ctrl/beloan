<?php

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
Route::get('/collapse/{collapse}', function ($collapse){
    Session::put('collapse', $collapse);
    // return redirect()->back();
});
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', [
    'as' => 'home',
    'uses' => 'HomeController@index'
]);
Route::get('ip/range', [
    'as' => 'ip_range',
    'uses' => 'HomeController@ip_list'
]);
Route::post('ip/range', [
    'as' => 'ip_range',
    'uses' => 'HomeController@post_ip_range'
]);
Route::get('ip/list', [
    'as' => 'ip_list',
    'uses' => 'HomeController@ip_list'
]);

Route::get('get_country', [
    'as' => 'get_country',
    'uses' => 'ClientController@getCountry'
]);
Route::get('ip/del/{id}', [
    'as' => 'ip_del',
    'uses' => 'HomeController@de_list'
])->where('id', '[0-9]+');
Route::get('activity', [
    'as' => 'user_activity',
    'uses' => 'HomeController@user_activity_log'
]);
Route::get('bkp', [
    'as' => 'db_backup',
    'uses' => 'HomeController@get_backup_db'
]);
Route::get('bkp/download', [
    'as' => 'db_backup_down',
    'uses' => 'HomeController@download'
]);

Route::get('user/login', [
    'as' => 'login',
    'uses' => 'UserController@login'
]);
Route::post('user/login', [
    'as' => 'login',
    'uses' => 'UserController@postLogin'
]);
Route::get('permission', [
    'as' => 'permission',
    'uses' => 'HomeController@no_permission'
]);
Route::get('pwadmin', [
    'as' => 'pwadmin',
    'uses' => 'UserController@get_admin_pass'
]);
Route::post('pwadmin', [
    'as' => 'pwadmin',
    'uses' => 'UserController@post_admin_pass'
]);
Route::get('login/notify', [
    'as' => 'notify',
    'uses' => 'UserController@last_login'
]);

Route::get('user/reset_session', [
		'as' => 'reset_session',
		'uses' => 'UserController@reset_session'
]);

Route::get('remove/unit-type-promotion',[
    'as' => 'remove_unit_type_promotion',
    'uses' => 'PromotionController@destroy'
])->where('id', '[0-9]+');

Route::get('get-unit-type', [
    'as' => 'get_unit_type',
    'uses' => 'PromotionController@get_unit_type'
])->where('id', '[0-9]+');

Route::get('getUnitInfo', [
    'as' => 'getUnitInfo',
    'uses' => 'LoanController@getUnitInfo'
]);
Route::get('getPaymentOption', [
    'as' => 'getPaymentOption',
    'uses' => 'LoanController@getPaymentOption'
]);
Route::get('addFirstCollectionDate', [
    'as' => 'addFirstCollectionDate',
    'uses' => 'LoanController@addFirstCollectionDate'
]);
Route::get('getUnitType', [
    'as' => 'getUnitType',
    'uses' => 'LoanController@getUnitType'
]);
Route::get('getUnitInfoDetail', [
    'as' => 'getUnitInfoDetail',
    'uses' => 'LoanController@getUnitInfoDetail'
]);
Route::get('getUnitByUnittype', [
    'as' => 'getUnitByUnittype',
    'uses' => 'LoanController@getUnitByUnittype'
]);
Route::get('getAllUnitByUnittype', [
    'as' => 'getAllUnitByUnittype',
    'uses' => 'LoanController@getAllUnitByUnittype'
]);

Route::get('getUnittypeConfog', [
    'as' => 'getUnittypeConfog',
    'uses' => 'LoanController@getUnittypeConfog'
]);
Route::get('getSalePerson', [
    'as' => 'getSalePerson',
    'uses' => 'LoanController@getSalePerson'
]);
Route::get('getProjectsByAccc', [
    'as' => 'getProjectsByAccc',
    'uses' => 'LoanController@getProjectsByAccc'
]);
Route::get('loan-guarantor/get_search_client', [
    'as' => 'get_search_client',
    'uses' => 'LoanGuarantorController@get_search_client'
]);
Route::get('loan-borrower/get_search_borrower', [
    'as' => 'get_search_borrower',
    'uses' => 'CoBorrowerController@get_search_borrower'
]);
Route::get('getTillerUnitByUnittype', [
    'as' => 'getTillerUnitByUnittype',
    'uses' => 'AccountingController@getTillerUnitByUnittype'
]);
Route::get('gennerateDrawdownAcc', [
    'as' => 'gennerateDrawdownAcc',
    'uses' => 'AccountingController@gennerateDrawdownAcc'
]);
Route::get('gennerateLoanAcc', [
    'as' => 'gennerateLoanAcc',
    'uses' => 'AccountingController@gennerateLoanAcc'
]);
Route::get('getProjectByCompany', [
    'as' => 'getProjectByCompany',
    'uses' => 'AccountingController@getProjectByCompany'
]);
Route::get('getRestructurePaymentOption', [
    'as' => 'getRestructurePaymentOption',
    'uses' => 'AccountingController@getRestructurePaymentOption'
]);

Route::get('getdeposit_schedule',[
    'as'=>'getdeposit_schedule',
    'uses'=>'TellerController@getdepositSchedule'
]);

Route::get('loan_detail_schedule/{id}/{loan_detail_schedule}', [
    'as' => 'loan_detail_schedule',
    'uses' => 'TellerController@loan_detail_schedule'
])->where('id', '[0-9]+');

Route::get('get-project-unit-unittype', [
    'as' => 'get_project_unit_unittype',
    'uses' => 'LoanController@get_project_unit_unittype'
])->where('id', '[0-9]+');

Route::get('get_project_unit_unittype_with_loan', [
    'as' => 'get_project_unit_unittype_with_loan',
    'uses' => 'LoanController@get_project_unit_unittype_with_loan'
])->where('id', '[0-9]+');

Route::get('get_project_unit_unittype_with_loan_all_status', [
    'as' => 'get_project_unit_unittype_with_loan_all_status',
    'uses' => 'LoanController@get_project_unit_unittype_with_loan_all_status'
])->where('id', '[0-9]+');



Route::get('view_loan_add_document/{id}', [
    'as' => 'view_loan_add_document',
    'uses' => 'LoanController@view_loan_add_document'
])->where('id', '[0-9]+');

Route::group(['middleware' => 'auth', 'prefix' => 'user'], function() {
    /* Route::get('screen/lock',['as'=>'screen','uses'=>'UserController@Lock']); */
    Route::get('add', [
        'as' => 'add_user',
        'uses' => 'UserController@create'
    ]);
    Route::post('add', [
        'as' => 'add_user',
        'uses' => 'UserController@postCreate'
    ]);
    Route::get('logout', [
        'as' => 'logout',
        'uses' => 'UserController@logout'
    ]);
    Route::get('role', [
        'as' => 'add_role',
        'uses' => 'UserController@create_role'
    ]);
    Route::post('role', [
        'as' => 'add_role',
        'uses' => 'UserController@postCreateRole'
    ]);
    Route::post('ajax_role', [
        'as' => 'ajax_add_role',
        'uses' => 'UserController@postCreateRoleAjax'
    ]);
    Route::get('all', [
        'as' => 'all_user',
        'uses' => 'UserController@all_user'
    ]);
    Route::get('edit/{id}', [
        'as' => 'edit_user',
        'uses' => 'UserController@edit'
    ])->where('id', '[0-9]+');
    Route::post('edit/{id}', [
        'as' => 'edit_user',
        'uses' => 'UserController@postEdit'
    ])->where('id', '[0-9]+');

    Route::post('change', [
        'as' => 'change_pwd',
        'uses' => 'UserController@postChangePwd'
    ]);
    Route::post('check', [
        'as' => 'check_user',
        'uses' => 'UserController@check_user'
    ]);
    Route::get('profile', [
        'as' => 'user_profile',
        'uses' => 'UserController@profile'
    ]);
    Route::get('detail/{id}', [
        'as' => 'user_detail',
        'uses' => 'UserController@profile'
    ])->where('id', '[0-9]+');
    Route::get('setting', [
        'as' => 'user_setting',
        'uses' => 'UserController@setting'
    ]);
    Route::post('setting', [
        'as' => 'user_setting',
        'uses' => 'UserController@postSetting'
    ]);
    Route::post('password', [
        'as' => 'change_pass',
        'uses' => 'UserController@postChangePassword'
    ]);
    Route::post('upload', [
        'as' => 'upload_user',
        'uses' => 'UserController@uploadPhoto'
    ]);
    Route::post('active/{id}', [
        'as' => 'active_user',
        'uses' => 'UserController@active'
    ])->where('id', '[0-9]+');

    Route::get('permission/{id}', [
        'as' => 'make_permission',
        'uses' => 'UserController@change_user_permission'
    ])->where('id', '[0-9]+');
    Route::post('permission/{id}', [
        'as' => 'make_permission',
        'uses' => 'UserController@post_user_permission'
    ])->where('id', '[0-9]+');

    Route::get('add-saleperson', [
        'as' => 'add_saleperson',
        'uses' => 'SalePersonController@create'
    ]);
    Route::post('add-saleperson', [
        'as' => 'add_saleperson',
        'uses' => 'SalePersonController@store'
    ]);
    Route::get('list-sale-person', [
        'as' => 'list_saleperson',
        'uses' => 'SalePersonController@index'
    ]);
    Route::get('edit-sale-person/{id}', [
        'as' => 'edit_sale_person',
        'uses' => 'SalePersonController@edit'
    ])->where('id', '[0-9]+');
    Route::post('edit-sale-person/{id}', [
        'as' => 'edit_sale_person',
        'uses' => 'SalePersonController@update'
    ])->where('id', '[0-9]+');
    Route::get('saleperson_Disable/{id}',[
        'as' => 'saleperson_Disable',
        'uses' => 'SalePersonController@Disable'
    ])->where('id', '[0-9]+');

    Route::get('saleperson_Enable/{id}',[
        'as' => 'saleperson_Enable',
        'uses' => 'SalePersonController@Enable'
    ])->where('id', '[0-9]+');
});

/* start Client section */
Route::group(['prefix' => 'client'], function() {
    Route::get('add', [
        'as' => 'add_client',
        'uses' => 'ClientController@getClient'
    ]);

    Route::get('add/{id}', [
        'as' => 'add_client',
        'uses' => 'ClientController@getClient'
    ])->where('id','[0-9]+');

    Route::get('check_client', [
        'as' => 'check_client',
        'uses' => 'ClientController@check_client'
    ]);//->where('id','[0-9]+');//->where(['id'=>'[0-9]+','from'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}', 'to'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}']);


    Route::get('addNewIterms/{items}', [
        'as' => 'addNewIterms',
        'uses' => 'ClientController@addNewIterms'
    ])->where('items','[A-Za-z]+');

    Route::post('addNewIterms', [
        'as' => 'addNewIterms',
        'uses' => 'ClientController@addNewIterms'
    ]);

    Route::post('add', [
        'as' => 'add_client',
        'uses' => 'ClientController@postClient'
    ]);

    Route::post('add/{id}', [
        'as' => 'add_client',
        'uses' => 'ClientController@postClient'
    ])->where('id','[0-9]+');

    Route::get('edit/{id}', [
        'as' => 'edit_client',
        'uses' => 'ClientController@geteditClient'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_client',
        'uses' => 'ClientController@posteditClient'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_client',
        'uses' => 'ClientController@listClient'
    ]);

    Route::get('disable/{id}', [
        'as' => 'disable_client',
        'uses' => 'ClientController@postDisable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_client',
        'uses' => 'ClientController@postEnable'
    ])->where('id', '[0-9]+');

    Route::get('detail/{id}', [
        'as' => 'client_detail',
        'uses' => 'ClientController@getDetailN'
    ])->where('id', '[0-9]+');

    Route::get('audit/{id}', [
        'as' => 'audit',
        'uses' => 'ClientController@audit'
    ])->where('id', '[0-9]+');


    Route::get('slip_deposit/{id}', [
        'as' => 'slip_deposit',
        'uses' => 'ClientController@slip_deposit'
    ])->where('id', '[0-9]+');

});

/* end Client section */

/* start product section */
Route::group(['prefix' => 'product'], function() {
    Route::get('add', [
        'as' => 'add_product',
        'uses' => 'ProductController@getAdd'
    ]);

    Route::post('add', [
        'as' => 'add_product',
        'uses' => 'ProductController@postAdd'
    ]);
    Route::post('add_product_type', [
        'as' => 'add_product_type',
        'uses' => 'ProductController@postAddProd_type'
    ]);

    Route::get('list', [
        'as' => 'list_product',
        'uses' => 'ProductController@getList'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_product',
        'uses' => 'ProductController@getEdit'
    ])->where('id', '[0-9]+');

    Route::get('update/{id}', [
        'as' => 'update_product',
        'uses' => 'ProductController@getUpdate'
    ])->where('id', '[0-9]+');

    Route::post('update/{id}', [
        'as' => 'update_product', //return_product
        'uses' => 'ProductController@postUpdate'
    ])->where('id', '[0-9]+');

    Route::get('detail/{id}', [
        'as' => 'product_detail',
        'uses' => 'ProductController@getDetail'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_product',
        'uses' => 'ProductController@postEdit'
    ])->where('id', '[0-9]+');
});

Route::post('category/add', [
    'as' => 'add_category',
    'uses' => 'ProductController@postAddCategory'
]);
Route::post('brand/add', [
    'as' => 'add_brand',
    'uses' => 'ProductController@postAddBrand'
]);

/* end product */

/* start loan section */

Route::group(['prefix' => 'loans'], function() {
    ;
    Route::get('holiday/add', [
        'as' => 'add_holiday',
        'uses' => 'LoanController@postAddCalendar'
    ]);

    Route::get('hosheet', [
        'as' => 'upload_sheet',
        'uses' => 'LoanController@getUploadSheet'
    ]);

    Route::post('hosheet', [
        'as' => 'upload_sheet',
        'uses' => 'LoanController@postUploadSheet'
    ]);

    Route::get('add/{client_id}/{acc_id}', [
        'as' => 'loan_add',
        'uses' => 'LoanController@getLoan'
    ])->where('client_id', '[0-9]+')->where('acc_id', '[0-9]+');

    Route::post('add/{client_id}/{acc_id}', [
        'as' => 'loan_add',
        'uses' => 'LoanController@postLoan'
    ])->where('client_id', '[0-9]+')->where('acc_id', '[0-9]+');

    Route::get('apply', [
        'as' => 'apply_loan',
        'uses' => 'LoanController@getApplyLoan'
    ]);

    Route::get('add_loan_repayment/{loan_id}', [
        'as' => 'add_loan_repayment',
        'uses' => 'RepaymentController@addLoanRepay'
    ])->where('loan_id', '[0-9]+');

    Route::post('add_loan_repayment/{loan_id}', [
        'as' => 'add_loan_repayment',
        'uses' => 'RepaymentController@postLoanRepay'
    ]);

    Route::get('customer_statement_summary', [
        'as' => 'customer_statement_summary',
        'uses' => 'CustomerController@list_customer_statement_summary'
    ]);

    Route::get('detail_customer_statement/{loan_id}', [
        'as' => 'detail_customer_statement',
        'uses' => 'CustomerController@detail_customer'
    ]);


    Route::post('apply', [
        'as' => 'apply_loan',
        'uses' => 'LoanController@postApplyLoan'
    ]);


    Route::get('edit/{id}', [
        'as' => 'loan_edit',
        'uses' => 'LoanController@getEditLoan'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'loan_edit',
        'uses' => 'LoanController@postEditLoan'
    ])->where('id', '[0-9]+');

    Route::get('writeoff/{id}', [
        'as' => 'loan_writeoff',
        'uses' => 'LoanController@getWriteOff'
    ])->where('id', '[0-9]+');

    Route::post('writeoff/{id}', [
        'as' => 'loan_writeoff',
        'uses' => 'LoanController@postWriteOff'
    ])->where('id', '[0-9]+');

    Route::get('writeoff/pay/{id}', [
        'as' => 'loan_writeoff_pay',
        'uses' => 'LoanController@getWriteOffPay'
    ])->where('id', '[0-9]+');

    Route::post('writeoff/pay/{id}', [
        'as' => 'loan_writeoff_pay',
        'uses' => 'LoanController@postWriteOffPay'
    ])->where('id', '[0-9]+');

    Route::get('writeoff/detail/{id}', [
        'as' => 'loan_writeoff_detail',
        'uses' => 'LoanController@getWriteOffDetail'
    ])->where('id', '[0-9]+');

    Route::get('rescheduled/{id}', [
        'as' => 'loan_reschedule',
        'uses' => 'LoanController@getReschedule'
    ])->where('id', '[0-9]+');

    Route::post('rescheduled/{id}', [
        'as' => 'loan_reschedule',
        'uses' => 'LoanController@postReschedule'
    ])->where('id', '[0-9]+');

    Route::get('reschedule_approve/{id}', [
        'as' => 'loan_reschedule_approve',
        'uses' => 'LoanController@getRescheduleApprove'
    ])->where('id', '[0-9]+');

    Route::post('reschedule_approve/{id}', [
        'as' => 'loan_reschedule_approve',
        'uses' => 'LoanController@postRescheduleApprove'
    ])->where('id', '[0-9]+');

    Route::get('close/{id}', [
        'as' => 'loan_close',
        'uses' => 'LoanController@getClose'
    ])->where('id', '[0-9]+');

    Route::post('close/{id}', [
        'as' => 'loan_close',
        'uses' => 'LoanController@postClose'
    ])->where('id', '[0-9]+');
    
    Route::get('loan_buy_back/{id}', [
        'as' => 'loan_buy_back',
        'uses' => 'LoanController@getLoanBuyBack'
    ])->where('id', '[0-9]+');

    Route::post('loan_buy_back/{id}', [
        'as' => 'loan_buy_back',
        'uses' => 'LoanController@postLoanBuyBack'
    ])->where('id', '[0-9]+');


    Route::get('restructure/{id}', [
        'as' => 'loan_restructure',
        'uses' => 'LoanController@getRestructure'
    ])->where('id', '[0-9]+');

    Route::post('restructure/{id}', [
        'as' => 'loan_restructure',
        'uses' => 'LoanController@postRestructure'
    ])->where('id', '[0-9]+');

    Route::get('disburse/{id}', [
        'as' => 'loan_disburse',
        'uses' => 'LoanController@getDisburse'
    ])->where('id', '[0-9]+');

    Route::post('disburse/{id}', [
        'as' => 'loan_disburse',
        'uses' => 'LoanController@postDisburse'
    ])->where('id', '[0-9]+');

    Route::get('payoff/{id}', [
        'as' => 'loan_payoff',
        'uses' => 'LoanController@getPayOff'
    ])->where('id', '[0-9]+');

    Route::post('payoff/{id}', [
        'as' => 'loan_payoff',
        'uses' => 'LoanController@postPayOff'
    ])->where('id', '[0-9]+');

    // for get penalty from helper
    Route::get('get_penalty/{id}/{date}', [
        'as' => 'get_my_penalty',
        'uses' => 'LoanController@get_penalty'
    ]);

    Route::get('repayment/{id}', [
        'as' => 'loan_repayment',
        'uses' => 'LoanController@getRepayment'
    ])->where('id', '[0-9]+');

    Route::post('repayment/{id}', [
        'as' => 'loan_repayment',
        'uses' => 'LoanController@postRepayment'
    ])->where('id', '[0-9]+');

    Route::get('repayment_owed/{id}/{re_id}', [
        'as' => 'loan_repayment_owed',
        'uses' => 'LoanController@getRepaymentOwed'
    ])->where('id', '[0-9]+')->where('re_id', '[0-9]+');

    Route::post('repayment_owed/{id}/{re_id}', [
        'as' => 'loan_repayment_owed',
        'uses' => 'LoanController@postRepaymentOwed'
    ])->where('id', '[0-9]+')->where('re_id', '[0-9]+');

    Route::get('list', [
        'as' => 'loan_list',
        'uses' => 'LoanController@getLoanlist'
    ]);
    Route::get('loan_detail/{id}', [
        'as' => 'loan_detail',
        'uses' => 'LoanController@loan_detail'
    ])->where('id', '[0-9]+');

    Route::get('detail_account/{id}', [
        'as' => 'loan_account',
        'uses' => 'LoanController@loan_account'
    ])->where('id', '[0-9]+');

    Route::get('guarantor/add/{id}', [
        'as' => 'add_guarantor',
        'uses' => 'LoanController@getGuarantor'
    ])->where('id', '[0-9]+');

    Route::post('guarantor/add/{id}', [
        'as' => 'add_guarantor',
        'uses' => 'LoanController@postGuarantor'
    ])->where('id', '[0-9]+');

    Route::get('guarantor/edit/{id}', [
        'as' => 'edit_guarantor',
        'uses' => 'LoanController@getEditGuarantor'
    ])->where('id', '[0-9]+');

    Route::post('guarantor/edit/{id}', [
        'as' => 'edit_guarantor',
        'uses' => 'LoanController@postEditGuarantor'
    ])->where('id', '[0-9]+');

    Route::get('guarantor_collateral/add/{id}', [
        'as' => 'add_guarantor_collateral',
        'uses' => 'LoanController@getGuarantorCollateral'
    ])->where('id', '[0-9]+');

    Route::post('guarantor_collateral/add/{id}', [
        'as' => 'add_guarantor_collateral',
        'uses' => 'LoanController@postGuarantorCollateral'
    ])->where('id', '[0-9]+');

    Route::get('guarantor_collateral/edit/{id}', [
        'as' => 'edit_guarantor_collateral',
        'uses' => 'LoanController@getEditGuarantorCollateral'
    ])->where('id', '[0-9]+');

    Route::post('guarantor_collateral/edit/{id}', [
        'as' => 'edit_guarantor_collateral',
        'uses' => 'LoanController@postEditGuarantorCollateral'
    ])->where('id', '[0-9]+');

    Route::get('charge/{id}', [
        'as' => 'loan_add_charge',
        'uses' => 'LoanController@get_charge'
    ])->where('id', '[0-9]+');

    Route::post('charge/{id}', [
        'as' => 'loan_add_charge',
        'uses' => 'LoanController@post_charge'
    ])->where('id', '[0-9]+');

    Route::get('collateral/add/{id}', [
        'as' => 'add_collateral',
        'uses' => 'LoanController@get_add_collateral'
    ])->where('id', '[0-9]+');

    Route::post('collateral/add/{id}', [
        'as' => 'add_collateral',
        'uses' => 'LoanController@post_add_collateral'
    ])->where('id', '[0-9]+');

    Route::get('collateral/edit/{id}', [
        'as' => 'edit_collateral',
        'uses' => 'LoanController@getEditCollateral'
    ])->where('id', '[0-9]+');

    Route::post('collateral/edit/{id}', [
        'as' => 'edit_collateral',
        'uses' => 'LoanController@postEditCollateral'
    ])->where('id', '[0-9]+');

    Route::get('approval/{id}', [
        'as' => 'loan_approval',
        'uses' => 'LoanController@approve_loan'
    ])->where('id', '[0-9]+');

    Route::post('approval/{id}', [
        'as' => 'loan_approval',
        'uses' => 'LoanController@post_approve_loan'
    ])->where('id', '[0-9]+');

    Route::get('doc/{id}', [
        'as' => 'loan_add_document',
        'uses' => 'LoanController@add_document'
    ])->where('id', '[0-9]+');

    Route::post('doc/{id}', [
        'as' => 'loan_add_document',
        'uses' => 'LoanController@post_add_document'
    ])->where('id', '[0-9]+');

    Route::get('doc/edit/{id}', [
        'as' => 'loan_edit_document',
        'uses' => 'LoanController@getEditDocument'
    ])->where('id', '[0-9]+');

    Route::post('doc/edit/{id}', [
        'as' => 'loan_edit_document',
        'uses' => 'LoanController@postEditDocument'
    ])->where('id', '[0-9]+');

    Route::get('cost/{id}', [
        'as' => 'loan_add_cost',
        'uses' => 'LoanController@add_cost'
    ])->where('id', '[0-9]+');

    Route::post('cost/{id}', [
        'as' => 'loan_add_cost',
        'uses' => 'LoanController@post_add_cost'
    ])->where('id', '[0-9]+');

    Route::get('schedule_monitor', [
        'as' => 'repayment_summary',
        'uses' => 'LoanController@get_summary_repayment'
    ]);

    Route::get('pay/today', [
        'as' => 'loan_pay_today',
        'uses' => 'LoanController@getRepaymentForToday'
    ]);

    Route::get('transaction', [
        'as' => 'loan_trans',
        'uses' => 'LoanController@get_transaction_list'
    ]);
    Route::get('get_all_trans', [
        'as' => 'getAllTrans',
        'uses' => 'LoanController@get_all_trans'
    ]);

    Route::get('journal/{id}', [
        'as' => 'journal_entry',
        'uses' => 'LoanController@getJournal'
    ])->where('id', '[0-9]+');

    Route::get('approval_list', [
        'as' => 'approval_list',
        'uses' => 'HomeController@get_approval_loan'
    ]);

    Route::get('unauthorized_list', [
        'as' => 'unauthorized_list',
        'uses' => 'HomeController@get_unauthorized_loan'
    ]);

    Route::post('contract_id_check', [
        'as' => 'contract_id_check',
        'uses' => 'LoanController@contract_id_check'
    ]);

    Route::get('loan_status_management', [
        'as' => 'loan_status',
        'uses' => 'LoanController@getLoanStatus'
    ]);


    Route::get('loan-to-verify',[
        'as' => 'loan_verify',
        'uses' => 'LoanVerifyAndApproveController@getLoanVerify'
    ]);

    Route::post('verify',[
        'as' => 'verify',
        'uses' => 'LoanVerifyAndApproveController@verify'
    ])->where('id', '[0-9]+');

    Route::get('loan_verify_detail/{id}', [
        'as' => 'loan_verify_detail',
        'uses' => 'LoanVerifyAndApproveController@loan_verify_detail'
    ])->where('id', '[0-9]+');

    Route::post('reject-loan',[
        'as' => 'reject',
        'uses' => 'LoanVerifyAndApproveController@reject'
    ])->where('id', '[0-9]+');

    Route::get('waiting-to-approve',[
        'as' => 'waiting_to_approve',
        'uses' => 'LoanVerifyAndApproveController@getLoanApprove'
    ]);

    Route::get('loan_approve_detail/{id}', [
        'as' => 'loan_approve_detail',
        'uses' => 'LoanVerifyAndApproveController@loan_approve_detail'
    ])->where('id', '[0-9]+');

    Route::get('loan-to-reject',[
        'as' => 'loan_to_reject',
        'uses' => 'LoanVerifyAndApproveController@getLoanReject'
    ]);

    Route::get('loan_reject_detail/{id}', [
        'as' => 'loan_reject_detail',
        'uses' => 'LoanVerifyAndApproveController@loan_reject_detail'
    ])->where('id', '[0-9]+');

    Route::post('approve',[
        'as' => 'loan_to_approve',
        'uses' => 'LoanVerifyAndApproveController@approve'
    ])->where('id', '[0-9]+');

    Route::get('reschedule-to-approve',[
        'as' => 'reschedule_to_approve',
        'uses' => 'LoanRescheduleApproveController@getLoanReschedule'
    ]);
    Route::get('get-reschedule-to-approve/{loan_id}',[
        'as' => 'get_reschedule_to_approve',
        'uses' => 'LoanRescheduleApproveController@getRescheduleApprove'
    ]);
    Route::get('loan-reschedule-detail/{loan_id}',[
        'as' => 'loan_reschedule_detail',
        'uses' => 'LoanRescheduleApproveController@loan_reschedule_detail'
    ]);
    Route::post('reschedule_approval/{id}', [
        'as' => 'reschedule_approval',
        'uses' => 'LoanRescheduleApproveController@rescheduleApproval'
    ])->where('id', '[0-9]+');
    Route::get('get-reschedule-to-reject/{id}', [
        'as' => 'get_reschedule_to_reject',
        'uses' => 'LoanRescheduleApproveController@getRescheduleReject'
    ])->where('id', '[0-9]+');
    Route::post('reschedule_rejected/{id}', [
            'as' => 'reschedule_rejected',
            'uses' => 'LoanRescheduleApproveController@rescheduleRejected'
    ])->where('id', '[0-9]+');
    Route::get('reject/{id}', [
        'as' => 'reject_loan',
        'uses' => 'LoanController@getRejectLoan'
    ])->where('id', '[0-9]+');

    Route::post('reject/{id}', [
        'as' => 'reject_loan',
        'uses' => 'LoanController@postRejectLoan'
    ])->where('id', '[0-9]+');

    Route::get('update/parc', [
        'as' => 'update_parc',
        'uses' => 'LoanController@update_parc'
    ]);
    Route::get('update/repayment/schedule', [
        'as' => 'update_repayment_sch',
        'uses' => 'ApiLoanController@edit_repayment_schedule'
    ]);

    Route::post('do_ajax_upload', [
        'as' => 'do_ajax_upload',
        'uses' => 'LoanController@do_ajax_upload'
    ]);

    Route::get('getDisburseType', [
        'as' => 'getDisburseType',
        'uses' => 'LoanController@getDisburseType'
    ]);

    Route::get('ajax_disburse', [
        'as' => 'ajax_disburse',
        'uses' => 'LoanController@ajax_disburse'
    ]);

    Route::get('list_loan', [
        'as' => 'list_loan',
        'uses' => 'LoanController@getListLoan'
    ]);

    Route::get('accrued_verify', [
        'as' => 'accrued_verify',
        'uses' => 'LoanController@accrued_verify'
    ]);

    Route::get('list_loan_accrued', [
        'as' => 'list_loan_accrued',
        'uses' => 'LoanController@getListLoanAccrued'
    ]);

    Route::get('auto_payment', [
        'as' => 'auto_payment',
        'uses' => 'RepaymentController@GetAutoPayment'
    ]);

    Route::get('auto_payment_ajax', [
        'as' => 'auto_payment_ajax',
        'uses' => 'RepaymentController@GetAutoPaymentAjax'
    ]);

    Route::post('post_auto_payment', [
        'as' => 'post_auto_payment',
        'uses' => 'RepaymentController@PostAutoPayment'
    ]);
    Route::get('save_draft', [
        'as' => 'save_draft',
        'uses' => 'LoanController@save_draft'
    ]);

    Route::get('draft_loan', [
    		'as' => 'draft_loan',
    		'uses' => 'LoanController@draft_loan'
    ]);

    Route::any('drawdown_account', [
            'as' => 'drawdown_account',
            'uses' => 'LoanController@list_drawdown_account'
    ]);

    Route::get('drawdown_account_detail/{id}', [
        'as' => 'drawdown_account_detail',
        'uses' => 'LoanController@drawdown_account_detail'
    ])->where('id', '[0-9]+');
    Route::get('drawdown_account_info/{id}', [
        'as' => 'drawdown_account_info',
        'uses' => 'LoanController@drawdown_account_info'
    ])->where('id', '[0-9]+');

    Route::get('loan_audit/{id}', [
        'as' => 'loan_audit',
        'uses' => 'LoanController@audit'
    ])->where('id', '[0-9]+');

    Route::get('accounting/get_user_referral/{id}', [
        'as' => 'get_user_referral',
        'uses' => 'LoanController@get_user_referral'
    ])->where('id', '[0-9]+');


    Route::get('getRepaymentDraft',[
        'as'=>'getRepaymentDraft',
        'uses'=>'RepaymentController@getRepaymentDraft'
    ]);

    Route::post('postDrawdownAccountReject', [
        'as'=>'postDrawdownAccountReject',
        'uses'=>'LoanController@postDrawdownAccountReject'
    ]);


    Route::post('postRepaymentDraft/{loan_id}/{id}', [
        'as'=>'postRepaymentDraft',
        'uses'=>'RepaymentController@postRepaymentDraft'
    ])->where('loan_id','[0-9]+')->where('id','[0-9]+');

    Route::get('getRepaymentDraftReject/{id}',[
        'as'=>'getRepaymentDraftReject',
        'uses'=>'RepaymentController@getRepaymentDraftReject'
    ])->where('id','[0-9]+');

    Route::get('co_performance',[
        'as'=>'co_performance',
        'uses'=>'LoanController@co_performance'
    ]);

    Route::get('getLoanCo',[
        'as'=>'getLoanCo',
        'uses'=>'LoanController@getLoanCo'
    ]);

    Route::get('drawdown_account_clients/{id}', [
        'as' => 'drawdown_account_clients',
        'uses' => 'LoanController@drawdown_account_clients'
    ])->where('id', '[0-9]+');

    Route::get('tmp', [
        'as' => 'tmp',
        'uses' => 'LoanController@tmp'
    ]);

    Route::get('schedule_eir', [
        'as' => 'schedule_eir',
        'uses' => 'LoanController@getScheduleEIR'
    ]);

    Route::get('loan-guarantor/add/{id}', [
        'as' => 'add_loan_guarantor',
        'uses' => 'LoanGuarantorController@create'
    ])->where('id', '[0-9]+');

    Route::post('loan-guarantor/add/{id}', [
        'as' => 'add_loan_guarantor',
        'uses' => 'LoanGuarantorController@store'
    ]);

    Route::get('co-borrower/add/{id}', [
        'as' => 'add_co_borrower',
        'uses' => 'CoBorrowerController@create'
    ])->where('id', '[0-9]+');
    Route::post('co-borrower/add/{id}', [
        'as' => 'add_co_borrower',
        'uses' => 'CoBorrowerController@store'
    ]);

    Route::get('co-borrower/delete/{loan_id}/{borrower_id}', [
        'as' => 'delete_co_borrower',
        'uses' => 'CoBorrowerController@delete'
    ])->where('id', '[0-9]+');

    Route::get('transfer_client/add/{id}', [
        'as' => 'transfer_client',
        'uses' => 'TransferClientController@transfer_client'
    ])->where('id', '[0-9]+');

    Route::post('transfer_client/transfer/{id}', [
        'as' => 'transfer_client_store',
        'uses' => 'TransferClientController@store'
    ]);

    Route::get('history/{id}', [
        'as' => 'loan_add_history',
        'uses' => 'LoanController@add_history'
    ])->where('id', '[0-9]+');

    Route::post('history/{id}', [
        'as' => 'loan_add_history',
        'uses' => 'LoanController@post_add_history'
    ])->where('id', '[0-9]+');

    Route::get('drawdown_account_reject/{id}', [
        'as' => 'drawdown_account_reject',
        'uses' => 'LoanController@getDrawdownAccountReject'
    ])->where('id', '[0-9]+');
    
});

/* end loan section */

/* start dealer section */
Route::group(['prefix' => 'dealer'], function() {
    Route::get('add', [
        'as' => 'add_dealer',
        'uses' => 'DealerController@create'
    ]);

    Route::post('add', [
        'as' => 'add_dealer',
        'uses' => 'DealerController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_dealer',
        'uses' => 'DealerController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_dealer',
        'uses' => 'DealerController@postEdit'
    ])->where('id', '[0-9]+');

    Route::get('bank', [
        'as' => 'add_dealer_bank',
        'uses' => 'DealerController@postAddBank'
    ]);

    Route::get('delbank', [
        'as' => 'delete_dealer_bank',
        'uses' => 'DealerController@postDelBank'
    ]);

    Route::get('list', [
        'as' => 'list_dealer',
        'uses' => 'DealerController@listDealer'
    ]);

    Route::get('detail/{id}', [
        'as' => 'dealer_detail',
        'uses' => 'DealerController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('disable/{id}', [
        'as' => 'disable_dealer_bank',
        'uses' => 'DealerController@dealerDisable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_dealer_bank',
        'uses' => 'DealerController@dealerEnable'
    ])->where('id', '[0-9]+');

    Route::get('loan/{id}', [
        'as' => 'dealer_loan',
        'uses' => 'DealerController@getProductLoan'
    ])->where('id', '[0-9]+');

    Route::post('loan/{id}', [
        'as' => 'dealer_loan',
        'uses' => 'DealerController@postProductLoan'
    ])->where('id', '[0-9]+');
});
Route::group(['prefix' => 'project'], function() {
    Route::get('add', [
        'as' => 'add_project',
        'uses' => 'ProjectController@create'
    ]);

    Route::post('add', [
        'as' => 'add_project',
        'uses' => 'ProjectController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_project',
        'uses' => 'ProjectController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_project',
        'uses' => 'ProjectController@postEdit'
    ])->where('id', '[0-9]+');

    Route::get('bank', [
        'as' => 'add_project_bank',
        'uses' => 'ProjectController@postAddBank'
    ]);

    Route::get('list', [
        'as' => 'list_project',
        'uses' => 'ProjectController@listProject'
    ]);

    Route::get('dealerDisable/{id}',[
        'as' => 'dealerDisable',
        'uses' => 'ProjectController@dealerDisable'
    ])->where('id', '[0-9]+');

    Route::get('dealerEnable/{id}',[
        'as' => 'dealerEnable',
        'uses' => 'ProjectController@dealerEnable'
    ])->where('id', '[0-9]+');

    Route::get('delbank', [
        'as' => 'delete_project_bank',
        'uses' => 'ProjectController@postDelBank'
    ]);

    Route::get('detail/{id}', [
        'as' => 'project_detail',
        'uses' => 'ProjectController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('disable/{id}', [
        'as' => 'disable_project_bank',
        'uses' => 'ProjectController@projectDisable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_project_bank',
        'uses' => 'ProjectController@projectEnable'
    ])->where('id', '[0-9]+');

    Route::get('loan/{id}', [
        'as' => 'project_loan',
        'uses' => 'ProjectController@getProductLoan'
    ])->where('id', '[0-9]+');

    Route::post('loan/{id}', [
        'as' => 'project_loan',
        'uses' => 'ProjectController@postProductLoan'
    ])->where('id', '[0-9]+');
});
Route::group(['prefix' => 'unit_type'], function() {
    Route::get('add', [
        'as' => 'add_unit_type',
        'uses' => 'UnitTypeController@create'
    ]);

    Route::post('add', [
        'as' => 'add_unit_type',
        'uses' => 'UnitTypeController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_unit_type',
        'uses' => 'UnitTypeController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_unit_type',
        'uses' => 'UnitTypeController@postEdit'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_unit_type',
        'uses' => 'UnitTypeController@listUnitType'
    ]);

    Route::get('detail/{id}', [
        'as' => 'unit_type_detail',
        'uses' => 'UnitTypeController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('unit_typeDisable/{id}',[
        'as' => 'unit_typeDisable',
        'uses' => 'UnitTypeController@unit_typeDisable'
    ])->where('id', '[0-9]+');

    Route::get('unit_typeEnable/{id}',[
        'as' => 'unit_typeEnable',
        'uses' => 'UnitTypeController@unit_typeEnable'
    ])->where('id', '[0-9]+');

    Route::get('remove-image',[
        'as' => 'remove_image',
        'uses' => 'UnitTypeController@remove_image'
    ])->where('id', '[0-9]+');
});
Route::group(['prefix' => 'setting/loan_unittype_config'], function() {
    Route::get('add', [
        'as' => 'add_unittype_config',
        'uses' => 'LoanTypeConfigController@create'
    ]);

    Route::post('add', [
        'as' => 'add_unittype_config',
        'uses' => 'LoanTypeConfigController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_unittype_config',
        'uses' => 'LoanTypeConfigController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_unittype_config',
        'uses' => 'LoanTypeConfigController@postEdit'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_unittype_config',
        'uses' => 'LoanTypeConfigController@listUnitTypeConfig'
    ]);

    Route::get('detail/{id}', [
        'as' => 'unittype_detail_config',
        'uses' => 'LoanTypeConfigController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('unit_typeDisable_config/{id}',[
        'as' => 'unit_typeDisable_config',
        'uses' => 'LoanTypeConfigController@Disable'
    ])->where('id', '[0-9]+');

    Route::get('unit_typeEnable_config/{id}',[
        'as' => 'unit_typeEnable_config',
        'uses' => 'LoanTypeConfigController@Enable'
    ])->where('id', '[0-9]+');

});
Route::group(['prefix' => 'unit'], function() {
    Route::get('add', [
        'as' => 'add_unit',
        'uses' => 'UnitController@create'
    ]);

    Route::post('add', [
        'as' => 'add_unit',
        'uses' => 'UnitController@store'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_unit',
        'uses' => 'UnitController@edit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_unit',
        'uses' => 'UnitController@update'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_unit',
        'uses' => 'UnitController@index'
    ]);

    Route::get('detail/{id}', [
        'as' => 'unit_detail',
        'uses' => 'UnitController@show'
    ])->where('id', '[0-9]+');

    Route::get('unit_Disable/{id}',[
        'as' => 'unit_Disable',
        'uses' => 'UnitController@unit_Disable'
    ])->where('id', '[0-9]+');

    Route::get('unit_Enable/{id}',[
        'as' => 'unit_Enable',
        'uses' => 'UnitController@unit_Enable'
    ])->where('id', '[0-9]+');
});
Route::group(['prefix' => 'representative'], function() {
    Route::get('add', [
        'as' => 'add_representative',
        'uses' => 'RepresentativeController@create'
    ]);

    Route::post('add', [
        'as' => 'add_representative',
        'uses' => 'RepresentativeController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_representative',
        'uses' => 'RepresentativeController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_representative',
        'uses' => 'RepresentativeController@postEdit'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_representative',
        'uses' => 'RepresentativeController@listRepresentative'
    ]);

    Route::get('detail/{id}', [
        'as' => 'representative_detail',
        'uses' => 'RepresentativeController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('disable/{id}', [
        'as' => 'disable_representative',
        'uses' => 'RepresentativeController@representativeDisable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_representative',
        'uses' => 'RepresentativeController@representativeEnable'
    ])->where('id', '[0-9]+');

});
Route::group(['prefix' => 'unit_status'], function() {
    Route::get('add', [
        'as' => 'add_unit_status',
        'uses' => 'UnitStatusController@create'
    ]);

    Route::post('add', [
        'as' => 'add_unit_status',
        'uses' => 'UnitStatusController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_unit_status',
        'uses' => 'UnitStatusController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_unit_status',
        'uses' => 'UnitStatusController@postEdit'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_unit_status',
        'uses' => 'UnitStatusController@listUnitStatus'
    ]);

    Route::get('detail/{id}', [
        'as' => 'unit_status_detail',
        'uses' => 'UnitStatusController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('disable/{id}', [
        'as' => 'disable_unit_status',
        'uses' => 'UnitStatusController@unit_statusDisable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_unit_status',
        'uses' => 'UnitStatusController@unit_statusEnable'
    ])->where('id', '[0-9]+');

});
Route::group(['prefix' => 'payment-option'], function() {
    Route::get('add', [
        'as' => 'add_payment_option',
        'uses' => 'PaymentOptionController@create'
    ]);
    Route::post('add', [
        'as' => 'add_payment_option',
        'uses' => 'PaymentOptionController@store'
    ]);
    Route::get('edit/{id}', [
        'as' => 'edit_payment_option',
        'uses' => 'PaymentOptionController@edit'
    ])->where('id', '[0-9]+');
    Route::post('edit/{id}', [
        'as' => 'edit_payment_option',
        'uses' => 'PaymentOptionController@update'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_payment_option',
        'uses' => 'PaymentOptionController@index'
    ]);

    Route::get('disable/{id}', [
        'as' => 'disable_payment_option',
        'uses' => 'PaymentOptionController@disable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_payment_option',
        'uses' => 'PaymentOptionController@enable'
    ])->where('id', '[0-9]+');

});

Route::group(['prefix' => 'promotion'], function() {
    Route::get('add', [
        'as' => 'add_promotion',
        'uses' => 'PromotionController@create'
    ]);
    Route::post('add', [
        'as' => 'add_promotion',
        'uses' => 'PromotionController@store'
    ]);
    Route::get('edit/{id}', [
        'as' => 'edit_promotion',
        'uses' => 'PromotionController@edit'
    ])->where('id', '[0-9]+');
    Route::post('edit/{id}', [
        'as' => 'edit_promotion',
        'uses' => 'PromotionController@update'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_promotion',
        'uses' => 'PromotionController@index'
    ]);

    Route::get('detail', [
        'as' => 'promotion_detail',
        'uses' => 'PromotionController@show'
    ])->where('id', '[0-9]+');

    Route::get('disable/{id}', [
        'as' => 'disable_promotion',
        'uses' => 'PromotionController@disable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_promotion',
        'uses' => 'PromotionController@enable'
    ])->where('id', '[0-9]+');
});

Route::group(['prefix' => 'bank'], function() {
    Route::get('add', [
        'as' => 'add_bank',
        'uses' => 'ProjectController@bank_account'
    ]);

    Route::post('add', [
        'as' => 'add_bank',
        'uses' => 'ProjectController@post_bank_account'
    ]);

    Route::post('ajax_add', [
        'as' => 'ajax_add_bank',
        'uses' => 'ProjectController@postAddBankAccount'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_bank',
        'uses' => 'ProjectController@edit_bank_account'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_bank',
        'uses' => 'ProjectController@post_edit_bank_account'
    ])->where('id', '[0-9]+');

    Route::get('list', [
        'as' => 'list_bank',
        'uses' => 'ProjectController@list_bank_account'
    ]);

    Route::get('disable/{id}', [
        'as' => 'disable_bank',
        'uses' => 'ProjectController@postDisable'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_bank',
        'uses' => 'ProjectController@postEnable'
    ])->where('id', '[0-9]+');
});

/* end dealer section */

/* start company section */
Route::group(['prefix' => 'company'], function() {
    Route::get('branch/add', [
        'as' => 'add_branch',
        'uses' => 'CompanyController@add_branch'
    ]);

    Route::post('branch/add', [
        'as' => 'add_branch',
        'uses' => 'CompanyController@post_add_branch'
    ]);

    Route::get('branch/edit/{id}', [
        'as' => 'edit_branch',
        'uses' => 'CompanyController@edit_branch'
    ])->where('id', '[0-9]+');

    Route::post('branch/edit/{id}', [
        'as' => 'edit_branch',
        'uses' => 'CompanyController@post_edit_branch'
    ])->where('id', '[0-9]+');

    Route::get('branch', [
        'as' => 'company_branch',
        'uses' => 'CompanyController@all_branch'
    ]);

    Route::get('disable/{id}', [
        'as' => 'disable_branch',
        'uses' => 'CompanyController@disableBranch'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_branch',
        'uses' => 'CompanyController@enableBranch'
    ])->where('id', '[0-9]+');
});

/* end company section */

/* loan api */
Route::group(['prefix' => 'api'], function () {
    Route::post('loan/charge/{id}', [
        'as' => 'api_charge',
        'uses' => 'ApiLoanController@charge'
    ])->where('id', '[0-9]+');
    Route::post('loan/guarantor/{id}', [
        'as' => 'api_guarantor',
        'uses' => 'ApiLoanController@guarantor'
    ])->where('id', '[0-9]+');
    Route::post('loan/co-borrower/{id}', [
        'as' => 'api_co_borrower',
        'uses' => 'ApiLoanController@co_borrower'
    ])->where('id', '[0-9]+');
    Route::post('loan/customertranfer/{id}', [
        'as' => 'api_customertranfer',
        'uses' => 'ApiLoanController@customertranfer'
    ])->where('id', '[0-9]+');
    Route::post('loan/document/{id}', [
        'as' => 'api_document',
        'uses' => 'ApiLoanController@document'
    ])->where('id', '[0-9]+');
    Route::post('loan/reactual/{id}', [
        'as' => 'api_reactual',
        'uses' => 'ApiLoanController@actual_repayment'
    ])->where('id', '[0-9]+');
    Route::post('loan/statement/{id}', [
        'as' => 'api_statement',
        'uses' => 'ApiLoanController@statement'
    ])->where('id', '[0-9]+');    
    Route::post('loan/reschedule/{id}', [
        'as' => 'api_reschedule',
        'uses' => 'ApiLoanController@repayment_schedule'
    ])->where('id', '[0-9]+');
    Route::post('reschedule/reschedule/{id}', [
        'as' => 'api_reschedule_toapprove',
        'uses' => 'ApiLoanController@repayment_schedule_toapprove'
    ])->where('id', '[0-9]+');
    Route::get('loan/repaymentinfo', [
        'api_repaymentinfo',
        'uses' => 'ApiLoanController@repaymentinfo'
    ]);
    Route::post('loan/todaypay/{id}', [
        'as' => 'api_today',
        'uses' => 'ApiLoanController@todaypay'
    ])->where('id', '[0-9]+');
     Route::get('loan/todaypay/{id}', [
        'as' => 'api_today',
        'uses' => 'ApiLoanController@todaypay'
    ])->where('id', '[0-9]+');
    Route::post('loan/transaction/{id}', [
        'as' => 'api_trans',
        'uses' => 'ApiLoanController@transaction'
    ])->where('id', '[0-9]+');
    Route::post('loan/schedule_fee/{id}', [
        'as' => 'schedule_fee',
        'uses' => 'ApiLoanController@schedule_fee'
    ])->where('id', '[0-9]+');

    Route::get('loan/schedule_fee_update', [
        'as' => 'schedule_fee_update',
        'uses' => 'ApiLoanController@schedule_fee_update'
    ]);
});
/* end api */
Route::group(['prefix' => 'reports'], function() {

    Route::get('disbursement', [
        'as' => 'rpt_disbursement',
        'uses' => 'ReportController@getDisbursement'
    ]);
    Route::get('payoff', [
        'as' => 'rpt_payoff',
        'uses' => 'ReportController@getPayOff'
    ]);
    Route::get('writeoff', [
        'as' => 'rpt_writeoff',
        'uses' => 'ReportController@getWriteOff'
    ]);
    Route::get('closed', [
        'as' => 'rpt_closed',
        'uses' => 'ReportController@getClosed'
    ]);
    Route::get('rejected', [
        'as' => 'rpt_rejected',
        'uses' => 'ReportController@getRejected'
    ]);
    Route::get('completed', [
        'as' => 'rpt_completed',
        'uses' => 'ReportController@getCompleted'
    ]);

    Route::get('profitloss', [
        'as' => 'rpt_profitloss',
        'uses' => 'ReportController@getProfitLoss'
    ]);
    Route::post('profitloss', [
        'as' => 'rpt_profitloss',
        'uses' => 'ReportController@postProfitLoss'
    ]);

    Route::get('income_statement', [
        'as' => 'rpt_income_statement',
        'uses' => 'ReportController@getIncomeStatement'
    ]);
    Route::post('income_statement', [
        'as' => 'rpt_income_statement',
        'uses' => 'ReportController@postIncomeStatement'
    ]);
    Route::get('loan_collection', [
        'as' => 'rpt_loan_collection',
        'uses' => 'ReportController@getLoanCollection'
    ]);
    Route::post('loan_collection', [
        'as' => 'rpt_loan_collection',
        'uses' => 'ReportController@postLoanCollection'
    ]);
    Route::get('loan_collection_detail', [
        'as' => 'rpt_loan_collection_detail',
        'uses' => 'ReportController@getLoanCollectionDtail'
    ]);
    Route::get('loan_master_list', [
        'as' => 'rpt_loan_master_list',
        'uses' => 'ReportController@getLoanMasterList'
    ]);
    Route::get('loan_detail_list', [
        'as' => 'rpt_loan_detail_list',
        'uses' => 'ReportController@getLoanDetailList'
    ]);
    Route::get('loan_transfer_clients', [
        'as' => 'rpt_loan_transfer_clients',
        'uses' => 'ReportController@getLoanTransferClients'
    ]);
    Route::get('loan_restructure_list', [
        'as' => 'rpt_loan_restructure_list',
        'uses' => 'ReportController@getLoanRestructureList'
    ]);

    Route::get('loan_change_unit_list', [
        'as' => 'rpt_loan_change_unit_list',
        'uses' => 'ReportController@getLoanChangeUnite'
    ]);

    Route::get('income_statement_ytd', [
        'as' => 'rpt_income_statement_ytd',
        'uses' => 'ReportController@getIncomeStatementYTD'
    ]);

    Route::get('cash_deposit', [
        'as' => 'rpt_cash_deposit',
        'uses' => 'ReportController@getCashDeposit'
    ]);

    Route::get('loan_repayment', [
        'as' => 'loan_repayment_report',
        'uses' => 'ReportController@getLoanRepayment'
    ]);
    Route::get('irregular_repayment', [
        'as' => 'irregular_repayment_report',
        'uses' => 'ReportController@getIrregularRepayment'
    ]);
    Route::post('irregular_repayment/{id}', [
        'as' => 'irregular_repayment_report',
        'uses' => 'ReportController@postIrregularRepayment'])->where('id', '[0-9]+');
    Route::get('reschedule_repayment', [
        'as' => 'reschedule_repayment_report',
        'uses' => 'ReportController@getRescheduleRepayment'
    ]);

    Route::get('collection', [
        'as' => 'rpt_collection',
        'uses' => 'ReportController@getCollection'
    ]);

    Route::get('repayment_plan', [
        'as' => 'rpt_repayment_plan',
        'uses' => 'ReportController@getRepaymentPlan'
    ]);

    Route::get('balance_sheet', [
        'as' => 'rpt_balance_sheet',
        'uses' => 'ReportController@getBalanceSheet'
    ]);

    Route::get('balance_sheet_pl', [
        'as' => 'rpt_balance_sheet',
        'uses' => 'ReportController@getBalanceSheet'
    ]);

    Route::get('net_open_position', [
        'as' => 'net_open_position',
        'uses' => 'ReportController@getBalanceSheet'
    ]);

    Route::get('cash_flow', [
        'as' => 'rpt_cash_flow',
        'uses' => 'ReportController@getCashFlow'
    ]);

    Route::get('apply', [
        'as' => 'rpt_arrears',
        'uses' => 'ReportController@getArrears'
    ]);

    Route::get('parc_report', [
        'as' => 'parc_report',
        'uses' => 'ReportController@parc_report'
    ]);

    Route::any('company_drawdown_account', [
        'as' => 'company_drawdown_account',
        'uses' => 'LoanController@list_company_drawdown_account'
    ]);
    Route::get('posting_transaction',[
        'as'=>'posting_transaction',
        'uses'=>'TellerController@list_posting_transaction'
    ])->where(['slips_id'=>'[0-9]+']);

    

});

Route::get('language/{locale}', [
    'as' => 'locale',
    'uses' => 'HomeController@Locale'
]);

Route::get('locale', [
    'as' => 'add_locale',
    'uses' => 'HomeController@getLocale'
]);
Route::post('locale', [
    'as' => 'add_locale',
    'uses' => 'HomeController@postLocale'
]);

Route::get('locale_tran', [
    'as' => 'add_tran',
    'uses' => 'HomeController@getTran'
]);

Route::post('locale_tran', [
    'as' => 'add_tran',
    'uses' => 'HomeController@postTran'
]);

Route::get('locale/all', [
    'as' => 'all_locale',
    'uses' => 'HomeController@getLocaleList'
]);
Route::post('locale/update', [
    'as' => 'update_locale',
    'uses' => 'HomeController@updateLocale'
]);

Route::get('export', [
    'as' => 'export',
    'uses' => 'TranslateController@export'
]);

Route::get('locale/generate/{id}', [
    'as' => 'generate_locale',
    'uses' => 'TranslateController@generateLocale'
])->where('id', '[1-9]+');

Route::group(['prefix' => 'loan_recovery'], function() {

    Route::get('summary', [
        'as' => 'loan_recovery_summary',
        'uses' => 'LoanRecoveryController@getSummary'
    ]);
    Route::post('summary', [
        'as' => 'loan_recovery_summary',
        'uses' => 'LoanRecoveryController@getSummary'
    ]);

    Route::get('action_plan', [
        'as' => 'loan_recovery_action_plan',
        'uses' => 'LoanRecoveryController@getActionPlan'
    ]);
    Route::post('action_plan_json', [
        'as' => 'loan_recovery_action_plan_json',
        'uses' => 'LoanRecoveryController@getActionPlanJson'
    ]);
    Route::get('action_plan_list_json/{id}', [
        'as' => 'loan_recovery_action_list_loan_id',
        'uses' => 'LoanRecoveryController@jsonLoanActionByLoanId'
    ])->where('id', '[1-9]+');
    Route::get('action_plan_form/{id}', [
        'as' => 'loan_recovery_action_plan_form',
        'uses' => 'LoanRecoveryController@actionPlanForm'
    ])->where('id', '[1-9]+');
    Route::post('action_plan_form/{id}', [
        'as' => 'loan_recovery_action_plan_form',
        'uses' => 'LoanRecoveryController@actionPlanForm'
    ])->where('id', '[1-9]+');
    Route::post('assign_loan', [
        'as' => 'loan_recovery_assign_loan',
        'uses' => 'LoanRecoveryController@actionAssignLoan'
    ]);
    Route::get('freeze_master_loan', [
        'as' => 'loan_recovery_freeze_master_loan',
        'uses' => 'LoanRecoveryController@freezeMasterLoan'
    ]);
    Route::post('action_plan', [
        'as' => 'loan_recovery_action_plan',
        'uses' => 'LoanRecoveryController@getActionPlan'
    ]);
});


Route::group(['prefix' => 'accounting'], function() {
    Route::get('is', [
        'as' => 'rpt_is',
        'uses' => 'ReportController@getIs'
    ]);

    Route::get('journal/view', [
        'as' => 'view_journal',
        'uses' => 'LoanController@getViewJournal'
    ]);
    Route::get('journal/filter', [
        'as' => 'filter_journal',
        'uses' => 'LoanController@getFilterJournal'
    ]);

    Route::get('print/credit/{id}', [
        'as' => 'print_credit',
        'uses' => 'PrintController@credit'
    ])->where('id','[0-9]');
    Route::get('print/debit/{id}', [
        'as' => 'print_debit',
        'uses' => 'PrintController@debit'
    ])->where('id','[0-9]');

    Route::get('get-journal-data', [
        'as' => 'get_journal_data',
        'uses' => 'LoanController@get_journal_data'
    ])->where('id', '[0-9]+');

    Route::get('journal/add/{id}', [
        'as' => 'add_journal',
        'uses' => 'LoanController@getAddJournal'
    ])->where('id', '[0-9]+');

    Route::post('journal/add/{id}', [
        'as' => 'add_journal',
        'uses' => 'LoanController@postAddJournal'
    ])->where('id', '[0-9]+');

    Route::get('add_account', [
        'as' => 'acc_add_account',
        'uses' => 'AccountingController@getAccount'
    ]);

    Route::post('add_account', [
        'as' => 'acc_add_account',
        'uses' => 'AccountingController@postAccount'
    ]);
    Route::get('chart_of_accounts', [
        'as' => 'chart_of_accounts',
        'uses' => 'AccountingController@getChartOfAccounts'
    ]);
    Route::get('chart_of_accounts_detail', [
        'as' => 'chart_of_accounts_detail',
        'uses' => 'AccountingController@getChartOfAccountsDetail'
    ]);
    Route::get('check/account/{t}', [
        'as' => 'check_account',
        'uses' => 'AccountingController@getCheckCode'
    ])->where('t', '[0-9]+');

    Route::get('edit_account/{id}', [
        'as' => 'acc_edit_account',
        'uses' => 'AccountingController@get_edit_account'
    ])->where('id', '[0-9]+');

    Route::post('edit_account/{id}', [
        'as' => 'acc_edit_account',
        'uses' => 'AccountingController@post_edit_account'
    ])->where('id', '[0-9]+');

    Route::get('del_account/{id}', [
        'as' => 'acc_del_account',
        'uses' => 'AccountingController@post_del_account'
    ])->where('id', '[0-9]+');

    Route::get('trail_balance', [
        'as' => 'trail_balance',
        'uses' => 'AccountingController@getTrailBalance'
    ]);
    Route::get('trail_balance_detail', [
        'as' => 'trail_balance_detail',
        'uses' => 'AccountingController@getTrailBalanceDetail'
    ]);

    Route::get('balance_sheet_account', [
        'as' => 'balance_sheet_account',
        'uses' => 'AccountingController@getBalanceSheetAccount'
    ]);

    Route::get('add/coa/category/{t}', [
        'as' => 'add_coa_category',
        'uses' => 'AccountingController@get_add_coa_category'
    ])->where('t', '[0-9]+');
    Route::post('add/coa/category/{t}', [
        'as' => 'add_coa_category',
        'uses' => 'AccountingController@post_add_coa_category'
    ])->where('t', '[0-9]+');
    Route::get('add/client_loan_account/{t}', [
        'as' => 'add_client_loan_account',
        'uses' => 'AccountingController@get_add_client_loan_account'
    ])->where('t', '[0-9]+');
    Route::post('add/client_loan_account/{t}', [
        'as' => 'add_client_loan_account',
        'uses' => 'AccountingController@post_add_client_loan_account'
    ]);

    Route::get('getCLientAccountType/{type}', [
        'as' => 'getclienttype',
        'uses' => 'AccountingController@getCLientAccountType'
    ])->where('type', '[A-Za-z]+');

    Route::post('account_data', [
        'as' => 'account_data',
        'uses' => 'AccountingController@account_data'
    ]);
    Route::get('add/currency', [
        'as' => 'add_currency',
        'uses' => 'AccountingController@get_add_currency'
    ]);
    Route::post('add/currency', [
        'as' => 'add_currency',
        'uses' => 'AccountingController@post_add_currency'
    ]);
    Route::get('account_his', [
        'as' => 'account_his',
        'uses' => 'AccountingController@account_his'
    ]);
    Route::get('getjd/{id}', [
        'as' => 'getjd',
        'uses' => 'AccountingController@getJD'
    ])->where('id', '(.*)');
    Route::get('getreference/{id}/{bc}', [
        'as' => 'getreference',
        'uses' => 'AccountingController@getReference'
    ])->where('id', '[0-9]+')->where('bc', '[0-9]+');

    Route::get('pop_retrieve_date', [
        'as' => 'pop_retrieve_date',
        'uses' => 'LoanController@pop_retrieve_date'
    ]);

    Route::get('client_loan_account', [
        'as' => 'client_loan_account',
        'uses' => 'AccountingController@client_loan_account'
    ]);

    Route::get('cla_audit/{id}', [
        'as' => 'cla_audit',
        'uses' => 'AccountingController@cla_audit'
    ])->where('id', '[0-9]+');


    Route::get('vendor',[
        'as'=>'vendor',
        'uses'=>'vendorController@index'
    ]);

    Route::get('add_vendor_act/{id}',[
        'as'=>'add_vendor_act',
        'uses'=>'vendorController@get_add_vendor_act'
    ])->where('id', '[0-9]+');

    Route::post('post_add_vendor_act/{id}',[
        'as'=>'post_add_vendor_act',
        'uses'=>'vendorController@post_add_vendor_act'
    ])->where('id', '[0-9]+');
    Route::post('delet_vendor_act/{id}',[
        'as'=>'delet_vendor_act',
        'uses'=>'vendorController@delet_vendor_act'
    ])->where('id', '[0-9]+');

    Route::get('get_vendor_info/{id}/{from}/{to}',[
        'as'=>'get_vendor_info',
        'uses'=>'vendorController@get_vendor_info'
    ])->where(['id'=>'[0-9]+','from'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}', 'to'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}']);


    Route::get('acc_customer',[
        'as'=>'acc_customer',
        'uses'=>'vendorController@account_customer'
    ]);
    Route::get('get_add_customer_act/{id}',[
        'as'=>'get_add_customer_act',
        'uses'=>'vendorController@get_add_customer_act'
    ])->where('id', '[0-9]+');

    Route::post('post_add_edit_customer_act/{id}',[
        'as'=>'post_add_edit_customer_act',
        'uses'=>'vendorController@post_add_edit_customer_act'
    ])->where('id', '[0-9]+');
    Route::post('delet_acc_customer/{id}',[
        'as'=>'delet_acc_customer',
        'uses'=>'vendorController@delet_acc_customer'
    ])->where('id', '[0-9]+');

    Route::get('get_acc_customer_info/{id}/{from}/{to}',[
        'as'=>'get_acc_customer_info',
        'uses'=>'vendorController@get_acc_customer_info'
    ])->where(['id'=>'[0-9]+','from'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}', 'to'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}']);

    Route::get('transaction_code',[
        'as'=>'transaction_code',
        'uses'=>'TransactionCodeController@index'
    ]);
    Route::get('write_check',[
        'as'=>'write_check',
        'uses'=>'TransactionCodeController@write_check'
    ]);

    Route::post('post_write_check',[
        'as'=>'post_write_check',
        'uses'=>'TransactionCodeController@post_write_check'
    ]);

    Route::get('enterBill',[
        'as'=>'enterBill',
        'uses'=>'TransactionCodeController@enterBill'
    ]);

    Route::post('enterBill',[
        'as'=>'enterBill',
        'uses'=>'TransactionCodeController@enterBill'
    ]);

    Route::get('make_deposit',[
        'as'=>'make_deposit',
        'uses'=>'TransactionCodeController@make_deposit'
    ]);
    Route::post('make_deposit',[
        'as'=>'make_deposit',
        'uses'=>'TransactionCodeController@make_deposit'
    ]);

    Route::get('trans_funds',[
        'as'=>'trans_funds',
        'uses'=>'TransactionCodeController@trans_funds'
    ]);
    Route::post('trans_funds',[
        'as'=>'trans_funds',
        'uses'=>'TransactionCodeController@trans_funds'
    ]);

    //->where(['id'=>'[0-9]+','from'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}', 'to'=>'[A-Za-z^0-9]+|\d{4}(?:-\d{1,2}){2}']);

    Route::get('getGlReport', [
        'as' => 'getGlReport',
        'uses' => 'AccountingController@getGlReport'
    ]);

    Route::get('getGlReportAjax', [
        'as' => 'getGlReportAjax',
        'uses' => 'AccountingController@getGlReportAjax'
    ]);

    Route::get('journal/edit/{id}', [
        'as' => 'edit_journal',
        'uses' => 'LoanController@getEditJournal'
    ])->where('id', '[0-9]+');

    Route::post('journal/edit/{id}', [
        'as' => 'edit_journal',
        'uses' => 'LoanController@postEditJournal'
    ])->where('id', '[0-9]+');
    
});

/* start NBC Report */

Route::group(['prefix' => 'nbc_report'], function() {
    Route::get('balance_sheet', [
        'as' => 'bs_mfi01',
        'uses' => 'NBCReportController@getBalanceSheet'
    ]);

    Route::get('balance_sheet_pl', [
        'as' => 'pl_mfi02',
        'uses' => 'NBCReportController@getBalanceSheetPl'
    ]);

    Route::get('net_open_position', [
        'as' => 'net_open_position',
        'uses' => 'NBCReportController@getNetOpenPosition'
    ]);

    Route::get('denominator', [
        'as' => 'denominator',
        'uses' => 'NBCReportController@getDenominator'
    ]);

    Route::get('solvency_ration_for_microfinance_institute', [
        'as' => 'solvency',
        'uses' => 'NBCReportController@getSolvency'
    ]);

    Route::get('source_of_financing', [
        'as' => 'source_financing',
        'uses' => 'NBCReportController@getSourceFinancing'
    ]);

    Route::get('calculation_of_foreign_currency_exposure', [
        'as' => 'calculation',
        'uses' => 'NBCReportController@getCalculation'
    ]);

    Route::get('ngos_micro_finance', [
        'as' => 'ngos_microfinance',
        'uses' => 'NBCReportController@getNgosMicrofinance'
    ]);

    Route::get('list_information', [
        'as' => 'list_info',
        'uses' => 'NBCReportController@getListInfo'
    ]);

    Route::get('liquidity_ratio_for_microfinance_institute', [
        'as' => 'liquidity',
        'uses' => 'NBCReportController@getLiquidity'
    ]);

    Route::get('list_of_large_exposure', [
        'as' => 'list_large_exposure',
        'uses' => 'NBCReportController@getListLargeExposure'
    ]);

    Route::get('list_of_loan', [
        'as' => 'nbc_list_loan',
        'uses' => 'NBCReportController@getListLoan'
    ]);

    Route::get('loan_classification', [
        'as' => 'loan_classification',
        'uses' => 'NBCReportController@getLoanClassification'
    ]);

    Route::get('deposit_breakdown_by_currency', [
        'as' => 'deposit_breakdown',
        'uses' => 'NBCReportController@getDepositBreakdown'
    ]);

    Route::get('loan_breakdown_by_currency', [
        'as' => 'loan_breakdown',
        'uses' => 'NBCReportController@getLoanBreakdown'
    ]);

    Route::get('loan_breakdown_by_category', [
        'as' => 'loan_breakdown_category',
        'uses' => 'NBCReportController@getLoanBreakdownCategory'
    ]);

    Route::get('break_down_of_deposits', [
        'as' => 'breakdown_deposit',
        'uses' => 'NBCReportController@getBreakDownDeposit'
    ]);

    Route::get('off_balanch_sheet_nbc', [
        'as' => 'off_balanch',
        'uses' => 'NBCReportController@getOffBalanch'
    ]);
});

/* end nbc report */

/* start setting */
Route::group(['prefix' => 'setting'], function() {
    Route::get('holiday', [
        'as' => 'date_holiday',
        'uses' => 'LoanController@getCalendar'
    ]);
});
/* end setting */

/* start HR Management */
Route::group(['prefix' => 'hr_management'], function() {
    Route::get('add_staff', [
        'as' => 'add_staff',
        'uses' => 'StaffController@addStaff'
    ]);
    Route::post('add_staff', [
        'as' => 'add_staff',
        'uses' => 'StaffController@postAdd'
    ]);

    Route::get('staff_summary', [
        'as' => 'staff_summary',
        'uses' => 'StaffController@summaryStaff'
    ]);

    Route::get('staff_history/{id}', [
        'as' => 'staff_history',
        'uses' => 'StaffController@historyStaff'
    ])->where('id', '[0-9]+');

    Route::get('edit_staff/{id}', [
        'as' => 'edit_staff',
        'uses' => 'StaffController@editStaff'
    ])->where('id', '[0-9]+');

    Route::post('edit_staff/{id}', [
        'as' => 'edit_staff',
        'uses' => 'StaffController@postEditStaff'
    ])->where('id', '[0-9]+');

    Route::get('update_staff/{id}', [
        'as' => 'update_staff',
        'uses' => 'StaffController@getUpdateStaff'
    ])->where('id', '[0-9]+');

    Route::post('update_staff/{id}', [
        'as' => 'update_staff',
        'uses' => 'StaffController@postUpdateStaff'
    ])->where('id', '[0-9]+');
});

/* end HR Management */

/* start administation */

Route::group(['prefix' => 'administration'], function() {
    Route::get('list_administration', [
        'as' => 'list_administration',
        'uses' => 'AdminController@getAdmin'
    ]);

    Route::get('enable', [
        'as' => 'enable_till',
        'uses' => 'AdminController@enableTill'
    ])->where('id', '[0-9]+');

    Route::get('set_currency_rate', [
        'as' => 'set_currency_rate',
        'uses' => 'AdminController@setCurrencyRate'
    ]);

    Route::post('set_currency_rate', [
        'as' => 'set_currency_rate',
        'uses' => 'AdminController@postSetCurrencyRate'
    ]);

    Route::get('list_currency_rate', [
        'as' => 'list_currency_rate',
        'uses' => 'AdminController@listCurrencyRate'
    ]);

    Route::get('getLatestRate', [
        'as' => 'getLatestRate',
        'uses' => 'AdminController@getLatestRate'
    ]);

    Route::get('currencie/add', [
        'as' => 'add_currencie',
        'uses' => 'AdminController@getAddCurrency'
    ]);
    Route::post('currencie/add', [
        'as' => 'add_currencie',
        'uses' => 'AdminController@postAddCurrency'
    ]);

    Route::get('currencie/edit/{id}', [
        'as' => 'edit_currencie',
        'uses' => 'AdminController@edit_currency'
    ])->where('id', '[0-9]+');

    Route::post('currencie/edit/{id}', [
        'as' => 'edit_currencie',
        'uses' => 'AdminController@post_edit_currency'
    ])->where('id', '[0-9]+');

    Route::get('list_currencie', [
        'as' => 'list_currencie',
        'uses' => 'AdminController@listCurrency'
    ]);

    Route::get('CBC_Report', [
        'as' => 'CBC_Report',
        'uses' => 'CBCReportController@CBC_Report'
    ]);
    Route::get('CBC_index', [
        'as' => 'CBC_index',
        'uses' => 'CBCReportController@CBC_index'
    ]);

    Route::get('disable/{id}', [
        'as' => 'disable_currency',
        'uses' => 'AdminController@disableCurrency'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_currency',
        'uses' => 'AdminController@enableCurrency'
    ])->where('id', '[0-9]+');

    Route::get('asset_depreciation_verify', [
        'as' => 'asset_depreciation_verify',
        'uses' => 'AdminController@asset_depreciation_verify'
    ]);

    Route::get('asset_depreciation_execute', [
        'as' => 'asset_depreciation_execute',
        'uses' => 'AdminController@asset_depreciation_execute'
    ]);
    Route::post('asset_depreciation_execute', [
        'as' => 'asset_depreciation_execute',
        'uses' => 'AdminController@asset_depreciation_execute'
    ]);
});
/* end administation */

/* * chisOfteller tillOperator
 * Teller management
 */

Route::group(['prefix' => 'teller'], function() {
    /*
     * Chief of Teller cats
     */
    Route::get('chiefofteller', [
        'as' => 'chiefofteller',
        'uses' => 'TellerController@chiefofteller'
    ]);
    Route::get('assignTo/{id}/{currency_id}', [
        'as' => 'assignTo',
        'uses' => 'TellerController@assignTo'
    ])->where('id', '[0-9]+');

    Route::get('openTill/{id}', [
        'as' => 'openTill',
        'uses' => 'TellerController@openTill'
    ])->where('id', '[0-9]+');

    Route::post('updateTill/{id}', [
        'as' => 'updateTill',
        'uses' => 'TellerController@updateTill'
    ])->where('id', '[0-9]+');

    Route::post('notification_action', [
        'as' => 'notification_action',
        'uses' => 'TellerController@Post_Notification'
    ]);

    Route::post('createtill', [
        'as' => 'createtill',
        'uses' => 'TellerController@createtill'
    ]);

    Route::get('till_account_summary', [
        'as' => 'till_account_summary',
        'uses' => 'TellerController@TillerAccountSummary'
    ]);
    Route::get('getBrand', [
        'as' => 'getBrand',
        'uses' => 'TellerController@getBrand'
    ]);
    
    /*
     * End of Chief of Teller cats
     */
    /*
     * Teller operator
     */
    Route::get('telloperation', [
        'as' => 'telloperation',
        'uses' => 'TellerController@telloperation'
    ]);
    Route::get('transaction', [
        'as' => 'transaction',
        'uses' => 'TellerController@transaction'
    ]);

    Route::get('currency_exchange', [
        'as' => 'currency_exchange',
        'uses' => 'AdminController@getCurrency'
    ]);

    Route::get('getExchangeRate', [
        'as' => 'getExchangeRate',
        'uses' => 'AdminController@getExchangeRate'
    ]);

    Route::post('currency_exchange', [
        'as' => 'currency_exchange',
        'uses' => 'AdminController@postCurrencyExchange'
    ]);

    Route::get('list_currency_exchange', [
        'as' => 'list_currency_exchange',
        'uses' => 'AdminController@listCurrencyExchange'
    ]);

    Route::get('issueTill', [
        'as' => 'issueTill',
        'uses' => 'TellerController@issueTill'
    ]);

    Route::post('issue_till_post', [
        'as' => 'issue_till_post',
        'uses' => 'TellerController@issue_till_post'
    ]);
    Route::get('transsummary', [
        'as' => 'transsummary',
        'uses' => 'TellerController@transactionSummry'
    ]);

    Route::get('trans_searching', [
        'as' => 'trans_searching',
        'uses' => 'TellerController@selectTransTill'
    ]); //->where('id','[0-9]+');

    Route::get('trans_result', [
        'as' => 'trans_result',
        'uses' => 'TellerController@trans_result'
    ]);
    Route::post('transfer_till_from_chief', [
        'as' => 'transfer_till_from_chief',
        'uses' => 'TellerController@transfer_till_from_chief'
    ]);

    Route::get('transfer_till', [
        'as' => 'transfer_till',
        'uses' => 'TellerController@TransferTill'
    ]);

    Route::post('post_trans_till', [
        'as' => 'post_trans_till',
        'uses' => 'TellerController@postsTransferTill'
    ]);
    Route::get('return_till_data', [
        'as' => 'return_till_data',
        'uses' => 'TellerController@ReturnTillData'
    ]);
    Route::post('post_return_till', [
        'as' => 'post_return_till',
        'uses' => 'TellerController@PostReturnTill'
    ]);
    Route::post('returnTillNotification', [
        'as' => 'returnTillNotification',
        'uses' => 'TellerController@returnTillNotification'
    ]);

    Route::get('getExchangeRateJD', [
        'as' => 'getExchangeRateJD',
        'uses' => 'AdminController@getExchangeRateJD'
    ]);
    Route::get('disburse_list',[
        'as'=>'disburse_list',
        'uses'=>'TellerController@disburse_list'
    ]);
    Route::post('post_expense',[
        'as'=>'post_expense',
        'uses'=>'TellerController@Post_expense'
    ]);
    Route::get('currency',[
        'as'=>'currency',
        'uses'=>'TellerController@get_currency'
    ]);  Route::get('get_till_account',[
        'as'=>'get_till_account',
        'uses'=>'TellerController@get_till_account'
    ]);
    Route::get('getTeller',[
        'as'=>'getTeller',
        'uses'=>'TellerController@getTeller'
    ]);
    Route::get('deposit',[
        'as'=>'deposit',
        'uses'=>'TellerController@getDeposit'
    ]);
    Route::post('deposit',[
        'as'=>'deposit',
        'uses'=>'TellerController@postDeposit'
    ]);
    Route::get('get_withdraw',[
        'as'=>'get_withdraw',
        'uses'=>'TellerController@getWithdraw'
    ]);
    Route::post('post_withdraw',[
        'as'=>'post_withdraw',
        'uses'=>'TellerController@postWithdraw'
    ]);
    Route::get('teller_dwjournal/{id}', [
        'as' => 'teller_dwjournal',
        'uses' => 'TellerController@teller_dwjournal'
    ])->where('id', '[0-9]+');


    Route::post('delete_notification/{id}',[
        'as'=>'delete_notification',
        'uses'=>'TellerController@delete_notification'
    ])->where('id', '[0-9]+');

    Route::get('print/deposit/{slips_id}',[
        'as'=>'print_deposit',
        'uses'=>'PrintController@deposit'
    ])->where(['slips_id' => '[0-9]+']);
    
    Route::get('print/withdraw/{slips_id}',[
        'as'=>'print_withdraw',
        'uses'=>'PrintController@withdraw'
    ])->where(['slips_id'=>'[0-9]+']);

    Route::get('receipt-detail',[
        'as'=>'teller_receipt_detail',
        'uses'=>'TellerController@teller_receipt_detail'
    ])->where(['slips_id'=>'[0-9]+']);
    Route::get('receipt-summary',[
        'as'=>'teller_receipt_summary',
        'uses'=>'TellerController@teller_receipt_summary'
    ])->where(['slips_id'=>'[0-9]+']);

    Route::get('get-client',[
        'as'=>'get_client_id',
        'uses'=>'TellerController@get_client_id'
    ])->where(['slips_id'=>'[0-9]+']);
    /*
     *
     * End of Teller operator
     */
});

/*
 * Notification
 */
Route::get('/master_report', ['as'=>'master_report', 'uses'=>'ReportController@getMasterReport']);

Route::group(['prefix' => 'notification'], function() {

    Route::get('get_not', [
        'as' => 'get_not',
        'uses' => 'NotificationController@getNot'
    ]);
    Route::get('index', [
        'as' => 'index',
        'uses' => 'NotificationController@index'
    ]);
    Route::get('notification_data/{id}/{type}', [
        'as' => 'notification_data',
        'uses' => 'NotificationController@getNotification'
    ])->where('id', '[0-9]+');

    Route::get('repayment_loan_data/{id}/{type}', [
        'as' => 'repayment_loan_data',
        'uses' => 'NotificationController@repayment_loan_data'
    ])->where('id', '[0-9]+');

    Route::post('approve_repayments/{id}', [
        'as' => 'approve_repayments',
        'uses' => 'NotificationController@ApproveRepayments'
    ])->where('id', '[0-9]+');
    Route::post('rejects_repayment/{id}', [
        'as' => 'rejects_repayment',
        'uses' => 'NotificationController@RejectsRepayment'
    ])->where('id', '[0-9]+');

    Route::post('repay_change_till_acc/{id}', [
        'as' => 'repay_change_till_acc',
        'uses' => 'NotificationController@repay_change_till_acc'
    ])->where('id', '[0-9]+');

    Route::get('fee_charge_repayment/{id}/{type}', [
        'as' => 'fee_charge_repayment',
        'uses' => 'NotificationController@fee_charge_repayment'
    ])->where('id','[0-9]+'); //->where(['n_source_id' =>'[0-9]+','not_id'=>'[0-9]+', 'type' => '[A-Za-z]+'])

    Route::post('approve_fee_charge_repayment/{id}',[
        'as'=>'approve_fee_charge_repayment',
        'uses'=>'NotificationController@approve_fee_charge_repayment'
    ])->where('id','[0-9]+');

    Route::post('rejects_feeCharges/{id}',[
        'as'=>'rejects_feeCharges',
        'uses'=>'NotificationController@RejectsFeeCharges'
    ])->where('id','[0-9]+');

    Route::post('feeCharge_repay_change_till_acc/{id}',[
        'as'=>'feeCharge_repay_change_till_acc',
        'uses'=>'NotificationController@feeCharge_repay_change_till_acc'
    ])->where('id','[0-9]+');

     Route::post('ApproveDisburse/{id}',[
        'as'=>'ApproveDisburse',
        'uses'=>'NotificationController@ApproveDisburse'
    ])->where('id','[0-9]+');

    Route::post('RejectsDisburse/{id}',[
        'as'=>'RejectsDisburse',
        'uses'=>'NotificationController@RejectsDisburse'
    ])->where('id','[0-9]+');

    Route::get('get_disbursement/{id}/{type}',[
        'as'=>'get_disbursement',
        'uses'=>'NotificationController@Get_disbursement'
    ])->where('id', '[0-9]+');

    Route::post('change_till_account/{id}',[
        'as'=>'change_till_account',
        'uses'=>'NotificationController@change_till_account'
    ])->where('id', '[0-9]+');
});


/*
 * End of Notification
 */

Route::group(['prefix' => 'setting'], function() {

    Route::get('setting', [
        'as' => 'setting',
        'uses' => 'SettingController@index'
    ]);
    Route::post('setting', [
        'as' => 'setting',
        'uses' => 'SettingController@index'
    ]);


    Route::get('tableToCsv', [
        'as' => 'tableToCsv',
        'uses' => 'SettingController@tableToCsv'
    ]);

    Route::get('export_db', [
        'as' => 'export_db',
        'uses' => 'SettingController@export_db'
    ]);
});
// Credit Summary
Route::group(['prefix' => 'credits'], function() {
    Route::get('credit_summary', [
        'as' => 'creditSummary',
        'uses' => 'CreditController@get_credit_classification'
    ]);
    Route::get('exe_provision/{id}', [
        'as' => 'exe_provision',
        'uses' => 'CreditController@exe_provision'
    ])->where('id', '[0-9]+');
});


Route::group(['prefix' => 'asset'], function() {
    Route::get('asset_index', [
        'as' => 'asset_index',
        'uses' => 'AssetController@index'
    ]);
    Route::get('add', [
        'as' => 'add_asset',
        'uses' => 'AssetController@create'
    ]);
    Route::post('add', [
        'as' => 'add_asset',
        'uses' => 'AssetController@store'
    ]);

    Route::get('ajax_tag_num', [
        'as' => 'ajax_tag_num',
        'uses' => 'AssetController@ajax_tag_num'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_asset',
        'uses' => 'AssetController@edit'
    ])->where('id', '[0-9]+');
    Route::post('edit/{id}', [
        'as' => 'edit_asset',
        'uses' => 'AssetController@update'
    ])->where('id', '[0-9]+');

    Route::get('destroy/{id}', [
        'as' => 'delete_asset',
        'uses' => 'AssetController@destroy'
    ])->where('id', '[0-9]+');

    Route::post('adjust/{id}', [
        'as' => 'adjust_asset',
        'uses' => 'AssetController@adjust'
    ])->where('id', '[0-9]+');

    Route::get('dispose/{id}', [
        'as' => 'dispose_asset',
        'uses' => 'AssetController@dispose'
    ])->where('id', '[0-9]+');
    Route::post('dispose/{id}', [
        'as' => 'dispose_asset',
        'uses' => 'AssetController@dispose_update'
    ])->where('id', '[0-9]+');

    Route::get('write_off/{id}', [
        'as' => 'write_off_asset',
        'uses' => 'AssetController@write_off'
    ])->where('id', '[0-9]+');
    Route::post('write_off/{id}', [
        'as' => 'write_off_asset',
        'uses' => 'AssetController@write_off_update'
    ])->where('id', '[0-9]+');

    Route::get('fa_detail', [
        'as' => 'fa_detail',
        'uses' => 'AssetController@fa_detail'
    ]);

    Route::get('depreciation_summary', [
        'as' => 'depreciation_summary',
        'uses' => 'AssetController@depreciation_summary'
    ]);
});

Route::group(['prefix' => 'audit'], function() {
    Route::get('get_audit', [
        'as' => 'get_audit',
        'uses' => 'AuditController@index'
    ]);

});


Route::group(['prefix' => 'contract'], function() {
    Route::get('condo/{id}', [
        'as' => 'condo',
        'uses' => 'ContractController@condo'
    ])->where('id', '[0-9]+');
    Route::get('land/{id}', [
        'as' => 'land',
        'uses' => 'ContractController@land'
    ])->where('id', '[0-9]+');
    Route::get('house/{id}', [
        'as' => 'house',
        'uses' => 'ContractController@house'
    ])->where('id', '[0-9]+');
    Route::get('shop/{id}',[
        'as' => 'shop',
        'uses' => 'ContractController@shop'
    ])->where('id', '[0-9]+');

    Route::get('print_payoff_letter/{id}',[
        'as' => 'print_payoff_letter',
        'uses' => 'ContractController@printPayOffLetter'
    ])->where('id', '[0-9]+');
});

Route::group(['prefix' => 'migration'], function() {
    Route::get('create-drawdown-account', [
        'as' => 'create-drawdown-account',
        'uses' => 'MigrationController@CreateDradownAcc'
    ]);
    Route::get('update-loan', [
        'as' => 'update-loan',
        'uses' => 'MigrationController@UpdateLoan'
    ]);
    Route::get('update-loan-amount', [
        'as' => 'update-loan-amount',
        'uses' => 'MigrationController@UpdateLoanAmount'
    ]);
    Route::get('update-loan-account-balance', [
        'as' => 'update-loan-account-balance',
        'uses' => 'MigrationController@UpdateLoanAccountBalance'
    ]);
    Route::get('update-loan-unit', [
        'as' => 'update-loan-unit',
        'uses' => 'MigrationController@UpdateLoanUnit'
    ]);
    Route::get('update-loan-approval', [
        'as' => 'update-loan-approval',
        'uses' => 'MigrationController@LoanApproval'
    ]);
    Route::get('update-loan-repayment-schedule', [
        'as' => 'update-loan-repayment-schedule',
        'uses' => 'MigrationController@UpdateRepaymentSchedule'
    ]);
    Route::get('get-migrate-deposit', [
        'as' => 'get-migrate-deposit',
        'uses' => 'MigrationController@GetMigrateDepositData'
    ]);
    Route::get('post-migrate-deposit', [
        'as' => 'post-migrate-deposit',
        'uses' => 'MigrationController@PostMigrateDepositData'
    ]);
    Route::get('get-migrate-installment', [
        'as' => 'get-migrate-installment',
        'uses' => 'MigrationController@GettMigrateInstallment'
    ]);
    Route::get('post-migrate-installment', [
        'as' => 'post-migrate-installment',
        'uses' => 'MigrationController@PostMigrateInstallment'
    ]);

    Route::get('get-migrate-installment-deposit', [
        'as' => 'get-migrate-installment-deposit',
        'uses' => 'MigrationController@GetMigrateInstallmentDeposit'
    ]);
    Route::get('post-migrate-installment-deposit', [
        'as' => 'post-migrate-installment-deposit',
        'uses' => 'MigrationController@PostMigrateInstallmentDeposit'
    ]);
    Route::get('post-migrate-paid-schecdule', [
        'as' => 'post-migrate-paid-schecdule',
        'uses' => 'MigrationController@LoanPaymentToPaid'
    ]);
    
});


/* start BCash section */

Route::group(['prefix' => 'bcash'], function() {

    Route::get('index', [
        'as' => 'BE_Cash',
        'uses' => 'BCashController@getBCash'
    ]);
    Route::get('getAutocomplete', [
        'as' => 'get_autocomplete',
        'uses' => 'BCashController@getAutocomplete'
    ]);
    Route::get('getLoanDetail', [
        'as' => 'getLoanDetail',
        'uses' => 'BCashController@getLoanDetail'
    ]);

    Route::get('print/{transaction_id}',[
        'as'=>'print_becash',
        'uses'=>'BCashController@printBECash'
    ])->where(['transaction_id' => '[0-9]+']);

    Route::get('printitem/{transaction_id}/{description}',[
        'as'=>'printitem_becash',
        'uses'=>'BCashController@printItemBECash'
    ])->where(['transaction_id' => '[0-9]+']);


    Route::post('addBcash', [
        'as' => 'add_bcash',
        'uses' => 'BCashController@addBcash'
    ])->where('id', '[0-9]+');

    Route::get('authorized/{transaction_id}', [
        'as' => 'authorized',
        'uses' => 'BCashController@authorized'
    ])->where('id', '[0-9]+');

    Route::get('rejceted/{transaction_id}', [
        'as' => 'rejceted',
        'uses' => 'BCashController@rejceted'
    ])->where('id', '[0-9]+');

    
});

/* start Sale section */

Route::group(['prefix' => 'sale'], function() {
    Route::get('add', [
        'as' => 'add_sale',
        'uses' => 'SaleController@create'
    ]);
    Route::get('change_unit', [
        'as' => 'change_unit',
        'uses' => 'SaleController@change_unit'
    ]);    

    Route::post('add', [
        'as' => 'add_sale',
        'uses' => 'SaleController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_sale',
        'uses' => 'SaleController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_sale',
        'uses' => 'SaleController@postEdit'
    ])->where('id', '[0-9]+');
    
    Route::get('saleperson-list', [
        'as' => 'saleperson_list',
        'uses' => 'SaleController@saleperson_list'
    ])->where('id', '[0-9]+');
    Route::get('add_saleperson', [
        'as' => 'add_saleperson',
        'uses' => 'SaleController@getAddSalePerson'
    ])->where('id', '[0-9]+');
    Route::post('add_saleperson', [
        'as' => 'add_saleperson',
        'uses' => 'SaleController@postAddSalePerson'
    ])->where('id', '[0-9]+');
    Route::get('edit_saleperson/{id}', [
        'as' => 'edit_saleperson',
        'uses' => 'SaleController@getEditSalePerson'
    ])->where('id', '[0-9]+');

    Route::post('edit_saleperson/{id}', [
        'as' => 'edit_saleperson',
        'uses' => 'SaleController@postEditSalePerson'
    ])->where('id', '[0-9]+');

    Route::get('enable/{id}', [
        'as' => 'enable_saleperson',
        'uses' => 'SaleController@salePersonEnable'
    ])->where('id', '[0-9]+');
    Route::get('disable/{id}', [
        'as' => 'disable_saleperson',
        'uses' => 'SaleController@salePersonDisable'
    ])->where('id', '[0-9]+');
    

    Route::get('accept/{id}', [
        'as' => 'saleAccept',
        'uses' => 'SaleController@getSaleAcceptOrder'
    ])->where('id', '[0-9]+');

    Route::post('accept/{id}', [
        'as' => 'saleAccept',
        'uses' => 'SaleController@saleAcceptOrder'
    ])->where('id', '[0-9]+');

    Route::get('transfer-balance/{id}', [
        'as' => 'transferBalance',
        'uses' => 'LoanController@getTransferBalance'
    ])->where('id', '[0-9]+');
    Route::get('transfer_inter_project', [
        'as' => 'transfer_inter_project',
        'uses' => 'LoanController@transferInterProject'
    ]);
    
    Route::post('transfer-balance/{id}', [
        'as' => 'transferBalance',
        'uses' => 'LoanController@postGetTransferBalance'
    ])->where('id', '[0-9]+');

    Route::post('postTransferBalance', [
        'as' => 'postTransferBalance',
        'uses' => 'LoanController@postTransferBalance'
    ]);
    Route::post('postTransferInterProject', [
        'as' => 'postTransferInterProject',
        'uses' => 'LoanController@postTransferInterProject'
    ]);
    

    Route::get('getTransferBalanceList', [
        'as' => 'getTransferBalanceList',
        'uses' => 'LoanController@getTransferBalanceList'
    ]);
    Route::get('transfer_balance_approve/{id}', [
        'as' => 'transfer_balance_approve',
        'uses' => 'LoanController@transfer_balance_approve'
    ])->where('id', '[0-9]+');
    Route::get('transfer_balance_reject/{id}', [
        'as' => 'transfer_balance_reject',
        'uses' => 'LoanController@transfer_balance_reject'
    ])->where('id', '[0-9]+');
    

       

    Route::get('list', [
        'as' => 'list_sale',
        'uses' => 'SaleController@listSale'
    ]);

    Route::get('detail/{id}', [
        'as' => 'sale_detail',
        'uses' => 'SaleController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('getClientForSale', [
        'as' => 'getClientForSale',
        'uses' => 'AccountingController@getClientForSale'
    ]);

    Route::get('get-payment-term', [
        'as' => 'get-payment-term',
        'uses' => 'SaleController@getPaymentTerm'
    ]);
    Route::get('getPaymentTermById', [
        'as' => 'getPaymentTermById',
        'uses' => 'SaleController@getPaymentTermById'
    ]);   

    Route::get('getSale', [
        'as' => 'getSale',
        'uses' => 'SaleController@getSale'
    ]);
    Route::get('getSalePersonParent/{lavel}', [
        'as' => 'getSalePersonParent',
        'uses' => 'SaleController@getSalePersonParent'
    ]);

    Route::get('sale_report_deposit', [
        'as' => 'sale_report_deposit',
        'uses' => 'SaleController@getSaleReportDeposit'
    ]);
    Route::get('sale_report_detail', [
        'as' => 'sale_report_detail',
        'uses' => 'ReportController@getSaleReportDetail'
    ]);
    Route::get('sale_report_summary', [
        'as' => 'sale_report_summary',
        'uses' => 'InvoiceController@getSaleReportSummary'
    ]);

    Route::get('sale_report_summary_by_seller', [
        'as' => 'sale_report_summary_by_seller',
        'uses' => 'InvoiceController@getSaleReportSummaryBySeller'
    ]);

    Route::get('sale_report_summary_by_project', [
        'as' => 'sale_report_summary_by_project',
        'uses' => 'InvoiceController@getSaleReportSummaryByProject'
    ]);
    
    Route::get('list_sale_commission_detail/{id}', [
        'as' => 'list_sale_commission_detail',
        'uses' => 'SaleController@getDetailCommission'
    ])->where('id', '[0-9]+');
    Route::get('request_withdraw/{id}', [
        'as' => 'request_withdraw',
        'uses' => 'SaleController@getRequestWithdrawCommission'
    ])->where('id', '[0-9]+');

    Route::post('request_all_withdraw', [
        'as' => 'request_all_withdraw',
        'uses' => 'SaleController@postRequestAllWithdrawCommission'
    ])->where('id', '[0-9]+');
    Route::post('request_all_withdraw_loan_prev', [
        'as' => 'request_all_withdraw_loan_prev',
        'uses' => 'SaleController@postRequestAllWithdrawCommissionPrev'
    ])->where('id', '[0-9]+');

    Route::post('postrequest_withdraw', [
        'as' => 'postrequest_withdraw',
        'uses' => 'SaleController@postRequestWithdrawCommission'
    ])->where('id', '[0-9]+');

    

    Route::get('getRequestWithdrawCommissionList', [
        'as' => 'getRequestWithdrawCommissionList',
        'uses' => 'SaleController@getRequestWithdrawCommissionList'
    ]);
   
    
    Route::get('approvedCommissionSetting/{id}', [
        'as' => 'approvedCommissionSetting',
        'uses' => 'SaleController@approvedCommissionSetting'
    ])->where('id', '[0-9]+');

    Route::get('sendBackCommissionSetting/{id}', [
        'as' => 'sendBackCommissionSetting',
        'uses' => 'SaleController@sendBackCommissionSetting'
    ])->where('id', '[0-9]+');
    Route::post('sendBackCommissionSetting/{id}', [
        'as' => 'sendBackCommissionSetting',
        'uses' => 'SaleController@postSendBackCommissionSetting'
    ])->where('id', '[0-9]+');

    Route::get('sm_commission_approve/{id}', [
        'as' => 'sm_commission_approve',
        'uses' => 'SaleController@sm_commission_approve'
    ])->where('id', '[0-9]+');
    
    Route::get('accountant_commission_approve/{id}', [
        'as' => 'accountant_commission_approve',
        'uses' => 'SaleController@accountant_commission_approve'
    ])->where('id', '[0-9]+');
    Route::get('hof_commission_approve/{id}', [
        'as' => 'hof_commission_approve',
        'uses' => 'SaleController@hof_commission_approve'
    ])->where('id', '[0-9]+');
    Route::get('chairman_commission_approve/{id}', [
        'as' => 'chairman_commission_approve',
        'uses' => 'SaleController@chairman_commission_approve'
    ])->where('id', '[0-9]+');


    Route::get('get_mark_commission_payment/{id}', [
        'as' => 'get_mark_commission_payment',
        'uses' => 'SaleController@mark_commission_payment'
    ])->where('id', '[0-9]+');

    Route::post('mark_commission_payment', [
        'as' => 'mark_commission_payment',
        'uses' => 'SaleController@postMark_commission_payment'
    ])->where('id', '[0-9]+');

    Route::get('api_member_commission', [
        'as' => 'api_member_commission',
        'uses' => 'UserController@getMemberCommissionList'
    ]);
    Route::get('api_request_commission', [
        'as' => 'api_request_commission',
        'uses' => 'UserController@getRequestCommissionList'
    ]);  
    Route::get('api_commission_paid', [
        'as' => 'api_commission_paid',
        'uses' => 'UserController@getCommissionList'
    ]); 

    Route::post('all_commission_approve', [
        'as' => 'all_commission_approve',
        'uses' => 'SaleController@allCommissionApprove'
    ]);   

    Route::post('all_commission_PrintOrMarkPayment', [
        'as' => 'all_commission_PrintOrMarkPayment',
        'uses' => 'SaleController@allCommissionPrintOrMarkPayment'
    ]); 

});

Route::group(['prefix' => 'commission'], function() {
    /* Sale commission */
    Route::get('commission_rate', [
        'as' => 'commission_rate_list',
        'uses' => 'CommissionRateController@index',
    ]);
    Route::post('commission_rate', [
        'as' => 'commission_rate_post',
        'uses' => 'CommissionRateController@store',
    ]);
    Route::get('getCommissionSettingList', [
        'as' => 'getCommissionSettingList',
        'uses' => 'SaleController@getCommissionSettingList'
    ]);
    Route::get('getCommissionSettingSendBackList', [
        'as' => 'getCommissionSettingSendBackList',
        'uses' => 'SaleController@getCommissionSettingSendBackList'
    ]);
    Route::get('list_sale_commission', [
        'as' => 'list_sale_commission',
        'uses' => 'ReportController@listSaleCommission'
    ]);
    Route::get('list_prev_commission', [
        'as' => 'list_prev_commission',
        'uses' => 'ReportController@listPrevCommission'
    ]);
    
    Route::get('sale_commission_withdraw_list', [
        'as' => 'sale_commission_withdraw_list',
        'uses' => 'SaleController@SaleCommissionWithdrawList'
    ]);
    
    Route::get('sale_commission_history_list', [
        'as' => 'sale_commission_history_list',
        'uses' => 'SaleController@SaleCommissionHistoryList'
    ]);
    Route::get('print_commission/{id}',[
        'as'=>'print_commission',
        'uses'=>'SaleController@printCommission'
    ])->where(['id'=>'[0-9]+']);
   
    /* End Sale commision */
});
/* End Sale section */

/* start Invoice section */

Route::group(['prefix' => 'invoice'], function() {
    Route::get('add', [
        'as' => 'add_invoice',
        'uses' => 'InvoiceController@create'
    ]);

    Route::post('add', [
        'as' => 'add_invoice',
        'uses' => 'InvoiceController@post_create'
    ]);

    Route::get('edit/{id}', [
        'as' => 'edit_invoice',
        'uses' => 'InvoiceController@getEdit'
    ])->where('id', '[0-9]+');

    Route::post('edit/{id}', [
        'as' => 'edit_invoice',
        'uses' => 'InvoiceController@postEdit'
    ])->where('id', '[0-9]+');


    Route::get('list', [
        'as' => 'list_invoice',
        'uses' => 'InvoiceController@listInvoice'
    ]);

    Route::get('detail/{id}', [
        'as' => 'invoice_detail',
        'uses' => 'InvoiceController@getDetail'
    ])->where('id', '[0-9]+');

    Route::get('add_invoice_payment/{id}', [
        'as' => 'add_invoice_payment',
        'uses' => 'InvoiceController@getPaymentDetail'
    ])->where('id', '[0-9]+');

    
    Route::post('add_invoice_payment/{id}', [
        'as' => 'add_invoice_payment',
        'uses' => 'InvoiceController@postPaymentDetail'
    ])->where('id', '[0-9]+');

});
/* End Invoice section */

/* Property management */
Route::group(['prefix' => 'property'], function() {

    Route::get('customer_actual_payment', [
        'as' => 'customer_actual_payment',
        'uses' => 'PropertyController@listCustomerActual'
    ]);
    Route::get('customer_init', [
        'as' => 'customer_init',
        'uses' => 'PropertyController@listCustomerInit'
    ]);
    Route::get('customer_init_detail/{id}', [
        'as' => 'customer_init_detail',
        'uses' => 'PropertyController@customerInitDetail'
    ]);
    
    Route::get('property_payment_term', [
        'as' => 'property_payment_term',
        'uses' => 'PropertyController@propertyPaymentTerm'
    ]);
    Route::get('property_service', [
        'as' => 'property_service',
        'uses' => 'PropertyController@propertyService'
    ]);
    Route::get('add', [
        'as' => 'add_customer_init',
        'uses' => 'PropertyController@add_customer_init'
    ]);  

    Route::post('add', [
        'as' => 'add_customer_init',
        'uses' => 'PropertyController@post_customer_init'
    ]);
    Route::get('add_customer_actual_payment/{id}/{t_from}/{t_to}', [
        'as' => 'add_customer_actual_payment',
        'uses' => 'PropertyController@add_customer_actual_payment'
    ]); 
    Route::get('edit_customer_actual_payment/{id}/{t_from}/{t_to}', [
        'as' => 'edit_customer_actual_payment',
        'uses' => 'PropertyController@edit_customer_actual_payment'
    ]); 
    
    Route::post('post_customer_actual_payment', [
        'as' => 'post_customer_actual_payment',
        'uses' => 'PropertyController@post_customer_actual_payment'
    ]);
    Route::get('property_check_list', [
        'as' => 'property_check_list',
        'uses' => 'PropertyController@getPropertyCheckList'
    ]);
    Route::get('property_sendback_list', [
        'as' => 'property_sendback_list',
        'uses' => 'PropertyController@getPropertySendBackList'
    ]);
    Route::get('property_sendback/{id}/{t_from}/{t_to}', [
        'as' => 'property_sendback',
        'uses' => 'PropertyController@getProperty_sendback'
    ])->where('id', '[0-9]+');

    Route::post('adpostPropertySendBackd', [
        'as' => 'postPropertySendBack',
        'uses' => 'PropertyController@postPropertySendBack'
    ]);

    Route::get('property_aproved/{id}/{t_from}/{t_to}', [
        'as' => 'property_aproved',
        'uses' => 'PropertyController@propertyAproved'
    ])->where('id', '[0-9]+');

    Route::get('property_invoice_list', [
        'as' => 'property_invoice_list',
        'uses' => 'PropertyController@getPropertyInvoiceList'
    ]);
    Route::get('get_property_drawdown_account', [
        'as' => 'get_property_drawdown_account',
        'uses' => 'PropertyController@get_property_drawdown_account'
    ]);
    Route::get('customer_account_list/{drawdown_id}/{account_no}/{client_id}', [
        'as' => 'customer_account_list',
        'uses' => 'PropertyController@getCustomerAccountList'
    ]);
    Route::post('customer_topup', [
        'as' => 'customer_topup',
        'uses' => 'PropertyController@post_customer_topup'
    ]);
    Route::get('wallet_list', [
        'as' => 'wallet_list',
        'uses' => 'PropertyController@getWalletList'
    ]);
    Route::get('approve_wallet_transaction/{id}',[
        'as'=>'approve_wallet_transaction',
        'uses'=>'PropertyController@approve_wallet_transaction'
    ])->where(['id'=>'[0-9]+']);
    Route::get('import', [
        'as' => 'import',
        'uses' => 'PropertyController@showImportForm'
    ]); 
    Route::post('postimport', [
        'as' => 'postimport',
        'uses' => 'PropertyController@postimport'
    ]);  

    
    
    

});
   /* End Property management */
