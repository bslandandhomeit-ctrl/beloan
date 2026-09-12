var AddLoan = function(data){
    this.balloon = 0;
    this.productData = data;
    this.init();
};
$.extend(AddLoan.prototype,{
    init: function(){
        var self = this;
        self.bindEvent();
    },
    bindEvent: function(){
        var self = this;
        $("#penalty_rate_type").on('change',function(e){
            e.preventDefault();
            var value = $(this).val();
            if(value == 3){
                $("#penal_rate2").removeClass('hidden');
            }else{
                $("#penal_rate2").addClass('hidden');
            }
        });
        $("#custom_flag").on("click", function (e) {

            var chck = $(this).prop('checked');
            if(chck){
                $("#monthly_payment").prop("readonly",false);
            }else{
                $("#monthly_payment").prop("readonly",true);
            }

        })
        $("#submitted_on").on('change',function(e){
            e.preventDefault();
            var value = $(this).val();
            $("#contract_date").val(value);
            $("#disburse_date").val(value);
            $("#start_date").val(value);

            //var value_date = new Date(value);
            //var first_paydate = new Date(value_date.getFullYear(), value_date.getMonth()+1, value_date.getDate());
            //var nextm = first_paydate.getMonth()+1;
            //$("#start_date").val(first_paydate.getFullYear()+'-'+nextm+'-'+first_paydate.getDate());
            //$("#start_date").val(first_paydate);
        });
        $("#repayment_type").on('change',function(e){
            e.preventDefault();
            var value = $(this).val();
            if(value == 3 || value == 4 || value == 5 || value == 9){
                $("#balloon_month").prop("readonly",true);
                $("#balloon_input").removeClass('hidden');
                $("#monthly_payment").val(0);
            }else{
                if(value == 7){
                    $("#monthly_amount_cl").removeClass('hidden');
                }
                $('#balloon').val('');
                $("#balloon_month").val('');
                $("#custom").html('');
                $("#balloon_input").addClass('hidden');
            }

        });

        $('.customize input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        }).on('ifToggled',function(e){
            e.preventDefault();
            var chck = $(this).prop('checked');
            if(chck){
                $("#balloon_month").prop("readonly",false);
                $("#monthly_payment").prop("readonly",false);
                for(var i=1; i<=$("#balloon").val(); i++){
                    $("#balloon"+i).prop("readonly",false);
                }
            }else{
                $("#balloon_month").prop("readonly",true);
                $("#monthly_payment").prop("readonly",true);
                for(var i=1; i<= $("#balloon").val(); i++){
                    $("#balloon"+i).prop("readonly",true);
                }
            }
        });

        $('.holiday input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        }).on('ifToggled',function(e){
            e.preventDefault();
            var chck = $(this).prop('checked');
            if(chck){
                $("#holiday_flag").val(1);
            }else{
                $("#holiday_flag").val(0);
            }
        });

        $("#balloon").on('keyup',function(e){
            e.preventDefault();
            var balloon_new = $(this).val();
            var period = $("#loan_duration").val() / balloon_new;
            var str= [];
            for(var i = 0; i < balloon_new; i++){
                str[i] = parseInt(period * (i+1));
            }
            var month_array = str.join(',');
            $("#balloon_month").val(month_array);
            var chck = $("#custom_flag").prop('checked');
            if(!chck){
                $("#balloon_month").prop("readonly",true);
            }
            if(balloon_new != '' && balloon_new !='undefined'){
                var balloon_amount = $("#loan_amount").val()/ balloon_new;
//                if(balloon_new > self.balloon){
//                    $("#custom").html('');
//                    for(var i = 1; i<= balloon_new ; i++){
//                        var str =  '<div class="form-group">'+
//                            '<label class="control-label col-sm-3">Balloon Amount No.'+i+'($)</label>' +
//                            ' <div class="col-md-8">' +
//                            '<input type="text" id="balloon'+i+'"'+(chck?"":"readonly")+
//                            ' class="form-control balloon_input " name="balloon_input[]"' +
//                            '<option value='+balloon_amount.toFixed(2)+'>'+
//                            '</div>' +
//                            ' </div>';
//                        $("#custom").append(str);
//                    }
//                }else if(balloon_new< self.balloon){
//                    for(var i = self.balloon; i > balloon_new ; i-- ){
//                       $("#custom").children('.form-group').eq(i-1).remove();
//                    }
//                    if(chck){
//                        for(var i = 1; i<= balloon_new ; i++){
//                            $("#balloon"+i).val(balloon_amount.toFixed(2));
//                            $("#balloon"+i).prop("readonly",false);
//                        }
//                    }else{
//                        for(var i = 1; i<= balloon_new ; i++){
//                            $("#balloon"+i).val(balloon_amount.toFixed(2));
//                            $("#balloon"+i).prop("readonly",true);
//                        }
//                    }
//                }

                $("#custom").html('');
                for(var i = 1; i<= balloon_new ; i++){
                    var str =  '<div class="row">'+
                        '<div class="col-sm-5"><label class="control-label">Balloon Amount No.'+i+'($)</label>' +
                        '</div><div class="col-sm-7">' +
                        '<input type="text" id="balloon'+i+'"'+(chck?"":"readonly")+
                        ' class="form-control balloon_input " name="balloon_input[]"' +
                        '<option value='+balloon_amount.toFixed(2)+'>'+
                        '</div>' +
                        ' </div>';
                    $("#custom").append(str);
                }

                self.balloon = balloon_new;
                var balloon_input_array = $(".balloon_input").map(function(){
                    return $(this).val();
                }).get().join();

                $("#balloon_amount_array").val(balloon_input_array);
            }
        });

        $("#product-id").select2();
        $("#product-id").on('change',function(e){
            var id = $(this).val();
            for(var i = 0; i<self.productData.length; i++){
                if(self.productData[i].id == id){
                    $("#product_name").val(self.productData[i].product_name);
                    $("#product_name").val(self.productData[i].product_name);
                    $("#loan_type").val(self.productData[i].product_types.products_type_name);
                    $("#sell_price").val((parseFloat(self.productData[i].product_price)).toFixed(2));
                    break;
                }
            }
        });
        $("#branch_name").select2();
        $("#co_name").select2();
        $("#down_payment").on('change',function(e){
            var down_pay = $(this).val();
            $("#loan_amount").val(($("#sell_price").val() - down_pay).toFixed(2));
        });

        $("#custom").on('change','.balloon_input',function(e){
            var ind = $(this).index('.balloon_input');
            var amount = $("#loan_amount").val();
            var monthly_pay = $("#monthly_payment").val();
            var rate = $("#interest_rate").val();
            var tenure = $("#loan_duration").val();
            var balloon_num = $("#balloon").val();
            var total_regular =((monthly_pay - ((rate/100.0) * amount)) * (tenure - balloon_num));
            if(amount >0) {
                var num =  parseInt($("#custom").children('.form-group').length) - (ind+1);
                $("#custom .balloon_input").each(function(index){
                    if(index > ind){
                        if(total_regular > 0){
                            var input_amount = (amount - total_regular) / num;
                        }else{
                            var input_amount = (amount) / num;
                        }
                        $("#custom").children('.form-group').eq(index).find('.balloon_input').val(input_amount);
                    }else{
                        var input_amount = $("#custom").children('.form-group').eq(index).find('.balloon_input').val();
                        if(input_amount != 'undefined'){
                            amount -= input_amount;
                        }
                    }
                });
                var balloon_input_array = $(".balloon_input").map(function(){
                    return $(this).val();
                }).get().join();
                $("#balloon_amount_array").val(balloon_input_array);
            }
        });

        $("#monthly_payment").on('change',function(e){
            var ind = $(this).index('.balloon_input');
            var amount = $("#loan_amount").val();
            var monthly_pay = $("#monthly_payment").val();
            var rate = $("#interest_rate").val();
            var tenure = $("#loan_duration").val();
            var balloon_num = $("#balloon").val();
            var repayment_type = $("#repayment_type").val();
            var total_regular = 0.0;

            if(repayment_type == 4 || repayment_type == 9) { //Flat Semi-Balloon ( fixed Monthly Payment )
                var start_date = new Date($("#start_date").val());
                var end_date = new Date(start_date.getFullYear()+3, start_date.getMonth(), start_date.getDate());
                var total_days = dateDiff(start_date, end_date);
                var total_interest = amount*total_days*(rate / 100.0)*12/360;
                console.log(total_interest);
                var total_monthly = monthly_pay*tenure;
                total_regular = (total_monthly - total_interest);
                //var const_bal = (amount - total_monthly_prin)/balloon_num;
                //total_regular = ((monthly_pay - ((rate / 100.0) * amount)) * (tenure - balloon_num));
                //total_regular = amount - total_interest;
                console.log(total_regular);
            }else{
                total_regular = monthly_pay * (tenure - balloon_num);
            }
            if(amount >0) {
                var num =  parseInt($("#custom").children('.form-group').length) - (ind+1);
                $("#custom .balloon_input").each(function(index){
                    if(index > ind){
                        var input_amount = 0.0;
                        if(total_regular > 0){
                            if(repayment_type == 4){
                                input_amount = ((amount - total_regular) / num);
                            }else {
                                input_amount = (amount - total_regular) / num;
                            }
                        }else{
                            input_amount = (amount) / num;
                        }
                        $("#custom").children('.form-group').eq(index).find('.balloon_input').val(input_amount);
                    }else{
                        var input_amount = $("#custom").children('.form-group').eq(index).find('.balloon_input').val();
                        if(input_amount != 'undefined'){
                            amount -= input_amount;
                        }
                    }
                });
                var balloon_input_array = $(".balloon_input").map(function(){
                    return $(this).val();
                }).get().join();
                $("#balloon_amount_array").val(balloon_input_array);
            }
        });

        function dateDiff(start_date, end_date){
            var late_day = Math.floor((end_date.getTime() - start_date.getTime())/(24*60*60*1000));
            return late_day;
        }

        $("#view_pay_schedule").on('click',function(e) {
        	var is_disburse = $('#is_disburse').val();
            var loan_id = $('#loan_id').val();
            var repay_type = $("#repayment_type").val(); //if(repay_type==8) return false;
            var chck = $("#custom_flag").prop('checked');
            var holiday_chck = $("#holiday_flag").prop('checked');
            var round = $('#round').val();
            var change_digit = parseInt($('#change_two_digit').find('span').text());
			var currency_val = $('#currency_symbol').val();
            // console.log(change_digit);
            var balloon_input = $(".balloon_input").map(function(){
                return $(this).val();
            }).get().join();

            //chuch
            var principal_input = '';
            var principal_input = $(".principal_input").map(function(){
                return $(this).val();
            }).get().join();
            //console.log($("#monthly_amount").val());
            $("#balloon_amount_array").val(balloon_input);
            var down_payment_value = $('#down_payment_value').val();
            var down_payment_duration = $('#down_payment_duration').val();
            var down_payment = $('#down_payment').val();
            var installment_duration = $('#installment_duration').val();
            var annual_interest = $('#annual_interest').val();
            var start_payment_date = $('#start_payment_date').val();
            var data = {
                currency:currency_val,
                l_client_name:$("#client-name").val(),
                l_days_of_month:$("#days_of_month").val(),
                l_start_date: $("#start_date").val(),
                l_amount: $("#loan_amount").val(),
                l_tenure: $("#loan_duration").val(),
                l_rate: $("#interest_rate").val(),
                l_repayment_type: repay_type,
                l_balloon_num: $("#balloon").val(),
                l_balloon_month: $("#balloon_month").val(),
                l_monthly_pay: $("#monthly_payment").val(),
                l_bal_amount_array: balloon_input,
                custom_flag: $('#custom_flag').val(),
                // holiday_flag: $('#holiday_flag').val(),
                holiday_flag: holiday_chck ? 1 : 0,
                is_disburse: is_disburse,
                principal_input: principal_input,
                disburse_on: $('#disburse_date').val(),
                intraday_rate_: $('#intraday_rate').val(),
                round: round,
                change_digit: change_digit,
                frequency: $('#frequency').val(),
                loan_id: loan_id,
                admin_fee: $('#admin_fee').val(),
                admin_fee_opt: $('#admin_fee_opt option:selected').val(),
                maintain_fee: $('#maintain_fee').val(),
                maintain_fee_opt: $('#maintain_fee_opt option:selected').val(),
                other_fee: $('#other_fee').val(),
                monthly_amount : $("#monthly_amount").val(),
                down_payment_value: down_payment_value,
                down_payment:down_payment,
                installment_duration:installment_duration,
                annual_interest:annual_interest,
                start_payment_date:start_payment_date,
                down_payment_duration:down_payment_duration
            };
            //console.log(data)
            $.ajax({
                url: '/api/loan/repaymentinfo',
                type:'GET',
                data:data,
                BeforeSend:function(){
                    $("#schedule-table").html("");
                },
                success:function(data, status) {
                    if(status==='success'){
                        $("#schedule-table").html(data);
                        loaded =0;
                    }

//                    //chuch extra disburse
//                    if(is_disburse==2){
//                    	loan_amount = $("#loan_amount").val();
//                    	var data1 = {
//                    		loan_id: $('#loan_id').val(),
//                        };
//                    	$.ajax({
//                            url: '/loans/ajax_disburse',
//                            type:'GET',
//                            data:data1,
//                            success:function(res){
//
//                            	res = JSON.parse(res);
//                            	for(i=0; i<res.total; i++){
//                            		res_i = res[i];
//                            		j = i+1;
//                            		row_j = $('.row_'+j);
//
//                            		//row_j.find('.col_2 .default-val').hide();
//                            		row_j.find('.col_3 .default-val').hide();
//                            		row_j.find('.col_4 .default-val').hide();
//                            		row_j.find('.col_5 .default-val').hide();
//
//                            		intraday = row_j.find('.col_2 .default-val').text();
//                            		row_j.find('.col_3 .can-edit').text(res_i.principal);
//                            		monthly_pay = parseFloat(intraday) + parseFloat(res_i.principal);
//                            		row_j.find('.col_4 .can-edit').text(monthly_pay);
//
//                            		loan_amount = parseFloat(loan_amount) - parseFloat(res_i.principal);
//                            		row_j.find('.col_5 .can-edit').text(parseFloat(loan_amount).toFixed(2));
//                            	}
//                            }
//                        });
//                    }
                },
                error:function(var1, var2, var3){
                    //console.log(var1.responseText);
                }
            });

        });


        $("#add_loan_repayment").on('click',function(e){
            var repay_type = $("#repayment_type").val();
            var chck = $("#custom_flag").prop('checked');
            var holiday_chck = $("#holiday_flag").prop('checked');
            var round = $('#round').val();

            var balloon_input = $(".balloon_input").map(function(){
                return $(this).val();
            }).get().join();
            $("#balloon_amount_array").val(balloon_input);
            var data = {
                currency:currency_val,
                l_client_name:$("#client-name").val(),
                l_days_of_month:$("#days_of_month").val(),
                l_start_date: $("#start_date").val(),
                l_amount: $("#loan_amount").val(),
                l_tenure: $("#loan_duration").val(),
                l_rate: $("#interest_rate").val(),
                l_repayment_type: repay_type,
                l_balloon_num: $("#balloon").val(),
                l_balloon_month: $("#balloon_month").val(),
                l_monthly_pay: $("#monthly_payment").val(),
                l_bal_amount_array: balloon_input,
                custom_flag: chck ? 1: 0,
                holiday_flag: holiday_chck?1:0,
                round:round
            };
            //console.log(data);
            $.ajax({
                url: '/api/loan/repaymentinfo',
                type:'GET',
                data:data,
                BeforeSend:function(){
                    $("#schedule-table").html("");
                },
                success:function(data){
                    $("#schedule-table").html(data);
                },
                error:function(var1, var2, var3){
                    console.log(var1.responseText);
                }
            });

        });

    }
});
