<div id="enterBill" class="modal fade" role="dialog">
    <style>
        label.control-label{
            margin-top:12px;
        }
    </style>
    <div class="modal-dialog" role="document" style="min-width: 1100px;">
        <form class="form-inline" id="form_enterBill" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Enter Bill</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-lg-6">

                            <div class="col-lg-12 form-group">
                                <label class="col-lg-4 control-label" style="margin-top:-1px;"> Vendor </label>
                                <select class="col-lg-7" name="vendor">
                                    <option value=""> Select a Vendor </option>
                                    @foreach($vendor as $vendors)
                                        <option value="{{$vendors->id}}">{{$vendors->company_name}} ( {{$vendors->fname.' '.$vendors->lname}} )</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="control-label"> Amount Due : </label>
                            </div>
                            <div class="col-lg-7">
                                <input class="form-control amount_due" type="text" name="amount_due" />
                            </div>
                            <div class="col-lg-4">
                                    <label class="control-label"> Term : </label>
                                </div>
                            <div class="col-lg-7">
                                    <input type="text" class="form-control" name="term"  />
                                </div>
                            <div class="col-lg-4">
                                <label class="control-label"> Bill Due: </label>
                            </div>
                            <div class="col-lg-7">
                                <input class="form-control" type="text" name="due_date" readonly />
                            </div>

                            <div class="col-lg-4">
                                <label class="control-label"> Invoice No: </label>
                            </div>

                            <div class="col-lg-7">
                                <input class="form-control" type="text" name="refer" />
                            </div>

                            <div class="col-lg-4">
                                <label class="control-label"> Date : </label>
                            </div>
                            <div class="col-lg-7">
                                <input class="form-control" type="text" name="mdate" value="{{date("Y-m-d",time())}}" />
                            </div>
                        </div>

                        <div class="col-lg-6">


                            <div class="col-lg-3">
                                <label class="control-label">  Address : </label>
                            </div>
                            <div class="col-lg-9">
                                <textarea class="form-control" name="address"></textarea>
                            </div>
                            <div class="col-lg-3">
                                <label class="control-label"> Description : </label>
                            </div>
                            <div class="col-lg-9">
                                <textarea class="form-control" name="description"></textarea>
                            </div>

                            <div class="col-lg-4">
                                <label class="control-label"> Invoice photo: </label>
                            </div>
                            <div class="col-lg-7">
                                <input class="file" type="file" name="file" />
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4>Expenses</h4>
                            <table class="table table-responsive table-bordered tbl_enterBill">
                                <thead>
                                    <tr>
                                        <th width="400px !important">Account</th>
                                        <th width="100px !important">Amount</th>
                                        <th width="100px !important">Memo</th>
                                        <th width="50px !important">Action</th>
                                    </tr>
                                    </thead>
                                <tbody>
                                    <tr class="coa">
                                        <td>
                                            <select class="coa_id" name="coa_id[]" style="margin-top:10px; height:27px; width:400px">
                                                <option value=""> Select an account </option>
                                                @if($coa)
                                                    @foreach($coa as $coas)
                                                        <option value="{{$coas->id}}">{{$coas->name}} - {{$coas->account_code}} ( {{$coas->currency_i->code}} )</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td><input class="form-control nAmount" name="amount[]" type="text"/></td>
                                        <td><input class="form-control" name="memo[]"  type="text"/></td>
                                        <td><i class="btn btn-sm glyphicon glyphicon-plus"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <input type="submit" value="Submit" id="query" class="btn btn-info"/>
                </div>
            </div>
        </form>
        <script>
            $('.tbl_enterBill').on('click', 'td i.glyphicon-plus,td i.glyphicon-minus', function(e){
                e.preventDefault();

                if ($(this).is('.glyphicon-plus')) {

                    var trcopy, clonetr;
                    trcopy = $(this).closest('.coa');
                    trcopy.find('select').select2("destroy");
                    clonetr = trcopy.clone();
                    trcopy.find('select').select2();
                    clonetr.find('select').select2();
                    var nexttr = clonetr.find('td i.glyphicon-plus');
                    if (nexttr.hasClass('glyphicon-plus')) {
                        nexttr.removeClass('glyphicon-plus');
                        nexttr.addClass('glyphicon-minus')
                    }
                    clonetr.find(':text').val('');
                    trcopy.after(clonetr);
                }if($(this).is('td i.glyphicon-minus')){
                    $(this).parent().parent().remove();
                }
            });

            $(document).ready(function(){

                var modales = $('#enterBill');
                $('select').select2();
                $('input[name=mdate]').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    setDate: new Date()
                });

                modales.on({
                    keyup : function() {

                        if($(this).is('input[name=term]')){

                            var mdateVals = $('input[name=mdate]');
                            var bdate = $('input[name=due_date]');

                            if(!parseInt($(this).val()) || $(this).val() === null) {

                                bdate.val('')

                            }else{

                                var mdate = new Date(mdateVals.val());
                                var sumDate = new Date(mdate.setDate(mdate.getDate()+parseInt($(this).val())));
                                var totalDate = sumDate.toISOString().slice(0,10);;
                                bdate.val(totalDate);
                                call_and_delete_Loading('#loading');
                            }

                        }
                        if($(this).is('.amount_due')){
                             calculate($(this));
                        }if($(this).is('.nAmount')){
                            calculate($(this));
                        }
                    },
                    change : function() {

                        call_and_delete_Loading('#loading');
                        if($(this).is('input[name=mdate]')) {

                            let billDueVals =  $('input[name=due_date]').val();

                            if(billDueVals && billDueVals !== null){

                                let selectVals  = $('input[name=mdate]').val();
                                let convertSelectvals = new Date(selectVals);

                                let selectedDate = new Date(convertSelectvals).toISOString().slice(0,10);
                                let billDueDate = new Date(billDueVals).toISOString().slice(0,10);
                                let sumDate = new Date(convertSelectvals.setDate(convertSelectvals.getDate()+parseInt($('input[name=term]').val())));
                                console.log(sumDate.toISOString().slice(0,10));
                                $('input[name=due_date]').val(sumDate.toISOString().slice(0,10));
                                if(selectedDate > billDueDate){

                                    return call_and_delete_Loading('#loading', 'Your due date can not smaller than date');
                                }
                            }
                        }
                    },
                    'click.dismiss.bs.modal':function(){
                        if($(this).is('[data-dismiss="modal"]')) {
                            return [
                                delete_allModel('#enterBill'),
                                call_and_delete_Loading('#loading')
                            ];
                        }
                    }
                },'input[name=term], input[name=mdate],[data-dismiss="modal"],.amount_due,.nAmount');

                $('#form_enterBill').validate({

                    rules: {
                        vendor: {
                            required: true
                        },refer:{
                            required:true
                        },amount_due:{
                            required:true,
                            number: true
                        },nAmount:{
                            required:true,
                            number: true
                        },term:{
                            required:true,
                            number:true
                        },
                        mdate: {
                            required:true
                        },'amount[]':{
                            required:true,
                            number:true
                        }
                    }, submitHandler: function () {

                        var form_data = new FormData();
                        form_data.append('file',  $('input[type=file]')[0].files[0]);
                        form_data.append('_token', $('meta[name=_token]').attr('content'));
                        var other_data = $('#form_enterBill').serializeArray();

                        $.each(other_data, function(key, input){
                            form_data.append(input.name, input.value);
                        });

                        if(confirm('Are you sure?')) {

                            return AjaxCallBack('{{route('enterBill')}}', 'post', 'json', form_data, 'write_check', function (data, status) {

                                if(status === 'success') {

                                    if( data.ins === true) {

                                        call_and_delete_Loading('#loading', 'Successfully', status);
                                        return delete_allModel(modales.selector);
                                    }if(!$.isEmptyObject(data.error)){
                                        call_and_delete_Loading('#loading', 'Sorry!!!<br/> We support only below extension <br/> '+data.error+' ', 'danger');
                                    } else {

                                        call_and_delete_Loading('#loading', 'try again');
                                    }
                                }
                            });
                        }
                    }
                });
            });


            function calculate(enterAmount) {

                var amount_due = $('.amount_due');
                var nAmount = $('.nAmount');
                call_and_delete_Loading('#loading');
                $('input[type=submit]').attr('disabled',false)
                var totalNamount = 0;

                if(!$.isNumeric(amount_due.val()))

                    call_and_delete_Loading('#loading', 'The amount due can not except any characters', 'danger');
                if(!$.isNumeric(enterAmount.val()))

                    call_and_delete_Loading('#loading', 'This value can not except any characters', 'danger');

                if(!parseFloat(amount_due.val()))

                    call_and_delete_Loading('#loading', 'Please input Due amount value', 'danger');
                if(!parseFloat(nAmount.val()))

                    call_and_delete_Loading('#loading', 'Please input expense amount value', 'danger');

                if(parseFloat(nAmount.val())) {

                    nAmount.each(function(e) {
                        totalNamount += (parseFloat($(this).val()))?parseFloat($(this).val()):0;
                    });

                    nAmount.css({'border':'1px solid #e2e2e4'});
                    nAmount.closest('td').removeClass('has-error has-feedback');

                    if(parseFloat(amount_due.val()) != parseFloat(totalNamount)) {

                        $('input[type=submit]').attr('disabled',true);
                        nAmount.css({'border':'2px inset red'});
                        nAmount.closest('td').addClass('has-error has-feedback');
                        call_and_delete_Loading('#loading', 'Expense amount Can not <br/> greater than due amount ','danger');
                    }
                }
            }

        </script>
        <style>
            .form-control{
                width: 100%!important;
            }
        </style>
    </div>
</div>
