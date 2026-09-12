function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<section id="unseen">';
    sOut += '<table class="table table-bordered table-striped table-condensed table-hover">';
    sOut += '<thead>';
    sOut += '<th>No</th>';
    sOut += '<th>Date</th>';
    sOut += '<th>Number of Days</th>';
    sOut += '<th>Interest</th>';
    sOut += '<th>Principle</th>';
    sOut += '<th>Ballon</th>';
    sOut += '<th>Total Monthly Payment</th>';
    sOut += '<th>Paid Interest</th>';
    sOut += '<th>Paid Principle</th>';
    sOut += '<th>Paid Ballon</th>';
    sOut += '<th>Principle Balance</th>';
    sOut += '<th>Overdue(days)</th>';
    sOut += '<th>Penalty</th>';
    sOut += '<th>Pay Off</th>';
    sOut += '<th>Write Off</th>';
    sOut += '<th>Total Paid Amount</th>';
    sOut += '<th>Repayment Owed</th>';
    sOut += '<th>PARC Level</th>';
    sOut += '<th>PARC Action</th>';
    sOut += '<th>Status</th>';
    sOut += '</thead>';
    sOut += '<tbody>';
    sOut += '<tr>';
    sOut +='<td>00001</td>';
    sOut +='<td>21-Jan-2015</td>';
    sOut +='<td>31</td>';
    sOut +='<td>$620.00</td>';
    sOut +='<td>$833.33</td>';
    sOut +='<td>-</td>';
    sOut +='<td>$3453.33</td>';
    sOut +='<td>$620.00</td>';
    sOut +='<td>$833.33</td>';
    sOut +='<td>-</td>';
    sOut +='<td>$8546.67</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='<td>$1453.33</td>';
    sOut +='<td>-</td>';
    sOut +='<td>0</td>';
    sOut +='<td>-</td>';
    sOut +='<td><a href="#" class="btn btn-info btn-xs"><i class="fa  fa-search"></i>&nbsp;View Receipt</a>&nbsp&nbsp<span>Paid on: 21-Feb-2015</span></td>';
    sOut +='</tr>';
    sOut += '<tr>';
    sOut +='<td>00002</td>';
    sOut +='<td>21-Feb-2015</td>';
    sOut +='<td>28</td>';
    sOut +='<td>$600.00</td>';
    sOut +='<td>$833.33</td>';
    sOut +='<td>$2000.00</td>';
    sOut +='<td>$3433.33</td>';
    sOut +='<td>$600.00</td>';
    sOut +='<td>$833.33</td>';
    sOut +='<td>$1000.00</td>';
    sOut +='<td>$6113.34</td>';
    sOut +='<td>3</td>';
    sOut +='<td>$15.00</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='<td>$2433.33</td>';
    sOut +='<td>$1000.00</td>';
    sOut +='<td>1</td>';
    sOut +='<td>Visit</td>';
    sOut +='<td><a href="#" class="btn btn-info btn-xs"><i class="fa fa-plus"></i>&nbsp;Add Receipt</a>&nbsp&nbsp<span>Inactive</span></td>';
    sOut +='</tr>';
    sOut += '<tr>';
    sOut +='<td colspan="2">Total</td>';
    sOut +='<td>59</td>';
    sOut +='<td>$1000.00</td>';
    sOut +='<td>$19000.33</td>';
    sOut +='<td>$8000.00</td>';
    sOut +='<td>$11000.33</td>';
    sOut +='<td>$1000.00</td>';
    sOut +='<td>$2000.00</td>';
    sOut +='<td>$8000.00</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='<td>$15.00</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='<td>$11015.00</td>';
    sOut +='<td>$0</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='<td>-</td>';
    sOut +='</tr>';
    sOut +='</tbody>';
    sOut += '</table>';
    sOut += '</section>';
    return sOut;
}

$(document).ready(function() {

    $('#dynamic-table').dataTable( {
        "aaSorting": [[ 4, "desc" ]],
    } );

    /*
     * Insert a 'details' column to the table
     */
    var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
    nCloneTd.innerHTML = '<img src="../../images/details_open.png">';
    nCloneTd.className = "center";

    $('#hidden-table-info thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    } );

    $('#hidden-table-info tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );

    /*
     * Initialse DataTables, with no sorting on the 'details' column
     */
    var oTable = $('#hidden-table-info').dataTable( {
        "aoColumnDefs": [{ 
            "bSortable": false, 
            "aTargets": [ 0 ],
            "bPaginate": false,
        }],
        "aaSorting": [[1, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": false
    });

    /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
    $(document).on('click','#hidden-table-info tbody td img',function () {
        var nTr = $(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            this.src = "../../images/details_open.png";
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            this.src = "../../images/details_close.png";
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
        }
    } );
} );