<?php 
    	$params = array(
    			'debit'=>$amount_1,
    			'credit'=>$amount_2,
    			'parent_debit'=>$coa_1->id,
    			'parent_credit'=>$coa_2->id,
    			'parent_debit_label'=>$coa_1->name.' ('.$branch_code.$coa_1->account_code.')',
    			'parent_credit_label'=>$coa_2->name.' ('.$branch_code.$coa_2->account_code.')',
    	);
    	echo getJournalDetail($params);
    	
    	$params = array(
    			'debit'=>$amount_3,
    			'credit'=>$amount_4,
    			'parent_debit'=>$coa_3->id,
    			'parent_credit'=>$coa_4->id,
    			'parent_debit_label'=>$coa_3->name.' ('.$branch_code.$coa_3->account_code.')',
    			'parent_credit_label'=>$coa_4->name.' ('.$branch_code.$coa_4->account_code.')',
    	);
    	echo getJournalDetail($params);