<?php
    $branch_name    = $company_branch && $company_branch->branch_name    ? $company_branch->branch_name    : 'ប៊ីអេស លែន & ហូម ខូ អិលធីឌី';
    $address_one    = $company_branch && $company_branch->address_one    ? $company_branch->address_one    : 'អគារលេខ B2-109, B2-110';
    $address_two    = $company_branch && $company_branch->address_two    ? $company_branch->address_two    : 'សង្កាត់​ទន្លេបាសាក់ ខណ្ឌចំការមន រាជធានីភ្នំពេញ';
    $contact_number = $company_branch && $company_branch->contact_number ? $company_branch->contact_number : '069 455555/ 099 788883';
    $branch_email   = $company_branch ? $company_branch->email   : '';
    $branch_website = $company_branch ? $company_branch->website : '';
?>
<table class="table table-bordered table-striped table-condensed tillTran" id="tran">
    <thead class="table-header">
        <tr>
            <th colspan="15" style="text-align: center;">
            {{ $branch_name }}
            </th>
        </tr>
        <?php if($projects_row && $projects_row->dealer != ''){?>
        <tr>
            <th colspan="15" style="text-align: center;">
           {{ $projects_row->dealer }}
            </th>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="15" style="text-align: center;">
           Head office: {{ $address_one }}
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center;">
            {{ ($address_two != '')?$address_two:'&nbsp;' }}
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center;">
           Tel: {{ $contact_number }}
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center;">
            E-mail:{{ $branch_email }} / Page:{{ $branch_website }}
            </th>
        </tr>
        <tr><th  colspan="15"></th></tr>
        <tr>
            <th  colspan="15" style="text-align: center;">Cashier Receipt Report - Details (Yearly)</th>
        </tr>
        <tr>
            <th  colspan="15" style="text-align: center;">(Print Date:<?php echo date("Y-m-d");?> / By <?php echo $teller_name; ?>)</th>
        </tr>
        <tr><th  colspan="15"></th></tr>
        <tr>
            <th colspan="2">Project Name:</th>
            <th  colspan="13"><?php echo implode(", ", $project_names);?></th>
        </tr>
        <tr>
            <th  colspan="2">Receipt Location:</th>
            <th  colspan="13"><?php echo implode(", ", $location_names);?></th>
        </tr>
        <tr>
            <th  colspan="2">Report Date:</th>
            <th  colspan="13"><?php echo $from_date;?> to <?php echo $to_date;?></th>
        </tr>
        <tr>
            <th  colspan="2">Grand Total:</th>
            <th  colspan="13">$<?php echo number_format($grand_total, 2, '.', '');?></th>
        </tr>
        <tr>
            <th  colspan="2">No of receipt:</th>
            <th  colspan="13"><?php echo count($rows);?></th>
        </tr>
        <tr>
            <th>No</th>
            <th>Transaction Date</th>
            <th>Receipt No</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Project</th>
            <th>Unit</th>
            <th>Methode</th>
            <th>Payment Type</th>
            <th>Description</th>
            <th>PMT.No</th>
            <th>PMT Date</th>
            <th>Interest</th>
            <th>Principal</th>
            <th>Other Fee</th>
            <th>Paid Amount</th>
            <th>Teller Name</th>
            <th>Teller Status</th>
        </tr>
    </thead>
    <tbody id="trans_results">
        <?php $no = 0; ?>
        @forelse($rows as $row)
            <?php
                $no++;
                $pmt_date = !empty($row->effective_pmt_date) ? $row->effective_pmt_date : $row->tranx_time;
                $class_paid_amount = $row->paid_amount < 0 ? 'cash_out' : 'cash_in';
            ?>
            <tr>
                <td>{{ $no }}</td>
                <td>{{ !empty($row->tranx_time)?date('d-M-Y',strtotime($row->tranx_time)):"" }}</td>
                <td>{{ !empty($row->receipt_no)?$row->receipt_no:"" }}</td>
                <td>{{ !empty($row->customer_id)?$row->customer_id:"" }}</td>
                <td>{{ !empty($row->client_name)?$row->client_name:"" }}</td>
                <td>{{ !empty($row->project_name)?$row->project_name:"" }}</td>
                <td>{{ $row->unit_code }}</td>
                <td>{{ !empty($row->methode)?$row->methode:"" }}</td>
                <td>{{ !empty($row->deposit_type)?$row->deposit_type:"" }}</td>
                <td>{{ !empty($row->description)?$row->description:"" }}</td>
                <td>{{ !empty($row->effective_pmt_no)?$row->effective_pmt_no:"" }}</td>
                <td>{{ !empty($pmt_date)?date('d-M-Y',strtotime($pmt_date)):"" }}</td>
                <td style="text-align:right;">${{ $row->effective_interest }}</td>
                <td style="text-align:right;">${{ $row->effective_principal }}</td>
                <td style="text-align:right;">${{ $row->admin_fee }}</td>
                <td class={{$class_paid_amount}} style="text-align:right;">${{ number_format((float)$row->paid_amount, 2, '.', '') }}</td>
                <td>{{ !empty($row->username)?$row->username:"" }}</td>
                <td>{{ $row->approve_status == 1 ? "Authorized" : "Unauthorized"}}</td>
            </tr>
        @empty
            <tr><td colspan="18" class="text-center">No data found.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="15">Total:</th>
            <th>{{ number_format($grand_total, 2, '.', '') }}</th>
        </tr>
        <tr><th  colspan="15"></th></tr>
        <tr><th  colspan="15"></th></tr>
        <tr>
            <th colspan="10" style="text-align:center">
                <p style="padding-left:100px;">Prepared by:</p>
            </th>
            <th colspan="10" style="text-align:center">
                <p>Verified by:</p>
            </th>
        </tr>
    </tfoot>
</table>
