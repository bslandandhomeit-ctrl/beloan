$(document).ready(function(){
    $("#rescheduleLoanForm").validate({
        rules:{
            contract_id:{
                required:true
            },
            product_id:{
                required: true,
                validSelectValue:true
            },
            loan_type:{
                required: true,
                validSelectValue:true
            },
            submitted_on:{
                required: true,
                isBeforeToday:true
            },
            company_branch_id:{
                required: true,
                validSelectValue:true
            },
            down_payment:{
                required: true,
                number:true
            },
            loan_amount:{
                required:true,
                number:true
            },
            loan_duration:{
                required:true,
                number:true,
                validSelectValue:true
            },
            interest_rate:{
                required:true
            },
            penalty_rate_type:{
                required:true,
                validSelectValue:true
            },
            penalty_period1:{
                required:true,
                validSelectValue:true
            },
            penalty_rate1:{
                required:true
            },
            payoff_period1:{
                required:true
            },
            payoff_period2:{
                required:true,
                validSelectValue:true
            },
            pay_off_rate1:{
                required:true

            },
            pay_off_rate2:{
                required:true
            },
            days_of_month:{
                required:true,
                validSelectValue:true
            },
            start_date:{
                required:true,
                // isBeforeToday:true
            },
            repayment_type:{
                required:true,
                validSelectValue:true
            },
            balloon:{
                //isBalloon:true
            },
            balloon_month:{
                isBalloon:true,
                required:true,
                balloonInput: function(){
                    return $("#balloon").val()
                }
            }
        },
        messages:{
            contract_id:{
                required:"Please enter a valid contract id"
            },
            product_id:{
                required: "Product ID is required.",
                validSelectValue:"Please select product id"
            },
            loan_type:{
                required: "Loan Type is required.",
                validSelectValue:"Please select Loan Type"
            },
            submitted_on:{
                required: "Please select submitted date.",
                isBeforeToday: "Submitted date must be before today"
            },
            company_branch_id:{
                required: "Price is required",
                validSelectValue: "Please select branch name"
            },
            down_payment:{
                required: "Please input down payment in number"
            },
            loan_amount:{
                required: "Please input Down Payment to automatically calculate Principal Amount",
                number: "Principal Amount must be number"
            },
            loan_duration:{
                required:"Please input Loan Tenure",
                number:"Please input valid number in month",
                validSelectValue:"Tenure must be more than 0 months"
            },
            interest_rate:{
                required:"Please input Interest Rate"
            },
            penalty_rate_type:{
                required:"Penalty Rate Type is required",
                validSelectValue:"Please select valid Penalty Rate Type"
            },
            penalty_period1:{
                required:"Penalty Period1 is required",
                validSelectValue:"Please select valid Penalty Period1"
            },
            penalty_rate1:{
                required:"Penalty Rate1 is required",
                validSelectValue:"Please select valid Penalty Rate1"
            },
            payoff_period1:{
                required:"Pay-Off Period1 is required"
            },
            payoff_period2:{
                required:"Pay-Off Period2 is required"
            },
            pay_off_rate1:{
                required:"Pay-Off Rate1 is required"
            },
            pay_off_rate2:{
                required:"Pay-Off Rate2 is required"
            },
            days_of_month:{
                required:"Please Select days of month",
                validSelectValue:"Select select valid days of month"
            },
            // start_date:{
            //     required: "Please select start date.",
            //     isBeforeToday: "Start date must be before today"
            // },
            repayment_type:{
                required:"Repayment Type is required",
                validSelectValue:"Please select one Repayment Type"
            },
            balloon:{
                //isBalloon:"Repayment Type must be Balloon"
            },
            balloon_month:{
                isBalloon:"Repayment Type must be Balloon",
                required:"Balloon months must be inputted in xx,yy"
            },
            project_id:{
                required:"Project is required",
                validSelectValue:"Please select one Project"
            }
        },
        submitHandler: function(form){
            form.submit();
            $('form').find(":submit").attr("disabled", true);
            $('form').find("label#saving").show();
        }
    });

});

$.validator.addMethod('validSelectValue',function(value){
    if(value == 0 || value =="")
        return false;
    return true;
},'Please choose one');
$.validator.addMethod('isBalloon',function(){
    var repay_type = $("#repayment_type").val();
    if(repay_type == 3 || repay_type ==4 || repay_type == 5 || repay_type = 9)
        return true;
    return false;
},'Please choose one');
$.validator.addMethod('balloonInput',function(value,element,param){
    var input = value.split(",");
    var ch = false;
    $.each(input,function(i,v){
        if(!v.match(/^-?[0-9]+$/) || v == null || v == "" || v == 0 || !$.isNumeric(v)){
            ch = true;
        }
    });
    if(!ch && $.isArray(input) && input.length == param){
        return true;
    }
    return false;
},'Invalid');
$.validator.addMethod('isBeforeToday',function(value){
    var objToday = new Date();
    var sub = value.split('-');
    var value_date = new Date(sub[0],sub[1]-1,sub[2]);
    if(value_date <= objToday){
      return true;
    }
    return false;
},'Please choose one');
$.validator.addMethod('isBeforeSummittedDate',function(value){
    var submitted_on = $("#submitted_on").val().split('-');
    var objSubmittedDate = new Date(submitted_on[0],submitted_on[1]-1,submitted_on[2]);
    var sub = value.split('-');
    var value_date = new Date(sub[0],sub[1]-1,sub[2]);
    if(value_date >= objSubmittedDate){
        return true;
    }
    return false;
},'Please choose one');
$.validator.addMethod("checkContractID", function(value, element)
{
    $.ajax(
        {
            type: "POST",
            url: "/loans/contract_id_check",
            dataType: "json",
            data: {
                username: value
            },
            success: function(data)
            {
                if(data.status == true){
                    return true;
                }
                return false;
            },
            error: function(xhr, textStatus, errorThrown)
            {
                return false;
            }
        });

}, 'Username already exits');
