<div id="deposit" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 80%;">
        <form class="form-group "  id="form_deposit" method="post" onsubmit="return false" enctype="multipart/form-data">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Make Deposit </h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-4 form-group">
                        <label> Deposit To </label>
                        <select class="to" name="to" style="margin-top:10px; height:27px; width:100%">
                            <option value="" >-- Select an account --</option>
                            @if($depositTo)
                                @foreach($depositTo as $coa_item)
                                    <option value="{{$coa_item->id}}">{{$coa_item->name}} - {{$coa_item->account_code}} ( {{$coa_item->currency_i->code}} )</option>
                                @endforeach
                            @endif;
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label> Date : </label>
                        <input class="form-control" type="text" name="mdate"/>
                    </div>

                    <div class="col-lg-3 form-group">
                        <label class=""> Memo </label>
                        <input class="form-control" type="text" name="memo"/>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="col-lg-3 form-group">
                            <label class=""> Invoice Number </label>
                            <input class="form-control" type="text" name="recipsNum"/>
                        </div>

                        <div class="col-lg-3 form-group">
                            <label class=""> Invoice Photo </label>
                            <input class="" type="file" name="file"/>
                        </div>

                        <table class="table table-responsive table-bordered tbl_deposit">
                            <thead>
                            <tr>
                                <th width="130px !important">RECEIVED FROM</th>
                                <th width="400px !important">FROM ACCOUNT</th>
                                <th width="80px">MEMO</th>
                                <th width="80px">REF NO</th>
                                <th width="80px">AMOUNT</th>
                                <!--<th width="40px">ACTION</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="coa">
                                <td>
                                    <select class="customers" name="customer"  style="margin-top:10px; height:27px; width:100%">
                                        <option value="">-- Select Customer --</option>
                                        @if($customer)
                                            @foreach($customer as $customer_item)
                                                <option value="{{$customer_item->id}}">{{$customer_item->fname.' '.$customer_item->lname}}</option>
                                            @endforeach
                                        @endif;
                                    </select>
                                </td>
                                <td>
                                    <select class="coa_id" name="coa_id" style="margin-top:10px; height:27px; width:100%">
                                        <option value="" >-- Select an account --</option>
                                        @if($coa)
                                            @foreach($coa as $coa_item)
                                                <option value="{{$coa_item->id}}">{{$coa_item->name}} - {{$coa_item->account_code}} ( {{$coa_item->currency_i->code}} )</option>
                                            @endforeach
                                        @endif;
                                    </select></td>
                                <td><input class="form-control" type="text" name="Arr_memo"/></td>
                                <td><input class="form-control" type="text" name="check_num" /></td>
                                <td><input class="form-control amount" type="text" name="amount" /></td>
                                <!--<td><i class="btn btn-sm glyphicon glyphicon-plus"></i></td>-->
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" value="Submit" id="submit" class="btn btn-info"/>
            </div>
        </div>
        </form>
    </div>
    <script>

         $(document).ready(function() {

            $("input[name=mdate]").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
            $('select').select2();
            var modals = $('#deposit');
            var formId = $("#form_deposit");
            var data = null;
            modals.on({
                'show.bs.modal':function(properties) {

                },'click.dismiss.bs.modal' : function(properties) {
                    if ($(this).is('[data-dismiss="modal"]')) {
                        return modals.remove();
                    }
                }
            },'[data-dismiss="modal"], .glyphicon-plus, .glyphicon-minus, #form_deposit, .customers');
             $('#form_deposit').validate({
                 rules: {
                     mdate:{
                         required:true
                     },memo:{
                         required:true
                     //},recipsNum:{
                     //    required:true
                     },customer:{
                         required:true
                     },coa_id:{
                         required:true
                     },Arr_memo:{
                         required:true
                     //},check_num:{
                     //    required:true
                     },amount:{
                         required:true
                     },to:{
                         required:true
                     }
                 }, submitHandler: function () {

                     var form_data = new FormData();
                     form_data.append('photo',  $('input[type=file]')[0].files[0]);
                     var data = $('#form_deposit').serializeArray();

                     $.each(data, function(key, input) {
                         form_data.append(input.name, input.value);
                     });
                     call_and_delete_Loading('#loading', 'Data are Saving..');
                     if(confirm('Are you sure ?')){
                         $.ajax({
                             url  : "{{route('make_deposit')}}",
                             data : form_data,
                             type : 'POST',
                             headers : {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                             contentType : false,
                             cache : false,
                             processData : false,
                             success : function (data, status) {

                                 if(status === 'success') {

                                     if(data.jd === true) {

                                         call_and_delete_Loading('#loading', 'Successfully!!!', status);
                                         $('.modal').each(function(){
                                             $(this).remove();
                                         });
                                     }if(!$.isEmptyObject(data.error)) {

                                         call_and_delete_Loading('#loading', 'Sorry!!!<br/> We support only below extension <br/> '+data.error+' ', 'danger');

                                     }
                                 }
                             }
                         });
                     }
                 }
             });
         });

    </script>
</div>



