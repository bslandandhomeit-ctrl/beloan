<link rel="stylesheet" type="text/css"
      href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}"/>
<div id="vendor_info" class="modal fade" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 1100px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Vendor information</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <table class="table table-responsive table-bordered">
                            <tbody>
                            <?PHP
                            foreach ($dataa as $item):

                                $vendors = $item->vendor;
                                if (empty($item->detail)) {
                                    $vendors = $item;
                                }

                            endforeach;
                            ?>
                            <tr>
                                <td>Company Name</td>
                                <td>{{$vendors->company_name}}</td>
                                <td style="border:none"></td>
                                <td> Phone</td>
                                <td>{{$vendors->main_phone}}</td>
                            </tr>
                            <tr>
                                <td>Full Name</td>
                                <td>{{$vendors->fname}}</td>
                                <td style="border:none"></td>
                                <td>Email</td>
                                <td>{{$vendors->main_email}}</td>
                            </tr>
                            <tr>
                                <td>Billed From</td>
                                <td>From Billed</td>
                                <td style="border:none"></td>
                                <td>Address</td>
                                <td>{{$vendors->address}}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h4>Transaction</h4>
                        <hr/>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs12">
                        <form class="form-inline" id="search">
                            <div class="form-group">
                                <label for="from">From:</label>
                                <input type="text" name="from" class="form-control dpYears" id="dateStart"
                                       placeholder="From"
                                       value="<?PHP echo date("Y-m-d", strtotime("first day of previous month")) ?>">
                            </div>
                            <div class="form-group">
                                <label for="todate">To:</label>
                                <input type="text" name="todate" class="form-control dpYears" id="dateEnd"
                                       placeholder="to"
                                       value="<?PHP echo date('Y-m-d', strtotime("+1 day", time())); ?>">
                            </div>
                        </form>
                        <table class="table table-responsive table-bordered transaction">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Type</th>
                                <th>Ref. Num</th>
                                <th>Date</th>
                                <th>Account</th>
                                <th>Amount ( $ )</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',false)}}"></script>
    <script>

        $(document).ready(function () {

            var trans_types = JSON.parse('<?php echo json_encode(config('static_data.trans_type')); ?>');
            var dataSource = <?PHP echo $data ?>;

            console.log(dataSource);

            $("#dateStart, #dateEnd").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
            $(function () {
                var $dTable = $("table.transaction").dataTable({
                    "aaSorting": [[0, 'asc']],
                    "aaData": dataSource,
                    "deferRender": true,
                    fixedHeader: true,
                    select: true,
                    "aoColumns": [
                        {"mData":'id'},
                        {"mData":function(data) {

                            return trans_types[parseFloat(data.journal.trans_type)]
                        }},
                        {"mData":function(data){
                            return padToiex(parseFloat(data.journal.invoice_number))
                        }},
                        {"mData":function(data){
                            entry_date = new Date(data.journal.entry_date).toISOString().slice(0,10);
                            return entry_date;
                        }},
                        {"mData":function(data){

                            return data.account.name;
                        }},
                        {"mData":function(data){
                             return data.debit
                        }},
                        {"mData":function(data){
                            return data.description
                        }}
                    ]
                });

                $("#dateStart").keyup(function () {
                    $dTable.fnDraw();
                });
                $("#dateStart").change(function () {
                    $dTable.fnDraw();
                });
                $("#dateEnd").keyup(function () {
                    $dTable.fnDraw();
                });
                $("#dateEnd").change(function () {
                    $dTable.fnDraw();
                });
            });
            $.fn.dataTableExt.afnFiltering.push(
                    function (oSettings, aData, iDataIndex) {

                        var dateStart = new Date($("#dateStart").val()).toISOString().slice(0, 10).replace(/-|\s/g, "");
                        var dateEnd = new Date($("#dateEnd").val()).toISOString().slice(0, 10).replace(/-|\s/g, "");
                        var evalDate = new Date(aData[3]).toISOString().slice(0, 10).replace(/-|\s/g, "");

                        if (evalDate >= dateStart && evalDate <= dateEnd) {
                            return true;
                        } else {
                            return false;
                        }
                    }
            );
        });

        function transactionTypes(jr_id) {

            var dataSource = <?PHP echo $dataa ?>;
            var transTye;
            $.each(dataSource, function(inx, val){
                if(parseInt(val.id) ===  parseInt(jr_id)){
                    transTye =  val.trans_type;
                }
            });
            return transTye;
        }

        function padToiex(number) {
            if (number <= 99999999) {
                number = ("0000000" + number).slice(-9);
            }
            return number;
        }

        $('#vendor_info').on('click.dismiss.bs.modal', '[data-dismiss="modal"]', function (properties) {

            if ($(this).is('[data-dismiss="modal"]')) {
                return $('#vendor_info').each(function () {
                    $(this).remove();
                })
            }
        })


    </script>
</div>
