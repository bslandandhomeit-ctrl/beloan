<div id="trans_funds" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal trans_funds" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Transfer Funds </h4>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="my_lable">Currency</label>
                            <select class="form-control currency" name="currency">
                                <option value=""> Select Currency </option>
                                @if($currency)
                                    @foreach($currency as $curr)
                                        <option value="{{$curr->id}}">{{$curr->name}}</option>
                                    @endforeach;
                                @endif;
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="my_lable"> Transfer funds from </label>

                            <select name="from" class="from" style="width:100%"></select>
                        </div>

                        <div class="form-group">
                            <label class="my_lable"> Transfer funds to </label>
                            <select name="to" class="to" style="width:100%"></select>
                        </div>

                        <div class="form-group">
                            <label class="my_lable"> Ref.No: </label>
                            <input type="text" name="reference" class="form-control" style="width:100%" />
                        </div>

                        <div class="form-group">
                            <label class="my_lable">Invoice Number</label>
                            <input type="text" name="receiptNum" class="form-control refNum" style="width:100%" />
                        </div>

                        <div class="form-group">
                            <label class="my_lable">Invoice Photo </label>
                            <input type="file" name="file" class="file" style="width:100%" />
                        </div>

                        <div class=" form-group">
                            <label class="my_lable"> Transaction Amount </label>
                            <input type="text" class="form-control amount" name="amount"/>
                        </div>

                        <div class="form-group">
                            <label class="my_lable"> Date </label>
                            <input type="text" class="form-control" name="mdate"/>
                        </div>
                        <div class="form-group">
                            <label class="my_lable"> Memo </label>
                            <textarea name="memo" class="form-control"></textarea>
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
        $('.tbl_writecheck').on('click', 'td i.glyphicon-plus,td i.glyphicon-minus', function (e) {
            e.preventDefault();
        });
        $(document).ready(function () {

            $('input[name=mdate]').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
            var models = $('#trans_funds');
            var formId = $("form.trans_funds");

            models.on({
                'click.dismiss.bs.modal': function () {

                    if ($(this).is('[data-dismiss="modal"]')) {
                        return models.remove();
                    }
                }, change: function () {

                    var coa = <?PHP echo $coa; ?>;
                    var opt = '';

                    if ($(this).is('.currency')) {

                        $('select.to').children('option').remove();
                        $('.select2-chosen').remove();
                        $('select.from ').children('option').remove();

                        var currency_id = parseInt($(this).val());
                        var coafilterByCurrency = coa.filter(function (el) {
                            return parseInt(el.currency) == currency_id
                        });
                            opt += '<option value="">--</option>';
                        $.each(coafilterByCurrency, function(inx, vals){
                            opt += '<option value="'+vals.id+'">'+vals.name+' ( '+vals.account_code+' ) '+'</option>'
                        });
                        $(opt).appendTo('.from');
                        $('select.from').select2()
                    }if($(this).is('.from')) {

                        $('select.to').children('option').remove();
                        $('.to.select2-chosen').remove();
                        var currency_id = parseInt($('select.currency option:selected').val());
                        var from_coa_id = parseInt($(this).val());

                        if(!parseInt(from_coa_id) || isNaN(from_coa_id)) {
                            $('select.to').children('option').remove();
                            $('select.to').select2('destroy')
                            $('.to .select2-chosen').remove();
                            return;
                        }
                        var toData = coa.filter(function (el) {
                            return parseInt(el.id ) !== from_coa_id && parseInt(el.currency) === currency_id
                        });

                            opt += '<option value="">Select an account</option>';
                        $.each(toData, function(inx, vals){
                            opt += '<option value="'+vals.id+'">'+vals.name+' ( '+vals.account_code+' ) '+'</option>'
                        });
                        $(opt).appendTo('.to');

                        $('select.to').select2();
                    }

                }
            }, '[data-dismiss="modal"], .currency, .from, #trans_funds,.trans_funds');

            formId.validate({
                rules: {
                    memo: {required:true},
                    mdate:{required:true},
                    amount:{required:true},
                    from:{required:true},
                    to:{required:true}
                },
                submitHandler: function () {

                    var form_data = new FormData();
                    form_data.append('photo',  $('input[type=file]')[0].files[0]);
                    var data = $('.trans_funds').serializeArray();

                    $.each(data, function(key, input) {
                        form_data.append(input.name, input.value);
                    });
                    call_and_delete_Loading('#loading', 'Data are Saving..');
                    if(confirm('Are you sure ?')) {
                        $.ajax({
                            url  : "{{route('trans_funds')}}",
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
                    }else{
                        call_and_delete_Loading('#loading');
                    }
                }
            });
        });

        var removeAllOptionsSelect = function(element_class_or_id){

            var element = $(element_class_or_id+" option");
            $.each(element,function(i,v){
                value = v.value;
                $(element_class_or_id+" option[value="+value+"]").remove();
            })
        };

        function currency_sybal(currency_id){
            var symbal =0;
            var currency = <?PHP echo $currency ?>;
            $.each(currency, function(inx, vals){
                if(parseInt(vals.id) === parseInt(currency_id)) {
                    symbal =  vals.symbol;
                }
            });
            return symbal;
        }
    </script>
    <style>
        .my_lable {
            min-width: 180px;
        }

        select {
            min-width: 204px;
        }
        .error{
            color:red;
        }
    </style>
</div>