$(document).ready(function () {
    $("#roleForm").validate({
        rules:{
            role:{
                required: true,
                noSpace:true
            },
            role_name:{
                required: true
            }
        },
        messages:{
            role:{
                required: "Please enter a role"
            },
            role_name:{
                required: "Please enter a role name"
            }
        }
    });
    $("#roleFormAjax").validate({
        rules:{
            role:{
                required: true,
                noSpace:true
            },
            role_name:{
                required: true
            }
        },
        messages:{
            role:{
                required: "Please enter a role"
            },
            role_name:{
                required: "Please enter a role name"
            }
        },
        submitHandler:function(){
            var data = {
                _token: $('#_token').val(),
                role: $('input[name=role]').val(),
                role_name: $('#role_name').val(),
                desc: $('#desc').val()
            }
            $.ajax({
                url: '/user/ajax_role',
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                type: 'POST',
                dataType: "json",
                data: data,

                cache:false,
                success: function(data)
                {
                    if(data.status == true){
                        $('#mRole').modal("toggle");
                        $('#role').append($("<option></option>").attr("value", data.id).text($('#role_name').val()));
                        $('#role' + " option[value="+ data.id + "]").prop('selected', true);

                    }else{
                        alert("Error in saving.");
                    }
                },
                error: function(xhr, textStatus, errorThrown)
                {
                    console.log(xhr.responseText);
                }
            });

        }
    });
    $("#userForm").validate({
        rules:{
            name:{
                required: true,
                minlength: 3
            },
            photo:{
                filesize:1048576
            },
            username:{
                required:true,
                minlength: 5,
                noSpace:true,
                remote:{
                    url: '/user/check',
                    type: "POST",
                    data:
                    {
                        username: function()
                        {
                            return $('#userForm :input[name="username"]').val();
                        },
                        _token:function(){
                            return $('#userForm :input[name="_token"]').val();
                        }
                    }
                }
            },
            password:{
                required:true,
                minlength: 5,
                passValid: true,
                noSpace:true
            },
            con_password:{
                equalTo: "#password"
            },
            email:{
                email: true
            },
            role_id:{
                validSelectValue: true
            }
        },
        messages:{
            name:{
                required: "Please enter a name"
            },
            username:{
                required:"Please enter a username",
                remote:"Username already exists"
            },
            password:{
                required:"Please enter a password"
            },
            con_password:{
                equalTo: "Password not match"
            },
            role_id:{
                validSelectValue: "Role is required"
            }
        }
    });

    $("#userEditForm").validate({
        rules:{
            name:{
                required: true,
                minlength: 3
            },
            photo:{
                filesize:1048576
            },
            email:{
                email: true
            },
            role_id:{
                validSelectValue: true
            }
        },
        messages:{
            name:{
                required: "Please enter a name"
            },
            role_id:{
                validSelectValue: "Role is required"
            }
        }
    });
    $("#clientForm").validate({
        rules:{
            photo:{
                filesize:1048576
            },
            signature:{
                filesize:1048576
            },
            client_type:{
                validSelectValue: true
            },
            client_name:{
                required: true,
                minlength: 3
            },
            phone1:{
                required: true
            },
            city:{
                validSelectValue: true
            },
            address:{
                required: true
            }
        },
        messages:{
            client_type:{
                validSelectValue: "Please select one client type"
            },
            client_name:{
                required: "Please enter a name"
            },
            phone1:{
                required: "Please enter phone number"
            },
            city:{
                validSelectValue: "Please select valid city/province"
            },
            address:{
                required: "Please enter address"
            }
        }
    });
    $('#bankForm').validate({
        rules:{
            bank_name:{
                required: true
            },
            account_name:{
                required: true
            },
            account_number:{
                required: true
            }
        },
        messages:{
            bank_name:{
                required: "Please enter bank name"
            },
            account_name:{
                required: "Please enter account name"
            },
            account_number:{
                required: "Please enter account number"
            }
        }
    });
    $("#comBranch").validate({
        rules:{
            branch_name:{
                required: true
                //validSelectValue: true
            },
            branch_code:{
                required: true
            },
            open_on:{
                required: true
            },
            location:{
                required: true
            }
        },
        messages: {
            branch_name:{
                required: "Please enter branch name"
                //validSelectValue: "Please enter a branch name"
            },
            branch_code:{
                required: "Please enter branch code"
            },
            open_on:{
                required: ""
            },
            location:{
                required: "Please select a location"
            }
        }
    });
    $("#frmCloseLoan").validate({
        rules:{
            close_date:{
                required: true
            },
            amount:{
                required: true
            }
        },
        messages:{
            close_date:{
                required: "Please select a date"
            },
            amount:{
                required: "Please enter amount"
            }
        }
    });
    $("#frmWriteOffPay").validate({
        rules:{
            repay_date:{
                required: true
            },
            repay_amount:{
                required: true,
            }
        },
        messages:{
            repay_date:{
                required: "Please select a date"
            },
            repay_amount:{
                required: "Please enter amount",
            }
        }
    });
    $("#frmDisburse").validate({
        rules:{
            disburse_on:{
                required: true
            },
            amount:{
                required: true
            },
            sel_tellers:{
                NotZero:0,
                required: true
            }
        },
        messages:{
            disburse_on:{
                required: "Please select a date"
            },
            amount:{
                required: "Please enter amount"
            },
            sel_tellers:{
                required: "Please select teller"
            }
        }
    });

    jQuery.validator.addMethod("NotZero", function(value, element, param) {
        return this.optional(element) || value != param;
    }, "Please choose a teller");

    $("#frmRescheduleApprove").validate({
        rules:{
            approval_date:{
                required: true
            },
            amount:{
                required: true
            }
        },
        messages:{
            approval_date:{
                required: "Please select a date"
            },
            amount:{
                required: "Please enter amount"
            }
        }
    });

    $("#frmRepayment").validate({
        rules:{
            repayment_date:{
                required: true
            },
            payment_month:{
                required: true,
                number: true,
                IsValidNumber:true
            },
            paid_principal:{
                required: true,
                number: true
            },
            paid_interest:{
                required: true,
                number: true
            },
            penalty_amount:{
                required: true,
                number: true
            },
            repayment_owed:{
                required: true,
                positiveValue: true
            },
            condition:{
                validSelectValue: true
            }
        },
        messages:{
            repayment_date:{
                required: "Please select a date"
            },
            payment_month:{
                required: "Please input the number of payment month",
                number: "Please input a number",
                IsValidNumber:"Please input valid payment month ( within loan tenure )"
            },
            paid_principal:{
                required: "Please enter principal amount",
                number: "Please input a number"
            },
            paid_interest:{
                required: "Please enter interest amount",
                number: "Please input a number"
            },
            penalty_amount:{
                required: "Please enter penalty amount",
                number: "Please input a number"
            },
            repayment_owed:{
                required: "Invalid value",
                positiveValue: "The value is equal or bigger than 0"
            },
            condition:{
                validSelectValue: "Please select a condition"
            }
        }
    });
    $("#frmRepaymentOwed").validate({
        rules:{
            repayment_date:{
                required: true
            },
            paid_principal:{
                required: true,
                number: true
            },
            paid_interest:{
                required: true,
                number: true
            },
            penalty_amount:{
                required: true,
                number: true
            },
            repayment_owed:{
                required: true,
                positiveValue: true
            },
            condition:{
                validSelectValue: true
            }
        },
        messages:{
            repayment_date:{
                required: "Please select a date"
            },
            paid_principal:{
                required: "Please enter principal amount",
                number: "Please input number only"
            },
            paid_interest:{
                required: "Please enter interest amount",
                number: "Please input number only"
            },
            penalty_amount:{
                required: "Please enter transaction amount",
                number: "Please input number only"
            },
            repayment_owed:{
                required: "Invalid value",
                positiveValue: "The value is equal or bigger than 0"
            },
            condition:{
                validSelectValue: "Please select a condition"
            }
        }
    });
    $("#frmGuarantor").validate({
        rules:{
            relationship:{
                required: true
            },
            name:{
                required: true,
                minlength: 3
            },
            phone1:{
                required: true
            },
            address:{
                required: true
            },
            "collateral_type[]":{
                required: true
            },
            "collateral_no[]":{
                required: true
            },
            "collateral_value[]":{
                required: true
            },
            "collateral_registration[]":{
                required: true
            },
            "collateral_address[]":{
                required: true
            }
        },
        messages:{
            relationship:{
                required: "Please select a relationship type"
            },
            name:{
                required: "Please enter a name"
            },
            phone1:{
                required: "Please enter phone number"
            },
            address:{
                required: "Please enter address"
            },
            "collateral_type[]":{
                required: "Please select collateral type"
            },
            "collateral_no[]":{
                required: "Please enter collateral number"
            },
            "collateral_value[]":{
                required: "Please enter collateral value"
            },
            "collateral_registration[]":{
                required: "Please select collateral registration"
            },
            "collateral_address[]":{
                required: "Please enter collateral address"
            }
        }
    });

    $("#frmGuarantorCollateral").validate({
        rules:{
            "collateral_type[]":{
                required: true
            },
            "collateral_no[]":{
                required: true
            },
            "collateral_value[]":{
                required: true
            },
            "collateral_registration[]":{
                required: true
            },
            "collateral_address[]":{
                required: true
            }
        },
        messages:{
            "collateral_type[]":{
                required: "Please select one option"
            },
            "collateral_no[]":{
                required: "Please enter collateral number"
            },
            "collateral_value[]":{
                required: "Please enter collateral value"
            },
            "collateral_registration[]":{
                required: "Please select one option"
            },
            "collateral_address[]":{
                required: "Please enter address"
            }
        }
    });

    $("#addChargeForm").validate({
        rules:{
            charge_type:{
                validSelectValue:true
            },
            charge_amount:{
                required:true,
                number:true
            },
            charge_date:{
                required:true
            },
            waived_amount:{
                number:true
            },
            sel_tellers:{
                required:true,
                validSelectValue:true
            }
        },
        messages:{
            charge_amount:{
                required:"Please enter amount"
            },
            charge_date:{
                required:""
            },
            sel_tellers: {
                required:'Please select a till account'
            }
        }
    });

    $("#addCollateralForm").validate({
        rules:{
            collateral_type:{
                validSelectValue:true,
                required:true
            },
            collateral_no:{
                required:true
            },
            collateral_value:{
                required:true
            },
            collateral_registration:{
                required:true,
                validSelectValue:true
            },
            collateral_address:{
                required:true
            }
        },
        messages:{
            collateral_no:{
                required:"Please enter collateral number"
            },
            collateral_value:{
                required:"Please enter collateral value"
            },
            collateral_type:{
                required:""
            },
            collateral_registration:{
                required:"Please select registration type"
            },
            collateral_address:{
                required:"Please enter collateral address"
            }
        }
    });
    $("#addApprovalLoanForm").validate({
        rules:{
            approval_date:{
                required:true
            }
        },
        messages:{
            approval_date:{
                required:""
            }
        }
    });
    $("#addDocumentForm").validate({
          rules:{
              doc_type:{
                  required:true,
                  validSelectValue:true
              },
              photo:{
                required: true
              }
          },
          messages:{
              doc_type:{
                  required:"Please select document type",
                  validSelectValue:"Please select document type"
              },
              photo:{
                required: " (required)"
              }
          }
    });
    $("#editLoanDoc").validate({
          rules:{
              doc_type:{
                  required:true,
                  validSelectValue:true
              }
          },
          messages:{
              doc_type:{
                  required:"Please select document type",
                  validSelectValue:"Please select document type"
              }
          }
    });
    $("#addCostForm").validate({
        rules:{
            cost_type:{
                required:true,
                validSelectValue:true
            },
            cost_amount:{
                required:true,
                number:true
            },
            cost_date:{
                required:true
            }
        },
        messages:{
            cost_type:{
                required:"Please select cost type",
                validSelectValue:"Please select cost type"
            },
            cost_amount:{
                required:"Please enter amount"
            },
            cost_date:{
                required:""
            }
        }
    });
    $("#frmPw").validate({
        rules:{
            admin_pass:{
                required:true,
                    noSpace: true,
                minlength: 5
            }
        },
        messages:{
            admin_pass:{
                required: "Please input password"
            }
        }
    });
    $('#frmReject').validate({
        rules:{
            reject_on:{
                required: true
            }
        },
        messages:{
            reject_on:{
                required: "Please select a date."
            }
        }
    });
    $("#frmIpRange").validate({
        rules:{
            ip_addr:{
                required:true,
                ipv4: true
            }
        },
        messages:{
            ip_addr:{
                required:"Please enter an IP address",
                ipv4: "Please enter an IPV4-formated address"
            }
        }
    });

    $("#assetForm").validate({
        rules:{
            branch:{
                required:true
            },
            category:{
                required:true
            },
            location:{
                required:true
            },
            classification:{
                required:true
            },
            currency:{
                required:true
            },
            invoice_num:{
                required:true
            },
            tag_num:{
                required:true
            },
            main_gl_id:{
                required:true
            },
            depre_gl_id:{
                required:true
            },
            exp_gl_id:{
                required:true
            },
            depre_meth:{
                required:true
            },
            asset_name:{
                required:true
            },
            purchased_date:{
                required:true
            },
            original_cost:{
                required:true
            },
            depre_year:{
                required:true
            },
            rate:{
                required:true
            },
            supplier:{
                required:true
            }

        }
    });

    $("#assetDisposeForm").validate({
        rules:{
            amount:{
                required:true
            },
            a_to:{
                required:true
            },
            approved_by:{
                required:true
            }
        }
    });

    $("#assetWriteOffForm").validate({
        rules:{
            net_book:{
                required:true
            },
            approved_by:{
                required:true
            }
        }
    });
});

$.validator.prototype.checkForm = function(){
    this.prepareForm();
    for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
        if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
            for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
                this.check( this.findByName( elements[i].name )[cnt] );
            }
        } else {
            this.check( elements[i] );
        }
    }
    return this.valid();
};
$.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes)
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param)
},"File size must be not greater than 1MB");

$.validator.addMethod('validSelectValue',function(value){
    if(value == 0 || value =="")
        return false;
    return true;
},'Please choose one');
$.validator.addMethod('passValid',function(value,element){
    var pattern = /^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
   return this.optional(element) || pattern.test(value);
},'At least 1 number, 1 lowercase, 1 uppercase letter');
$.validator.addMethod("noSpace", function(value, element) {
    return value.indexOf(" ") < 0;
}, "No space please");
$.validator.addMethod("IsValidNumber", function(value) {
    var tenure = parseInt($("#loan_tenure").val());
    if(value > tenure || value <= 0){
        return false;
    }
    return true;
}, "Please input valid Number within Loan Tenure");

$.validator.addMethod('positiveValue',function(value){
    if(value < 0)
        return false;
    return true;
},'The value is equal or bigger than 0');

$.validator.addMethod("checkUser", function(value, element)
{
    $.ajax(
        {
            type: "POST",
            url: "/user/check",
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
$.validator.addMethod('ipv4',function(value,element){
    var pattern = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return this.optional(element) || pattern.test(value);
},'Please enter valid ip address.');

//$.ajaxSetup({
//    headers: {
//        'X-CSRF-TOKEN': $('input[name="_token"]').val()
//    }
//});
