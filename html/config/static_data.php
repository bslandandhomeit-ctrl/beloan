<?php
return [
    'client_type' => [
        1 => 'Individual',
        2 => 'Group',
        3 => 'Organization'
    ],
    'payment_type' => [
        0 => 'Drawdown Account',
        1 => 'Cash In Vault',
        6 => 'Cash on Hand-Teller',
  //       2 => 'Current Accounts with RHB Bank(000000000000000)',
  //       3 => 'Demand and Savings Deposits with PPCB bank',
  //       4 => 'Current Accounts with ABA Bank(000000000000000)',
  //       5 => 'Emoney',
  //       7 => 'Foreign Exchange Position Account',
  //       8 => 'Inter-Branch Accounts-Due from Branch-BTB',
  //       9 => 'Inter-Branch Accounts-Due from Branch-SRP',
		// 10 => 'Inter-Branch Accounts-Due to HO',
        // 100 => 'ABA-BCC-000816490-Borey Chaktumok City (BCC)'
        //4 => 'E Money',
        //5 => 'Credit Card',
        //6 => 'Mobile Banking',
        //7 => 'AMK',
        //8 => 'ASIA',
        //9 => 'True Money',
        100 => '000816490 ABA Revenue (USD) - Borey Chaktumok City (BCC)',
        101 => '66675555 Chipmong Revenue Expenses (USD) - BS Land and Home (BS)',
        102 => '001036662 ABA Revenue Expenses (USD) - BS Holiday City (BHC)',
        103 => '002489507 BS Land and Home Co., Ltd (BHQ)',
        105 => '000826822 ABA Revenue Expenses (USD) - Chaktumuk CityView (CCC)',
        106 => '000816491 ABA Revenue Expenses (USD) - Sihanouk City View (SCC)',
        107 => '000868966 ABA Revenue Expenses (USD) - East Land Say Chrom (EDH)',
        108 => '002465254 East Land and Home Co., Ltd',
        109 => '001160513 ABA Revenue Expenses (USD) - East Kean Svay City (EKC)',
        110 => '000815888 ABA Revenue (USD) - Borey East Land and Home (ELH)',
        111 => '000815895 ABA Revenue Expenses (USD) - East Natural City',
        112 => '002262407 ABA Rev Exp - EAST Prime Land (EPL)',
        113 => '000815978 ABA Revenue (USD) - East Sihanouk City (ESC)',
        114 => '000815756 ABA Revenue (USD) - East Sihanouk Park (ESP)',
        115 => '000815761 ABA Rev. Exp. (USD) - East SenSok Condominium (ESS)',
        116 => '0700-03-530888-88 Acleda Bank for Revenue Expenses (USD) - East Natural City (ENC)',
        117 => '66678888 Chipmong Revenue Expenses (USD) - Borey East Land and Home (ELH)',
        118 => 'PPCB Bank Acc. for Revenue Expenses (USD) - East Land Say Chrom (EDH)',
        119 => 'PPCB Bank Acc. for Revenue Expenses (USD) - Borey East Land and Home (ELH)',
        120 => 'PPCB Bank Acc. for Revenue Expenses (USD) - East Sihanouk City (ESC)',
        121 => 'PPCB Bank Acc. for Revenue Expenses (USD) - ESP',
        122 => 'PPCB Bank Acc. for Revenue Expenses (USD) - East SenSok Condominium (ESS)',
        123 => '10034861-Chipmong Gateway East Land & Home',
        124 => 'Lyhour VeLuy',
        125 => 'Wing',
        126 => 'True Money',
        127 => 'Borey Chaktumok City (BCC)  All  PPCB Bank  BS Land and Home Co., Ltd  112-01-094273-2',
        128 => 'Lay Sreyleak',
    ],
    'payment_type_disbus' => [
        // 0 => 'Drawdown Account',
        // 1 => 'Cash In Vault',
        // 6 => 'Cash on Hand-Teller',
        // 2 => 'Current Accounts with RHB Bank(000000000000000)',
        // 3 => 'Demand and Savings Deposits with PPCB bank',
        // 4 => 'Current Accounts with ABA Bank(000000000000000)',
        // 5 => 'Emoney',
        // 7 => 'Foreign Exchange Position Account',
        // 8 => 'Inter-Branch Accounts-Due from Branch-BTB',
        // 9 => 'Inter-Branch Accounts-Due from Branch-SRP',
        // 10 => 'Inter-Branch Accounts-Due to HO'
        //4 => 'E Money',
        //5 => 'Credit Card',
        //6 => 'Mobile Banking',
        //7 => 'AMK',
        //8 => 'ASIA',
        //9 => 'True Money',
        // 1000 => 'Project Loan',
        1000 => 'Deposit Account (All Deposit)',
    ],
    'collateral_type' => [
        'CA' => 'Cash',
        'GI' => 'Guarantor',
        'MO' => 'Mortgage',
        'NO' => 'NO',
        'OT' => 'Others',
        'SH' => 'Shares',
        'MP' => 'Multiple',
        'FD' => 'Fixed Deposit',
        'LT' => 'Land Title',
        'TO' => 'Ownership Title – Land/Buildings',
        'FB' => 'Floating Debenture',
        'MV' => 'Motor Vehicle',
        'IN' => 'Inventory',
        'LC' => 'Letter of Credit',
        'CG' => 'Corporate Guarantees',
    ],
    'collateral_regis_type' => [
        1 => 'hard-title(commune level)',
        2 => 'hard-title(distict level)',
        3 => 'soft-title(commune level)',
        4 => 'soft-title(distict level)',
        5 => 'vehicle document',
        6 => 'other'
    ],
    'role' => [// not used
        1 => 'Admin',
        2 => 'Super User',
        3 => 'CEO',
        4 => 'Director',
        5 => 'General Manager',
        6 => 'IT Manager',
        7 => 'Accountant Manager',
        8 => 'Accountant',
        9 => 'Chief Of CO',
        10 => 'CO',
        11 => 'Chief of Teller'
    ],
    'company_branch' => [
        1 => 'Phnom Penh',
        2 => 'Battambang',
        3 => 'Siam Reap',
        4 => 'Bantey Meanchey'
    ],
    'loan_type' => [
        1 => 'Agriculture',
        2 => 'Trade and Commerce',
        3 => 'Transportation',
        4 => 'Construction',
        5 => 'Household/Family',
        6 => 'Others'
    ],
    'repayment_type' => [
        1 => 'Equal Principal',
        //2 => 'Flat Equal Installment',
        3 => 'Semi-Balloon ( fixed Principal )',
        //4 => 'Flat Semi-Balloon ( fixed Monthly Payment )',
        //5 => 'Flat Semi-Balloon ( fixed Principal )',
        //6 => 'Flat Amortize',
        7 => 'Annuity / Amortization',
        9 => 'Semi-Balloon / Grace Period',
        8 => 'Manual',
    ],
    'loan_status' => [
        1 => 'Unauthorized',
        2 => 'Approved',
        3 => 'Disbursed',
        4 => 'Rejected',
        5 => 'Write-Off',
        6 => 'Closed',
        7 => 'Rescheduled ( unauthorized )',
        8 => 'Rescheduled ( approved )', // can be renew loan
        9 => 'Pay-Off',// paid all amount
        10 => 'Completed', // paid by monthly
        11 => 'Terminate', // paid by monthly
        12 => 'Loan buy back' // loan buy back
    ],
    'reschedule_status' => [
        0 => 'Rescheduled ( rejected )',
        1 => 'Rescheduled ( approved )',
    ],
    //1,2,4,7
    'loan_payment_status' => [
        0 => 'Owed',
        1 => 'Completed',
        2 => 'Paid Owed'
    ],
    'loan_payment_condition' => [
        1 => 'Pay next time',
        2 => 'Pay with next payment date',//pay for next load payment period
    ],
    'penalty_type' => [
        1 => 'per day',
        2 => 'per month',
        3 => 'per period'
    ],
    'letter_type' => [
        1 => 'Passport',
        2 => 'Driver License',
        3 => 'Family Book',
        4 => 'Other'
    ],
    'penalty_rate_type' => [
        1 => 'Per day',
        2 => 'Per month',
        3 => 'Per period'
    ],
    'relationship_type' => [
        1 => 'Spouse',
        2 => 'Parent',
        3 => 'Business Associate',
        4 => 'Sibling',
        5 => 'Other'
    ],
    'fee_cost_type' => [
        1 => 'Referred Fee',
        2 => 'Lawyer Fee',
        3 => 'CBC Check',
        4 => 'Setfo Fee',
        5 => 'Insurance',
        6 => 'Collateral Evaluation',
        7 => 'Hunting Fee',
        8 => 'Fixing Cost'
    ],
    'fee_charge' => [
        1 => 'Admin Fee',
        2 => 'Re-schdule Loan Fee',
        3 => 'Cancellation fee',
        4 => 'Maintenance Fee',
        5 => 'Electricity Fee',
        6 => 'Water Fee',
        7 => 'Tittle Transfer Fee',
        9 => 'Other Fee Charge',
        10 => 'Sub Sale',
    ],
    'loan_doc_type' => [
        1 => 'ES',
        2 => 'Agreement',
        3 => 'Schedule Evidence',
        4 => 'Re-schedule Evidence',
        5 => 'Approval Mail',
        6 => 'Other'
    ],
    'days_of_month' => [
       // 1 => 'Fixed', // 30days fixed
        2 => 'Flexible', // varied by number of month
    ],
    'trans_type' => [
        1 => 'Loan Repayment',
        2 => 'Principal Repayment',
        3 => 'Interest Repayment',
        4 => 'Penalty Repayment',
        5 => 'Arrears Repayment',
        6 => 'Fee Charge Repayment',
        7 => 'Cost Repayment',
        8 => 'Disbursement',
        9 => 'Pay-Off',
        10 => 'Write-Off',
        11 => 'General Journal',
        12 => 'Write Check',
        13 => 'Enter Bill',
        14 => 'Make deposit',
        15 => 'Transfer Funds'
    ],
    'account_type' => [
        1 => 'Income',
        2 => 'Expense',
        3 => 'Asset',
        4 => 'Equity',
        5 => 'Liability'
    ],
//    'gender' => [/// duplicated
//        'M' => 'Male',
//        'F' => 'Female'
//    ],
    'branch' => [
        1 => 'Phnom Penh',
        2 => 'Battambang',
        3 => 'Banteay Meanchey',
        4 => 'Kampong Cham',
        5 => 'Kampong Chhnang',
        6 => 'Kampong Speu',
        7 => 'Kampong Thom',
        8 => 'Kampot',
        9 => 'Kandal',
        10 => 'Koh Kong',
        11 => 'Kep',
        12 => 'Kratie',
        13 => 'Mondulkiri',
        14 => 'Oddar Meanchey',
        15 => 'Pailin',
        16 => 'Preah Sihanouk',
        17 => 'Preah Vihear',
        18 => 'Prey Veng',
        19 => 'Pursat',
        20 => 'Ratanakiri',
        21 => 'Siem Reap',
        22 => 'Stung Treng',
        23 => 'Svay Rieng',
        24 => 'Takeo',
        25 => 'Tbong Khmum'
    ],
    'action_type' => [
        1 => 'Ordered',
        2 => 'Imported',
        3 => 'In Stock',
        4 => 'Out Stock',
        5 => 'Returning',
        6 => 'Returned',
        7 => 'Fixing',
        8 => 'Resold',
        9 => 'Returned back with pay_off',
    ],
    'currency' => [
        1 => 'KHR',
        2 => 'USD',
        3 => 'EUR',
        4 => 'JPY',
        5 => 'THB',
        6 => 'HKD',
        7 => 'MYR',
        8 => 'SGD',
        9 => 'VND',
    ],
    'currency_name' => [
        'KHR' => 'Khmer Riel',
        'USD' => 'US Dollar',
        'EUR' => 'European Euro',
        'JPY' => 'Japanese Yen',
        'THB' => 'Thai Baht',
        'HKD' => 'Hong Kong Dollar',
        'MYR' => 'Myanmar Kyat',
        'SGD' => 'Singapore Dollar',
        'VND' => 'Vietnamese Dong',
    ],
    'currency_symbol' => [
        1 => '៛',
        2 => '$',
        3 => '€',
        4 => '¥',
        5 => '฿',
        6 => '$',
        7 => 'RM',
        8 => '$',
        9 => '₫',
    ],
    'sector' => [
        0 => 'Non sector',
        1 => 'NBC',
        2 => 'Bank Resident',
        3 => 'Bank Non-Resident',
        4 => 'MFI-Resident',
        5 => 'Individual Resident',
        6 => 'Individual Non-Resident',
        7 => 'Private sector Resident',
        8 => 'Private Sector Non-Resident',
        9 => 'Small and Medium Enterprise'
    ],
    'performance' => [
        1 => 'very bad',
        2 => 'bad',
        3 => 'fairly',
        4 => 'good',
        5 => 'very good'
    ],
    'interest_option' => [
        1 => 'monthly',
        2 => 'daily'
    ],
    'client_loan_account_pre_prefix' => [
        'coa' => '',
        'air' => 'AIR-',
        'interest' => 'Inc-Int-',
        'suspense' => 'Int-in-Suspense-'
    ],
    //Old Prakas
    'client_loan_account_prefix' => [
        2 => 'Stand-L-',
        3 => 'Sub-Stand-L-',
        4 => 'Doubtful-L-',
        5 => 'Loss-L-'
    ],
    'client_loan_account_prefix_new' => [
        2 => 'Stand-L-',
        3 => 'Spec-Mention-',
        4 => 'Sub-Stand-L-',
        5 => 'Doubtful-L-',
        6 => 'Loss-L-'
    ],
    'client_leasing_account_prefix' => [
        2 => 'Stand-Leasing-',
        3 => 'Sub-Stand-Leasing-',
        4 => 'Doubtful-Leasing-',
        5 => 'Loss-Leasing-'
    ],
    'client_leasing_account_prefix_new' => [
        2 => 'Stand-Leasing-',
        3 => 'Spec-Mention-Leasing-',
        4 => 'Sub-Stand-Leasing-',
        5 => 'Doubtful-Leasing-',
        6 => 'Loss-Leasing-'
    ],
    'provision_rate' => [ // old provision
        2 => 0,
        3 => 10,  // sub standard
        4 => 20,  // doubtful
        5 => 70   // loan loss
    ],
    'provision_rate_new' => [ // new provision
        2 => 1,   //standard
        3 => 2,   // special mention
        4 => 17,  // sub standard
        5 => 30,  // doubtful
        6 => 50   // loan loss
    ],
    'coa_symbol' => [
        'cce' => 'Cash and Cash Equivalence',
        'bcb' => 'Balance with central bank',
        'bbo' => 'Balance with banks and OFIs',
        'oa' => 'Other Assets',
        'lac' => 'Loans and Advances to customers',
        'lpdbd' => 'Less: Provision for doubtful and bad debt',
        'ies' => 'Investment in equity securities',
        'pe' => 'Property and equipment',
        'ld' => 'Less: Depreciations',
        'sd' => 'Subordinated Debt',
        'dc' => 'Deposit from Customers',
        'b' => 'Borrowing',
        'ol' => 'Other liabilities',
        'pitcy' => 'Provision for income tax current year',
        'is' => 'Interest in Suspense',
        'gp' => 'General provision',
        'sc' => 'Share capital',
        'r' => 'Reserves',
        'se_sd' => 'Subordinated debt approved by NBC',
        're' => 'Retained Earning',
        'pcy' => 'Profit Current Year',
        'oii' => 'Others Interest Incomes',
        'iil' => 'Interest Income from Loans',
        'ilcf' => 'Income from Loan commitment fees',
        'iofc' => 'Income Other Fees and Commission',
        'onii' => 'Other Non-Interest Income',
        'igfe' => 'Income Gain on foreign exchange',
        'rl' => 'Income Recovery on loans',
        'igdpe' => 'Income Gain on disposals of property and equipment',
        'oie' => 'Others Interest Expenses',
        'ied' => 'Interest Expense on Deposits',
        'ieb' => 'Interest Expense on Borrowing',
        'posc' => 'Payroll and other staff cost',
        'dpe' => 'Depreciation of property and equipment',
        'ooe' => 'Other operating expenses',
        'eit' => 'Expense Income tax',
        'pdbd' => 'Provision for doubtful and bad debt',
        'lostd' => 'Loans Outstanding',
    ],
    'coa_esc_code' => [
        '01' => 'All other account type, all ownership in economic sectors',
        '10' => 'Royal Government of Cambodia.',
        '11' => 'Royal Governmant of Cambodia-Central Government',
        '12' => 'Royal Governmant of Cambodia-Provincial Government',
        '13' => 'Royal Governmant of Cambodia-Local Government',
        '20' => 'State Owned Enterprises',
        '21' => 'State Owned Enterprise-Financial Institutions',
        '22' => 'State Owned Enterprise-Non Financial Institutions',
        '30' => 'Private Sectors',
        '31' => 'Private Sectors-Coporations',
        '32' => 'Private Sectors-Partnerships',
        '33' => 'Private Sectors-Sole Propriatorships',
        '34' => 'Private Sectors-Households',
        '35' => 'Private Sectors-Non Profit Institutions',
        '40' => 'Non Residents',
        '41' => 'Non Residents-Corporations & Quasi-Corporations',
        '42' => 'Non Residents-Individuals'
    ],
    'client_loan_account_status' => [
        0 => 'Inactive',
        1 => 'Open',
        2 => 'Standard',
        3 => 'Sub-Standard',
        4 => 'Doubtful',
        5 => 'Loss Loan',
        6 => 'Write-Off',
        7 => 'Completed',
        8 => 'Closed'
    ],
    'client_loan_account_status_new' => [
        0 => 'Inactive',
        1 => 'Open',
        2 => 'Standard',
        3 => 'Special Mention',
        4 => 'Sub-Standard',
        5 => 'Doubtful',
        6 => 'Loss Loan',
        7 => 'Write-Off',
        8 => 'Completed',
        9 => 'Closed'
    ],
    
	'loan_product_status' => [
        2 => 'N',
        3 => 'U',
        4 => 'D',
        5 => 'L',
        6 => 'W',
        7 => 'C',
        8 => 'C',
    ],
	 'loan_product_status_new' => [
         2 => 'N',
         3 => 'S',
         4 => 'U',
         5 => 'D',
         6 => 'L',
         7 => 'W',
         8 => 'C',
         9 => 'C',
	],
    'transaction_type' => [
        1 => 'Disbursement',
        2 => 'Loan Repayment',
        3 => 'Arrears Repayment',
        4 => 'Auto Accrued Interest',
        5 => 'Fee Charge Repayment',
        6 => 'Cost Repayment',
        7 => 'Pay-Off',
        8 => 'Write-Off',
        9 => 'Close',
        10 => 'Reschedule(Approved)'
    ],
    'breakdown_type' => [
        1 => 'Principal',
        2 => 'Interest',
        3 => 'Penalty',
        4 => 'Fee'
    ],
    'drawdown_status' => [
        //0 => 'Inactive',
        //1 => 'Active',
        //2 => 'Close'
        0 => 'Unauthorized',
        1 => 'Authorized'
    ],
    'identification' => [
        'N' => 'National ID',
        'F' => 'Family Book',
        'P' => 'Passport',
        'D' => 'Drivers Licence',
        'B' => 'Birth Certificate',
        'V' => 'Voter Reg. Card',
        'T' => 'Tax Number',
        'R' => 'Resident Book'
    ],
    'gender' => [
        'M' => 'Male',
        'F' => 'Female',
        // 'U' => 'Unknown'
    ],
    'marital_status' => [
        // 'D' => 'Divorced',
        'M' => 'Married',
        // 'P' => 'Separated',
        'S' => 'Single',
        // 'U' => 'Unknown',
        'W' => 'Widow/Widower',
        // 'F' => 'Defacto'
    ],
    'applicant_type' => [
        'P' => 'Primary',
        'G' => 'Guarantor',
        'S' => 'Supplementary'
    ],
    'account_cbc_type' => [
        'J' => 'Joint',
        'S' => 'Single',
        'G' => 'Group'
    ],
    'address_type' => [
        'RESID' => 'Residential',
        'WORK' => 'Work',
        'POST' => 'Correspondence',
        // 'U' => 'Unknown',
        'COMM' => 'Common',
    ],

    'product_status' => [
        'N' => 'Normal',
        'S' => 'Special Mention',
        'C' => 'Closed',
        'W' => 'Loss',
        'U' => 'Substandard',
        'D' => 'Doubtful'
    ],
    'payment_frequency' => [
        'W' => 'Weekly',
        'F' => 'Two Weekly',
        'M' => 'Monthly',
        'Q' => 'Quarterly',
        'H' => 'Half-yearly',
        'Y' => 'Yearly',
        'O' => 'Other',
    ],
    'payment_status' => [
        'N' => 'N - New - Not yet activated',
        'Q' => 'Q - No transactions this cycle',
        '0' => '0 – Current',
        '1' => '1 - 30 days overdue',
        '2' => '2 - 60 days overdue',
        '3' => '3 - 90 days overdue',
        '4' => '4 - 120 days overdue',
        '5' => '5 - 150 days overdue',
        '6' => '6 - 180 days overdue',
        '7' => '7 – 210 days overdue',
        '8' => '8 – 240 days overdue',
        '9' => '9 – 270 days overdue',
        'T' => '10 –300 days overdue',
        'E' => '11 – 330 days overdue',
        'W' => 'W – Loss',
        'C' => 'C – Closed',
        'D' => 'D – Defer Payment',
        'V' => ' V – Vacation Payment'
    ],
    'write_off_status' => [
        'FS' => 'Fully Paid',
        'NS' => 'Negotiated Settlement',
        'OS' => 'Outstanding',
        'PP' => 'Partially Paid',
        'LA' => 'Legal Action'
    ],
    'ref_name_type' => [
        '0' => 'other',
        '1' => 'vendor',
        '2' => 'customer',
        '3' => 'staff'
    ],
    'repayment_draft_type' => [
        0 => 'Waiting approve',
        1 => 'Approved',
        2 => 'Rejected'
    ],
    'postal_code' => [
        '' => 1
    ],
    //role of access repayment draft
    'allow_roles' => [
        1, 2
    ],

    'department'=>[
        '1' => 'Operation',
        '2' => 'Finance and Accounting',
        '3' => 'HR and Admin',
        '4' => 'IT',
        '5' => 'Internal Audit',
        '6' => 'Chief Executive',
        '7' => 'BOD',
        '8' => 'System Admin'
    ],
    'sub_department'=>[
        '11' => 'Operation',
        '12' => 'Credit',
        '13' => 'Loan',
        '21' => 'Accounting',
        '22' => 'Teller',
        '31' => 'HR and Admin',
        '41' => 'IT',
        '51' => 'Internal Audit',
        '61' => 'Executive',
        '71' => 'BOD',
        '81' => 'System Admin'
    ],
    'asset_locations'=>[
        '01'=> 'In front of Building',
        '02'=> 'On the side of Building',
        '03'=> 'Office building',
        '04'=> 'Operation Counter',
        '05'=> 'Operation Department',
        '06'=> 'Admin & HR Department',
        '07'=> 'Accounting Department',
        '08'=> 'IT Department',
        '09'=> 'Audit Department',
        '10'=> 'Ground Floor',
        '11'=> 'Mezzanine',
        '12'=> 'First Floor',
        '13'=> 'Second Floor',
        '14'=> 'Meeting Rom',
    ],

    'asset_abbrev' => [
        "LND" => 'Land',
        "BLD" => 'Building',
        "LHI" => 'Leasehold Improvement',
        "FF"  => 'Furniture and Fixture',
        "EQ"  => 'Equipment',
        "CE"  => 'Computer Equipment',
        "MO"  => 'Computer Software',
        "CS"  => 'Computer Software',
        "OT"  => 'Formation Expense'
    ],
    'asset_categories' => [
        '101' => 'Chairs',
        '102' => ' Desks',
        '103' => ' Tables',
        '104' => ' Cabinet',
        '105' => ' Closset',
        '106' => ' Rack',
        '107' => ' Safe',
        '199' => ' Other furniture & fixture',
        '201' => ' Genertor',
        '202' => ' Air-Conditioner',
        '203' => ' Fan',
        '204' => ' Refrigerator',
        '205' => ' Water Cooler',
        '206' => ' Security Camera',
        '207' => ' Counting Machine',
        '208' => ' TV',
        '209' => ' LCD Projector',
        '210' => ' Desk Phone or Fax Machine',
        '211' => ' Mobile Phone',
        '212' => ' Type Writer',
        '213' => ' Copy Machine',
        '214' => ' Finger Printing Machine',
        '215' => ' Caculator',
        '216' => ' Paper Shreder',
        '217' => ' Office Tools',
        '218' => ' Projector Screen',
        '299' => ' Other equipment',
        '301' => ' Desktop PC',
        '302' => ' Laptop PC',
        '303' => ' Tablet',
        '304' => ' Server',
        '305' => ' System Unit',
        '306' => ' Monitor',
        '307' => ' Printer',
        '308' => ' Scanner',
        '309' => ' Switch',
        '310' => ' Router',
        '311' => ' Firewall',
        '312' => ' UPS',
        '399' => ' Other Computer Equipment',
        '401' => ' Car',
        '402' => ' Motobike',
        '499' => ' Other Motor Vehicles',
        '501' => ' Computer Software',
        'LND' => 'Land',
        'BLD' => 'Building',
        'LHI' => 'Leasehold Improvement'
    ],

    'asset_classifications' => [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C'
    ],
    'asset_depre_meth' => [
        1 => 'Straight-Line',
        2 => 'Other'
    ],
    'salutation' => [
        'MR' => 'Mr',
        'Mrs' => 'Mrs',
        'Ms' => 'Ms'
    ],
    'education_lvl' => [
        'PR' => 'Primary',
        'SE' => 'Secondary',
        'BA' => 'Bachelor',
        'MBA'=> 'Master',
        'DR' => 'Doctoral',
    ],
    'ContactNumberType' => [
        'O' => 'Office',
        // 'F' => 'Fax',
        'H' => 'Home',
        'M' => 'Mobile',
        // 'U' => 'Unknown'
    ],
    'employer_type' => [
        'C' => 'Current',
        'P' => 'Previous'
    ],
    'tenureType' => [
        '0' => '<=1 year',
        '1' => '>1 year',
    ],
    'ownershipType' => [
        'Groups' => 'Group',
        'Indi' => 'Individual',
        'Corperations' => 'Corporation'
    ],
    'loanCategory' => [
        'WCL' => 'Working Capital Loans (WCL)',
        'AML' => 'Agriculture Machinery Loans (AML)',
        'HML' => 'Heavy Machinery Loans (HML)',
        'ATL' => 'Automobile Loans (ATL)',
        'PEL' => 'Personal Loans (PEL)',
        'EML' => 'Staff Loans (EML)',
        'PHL' => 'Public Housing Loan (PHL)',
		'HIL' => 'Home Improvement Loan (HIL)',
		'Managers' => 'Managers',
        'Shareholders' => 'Shareholders',
        'External Auditors' => 'External Auditors'
    ],
	'new_loanCategory' => [
		"AML" => "ML",
		"WCL" => "WCL",
		"HML" => "HML",
		"PEL" => "PL",
		"ATL" => "CL",
		"PHL" => "HL",
		"Employees" => "SL"
	],
    'residentType' =>[
        'RT' => 'Resident',
        'NRT' => 'Non-Resident'
    ],
    // General query quote
    'quoteType' =>[
        'LIKE' => 'LIKE',
        'NOT LIKE' => 'NOT LIKE',
        '=' => '=',
        '!=' => '!='
    ],
    'selectRows' =>[
        'All' => 'All',
        '10' => '10',
        '50' => '50',
        '100' => '100',
        '500' => '500',
        '1000' => '1000',
        '5000' => '5000',
        '10000' => '10000',
        '50000' => '50000',
    ],
    'feeOpt' =>[
        '0' => 'One Time',
        '1' => 'Monthly',
        '2' => 'Yearly',
        '3' => 'With Outstanding Balance',
    ],
    'auto_authorize_drawdown'=> 1,
    'auto_authorize_account'=> 1,
    'unit_status' => [
        'available' => 'Available',
        'deposit' => 'Deposit',
        'contract' => 'Contract',
        'booking'  => 'Booking',
    ],
    'unit_status_color' => [
        'available' => '#33FF9C',
        'deposit' => '#33DAFF',
        'contract' => '#F51C1C',
        'booking'  => '#5D34F7',
    ],
    'auto_select_option' => [
        'tenure' => 1,
        'ownership' => 'Indi',
        'currency' => 2,
        'resident_status' => 'RT',
        'laon_category' => 'PHL',
        'parent_account' => 378,
    ],
    'loan_penalty_type'=> [
        '$' => 'USD ($)',
        '%' => 'Percentage (%)',
    ],
    'autopayment_config' => [
        'not_owed_auto_pay' => 1
    ],
    'contract_template' => [
                            '1'=>'Housing',
                            '2'=>'Land',
                            '3' => 'Condo',
                            '4' => 'Shop'
    ],
    'transfer_client_type' => [
                        1       => 'Ownership',
                        2       => 'Sub Sale',
    ],
    'ajax_permission' => [
                        'get_project_unit_unittype',
                        'get-unit-type',
                        'getUnitInfo',
                        'loan_detail_schedule',
                        'getdeposit_schedule',
                        'getTillerUnitByUnittype',
                        'gennerateDrawdownAcc',
                        'gennerateLoanAcc',
                        'getProjectByCompany',
                        'getRestructurePaymentOption',
                        'getdeposit_schedule',
                        'view_loan_add_document',
                        'get_journal_data',
                        'api_guarantor',
                        'api_co_borrower',
    ],
    'deposit_type' => [
        1 => 'Loan Installment',
        2 => 'Deposit',
        3 => 'Down-Payment',
        4 => 'Pay-Off',
        5 => 'Penalty Fee',
        6 => 'Fee Charge',
    ],
    'company' => [
        1 => 'East Land and Home Co., Ltd',
        2 => 'BS Land and Home Co., Ltd',
    ],
    'company_type' => [
        1 => 'Real Estate',
        2 => 'Property',
        3 => 'IIP',
    ],

];
